<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>1元享星巴克</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/xBKActivity/'); ?>css/xbk.css" rel="stylesheet" type="text/css">
        <script src="<?php echo F::getStaticsUrl('/common/'); ?>js/jquery.min.js" type="text/javascript"></script>
	</head>

	<body>
		<div class="xbk">
			<div>
				<img src="<?php echo F::getStaticsUrl('/home/xBKActivity/'); ?>images/XBK_01.jpg" class="lotteryimg" />
				<div class="xbk_btn">
					<a href="javascript:qiang()"></a>
					<img src="<?php echo F::getStaticsUrl('/home/xBKActivity/'); ?>images/XBK_02.jpg" class="lotteryimg" />
				</div>

				<div class="footer">
					<p>领取规则：</p>
					<p>1.本次活动时间为7月10日-7月12日，逾期不予兑换；</p>
					<p>2.每人限购1份，仅限活动现场用户领取，不提供外送、打包等服务；</p>
					<p>3.每人需凭1元购订单号领取，订单号查询：彩之云APP->我->订单记录->购买记录；</p>
					<p>4.星巴克产品每天限量供应，先到先得，领完即止；</p>
					<p>5.彩之云保留法律范围内本次活动的最终解释权。</p>
					<p class="footer_text">★注：活动最终解释权归彩生活所有</p>
				</div>
				
			</div>
		</div>

		<!--弹出框 start-->
		<!-- <div class="opacity" style="display:none;"> -->
	        <div class="cd-popup alert1" role="alert">
	            <div class="cd-popup-container">
	                <p>您还没有登录呢。</p>
	                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
	            </div>
	        </div>
	        
	        <div class="cd-popup alert2" role="alert">
	            <div class="cd-popup-container">
	                <p>您已享受一杯香浓的幸福时光。把余下的幸福留给未出现的TA吧！</p>
	                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
	            </div>
	        </div>
	      
	        <div class="cd-popup alert3" role="alert">
	            <div class="cd-popup-container">
	                <p>恭喜你抢到1元换购码。</p>
	                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
	            </div> <!-- cd-popup-container -->
	        </div> <!-- cd-popup -->
	        
	        <div class="cd-popup alert4" role="alert">
	            <div class="cd-popup-container">
	                <p>抢码失败。</p>
	                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
	            </div>
	        </div>
	        <div class="cd-popup alert5" role="alert">
	            <div class="cd-popup-container">
	                <p>已经抢完啦。</p>
	                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
	            </div>
	        </div>
	        <div class="cd-popup alert6" role="alert">
	            <div class="cd-popup-container">
	                <p>活动时间还没开始哦。</p>
	                <a href="#" class="cd-popup-close"><img src="<?php echo F::getStaticsUrl('/home/houseConference/'); ?>images/close.png" class="lotteryimg img-replace" /></a>
	            </div>
	        </div>
    	<!-- </div> -->
        <!--弹出框 end--> 
	</body>
	<script>
            function qiang(){
               $('.xbk_btn a').css('z-index',-1);
               // $('.opacity').show();
               $.ajax({
                 type: 'get',
                 url: '/xBKActivity/xBKCode',
                 data: null,
                 dataType: 'json',
                 async: false,
                 error: function () {
                    $('.alert4').addClass('is-visible');
                 },
                 success: function (json) {
                    if (json.suc == 2 && json.is_use == '0'){
                        //已经抢过了
                        location.href = "<?=$oneBuyHref?>&pid=9852";
                        return;
                    }else if(json.suc == 3){
                    	//成功
                        location.href = "<?=$oneBuyHref?>&pid=9852";
                        return;
                    }
                    $('.alert' + json.suc).addClass('is-visible');
                    //if (json.suc == 5) $('.cover_grab,.already_grab').show(); //显示抢完了
                 }
               });          
            }

            $(document).ready(function(){
                //close popup
                $('.alert1, .alert2, .alert3, .alert4, .alert5, .alert6').click(function(event){
                        if( $(event.target).is('.img-replace') || $(event.target).is('.cd-popup') ) {
                                event.preventDefault();
                                $(this).removeClass('is-visible');
                                $('.xbk_btn a').css('z-index',10);
                        }
                });
            });
        </script>
</html>