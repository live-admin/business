<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>领取泰康奖品</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/december/christmas.css');?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>

 <script type="text/javascript">
  
  //弹出框居中函数
   function center(obj) {
    var screenWidth = $(window).width();
    var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
    var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
    var objLeft = (screenWidth - obj.width())/2 ;
    var objTop = (screenHeight - obj.height())/2 + scrolltop;
    obj.css({left: objLeft + 'px', top: objTop + 'px','display': 'block'});
    //浏览器窗口大小改变时
    $(window).resize(function() {
    screenWidth = $(window).width();
    screenHeight = $(window).height();
    scrolltop = $(document).scrollTop();
    objLeft = (screenWidth - obj.width())/2 ;
    objTop = (screenHeight - obj.height())/2 + scrolltop;
    obj.css({left: objLeft + 'px', top: objTop + 'px'});
    });
   
  } 
  
  
   
</script>
</head> 
 
<body style="position:relative;"> 
   <div class="phone_contairn web_contairn">
     <div class="rulepage">
       <div class="rule_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/happiness/rule_top1.jpg');?>" class="lotteryimg"/></div>
       <div class="often_content">
         <div class="result_content">
           <div class="rule_content">
             <h3>请选择您的投保类型<br />（二选一，每个身份证ID和手机号限领一份）</h3>
             <ul>
               <li>
                   <input type="radio" name="rdname" id="rd-1" value="0" checked="checked" />
                   <label for="rd-1">飞常保（一年泰康航空意外险，保额100万）</label>
               </li>
               <li>
                 <input type="radio" name="rdname" id="rd-2" value="1" />
                 <label for="rd-2">铁定保（一年泰康铁路意外险，保额50万）</label>
               </li>
             </ul>
             <h3>为了确保您能成功领取，请填写以下信息：</h3>
             <ul class="person_info">
               <li>
                 <label><b class="ftx04">*</b>姓名：</label>
                 <input type="text" id="username" name="username" value="" />
                 <p class="errortip_name" style="color:red;padding-left:65px;"></p>
               </li>
               <li>
                 <label><b class="ftx04">*</b>身份证：</label>
                 <input type="text" id="identity" name="identity" value="" />
                 <p class="errortip_identity" style="color:red;padding-left:65px;"></p>
               </li>
               <li>
                 <label><b class="ftx04">*</b>手机号：</label>
                 <input type="text" id="mobile" name="mobile" value="" />
                 <p class="errortip_mobile" style="color:red;padding-left:65px;"></p>
               </li>
               <li>
                 <label><b class="ftx04">*</b>邮箱：</label>
                 <input type="text" id="email" name="email" value="" />
                 <p class="errortip_email" style="color:red;padding-left:65px;"></p>
               </li>
             </ul>  
             <div class="toubao">
               <p class="post">
                 <input type="checkbox" id="chk-1" name="shouming" checked="checked"/>
                 <label for="chk-1">我已阅读并接受</label>
                 <a href="javascript:void(0);" class="tkangxuzhi">《泰康人寿投保须知》</a>
               </p>
               <p class="errortip_xieyiQuery" style="color:red; text-align:center;"></p>
               <a href="javascript:void(0);" class="confirm_btn">确认领取</a>
               <a href="/luckyAppWeb" class="closeOpacity guangbi">返回</a>
             </div>
           </div>
         </div>
         
       </div>
     </div>
     
     
     
   </div>
    <!--弹出框 start-->
   <div class="opacity opacityweb" style="display:none;">

     <!--泰康人寿三 start-->
     <div class="alertcontairn alertcontairn_taikang3" style="display:none; background:#fff;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p style="padding:0 5%; text-align:left; text-indent:28px;">您的投保信息已经提交成功,泰康人寿将会在3个工作日内对
                  投保信息进行审核处理;如果免费领取保险成功,泰康保险将会发送成功短信至投保人手机,并发送电子保单至投保人邮箱.
               </p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb" class="jixuyaojiang">返回</a>
         <a href="/luckyAppWeb/happinessResult">我的抽奖记录</a>
       </div>
     </div>
     <!--泰康人寿三 end-->




     <!--泰康人寿四 start-->
     <div class="alertcontairn alertcontairn_taikang4" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>您的投保信息提交失败，请联系彩之云值班客服。
               </p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--泰康人寿四 end-->

     

     <div class="alertcontairn alertcontairn_alert" style="display:none;">
     <div class="taikang_rulepage">
       <div class="rule_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/taikang_head.jpg');?>" class="lotteryimg"/></div>
       <div class="often_content">
         
         <div class="rule_content">
           <h3>泰康人寿投保须知：</h3>
           <ul>
             <li>1、选择常用的出行工具，飞常保、铁定保只能二选一哦；每个身份证ID和手机号限领一份， 如果自己已经
                 领取，可以为亲朋好友免费投保。</li>
             <li>2、投保地区：本计划仅限在中国大陆（除西藏自治区）有固定居住地的人士投保。</li>
             <li>3、投保年龄：18--80周岁（含）。</li>
             <li>4、投保范围：飞常保承保范围不包括飞机飞行员和机上服务人员；铁定保承保范围不包括列车工作人员。
                 如投保，泰康人寿公司不承担保险责任。
             </li>
             <li>5、投保前倾详细阅读保险条款和重要申请（见刮奖结果页面.> 查看【保险说明】），泰康人寿承担的保险
                 责任以所签发保单为准。
             </li>
             <li>6、投保信息提交成功后，泰康人寿将会在3个工作日内对投保信息进行审核处理；如果免费领取保险成功，
                 泰康保险将会发送成功短信至投保人手机，并发送电子保单至投保人邮箱。
             </li>
             <li>7、本保险的最终解释权由泰康人寿保险股份有限公司承担。</li>
             
           </ul>
         </div>
         <div class="goback">
            <a href="javascript:void(0);" class="closeOpacity">关闭</a>
         </div>
       </div>
     </div>
     </div>


     
   </div>
   <!--弹出框 end-->
  
  <script type="text/javascript">

   $('.closeOpacity').live('click',function(){
       $('.opacity').hide();
     $('.opacity>div').hide();  
      
   })  

    
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
                  '/luckyAppWeb/checkIdentity',
                  {'identity':$.trim($(this).val())},
                  function (data){
                      if(data.pass==0){
                          $('input[name="identity"]').next().text('身份证号码格式不正确');
                          ok2=false;
                      }else if(data.pass==2){                          
                          ok2=true;
                      }else{
                          $('input[name="identity"]').next().text('身份证号码有重复');
                          ok2=false;
                      }
                  }
                  ,'json');
                
            }else{
                $(this).next().text('身份证号码格式不正确');
            }
             
        });
 
        //验证手机
        $('input[name="mobile"]').blur(function(){
            if($.trim($(this).val())!='' && /^1(3|4|5|7|8)\d{9}$/.test($.trim($(this).val()))){
                $(this).next().text('');
                var mobile = $.trim($(this).val());
                $.post(
                  '/luckyAppWeb/checkExistMobile',
                  {'mobile':mobile},
                  function (data){
                      if(data.pass==0){
                          $('input[name="mobile"]').next().text('手机格式不正确');
                          ok3=false;
                      }else if(data.pass==2){
                          $('input[name="mobile"]').next().text('');
                          ok3=true;
                      }else{
                          $('input[name="mobile"]').next().text('手机号码有重复');
                          ok3=false;
                      }
                  }
                  ,'json');
            }else{
                $(this).next().text('手机格式不正确');
                ok3=false;
            }
             
        });

        //验证邮箱
        $('input[name="email"]').blur(function(){
            if($(this).val().search(/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/)==-1){
                $(this).next().text('请输入正确的EMAIL格式');
                ok4=false;
            }else{                 
                $(this).next().text('');
                ok4=true;
            }
             
        });

        
     $('.tkangxuzhi').click(function(){

        $('.opacity').show();
        center($('.alertcontairn_alert'));
        // $('.alertcontairn_alert').show();
        $('.opacity').css('height',$(document).height()+100+'px');
     });

    //提交按钮,所有验证通过方可提交
    $('.confirm_btn').click(
          function(){

            if($('#username').val()==''){
                $('.errortip_name').text('请输入姓名');
                ok1=false;
            }else{
                ok1=true;
            }
            
            if($('#identity').val()==''){
                $('.errortip_identity').text('请输入正确的身份证号码');
                ok2=false;
            }

            if($('#mobile').val()==''){
                $('.errortip_mobile').text('请输入正确的手机号码');
                ok3=false;
            }

            if($('#email').val()==''){
                $('.errortip_email').text('请输入正确的电子邮箱');
                ok4=false;
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
              <?php if(isset($lucky_result_id)){?>
              $.post(
                  '/luckyAppWeb/doTaiKang_Life',
                  {'type':type,'name':name,'identity':identity,'mobile':mobile,'email':email,'lucky_result_id':"<?php echo $lucky_result_id;?>"},
                  function (data){
                      if(data.pass==1){
                        $('.opacity').show();
                        center($('.alertcontairn_taikang3'));
                        // $('.alertcontairn_taikang3').show();
                      }else{
                        $('.opacity').show();
                        center($('.alertcontairn_taikang4'));
                        // $('.alertcontairn_taikang4').show();
                      }
                  }
                  ,'json');
             <?php }else{?> 
             $.post(
                  '/luckyAppWeb/doTaiKang_Life',
                  {'type':type,'name':name,'identity':identity,'mobile':mobile,'email':email},
                  function (data){
                      if(data.pass==1){
                        $('.opacity').show();
                        center($('.alertcontairn_taikang3'));
                        // $('.alertcontairn_taikang3').show();
                      }
                  }
                  ,'json'); 
             <?php } ?> 
            }else{
              return false;
            }

    });


   </script>
   
  
</body> 

</html>