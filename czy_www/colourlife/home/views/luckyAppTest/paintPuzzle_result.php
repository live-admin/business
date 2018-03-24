<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>中奖结果</title>
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">      
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/paintPuzzle/puzzle.css');?>" rel="stylesheet"> 
</head> 
 
<body>
  <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/lucky/paintPuzzle/jquery.js');?>"></script>
  <script src="<?php echo F::getStaticsUrl('/common/js/lucky/paintPuzzle/gunDong.js');?>"></script>  
   <div class="phone_contairn">
     <div class="rulepage">
       <div class="rule_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/rule_top.jpg');?>" class="lotteryimg"/></div>
       <div class="often_content">
         <div class="result_content">
           <!-- <p style="margin-top:30px; color:red; text-align:left; font-size:14px;">恭喜龙府北郡业主邹喜中5000元大奖<br />恭喜领秀中原业主姚喜中5000元大奖</p> -->
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
                 <?php if($value->prize_id==106||$value->prize_id==89||$value->prize_id==125){ ?>
                   <a class="acolor" href="/luckyApp/christmasShuoMingHuameida">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==107||$value->prize_id==90||$value->prize_id==126) { ?>
                   <a class="acolor" href="/luckyApp/christmasShuoMingQuyuan">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==108||$value->prize_id==91||$value->prize_id==127) {?>
                   <a class="acolor" href="/luckyApp/christmasShuoMingWonderland">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==109||$value->prize_id==92||$value->prize_id==128) { ?>
                    <a class="acolor" href="/luckyApp/christmasShuoMingSanjiaozhou">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==110||$value->prize_id==93||$value->prize_id==136) { ?>
                    <a class="acolor" href="/luckyApp/christmasShuoMingTaihutiancheng">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==111||$value->prize_id==94||$value->prize_id==129) { ?>
                    <a class="acolor" href="/luckyApp/christmasShuoMingHailingdao">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==112||$value->prize_id==95||$value->prize_id==130) { ?>
                    <a class="acolor" href="/luckyApp/christmasShuoMingLizigongguan">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==113||$value->prize_id==96||$value->prize_id==131) { ?>
                    <a class="acolor" href="/luckyApp/christmasShuoMingHaoyahotel">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==114||$value->prize_id==97||$value->prize_id==132) { ?>
                    <a class="acolor" href="/luckyApp/christmasShuoMingFlyvilla">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }elseif($value->prize_id==115||$value->prize_id==98||$value->prize_id==133) { ?>
                    <a class="acolor" href="/luckyApp/christmasShuoMingFengchidao">
                   <?php echo $value->prize->prize_name; ?>
                   </a>
                 <?php }else{?>
                   <?php echo $value->prize->prize_name; ?>
                 <?php } ?>  
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
           <div class="rule_content">
             <h3>温馨提示：</h3>
             <ul>
               <li>1、泰康人寿奖品说明：
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
               <li>2、景点或酒店电子优惠劵，点击下面对应优惠商家查看奖品详情和使用说明：
                   <!-- old -->
                   <!-- <p><a href="">&gt;&gt;&nbsp;深圳豪派特华美达酒店</a></p>
                   <p><a href="">&gt;&gt;&nbsp;深圳趣园私人酒店公寓</a></p>
                   <p><a href="">&gt;&gt;&nbsp;惠州巽寮湾海岛三角洲</a></p>
                   <p><a href="">&gt;&gt;&nbsp;惠州巽寮湾湾凤池岛</a></p>
                   <p><a href="">&gt;&gt;&nbsp;惠州巽寮湾皓雅养生度假酒店</a></p>
                   <p><a href="">&gt;&gt;&nbsp;惠州丽兹公馆</a></p>
                   <p><a href="">&gt;&gt;&nbsp;惠州罗浮山公路度假山庄</a></p>
                   <p><a href="">&gt;&gt;&nbsp;阳江颐景花园彩悦皓雅度假公寓</a></p>
                   <p><a href="">&gt;&gt;&nbsp;苏州太湖天城酒店公寓</a></p>
                   <p><a href="">&gt;&gt;&nbsp;江西婺源清风仙境</a></p> -->
                   <p><a href="/luckyApp/christmasShuoMingHuameida">&gt;&gt;&nbsp;深圳豪派特华美达酒店</a></p>
                   <p><a href="/luckyApp/christmasShuoMingQuyuan">&gt;&gt;&nbsp;深圳趣园私人酒店公寓</a></p>
                   <p><a href="/luckyApp/christmasShuoMingSanjiaozhou">&gt;&gt;&nbsp;惠州巽寮湾海岛三角洲</a></p>
                   <p><a href="/luckyApp/christmasShuoMingFengchidao">&gt;&gt;&nbsp;惠州巽寮湾凤池岛</a></p>
                   <p><a href="/luckyApp/christmasShuoMingHaoyahotel">&gt;&gt;&nbsp;惠州巽寮湾皓雅养生度假酒店</a></p>
                   <p><a href="/luckyApp/christmasShuoMingLizigongguan">&gt;&gt;&nbsp;惠州丽兹公馆</a></p>
                   <p><a href="/luckyApp/christmasShuoMingFlyvilla">&gt;&gt;&nbsp;惠州罗浮山彩别院</a></p>
                   <p><a href="/luckyApp/christmasShuoMingHailingdao">&gt;&gt;&nbsp;阳江颐景花园彩悦皓雅度假公寓</a></p>
                   <p><a href="/luckyApp/christmasShuoMingTaihutiancheng">&gt;&gt;&nbsp;苏州太湖天城酒店公寓</a></p>
                   <p><a href="/luckyApp/christmasShuoMingWonderland">&gt;&gt;&nbsp;江西婺源清风仙境</a></p>
               </li>
               <li>3、红包中奖后立刻发放到红包帐号。
                   <p>红包可用于：缴纳物业费和停车费，预缴物业费，商品交易，手机充值。</p>
                   <p>您可以在"我-我的红包"中查看红包余额。</p>
               </li>
               <li>4、彩生活保留法律范围内对本次活动的所有解释权。</li>
             </ul>
           </div>
         </div>
         <div class="goback">
           <a href="/luckyApp">返回</a>
         </div>
       </div>
     </div>
     
     
     
   </div>   
  
</body> 

</html>