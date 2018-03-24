<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>完美蟹逅结果页</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/september/shake.css');?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js');?>"></script>
<style type="text/css">
.no_right{
  color:#9c9b9b!important;
}
.lotteryrecord th, .lotteryrecord td {
    width: 27%;
}
</style>
</head> 
 
<body> 
   <div class="phone_contairn">
     <div class="rulepage" style="background:#fff;">
         <div class="rule_head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab1.jpg');?>" class="lotteryimg"/></div>
         <div class="often_content crabresult" style="padding:0;">
          
               <!-- <dt>恭喜金陵天成小区业主陈先生获得5000元红包大奖！</dt>
               <dt>恭喜汇港名苑小区业主赖小姐获得5000元红包大奖！</dt>
               <dt>恭喜高行绿洲四期业主沈**获得了100元红包</dt>
               <dt>恭喜东洲花园业主获得了50元红包</dt>
               <dt>恭喜东洲花园业主获得了50元红包</dt>
               <dt>恭喜135****5783获得了10000元红包</dt> -->
               <?php if(count($listResut)>0){?>
                 <div class="tickerbox">
                      <dl id="ticker">
                      <?php foreach($listResut as $result){ ?>                    
                        <dt><?php echo $result['msg']; ?></dt>
                      <?php } ?>
                      </dl>
                </div>
               <?php } ?> 
           <table class="lotteryrecord">
             <thead>
               <tr>                                                             
                 <th>抢码时间</th>
                 <th>我的兑奖码</th>
                 <th>中奖情况</th>
                 <th>开奖码</th>
               </tr>
             </thead>
             <tbody>
          <!-- 螃蟹 -->
           <?php foreach ($list as $val){?>
              <tr>

              <!-- 抢号时间 -->
              <?php if($val['is_right']==0){?>
               <td>
                   <?php echo $val['lucky_date'] ;?>
               </td>    
               <?php } ?>



               <?php if($val['is_right']==1){?>
               <td class="lotteryed">
                   <?php echo $val['lucky_date'] ;?>
               </td>    
               <?php } ?>


               <?php if($val['is_right']==2){?>
               <td class="no_right">
                   <?php echo $val['lucky_date'] ;?>
               </td>    
               <?php } ?>
               <!-- 抢号时间 -->





               <!-- 我的抽奖号码 -->
                <?php if($val['is_right']==0){?>
                <td>
                   <?php echo $val['mycode'] ;?>
                </td>    
               <?php } ?>



               <?php if($val['is_right']==1){?>
               <td class="lotteryed">
                   <?php echo $val['mycode'] ;?>
               </td>    
               <?php } ?>


               <?php if($val['is_right']==2){?>
               <td class="no_right">
                   <?php echo $val['mycode'] ;?>
               </td>    
               <?php } ?>
               <!-- 我的抽奖号码 -->


               <!-- 中奖情况 -->
               <?php if($val['is_right']==0){?><td>未开奖</td><?php } ?>
               <?php if($val['is_right']==1){?><td class="lotteryed">中奖</td><?php } ?>
               <?php if($val['is_right']==2){?><td class="no_right">未中奖</td><?php } ?>
               <!-- 中奖情况 -->



                <!-- 兑奖码 -->
                <?php if($val['is_right']==0){?><td></td><?php } ?>

                <?php if($val['is_right']==1){?><td class="lotteryed"><?php echo $val['right_result'];?></td><?php } ?>

                <?php if($val['is_right']==2){?><td class="no_right"><?php echo $val['right_result'];?></td><?php } ?>
                <!-- 兑奖码 -->
    
             </tr>
           <?php }?>
          <!-- 螃蟹 -->
             </tbody>
           </table>
           <div class="goback">
             <!-- <a href="/robPerfectCrab">返回</a> -->
             <a href="/luckyApp">返回</a>
           </div>
         </div>
         
     </div>
     
     
     
   </div>
   
  
   
   
  
</body> 

</html>