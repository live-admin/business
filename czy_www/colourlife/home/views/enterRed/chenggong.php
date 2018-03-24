<!DOCTYPE html>
<html>
  <head>
      <meta charset="gb2312">
      <meta name="viewport" content="width=device-width, initial-scale=1"> 
      <meta name="format-detection" content="telephone=no" />
      <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/enterred/css/ruhuo.css');?>"></link>
      <script type="text/javascript" src="<?php echo F::getStaticsUrl('/enterred/js/jquery.js');?>"></script>
      <style>
          .ruhuo a{color: #FFF;}
          .money .error{font-size: 25px;}
          
      </style>
  </head>
  <body>
      <div class="ruhuo" style="color:#FFF;">
      <div><img src="<?php echo F::getStaticsUrl('/enterred/images/head.jpg');?>" class="lotteryimg" /></div>   
      <div class="ruhuo_contairn">
          <dl class="ruhuo_content" >
           <!--<dt class="ruhuo_title">恭喜您入伙成功并获得一次抽奖机会！</dt>-->
           <dd>
               <p class="ruhuo_title" style="font-size:30px">恭喜您成功申请验房</p>
<!--             <img src="<?php echo F::getStaticsUrl('/enterred/images/packet.png');?>" title="<?php echo $mobile;?>"/>
             <p class="money" style="display:none;"></p>-->
           </dd>
         </dl>
      </div>
      <div class="ruhuo_bottom">
        <table>
          <tbody>
            <tr>
<!--                <td style="width:100%;text-align:center">
                    <p class="sign"><a href="<?php //echo $reurl;?>" style="font-size:35px;">马上签约</a></p>
                <td>-->
              <td>
                  <a href="<?php echo $zhuangxiu_url.'&reurl=/sq.aspx'?>" target="_blank" >
                    <img src="<?php echo F::getStaticsUrl('/enterred/images/img1.jpg');?>" />
                    <p>我拟装修</p>
                  </a>
              </td>
              <td>
                <a href="<?php echo $zufang_url;?>" target="_blank" >
                    <img src="<?php echo F::getStaticsUrl('/enterred/images/img2.jpg');?>" />
                    <p>我拟租房</p>
                </a>
              </td>
              <td>
                <a href="<?php echo $daikuan_url;?>" target="_blank">
                    <img src="<?php echo F::getStaticsUrl('/enterred/images/img3.jpg');?>" />
                    <p>我拟贷款</p>
                </a>
              </td>
              <td>
                  <a href="javascript:void(0);" class='lingqu_oneyuan'>
                    <img src="<?php echo F::getStaticsUrl('/enterred/images/img4.jpg');?>" title="<?php echo $mobile;?>"/>
                    <p>领取一元购码</p>
                  </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
<script>
$(function(){
    $(".ruhuo_content img").click(function(){
        var ruhuo = $(this); 
        var mobile = ruhuo.attr("title"); 
        $.ajax({ 
            type:"POST",
            url:"/EnterRed/checkRedPack",
            data:"mobile="+mobile,
            dataType:'json',
            cache:false,
            success:function(data){
                if(data.success==1){
                    ruhuo.unbind('click');
                    ruhuo.attr('src','<?php echo F::getStaticsUrl('/enterred/images/open_packet.png');?>');
                    $('.money').show();
                    $('.money').append(data.amount+"<span>元</span>");
                }else{
                    ruhuo.unbind('click');
                    ruhuo.attr('src','<?php echo F::getStaticsUrl('/enterred/images/open_packet.png');?>');
                    $('.money').show();
                    $('.money').append("<span class='error'>"+data.msg+"<span>");
                } 
            } 
        }); 
        return false; 
        
    });


    $(".lingqu_oneyuan img").click(function(){
        var ruhuo = $(this); 
        var mobile = ruhuo.attr("title"); 
        $.ajax({
          type: 'POST',
          url:"/EnterRed/sendCode",
          data:"mobile="+mobile,
          dataType:'json',
          cache:false,
          async: false,
          error: function () {
            location.href = '/EnterRed/change?mobile='+mobile;
            return;
          },
          success: function (data) {
            location.href = '/EnterRed/change?mobile='+mobile;
            return;
          }
        });        
    });

});
</script>

