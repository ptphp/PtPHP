<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Member;
use Controller\Admin\AbstractAdmin as AbstractAdmin;

use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use Symfony\Component\Config\Definition\Exception\Exception;

class User extends AbstractAdmin{
    static function table(){
        return self::_table("user");
    }
    static function pk(){
        return "user_id";
    }
    /**
     * 删除
     * @param $id
     * @param $ids
     * @return array
     */
    function action_remove($id,$ids){
        $pk = self::pk();
        $table = self::table();
        if($id){
            $row = self::_db()->row("select * from $table where $pk = ?",$id);
            if(!$row) _throw("记录不存在");
            self::_db()->delete($table,array(
                $pk=>$id
            ));
        }
        if($ids){
            $ids = handle_mysql_in_ids($ids);
            $table_staff_user = self::_table("staff_user");
            self::_db()->run_sql("delete from $table where $pk in ($ids)");
            self::_db()->run_sql("delete from $table_staff_user where $pk in ($ids)");
        }
        return array("msg"=>"删除成功");
    }

    function action_row($id){
        $pk = self::pk();
        $table = self::table();
        $left_join = self::get_join_sql();
        $table_alias = "u";
        $row = self::_db()->row("select u.*,{$table_alias}.{$pk} as `key`,s.mobile as staff_mobile,i.*,f.* from $table as u $left_join where u.{$pk} = ?",$id);
        $row['password'] = "";
        return array(
            "row"=>$row
        );
    }

    static function get_join_sql(){
        $table_staff = self::_table("org_staff");
        $table_staff_user = self::_table("staff_user");
        $talbe_user_info = self::_table("user_info");
        $talbe_user_fund = self::_table("user_fund");

        $left_join  = "left join $table_staff_user as su on su.user_id = u.user_id ";
        $left_join  .= "left join $table_staff as s on s.stf_id = su.stf_id ";
        $left_join  .= "left join $talbe_user_info as i on i.user_id = u.user_id ";
        $left_join  .= "left join $talbe_user_fund as f on f.user_id = u.user_id ";
        return $left_join;
    }

    function action_list($limit,$page,$sorter,$search,$filters){
        $pk = self::pk();
        $table_alias = "u";
        $table = self::table();
        $select_field = "{$table_alias}.*,{$table_alias}.{$pk} as `key`,s.mobile as staff_mobile,i.*,f.*";

        $limit = empty($limit) ? 10 : intval($limit);
        $page  = empty($page)  ? 1  : intval($page);
        $condition = array();
        if(!empty($search)){
            self::_debug($search);
            $condition = json_decode($search,1);
        }
        self::_debug($condition);
        if(!empty($filters)){
            $filters = json_decode($filters,1);
            self::_debug($filters);
            $condition = array_merge($condition,$filters);
        }
        self::_debug($condition);

        $where = 'where 1 = 1';
        $args = array();
        if(!empty($condition['mobile'])){
            $where .= " and ({$table_alias}.mobile like ? or s.mobile like ?) ";
            $args[] = "%".$condition['mobile']."%";
            $args[] = "%".$condition['mobile']."%";
        }
        if(!empty($condition['user_id'])){
            $where .= " and {$table_alias}.user_id = ?";
            $args[] = $condition['user_id'];
        }
        $left_join = self::get_join_sql();
        $count_res = self::_db()->select_row("SELECT COUNT({$table_alias}.{$pk}) AS total FROM $table as {$table_alias} $left_join $where",$args);
        $records = $count_res['total'];
        $total_pages = $records > 0  ? ceil($records/$limit) : 1;
        $skip = ($page - 1) * $limit;

        $sorter_order_tpl = array("ascend"=>"asc","descend"=>"desc");
        if(!empty($sorter)) $sorter = json_decode($sorter,1);
        $sorter_field_tpl = array("ord");
        $sort_field = !empty($sorter['field']) && in_array($sorter['field'],$sorter_field_tpl) ? $sorter['field']  : $table_alias.".".$pk;
        $sort_order = !empty($sorter['order']) && !empty($sorter_order_tpl[$sorter['order']]) ? $sorter_order_tpl[$sorter['order']]:"desc";
        $order  = "ORDER BY $sort_field $sort_order";
        $sql = "SELECT $select_field FROM $table as {$table_alias} $left_join $where $order LIMIT $skip,$limit ";
        self::_debug(array($sql,$args));
        $rows = self::_db()->rows($sql,$args);
        foreach($rows as &$row){
            $row['password'] = "";
        }
        $res = array(
            "total"=>$records,
            "page"=>$page,
            "total_pages"=>$total_pages,
            "limit"=>$limit,
            "skip"=>$skip,
            "rows"=>$rows,
        );
        if(!self::is_production())
            $res['debug'] = array(
                "sql"=>$sql,
                "args"=>$args,
                "params"=>array($limit,$page,$sorter,$search,$filters,$condition),
            );
        return $res;
    }
    static function getSaveRowValue($row,$key,$default=null){
        return empty($row[$key]) ? $default : $row[$key];
    }
    static function getSaveRow($row,$is_add = true){
        $row = json_decode($row,1);
        $_row = array(
            "mobile"=>self::getSaveRowValue($row,"mobile",""),
            "password"=>self::getSaveRowValue($row,"password",""),
        );

        return array(
            "row"=>$_row
        );
    }
    function action_update($id,$row){
        $res = self::getSaveRow($row,false);
        $table = self::table();
        $pk = self::pk();

        if(!Utils::is_mobile($res['row']['mobile'])){
            _throw("手机号不合法");
        }

        $user_mobile = self::_db()->row("select mobile from $table where user_id <> ?  and mobile = ?",$id,$res['row']['mobile']);
        if($user_mobile){
            _throw("手机号已存在");
        }
        if(!empty($res['row']['password'])){
            $password = $res['row']['password'];
            $row = self::_db()->row("select salt from $table where user_id = ?",$id);
            $salt = $row['salt'];
            $res['row']['password'] = \Model_Admin_Auth::gen_password($password,$salt);
        }else{
            unset($res['row']['password']);
        }

        self::_db()->update($table,$res['row'],array(
            $pk=>$id
        ));
        $res = $this->action_row($id);
        $res[$pk] = $id;
        return $res;
    }

    function action_add($row){
        $res = self::getSaveRow($row);
        $table = self::table();
        $pk = self::pk();
        $res['row']['add_time'] = Utils::date_time_now();

        if(!Utils::is_mobile($res['row']['mobile'])){
            _throw("手机号不合法");
        }

        $user_mobile = self::_db()->row("select mobile from $table where mobile = ?",$res['row']['mobile']);
        if($user_mobile){
            _throw("手机号已存在");
        }
        if(!empty($res['row']['password'])){
            $password = $res['row']['password'];
            $salt = \Model_Admin_Auth::gen_salt();
            $res['row']['password'] = \Model_Admin_Auth::gen_password($password,$salt);
            $res['row']['salt'] = $salt;
        }else{
            _throw("密码不能为空");
        }
        $id = self::_db()->insert($table,$res['row']);
        $res = $this->action_row($id);
        $res[$pk] = $id;
        return $res;
    }
}
