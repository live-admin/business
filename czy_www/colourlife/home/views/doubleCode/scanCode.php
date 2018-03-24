<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>跳转中</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/m/insure/');?>js/common/jquery-1.11.3.js"></script>
</head>
<body>
    <script type="text/javascript">
        $(document).ready(function(){
            function setupWebViewJavascriptBridge(callback) {
                if (window.WebViewJavascriptBridge) { return callback(WebViewJavascriptBridge); }
                if (window.WVJBCallbacks) { return window.WVJBCallbacks.push(callback); }
                window.WVJBCallbacks = [callback];
                var WVJBIframe = document.createElement('iframe');
                WVJBIframe.style.display = 'none';
                WVJBIframe.src = 'wvjbscheme://__BRIDGE_LOADED__';
                document.documentElement.appendChild(WVJBIframe);
                setTimeout(function() { document.documentElement.removeChild(WVJBIframe) }, 0);
            }

            var param = {
                'mobile' : '<?php echo $mobile; ?>',
            };

            if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
                ios(param); //苹果调用代码
            }else{
                android(param); //android调用代码
            }

            function ios() {
                setupWebViewJavascriptBridge(function(bridge) {
                    bridge.callHandler('ColourlifeTicket', param, function(response) {});
                });
            }

            function android()
            {
                jsObject.ColourlifeTicket(JSON.stringify(param));
            }

        });
    </script>
</body>
</html>