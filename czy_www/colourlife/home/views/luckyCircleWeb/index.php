<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>幸福中国行-彩之云</title>
<link href="<?php echo F::getStaticsUrl('/common/css/luckyAppWeb.css'); ?>" rel="stylesheet">
<style type="text/css">
.demo{width:417px; height:417px; position:relative; margin:50px auto}
#disk{width:417px; height:417px; background:url(<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/disk.jpg'); ?>) no-repeat}
#start{width:163px; height:320px; position:absolute; top:46px; left:130px;}
#start img{cursor:pointer}
</style>
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/jQueryRotate.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.easing.min.js'); ?>"></script>
<script type="text/javascript">
var a=0;
var p="";
$(function(){ 
     $("#startbtn").click(function(){ 
        lottery(); 
    }); 
}); 
function lottery(){ 
	$("#startbtn").rotate({ 
        duration:20000, //转动时间 
        angle: 180, 
        animateTo:7200, //转动角度 
        easing: $.easing.easeOutSine, 
        callback: function(){
        	 //var an=$("#startbtn").getRotateAngle();
        	 //alert(an);
        } 
    }); 
	
    $.ajax({ 
   	 	type: "POST",
	   	url: "/luckyCircleWeb/doLucky",
	   	data: "actid=2",
	   	dataType:'json',
	   	error:function(){
		   	 var initAngle=parseInt($("#startbtn").getRotateAngle()) ;
				$("#startbtn").rotate({ 
	             duration:3000, //转动时间 
	             angle: initAngle%360, 
	             animateTo:3600+0,
	             easing: $.easing.easeOutSine, 
	             callback: function(){ 
	            	 alert('系统异常');
	             } 
	         }); 
			
	   	},
        success:function(data){ 
		   if(data.success==0 && data.data.location==1){
			   location.href=data.data.href;
			   return;
		   }
		   if(data.success==0){
			   var initAngle=parseInt($("#startbtn").getRotateAngle()) ;
				$("#startbtn").rotate({ 
	                duration:3000, //转动时间 
	                angle: initAngle%360, 
	                animateTo:3600+0,
	                easing: $.easing.easeOutSine, 
	                callback: function(){ 
	                    alert(data.data.msg); 
	                } 
	            }); 
			}else{
				num=data.data.result.index;
				result=data;
	           // $("#startbtn").unbind('click').css("cursor","default"); 
	            a = data.data.result.angle; //角度 
	            p = data.prize; //奖项 
	            var initAngle=parseInt($("#startbtn").getRotateAngle()) ;
	            
				$("#startbtn").rotate({ 
	                duration:3000, //转动时间 
	                angle: initAngle%360, 
	                animateTo:1080+a, //转动角度 
	                easing: $.easing.easeOutSine, 
	                callback: function(){ 
	                    var con = confirm('恭喜你，中得 ['+data.data.result.prize_name+']\n还要再来一次吗？'); 
	                    var nowtAngle=parseInt($("#startbtn").getRotateAngle()) ;
	                    alert("initAngle是："+initAngle+"\t"+"结果角度是："+JSON.stringify(a)+"\t"+"现在的角度是:"+nowtAngle);
	                    if(false){ 
	                    	lottery();
	                    }else{ 
	                        return false; 
	                    } 
	                } 
	            }); 
			}
        } 
    }); 
} 
</script>
</head>

<body>
<div class="lottery_topic">
  
   <div class="topic_head">
     <div class="cloud">
       <div class="bluecloud">
         <div class="cloudtxt">
           <h3>[新年贺喜]</h3>
           <p>
             正月初一至十五，成功注册彩<br />
             之云APP赠送8GU盘或电子体<br />
             温计一个彩生活业主专享。
           </p>
         </div>
       </div>
       <div class="orangecloud">
         <div class="cloudtxt">
           <h3>[天天抽奖]</h3>
           <p>
             1月25日至3月10日，登录APP<br />
             可免费抽奖，任意交易成功再<br />
             获赠一次抽奖！
           </p>
         </div>
       </div>
       <div class="greencloud">
         <div class="cloudtxt">
           <h3>[冬日团购]</h3>
           <p>
             1月31日至3月10日登录APP或<br />
             网站订购夏威夷小木瓜即享超<br />
             值团购优惠还可以额外获赠一<br />
             次免费抽奖。
           </p>
         </div>
       </div>
       <div class="redcloud">
         <div class="cloudtxt">
           <h3>[逢八有礼]</h3>
           <p>
             1月31日至3月10日逢8、18、28
             日在平台成功交易可获赠10斤装
             夏威夷小木瓜一份。
           </p>
         </div>
       </div>
     </div>
   </div>
   <div class="topic">
     <div class="topic_top">
        <p>活动期间，每天登陆彩之云APP，即可获得在线抽奖资格一次，任意成功订单再赠送一次抽奖机会。</p>
     </div>
      <p style="text-align: center;font-size: 14px; text-indent:60px">
      	<?php if($isGuest){?>
       			您还未登录，登录后才可抽奖哦，<span id="luckyCan" style="display: none">1</span>
       		<?php }else{?>
       			今天您还有<span id="luckyCan"><?php echo $luckyCustCan;?></span>次机会试手气，
       		<?php }?>
       		已经有<span><?php echo $allJoin;?></span>人参加抽奖
       		</p>
     <div class="topic_part1">
       <div class="f_left">
          <p class="list_title">活动规则：</p>
          <ul>
            <li>1、活动时间：2014年1月25日-2014年3月18日</li>
            <li>2、活动参与资格：所有彩之云注册用户</li>
            <li>3、抽奖资格说明：</li>
            <li>（1）彩之云用户每天登录APP，获赠一次抽奖
                机会。<br />
                （2）用户在平台上产生成功交易一次，（购买
                商品，成功充值，成功缴纳物业费，停车费），
                获赠一次抽奖机会<br />
                （3）用户抽到幸福奖时，额外获赠一次抽奖机
                会。
            </li>
            <li>4、中奖之后7到15个工作日发放奖品，由您所在小区的彩生活客户经理直接送到您的手上。</li>
            <li>5、彩生活保留法律允许范围内对活动的解释权。</li>
          </ul>
       </div>
       <div class="f_center">
       		<div id="disk"></div>
        	<div id="start"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/start.png'); ?>" id="startbtn"></div>
       </div>
       <div class="f_right">
         <p class="list_title">最新中奖用户</p>
         <div class="lotteryer" id="lotteryer">
            <ul>
            </ul>
         </div>
       </div>
      

     </div>
     <div class="topic_part2">
        <div class="part2_ctt">
           <div class="part2_left">
             <div class="p2_l_top">
               <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img4.png'); ?>" width="312" height="98"/>   
               <p>彩生活精选夏威夷小木瓜<br />夏威夷原种、百分百原生态、木瓜中的上品。</p> 
               <a href="http://ckcyds.c.colourlife.com/goods/669" target="_blank" class="atonce_pay">
                 <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/payBtn.jpg'); ?>" width="101" height="30"/>
               </a>         
             </div>
           </div>
           <div class="part2_right">
             <div class="p2_r_top">
               <a href="http://ckcyds.c.colourlife.com/goods/668" target="_blank"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img5.png'); ?>" width="282" height="187"/></a>  
               <p>【品名】：夏威夷小木瓜</p> 
               <p>【规格】：5斤礼品装/10斤礼品装</p>
             </div>
             
           </div>
        </div>
     </div>
     <div class="topic_part2">
        <div class="part3_ctt">
          <h3>天天团.特惠商品推荐</h3>
          <ul>
            <li>
              <h4>江山&nbsp;&nbsp;蜂王礼盒</h4>
              <div class="part3_proimg">
                <a href="http://bslt.c.colourlife.com/goods/378" target="_blank"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img7.png'); ?>" width="188" height="163" class="p3_img" /></a>
                <span class="caozhao"></span>
                <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/price1.png'); ?>" class="priceimg" />
              </div>
            </li>
            <li>
              <h4>富锦&nbsp;&nbsp;山珍海味组合</h4>
              <div class="part3_proimg">
                <a href="http://bslt.c.colourlife.com/goods/401" target="_blank""><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img6.png'); ?>" width="188" height="163" class="p3_img" /></a>
                <span class="caozhao"></span>
                <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/price2.png'); ?>" class="priceimg" />
              </div>
            </li>
            <li class="p3_lastli">
              <h4>富安娜&nbsp;&nbsp;床品四件套（花开茶扉）</h4>
              <div class="part3_proimg">
                <a href="http://bslt.c.colourlife.com/goods/372" target="_blank"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img8.png'); ?>" width="325" height="210" class="p3_img" /></a>
                <span class="caozhao"></span>
                <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/price3.png'); ?>" class="priceimg" />
              </div>
            </li>
          </ul> 
        </div>
     </div>
     <div class="bot"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/bot.jpg'); ?>" width="974" height="54"/></div>
     
  </div>
  
</div>
</body>
</html>
