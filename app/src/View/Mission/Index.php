<?php
Model_Session::session_start();
$env = PtConfig::$env;
$user_id = Controller\Mission\Auth::get_user_id();
$is_wechat = PtPHP\Utils::is_wechat_browser();
if($is_wechat && empty($_SESSION['wx_openid'])) {
    $auth_info = Model_Wechat_Api::get_auth_info();
    $openid = $auth_info['openid'];
    $user_wx = Model_Wechat_User::get_auth_info_by_openid($openid);
    if (empty($user_wx)) {
        PtPHP\Model::_debug(array(__METHOD__, "save info"));
        $user_wx = Model_Wechat_User::save($auth_info);
    } else {
        PtPHP\Model::_debug(array(__METHOD__, "from db"));
    }
    $_SESSION['wx_openid'] = $openid;
    unset($user_wx['info']);
    $_SESSION['wx_auth_info'] = json_encode($user_wx);
}

$wx_auth_info = isset($_SESSION['wx_auth_info'])?$_SESSION['wx_auth_info']:null;
//var_dump($_GET);exit;

if(!$user_id && !empty($_GET['access_token'])){
    $access_token = $_GET['access_token'];
    $user_info = Model\Ldt\Mission\Sso::getUserInfo($access_token);
    $user_id = Model\Ldt\Mission\Sso::handleResponse($user_info);
    Controller\Mission\Auth::set_auth_uid($user_id);
}
if(!empty($_SESSION['wx_openid']) && $user_id){
    Model_Wechat_User::bind_user($_SESSION['wx_openid'],$user_id);
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>...</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <?php if(!empty($app_css_url)){ ?>
        <link rel="stylesheet" href="<?php echo $app_css_url;?>">
    <?php } ?>
    <script>
        window.onerror = function(errorMessage, scriptURI, lineNumber,columnNumber,errorObj) {
            //alert([errorMessage, scriptURI, lineNumber,columnNumber,errorObj].join(" | "))
        }
        window.production = <?php echo PtConfig::$env == "production" ? "true":"false"?>;
        window.apiUrl = "/api.php";
        window.auth = {
            is_logined:<?php echo $user_id ? "true":'false';?>,
            wx_auth_info:<?php echo $wx_auth_info ? $wx_auth_info:'null';?>,
        };
        window.get_sso_auth_login_url = function(){
            var c_url = location.href;
            if(c_url.indexOf("#") > 0){
                if(c_url.indexOf("?") > 0){
                    c_url = c_url.replace("#","&from=sso#");
                }else{
                    c_url = c_url.replace("#","?from=sso#");
                }
            }else{
                if(c_url.indexOf("?") > 0){
                    c_url += "&from=sso";
                }else{
                    c_url += "?from=sso";
                }
            }

            return window.SSO_AUTH_URL + encodeURIComponent(c_url);
        };
        window.go_login = function(){
            location.href = window.get_sso_auth_login_url();
        };
        function del_wechat_callback_code(){
            //去掉微信回调code
            if(location.search.length > 0 && location.search.match(/code=(.+)&state=(.)&?/)){
                var search = location.search.replace(/code=(.+)&state=(.+&?#?$)/,"")
                location.href = location.origin + location.pathname + (search == "?" ? "":search) + (location.hash ? location.hash:"");
            }
        }
        del_wechat_callback_code();
    </script>
</head>
<body ontouchstart>
<div class="container" id="root"></div>
<script src="<?php echo $vendor_js_url;?>"></script>
<script src="<?php echo $app_js_url;?>"></script>
</body>
</html>