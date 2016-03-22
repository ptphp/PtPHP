<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

class Curl{
    var $cache = FALSE;
    var $cache_file = '';
    var $del_cache = FALSE;
    var $debug = FALSE;
    var $_proxy = '';
    function set_debug($f = 0){
        if($f) $this->debug = true;
    }
    function get_cookie_save_file($url){
        $info = parse_url($url);
        return (Utils::is_win()?"e:\\curl_":"/tmp/curl_").$info['host'].(empty($info['port'])?"":"_".$info['port']).".cookie";
    }

    /**
    function get_cookie($header){
    preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $matches);
    $cookies = implode(';', $matches[1]);
    return $cookies;
    }
     */

    /**
     * @param $cookie_file_path
     * @return array
     */
    function get_cookie($cookie_file_path){
        $r = array();
        if(!is_file($cookie_file_path)){
            return $r;
        }
        $cookie = file_get_contents($cookie_file_path);

        foreach(explode("\n",$cookie) as $line) {
            if(isset($line[0]) && $line[0] != '#' && substr_count($line, "\t") == 6) {
                //echo $line.PHP_EOL;
                $tokens = explode("\t", $line);
                $tokens = array_map('trim', $tokens);
                $tokens[4] = empty($tokens[4])?"":date('Y-m-d h:i:s', $tokens[4]);
                //print_json($tokens);exit;
                $r[$tokens[5]] = array(
                    "name"=>$tokens[5],
                    "value"=>$tokens[6],
                    "domain"=>$tokens[0],
                    "path"=>$tokens[2],
                    "expire"=>$tokens[4],
                    "str"=>$line,
                );
            }
        }
        return $r;
    }
    function request($method = 'GET',$url = '',$query='',$_options = array()){

        if(!function_exists("curl_init")){
            throw new Exception("curl not found");
        }

        $cache_file = '';

        if($this->cache){
            $cache_file = sys_get_temp_dir().'/curl'.md5($url);

            if(is_file($cache_file)){
                if($this->del_cache){
                    unlink($cache_file);
                }else{
                    return include $cache_file;
                }
                //print_pre($cache_file);
            }
        }

        $http_header = array(
            #"Connection: keep-alive",
            "Accept-Encoding:gzip,deflate,sdch",
            "Accept-Language:zh-CN,zh;q=0.8,en;q=0.6"
        );
        if($method == "POST"){
            $http_header[] = "Expect: ";
        }
        if(!empty($_options[CURLOPT_COOKIEFILE])){
            $cookie_save_file = $_options[CURLOPT_COOKIEFILE];
        }else{
            $cookie_save_file = $this->get_cookie_save_file($url);
        }

        //echo $cookie_save_file;
        //pt_log($cookie_save_file);
        //X-Requested-With:XMLHttpRequest
        $options = array(
            CURLOPT_HEADER          =>1,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_0, //强制协议为1.0
            CURLOPT_VERBOSE         => 0,
            CURLOPT_URL 			=> $url,
            CURLOPT_RETURNTRANSFER 	=> 1,
            CURLOPT_TIMEOUT 		=> 50,
            CURLOPT_ENCODING 		=> "gzip",
            CURLOPT_SSL_VERIFYPEER	=> 0,
            CURLOPT_SSL_VERIFYHOST	=> 0,
            CURLOPT_IPRESOLVE       => CURL_IPRESOLVE_V4,//强制使用IPV4协议解析域名
            CURLOPT_DNS_USE_GLOBAL_CACHE => 0,
            CURLOPT_DNS_CACHE_TIMEOUT=>5,
            CURLOPT_USERAGENT  		=> 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36',
            CURLOPT_HTTPHEADER  	=> $http_header,
            CURLOPT_CUSTOMREQUEST 	=> $method, // GET POST PUT PATCH DELETE HEAD OPTIONS
            //CURLOPT_PROXY  			=> $proxy,
            //CURLOPT_COOKIE            => "cookie_str",
            CURLOPT_COOKIEFILE    => $cookie_save_file,   #包含cookie数据的文件名
            CURLOPT_COOKIEJAR     => $cookie_save_file,   #连接结束后保存cookie信息的文件。
        );
        if($this->debug) $options[CURLOPT_VERBOSE] = 1;

        $ch = curl_init();
        $options = $_options + $options;

        if($method == "POST") {
            if(is_array($query)) $query = http_build_query($query);
            $options[CURLOPT_POSTFIELDS] = $query;
        }
        if($this->_proxy)
            $options[CURLOPT_PROXY] = $this->_proxy;
        //print_pre($options);

        curl_setopt_array($ch, $options);

        $content = curl_exec($ch);
        if(!$content)
        {
            throw new \Exception(curl_error($ch));
            //$res['error'] = curl_error($ch);
        }
        //\Console::log($content);
        $info = curl_getinfo($ch);
        $res = array();
        $res['header'] = '';
        $res['http_code'] = $info['http_code'];
        $res['redirect_url'] = $info['redirect_url'];
        $res['info'] = $info;
        $res['cookie_file'] = $cookie_save_file;
        $res['cookie'] = $this->get_cookie($cookie_save_file);
        $res['location'] = '';
        $res['body'] = '';
        $res['error'] = '';

        if(!$content){
            return $res;
        }
        if(isset($options[CURLOPT_HEADER]) && $options[CURLOPT_HEADER] == 1){
            $t = explode("\r\n\r\n", $content);
            //console($t[0]);
            //exit;
            if(0 && isset($options[CURLOPT_PROXY])){
                $res['header'] = $t[1];
                $res['body'] = str_replace($t[0]."\r\n\r\n","",$content);
                $res['body'] = str_replace($t[1]."\r\n\r\n","",$res['body']);
            }else{
                //console(11);
                if($t[0] == "HTTP/1.1 100 Continue"){
                    $res['header'] = $t[1];
                    $res['body'] = str_replace($t[0]."\r\n\r\n","",$content);
                    $res['body'] = str_replace($res['header']."\r\n\r\n","",$res['body']);
                }else{
                    $res['header'] = $t[0];
                    $res['body'] = str_replace($res['header']."\r\n\r\n","",$content);
                }
            }

            $res['location'] = $this->get_location($res['header']);

        }else{
            $res['body'] = $content;
        }

        if($this->is_gzip($res['body']))
            $res['body'] = gzdecode($res['body']);

        if($this->cache){
            $res['cache_file'] = $cache_file;
            $this->cache_file = $cache_file;
            file_put_contents($cache_file, "<?php \nreturn ".var_export($res,true).";");
        }
        //print_pre($res);
        return $res;
    }
    function is_gzip($content){
        if(!$content || strlen($content) < 2)
            return FALSE;
        $bin = substr($content, 0 , 2);

        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);

        $isGzip = FALSE;
        switch ($typeCode)
        {
            case 31139:
                //网站开启了gzip
                $isGzip = TRUE;
                break;
            default:
                $isGzip = FALSE;
        }
        return $isGzip;
    }


    function get_location($header){
        preg_match("/Location:([^\r\n]*)/i", $header, $matches);

        return $matches?trim($matches[1]):'';
    }
    function get($url,$_options = array()){
        return $this->request('GET',$url,'',$_options);
    }

    function post($url,$data = '',$_options = array()){
        $_options[CURLOPT_POST] = 1;
        return $this->request('POST',$url,$data,$_options);
    }
    function ajax_post($url,$data,$_options = array()){
        $_options[CURLOPT_POST] = 1;
        $_options[CURLOPT_HTTPHEADER] = array(
            #"X-Requested-With:XMLHttpRequest",
            "Accept-Encoding:gzip,deflate,sdch",
            "Accept-Language:zh-CN,zh;q=0.8,en;q=0.6"
        );

        return $this->request('POST',$url,$data,$_options);
    }
    function curl_proxy_checker ($url,$proxy,$time_out = 5,$user_agent = "Mozilla/4.0"){
        $start_time = microtime(0);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_PROXY, $proxy);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, $time_out);
        curl_exec ($ch);
        $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $time = microtime(0) - $start_time;
        curl_close($ch);
        if($status != "200"){
            $time = $time_out;
        }

        return array(
            'time'=>$time,
            'status'=>$status,
        );
    }
    function mini($method,$url,$query = ''){
        $method = strtoupper($method);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
        if($method == "POST")
            curl_setopt($ch, CURLOPT_POSTFIELDS,$query);

        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}