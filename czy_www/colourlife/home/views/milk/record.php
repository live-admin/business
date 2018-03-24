<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>邀请列表</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/october/milk.css?time=123456'); ?>" rel="stylesheet" />
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
 
</head> 
 
<body> 
   <div class="milk_contairn">
     <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/october/head.jpg');?>" class="lotteryimg"/></div> 
     <div class="milk_content">
      
       <div class="cord">
         <h3>邀请记录（共邀请了<?php echo count($list)?>位好友）</h3>
         <table>
           <thead>
             <tr>
               <th>邀请时间</th>
               <th>被邀请人手机号</th>
               <th>购买状态</th>
               <th>我的奖励</th>
             </tr>
           </thead>
           <tbody>
            <?php foreach ($list as $value) {?>
             <tr>
               <td><?php echo date('Y-m-d H:i:s',$value->create_time);?></td>
               <td><?php echo $value->mobile;?></td>
               <td><?php echo ($value->is_buy==0 && $value->is_send==0)?"还未购买":"首次购买成功";?></td>
               <td><?php echo ($value->is_buy==1 && $value->is_send==1)?"5元已发放":"未获得红包";?></td>
             </tr>
            <?php } ?> 
           </tbody>
         </table>
         <a href="/milk/invite" class="goback_btn">返回</a>
       </div>
      
     </div>

     
     
   </div>
   
  
   
   
  
</body> 

</html>