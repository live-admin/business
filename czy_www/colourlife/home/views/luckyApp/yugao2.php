<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>预告活动页</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/november/November.css');?>" rel="stylesheet">
 
</head> 
 
<body> 
   <div class="phone_contairn">

     <div class="yugao_part1">
       <img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/n_back.jpg');?>" class="lotteryimg" />
       <p class="link_box">
         <a href="/crab"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/link1.png');?>" class="lotteryimg" /></a>
         <a href="/invite"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/november/link2.png');?>" class="lotteryimg" /></a>
         
       </p>
     </div>
     <div class="yugao_part2">
       <!-- <a href=""><img src="images/advertisement.jpg" class="lotteryimg" /></a> -->
       <?php if($community_id==1 && date('Y-m-d H:i:s',time())>='2014-10-11 23:59:59' && date('Y-m-d H:i:s',time())<='2014-11-12 23:59:59'){?>   
        <a href="/milk" style="display:block; margin-bottom:10px;">
            <img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/820x80.jpg');?>" class="lotteryimg" />
        </a> 
       <?php } ?>
      
       <a href="http://www.oznerwater.com/Api/saleredirect.aspx?<?php echo $completeURL;?>" style="display:block; margin-bottom:10px;"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/666-03.jpg');?>" class="lotteryimg" /></a>
       <a href='http://www.e-zhongjie.com:81/Sceret/zi/Yad.aspx'><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/advertisement.jpg');?>" class="lotteryimg" /></a>
     </div>
     
   </div>
   
  
   
   
  
</body> 

</html>