<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 15/12/21
 * Time: 下午2:45
 */
use PtPHP\Model as Model;
/**
 * 活动
 * Class Model_Event
 */
class Model_Event extends Model{
    const HANDLED_NUMS_KEY="l_ev_h_num_";
    #队列KEY
    const QUEUE_KEY = "l_ev_que_";
    #正在处理队列KEY
    const QUEUE_WORKING_KEY = "l_ev_que_woking_";
    #队列每次处理数量
    const ONCE_HANDLE_LEN_KEY = "l_ev_once_";
    #队列每次处理数量 默认值
    const ONCE_HANDLE_LEN_DEFAULT = 100;
    #循环时间间隔 单位:秒
    const LOOP_INTERVAL_KEY = "l_ev_interval_";
    #默认循环时间间隔
    const LOOP_INTERVAL_DEFAULT = 1;

    /**
     * 用户领完券后加入侍处理队列
     * @param $data
     * @return int
     */
    static function push_to_queue($data){
        $res = self::_redis()->lPush(self::QUEUE_KEY,json_encode($data));
        //self::_debug($res);
        return $res;
    }

    /**
     * 侍处理队列抽出
     * @return mixed|null
     */
    static function pop_from_queue(){
        $data = self::_redis()->lPop(self::QUEUE_KEY);
        return empty($data)?array():json_decode($data,1);
    }

    static function push_working_queue($rows){
        foreach($rows as $row){
            self::_redis()->lPush(self::QUEUE_WORKING_KEY,json_encode($row));
        }
    }
    static function del_working_queue(){
        self::_redis()->del(self::QUEUE_WORKING_KEY);

    }
    static function pop_from_working_queue(){
        $data = self::_redis()->lPop(self::QUEUE_WORKING_KEY);
        return empty($data)?array():json_decode($data,1);
    }
    static function queue_working_length(){
        return self::_redis()->lLen(self::QUEUE_WORKING_KEY);
    }

    /**
     * 侍处理队列长度
     * @return int
     */
    static function queue_length(){
        return self::_redis()->lLen(self::QUEUE_KEY);
    }

    static function get_once_handle_len(){
        $len =  self::_redis()->get(self::ONCE_HANDLE_LEN_KEY);
        return !$len?self::ONCE_HANDLE_LEN_DEFAULT:intval($len);
    }
    static function set_once_handle_len($len){
        return self::_redis()->set(self::ONCE_HANDLE_LEN_KEY,$len);
    }

    static function get_loop_interval(){
        $ms =  self::_redis()->get(self::LOOP_INTERVAL_KEY);
        return !$ms?self::LOOP_INTERVAL_DEFAULT:floatval($ms);
    }
    static function set_loop_interval($s){
        return self::_redis()->set(self::LOOP_INTERVAL_KEY,$s);
    }
    /**
     * 处理队列数据
     * @param $rows
     */
    static function process_rows($rows){

    }

    /**
     * 处理队列数据 失败 回滚
     * @param $rows
     */
    static function process_rows_rollback($rows){
        foreach($rows as $row){
            self::push_to_queue($row);
        }
    }
    static function get_handled_nums(){
        $nums = self::_redis()->get(self::HANDLED_NUMS_KEY);
        return empty($nums)?0:intval($nums);
    }
    static function set_handled_nums($num){
        self::_redis()->incrBy(self::HANDLED_NUMS_KEY,$num);
    }
    static function get_task_info(){
        $info = array(
            "queue_length"=>self::queue_length(),
            "handled_nums"=>self::get_handled_nums(),
            "pop_length"=>self::get_once_handle_len(),
            "interval"=>self::get_loop_interval()
        );
        return $info;
    }
}