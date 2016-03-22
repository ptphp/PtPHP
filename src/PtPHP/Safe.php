<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;
use Exception;

class Safe{
    /*
	使用方式:
	1. 调用 create_encrypt_info() 生成公钥和私钥
	2. 将公钥告诉用户, set_encrypt_info() 保存公钥和私钥
	3. 收到用户的数据后, 调用 safe_decrypt() 解密数据, 返回时会删除公钥和私钥
	*/

    static function create_encrypt_info(){
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 1024,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        $private_key = '';
        openssl_pkey_export($res, $private_key);
        $details = openssl_pkey_get_details($res);
        $public_key = $details["key"];
        if(!$private_key || !$public_key){
            _throw("get_encrypt_keys failed");
        }
        return array(
            'public_key' => $public_key,
            'private_key' => $private_key,
        );
    }
    // $src: array, string
    static function decrypt($src,$private_key){
        if(is_string($src)){
            $out = '';
            $s = openssl_private_decrypt(base64_decode(trim($src)), $out, $private_key);
            if(!$s){
                return false;
            }
            $ret = $out;
        }
        if(is_array($src)){
            $ret = array();
            foreach($src as $k=>$v){
                $out = '';
                $s = openssl_private_decrypt(base64_decode(trim($v)), $out, $private_key);
                if(!$s){
                    return false;
                }
                $ret[$k] = $out;
            }
        }
        return $ret;
    }

}