<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>中奖结果</title>
<link href="<?php echo F::getStaticsUrl('/christmasappweb/css/christmas.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/christmasappweb/js/gunDong.js'); ?>"></script>
 
</head> 
 
<body> 
   <div class="phone_contairn">
     <div class="rulepage">
       <div class="rule_head"><img src="<?php echo F::getStaticsUrl('/christmasappweb/');?>images/rule_top.jpg" class="lotteryimg"/></div>
       <div class="often_content">
         <div class="result_content">
           <p style="margin-top:30px; color:#fff; text-align:left; font-size:16px;">恭喜龙府北郡业主邹喜中5000元大奖<br />恭喜领秀中原业主姚喜中5000元大奖</p>
           <div class="tickerbox">
             <dl id="ticker">
              <?php foreach($listResutl as $result){ ?>  
                <dt><?php echo $result['msg']; ?></dt>
             <?php } ?> 
             </dl>
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
                  <td <?php echo $value->deal_state==0?("class='not_getit'"):(''); ?> >
                        <!-- 55000大奖红包 -->

                        <?php if($value->prize->prize_name == "5000大奖红包"){ 
                          if($value->deal_state!=2){ ?>
                            待审核
                          <?php }else{ ?>
                            已领奖
                          <?php } ?>

                        <!-- 5000大奖红包 -->


                        

                        <!-- 泰康人寿 -->
                        
                        <?php }elseif($value->prize->prize_name == "泰康人寿"){ 
                          
                          if($value->getTaikangLife($value->id)==2){ ?>
                             已提交 
                          <?php } else if($value->getTaikangLife($value->id)==3){?>
                             已过期
                          <?php } ?>   

                          <?php if($value->deal_state!=2 && $value->getTaikangLife($value->id)==1){ ?>
                            未领奖
                            <!-- <div class="modify"><a class="modifybtn" href="javascript:lingqu(<?php echo $value->id; ?>);"><span>修改</span></a></div> -->
                            <div class="modify"><a class="modifybtn" href="<?php echo $this->createUrl('luckyAppWeb/lingqutaikang',array('id'=>$value->id));?>"><span>修改</span></a></div>    
                            
                          <?php }?>
                        <!-- 泰康人寿 -->


                        <!-- 其余红包 -->

                        <?php }else{ 
                          if($value->deal_state!=2){ ?>
                            未领奖
                          <?php }else{ ?>
                            已领奖
                          <?php } }?>

                        <!-- 其余红包 -->



               </td>
             </tr>
           <?php }?>

           


         </tbody>
           </table>
           <div class="rule_content">
             <h3>温馨提示：</h3>
             <ul>
               <li>1、泰康人寿 一年"免费意外保险"奖品说明：
                   <p>（1）"飞常保"和"铁定保"只能二选一，投保前请仔细阅读《泰康人寿投保须知》。</p>
                   <p>（2）理赔和保险责任由泰康保险公司承担，时效为一年，中奖后请注意填写完整的投保信息。</p>
                   <p>（3）中奖人可以为自己或亲朋好友领取此保险，但每一位身份证ID和手机只能领取一份保险名额。</p>
                   <p>（4）投保信息提交成功后，泰康人寿将会在3个工作日内对投保信息进行审核处理；如果投保成功，泰康保险将会发送成功短信至投保人手机，并发送电子保单至投保人邮箱。</p>
                   <p>（5）领奖情况状态说明：</p>
                   <p>未领取：中奖用户未及时提交投保信息，可在中奖后24小时后内有一次机会完善信息领取。</p>
                   <p>已过期：中奖用户未及时提交投保信息，并超过24小时。</p>
                   <p>已提交：中奖用户提交投保信息成功。</p>
                   <p>领取成功：投保成功，获得一年泰康人寿免费意外险。</p>
                   <p>领取失败：投保失败，填写的投保信息已经投保过，不能重复领取；或者填写的投保信息不满足投保条件。</p>
                   <p>（6）本保险的最终解释权由泰康人寿保险股份有限公司承担。</p>
               </li>
               <li>2、黑莓酒奖品领取说明：
                   <p>奖品内容: 彩生活定制黑莓酒一盒，500ml*2。</p>
                   <p>奖品配送：因黑莓酒对运输的要求较高，奖品将统一在活动结束(1月31日)后７个工作日内由客户经理配送到中奖人家里。</p>
               </li>
               <li>3、景点或酒店电子优惠劵，点击下面对应优惠商家查看奖品详情和使用说明：
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=huameida');?>">&gt;&gt;&nbsp;深圳豪派特华美达酒店</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=quyuan');?>">&gt;&gt;&nbsp;深圳趣园私人酒店公寓</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=shanjiaozhou');?>">&gt;&gt;&nbsp;惠州巽寮湾海岛三角洲</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=fengchidao');?>">&gt;&gt;&nbsp;惠州巽寮湾湾凤池岛</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=hao_ya');?>">&gt;&gt;&nbsp;惠州巽寮湾皓雅养生度假酒店</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=lizhi');?>">&gt;&gt;&nbsp;惠州丽兹公馆</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=luofushang');?>">&gt;&gt;&nbsp;惠州罗浮山公路度假山庄</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=yi_jing');?>">&gt;&gt;&nbsp;阳江颐景花园彩悦皓雅度假公寓</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=tai_hu');?>">&gt;&gt;&nbsp;苏州太湖天城酒店公寓</a></p>
                   <p><a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb/view?tpl=qing_feng');?>">&gt;&gt;&nbsp;江西婺源清风仙境</a></p>
               </li>
               <li>4、5000元大奖领取说明：
                   <p>（1）中奖后3个工作日内彩生活客户经理上门核实中奖人的中奖信息和中奖身份（必须是彩生活业主或者是彩生活业主直系亲属）后，系统在一个工作日发放5000元红包到中奖人的红包帐号。</p>
                   <p>（2）彩生活业主证明要求出示：在彩生活服务小区的房产所有证或租赁合同、身份证。</p>
                   <p>（3）彩生活业主直系亲属证明要求出示：与彩生活业主直系亲属关系的户口本、彩生活业主的房产所有证或租赁合同、中奖人身份证及业主身份证。</p>
               </li>
               <li>5、5000元大奖得主可携带家属（包括大奖得主，总共两大一小）出席彩生活年度盛会，年会现场将在公证人员监督下抽取百万现金大奖，且免费提供五星级酒店住宿，外地业主将提供往返交通费。
               </li>
               <li>6、红包可用于：缴纳物业费和停车费，预缴物业费，商品交易，手机充值。</li>
               <li>7、您可以在"我-我的红包"中查看红包余额。</li>
               <li>8、本次活动仅限彩生活业主（彩生活服务小区内的房产所有人或租赁人）及其直系亲属（配偶、父母、子女）参加，彩生活有权对中奖人信息进行身份核实，并保留法律范围内的最终解释权。</li>
             </ul>
           </div>
         </div>
         <div class="goback">
           <a href="<?php echo F::getFrontendUrl(F::getCommunityDomain(),'/luckyAppWeb');?>">返回</a>
         </div>
       </div>
     </div>
     
     
     
   </div>
   
  
   
   
  
</body> 

</html>