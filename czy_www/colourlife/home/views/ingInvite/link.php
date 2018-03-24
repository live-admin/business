<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>上市感恩季</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/invite/hongbao.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
</head>

<body style="background:#fff8e4;">
<div class="hongbao" style="background:#fff8e4;">
  <div class="like_h3"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/h3.gif');?>" class="lotteryimg" /></div>
  <a href="javascript:void(0);" class="blocklink five_y getit"><span>1</span>点击领取5元红包</a>
  <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/img2.gif');?>" class="down" />
  <a href="javascript:void(0);" class="blocklink renshouClick"><span>2</span>点击领取百万泰康意外险</a>
  <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/img2.gif');?>" class="down" />
  <a href="javascript:void(0);" class="blocklink e_weixiu getweixiu"><span>3</span>点击领取E维修代金劵</a>
  <div class="link4 clearfix">
    <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/img2.gif');?>" class="downlittle"  />
    <div class="floatleft"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/img1.gif');?>" class="lotteryimg"  /></div>
    <a href="/luckyApp/inviteLucky?cust_id=<?php echo $cust_id;?>" class="floatright"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/img3.gif');?>" class="lotteryimg"  /></a>
  </div>
  <p class="red" style="font-size:12px; margin-bottom:50px;">活动时间：2015年5月20日至2015年6月17日</p>
  <div class="bot_p">
    <p>★新注册成功用户即可免费获赠10次天天好运抽奖机会</p>
    <p>★注：体验区用户不能参与注册大礼包的领取，活动最终解释权归彩生活所有</p>
  </div>


<!--弹出框 start-->
  <div class="opacity" style="display:none;">

   <!-- 红包模块 start-->
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
         


         <div class="alertcontairn alertcontairn_nosecond" style="display:none;">
           <div class="textinfo">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
             <div class="alertlottery_img"></div>
             <p class="alert_p">红包已经领取过，不能重复领取</p>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
             </div>
           </div>
         </div>


         <div class="alertcontairn alertcontairn_success" style="display:none;">
           <div class="textinfo">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
             <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/alertimg1.gif');?>" class="lotteryimg" /></div>
             <p class="red">恭喜您领取了5元注册红包</p>
             <div class="pop_btn">
               <a href="/ingInvite/invite">邀请好友</a>
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



         <div class="alertcontairn alertcontairn_nosecond21" style="display:none;">
           <div class="textinfo">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
             <div class="alertlottery_img"></div>
             <p class="alert_p">请重新登录后在领取红包</p>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
             </div>
           </div>
         </div>


        
        <div class="alertcontairn alertcontairn_nosecond22" style="display:none;">
         <div class="textinfo">
           <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
           <div class="alertlottery_img"></div>
           <p class="alert_p">请完善您的个人基本信息</p>
           <div class="pop_btn">
             <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
           </div>
         </div>
       </div>

   <!-- 红包模块 end-->






   <!-- E维修代金劵 start-->
    <div class="alertcontairn alertcontairn_lingqusuccess" style="display:none;">
      <div class="textinfo">
         <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
         <div class="alertlottery_img"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/alertimg1.gif');?>" class="lotteryimg" /></div>
         <p class="red">恭喜您领取了E维修20元礼包</p>
         <div class="pop_btn">
            <a href="/ingInvite/invite">邀请好友</a>
         </div>
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
         <p class="alert_p">代金券发放时间已过期</p>
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
         </div>
       </div>
     </div>
  
   <!-- E维修代金劵 end-->

  
  


   <!--泰康人寿 start-->

   <div class="alertcontairn alertcontairn_taikang2" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="renshou">
         <p>请选择您的投保类型（二选一，每个身份证ID和手机号限领一份）</p>
         <ul>
           <li>
             <input type="radio" name="rs_style" id="fcb" value="0" checked="checked" />
             <label for="fcb">飞常保（一年泰康航空意外险，保额100万）</label>
           </li>
           <li>
             <input type="radio" name="rs_style" id="tdb" value="1" />
             <label for="tdb">铁定保（一年泰康铁路意外险，保额50万）</label></li>
         </ul>
         <p>为了确保您能成功领取，请填写以下信息：</p>
         <ul class="info">
           <li><label><b class="ftx04">*</b>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</label><input type="text" id="username" name="username" value="" /><p class="errortip_name" style="color:red;padding-left:65px;"></p></li>
           <li><label><b class="ftx04">*</b>身份证号：</label><input type="text" id="identity" name="identity" value="" /><p class="errortip_identity" style="color:red;padding-left:65px;"></p></li>
           <li><label><b class="ftx04">*</b>手&nbsp;&nbsp;机&nbsp;&nbsp;号：</label><input type="tel" id="mobile" name="mobile" value="" /><p class="errortip_mobile" style="color:red;padding-left:65px;"></p></li>
           <li><label><b class="ftx04">*</b>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;箱：</label><input type="email" id="email" name="email" value="" /><p class="errortip_email" style="color:red;padding-left:65px;"></p></li>
         </ul>
         <p style="text-align:center;">
           <input type="checkbox" id="read" name="shouming" checked="checked"/>
           <label for="read">我已阅读并接受</label>
           <a href="javascript:void(0);" class="tkangxuzhi" style="color:#005aa0;">《泰康人寿投保须知》</a>
         </p>
         <p class="errortip_xieyiQuery" style="color:red; text-align:center;"></p>
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="confirm_btn renshoubtn">确认领取</a>
       </div>
     </div>
   </div>
   


   <div class="alertcontairn alertcontairn_taikang3" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="renshou" style="padding:50px 10px 0">
         <p>您的投保信息已经提交成功，泰康人寿将会在10个工作日内对投保信息进行审核处理；如果免费领取保险成功，泰康保险将会发送成功短信至投保人手机。</p>
       </div>
       <div class="pop_btn">
         <a href="/ingInvite/invite">邀请好友</a>
       </div>
     </div>
   </div>

  <div class="alertcontairn alertcontairn_taikang1" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">您已经领取过泰康人寿保险</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>


   <div class="alertcontairn alertcontairn_taikang5" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">访客体验区不能领取泰康人寿保险</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>



   <div class="alertcontairn alertcontairn_taikang6" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">活动期间注册的用户才可以领取</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>
   
   <div class="alertcontairn alertcontairn100" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">亲~您的邀请战绩太出色，<br/>小二努力数红包中，请稍等。</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>




   <div class="alertcontairn alertcontairn_taikang4" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">您的投保信息提交失败，请联系彩之云值班客服</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
   </div>

   <!--泰康人寿 end-->
  

  <div class="alertcontairn alertcontairn_netWork" style="display:none;">
     <div class="textinfo">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/invite/close.gif');?>" class="closeOpacity" />
       <div class="alertlottery_img"></div>
       <p class="alert_p">您的网络异常，请检查网络后重试</p>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity closeAnother">关闭</a>
       </div>
     </div>
  </div>




  <div class="alertcontairn alertcontairn_alert" style="display:none;">
     <div class="taikang_rulepage">
       <div class="rule_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/taikang_head.jpg');?>" class="lotteryimg"/></div>
       <div class="often_content">
         
         <div class="rule_content">
           <h3>泰康人寿投保须知：</h3>
           <ul>
             <li>1、选择常用的出行工具，飞常保、铁定保只能二选一哦；每个身份证ID和手机号限领一份， 如果自己已经领取，可以为亲朋好友免费投保。</li>
             <li>2、投保地区：本计划仅限在中国大陆（除西藏自治区）有固定居住地的人士投保。</li>
             <li>3、投保年龄：18--80周岁（含）。</li>
             <li>4、投保范围：飞常保承保范围不包括飞机飞行员和机上服务人员；铁定保承保范围不包括列车工作人员。如投保，泰康人寿公司不承担保险责任。</li>             
             <li>5、投保信息提交成功后，泰康人寿将会在3个工作日内对投保信息进行审核处理；如果免费领取保险成功，泰康保险将会发送成功短信至投保人手机，并发送电子保单至投保人邮箱。 </li>
             <li>6、泰康人寿承担的保险责任以所签发保单为准，本保险的最终解释权由泰康人寿保险股份有限公司承担。</li>             
           </ul>
         </div>
         <div class="pop_btn">
            <a href="javascript:void(0);" class="taikang_closeOpacity closeAnother">关闭</a>
         </div>
       </div>
     </div>
  </div>

  
 </div>
<!--弹出框 end-->
  
</div>

<script type="text/javascript"> 


$(function(){
  
$('.renshouClick').click(function(){
    $('.alertcontairn').hide();
    $.post(
      '/ingInvite/taiKangState',
      function (data){
         if(data.state == 1){
             $('.opacity').show();
             $('.opacity').css('position','fixed');     
             $('.alertcontairn_taikang1').show(); 
         }else if(data.state == 0){
             $('.opacity').show();
             $('.opacity').css('position','absolute');
             $('.alertcontairn_taikang2').show();
         }else if(data.state == 2){
             $('.opacity').show();
             $('.opacity').css('position','fixed');
             $('.alertcontairn_taikang5').show();       
         }else if(data.state == 3){
             $('.opacity').show();
             $('.opacity').css('position','fixed');
             $('.alertcontairn_taikang6').show();       
         }else{
             $('.opacity').show();
             $('.opacity').css('position','fixed');
             $('.alertcontairn_netWork').show();            
         }
      }
      ,'json').error(function() {
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_netWork').show();
      });
})
  
$('.closeOpacity').click(function(){
  // $('.opacity').hide();
  $('.opacity,.alertcontairn').hide();  
})
	
  
})
</script>



<!-- 红包&E维修代金劵 -->
<script type="text/javascript">
    $('.getit').click(function(){
      $('.alertcontairn').hide();
      $('.opacity').show();$('.opacity').css('position','fixed');
       getLuckyData();
    });

    $('.getweixiu').click(function(){
      $('.alertcontairn').hide();
      $('.opacity').show();$('.opacity').css('position','fixed');
       getWeiXiuData();
    });


    // $('.pop_btn a').click(function(){
    //   $('.opacity,.alertcontairn').hide();
    // })

    function getLuckyData() { //得到数据
      $.ajax({
        type: 'POST',
        url: '/ingInvite/doSendRedPacket',
        data: 'actid=11&flag=colourlife',
        dataType: 'json',
        async: false,
        error: function () {
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_sendfail').show();//程序异常
        },
        success: function (data) {
          showPackage(data);
        }
      });
    }


    //根据结果弹出红包
    function showPackage(prize) {
      if (prize==0) {//用户不存在或被禁用
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_badcust').show();
      }else if(prize==1){//活动用户不含体验区
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_badcomm').show();
      }else if(prize==2){//用户不是在活动时间内注册
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_regtime').show();
      }else if(prize==3){//红包发放失败
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_sendfail').show();
      }else if(prize==4){//成功发放
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_success').show();
      }else if(prize==5){//活动失效
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_badact').show();
      }else if(prize==6){//红包已经领取过,不能重复领取
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_nosecond').show();
      }else if(prize==21){//请完善您的个人基本信息
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_nosecond21').show();
      }else if(prize==22){//请更改注册名后再领取!
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_nosecond22').show();
      }else if(prize==100){//请更改注册名后再领取!
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn100').show();
      }else{//异常
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_sendfail').show();
      }
    }




    function getWeiXiuData() { //得到数据
      $.ajax({
        type: 'POST',
        url: '/ingInvite/doWeiXiuJuan',
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
      }else if(prize==5){//活动失效
          $('.opacity').show();$('.opacity').css('position','fixed');
          $('.alertcontairn_badact').show();
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



</script>






<!-- 泰康 -->
<script type="text/javascript">    
  var ok1=false;
  var ok2=false;
  var ok3=false;
  var ok4=false;
  var ok5=false;        

 // 验证用户名
$('input[name="username"]').blur(function(){
    if($(this).val()!=''){
        $(this).next().text('');
        ok1=true;
    }else{
        $(this).next().text('请输入姓名');
        ok1=false;
    }
     
});

      
//验证身份证
$('input[name="identity"]').blur(function(){
    if(($.trim($(this).val()).length == 18 || $.trim($(this).val()).length ==15) && $.trim($(this).val())!=''){
        $(this).next().text('');
        $.post(
          '/luckyApp/checkIdentity',
          {'identity':$.trim($(this).val())},
          function (data){
              if(data.pass==0){
                  $('input[name="identity"]').next().text('请输入正确的身份证号码');
              }else if(data.pass==2){                          
                  ok2=true;
              }else{
                  $('input[name="identity"]').next().text('身份证号码有重复');
              }
          }
          ,'json');
        
    }else{
        $(this).next().text('请输入正确的身份证号码');
    }
     
});
 
//验证手机
$('input[name="mobile"]').blur(function(){
    if($.trim($(this).val())!='' && /^1(3|4|5|7|8)\d{9}$/.test($.trim($(this).val()))){
        var mobile = $.trim($(this).val());
        $.post(
          '/luckyApp/checkExistMobile',
          {'mobile':mobile},
          function (data){
              if(data.pass==0){
                  $('input[name="mobile"]').next().text('请输入正确的手机号码');
              }else if(data.pass==2){
                  $('input[name="mobile"]').next().text('');
                  ok3=true;
              }else{
                  $('input[name="mobile"]').next().text('手机号码有重复');
              }
          }
          ,'json');
    }else{
        $(this).next().text('请输入正确的手机号码');
    }
     
});

//验证邮箱
$('input[name="email"]').blur(function(){
    if($(this).val().search(/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/)==-1){
        $(this).next().text('请输入正确的电子邮箱');
    }else{                 
        $(this).next().text('');
        ok4=true;
    }     
});

// 泰康须知打开
$('.tkangxuzhi').click(function(){
  $('.alertcontairn_taikang2').hide();
  $('.opacity').show();$('.opacity').css('position','absolute');
  $('.alertcontairn_alert').show();
  $('.opacity').css('height',$(document).height()+100+'px');
});



// 泰康须知关闭
$('.taikang_closeOpacity').click(function(){
  $('.alertcontairn_alert').hide();
  $('.opacity').show();$('.opacity').css('position','absolute');
  $('.alertcontairn_taikang2').show();
});



//提交按钮,所有验证通过方可提交
$('.confirm_btn').click(
      function(){
        if($('#username').val()==''){
            $('.errortip_name').text('请输入姓名');
        }else{
            ok1=true;
        }

        if($('#identity').val()==''){
            $('.errortip_identity').text('请输入正确的身份证号码');
        }

        if($('#mobile').val()==''){
            $('.errortip_mobile').text('请输入正确的手机号码');
        }

        if($('#email').val()==''){
            $('.errortip_email').text('请输入正确的电子邮箱');
        }

        if($.trim($('input:checkbox').attr('checked'))=='checked'){
          ok5=true;
        }else{
          $('.errortip_xieyiQuery').text('请勾选同意协议');
        }
       
        if(ok1 && ok2 && ok3 && ok4 && ok5){
          var type = $.trim($('input:radio:checked').val());
          var name = $.trim($('#username').val());
          var identity = $.trim($('#identity').val());
          var mobile = $.trim($('#mobile').val());
          var email = $.trim($('#email').val());
         $.post(
              '/luckyApp/doTaiKang_LifeNew',
              {'type':type,'name':name,'identity':identity,'mobile':mobile,'email':email},
              function (data){
                  if(data.pass==1){
                    $('.alertcontairn').hide();
                    $('.opacity').show();$('.opacity').css('position','fixed');
                    $('.alertcontairn_taikang3').show();
                  }
              }
              ,'json'); 
        }else{
          return false;
        }

});
</script>



</body>
</html>
