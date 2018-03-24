<!DOCTYPE html>
<html>
  <head>
    <meta charset="gb2312">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/common/css/xingrenyouli/xingrenyouli.css');?>"></link>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
  </head>
  <body>
    
    <div class="xingren">
           <div class="head">
             <img src="<?php echo F::getStaticsUrl('/common/images/xingrenyouli/head.jpg');?>" class="lotteryimg" />
             <p style="padding:0 5px; font-size:11px;">4月1日 至4月30日，<br />新注册用户（不含体验区），可获赠新人好礼。</p>
           </div>
           <div class="block">
             <div class="block_link">
               <span class="icon"><img src="<?php echo F::getStaticsUrl('/common/images/xingrenyouli/img1.jpg');?>" class="lotteryimg" /></span>
               <span class="word"><img src="<?php echo F::getStaticsUrl('/common/images/xingrenyouli/word2.gif');?>" class="lotteryimg" /></span>
               <a href="javascript:void(0);" class="getit">点此领取</a>
             </div>
             <p class="block_p">彩之云红包，万能省钱神器！缴物业费、停车费、历史欠费，话费充值、彩之云超市购物、天天团血拼，统统可以抵扣现金！</p>
           </div>
           <div class="xr_content">
              <div class="xr_focus"><img src="<?php echo F::getStaticsUrl('/common/images/xingrenyouli/focus.jpg');?>" class="lotteryimg" /></div>
              <p>来天天好运抽奖赚红包！新注册用户免费获赠10次抽奖机会</p>
              <a href="/luckyApp?cust_id=<?php echo $cust_id;?>" class="clickhere">戳这里</a>
              <p style="margin-bottom:10px;">拼个图，赏个景，红包就到碗里来！</p>
           </div>
           <div class="block">
             <div class="block_link">
               <span class="icon"><img src="<?php echo F::getStaticsUrl('/common/images/xingrenyouli/img2.jpg');?>" class="lotteryimg" /></span>
               <span class="word"><img src="<?php echo F::getStaticsUrl('/common/images/xingrenyouli/word3.gif');?>" class="lotteryimg" /></span>
               <a href="javascript:void(0);" class="getweixiu">点此领取</a>
             </div>
             <p class="block_p">有了易师傅，维修不用愁，代金券在手，还能更省钱哦！（代金券有效期：4月1日至6月30日）</p>
           </div> 
      </div>


      <div class="opacity" style="display:none; position:fixed">
         <div class="alertcontairn alertcontairn_sendfail" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     红包领取失败!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>
         


         <div class="alertcontairn alertcontairn_nosecond" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     红包已经领取过，不能重复领取!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>


         <div class="alertcontairn alertcontairn_success" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     红包领取成功!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>

         <div class="alertcontairn alertcontairn_badact" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     活动失效！
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>

         <div class="alertcontairn alertcontairn_badcust" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                     用户不存在或被禁用!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>

         <div class="alertcontairn alertcontairn_badcomm" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    活动用户不含体验区!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>

         <div class="alertcontairn alertcontairn_regtime" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    用户不是在活动时间内注册!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>
        

        <!-- 代金劵 -->
        <div class="alertcontairn alertcontairn_lingqufail" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    领取失败!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>


        
        <div class="alertcontairn alertcontairn_lingqusuccess" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    E维修代金劵领取成功!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>



         <div class="alertcontairn alertcontairn_nolingqusec" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    代金劵已经领取,不能重复领取!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>


         <div class="alertcontairn alertcontairn_badmobile" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    无效的用户手机号码!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>


         <div class="alertcontairn alertcontairn_databaseerror" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    数据操作异常!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>


         <div class="alertcontairn alertcontairn_juanpass" style="display:none;">
           <div class="textinfo">
             <table>
               <tr>
                 <td>
                   <p class="alert_p">
                    代金券发放时间已过期!
                   </p>
                 </td>
               </tr>
             </table>
             <div class="pop_btn">
               <a href="javascript:void(0);" class="closeOpacity">关闭</a>
             </div>
           </div>
         </div>

    </div>


    <script type="text/javascript">

    $('.getit').click(function(){
      $('.opacity').show();
       getLuckyData();
    });

    $('.getweixiu').click(function(){
      $('.opacity').show();
       getWeiXiuData();
    });



    $('.pop_btn a').click(function(){
      $('.opacity,.alertcontairn').hide();
    })

    function getLuckyData() { //得到数据
      $.ajax({
        type: 'POST',
        url: '/luckyApp/doSendRedPacket',
        data: 'actid=11&flag=colourlife',
        dataType: 'json',
        async: false,
        error: function () {
          $('.opacity').show();
          $('.alertcontairn_sendfail').show();//程序异常
        },
        success: function (data) {
          showPackage(data);
        }
      });
    }


    //根据结果弹出红包
    function showPackage(prize) {
      if (prize==0) {//用户不存在或被禁用
          $('.opacity').show();
          $('.alertcontairn_badcust').show();
      }else if(prize==1){//活动用户不含体验区
          $('.opacity').show();
          $('.alertcontairn_badcomm').show();
      }else if(prize==2){//用户不是在活动时间内注册
          $('.opacity').show();
          $('.alertcontairn_regtime').show();
      }else if(prize==3){//红包发放失败
          $('.opacity').show();
          $('.alertcontairn_sendfail').show();
      }else if(prize==4){//成功发放
          $('.opacity').show();
          $('.alertcontairn_success').show();
      }else if(prize==5){//活动失效
          $('.opacity').show();
          $('.alertcontairn_badact').show();
      }else if(prize==6){//红包已经领取过,不能重复领取
          $('.opacity').show();
          $('.alertcontairn_nosecond').show();
      }else{//异常
          $('.opacity').show();
          $('.alertcontairn_sendfail').show();
      }
    }




    function getWeiXiuData() { //得到数据
      $.ajax({
        type: 'POST',
        url: '/luckyApp/doWeiXiuJuan',
        data: 'actid=11&flag=colourlife',
        dataType: 'json',
        async: false,
        error: function () {
          $('.opacity').show();
          $('.alertcontairn_lingqufail').show();//程序异常
        },
        success: function (data) {
          showWeiXiu(data);
        }
      });
    }


    //根据结果弹出红包
    function showWeiXiu(prize) {
      if (prize==0) {//用户不存在或被禁用
          $('.opacity').show();
          $('.alertcontairn_badcust').show();
      }else if(prize==1){//活动用户不含体验区
          $('.opacity').show();
          $('.alertcontairn_badcomm').show();
      }else if(prize==2){//用户不是在活动时间内注册
          $('.opacity').show();
          $('.alertcontairn_regtime').show();
      }else if(prize==3){//无效的用户手机号码
          $('.opacity').show();
          $('.alertcontairn_badmobile').show();
      }else if(prize==4){//领取成功
          $('.opacity').show();
          $('.alertcontairn_lingqusuccess').show();
      }else if(prize==5){//活动失效
          $('.opacity').show();
          $('.alertcontairn_badact').show();
      }else if(prize==6){//代金劵已经领取,不能重复领取
          $('.opacity').show();
          $('.alertcontairn_nolingqusec').show();
      }else if(prize==7){//数据操作异常
          $('.opacity').show();
          $('.alertcontairn_databaseerror').show();
      }else if(prize==8){//代金券发放时间已过期
          $('.opacity').show();
          $('.alertcontairn_juanpass').show();
      }else{//异常
          $('.opacity').show();
          $('.alertcontairn_lingqufail').show();
      }
    }



    </script>
  </body>
</html>