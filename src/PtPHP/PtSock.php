<?php
/**
 * @link http://www.ptphp.com
 * @copyright Copyright (c) 2012 PtPHP Software LLC
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author joseph <ptphp@qq.com>
 */

namespace PtPHP;

class PtSock {
    static function send($url, $data = array(), $method = "POST") {
        if (substr($url, 0, 7) != "http://") {
            $url = "http://" . $url;
        }
        $method = strtoupper($method);
        if (!empty($data)) {
            $method = "POST";
        }
        if (is_array($data)) {
            $data = json_encode($data);
        }
        $info = parse_url($url);
        $port = 80;
        if (isset($info['port'])) {
            $port = $info['port'];
        }
        $host = $info['host'];
        $path = $info['path'];
        $query = empty($info['query'])?"":"?".$info['query'];
        //var_dump($info);
        //exit;
        $fp = fsockopen($host, $port, $errno, $errstr, 10);
        try {
            if (!$fp) {
                throw new Exception("$errstr ($errno)");
            } else {
                $head = "$method $path$query HTTP/1.0\r\n";
                $head.="Host: $host\r\n";
                $head.="Content-type: application/x-www-form-urlencoded\r\n";
                $head.="Content-Length: " . strlen(trim($data)) . "\r\n";
                $head.="\r\n";
                $head.=trim($data);
                //echo $head;
                fputs($fp, $head);
                while (!feof($fp)) {
                    $content = fgets($fp, 128);
                    //echo $content;
                    break;
                }

                fclose($fp);
            }
        } catch (Exception $e) {
            // log
        }
    }
}
