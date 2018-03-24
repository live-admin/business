<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=yes;" />
<title>抢月饼中奖结果</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/august/lottery.css?time=123'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>


</head>

<body style="background:#f7f3e8;position:relative;">
<div class="lottery_topic">     
       <div class="lottery_content">
      <table class="lotteryrecord">
         <thead>
           <tr>                                                             
             <th>抢购时间</th>
             <th>商品</th>
             <th>配送情况</th>
           </tr>
         </thead>
         <tbody>


         
          <!-- 月饼 -->
           <?php foreach ($listMoon as $val){?>
              <tr>
               <td>
                   <?php echo $val['lucky_date'] ;?>                                                       
               </td>
                 <td>
                   <?php echo $val['prize_name']; ?>
                 </td>
               <td <?php echo $val['status']<2?("class='not_getit'"):(''); ?> >
                        <?php echo $val['status']==2?("已领奖"):("未领奖") ;?>  
                        <?php //if($val['status']==0){?>
                          <!-- <div class="pop_btn modify"><a class="modifybtn" href="javascript:addresslink(<?php //echo $val['id']; ?>);" class="addresslink confirm"><span>修改</span></a></div>  -->
                        <?php //} ?>  
               </td>
             </tr>
           <?php }?>
          <!-- 月饼 -->



         </tbody>
      </table>
      <dl class="warmTip">
        <dt></dt>
        <dd style="color:#b20224;">抢购规则：</dd>
        <dd>
         1、第一波3月19日上午11点开始，限量200支，抢完即止；第二波3月19日下午3点开始，限量300支，抢完即止。
        </dd>
        <dd>
         2、本次抢购仅开放给彩科彩悦用户，每个用户限制抢购一支。
        </dd>
        <dd>
         3、抢购结束后，2个工作日内由彩生活客户经理配送到位。
        </dd>
        <dd>
         4、彩生活服务集团享受法律范围的活动最终解释权。
        </dd>
      </dl>
       <div class="lottery_bottom">
        <a href="/newMilk">返回</a>
      </div>
     </div>

 </div>
 
<!--  <div class="opacity" style="display:none;">  -->


    <!--月饼确定收货地址 start-->
    <!-- <div class="tips_contairn" style="display:none;">
        <div class="alertcontairn_content">
            <div class="textinfo" style="text-align:left; color:#505050; border-style: none; padding-top:0;">
              <input type="hidden" id="MoonCakes_result_id" value=""/>  
                <h3>确认您的地址</h3>
                <div class="line" style="border-color:#bca270"></div>
                <p id="colour_address"></p>
                <p><label>姓名：</label><input type="text" class="Tnamenum" id="linkman" defaultTxt="请输入收货人姓名" /></p>
                <p><label>电话：</label><input type="text" class="Tnamenum" id="tel" defaultTxt="请输入电话号码" /></p>
                <p class="errortip" style="color:red; height:20px;"></p>
                <div class="line" style="border-color:#bca270"></div>
                <dl>
                    <dt>温馨提示：</dt>
                    <dd>1、变更或者修改收货人及联系电话请先修改后确认。</dd>
                    <dd>2、收货地址不正确，请在"我-我的账户"中修改。</dd>
                    <dd>3、如果您中奖时没有确认收货信息，可以在"抢月饼结果"中再次确认。</dd>
                </dl>
            </div>
            <div class="line" style="border-color:#bca270"></div>
            <div class="pop_btn">
                <a href="javascript:sure_address();" class="closeOpacity confirm"><span>确定</span></a>
                <a href="javascript:myclose();"  class="closeOpacity confirm"><span>关闭</span></a>
            </div>
        </div>
    </div> -->
    <!--月饼确定收货地址 end-->


<!--  </div>  -->
 <script type="text/javascript">
// (function($) {
//   $.fn.cl_inputDefaultTxt = function(opt) {
//     var _opt = $.extend({}, $.fn.cl_inputDefaultTxt.defaults, opt);
    
//     return this.each(function() {
//       var _this = $(this),
//         _val = _this.val(),
//         _dTxt = _this.attr("defaultTxt");
        
//       _this.focus(function() {
//         var __this = $(this),
//           __val = __this.val();
          
//         if (__val === _dTxt) {
//           __this.val("");
//         }
//       });
      
//       _this.focusout(function() {
//         var __this = $(this),
//           __val = __this.val();
          
//         if (__val === "") {
//           __this.val(_dTxt);
//         }
//       });
//     });
//   };
  
//   $.fn.cl_inputDefaultTxt.defaults = {};
// })(jQuery);

// $(function(){
//   $('.moblie_number').cl_inputDefaultTxt({});  
// });


    
//     function myclose(){
//         $('.opacity').hide();
//         $('.tips_contairn').hide();        
//     }


//     function addresslink(id){
//         $('#MoonCakes_result_id').val(id);      
//         $.post(
//             '/robMoonCakes/getCustomerInfo',
//             {'id':id},
//             function (data){
//                 $('#linkman').val(data.name);
//                 $('#tel').val(data.tel);
//                 $('#colour_address').html(data.address);
//             }
//             ,'json');
//         $('.opacity').show();
//         $('.opacity>div').eq(0).show();
//         $('.opacity>div').eq(0).siblings().hide();
//     }





//     function sure_address(){
//         var id = $('#MoonCakes_result_id').val(); 
//         var linkman = $('#linkman').val();
//         var tel = $('#tel').val();
//         if(linkman ==''){
//            $('.errortip').text('收件人必填');
//            return;
//         }
//         if(tel.length!=11 || isNaN(tel)){
//             $('.errortip').text('不是正确的手机号码');
//             return;
//         }       
//         if(tel==''||tel=='您的手机号码'){
//            $('.errortip').text('号码不能为空');
//            return;
//         }
        

//         $('#MoonCakes_result_id').val(id);      
//         $.post(
//             '/robMoonCakes/getCustomerInfo',
//             {'id':id},
//             function (data){
//                 $('#linkman').val(data.name);
//                 $('#tel').val(data.tel);
//                 $('#colour_address').html(data.address);
//             }
//             ,'json');


        
//         $.post(
//             '/robMoonCakes/fillReceiving',
//             {'id':id,'linkman':linkman,'tel':tel},
//             function (data){
//               if(data == 1){
//                     location.href = "/robMoonCakes/mylottery";
//               }
//             }
//             ,'json');
//     }
 </script>

 </body>
</html> 