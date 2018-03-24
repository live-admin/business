<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>我的抽奖码</title>

<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/luckyCar/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
</head>

<body>
<div class="lottery_topic lotterys_page">
   <div class="lottery_subpage">
     <div class="lotterys_page_content">
       
       <div class="lottery_details_link">
         <a href="javascript:void(0);" class="acitverule">我的抽奖码</a> 
       </div>
      
       <table class="lottery_table">  
        <thead>
         <tr>
           <th>序号</th>
           <th>抽奖码</th>
           <th>获取时间</th>
           <th>中奖情况</th>
         </tr>
        </thead> 
        
        <tbody>
         <?php foreach ($list as $k=>$value){?>
           <tr>
             <td><?php echo $k;?></td>
             <td><?php echo $value->getFullDrawCode($value->mycode);?></td>
             <td><span class="date_time"><?php echo date('Y-m-d',$value->create_time) ;?></span><span><?php echo date('H:i',$value->create_time) ;?></span></td>
             <td>
                <?php
                if($value->is_right==0){
                  echo '未开奖';
                }elseif ($value->is_right==1) {
                  echo '已中奖';
                }elseif ($value->is_right==2) {
                  echo '未中奖';
                }else{
                  echo 'N/A';
                }
                ?>
             </td>
           </tr>
         <?php } ?>
        </tbody>
       </table>
       <dl style="margin-bottom:20px;">
         <dt class="tips">★汽车大奖为：</dt>
         <dd>上海大众 朗逸2015款 1.6L 裸车一台</dd>
       </dl>
       <dl>
         <dt class="tips">★如何开奖？</dt>
         <dd>6月18日的重庆时时彩第一、二期结果组合为开奖码，与开奖码绝对值差距最小的抽奖码为中奖码。如果同时有2个绝对值差距最小，则以第三、四期结果组合为开奖码重新开奖；依次类推。<br />
             示例：如果开奖日是4月25日，则汽车开奖码为：3250367318
         </dd>
         <dd><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/img2.jpg');?>" style="width:155px; display:block; margin:10px auto;"/></dd>
       </dl>
       <dl style="margin-bottom:20px;">
         <dt class="tips">★奖品发放说明：</dt>
         <dd>1.开奖后，中奖者仅需支付1元即可换购汽车。</dd>
         <dd>2.汽车换购成功后，由彩生活客户经理在一个工作日内上门核实身份后，30个工作日内进行奖品配送，届时需要中奖人提供相关购车信息。</dd>
         <dd>3.彩生活业主身份证明：出示在彩生活服务小区的房产所有证或租赁合同、身份证。</dd>
         <dd>4.彩生活业主直系亲属身份证明：出示与彩生活业主直系亲属关系的户口本、彩生活业主的房产所有证或租赁合同、中奖人身份证及业主身份证。</dd>
         <dd>5.本次活动仅限彩生活业主（彩生活服务小区内的房产所有人或租赁人）及其直系亲属（配偶、父母、子女）参加，彩生活有权对中奖人信息进行身份核实，并保留法律范围内的最终解释权。</dd>
       </dl>
       <p>据苹果公司免责条款特此声明：该活动与苹果公司无关</p>
     </div>
     <div class="lotterybottom"></div>
   </div>
   
</div>

 
</body>
</html>
