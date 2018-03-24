<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>喜迎国庆，天天摇大奖活动预告</title>
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/september/shake.css'); ?>" rel="stylesheet">
 
</head> 
 
<body> 
   <div class="phone_contairn">

     <div class="yugao_part1">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/yugao1.jpg');?>" class="lotteryimg yugao1" />
       <p class="ontime">9月18日22点活动开始</p>
     </div>
     <div class="yugao_part2">
       <table>
         <tr>
           <td><a href="/crab"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/yugao2.jpg');?>" class="lotteryimg" /></a></td>
           <td><a href="/invite"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/yugao3.jpg');?>" class="lotteryimg" /></a></td>
         </tr>
       </table>
     </div>
     
     
     
     
   </div>
   
   <script type="text/javascript">
   $(document).ready(function(){
	 function reSize(){
	   var body_height=$(window).height();//获得窗口高度
	   $('.yugao1').css('height',body_height-90+'px');
	   
	 }
	 reSize();
	 $(window).resize(function() {
		 reSize();
	 })
	 
	 
  })
  
   </script>
   
   
  
</body> 

</html>