<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?php echo $site_title;?></title>
    <script src="//as.alipayobjects.com/g/component/??console-polyfill/0.2.2/index.js,es5-shim/4.1.14/es5-shim.min.js,es5-shim/4.1.14/es5-sham.min.js,html5shiv/3.7.2/html5shiv.min.js,media-match/2.0.2/media.match.min.js"></script>
    <script src="//cdn.bootcss.com/jsencrypt/2.1.0/jsencrypt.min.js"></script>
    <?php if(!empty($app_css_url)){ ?>
        <link rel="stylesheet" href="<?php echo $app_css_url;?>">
    <?php } ?>
    <script>
        window.onerror = function(errorMessage, scriptURI, lineNumber,columnNumber,errorObj) {
            //alert([errorMessage, scriptURI, lineNumber,columnNumber,errorObj].join(" | "))
        }
        window.production = <?php echo PtConfig::$env == "production" ? "true":"false"?>;
        window.apiUrl = "/mission/service/api.php";
    </script>
</head>
<body ontouchstart>
<div class="container" id="root"></div>
<script src="<?php echo $vendor_js_url;?>"></script>
<?php if(!empty($antd_js_url)){ ?>
    <script src="<?php echo $antd_js_url;?>"></script>
<?php } ?>
<script src="<?php echo $app_js_url;?>"></script>
</body>
</html>