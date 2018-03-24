<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
<meta name="MobileOptimized" content="240"/>
<title>完美蟹逅</title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/september/shake.css');?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js');?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/jQueryRotate.js');?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/gunDong.js');?>"></script>
<script type="text/javascript">
  
  //弹出框居中函数
	 function center(obj) {
	  var screenWidth = $(window).width();
	  var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
	  var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
	  var objLeft = (screenWidth - obj.width())/2 ;
	  var objTop = (screenHeight - obj.height())/2 + scrolltop;
	  obj.css({left: objLeft + 'px', top: objTop + 'px','display': 'block'});
	  //浏览器窗口大小改变时
	  $(window).resize(function() {
	  screenWidth = $(window).width();
	  screenHeight = $(window).height();
	  scrolltop = $(document).scrollTop();
	  objLeft = (screenWidth - obj.width())/2 ;
	  objTop = (screenHeight - obj.height())/2 + scrolltop;
	  obj.css({left: objLeft + 'px', top: objTop + 'px'});
	  });
	 
	} 
	
	$('.closeOpacity').live('click',function(){
	  $('.opacity').hide();
	  $('.opacity>div').hide();	
		
	})
   
</script>
<style type="text/css">
.web_crab .lottery_crab{
  height: auto;
}
</style>

</head> 
<?php
    $domain = F::getCommunityDomain();
    $domain  = empty($domain)?'ckcyds':F::getCommunityDomain();

?> 
<body style="position:relative;"> 
   <div class="web_crab">
      <div class="web_shake_content">
        <!--摇号码start-->
        <div class="crab">
           <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab1.jpg');?>" class="lotteryimg" /></div>
           <div class="lottery_crab">
             <img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab2.jpg');?>" class="lotteryimg" />
             <div class="lottery_crab_content">

             <div class="mn_part">
                
                <p style="margin-top:20px;">
                    <span id="coloulife_refush2"><span class="nexttime" id="colourlife_span"><?php echo  ($status==1||$status==3)&&$timeRemainingNext?"下一场：".date("d日H点",strtotime($timeRemainingNext))."准时开启":"本次活动已全部结束"; ?></span>
                </p>

               
                <div class="residue_time">
                  <p style="display: none;">
                      <span class="year">2014</span>
                      <span class="month"><?php echo $m; ?></span>
                      <span class="day"><?php echo $d; ?></span>
                      <span class="colourlife_h"><?php echo $h; ?></span>
                      <span class="colourlife_i"><?php echo $i; ?></span>
                      <span class="colourlife_s"><?php echo $s; ?></span>
                  </p>

                   <div class="time_p">
                     <p>                    
                        <?php if($status==1){?>
                            <span id="coloulife_refush">距离活动开始</span> 还有：<span class="hour"><?php echo $h; ?></span>小时<span class="minute"><?php echo $i; ?></span>分<span class="sec"><?php echo $s; ?></span>秒
                        <?php }?>

                        <?php if($status==3){?>
                            <span id="coloulife_refush">距离本场活动结束</span> 还有：<span class="hour"><?php echo $h; ?></span>小时<span class="minute"><?php echo $i; ?></span>分<span class="sec"><?php echo $s; ?></span>秒
                        <?php }?>

                        <?php if($status==2){?>
                            <span id="coloulife_refush">活动已全部结束</span>
                        <?php }?>                    
                      </p>
                    <p>已有<span><?php echo $allJoin;?></span>位业主参与此活动</p>
                   </div>
                </div> 
            </div> 


                <a href="javascript:void(0);" class="lottery_crab_btn"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab_lottery1.png');?>" class="lotteryimg" /></a>
                <div class="crablink">
                   <a href="#crabrules">活动规则</a>
                   <span class="spar">|</span>
                   <a href="/luckyAppWeb/perfectCrabResultWeb">抽奖结果</a>
                </div>
             </div>
           </div>
           
        </div>
        <!--摇奖 end-->



        <div class="c_rule">
          <div><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/web_c_rule.jpg');?>"/></div>  
          <div class="rule_content">
           <dl>
            <a name="crabrules"></a>
             <dt>活动规则：</dt>
             <dd>1、活动时间：9月18日至9月30日，一天一场次(每天0点开始，23: 59分结束)</dd>
             <dd>2、用户需要使用0.1元红包购买抽奖机会，购买成功后系统随机发放5位数字作为兑奖码。</dd>
             <dd>3、每场次结束次日内开奖，以每场次结束后次日"重庆时时彩"的第一期5位数字为开奖码。</dd>
             <dd>4、兑奖码与开奖码相差最少（取绝对值）的， 则为中奖号码；绝对值相同的，则同时中奖。</dd>
             <dd>5、用户可以在"抽奖结果"页面查看中奖情况，开奖后由彩生活客服联系中奖人配送奖品。</dd>
             <dd>6、所有用户都可以参加（包括1778体验区用户），奖品的配送方式是顺丰快递，开奖后需要中
              奖人提供顺丰可达的配送地址。
             </dd>
             
           </dl>
           <h3>活动奖品由蟹状元商家提供：</h3>
           <div class="crabimg"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab_rule2.jpg');?>" class="lotteryimg"/></div>
           <div class="crabimg"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab_rule3.jpg');?>" class="lotteryimg"/></div>
           <div class="crabimg"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab_rule4.jpg');?>" class="lotteryimg"/></div>
           <div class="crabimg"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab_rule5.jpg');?>" class="lotteryimg"/></div>
           <div class="crabimg"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/crab_rule6.jpg');?>" class="lotteryimg"/></div>
           <h3>3种提货方式， 24小时顺丰闪电发货：</h3>
           <ul>
             <li>1、在线提蟹：http://www.topcrab.com/</li>
             <li>2、电话提蟹：400-807-9777</li>
             <li>3、门店提蟹：可通过以下地区：上海、北京、天津、武汉、成都、长沙、广州、深圳、
              西安、重庆、济南旗舰店提货</li>
           </ul>
           <h3>本商家在售套餐推荐</h3>
           <div class="crab_style_list clearfix">
             <div class="crab_style_left"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/september/img4.jpg');?>" class="lotteryimg"/></div>
             <ul class="crab_style_right">
               <li><a href="http://<?php echo $domain;?>.c.colourlife.com/goods/1299">
                 <span class="crab_s_cheapprice">特惠价：268元</span>
                 <span class="crab_s_normalprice">498型至尊礼盒(10只)</span></a>
               </li>
               <li><a href="http://<?php echo $domain;?>.c.colourlife.com/goods/1300">
                 <span class="crab_s_cheapprice">特惠价：348元</span>
                 <span class="crab_s_normalprice">688型至尊礼盒(10只)</span></a>
               </li>
               <li><a href="http://<?php echo $domain;?>.c.colourlife.com/goods/1301">
                 <span class="crab_s_cheapprice">特惠价：448元</span>
                 <span class="crab_s_normalprice">888型至尊礼盒(10只)</span></a>
               </li>
               <li><a href="http://<?php echo $domain;?>.c.colourlife.com/goods/1302">
                 <span class="crab_s_cheapprice">特惠价：598元</span>
                 <span class="crab_s_normalprice">1198型至尊礼盒(10只)</span></a>
               </li>
             </ul>
           </div>
         </div>
         
        </div>
        
      </div>
   
   </div>
   
   
    <!--弹出框 start-->
   <div class="opacity opacityWeb" style="display:none;">


   <!--扣钱提示 start-->
     <div class="alertcontairn alertcontairn_tip" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>参与本次抽奖将扣除您<b><span>0.1元</span></b>红包,是否继续?</p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="javascript:rob()" class="rob">继续</a>
         <a href="javascript:void(0);" class="closeOpacity">取消</a>
       </div>
     </div>
     <!--扣钱提示 end-->





     <!--没钱了 start-->
     <div class="alertcontairn alertcontairn_nored" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>
                 您已经没有红包了，下次再来玩吧<br />
                 <a href="/luckyAppWeb#yaodajiang" class="get_money">&gt;&gt; 摇大奖，领红包</a>
               </p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/perfectCrabResultWeb">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--没钱了 end-->





     <!--活动未开始 start-->
     <div class="alertcontairn alertcontairn_nostart" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>
                 活动尚未开始，敬请期待!             
               </p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--活动未开始 end-->



     <!--未获取兑奖码 start-->
     <div class="alertcontairn alertcontairn_no" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p class="miss">很遗憾，您没有获取到本次兑奖码</p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="robPerfectCrab/perfectCrabResultWeb">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--未获取兑奖码 end-->





      <!--兑奖码正确结果 start-->
     <div class="alertcontairn alertcontairn_ok" style="display:none;">
       <div class="textinfo">
         <table>
           <tr>
             <td>
               <p>您本次兑奖码<span class="result_span"></span><br /><br />
                  获奖结果将在次日11:00公布，记得查看喔！
               </p>
             </td>
           </tr>
         </table>
       </div>
       <div class="pop_btn">
         <a href="/luckyAppWeb/perfectCrabResultWeb">我的抽奖记录</a>
         <a href="javascript:void(0);" class="closeOpacity">关闭</a>
       </div>
     </div>
     <!--兑奖码正确结果 end-->




     
     
     <!--摇奖 start-->
     <div class="alertcontairn alertcontairn_lottery" style="display:none;">
       <div class="lotry_number">
           <span>1</span>
           <span>2</span>
           <span>3</span>
           <span>4</span>
           <span>5</span>
       </div>
       <input type="hidden" class="hiddennumber" alt="" /><!--隐藏数字-->
     </div>
     <!--摇奖 end-->



     
     
   </div>
   <!--弹出框 end-->
   
   <script type="text/javascript">
     var hiddenNum=0;//用来放隐藏数字。
   $('.lottery_crab_btn').live('click',function(){    
     $('.opacity').show();
     center($('.alertcontairn_tip'));
   })
   
   $('.closeOpacity').live('click',function(){
       $('.opacity').hide();
     $('.opacity>div').hide();  
      
   })
   
   var timeout,timeout_two,j=0;
   
   function randNum(elem,count,region){
    //alert(region);return;
    var Num=$(elem);
    var arrNum = [];
    var i = 0;
    for(var i = 0; i < count; i++){
      arrNum.push(i);
    } 
    Num.each(function(){//给每个文本框赋随机值;
      index = arrNum[Math.floor(Math.random() * count)];
      $(this).text(index);
    }); 
    
    
    if( ++j > 50){//闪动十次后停止;
      if(hiddenNum||hiddenNum!='0'){//当隐藏数组不为空时执行下面的条件语句操作,条件语句里显示出该的数组。结束滚动数字;否则继续滚动
      $('.hiddennumber').attr('alt','');
      Num.each(function(index,item){//给每个文本框赋随机值;
        index = hiddenNum[index];
        $(this).text(index);
      }); 
      clearTimeout(timeout);
      timeout_two=setTimeout('resultShow('+region+')',2000);//停个两秒后，隐藏抽奖框，显示抽奖结果框
        return;
      }
      else{     
      clearTimeout(timeout);
      Num.text('X');
      timeout_two=setTimeout('resultShow('+region+')',2000);
       }
       return;
    }
    timeout=setTimeout(function(){randNum($(".lotry_number span"),10,region)},50);
    
    }
    
    function resultShow(region){//隐藏抽奖框，显示抽奖结果框
      j=0;
    $('.alertcontairn_lottery').hide();
    $('.result_span').text(hiddenNum);
    if(region==0 || region==2 || region==5){////程序0没抢到(没扣红包)；1抢到了；2抢光了(号码不够)；3红包金额不足 4活动未开始 5没抢到(扣了红包)
      center($('.alertcontairn_no'));
    }else if(region==1){
      center($('.alertcontairn_ok'));
    }else if(region==3){
      center($('.alertcontairn_nored'));
    }else if(region==4){
      center($('.alertcontairn_nostart'));
    }else{
      center($('.alertcontairn_no'));
    }
    clearTimeout(timeout_two);
    }
   
  
</script>


<script type="text/javascript">
$(function(){
    //抢购倒计时
    function CountDown() {
        this.y;
        this.d;
        this.h;
        this.hr;
        this.m;
        this.s;
        this.startTime;
        this.obj;
        this._self;
    }

    //初始化对象
    CountDown.prototype.init = function () {
        this.startTime.setFullYear(this.y, this.d, this.h);
        this.startTime.setHours(this.hr);
        this.startTime.setMinutes(this.m);
        this.startTime.setSeconds(this.s);
        this.startTime.setMilliseconds(999);
        this.EndTime = this.startTime.getTime();
        this._self = this;
        this.GetRTime();
    }

    CountDown.prototype.GetRTime = function () {
        var NowTime = new Date();
        var nMS = this.EndTime - NowTime.getTime();
        var nH = Math.floor(nMS / (1000 * 60 * 60));
        var nM = Math.floor(nMS / (1000 * 60))%60;
        var nS = Math.floor(nMS / 1000) % 60;
        if (nMS > 0) {
            this.obj.find(".hour").text(nH);
            this.obj.find(".minute").text(nM);
            this.obj.find(".sec").text(nS);
        }
        else{
            this.obj.find(".hour").text("0");
            this.obj.find(".minute").text("0");
            this.obj.find(".sec").text("0");
        }
        var _self = this;
        window.setTimeout(function (){_self.GetRTime();}, 1000);
    }
    var ObjArray = new Array();
    var TmpObject = new CountDown();
    function activeTime(year,month,day,hour,minute,second){
        month=(month)?month-1:month;
        $(".residue_time").each(function () {
            TmpObject.startTime = new Date();
            TmpObject.y = year||parseFloat($(this).find(".year").text());
            TmpObject.d = month||parseFloat($(this).find(".month").text())-1;
            TmpObject.h = day||parseFloat($(this).find(".day").text());
            TmpObject.hr = hour||parseFloat($(this).find(".hour").text())||0;
            TmpObject.m = minute||parseFloat($(this).find(".minute").text())||0;
            TmpObject.s = second||parseFloat($(this).find(".second").text())||0;
            TmpObject.obj = $(this);
            var tmp = $(this).find(".hour");
            TmpObject.init();
        });
    }
    activeTime();

})


   
//点击抢号码
//0没抢到(没扣红包)；1抢到了；2抢光了(号码不够)；3红包金额不足 5没抢到(扣了红包)
function rob(){
    $('.alertcontairn_tip').hide();
    $.post(
        '/luckyAppWeb/robCrab',
        function (data){
            if(data.ok == 3){
              center($('.alertcontairn_nored'));
              return;
            }
            center($('.alertcontairn_lottery'));       
            if(data.ok == 1){//抢到号码 
                $('.hiddennumber').attr('alt',data.code);              
            }            
            //调用抽选随机数
            hiddenNum=$('.hiddennumber').attr('alt');
            randNum($(".lotry_number span"),10,data.ok); 
        }
        ,'json');
}

</script>
</body> 

</html>