<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>首页</title>
		<link href="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>css/gedc.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/'); ?>js/jquery.min.js"></script>
	</head>
	<body>
		<div class="gedc">
			<a id="top"></a>
			<!--头部图片-->
			<div class="head_img">
				<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/head.jpg" />
			</div>

			<!--1元购惊喜-->
			<div class="title text_white">1元购惊喜<span class="text_red">10:00开抢</span>
			</div>

			<!--嘿客与海外直购-->
			<div class="frist_row_img clearfix">
                            <a href="<?=F::getHomeUrl('/thanksgivingActivity/sf')?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_01.png" /></a>
			    <a href="<?=F::getHomeUrl('/thanksgivingActivity/anshi')?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_02.png" /></a>
			</div>

			<!--券券有礼-->
			<div class="title text_white">券券有礼<span class="vouchers">活动规则</span></div>
			<!--领取代金券-->
			<div class="frist_row_img clearfix">
				<img class="service" src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_03.jpg" />
				<img class="rent" src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_04.jpg" />
			</div>

			<!--红包大派送-->
			<div class="title text_white">饭票大派送</div>

			<!--红包大派送-->
			<div class="frist_row_img clearfix">
                            <img class="return_img" src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_06.jpg" />
                            <a href="<?=$pintuHref?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_07.jpg" /></a>
			</div>

			<!--京东热卖-->
                        <div class="title text_white">比京东价低<a href="<?=F::getHomeUrl('/thanksgivingActivity/jd')?>"><span class="text_red">更多热卖</span></a>
			</div>

			<!--热卖商品-->
			<div class="second_row_img clearfix">
				<div class="clearfix">
					<a href="<?=F::getHomeUrl('/thanksgivingActivity/jd')?>">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_10.jpg" />
					</a>
					<a href="<?=F::getHomeUrl('/thanksgivingActivity/jd')?>">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_11.jpg" />
					</a>
					<a href="<?=F::getHomeUrl('/thanksgivingActivity/jd')?>">
						<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_12.jpg" />
					</a>
				</div>
			</div>

			<!--开启财富之旅-->
			<div class="title text_white">开启财富之旅</div>

			<!--投资-->
			<div class="frist_row_img clearfix">
                            <img class="sycxg" src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_08.png" />
                            <a href="<?=$toFriendHref?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/img_09.png" /></a>
			</div>
			<p class="botp">★注：彩生活享有本次活动的最终解释权 </p>
		</div>

		<!--100返5弹出框-->
		<div class="popuo_return return_div" style="display:none;">
			<div class="use_description">
				<div class="colse_btn clearfix">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/close.png" />
				</div>
				<h3>充值规则</h3>
				<p>庆贺彩生活上市一周年，饭票充值立返5%！</p>
				<p>全新饭票充值功能，现在充100得105，充200得210以此类推，6.30限量前1000名抢充。</p>
				<p>周年礼赞，一起红火！</p>
				<p class="use">彩之云饭票，必备省钱利器，直抵现金，京东特供、海外直购、天天团、生鲜速递……统统可用！</p>
				<p>饭票充值请进入：个人中心（如下图）</p>
				<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/alert_return.png" />
				<p class="use_foot padding_bottom">充值完成，拿着饭票去逍遥吧！</p>
			</div>
		</div>

		
                <!--收益创新高-->
		<div class="popuo_return sy_div" style="display:none;">
			<div class="use_description">
				<div class="colse_btn clearfix">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/close.png" />
				</div>
				<h3>收益创新高</h3>
				<p>庆贺彩生活上市一周年，6.30理财收益+1% 股市大跌，余额宝跌破4%, E理财任性逆袭
				</p>

				<p class="use_foot">最受懒人青睐，一元起投，一次解决</p>
				<p>爱定宝</p>
				<p>3个月 5%<span class="text_red">+1%</span>
				</p>
				<p>6个月 8%<span class="text_red">+1%</span>
				</p>
				<p>12个月 10%<span class="text_red">+1%</span>
				</p>

				<p class="use">E理财新成员，一元起投，一月到期
					</br>月利宝 5%<span class="text_red">+1%</span>
				</p>

				<p>仅限6月30日当天成交订单</p>
				<p class="padding_bottom">现在就去约会理财真爱！</p>
                                <div class="go"><a href="<?=$liCaiHref?>"><img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/go.jpg" /></a></div>
			</div>
		</div>

		<!--E维修-->
		<div class="popuo_return service_div" style="display:none;">
			<div class="use_description">
				<div class="colse_btn clearfix">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/close.png" />
				</div>
				<h3>E维修代金券使用说明</h3>
				<p>1.本券金额为20元，用户可用代金劵来抵扣维修费用。
				</p>
				<!--<p class="use_foot">最受懒人青睐，一元起投，一次解决</p>-->
				<p>2.使用规则</p>
				<p class="text_pad">a.本券不可叠加使用，一人只能使用一张。
				</p>
				<p class="text_pad">b.本券已与用户手机号绑定，仅限个人使用，转赠他人无效。
				</p>
				<p>3.查看路径</p>
				<p class="text_pad">Ｅ维修–个人维修–我的–代金劵。</p>
				<p>4.代金券有效时间</p>
				<p class="padding_bottom text_pad">自领取之日起3个月内有效。</p>
				<!--<p class="use">E理财新成员，一元起投，一月到期
					</br>月利宝 5%<span class="text_red">+1%</span>
				</p>-->
			</div>
		</div>

		<!--E租房-->
		<div class="popuo_return rent_div" style="display:none;">
			<div class="use_description">
				<div class="colse_btn clearfix">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/close.png" />
				</div>
				<h3>Ｅ租房住房劵使用说明</h3>
				<p>1.本券金额为100元，用户可用住房券来抵扣房租。
				</p>
				<!--<p class="use_foot">最受懒人青睐，一元起投，一次解决</p>-->
				<p>2.使用规则</p>
				<p class="text_pad">a.本券仅限彩之家新租户或续租客户使用。
				</p>
				<p class="text_pad">b.本券活动期间内只能使用1次。
				</p>
				<p class="text_pad">c.本券不可叠加使用，一人只能使用一张。
				</p>
				<p class="text_pad">d.本券已与用户手机号绑定，仅限个人使用，转赠他人无效。
				</p>
				<p>3.查看路径</p>
				<p class="text_pad">进入E租房-彩伙伴-我的钱包-我的礼品券</p>
				<p>4.住房券有效时间</p>
				<p class="padding_bottom text_pad">自领取之日起一年内有效。</p>
				<!--<p class="use">E理财新成员，一元起投，一月到期
					</br>月利宝 5%<span class="text_red">+1%</span>
				</p>-->
			</div>
		</div>
		<!--券券有礼活动规则弹出框-->
		<div class="popuo_return vouchers_div" style="display:none;">
			<div class="use_description">
				<div class="colse_btn clearfix">
					<img src="<?php echo F::getStaticsUrl('/home/thanksgivingActivity/'); ?>images/close.png" />
				</div>
				<h3>券券有礼活动规则</h3>
				<p>一.E维修代金劵使用说明</p>
				<p>1.本券金额为20元，用户可用代金劵来抵扣维修费用。
				</p>
				<p>2.使用规则 </p>
				<p>a.本券不可叠加使用，一人只能使用一张。
				</p>
				<p>b.本券已与用户手机号绑定，仅限个人使用，转赠他人无效。
				</p>
				<p>3.查看路径：Ｅ维修–个人维修–我的–代金劵。 </p>
				<p>4.代金券有效时间：自领取之日起3个月内有效。</p>

				<p class="use_foot">二.E租房住房劵使用说明：</p>
				<p>1.本券金额为100元，用户可用住房券来抵扣房租。 </p>
				<p>2.使用规则</p>
				<p>a. 本券仅限彩之家新租户或续租客户使用。</p>
				<p>b. 本券活动期间内只能使用1次。 </p>
				<p>c. 本券不可叠加使用，一人只能使用一张。</p>
				<p>d. 本券已与用户手机号绑定，仅限个人使用，转赠他人无效。</p>
				<p>3.查看路径：进入E租房-彩伙伴-我的钱包-我的礼品券</p>
				<p class="padding_bottom">4.住房券有效时间 自领取之日起一年内有效。</p>
				<!--<p class="use">E理财新成员，一元起投，一月到期
					</br>月利宝 5%<span class="text_red">+1%</span>
				</p>-->
			</div>
		</div>
                
                
                
                
                
                
                <div class="opacity" style="display: none;">
                
                    
                    
                        <div class="alertcontairn alertcontairn_sendfail" style="display:none;">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
           <div class="alertlottery_img"></div>
           <p class="alert_p">红包领取失败</p>
           <div class="pop_btn">
             <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
           </div>
         </div>
      </div>

         <div class="alertcontairn alertcontairn_badact" style="display:none;">          
           <div class="textinfo">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
             <div class="alertlottery_img"></div>
             <p class="alert_p">活动失效</p>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
             </div>
           </div>
         </div>

         <div class="alertcontairn alertcontairn_badcust" style="display:none;">
           <div class="textinfo">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
             <div class="alertlottery_img"></div>
             <p class="alert_p">用户不存在或被禁用</p>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
             </div>
           </div>
         </div>

         <div class="alertcontairn alertcontairn_badcomm" style="display:none;">
           <div class="textinfo">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
             <div class="alertlottery_img"></div>
             <p class="alert_p">活动用户不含体验区</p>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
             </div>
           </div>
         </div>

         <div class="alertcontairn alertcontairn_regtime" style="display:none;">
           <div class="textinfo">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
             <div class="alertlottery_img"></div>
             <p class="alert_p">用户不是在活动时间内注册</p>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
             </div>
           </div>
         </div>    
                    
                <!-- E维修代金劵 start-->
                 <div class="alertcontairn alertcontairn_lingqusuccess" style="display:none;">
                   <div class="textinfo">
                      <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
                      <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/alertimg1.gif');?>" class="lotteryimg" /></div>
                      <p class="red">恭喜您领取了代金卷礼包</p>
                    </div>
                 </div>



                  <div class="alertcontairn alertcontairn_nolingqusec" style="display:none;">
                    <div class="textinfo">
                      <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
                      <div class="alertlottery_img"></div>
                      <p class="red">代金劵已经领取,不能重复领取</p>
                      <div class="pop_btn">
                        <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
                      </div>
                    </div>
                  </div>


                  <div class="alertcontairn alertcontairn_badmobile" style="display:none;">
                    <div class="textinfo">
                      <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
                      <div class="alertlottery_img"></div>
                      <p class="alert_p">无效的用户手机号码</p>
                      <div class="pop_btn">
                        <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
                      </div>
                    </div>
                  </div>


                  <div class="alertcontairn alertcontairn_databaseerror" style="display:none;">
                    <div class="textinfo">
                      <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
                      <div class="alertlottery_img"></div>
                      <p class="alert_p">数据操作异常</p>
                      <div class="pop_btn">
                        <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
                      </div>
                    </div>
                  </div>


                  <div class="alertcontairn alertcontairn_juanpass" style="display:none;">
                    <div class="textinfo">
                      <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
                      <div class="alertlottery_img"></div>
                      <p class="alert_p">代金券发放时间还没开始</p>
                      <div class="pop_btn">
                        <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- E维修代金劵 end-->
                
                
                
                
                
                
                
		<!--E装修-->
		<script>
                    
                    
    function getWeiXiuData() { //得到数据
      $.ajax({
        type: 'POST',
        url: '/thanksgivingActivity/doWeiXiuJuan',
        data: 'actid=11&flag=colourlife',
        dataType: 'json',
        async: false,
        error: function () {
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_lingqufail').show();//程序异常
        },
        success: function (data) {
          showWeiXiu(data);
        }
      });
    }

    //根据结果弹出结果
    function showWeiXiu(prize) {
      if (prize==0) {//用户不存在或被禁用
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_badcust').show();
      }else if(prize==1){//活动用户不含体验区
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_badcomm').show();
      }else if(prize==2){//用户不是在活动时间内注册
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_regtime').show();
      }else if(prize==3){//无效的用户手机号码
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_badmobile').show();
      }else if(prize==4){//领取成功
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_lingqusuccess').show();
      }else if(prize==5){
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_juanpass').show();
      }else if(prize==6){//代金劵已经领取,不能重复领取
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_nolingqusec').show();
      }else if(prize==7){//数据操作异常
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_databaseerror').show();
      }else if(prize==8){//代金券发放时间已过期
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_juanpass').show();
      }else{//异常
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_lingqufail').show();
      }
    }

                    
                    
                    
                    
			 //券券有礼
			$('.vouchers').click(function() {
				$('html, body').animate({
					scrollTop: 0
				}, 'slow'); //回到顶部 
				$(".vouchers_div").show();
			});
			 //免费领E维修
			$('.service').click(function() {
                                  $.ajax({
                                    type: 'POST',
                                    url: '/thanksgivingActivity/doWeiXiuJuan',
                                    data: 'actid=11&flag=colourlife',
                                    dataType: 'json',
                                    async: false,
                                    error: function () {
                                      $('.opacity').show();$('.opacity').css('position','fixed');
                                      $('.alertcontairn_lingqufail').show();//程序异常
                                    },
                                    success: function (data) {
                                        if (data ==4){
                                            $('html, body').animate({
                                                    scrollTop: 0
                                            }, 'slow'); //回到顶部 
                                            $(".service_div").show();
                                            return;
                                        }
                                        showWeiXiu(data);
                                    }
                                  });	
			});
                        
                        
                        
                        
                        
                        
			 //免费领E租房
			$('.rent').click(function() {//alert('ok');
                                  $.ajax({
                                    type: 'get',
                                    url: '/thanksgivingActivity/doZuFanJuan',
                                    data: 'actid=11&flag=colourlife',
                                    dataType: 'json',
                                    async: false,
                                    error: function () {
                                      $('.opacity').show();$('.opacity').css('position','fixed');
                                      $('.alertcontairn_lingqufail').show();//程序异常
                                    },
                                    success: function (data) {
                                        if (data ==4){
                                            $('html, body').animate({
                                                    scrollTop: 0
                                            }, 'slow'); //回到顶部 
                                            $(".rent_div").show();
                                            return;
                                        }
                                        showWeiXiu(data);
                                    }
                                  });	
                            return;
                            
                            
				
			});
			 //收益创新高
			$('.sycxg').click(function() {
				$('html, body').animate({
					scrollTop: 0
				}, 'slow'); //回到顶部 
				$(".sy_div").show();
			});
			 //100返5
			$('.return_img').click(function() {
				$('html, body').animate({
					scrollTop: 0
				}, 'slow'); //回到顶部 
				$(".return_div").show();
			});
			 //关闭窗口
			$('.colse_btn').click(function() {
				$(this).parents('.popuo_return').hide();
				$('.gedc').show();
			});
                        
                        $('.closeOpacity').click(function(){
                            $('.opacity,.opacity>div').hide();
                        })
                        
		</script>
	</body>

</html>