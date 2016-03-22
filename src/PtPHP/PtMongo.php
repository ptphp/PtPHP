<?php
namespace PtPHP;
use Exception;
use MongoClient;
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 7/17/15
 * Time: 12:33 PM
 */

class PtMongo{
    static function obj($key = "default"){
        if(!class_exists("MongoClient")){
            throw new Exception("MongoClient not found");
        }
        $g_key = "mongo_obj_$key";
        if(!isset($GLOBALS[$g_key])){
            global $setting;
            if(isset($setting) && isset($setting['mongo']) && isset($setting['mongo'][$key])){
                $config = $setting['mongo'][$key];
            }else{
                $config['host']     = "127.0.0.1";
                $config['port']     = "27017";
            }
            try{
                $obj = new MongoClient("mongodb://".$config['host'].":".$config['port']);
                $GLOBALS[$g_key] = $obj;
            }catch (Exception $e){
                throw new Exception($e->getMessage());
            }

        }else{
            $obj = $GLOBALS[$g_key];
        }
        return $obj;
    }
}