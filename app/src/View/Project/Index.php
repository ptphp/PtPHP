<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>projects</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container" style="margin-top:20px;">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">配置</div>
                <div class="panel-body">
                    <div class="list-group">
                        <a class="list-group-item">开发环境:<?php echo PtConfig::$env?></a>
                    </div>
                    <?php if(!empty($auth_info)) {
                        if(is_string($auth_info)) $auth_info = json_decode($auth_info,1);
                        ?>
                    <table class="table">
                        <tr>
                            <th>nickname</th>
                            <td><?php echo $auth_info['nickname']?></td>
                        </tr>
                    </table>
                    <?php } ?>
                    <?php
                    $user_id =  Controller\Mission\Auth::get_user_id();
                    if(!empty($user_id)) { ?>
                        <table class="table">
                            <tr>
                                <th>user_id</th>
                                <td><?php echo $user_id;?></td>
                            </tr>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">项目</div>
                <div class="panel-body">
                        <table class="table">
                        <?php foreach($projects as $project_name=>$project){ if($project['status'] == 1) { ?>
                        <tr data-name="<?php echo $project_name;?>">
                           <th><?php echo $project['site_title'];?></th>
                            <td style="width: 120px">
                                <button class="btn btn-primary btn-sm" onclick="go_pro_dev(this)">开发</button>
                                <button class="btn btn-default btn-sm" onclick="go_pro_pro(this)">生产</button>
                            </td>
                        </tr>
                        <?php } }?>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">工具</div>
                <div class="panel-body">
                    <button onclick="session_destroy()" class="btn btn-primary">session destroy</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/jquery/1.10.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    window.onerror = function(errorMessage, scriptURI, lineNumber,columnNumber,errorObj) {
        //alert([errorMessage, scriptURI, lineNumber,columnNumber,errorObj].join(" | "))
    }
    function del_wechat_callback_code(){
        //去掉微信回调code
        if(location.search.length > 0 && location.search.match(/code=(.+)&state=(.)&?/)){
            var search = location.search.replace(/code=(.+)&state=(.+&?#?$)/,"")
            location.href = location.origin + location.pathname + (search == "?" ? "":search) + (location.hash ? location.hash:"");
        }
    }
    del_wechat_callback_code();
    function go_pro_pro(obj){
        var name = $(obj).parents("tr").data("name");
        location.href = "/project.php?pd=1&&app="+name;
    }
    function go_pro_dev(obj){
        var name = $(obj).parents("tr").data("name");
        location.href = "/project.php?app="+name;
    }
    function session_destroy(){
        location.href = "/project.php?action=session_destroy";
    }
</script>
</body>
</html>