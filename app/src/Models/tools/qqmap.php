<?php
use PtPHP\Model as Model;
use PtPHP\Curl as Curl;
class Model_Tools_Qqmap extends Model{
    static function convert_gps($latx,$lngy){
        //PtApp::$setting['qq_map']['key_service']
        $key = Model_Setting::get("qq_map_key_service1");
        if(!$key){
            Model_Setting::add("qq_map_key_service1","I6OBZ-EFNHR-JAZWY-WHLXW-O3TOH-EIFLA","QQ地图 SERVICE KEY");
            Model_Setting::add("qq_map_key_js","66DBZ-IG7WJ-G2CFQ-KVS4Z-PBQA5-WQFLR","QQ地图 JS KEY");
        }
        $url = "http://apis.map.qq.com/ws/coord/v1/translate";
        $data = array(
            "locations"=>$latx.",".$lngy,
            "type"=>1,
            "key"=>$key,
        );
        $curl = new Curl();
        $url = $url."?".http_build_query($data);
        $res = $curl->get($url);
        $body = json_decode($res['body']);
        if($body->status > 0){
            throw new Exception($body->message);
        }
        return $body->locations[0];
    }
    #lon为经度，lat为纬度，一定不要弄错了哦
    static function gps_distance($lon1, $lat1, $lon2, $lat2){
        return (2*ATAN2(SQRT(SIN(($lat1-$lat2)*PI()/180/2)
                *SIN(($lat1-$lat2)*PI()/180/2)+
                COS($lat2*PI()/180)*COS($lat1*PI()/180)
                *SIN(($lon1-$lon2)*PI()/180/2)
                *SIN(($lon1-$lon2)*PI()/180/2)),
                SQRT(1-SIN(($lat1-$lat2)*PI()/180/2)
                    *SIN(($lat1-$lat2)*PI()/180/2)
                    +COS($lat2*PI()/180)*COS($lat1*PI()/180)
                    *SIN(($lon1-$lon2)*PI()/180/2)
                    *SIN(($lon1-$lon2)*PI()/180/2))))*6378140;

    }

    function action_test_convert_gps(){
        $latx = "31.2398240000";
        $lngy = "121.4122800000";
        $res = self::convert_gps($latx,$lngy);
        return $res;
    }
    function action_test_gps_distance(){
        echo self::gps_distance(39.91917,116.3896,39.91726,116.3940);
    }
}