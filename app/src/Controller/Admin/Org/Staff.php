<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 16/1/19
 * Time: 下午2:53
 */
namespace Controller\Admin\Org;
use Controller\Admin\AbstractAdmin as AbstractAdmin;
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;

class Staff extends AbstractAdmin{

    static function table(){
        return self::_table("org_staff");
    }
    /**
     * 删除
     * @param $id
     * @param $ids
     * @return array
     */
    function action_remove($id,$ids){
        $table = self::table();
        if($id){
            $row = self::_db()->row("select * from $table stf_id = ?",$id);
            if(!$row) _throw("记录不存在");
            self::_db()->delete($table,array(
                "id"=>$id
            ));
        }
        if($ids){
            $ids = handle_mysql_in_ids($ids);
            self::_db()->run_sql("delete from $table where stf_id in ($ids)");
        }
        return array("msg"=>"删除成功");
    }

    function action_row($id){
        $table = self::table();
        $row = self::get_detail($id);
        return array(
            "row"=>$row
        );
    }
    static function get_join_sql(){
        $table_department = self::_table("org_department");
        $table_role = self::_table("org_role");
        $table_position = self::_table("org_position");
        $table_staff_user = self::_table("staff_user");
        $table_user = self::_table("user");
        $left_join  = "left join $table_department as d on d.dep_id = s.dep_id ";
        $left_join .= "left join $table_position as p on p.pot_id = s.pot_id ";
        $left_join .= "left join $table_role as r on r.role_id = s.role_id ";
        $left_join .= "left join $table_staff_user as su on su.stf_id = s.stf_id ";
        $left_join .= "left join $table_user u on u.user_id = su.user_id ";
        return $left_join;
    }
    function action_list($limit,$page,$sorter,$search,$filters){
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
        $table = self::table();
        $select_field = "s.*,u.user_id,s.stf_id as `key`,d.dep_name,p.pot_name,r.role_name";

        $where = 'where 1 = 1';
        $args = array();
        if(!empty($condition['stf_name'])){
            $where .= " and s.stf_name like ?";
            $args[] = "%".$condition['stf_name']."%";
        }
        if(!empty($condition['mobile'])){
            $where .= " and s.mobile like ?";
            $args[] = $condition['mobile']."%";
        }
        if(!empty($condition['role_id'])){
            $where .= " and r.role_id  = ?";
            $args[] = $condition['role_id'];
        }
//        if(!empty($condition['status'])){
//            $where .= " and status in (?)";
//            $args[] = implode(",",$condition['status']);
//        }
        $table_role = self::_table("org_role");
        $count_res = self::_db()->select_row("SELECT COUNT(s.stf_id) AS total FROM $table as s left join $table_role as r on r.role_id = s.role_id $where",$args);
        $records = $count_res['total'];
        $total_pages = $records > 0  ? ceil($records/$limit) : 1;
        $skip = ($page - 1) * $limit;

        $sorter_order_tpl = array("ascend"=>"asc","descend"=>"desc");
        if(!empty($sorter)) $sorter = json_decode($sorter,1);
        $sorter_field_tpl = array("ord");
        $sort_field = !empty($sorter['field']) && in_array($sorter['field'],$sorter_field_tpl) ? $sorter['field']  : "s.stf_id";
        $sort_order = !empty($sorter['order']) && !empty($sorter_order_tpl[$sorter['order']]) ? $sorter_order_tpl[$sorter['order']]:"desc";
        $order  = "ORDER BY $sort_field $sort_order";
        $left_join = self::get_join_sql();
        $sql = "SELECT $select_field FROM $table as s $left_join $where $order LIMIT $skip,$limit ";
        //self::_debug($sql);
        $rows = self::_db()->rows($sql,$args);
        $departments = \Controller\Admin\Org\Department::getDepartmentRows();
        $table_role = self::_table("org_role");
        $roles = self::_db()->rows("select * from $table_role");
        $res = array(
            "total"=>$records,
            "page"=>$page,
            "total_pages"=>$total_pages,
            "limit"=>$limit,
            "skip"=>$skip,
            "rows"=>$rows,
            "departments"=>$departments,
            "roles"=>$roles,
        );
        if(!self::is_production())
            $res['debug'] = array(
                "sql"=>$sql,
                "args"=>$args,
                "params"=>array($limit,$page,$sorter,$search,$filters,$condition),
            );
        return $res;
    }
    static function getSaveRow($row,$is_add = true){
        $row = json_decode($row,1);
        if(empty($row['stf_name'])) _throw("姓名不能为空");
        if(!empty($row['mobile']) && !Utils::is_mobile($row['mobile'])) _throw("手机号不合法");

        $_row = array(
            "stf_name"=>$row['stf_name'],
            "role_id"=>empty($row['role_id'])?null:intval($row['role_id']),
            "dep_id"=>empty($row['dep_id'])?null:intval($row['dep_id']),
            "pot_id"=>empty($row['pot_id'])?null:intval($row['pot_id']),
            "mobile"=>empty($row['mobile'])?null:$row['mobile'],
            "password"=>empty($row['password'])?null:$row['password'],
            "py"=>\CUtf8_PY::encode($row['stf_name']),
        );
        return array(
            "row"=>$_row
        );
    }
    function action_update($id,$row){
        $table = self::table();
        $res = self::getSaveRow($row,false);
        $password = null;

        if(!empty($res['row']['mobile'])){
            $mobile = $res['row']['mobile'];
            $staff = self::_db()->row("select mobile from $table where stf_id <> ? and mobile = ?",$id,$mobile);
            if($staff) _throw("手机号已存在");
        }

        if(!empty($res['row']['password'])){
            $password = $res['row']['password'];
        }
        unset($res['row']['password']);
        self::_db()->update($table,$res['row'],array(
            "stf_id"=>$id
        ));

        if($password){
            $auth_user = \Model_Admin_Staff::get_auth_user_by_stf_id($id);
            if($auth_user){
                $salt = $auth_user['salt'];
            }else{
                $salt = \Model_Admin_Auth::gen_salt();
            }
            $password = \Model_Admin_Auth::gen_password($password,$salt);
            if($auth_user){
                $user_row = array(
                    "password"=>$password,
                );
                self::_db()->update(self::_table("user"),$user_row,array("user_id"=>$auth_user['user_id']));
            }else{
                $user_row = array(
                    "password"=>$password,
                    "salt"=>$salt,
                    "add_time"=>Utils::date_time_now(),
                );
                $user_id = self::_db()->insert(self::_table("user"),$user_row);
                self::_db()->insert(self::_table("staff_user"),array(
                    "stf_id"=>$id,
                    "user_id"=>$user_id,
                ));
            }
        }
        return array(
            "stf_id"=>$id,
            "row"=>self::get_detail($id)
        );
    }
    static function get_detail($id){
        $table = self::table();
        $left_join = self::get_join_sql();
        $row = self::_db()->row("select s.*,u.user_id,s.stf_id as `key`,p.pot_name,d.dep_name,r.role_name from $table as s $left_join where s.stf_id = ?",$id);
        return $row;
    }

    function action_add($row){
        $table = self::table();
        $res = self::getSaveRow($row);
        $res['row']['add_time'] = Utils::date_time_now();

        if(!empty($res['row']['mobile'])){
            $mobile = $res['row']['mobile'];
            $staff = self::_db()->row("select mobile from $table where mobile = ?",$mobile);
            if($staff) _throw("手机号已存在");
        }

        $password = null;
        if(!empty($res['row']['password'])){
            $password = $res['row']['password'];
            unset($res['row']['password']);
        }
        unset($res['row']['password']);
        $id = self::_db()->insert($table,$res['row']);
        if($password){
            $salt = \Model_Admin_Auth::gen_salt();
            $password = \Model_Admin_Auth::gen_password($password,$salt);
            $user_row = array(
                "password"=>$password,
                "salt"=>$salt,
                "add_time"=>Utils::date_time_now(),
            );
            $user_id = self::_db()->insert(self::_table("user"),$user_row);
            self::_db()->insert(self::_table("staff_user"),array(
                "stf_id"=>$id,
                "user_id"=>$user_id,
            ));
        }
        return array(
            "stf_id"=>$id,
            "row"=>self::get_detail($id)
        );
    }
}
