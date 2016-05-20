<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

use Exception;

class Utils {
    static function print_pre($var){
        echo "<pre />";
        var_export($var);
        return true;
    }
    static function handle_exception_file_path($path){
        return implode("/",array_slice(explode("/",$path),-3));
    }
    static function get_exception_file_line($trace){
        $c_t = $trace[0];
        return array(
            "file"=>self::handle_exception_file_path($c_t['file']),
            "line"=>$c_t['line']
        );
    }
    static function I($key){
        return isset($_REQUEST[$key])?$_REQUEST[$key]:null;
    }
    static function action(){
        $action = self::I("action");
        if(!$action) throw new Exception("action 不能为空");
        $fuc = "action_".$action;
        $res = $fuc();
        if($res !== null) echo json_encode($res);
    }
    static function is_win(){
        return strtoupper(substr(PHP_OS,0,3))==='WIN';
    }

    /**
     * todo
     * @param $mobile
     * @return bool
     */
    static function is_mobile($mobile){
        return preg_match("/^(14[0-9]|17[0-9]|13[0-9]|15[\d]|18[\d])\d{8}$/",$mobile);
    }
    static function is_email($email){
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return !filter_var($email, FILTER_VALIDATE_EMAIL) === false;
    }
    static function is_cli(){
        return strtolower(PHP_SAPI) == "cli";
    }
    /**
     * $unicodeChar = "\u56de\u590d\uff1a";
     * echo  unicodeString($unicodeChar);
     * unicode 编码转 简体中文
     * @param $str
     * @param null $encoding
     * @return mixed
     */
    static function unicodeString($str, $encoding=null) {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), "utf-8", "UTF-16BE");'), $str);
    }
    static function is_local_dev(){
        return self::get_pt_env("PT_LOCAL") == 'local';
    }
    static function get_pt_env($key){
        if(self::is_cli()){
            if(isset($_SERVER[$key]))
                return $_SERVER[$key];
            else
                return null;
        }else{
            if(defined("PATH_PRO") && is_file(PATH_PRO."/.env")){
                $env = file_get_contents(PATH_PRO."/.env");
                if($env) $_SERVER[$key] = trim($env);
            }

            if(isset($_SERVER[$key]))
                return $_SERVER[$key];
            else
                return null;
        }
    }
    static function get_argvs(){
        $argvs = $_SERVER['argv'];
        return array_slice($argvs,3);
    }
    static function pre($var,$is_exit = 0){
        echo PRE;
        print_r($var);
        if($is_exit){
            exit;
        }
    }
    static function print_json($var){
        echo json_encode($var,JSON_PRETTY_PRINT);
        if(self::is_cli()){

        }else{
            exit;
        }
    }
    static function die_json($var){
        ob_clean();
        echo json_encode($var,JSON_PRETTY_PRINT);
        if(is_cli()){

        }else{
            exit;
        }
    }
    static function server_param($key){
        return empty($_SERVER[$key]) ? null : $_SERVER[$key];
    }
    /**
     * 获取当前URL地址带端口
     * @return string
     */
    static function current_url_address() {
        //todo
        $request_uri = self::server_param("REQUEST_URI");
        return "http://".$_SERVER['HTTP_HOST'].$request_uri;
//        $domain = self::current_domain();
//        $php_self = self::server_param("PHP_SELF") ? self::server_param("PHP_SELF") : self::server_param("SCRIPT_NAME");
//        $path_info = self::server_param("PATH_INFO") ? self::server_param("PATH_INFO") : '';
//        $request_uri = self::server_param("REQUEST_URI");
//        if(self::server_param("REQUEST_URI")){
//            $relate_url = self::server_param("REQUEST_URI");
//        }
//        //todo
//        $url = $domain.$relate_url;
//        echo $url;exit;
//        return $url;
    }
    static function current_domain(){
        static $domain = null;
        if($domain === null){
            $host = self::server_param("HTTP_HOST");
            if(strpos($host,":") !== false){
                $host = "http://".$host;
            }else{
                $port = self::server_param("SERVER_PORT");
                if($port == '80'){
                    $host =  "http://".$host;
                }else if($port == '443'){
                    $host = "https://".$host;
                }else{
                    $host = "http://".$host.":".$port;
                }
            }
            $domain = $host;
        }

        return $domain;
    }
    static function location($url,$msg = ""){
        if(is_cli()) return;
        ob_clean();
        if(substr($url,0,1) == "/") $url = "http://".current_domain().$url;
        if(is_xhr()){
            json_redirect($msg,$url,1);exit;
        }else{
            header("Location: $url");exit;
        }
    }

    /**
     * 对查询结果集进行排序
     * @access public
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param array $sortby 排序类型
     * asc正向排序 desc逆向排序 nat自然排序
     * @return array
     */
    function list_sort_by($list,$field, $sortby='asc') {
        if(is_array($list)){
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ( $refer as $key=> $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    static function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 将list_to_tree的树还原成列表
     * @param  array $tree  原来的树
     * @param  string $child 孩子节点的键
     * @param  string $order 排序显示的键，一般是主键 升序排列
     * @param  array  $list  过渡用的中间数组，
     * @return array        返回排过序的列表数组
     */
    function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
        if(is_array($tree)) {
            $refer = array();
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if(isset($reffer[$child])){
                    unset($reffer[$child]);
                    self::tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = self::list_sort_by($list, $order, $sortby='asc');
        }
        return $list;
    }
    static function ip($to_long = false){
        static $cip = null;
        if($cip == null){
            if(isset($_SERVER["HTTP_CLIENT_IP"]) && $_SERVER["HTTP_CLIENT_IP"]!='unknown'){
                $cip = $_SERVER["HTTP_CLIENT_IP"];
            }else if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"]!='unknown'){
                $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            }else if(isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"]!='unknown'){
                $cip = $_SERVER["REMOTE_ADDR"];
            }else{
                $cip = "0.0.0.0";
            }
            if($to_long)
                $cip = ip2long($cip);
        }

        return $cip;
    }

    // 从数组列表中, 使用 k_attr 和 v_attr 指定的字段, 组成一个关联数组.
    function _kvs($arr_arr, $k_attr, $v_attr){
        $kvs = array();
        foreach($arr_arr as $arr){
            if(is_array($arr)){
                $k = $arr[$k_attr];
                $v = $arr[$v_attr];
            }else{
                $k = $arr->$k_attr;
                $v = $arr->$v_attr;
            }
            $kvs[$k] = $v;
        }
        return $kvs;
    }
    static function host(){
        $host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"0.0.0.0";
        $port = isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:"80";
        if(strpos($host, ':') === false && $port != 80 && $port != 443){
            $host .= ":{$port}";
        }
        return $host;
    }
    static function base_url(){
        static $link = null;
        if($link === null){
            $host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"0.0.0.0";
            $port = isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:"80";
            if(strpos($host, ':') === false && $port != 80 && $port != 443){
                $host .= ":{$port}";
            }
            $path = dirname(isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:"/");
            if($path == '/'){
                $path = '';
            }
            if(isset($_SERVER['HTTPS']) || $port == 443){
                $link = "https://{$host}{$path}";
            }else{
                $link = "http://{$host}{$path}";
            }
        }
        return $link;
    }
    static function post($url, $data){
        if(is_array($data)){
            $data = http_build_query($data);
        }
        $ch = curl_init($url) ;
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = @curl_exec($ch) ;
        curl_close($ch) ;
        return $result;
    }

    static function get($url, $data=null){
        if(is_array($data)){
            $data = http_build_query($data);
            if(strpos($url, '?') === false){
                $url .= '?' . $data;
            }else{
                $url .= '&' . $data;
            }
        }
        $ch = curl_init($url) ;
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = @curl_exec($ch) ;
        curl_close($ch) ;
        return $result;
    }
    static function json_decode($str, $assoc=false){
        return json_decode($str, $assoc);
    }
    static function json_encode($input, $opt=0){
        if(defined('JSON_UNESCAPED_UNICODE')){
            return json_encode($input, JSON_UNESCAPED_UNICODE | $opt);
        }
        if(is_string($input)){
            $text = $input;
            $text = str_replace('\\', '\\\\', $text);
            $text = str_replace(
                array("\r", "\n", "\t", "\""),
                array('\r', '\n', '\t', '\\"'),
                $text);
            return '"' . $text . '"';
        }else if($input === null){
            return 'null';
        }else if($input === true){
            return 'true';
        }else if($input === false){
            return 'false';
        }else if(is_array($input) || is_object($input)){
            $arr = array();
            $is_obj = is_object($input) || (array_keys($input) !== range(0, count($input) - 1));
            foreach($input as $k=>$v){
                if($is_obj){
                    $arr[] = self::json_encode($k) . ':' . self::json_encode($v);
                }else{
                    $arr[] = self::json_encode($v);
                }
            }
            if($is_obj){
                return '{' . join(',', $arr) . '}';
            }else{
                return '[' . join(',', $arr) . ']';
            }
        }else{
            return $input . '';
        }
    }
    static function xml_to_obj($str){
        $xml = @simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($xml){
            $xml = @json_decode(@json_encode($xml));
        }
        if($xml){
            $xml = self::trim_xml_obj($xml);
        }
        return $xml;
    }

    private static function trim_xml_obj($obj){
        foreach($obj as $k=>$v){
            if(is_object($v)){
                if(count((array)$v) == 0){
                    $v = '';
                }else{
                    $v = self::trim_xml_obj($v);
                }
            }
            $obj->$k = $v;
        }
        return $obj;
    }

    static function xml_to_array($str){
        $xml = @simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($xml){
            $xml = @json_decode(@json_encode($xml), 1);
        }
        return $xml;
    }
    /**
     * forbidden_tags 比 allow_tags 优先
     * @allow_tags, @forbidden_tags: 逗号分隔的标签名字符串.
     */
    static function clean_html($html, $allow_tags=null, $forbidden_tags=null, $urlbase=''){
        if(!is_array($allow_tags)){
            if(!is_string($allow_tags) || !$allow_tags){
                $allow_tags = 'a,img,br,pre,del,p,h1,h2,h3,h4,table,caption,tbody,tr,th,td,ul,ol,li,b,strong,div,embed,blockquote';
            }
            $ps = explode(',', $allow_tags);
            $allow_tags = array();
            foreach($ps as $p){
                $p = trim($p);
                $allow_tags[$p] = 1;
            }
        }
        if(!is_array($forbidden_tags)){
            if(!is_string($forbidden_tags) || !$forbidden_tags){
                $forbidden_tags = '';
            }
            $ps = explode(',', $forbidden_tags);
            $forbidden_tags = array();
            foreach($ps as $p){
                $p = trim($p);
                $forbidden_tags[$p] = 1;
            }
        }
        if(strpos($urlbase, '/') !== strlen($urlbase) - 1){
            $urlbase .= '/';
        }

        $dom = new DOMDocument();
        @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html);
        $root = $dom->documentElement;
        $html = self::clean_html_node($root, $allow_tags, $forbidden_tags, 0, $urlbase);
        return $html;
    }

    private static function clean_html_node($node, $allow_tags, $forbidden_tags, $indent=0, $urlbase=''){
        static $attr_define = array(
            'a' => 'href|title',
            'img' => 'src|alt',
            'td' => 'rowspan|colspan',
            'th' => 'rowspan|colspan',
            'table' => 'border|cellspacing|cellpadding|bordercolor|width',
            'embed' => 'src|type|width|height',
        );

        $tag = strtolower($node->nodeName);

        if($node->nodeType == XML_TEXT_NODE){
            return htmlspecialchars(trim($node->nodeValue));
        }
        if($node->nodeType != XML_ELEMENT_NODE){
            return '';
        }
        if($tag == 'pre'){
            return '<pre>' . htmlspecialchars($node->textContent) . '</pre>';
        }

        $ps = array();
        if($node->childNodes == null){
            return '';
        }
        foreach($node->childNodes as $n){
            $ps[] = self::clean_html_node($n, $allow_tags, $forbidden_tags, $indent+1, $urlbase);
        }
        $child_text = join('', $ps);

        //
        if(!$tag || isset($forbidden_tags[$tag]) || !isset($allow_tags[$tag])){
            return $child_text;
        }

        //$text = str_pad('', $indent, "\t", STR_PAD_LEFT);
        $text = '';
        switch($tag){
            case 'br':
                $text .= "\n<br/>\n";
                break;
            case 'div':
            case 'p':
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'tbody':
            case 'tr':
            case 'ul':
            case 'ol':
            case 'li':
            case 'blockquote':
            case 'strong':
                $text .= "<$tag>$child_text</$tag>\n";
                break;
            case 'del':
            case 'caption':
            case 'b':
            case 'strong':
                $text .= "<$tag>$child_text</$tag>";
                break;
            default:
                if(isset($attr_define[$tag])){
                    $attr = '';
                    $attr_list = explode('|', $attr_define[$tag]);
                    foreach($attr_list as $k){
                        $v = trim($node->getAttribute($k));
                        if(strlen($v) > 0){
                            if(in_array($k, array('src','href'))){
                                if(strpos($v, 'http://') === false && strpos($v, 'https://') === false){
                                    if($v[0] === '/'){
                                        $v = substr($v, 1);
                                    }
                                    $v = $urlbase . $v;
                                }
                            }
                            $v = htmlspecialchars($v);
                            $attr .= " $k=\"$v\"";
                        }
                    }
                    $text .= "<{$tag}{$attr}>$child_text</$tag>";
                    if(in_array($tag, array('embed','table','td','th'))){
                        $text .= "\n";
                    }
                }else{
                    $text .= $child_text;
                }
                break;
        }
        return $text;
    }

    static function stripslashes($mixed){
        if(is_string($mixed)){
            return stripslashes($mixed);
        }else if(is_array($mixed)){
            foreach($mixed as $k=>$v){
                $mixed[$k] = self::stripslashes($v);
            }
            return $mixed;
        }else if(is_array($mixed)){
            foreach($mixed as $k=>$v){
                $mixed->$k = self::stripslashes($v);
            }
            return $mixed;
        }else{
            return $mixed;
        }
    }
    /**
     * 字符串转换为数组，主要用于把分隔符调整到第二个参数
     * @param  string $str  要分割的字符串
     * @param  string $glue 分割符
     * @return array
     */
    function str2arr($str, $glue = ','){
        return explode($glue, $str);
    }

    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     * @param  array  $arr  要连接的数组
     * @param  string $glue 分割符
     * @return string
     */
    function arr2str($arr, $glue = ','){
        return implode($glue, $arr);
    }

    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @return string
     */
    function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
            if(false === $slice) {
                $slice = '';
            }
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice.'...' : $slice;
    }

    /**
     * 数据签名认证
     * @param  array  $data 被认证的数据
     * @return string       签名
     */
    function data_auth_sign($data) {
        //数据类型检测
        if(!is_array($data)){
            $data = (array)$data;
        }
        ksort($data); //排序
        $code = http_build_query($data); //url编码并生成query字符串
        $sign = sha1($code); //生成签名
        return $sign;
    }
    /**
     * 格式化字节大小
     * @param  number $size      字节数
     * @param  string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     */
    function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * 时间戳格式化
     * @param int $time
     * @return string 完整的时间显示
     * @author huajie <banhuajie@163.com>
     */
    function time_format($time = NULL,$format='Y-m-d H:i:s'){
        static $now_time = null;
        if(isset($_SERVER['REQUEST_TIME']))
            $now_time = $_SERVER['REQUEST_TIME'];
        $time = $time === NULL ? $now_time : intval($time);
        return date($format, $time);
    }
    static function date_time_now(){
        return date("Y-m-d H:i:s");
    }
    function excelOutByData($type,$count,$data,$fileName='test'){

        header("Content-Type:application/force-download");
        header("Content-Type:applicationnd.ms-excel");
        header("Content-Disposition:attachment;filename=".$fileName.'.xls');
        header('Pragma: no-cache');
        header('Expires: 0');
        //构造数据输出
        $size = 5000;
        $pages = ceil($count/$size);

        foreach($type as $val){
            $header[] = $val['name'];
        }
        $header = iconv('utf-8', 'gbk', implode("\t",$header));
        echo $header."\n";
        foreach ($data as $item) {
            $arr = array();
            foreach($type as $k=>$vv){
                $v = $vv['type'];
                if(is_array($v)){
                    $arr[] = iconv('utf-8', 'gbk', $v[$item[$k]]);
                }else if($v == 'string'){
                    $arr[] = iconv('utf-8', 'gbk', $item[$k]);
                }else if($v == 'time'){
                    $arr[] = date('Y-m-d H:i',$item[$k]);
                }else{
                    $arr[] = iconv('utf-8', 'gbk', $item[$k]);
                }
            }
            echo implode("\t",$arr)."\n";
        }

        die();
    }
    /**
     * 银行卡数据处理
     * @author song.du@spisolar.com
     * @param  unknown_type $card [银行卡号 4827000011171004302]
     * @param  unknown_type $s 开始索引  0
     * @param  unknown_type $e 结束索引 4
     * @param  unknown_type $x [替换字符  default **********]
     * @return string  [处理后字符串  4827**********4302]
     */
    function withCard($card, $s=0, $e=4, $r='**********'){
        return substr($card, $s, $e).$r.substr($card, -4);
    }
    /**
     * 获取区间时间 [ 1周前  1个月  3个月 ]
     * @param unknown_type $type  1[ 1周前  ] 2 [1个月] 3[3个月 ]
     * @param unknown_type $e [不指定默认当前时间  2015-03-31 23:59:59]
     * @return multitype:unknown string
     */
    function getSectionTime($type, $e=true){
        if($e){
            $e = date('Y-m-d 23:59:59');
        }
        switch ($type){
            case 1: //1周
                $b = date("Y-m-d",mktime(0,0,0,date("m"),date("d") - 7,date("Y")));
                break;
            case 2:  //1个月
                $b = $time=date("Y-m-d",mktime(0,0,0,date("m") - 1,date("d"),date("Y")));
                break;
            case 3: //3个月
                $b = $time=date("Y-m-d",mktime(0,0,0,date("m") - 3,date("d"),date("Y")));
                break;
            default: //1周
                $b = date("Y-m-d",mktime(0,0,0,date("m"),date("d") - 7,date("Y")));
        }
        return array('b' => $b, 'e' => $e);
    }

    //转换url
    function url_base64_decode($code)
    {
        $code=str_replace("!",'+',$code);//把所用"+"替换成"!"
        $code=str_replace("*",'/',$code);//把所用"/"替换成"*"
        $str=base64_decode($code);
        return $str;
    }

    /**
     * 获取当前URL地址
     * @param array $del_param 忽略某些参数
     * @return string
     */
    function get_url($del_param=array())
    {
        $url="http://".$_SERVER["HTTP_HOST"];

        if(isset($_SERVER["REQUEST_URI"]))
        {
            $url.=$_SERVER["REQUEST_URI"];
        }
        else
        {
            $url.=$_SERVER["PHP_SELF"];
            if(!empty($_SERVER["QUERY_STRING"]))
            {
                $url.="?".$_SERVER["QUERY_STRING"];
            }
        }

        $parsed = parse_url($url);
        parse_str(isset($parsed['query'])?$parsed['query']:'', $params);

        $parsed['params'] = array_diff_key($params, array_fill_keys($del_param, 0));

        $url = $parsed['scheme'] .'://' . $parsed['host'].$parsed['path'];


        foreach($parsed['params'] as $k=>$v)
        {
            $url .=  strpos($url,'?') ? "&$k=$v" : "?$k=$v" ;
        }

        return $url;
    }

    /**
     * 正则验证
     * @author dusong
     * @param unknown_type $value
     * @param unknown_type $type
     */
    function regexp($value, $type){
        static $mapReg = array(
            'name' => "/^[\\u4e00-\\u9fa5]+$/", //真实姓名
            'idcard' => "/^[1-9]([0-9]{14}|[0-9]{17})$/", //身份证
            'email' => "/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$/", //邮箱
        );
        return preg_match($mapReg[$type], $value);
    }
    function get_device_type() {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
            $type = 'ios';
        }
        if(strpos($agent, 'android')){
            $type = 'android';
        }
        return $type;
    }
    /**
     * Array filter by key
     *
     * @return array
     */
    function array_key_filter($array = '',$arr = ''){
        if(is_array($array) && is_array($arr)){
            $array = array_filter($array);
            $arr = array_filter($arr);
            foreach($array as $k => $v){
                if(!in_array($k,$arr)){
                    unset($array[$k]);
                }
            }
            return $array;
        }else{
            return false;
        }
    }
    /**
     * [检查是否来自微信]
     * @return bool
     */
    static function is_wechat_browser() {
        return isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }
    /**
     *+----------------------------------------------------------
     * 生成随机字符串
     *+----------------------------------------------------------
     * @param int       $length  要生成的随机字符串长度
     * @param string    $type    随机码类型：0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
     *+----------------------------------------------------------
     * @return string
     *+----------------------------------------------------------
     */
    function randCode($length = 5, $type = 0) {
        $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
        if ($type == 0) {
            array_pop($arr);
            $string = implode("", $arr);
        } elseif ($type == "-1") {
            $string = implode("", $arr);
        } else {
            $string = $arr[$type];
        }
        $count = strlen($string) - 1;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $string[mt_rand(0, $count)];
        }
        return $code;
    }


}
