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
			  font: 14px Microsoft YaHei, Helvitica, Verdana, Arial, san-serif;
			  border:0 none;
		  }
		  body{ background:#e3f6ff;}
		  a,a:hover{ text-decoration:none; color:#000;}
		  ul, ol, li{list-style:none outside none;}
		  input, button{font-size:12px; margin:0; vertical-align: middle;}
		  img{border:0 none; display:block; margin:0; padding:0;}
		  .floatleft{float:left;}
		  .floatright{float:right;}
		  .clr{clear:both;}
		  .boximg{width:100%;}
		  .wifi{overflow:hidden; width:100%; text-align:center; color:#000;}
		  .erro_content{padding:25px 0; text-align:center; background:#ebebeb;}
		  .scale{width:40%; margin:100px auto 0;}
		  .logo{position:absolute; width:50%; left:25%; bottom:0;}
		  .jy{padding:2%; width:85%; margin:30px auto 60px; text-align:left;}
		  .jy dd{margin:5px 0;}
		  .jy dt{margin-bottom:15px;}
		  .jy dd{color:#595959; font-size:12px;}
		  .wifilogo{width:50px; margin:0 auto;}
		  .czy_logo{position:absolute; width:100%; text-align:center; left:0; bottom:0;}
		  .czy_logo img{width:100px; margin:0 auto 30px;}
        </style>
    </head>
    <body>
		<div class="wifi">
            <div class="erro_content">
              <div class="wifilogo">
                <a href="<?php echo F::getHomeUrl('/WifiApp')?>"><img src="<?php echo F::getStaticsUrl('/wifiapp/images/img3.gif');?>" class="boximg" /></a>
              </div>
              <p style="margin-top:10px; color:#9f9e9e;"><a href="<?php echo F::getHomeUrl('/WifiApp')?>" style="color:#9f9e9e; display:block; width:100%;">啊噢,连接失败了，再试一下吧！点击重新连接。</a></p>
            </div>
            <dl class="jy">
              <dt>贴心提示：</dt>
              <dd>
                 1、检查您的手机WIFI是否打开
              </dd>
              <dd>
                 2、检查您所连接的网络是否为小区WIFI。
              </dd>
            </dl>
            <div class="czy_logo">
              <img src="<?php echo F::getStaticsUrl('/wifiapp/images/logo.png');?>" />
            </div>
		</div>
	</body>
</html>

