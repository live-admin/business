<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>中奖情况</title>

<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/luckyCar/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
</head>

<body>
<div class="lottery_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/luckyCar/lotteryhead.jpg');?>" class="lotteryimg"/></div>
<div class="lottery_topic lotterys_page">
   <div class="lotterys_page_content">
     <div class="lottery_details_link">
       <a href="javascript:void(0);" class="acitverule">中奖情况</a> 
     </div>
     <table class="lotteryrecord">
       <thead>
         <tr>                                                             
           <th>抽奖时间</th>
           <th>奖品</th>
           <th>领奖情况</th>
         </tr>
       </thead>
           <tbody>
             <?php foreach ($list as $value){?>
              <tr>
               <td>
                   <?php echo $value->lucky_date ;?>                                                       
               </td>
               <td>                 
                   <?php echo $value->prize->prize_name; ?> 
                 </td>
                  <td <?php echo ($value->deal_state==0)?("class='not_getit'"):(''); ?> >
                        
                        <!-- 55000大奖红包 -->
                         <?php if($value->prize->prize_name == "5000大奖红包"){ 
                          if($value->deal_state!=2){ ?>
                            待审核
                          <?php }else{ ?>
                            已领奖
                          <?php } ?>
                        <!-- 5000大奖红包 -->                        

                        <!-- 泰康人寿 -->                        
                        <?php }elseif($value->prize->prize_name=="泰康人寿"){
                          if($value->getTaikangLife($value->id)==2){ ?>
                             已提交
                          <?php } else if($value->getTaikangLife($value->id)==3){?>
                             已过期
                          <?php } ?>
                          <?php if($value->deal_state!=2 && $value->getTaikangLife($value->id)==1){ ?>
                             未领奖
                            <!-- <div class="modify"><a class="modifybtn" href="javascript:lingqu(<?php //echo $value->id; ?>);"><span>修改</span></a></div> -->
                            <div class="modify"><a class="modifybtn" href="<?php echo $this->createUrl('luckyApp/taikanglingqu',array('id'=>$value->id));?>"><span>修改</span></a></div>
                          <?php }?>
                        <!-- 泰康人寿 -->


                        <!-- 其余红包 -->
                        <?php }else{ 
                          if($value->deal_state!=2){ ?>
                            未领奖
                          <?php }else{ ?>
                            已领奖<?php if(in_array($value->prize_id,$value->entityList)&&$value->getLuckyShopCode($value->id)!=false){ echo '<br/>优惠码：'.$value->getLuckyShopCode($value->id);}?>
                          <?php } }?>
                        <!-- 其余红包 -->

                   </td>
                 </tr>
              <?php }?>
          </tbody>
      </table>
     <dl class="lottery_tips_box">
       <dt class="tips">★ 温馨提示</dt>
       <dd>1.抽中汽车抽奖码自动参加汽车大奖活动，6月18日开奖，<a href="/luckyApp/carTopic" class="linkcolor">点击了解详情&gt;&gt;</a></dd>
       <dd>2.黑莓酒礼盒／甜蜜红枣／精美小礼品奖品，抽中后将获得对应奖品的1元购特权码（中奖页面直接点击“换购”或前往<a href="<?=$url;?>" class="linkcolor">1元购专区</a>，支付1元即可换购对应奖品）；成功换购15个工作内，由彩生活客户经理配送到家。</dd>
       <dd>3.彩生活服务集团享有本次活动的最终解释权。</dd>
     </dl>
     <p>据苹果公司免责条款特此声明：该活动与苹果公司无关</p>
   </div>
   <div class="lotterybottom"></div>
</div>

</body>
</html>
