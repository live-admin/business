<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>梦想大看台</title>
<link href="<?php echo F::getStaticsUrl('/common/css/dreamLook.css'); ?>" rel="stylesheet" />
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
<style type="text/css">
.vote_yjingd_jies {
    float: right;
    height: 37px;
    margin: 20px 5px 0 0;
    text-align: center;
    width: 97px;
}
.vote_yjingd_jies a {
    background: none repeat scroll 0 0 #999;
    border-radius: 3px;
    color: #fff;
    display: block;
    font-size: 16px;
    height: 37px;
    line-height: 37px;
    text-align: center;
    text-decoration: none;
    width: 100%;
    cursor: default;
}
</style>
</head> 
 
<body style="position:relative;">
   <?php if($status==1|| $status==2){?>
   <div class="phone_contairn">
     <div class="vote_head">
       <img src="<?php echo F::getStaticsUrl('/common/images/head.jpg');?>" class="lotteryimg"  />
       <p>本期的<?php echo $count;?>个生日愿望</p>
       <!-- <p>主题:<?php //echo $activityName;?><br/>本期的<?php //echo $count;?>个生日愿望</p> -->
       <a href="/dreamVote/rule">>> 活动详情</a>
     </div>
     <dl class="person_look">

      <?php foreach ($list as $val){?>

       <dd>
         <div class="info">
           <table>
             <tr>
              <a name="label_empId_<?php echo $val->id; ?>" ></a>
               <td style="width:90px; text-align:center;">
                 <a href="/dreamVote/<?php echo $val->id;?>" style="width:80px; display:inline-block;"><img src="<?php echo $val->getEmployeeImgUrl();?>" class="lotteryimg"  /></a>
               </td>
               <td>
                 <a href="/dreamVote/<?php echo $val->id;?>">
                   <i><?php echo $val->EmployeeName;?></i>
                   <span>（<?php echo $val->employee->BranchBYONE;?>）</span>
                 </a><br/>
                 <!-- <span>生日心愿：<?php //echo $val->dream;?></span><br/> -->
                   <i style="font-size:14px;">心愿故事：</i><?php echo F::getStringLength($val->dream)>60?F::subString($val->dream,0,60)."...  ":$val->dream;?><a href="/dreamVote/<?php echo $val->id;?>"><span class="catchynormal">[查看更多]</span></a>
               </td>
             </tr>
           </table>
         </div>
         <div class="vote clearfix">
           <div class="vote_count">
             <span class="catchy"><?php echo strlen($val->votes)>=2?"??".substr($val->votes,-2):"??0".$val->votes;?><?php //echo $val->votes?>票</span>
             <p>累计投票数</p>
           </div>
           <span class="spr"><img src="<?php echo F::getStaticsUrl('/common/images/spr.jpg');?>" class="lotteryimg"  /></span>
           <div class="voting_day">
             <span class="catchy"><?php echo $status==2?"已结束":$day."天";?></span>
             <p>距离投票结束</p>
           </div>

           <?php if($status==1&&$val->flag==0){?>
           <div class="vote_btn">            
            <a href="javascript:doVote(<?php echo $val->id;?>);">投票</a>
           </div>
           <?php } ?>

          
           <?php if($status==1&&$val->flag==1){?>
           <div class="vote_yjingd_jies">            
            <a href="javascript:void(0);">已经投票</a>
           </div>
           <?php } ?>

           <?php if($status==2){?>
           <div class="vote_yjingd_jies">            
            <a href="javascript:void(0);">已结束</a>
           </div>
           <?php } ?>

         </div>
       </dd>

      <?php } ?>
      
     </dl>
     <p class="votebot">已经到最后</p>
   </div>
   <?php }else{ ?>
    <div style="width:300px; margin:0 auto; text-align:left; overflow:hidden;">
      <h3 style="font-size:18px; color:#f00; margin:100px 0 10px ">亲爱的彩子家人们：<br/></h3>
      <p style="text-indent:28px; line-height:180%; font-size:14px;">
        员工圆梦活动“终极PK”投票环节，将于<span style="color:#f00;">2014年12月20日零点</span>正式启动，届时欢迎大家在此投出您宝贵的一票，谢谢。
      </p>
    </div>
   <?php } ?>
   <!--弹出框 start-->
   <div class="opacity" style="display:none;">
     <!--投票 start-->
     <div class="alertcontairn ok" style="display:none;">
       <div class="textinfo">
         您今天已经投过一票，明天再来吧！
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--投票 end-->
     

     <!--投票异常 start-->
     <div class="alertcontairn miss" style="display:none;">
       <div class="textinfo">
         投票程序异常，请联系管理员！
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--投票 end-->
    


     <!--投票成功 start-->
     <div class="alertcontairn success" style="display:none;">
       <div class="textinfo">
         感谢您的支持，我们已收到您的投票！
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeSuceess">确定</a>
       </div>
     </div>
     <!--投票成功 end-->



     <!--投票ip start-->
     <div class="alertcontairn ip_xianzhi" style="display:none;">
       <div class="textinfo" style="padding:50px 5%">
         此IP地址今日已投票数超过3票，如果你用的是无线网，则请更改为自己的移动网络进行投票，谢谢！
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeSuceess">确定</a>
       </div>
     </div>
     <!--投票ip end-->



     
   </div>
   <!--弹出框 end-->
   
   <script type="text/javascript">
      $('.closeOpacity').click(function(){
        $(this).parents('.opacity').hide();
      })

      function doVote(id){
          $.ajax({
            type: 'POST',
            url: '/dreamVote/doVote',
            data: 'actid=<?php echo Item::DREAM_ACT_ID;?>&id='+id,
            dataType: 'json',
            async: false,
            error: function () {
              $('.opacity').show();
              $('.miss').siblings().hide();
              $('.miss').show(1000); 
            },
            success: function (data) {
              if (data.data == 1) {
                $('.opacity').show();
                $('.success').siblings().hide();
                $('.success').show(1000);
                // $('.success').show(1000,showColor);
              } else if (data.data == 2) {
                $('.opacity').show();
                $('.ip_xianzhi').siblings().hide();
                $('.ip_xianzhi').show(1000);
              } else {
                $('.opacity').show();
                $('.ok').siblings().hide();
                $('.ok').show(1000);
              }
            }
          })
      }


      function showColor()
      {
      window.location.href = location.href;
      }


      $('.closeSuceess').click(function(){
        // $(this).parents('.opacity').hide();
        window.location.href = location.href;
      })

   
   </script>
  
</body> 

</html>