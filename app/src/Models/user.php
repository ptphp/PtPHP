<?php
use PtPHP\Model as Model;
use PtPHP\Utils as Utils;
use PtPHP\Crypt as Crypt;
/**
 * User
 * Class Model_User
 * @author Joseph
 */
class Model_User extends Model{
    const TABLE                  = "et_user";
    const CACHE_KEY              = "q_u2";
    const CACHE_MOBILE_KEY       = "q_u_2";
    const CACHE_USERNAME_KEY     = "q_u_2";
    const CACHE_PASSWORD_KEY     = "p_e_k";
    const CACHE_MIN_PASSPORT_UID = "ldt_min_passport_uid";

    /**
     * 获取绿能宝USER_ID
     * @param $user_id
     * @return mixed
     */
    static function get_passport_uid($user_id){
        $min_passport_uid = self::_redis()->get(self::CACHE_MIN_PASSPORT_UID);
        if(!$min_passport_uid){
            $res = self::_db()->select_row("select value from dian_hash where `hash` = ? and `key` = ?","ldt_setting",self::CACHE_MIN_PASSPORT_UID);
            if($res) $min_passport_uid = $res['value'];
            else $passport_uid = null;
        }
        $min_passport_uid = intval($min_passport_uid);
        if($user_id <= $min_passport_uid) $passport_uid = $user_id;
        else{
            $user_info = self::get_user_info($user_id);
            if(empty($user_info['passport_uid'])){
                $passport_uid = null;
            }else{
                $passport_uid = $user_info['passport_uid'];
            }
        }
        return $passport_uid;
    }

    /**
     * 获取 USER INFO
     * 先从缓存取,不存在就从DB里取
     *
     * @param $user_id
     * @return array|bool|mixed
     */
    static function get_user_info($user_id){
        $table = self::_table("user");
        $user_info = self::get_user_info_cache($user_id);
        if(!$user_info){
            $user_info = self::_db()->select_row("select * from $table where user_id = ?",$user_id);
            //self::_debug("from table");
            //self::_debug($user_info);
            if($user_info){
                self::set_user_info_cache($user_info['user_id'],$user_info);
            }
        }

        return $user_info;
    }

    /**
     * 缓存中取 USER INFO
     * @param $user_id
     * @return bool|mixed
     */
    static function get_user_info_cache($user_id){
        $user_info =  self::_redis()->get(self::CACHE_KEY.$user_id);
        return empty($user_info)?false:json_decode($user_info,1);
    }

    /**
     * 缓存 USER INFO
     * @param $user_id
     * @param $user_info
     * @return bool|string
     */
    static function set_user_info_cache($user_id,$user_info = array()){
        if(empty($user_info)){
            $table = self::_table("user");
            $user_info = self::_db()->select_row("select * from $table where user_id = ?",$user_id);
        }
        self::_redis()->set(self::CACHE_MOBILE_KEY.$user_info['mobile'],$user_id);
        self::_redis()->set(self::CACHE_USERNAME_KEY.$user_info['username'],$user_id);
        return self::_redis()->set(self::CACHE_KEY.$user_id,json_encode($user_info));
    }

    static function gen_password($password){
        return md5(PASSWORD_SALT.md5($password.PASSWORD_SALT));
    }
    static function get_password_encrypt_key(){
        $key = self::_redis()->get(self::CACHE_PASSWORD_KEY);
        if(!$key){
            $res = self::_db()->select_row("select `value` from dian_hash where `hash` = ? and `key` = ?","ldt_setting",self::CACHE_PASSWORD_KEY);
            if($res) $key = $res['value'];
            else $key = md5(__FILE__);
        }
        return $key;
    }
    static function password_encrypt($password){
        $key = self::get_password_encrypt_key();//密钥
        return Crypt::encrypt($password,$key);
    }
    static function password_decrypt($password){
        $key = self::get_password_encrypt_key();//密钥
        return Crypt::decrypt($password,$key);
    }

    /**
     * 创建新用户
     * @param $username
     * @param $password
     * @param $mobile
     * @param $locked
     * @param $passport_uid
     * @return array
     */
    static function create_new_user($username,$password,$mobile,$locked = 'N',$passport_uid = null){
        $user_info = array(
            "passport_uid"=>$passport_uid,
            "balance"=>0.00,
            "user_type"=>null,
            "username"=>$username,
            "mobile"=>$mobile,
            "locked"=>$locked,
            "ip"=>Utils::ip(true),
            "password"=>self::gen_password($password),
            "password_encrypt"=>self::password_encrypt($password),
            "addtime"=>time()
        );
        $table = self::_table("user");
        $user_id = self::_db()->insert($table,$user_info);
        $user_info['user_id'] = $user_id;
        self::set_user_info_cache($user_id,$user_info);
        return $user_info;
    }

    /**
     * 检查用户名或者手机号是不是存在
     * @param $username
     * @return bool
     */
    static function check_user_exsits($username){
        $table = self::_table("user");
        $is_mobile = Utils::is_mobile($username);
        $key = ($is_mobile ? self::CACHE_MOBILE_KEY : self::CACHE_USERNAME_KEY);
        $cache_user_id = self::_redis()->get($key.$username);
        //self::_debug("cache_user_id",$username,$cache_user_id);
        if(!$cache_user_id){
            $field = $is_mobile? "mobile":"username";
            $user_info = self::_db()->select_row("select id from $table where $field = ?",$username);
            //self::_debug("$user_info",$user_info);
            if($user_info){
                self::_redis()->set($key.$username,$user_info['id']);
                return $user_info['id'];
            }else
                return false;
        }else
            return $cache_user_id;

    }

    /**
     * todo
     * @param $mobile
     * @param $password
     * @throws Exception
     */
    static function reset_pwd($mobile,$password){
        if(!Utils::is_mobile($mobile)) _throw("手机号不能不合法!");
        if(strlen($password) < 6) _throw("密码不能少于6位");

        //if(!self::check_user_exsits($mobile)) _throw("手机号不存在");

        #passport check mobile exsits
//        try{
//            $mobile_check_res = Model_Passport::check_mobile($mobile);
//            if(empty($mobile_check_res['userid'])){
//                self::_redis()->set(self::CACHE_MOBILE_KEY.$mobile,$mobile_check_res['userid']);
//                throw new Exception("手机号不存在");
//            }
//        }catch(Exception $e){
//            throw new Exception($e->getMessage());
//        }

        #passport regist user
        $user_id = null;
        try{
            $passport_user = Model_Passport::reset_password($mobile);
            $passport_uid = $passport_user['userid'];
            $passport_password = $passport_user['password'];
            self::send_reset_pwd_sms($mobile,$password);
            $user_id = self::check_user_exsits($mobile);
            if(!$user_id){
                $user_info = self::create_new_user($mobile,$password,$mobile,"N",$passport_uid);
            }else{
                $table = self::_table("user");
                self::_db()->update($table,array(
                    "password"=>self::gen_password($passport_password),
                    "password_encrypt"=>self::password_encrypt($passport_password)
                ),array(
                    'user_id'=>$user_id
                ));
                self::set_user_info_cache($user_id);
            }

        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * todo
     * @param $mobile
     * @param $password
     */
    static function send_reset_pwd_sms($mobile,$password){

    }
    /**
     * 用户注册
     * @param $username
     * @param $mobile
     * @param $password
     * @return array
     * @throws Exception
     */
    static function reg($username,$mobile,$password = null){
        if($username == '') _throw("用户名不能为空!");
        if(!Utils::is_mobile($mobile)) _throw("手机号不能不合法!");
        if(strlen($password) < 6) _throw("密码不能少于6位");

        if(self::check_user_exsits($mobile)) _throw("手机号已存在");

        #passport check mobile exsits
        try{
            $mobile_check_res = Model_Passport::check_mobile($mobile);
            if(!empty($mobile_check_res['userid'])){
                self::_redis()->set(self::CACHE_MOBILE_KEY.$mobile,$mobile_check_res['userid']);
                _throw("手机号已存在,请直接登陆,如果不记得密码请重置密码");
            }
        }catch(AppException $e){
            if($e->getCode() != '10005'){
                _throw($e->getMessage());
            }
        }
        #passport regist user
        try{
            $passport_user = Model_Passport::regist($username,$password,$mobile);
            $passport_uid = $passport_user['userid'];
        }catch(AppException $e){
            _throw($e->getMessage());
        }

        $user_info = self::create_new_user($username,$password,$mobile,"N",$passport_uid);
        self::_debug("注册成功");
        return $user_info;
    }
    static function set_login_user_info($user_info){
        Model_Auth::set_login_session($user_info['user_id']);
    }
    static function check_login_user_info($user_info,$password){
        self::_debug($user_info);
        if(empty($user_info)) _throw("用户不存在");
        if($user_info['password'] != self::gen_password($password)) _throw("密码不正确");
        if($user_info['locked'] && $user_info['locked'] == 'Y') _throw("用户已被锁定");
        //self::set_login_user_info($user_info);
    }

    /**
     * 使用密码登陆
     * @param $username
     * @param $password
     * @return array|bool|mixed
     * @throws Exception
     */
    static function login_by_password($username,$password){
        self::_debug($username,$password);
        try{
            if($user_id = self::check_user_exsits($username)){ //绿电通验证
                self::_debug("user_id".$user_id);
                $user_info = self::get_user_info($user_id);
                self::check_login_user_info($user_info,$password);
            }else{//passport 验证
                $passport_user = Model_Passport::login($username,$password);
                if(Utils::is_mobile($passport_user['username'])){
                    $mobile = $passport_user['username'];
                }else{
                    $mobile = "";
                    try{
                        $passport_user = Model_Passport::get_user($passport_user['user_id']);
                        $mobile = $passport_user['mobile'];
                    }catch(Exception $e){
                        self::_warn("passport get user error ".$e->getMessage());
                    }
                }
                $user_info = self::create_new_user($passport_user['username'],$password,$mobile,$passport_user['locked'],$passport_user['userid']);
                self::check_login_user_info($user_info,$password);

            }
        }catch(AppException $e){
            _throw($e->getMessage());
        }
        self::_debug("登陆成功");
        return $user_info;
    }

    static function action_list($limit = 10){
        $table = self::_table("user");
        $rows = self::_db()->select_rows("select * from $table order by user_id  desc limit $limit");
        $count = self::_db()->select_row("select count(*) as total from $table order by user_id desc");
        return array(
            "rows"=>$rows,
            "total"=>$count['total'],
            "limit"=>$limit
        );
    }

}