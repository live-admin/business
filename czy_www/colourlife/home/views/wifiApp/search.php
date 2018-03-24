<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Cache-Control" content="no-cache"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
        <meta name="format-detection" content="telephone=no" />
        <meta name="MobileOptimized" content="320"/>
        <title>WIFI免费畅连</title>
        <style>
            body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, textarea, p, blockquote, th, td {
                margin: 0;
                padding: 0;
                font: 12px Microsoft YaHei, Helvitica, Verdana, Arial, san-serif;
                border:0 none;
            }
            body{ background:#fff;}
            a,a:hover{ text-decoration:none;}
            ul, ol, li{list-style:none outside none;}
            input, button{font-size:12px; margin:0; vertical-align:middle;}
            img{border:0 none; display:block; margin:0; padding:0;}
            .floatleft{float:left;}
            .floatright{float:right;}
            .clr{clear:both;}
            .boximg{width:100%;}
            .wifi{overflow:hidden; width:100%; text-align:center;}
            .scale{width:280px; margin:30px auto 0;}
            .czy_logo{position:absolute; width:100%; text-align:center; left:0; bottom:0;}
            .czy_logo img{width:100px; margin:0 auto 30px;}
        </style>
        <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
    </head>
    <body>
        <div class="wifi">
            <div class="scale">
                <img src="<?php echo F::getStaticsUrl('/wifiapp/images/img1.gif');?>" class="boximg" />
            </div>
            <p style="color:#595959;">主人，正在努力为您查找WIFI中。</p>
            <div class="czy_logo">
                <img src="<?php echo F::getStaticsUrl('/wifiapp/images/logo.png');?>" />
            </div>
        </div>
    </body>
    <script>
    function load(){
        location.href='http://10.1.0.6/colourlife/?user-agent=www.colourlife.com';
    }
    setTimeout("load()", 3000);
    
    </script>
</html>
