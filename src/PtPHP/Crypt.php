<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

class Crypt{
    static function format_field($s){
        return strlen($s).":".$s;
    }

    static function _create_signature($secret,$to_sign){
        return hash_hmac("sha256", $to_sign, $secret);
    }

    static function create_signed_value($secret, $name, $value){
        $timestamp = time();

        $value = base64_encode($value);

        $t = array(
            "2|1:0",
            self::format_field($timestamp),
            self::format_field($name),
            self::format_field($value),
            ''
        );

        $to_sign = implode("|",$t);
        $signature = self::_create_signature($secret, $to_sign);
        return $to_sign . $signature;
    }
    static function _consume_field($s){
        $i = strpos($s,":");
        $j = strpos($s,"|");
        #\Console::log(" : position :{1}",$i);
        #\Console::log(" | position :{1}",$j);
        $len = substr($s,0,$i);
        $field_value = substr($s,$i+1,$j-$i-1);
        #\Console::log(" len :{1}",$len);
        #\Console::log(" field_value :{1}",$field_value);
        $rest = substr($s,$j+1);
        return array(
            'field_value'=>$field_value,
            'rest'=>$rest,
        );
    }
    static function decode_signed_value($secret, $name, $value, $max_age_days=31){
        $clock = time();
        $rest = substr($value,2);
        #\Console::log($rest);
        $res = self::_consume_field($rest);
        #\Console::log($res);
        $key_version = $res['field_value'];
        $rest = $res['rest'];

        $res = self::_consume_field($rest);
        #\Console::log($res);
        $timestamp = $res['field_value'];
        $rest = $res['rest'];


        $res = self::_consume_field($rest);
        #\Console::log($res);
        $name_field = $res['field_value'];
        $rest = $res['rest'];

        $res = self::_consume_field($rest);
        #\Console::log($res);
        $value_field = $res['field_value'];
        $rest = $res['rest'];
        $passed_sig = $rest;
        $signed_string = substr($value,0,-strlen($passed_sig));

        $expected_sig = self::_create_signature($secret, $signed_string);
        #\Console::log($expected_sig);
        #\Console::log($passed_sig);
        if($expected_sig != $passed_sig){
            return null;
        }
        if($name_field != $name){
            return null;
        }

        if(intval($timestamp) < (time() - $max_age_days * 86400)){
            return null;
        }

        return base64_decode($value_field);
    }

    public static function encrypt($input, $key) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = self::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    private static function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function decrypt($sStr, $sKey) {
        $decrypted= mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $sKey,
            base64_decode($sStr),
            MCRYPT_MODE_ECB
        );

        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }

}
/**
import javax.crypto.Cipher;
import javax.crypto.spec.SecretKeySpec;

import org.apache.commons.codec.binary.Base64;

public class Security {
    public static String encrypt(String input, String key){
        byte[] crypted = null;
        try{
            SecretKeySpec skey = new SecretKeySpec(key.getBytes(), "AES");
            Cipher cipher = Cipher.getInstance("AES/ECB/PKCS5Padding");
            cipher.init(Cipher.ENCRYPT_MODE, skey);
            crypted = cipher.doFinal(input.getBytes());
        }catch(Exception e){
            System.out.println(e.toString());
        }
        return new String(Base64.encodeBase64(crypted));
    }

    public static String decrypt(String input, String key){
    byte[] output = null;
        try{
            SecretKeySpec skey = new SecretKeySpec(key.getBytes(), "AES");
            Cipher cipher = Cipher.getInstance("AES/ECB/PKCS5Padding");
            cipher.init(Cipher.DECRYPT_MODE, skey);
            output = cipher.doFinal(Base64.decodeBase64(input));
            }catch(Exception e){
        System.out.println(e.toString());
    }
        return new String(output);
    }

	public static void main(String[] args) {
    String key = "1234567891234567";
		String data = "example";

		System.out.println(Security.encrypt(data, key));

		System.out.println(Security.decrypt(Security.encrypt(data, key), key));


	}
}


 *
 */