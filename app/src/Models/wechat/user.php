<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
/**
 * Class Model_Wechat_User
 * @author Joseph
 */

class Model_Wechat_User extends Model{
    const TABLE = "user_wx";
    static function table($name = ""){
        return self::_table($name ? $name : self::TABLE);
    }
    static function get_auth_info_by_openid($openid){
        $table = self::table();
        $row = self::_db()->row("select * from $table where openid = ?",$openid);
        if($row){
            $row['info'] = empty($row['info']) ? null : json_decode($row['info'],1);
        }
        return $row;
    }
    static function get_avatar($url){
        try{
            $content = file_get_contents($url);
            if(!empty($content)){
                $res = Model_Tools_Qiniu::upload_content($content,"wechat/avatar/".md5($url));
                $url = Model_Tools_Qiniu::get_res_url($res);
            }
        }catch(Exception $e){
            self::_error(array(__METHOD__,"get_avatar error",$e->getMessage()));
        }
        return $url;
    }
    static function save($info){
        $openid = $info['openid'];
        $res = self::get_auth_info_by_openid($openid);
        $row = array();
        $row['info']   = json_encode($info);
        $row['openid'] = $openid;
        $row['unionid'] = empty($info['unionid']) ? "":$info['unionid'];
        $row['nickname'] = $info['nickname'];
        $row['add_time'] = Utils::date_time_now();
        if($res){
            $infoChanged = false;
            $row['avatar'] = $res['avatar'];
            if($info['nickname'] !== $res['nickname']){
                $infoChanged = true;
            }
            if($info['headimgurl'] !== $res['info']['headimgurl']){
                $infoChanged = true;
                $row['avatar'] = self::get_avatar($info['headimgurl']);
            }
            if($infoChanged){
                self::_db()->update(self::table(),$row,array("id"=>$res['id']));
            }
        }else{
            $row['avatar'] = self::get_avatar($info['headimgurl']);
            $row['id'] = self::_db()->insert(self::table(),$row);
        }
        return $row;
    }
    static function get_uid_by_openid($openid){
        $table = self::table("user_wx_rel");
        $row = self::_db()->row("select user_id from $table where openid = ?",$openid);
        return empty($row) ? null:$row['user_id'];
    }
    static function bind_user($openid,$user_id){
        $table = self::table("user_wx_rel");
        $row = self::_db()->row("select * from $table where user_id = ? and openid = ?",$user_id,$openid);
        if(empty($row)){
            self::_db()->insert($table,array(
                "user_id"=>$user_id,
                "openid"=>$openid,
                "bind_time"=>Utils::date_time_now(),
            ));
        }
    }
}