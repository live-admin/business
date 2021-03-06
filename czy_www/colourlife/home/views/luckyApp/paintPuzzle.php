<html>
  <head>
    <meta charset="gb2312">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0">
    <title>天天好运</title>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/common/css/lucky/paintPuzzle/jquery.mobile.structure-1.0.css');?>"></link>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/common/css/lucky/paintPuzzle/jquery.mobile-1.0.css');?>"></link>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/common/css/lucky/paintPuzzle/puzzle.css');?>"></link>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/lucky/paintPuzzle/jquery.js');?>"></script>    
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/lucky/paintPuzzle/jigsawplugin.js');?>"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/lucky/paintPuzzle/jquery.mobile-1.0.js');?>"></script>
  </head>
  <body style="min-height:400px;">
    <div data-role="page" id="jpanel">
            <div data-role="head" class="gamehead">
              <img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/head.jpg');?>" style="width:100%; display:block; border:none; margin:0; padding:0;" id="headimg">
              <span id="luckyTodayCan" style="display: none"><?php echo $luckyTodayCan;?></span>
              <p>本次活动总共有<span><?php echo $allJoin; ?></span>人次参加，<br/>你还有<span id="luckyCustCan"><?php echo $luckyCustCan;?></span>次拼图机会。</p>
            </div>
      <div id="gamePanelContainer" class="ui-content" style="margin-top:-5%; position:relative;" data-role="content" role="main">
        <div id="gamePanel" style="margin:0 auto; width:300px;">
                   <img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/1.jpg');?>" style="width:100%; display:block; border:none; margin:0; padding:0;">
                </div>
                <div class="control">
                  <input type="button" data-theme="f" id="start" value="开始"/>
                  <div class="timebox">
                    <p>30'</p>
                  </div>
                </div>
      </div>
            <div class="sublink_box">
              <a rel="external" data-ajax="false" href="/luckyApp/paintPuzzleRule">活动规则</a>
              <a rel="external" data-ajax="false" href="/luckyApp/paintPuzzleResult">我的拼图记录</a>
            </div>
            <div data-role="footer" data-theme="g" class="activerule"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/bottom.jpg');?>" style="width:100%; display:block; border:none; margin:0; padding:0;" /></div>
            
            
    </div>
  <!--弹出框 start-->
  <div class="opacity" style="display:none;">
   <!--超过5次 start-->
   <div class="alertcontairn alertcontairn_dayfive" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               亲~每天只能参与5次拼图哦，明日再战吧！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     
   </div>
   <!--超过5次 end-->
   <!--次数用完 start-->
   <div class="alertcontairn alertcontairn_nochance" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               T^T您的拼图机会已经用完，
             </p>
             <p class="alert_p">
               <a href="" class="lookrule">&gt;&gt;&nbsp; 查看如何获得拼图机会</a>
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     
   </div>
   <!--次数用完 end-->
   <!--未完成拼图 start-->
   <div class="alertcontairn alertcontairn_goto" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               非常遗憾，您未完成拼图，再来一次，练练手吧！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="go_on">继续拼图</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     
   </div>
   <!--未完成拼图 end-->
   
   <!--谢谢参与 start-->
   <div class="alertcontairn alertcontairn_thanks" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               拼图你最行！再来一次，奖品就蹦出来啦！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="go_on">继续拼图</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--谢谢参与 end-->

   <!--泰康人寿1 start-->
   <div class="alertcontairn alertcontairn_life" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               如此神速，你就是拼图能手！<br />获得1份泰康保险<br />
               免费意外险，百万保障、安全出行！。
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/taikanglingqu">领取</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--泰康人寿1 end-->  
  
   <!--88元大奖 start-->
   <div class="alertcontairn alertcontairn_redpacket" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td>
             <p class="alert_p">
               眼疾手快，你就是拼图达人！获得<span style="font-size:16px; color:#10afb1" id="lucky_redpacket"></span>元红包，继续加油！
             </p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="go_on">继续拼图</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--88元大奖 end-->
   <!--电子券-阳江海陵岛颐景 start-->
   <div class="alertcontairn e_coupon alertcontairn_hailingdao" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得阳江海陵岛颐景度假公寓礼包，舒适享受，漫游海陵。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingHailingdao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img1.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_hailingdao"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleHailingdao">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-阳江海陵岛颐景 end-->
   <!--电子券-苏州太湖天成酒店 start-->
   <div class="alertcontairn e_coupon alertcontairn_taihu" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得苏州太湖天成酒店礼包，温润江南，小楼听风雨。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingTaihutiancheng"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img2.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_taihu"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleTaihutiancheng">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-苏州太湖天成酒店end-->
   <!--电子券-趣园私人公寓 start-->
   <div class="alertcontairn e_coupon alertcontairn_quyuan" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得趣园私人公寓大礼包，私人订制，尽享奢华。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingQuyuan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img3.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_quyuan"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleQuyuan">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-趣园私人公寓 end-->
   <!--电子券-婺源清风仙境 start-->
   <div class="alertcontairn e_coupon alertcontairn_wonderland" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得婺源清风仙境畅享礼包，揽怡人美景，享陈酿美酒。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingWonderland"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img4.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_wonderland"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleWonderland">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-婺源清风仙境 end-->
   <!--电子券-巽寮皓雅酒店 start-->
   <div class="alertcontairn e_coupon alertcontairn_haoyahotel" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得惠州巽寮皓雅酒店礼包，海天一色，纵享自然。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingHaoyahotel"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img5.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_haoyahotel"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleHaoyahotel">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-巽寮皓雅酒店 end-->
   <!--电子券-罗浮山飞云山庄 start-->
   <div class="alertcontairn e_coupon alertcontairn_flyvilla" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得惠州罗浮山彩别院礼包，山色美景，尽情畅享。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingFlyvilla"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img6.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_flyvilla"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleFlyvilla">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-罗浮山飞云山庄 end-->
   <!--电子券-惠州丽兹公馆 start-->
   <div class="alertcontairn e_coupon alertcontairn_lizigongguan" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得惠州丽兹公馆大礼包，带给您宾至如归的温馨。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingLizigongguan"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img7.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_lizigongguan"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleLizigongguan">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-惠州丽兹公馆 end-->
   <!--电子券-深圳豪派特华美达 start-->
   <div class="alertcontairn e_coupon alertcontairn_huameida" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得深圳豪派特华美达豪礼包，五星级享受，专业服务。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingHuameida"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img8.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_huameida"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleHuameida">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-深圳豪派特华美达 end-->
   <!--电子券-惠州巽寮三角洲岛 start-->
   <div class="alertcontairn e_coupon alertcontairn_sanjiaozhou" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得惠州巽寮三角洲岛畅游礼包，垂钓浮潜，与海共舞。
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingSanjiaozhou"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img9.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_sanjiaozhou"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleSanjiaozhou">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-惠州巽寮三角洲岛 end-->
   <!--电子券-惠州巽寮凤池岛 start-->
   <div class="alertcontairn e_coupon alertcontairn_fenchidao" style="display:none;">
     <div class="textinfo">
       <table>
         <tr>
           <td colspan="2">
             <p class="alert_p">
               duang~恭喜您成为拼图大师，获得惠州巽寮凤池岛度假村旅行套餐，日暖凤池，与自然零距离
             </p>
             <p class="alert_p" style="margin:10px 0 0 15px"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/boxtips.jpg');?>" class="boxtips"/></p>
           </td>
         </tr>
         <tr>
           <td style="width:65%; padding:0;">
             <a rel="external" data-ajax="false" href="/luckyApp/christmasShuoMingFengchidao"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/paintPuzzle/img10.jpg');?>" class="lotteryimg"/></a>
           </td>
           <td class="yhm">
             <p>优惠码：</p>
             <p id="lucky_fenchidao"></p>
           </td>
         </tr>
       </table>
       <div class="pop_btn">
         <a rel="external" data-ajax="false" href="/luckyApp/christmasRuleFengchidao">使用规则</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
   </div>
   <!--电子券-惠州巽寮凤池岛 end-->
 </div>
 <!--弹出框 end-->   
    <script type="text/javascript">
        var action=true;
        var radom=Math.floor(Math.random()*19+1);
        $('#gamePanel img').attr('src','<?php echo F::getStaticsUrl("/common/images/lucky/paintPuzzle/");?>'+radom+'<?php echo ".jpg";?>');
          $('#gamePanel').css({'background':'url(<?php echo F::getStaticsUrl("/common/images/lucky/paintPuzzle/");?>'+radom+'<?php echo ".jpg";?>) no-repeat','background-size':'contain'});
        function startGame(){
            action=false;
            $('#gamePanel').empty();
            $.jigsaw('gamePanel', '<?php echo F::getStaticsUrl("/common/images/lucky/paintPuzzle/");?>'+radom+'<?php echo ".jpg";?>', 300, 3, 9,30, function(){
               //拼图成功的回调函数
               $('.opacity').show();
               $('#gamePanel').empty();
               getLuckyData();
               action=true;
               // $('.opacity .alertcontairn').eq(6).show();
            },function(){
               //拼图失败的回调函数
               $('.opacity').show();
               $('#gamePanel').empty();
               getBadLuckyData();
               // $('.opacity .alertcontairn').eq(2).show();
               // $('.alertcontairn_goto').show();
               action=true;
        }); 
          
       }




       // old
       //  $('#gamePanel').css({'background':'url(<?php echo F::getStaticsUrl("/common/images/lucky/paintPuzzle/pic1.jpg");?>) no-repeat','background-size':'contain'});
          

       //  function startGame(){
       //      $('#gamePanel').empty();

       //      $.jigsaw('gamePanel', '<?php echo F::getStaticsUrl("/common/images/lucky/paintPuzzle/pic1.jpg");?>', 300, 3, 9,30, function(){
       //         //拼图成功的回调函数
       //         $('.opacity').show();
       //         $('#gamePanel').empty();
       //         $('.opacity .alertcontairn').eq(6).show();
       //      },function(){
       //         //拼图失败的回调函数
       //         $('.opacity').show();
       //         $('.opacity .alertcontairn').eq(2).show();
           
       //  }); 
          
       // }
        
      // old  
      // $('#start').click(function(){
      //   startGame();
      // })
      
      // new
      $('#start').click(function(){
        if(lottery()){
          if(action)startGame();
          // getLuckyData();
        }        
      })
      

      function lottery(){
          var postCan=parseInt($("#luckyTodayCan").text());
          var custCan=parseInt($("#luckyCustCan").text());        
          if(custCan<1){
              $('.opacity').show();
              $('.alertcontairn_nochance').show();
              return false;
          }
          else if(postCan<1){
              $('.opacity').show();
              $('.alertcontairn_dayfive').show();
              return false;
          }else{
             return true;
          }
         
      }



      function getLuckyData() { //得到数据
            $.ajax({
              type: 'POST',
              url: '/luckyApp/doPaintPuzzle',
              data: 'actid=11',
              dataType: 'json',
              async: false,
              error: function () {
                $('.opacity').show();
                $('.alertcontairn_thanks').show();
              },
              success: function (data) {
                var postCan = parseInt($('#luckyTodayCan').text());
                var custCan = parseInt($('#luckyCustCan').text());
                $('#luckyTodayCan').text(postCan-1);
                $('#luckyCustCan').text(custCan-1);
                if (data.success == 0 && data.data.location == 1) {
                  location.href = data.data.href;
                  return;
                }
                if (data.success == 0) {
                  $('.opacity').show();
                  $('.alertcontairn_thanks').show();
                } else {
                  getPrize = data.data.result;
                  showPackage(getPrize);
                }
              }
            });
        }


        function getBadLuckyData() { //得到数据
            $.ajax({
              type: 'POST',
              url: '/luckyApp/doPaintPuzzle',
              data: 'actid=11&flag=colourlife',
              dataType: 'json',
              async: false,
              error: function () {
                $('.opacity').show();
                $('.alertcontairn_goto').show();
              },
              success: function (data) {
                var postCan = parseInt($('#luckyTodayCan').text());
                var custCan = parseInt($('#luckyCustCan').text());
                $('#luckyTodayCan').text(postCan-1);
                $('#luckyCustCan').text(custCan-1);
                if (data.success == 0 && data.data.location == 1) {
                  location.href = data.data.href;
                  return;
                }
                $('.opacity').show();
                $('.alertcontairn_goto').show();
              }
            });
        }



        //根据结果弹出红包
        function showPackage(prize) {        
            var prizeid=parseInt(prize.id);
            if (prizeid==124) {//谢谢参与
                $('.opacity').show();
                $('.alertcontairn_thanks').show();
            } else if (prizeid==134) {//泰康人寿
                $('.opacity').show();
                $('.alertcontairn_life').show();
            } else if(prizeid>=118&&prizeid<=123){//其他红包
                $('.opacity').show();
                $('#lucky_redpacket').html(prize.rednum);
                $('.alertcontairn_redpacket').show();
            } else if(prizeid==135){//黑莓酒
                $('.opacity').show();
                $('.alertcontairn_wine').show();
            } else if(prizeid==125){//华美达优惠码
                $('.opacity').show();
                $('#lucky_huameida').html(prize.lucky_code);
                $('.alertcontairn_huameida').show();
            }else if(prizeid==126){//趣园酒店公寓优惠码
                $('.opacity').show();
                $('#lucky_quyuan').html(prize.lucky_code);
                $('.alertcontairn_quyuan').show();
            }else if(prizeid==127){//清风仙境优惠码
                $('.opacity').show();
                $('#lucky_wonderland').html(prize.lucky_code);
                $('.alertcontairn_wonderland').show();
            }else if(prizeid==128){//海岛三角洲优惠码
                $('.opacity').show();
                $('#lucky_sanjiaozhou').html(prize.lucky_code);
                $('.alertcontairn_sanjiaozhou').show();
            }else if(prizeid==129){//海陵岛优惠码
                $('.opacity').show();
                $('#lucky_hailingdao').html(prize.lucky_code);
                $('.alertcontairn_hailingdao').show();
            }else if(prizeid==130){//惠州丽兹公馆优惠码
                $('.opacity').show();
                $('#lucky_lizigongguan').html(prize.lucky_code);
                $('.alertcontairn_lizigongguan').show();
            }else if(prizeid==131){//皓雅养生度假酒店优惠码
                $('.opacity').show();
                $('#lucky_haoyahotel').html(prize.lucky_code);
                $('.alertcontairn_haoyahotel').show();
            }else if(prizeid==132){//罗浮山公路度假山庄优惠码
                $('.opacity').show();
                $('#lucky_flyvilla').html(prize.lucky_code);
                $('.alertcontairn_flyvilla').show();
            }else if(prizeid==133){//凤池岛优惠码
                $('.opacity').show();
                $('#lucky_fenchidao').html(prize.lucky_code);
                $('.alertcontairn_fenchidao').show();
            }else if(prizeid==136){//苏州太湖天成优惠码
                $('.opacity').show();
                $('#lucky_taihu').html(prize.lucky_code);
                $('.alertcontairn_taihu').show();
            }else{//异常
                $('.opacity').show();
                $('.alertcontairn_thanks').show();
            }
        }





      //判断是否支持触摸事件  
      function isTouchDevice() {  
        try {
          document.createEvent("TouchEvent");  
        }  
        catch (e){
          alert("不支持TouchEvent事件！" + e.message);  
        }  
      }  
      window.onload = isTouchDevice;
      $('.pop_btn a').click(function(){
      $('.opacity').hide();
      $('.alertcontairn').hide();
      radom=Math.floor(Math.random()*19+1);
      $('#gamePanel img').attr('src','<?php echo F::getStaticsUrl("/common/images/lucky/paintPuzzle/");?>'+radom+'.jpg');
      $('#gamePanel').css({'background':'url(<?php echo F::getStaticsUrl("/common/images/lucky/paintPuzzle/");?>'+radom+'.jpg) no-repeat','background-size':'contain'});
      $('.timebox p').css('color','#10afb1').text("30'");
      })
  
    </script>
  </body>
</html>