<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>满意度调查</title>
        <script type="text/javascript" src="<?php echo F::getStaticsUrl("/home/XieyiApp/js/jquery.min.js"); ?>"></script>
        <style>
            div{
                border: 1px solid red;
            }
        </style>
        <script type="text/javascript">
            function action(type)
            {
                if (type == 'start' || type == 'stop') {
                    $.post('/SatisfactionSurvey/Console?access_token=colourlife', {'type': type}, function (result) {
                        if (result.code == '0' && type == 'start') {
                            document.getElementById('sendSmsIframe').src='/SatisfactionSurvey/SendSms?access_token=colourlife'
                        }
                    }, 'json')
                } else {
                    document.getElementById('sendStatusIframe').src='/SatisfactionSurvey/SendStatus?access_token=colourlife'
                }

            }
        </script>
	</head>
	<body>
		<div style="height: 600px; overflow: hidden;">
            <div style="height: 600px; width: 700px; float: left;">
                <p style="overflow-x:hidden; padding-left: 10px;">发送记录：</p>
                <iframe src="" width="100%" height="510" id="sendSmsIframe" frameborder="0" scrolling="yes"></iframe>
            </div>
            <div style="height: 600px; width: 700px; float: left; padding-left: 10px;">
                <p style="overflow-x:hidden; padding-left: 10px;">发送情况：</p>
                <iframe src="/SatisfactionSurvey/SendStatus?access_token=colourlife" width="100%" height="510" id="sendStatusIframe" frameborder="0" scrolling="yes"></iframe>
            </div>
        </div>
        <p><button onclick="action('start')">开始</button><button onclick="action('stop')" style="margin-left: 20px;">停止</button><button onclick="action('flush')" style="margin-left: 20px;">刷新</button></p>
	</body>


</html>
