<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

use \Exception as Exception;
use \PDOException as PDOException;
use \PDO as PDO;

function is_array_sin($array){
    return count($array)== count($array, 1);
}
/**
 * Pdo 类
 * @author Joseph
 *
 * PDO::query() 主要是用于有记录结果返回的操作，特别是SELECT操作
 * PDO::exec() 主要是针对没有结果集合返回的操作，如INSERT、UPDATE等操作
 * PDO::prepare() 主要是预处理操作，需要通过$rs->execute()来执行预处理里面的SQL语句，这个方法可以绑定参数，功能比较强大（防止sql注入就靠这个）
 * PDO::lastInsertId() 返回上次插入操作，主键列类型是自增的最后的自增ID
 * PDOStatement::fetch() 是用来获取一条记录
 * PDOStatement::fetchAll() 是获取所有记录集到一个集合
 * PDOStatement::fetchColumn() 是获取结果指定第一条记录的某个字段，缺省是第一个字段
 * PDOStatement::rowCount() :主要是用于PDO::query()和PDO::prepare()进行DELETE、INSERT、UPDATE操作影响的结果集，对PDO::exec()方法和SELECT操作无效。
 *

PtPHP\Database::$config = array(
    'default'=>array(
        'type'=>'mysql',
        'host'=>'127.0.0.1',
        'port'=>3306,
        'dbname'=>'ptphp',
        'dbuser'=>'root',
        'dbpass'=>'root',
        'charset'=>'utf8',
    )
);
$db = PtPHP\Database::init("default");
$res = $db->select_row("select 1");
 *
 */
class Database {
    private static $_obj = array();
    private $conn;
    private $stm;
    public  $auto_commit = True;
    public  $last_sql;
    public static $config = array();
    public static $db_config = array();
    public static $run_stack = array();
    private function __construct($key)
    {
        //pt_log("init");
        $this->config($key);
    }
    function print_setting(){
        print_r(self::$db_config);exit;
    }
    function get_run_stack(){
        return self::$run_stack;
    }
    public static function init($key = 'default')
    {
        if(empty(self::$config)){

            if(empty($setting['db'])){
                if(class_exists("PtApp")){
                    self::$config = \PtApp::$setting['db'];
                }

            }else{
                self::$config = $setting['db'];
            }
        }
        if(empty(self::$_obj[$key]))
        {
            return self::$_obj[$key] = new Database($key);
        }
        return self::$_obj[$key];
    }
    public function close(){
        echo "close";
    }

    public function config($key){

        if(!class_exists("PDO")){
            throw new Exception("PDO not found");
        }
        //pt_log(debug_backtrace());
        if(empty(self::$config[$key])){
            throw new Exception("no config find in setting");
        }

        self::$db_config = $_config = self::$config[$key];

        try{
            if(!isset($_config['type'])){
                $_config['type'] = 'mysql';
            }
            if(!isset($_config['port'])){
                $_config['port'] = 3306;
            }
            if(!isset($_config['charset'])){
                $_config['charset'] = "utf8";
            }
            if($_config['type'] == 'sqlite'){
                throw new Exception("sqlite not implement");
                /**
                 * $_path = PATH_PRO."/Data/".$_config['dbname'];
                $dir = dirname($_path);
                if(!is_dir($dir)){
                @pt_mkdir($dir);
                }
                $dsn = $_config['type'].":".$_path;
                $this->conn = new PDO($dsn);
                 */
            }else{
                $dsn = $_config['type'].":host=".$_config['host'].";charset=".$_config['charset'].";dbname=".$_config['dbname'].";port=".$_config['port'];
                $this->conn = new PDO($dsn,$_config['dbuser'],$_config['dbpass']);
            }

            if(!isset($_config['charset'])){
                $_config['charset'] = 'utf8';
            }
            $this->conn->query("set names ".$_config['charset'].";");
            $this->conn->setAttribute(PDO::ATTR_TIMEOUT, 30);
            //$this->conn->query("set interactive_timeout=24*3600;");
            $this->setSafe();
            $this->setErrMode();

        }catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    /**
     * 禁用仿真预处理，使用真正的预处理。这样确保语句在发送给MySQL服务器前没有通过PHP解析，不给攻击者注入SQL的机会
     */
    private function setSafe(){
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    /**
     * PDO有三种错误处理方式：
     * • PDO::ERRMODE_SILENT     不显示错误信息，只设置错误码
     * • PDO::ERRMODE_WARNING    显示警告错
     * • PDO::ERRMODE_EXCEPTION  抛出异常
     *
     */
    private function setErrMode(){
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function rollback(){
        if(False == $this->auto_commit){
            $this->conn->rollBack();
        }
        return $this;
    }

    function commit(){
        if(False == $this->auto_commit){
            $this->conn->commit();
            $this->auto_commit = True;
        }
        return $this;
    }

    function bt(){
        $this->auto_commit = FALSE;
        $this->conn->beginTransaction();
        return $this;
    }

    function quote($str){
        return $this->conn->quote($str);
    }

    //执行SQL
    public function run_sql($sql) {
        $args = func_get_args();
        if(count($args) > 1){
            if(is_array($args[1])){
                $args = $args[1];
            }else{
                array_shift($args);
            }
        }else{
            $args = array();
        }
        $this->run($sql,$args,FALSE);
        return $this->row_count();
    }

    //返回插入ID
    public function last_id() {
        return $this->conn->lastInsertId();
    }

    public function exist($table,$field,$value,$pk = "id"){
        return $this->select_row("select $pk from $table where $field = ?",$value);
    }
    public function count($talbe,$condition,$pk = "id",$total = "total"){
        $keys = array_keys($condition);
        $where = " ";
        foreach ($keys as $key) {
            $args[] = $condition[$key];
            $where .= ' `'.$key."`= ? and";
        }
        $this->select_row("select count($pk) as $total from $talbe where $where",$args);
    }
    public function get_last_sql(){
        return self::$run_stack[0];
    }
    //返回一维数组
    public function row($sql){
        $args = func_get_args();
        if(count($args) > 1){
            if(is_array($args[1])){
                $args = $args[1];
            }else{
                array_shift($args);
            }
        }else{
            $args = array();
        }
        return $this->run($sql,$args,TRUE,'one');
    }
    //返回二维数组
    public function rows($sql){
        $args = func_get_args();
        if(count($args) > 1){
            if(is_array($args[1])){
                $args = $args[1];
            }else{
                array_shift($args);
            }
        }else{
            $args = array();
        }
        return $this->run($sql,$args,TRUE,'all');
    }
    //返回一维数组
    public function select_row($sql){
        $args = func_get_args();
        if(count($args) > 1){
            if(is_array($args[1])){
                $args = $args[1];
            }else{
                array_shift($args);
            }
        }else{
            $args = array();
        }
        return $this->run($sql,$args,TRUE,'one');
    }
    //返回二维数组
    public function select_rows($sql){
        $args = func_get_args();
        if(count($args) > 1){
            if(is_array($args[1])){
                $args = $args[1];
            }else{
                array_shift($args);
            }
        }else{
            $args = array();
        }
        return $this->run($sql,$args,TRUE,'all');
    }
    public function select_rows_obj($key,$sql){
        $args = func_get_args();
        if(count($args) > 2){
            if(is_array($args[2])){
                $args = $args[2];
            }else{
                array_shift($args);
                array_shift($args);
            }
        }else{
            $args = array();
        }
        $rows = $this->select_rows($sql,$args);
        $res = array();
        foreach($rows as $row){
            $res[$row[$key]] = $row;
        }
        return $res;
    }

    public function query($sql){
        $result = $this->conn->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exec($sql){
        $this->conn->exec($sql);
    }
    private function get_real_sql($sql,$args){
        //todo
        return $sql;
        //stop($args);
        $sql = preg_replace_callback(
            '/[?]/',
            function ($k) use ($args) {
                static $i = 0;
                //pt_log($i);
                return sprintf("'%s'", empty($args[$i++])?"":$args[$i++]);
            },
            $sql
        );
        return $sql;
    }
    public function run($sql,$args = array(),$return = FALSE,$returnType = 'one',$fetcheType = PDO::FETCH_ASSOC) {
        $RSarray = array();
        try{
            $this->last_sql = $sql;
            $start_time = microtime(1);
            $stack = array(
                "sql"=>$sql,
                "args"=>$args,
            );

            $this->stm = $this->conn->prepare($sql);
            if($args){
                //if(function_exists("pt_local_dev")){
                    //if(pt_local_dev()){
                        //$stack['real_sql'] = $this->get_real_sql($sql,$args);
                $stack['args'] = $args;
                    //}
                //}
                $this->stm->execute($args);
            }else{
                $this->stm->execute();
            }

            if($return){
                if($returnType == 'one'){
                    $RSarray = $this->stm->fetch($fetcheType);
                }else{
                    $RSarray = $this->stm->fetchAll($fetcheType);
                }
                $stack['time'] = microtime(1) - $start_time;
                $this->add_run_stack($stack);
                return $RSarray;
            }else{
                $stack['time'] = microtime(1) - $start_time;
                $this->add_run_stack($stack);
            }
        }catch (PDOException $e){
            $this->add_run_stack($stack);
            $msg = $e->getMessage();
            if(Utils::is_cli()){
                $msg .="\n".var_export($stack,true);
            }
            throw new Exception($msg);
        }
        return $RSarray;

    }

    function insert($table,$rows){
        return $this->_insert($table,$rows,"INSERT");
    }

    function replace($table,$rows){
        $this->_insert($table,$rows,"REPLACE");
    }

    public function delete($table,$condition){
        $args = array();
        $where = $this->get_where($condition,$args);
        $sql = "DELETE FROM  `$table` WHERE ".$where;
        $this->run_sql($sql,$args);
        //echo $this->row_count();
        return $this->row_count();
    }

    /**
     * @param $table
     * @param $key
     * @param $args
     * @param array $condition
     * @return mixed
     * @throws Exception
     */

    public function delete_in($table,$key,$args,$condition = array()){
        if(empty($args)){
            throw new Exception("\$rows 不能不为空");
        }
        if(!is_array($args)){
            throw new Exception("\$rows 不是数组");
        }
        $placeholder = substr(str_repeat('?,',count($args)),0,-1);
        $where = " $key in ($placeholder) and";
        if(is_array($condition) && !empty($condition)){
            $where .= $this->get_where($condition,$args);
        }
        $where  = substr($where,0,-4);
        $sql = "DELETE FROM `$table` WHERE $where";
        $this->run_sql($sql,$args);
        return $this->row_count();
    }
    function update($table,$row,$condition){
        if(empty($row)){
            throw new Exception("\$rows 不能不为空");
        }
        if(empty($condition)){
            throw new Exception("\$condition 不能不为空");
        }
        $set = '';
        $keys = array_keys($row);
        $args = array();
        foreach ($keys as $key) {
            $args[] = $row[$key];
            $set .= ' `'.$key."`= ?,";
        }
        $set = substr($set,0,-1);
        $where = $this->get_where($condition,$args);
        $sql = "UPDATE `$table` SET $set where ".$where;
        $this->run_sql($sql,$args);
        //echo $this->row_count();
        return $this->row_count();
    }
    function get_where($condition,&$args){
        $keys1 = array_keys($condition);
        $where = "";
        foreach ($keys1 as $key) {
            $args[] = $condition[$key];
            $where .= ' `'.$key."`= ? and";
        }
        $where  = substr($where,0,-4);
        return $where;
    }
    function row_count(){
        return $this->stm->rowCount();
    }

    public function show_tables($like = ''){
        $sql = "show tables";
        if($like){
            $sql .= " like '".$like."'";
        }
        $result = $this->conn->query($sql);
        $tables = array();
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        //print_r($tables);
        return $tables;
    }

    function _truncate($table){
        $sql = "TRUNCATE TABLE `$table`";
        $this->run($sql);
    }

    public function desc_table($table,$type="mysql"){
        //sqlite  PRAGMA table_info(admin)
        //msyql   SHOW COLUMNS FROM admin
        //msyql   desc admin

        if($type == 'mysql'){
            $sql = "SHOW COLUMNS FROM " . $table;
        }else{
            $sql = "PRAGMA table_info(" . $table . ")";
        }
        //echo $sql;
        //exit;
        $result = $this->conn->query($sql);
        $table_fields = $result->fetchAll(PDO::FETCH_ASSOC);

        return $table_fields;
    }

    public function show_create_table($table){
        $sql = 'show create table `'.$table."`";
        $result = $this->conn->query($sql);
        $res = $result->fetch(PDO::FETCH_NUM);
        if($res){
            return $res[1];
        }else{
            return array();
        }
    }
    private function add_run_stack($stack){
        //if(function_exists("pt_local_dev")){
        //    if(pt_local_dev()){
                array_unshift(self::$run_stack,$stack);
        //    }
        //}
    }
    private function _insert($table,$rows,$type = "INSERT"){
        if(empty($rows)){
            throw new Exception("\$rows 不能不为空");
        }
        $sin = is_array_sin($rows);
        if($sin){
            $keys = array_keys($rows);
        }else{
            $keys = array_keys($rows[0]);
        }
        if($type != "INSERT"){
            $type = "REPLACE";
        }
        $fields = '`'.implode('`, `',$keys).'`';

        if($sin){
            $placeholder = substr(str_repeat('?,',count($keys)),0,-1);
            $values = "VALUES($placeholder)";
        }else{
            $placeholder = "";
            foreach($rows as $row){
                $placeholder .= "(".substr(str_repeat('?,',count($keys)),0,-1)."),";
            }
            $placeholder = substr($placeholder,0,-1);
            $values = "VALUES$placeholder;";
            //print_r($rows);
            //echo $type.' INTO `'.$table.'` ('.$fields.' ) '.$values;
            //exit;
        }

        $sql = $type.' INTO `'.$table.'` ('.$fields.' ) '.$values;

        $args = array();

        if($sin){
            foreach($keys as $key){
                $args[] = $rows[$key];
            }
        }else{
            foreach($rows as $row){
                foreach($keys as $key){
                    $args[] = $row[$key];
                }
            }
        }
        $this->run_sql($sql,$args);
        return $this->last_id();
    }

    function __destruct() {
        $this->conn = null;
        $this->stm  = null;
    }

    public function __clone()
    {
        throw new Exception('Clone is not allow');
    }
}
