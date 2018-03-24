<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=yes;" />
<title>查看我的粽子</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>


</head>

<body style="background:#fff;">
<div class="lottery_topic" style="background:#fff;">

     <div class="lottery_content">
      <table class="lotteryrecord">
         <thead>
           <tr>                                                             
             <th>抽奖时间</th>
             <th>奖品</th>
             <th>领奖情况</th>
           </tr>
         </thead>
         <tbody>
           <?php foreach ($list as $_v){ ?>   
           <tr>               
             <td>
                 <?php echo date("Y-m-d",$_v->create_time); ?>                                                       
             </td>
             <td>
                 五芳斋粽子一份
             </td>
             <td <?php if($_v->status == RiceDumplingsResult::STATUS_WAIT){ ?>class="not_getit"<?php } ?>>                 
                 <?php echo $_v->StatusName ?>
                 <?php if($_v->status == RiceDumplingsResult::STATUS_WAIT){ ?>
                    <div class="pop_btn modify">
                        <a class="closeOpacity" href="javascript:showInfo(<?php echo $_v->id; ?>);"><span>修改</span></a>
                    </div>
                 <?php } ?>           
             </td>
           </tr>
          <?php } ?>
         </tbody>
      </table>
      <dl class="warmTip">
        <dt><span><img src="<?php echo F::getStaticsUrl('/common/images/lucky/may/rule1.png'); ?>" /></span><span>抢粽子活动规则: </span></dt>
        <dd>1、活动时间：2014年5月5日至2014年5月28日</dd>
        <dd>2、免费抢粽：
          <ul>
            <li>（1）每天（8点至22点）整点准时开抢</li>
            <li>（2）每次限量开抢，先抢先得</li>
            <li>（3）每天每个用户最多1次抢到粽子的机会</li>
          </ul>
        </dd>
        <dd>3、抢到的礼品粽在抢到后15个工作日内由彩生活客户经理配送到您确认的收货地址。</dd>
        <dd>4、彩生活享有法律范围内的活动最终解释权。</dd>
      </dl>
      <div class="lottery_bottom">
      	 <a href="/robRiceDumplings">返回</a>
      </div>
     </div>
    
    
    <!--弹出框 start-->
   <div class="opacity" style="display:none;">
     <!--收货地址 start-->
     <div class="grab_over" id="grab_over1" style="display:none;">
       <div class="alertcontairn_content">
         <div class="textinfo">
           <h3>确认您的地址</h3>
           <input type="hidden" id="rice_rumplings_result_id" value="" />
           <p id="colour_address"><span >碧水龙庭B栋3单元402</span></p>
           <p><label>姓名：</label><input type="text" id="linkman" value="" /></p>
           <p><label>电话：</label><input type="text" id="tel" value="" /></p>
           <div class="spr_line"></div>
           <p>温馨提示：</p>
           <p>1、变更或者修改收货人及联系电话请先修改后保存。</p>
           <p>2、收货地址不正确，请在“我-我的账户”中修改。</p>
           <p>3、如果您中奖时没有确认收货信息，可以在“我抢到的粽子”中再次确认。</p>
         </div>
         
         <div class="pop_btn">
           <a href="javascript:void(0);" class="closeOpacity" id="sure_address"><span>确定</span></a>
         </div>
       </div>
     </div>
   </div>   
</div>

<script type="text/javascript">
  var h=$(document).height();
  $('.lottery_topic').css('min-height',2*h);    
//弹出框修改地址
function showInfo(id){
    $.post(
        "/luckyApp/getAddressByAjax",
        {'id':id},
        function (data){
            $('#rice_rumplings_result_id').val(id);
            $('#linkman').val(data.name);
            $('#tel').val(data.tel);
            $('#colour_address').html(data.address);
        }
    ,'json');
    $('.opacity').show();
    $('#grab_over1').show();
}


  //确认收货地址
  $('#sure_address').click(         
   function (){
       var id = $('#rice_rumplings_result_id').val();
       var linkman = $('#linkman').val();
       var tel = $('#tel').val();
       $.post(
           '/luckyAppWeb/updateReceiving',
           {'id':id,'linkman':linkman,'tel':tel},
           function (data){
               $('.opacity').hide();
               $('.grab_over1').hide();
           }
       ,'json');
   }
  );

</script>
    
    
</body>
</html>
