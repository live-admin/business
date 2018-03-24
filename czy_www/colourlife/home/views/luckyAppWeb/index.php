<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>幸福中国行-彩之云</title>
<link href="<?php echo F::getStaticsUrl('/common/css/luckyAppWeb.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>


</head>

<body>
<div class="lottery_topic">
  
   <div class="topic_head">
     <div class="cloud">
       <div class="bluecloud">
         <div class="cloudtxt">
           <h3>[新年贺喜]</h3>
           <p>
             正月初一至十五，成功注册彩<br />
             之云APP赠送8GU盘或电子体<br />
             温计一个彩生活业主专享。
           </p>
         </div>
       </div>
       <div class="orangecloud">
         <div class="cloudtxt">
           <h3>[天天抽奖]</h3>
           <p>
             1月25日至3月10日，登录APP<br />
             可抽奖3次，任意交易成功再<br />
             获赠一次抽奖！
           </p>
         </div>
       </div>
       <div class="greencloud">
         <div class="cloudtxt">
           <h3>[冬日团购]</h3>
           <p>
             1月31日至3月10日登录APP或<br />
             网站订购夏威夷小木瓜即享超<br />
             值团购优惠还可以额外获赠一<br />
             次免费抽奖。
           </p>
         </div>
       </div>
       <div class="redcloud">
         <div class="cloudtxt">
           <h3>[逢八有礼]</h3>
           <p>
             1月31日至3月10日逢8、18、28
             日在平台成功交易可获赠10斤装
             夏威夷小木瓜一份。
           </p>
         </div>
       </div>
     </div>
   </div>
   <div class="topic">
     <div class="topic_top">
        <p>活动期间，每天登陆彩之云APP，即可获得在线抽奖资格一次，任意成功订单再赠送一次抽奖机会。</p>
     </div>
      <p style="text-align: center;font-size: 14px; text-indent:60px">
      	<?php if($isGuest){?>
       			您还未登录，登录后才可抽奖哦，<span id="luckyCan" style="display: none">1</span>
       		<?php }else{?>
       			今天您还有<span id="luckyCan"><?php echo $luckyCustCan;?></span>次机会试手气，
       		<?php }?>
       		已经有<span><?php echo $allJoin;?></span>人参加抽奖
       		</p>
     <div class="topic_part1">
       <div class="f_left">
          <p class="list_title">活动规则：</p>
          <ul>
            <li>1、活动时间：2014年1月25日-2014年3月18日</li>
            <li>2、活动参与资格：所有彩之云注册用户</li>
            <li>3、抽奖资格说明：</li>
            <li>（1）彩之云用户每天登录APP，获赠一次抽奖
                机会。<br />
                （2）用户在平台上产生成功交易一次，（购买
                商品，成功充值，成功缴纳物业费，停车费），
                获赠一次抽奖机会<br />
                （3）用户抽到幸福奖时，额外获赠一次抽奖机
                会。
            </li>
            <li>4、中奖之后7到15个工作日发放奖品，由您所在小区的彩生活客户经理直接送到您的手上。</li>
            <li>5、彩生活保留法律允许范围内对活动的解释权。</li>
          </ul>
       </div>
       <div class="f_center">
          <p class="btn_arr abs"><input value="" id="btn" type="button" class="lt_start play_btn"  /></p>
          <table class="playtab" id="tb" >
             <tbody>
               <tr>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content first_lt">
                          <div>
                            <p><?php echo empty($layoutList[1])?(''):($layoutList[1]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[1])?(''):($layoutList[1]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[1])?(''):($layoutList[1]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content">
                          <div>
                            <p><?php echo empty($layoutList[2])?(''):($layoutList[2]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[2])?(''):($layoutList[2]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[2])?(''):($layoutList[2]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content second_loty">
                          <div>
                            <p><?php echo empty($layoutList[3])?(''):($layoutList[3]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[3])?(''):($layoutList[3]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[3])?(''):($layoutList[3]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
               </tr>
               <tr>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content">
                          <div>
                            <p><?php echo empty($layoutList[8])?(''):($layoutList[8]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[8])?(''):($layoutList[8]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[8])?(''):($layoutList[8]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
                 <td>
                    <div class="lottery_tab lottery_start">
                       
                    </div>
                 </td>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content">
                          <div>
                            <p><?php echo empty($layoutList[4])?(''):($layoutList[4]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[4])?(''):($layoutList[4]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[4])?(''):($layoutList[4]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
               </tr>
               <tr>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content">
                          <div>
                            <p><?php echo empty($layoutList[7])?(''):($layoutList[7]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[7])?(''):($layoutList[7]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[7])?(''):($layoutList[7]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content">
                          <div>
                            <p><?php echo empty($layoutList[6])?(''):($layoutList[6]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[6])?(''):($layoutList[6]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[6])?(''):($layoutList[6]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
                 <td>
                    <div class="lottery_tab">
                       <div class="lt_content last_lt">
                          <div>
                            <p><?php echo empty($layoutList[5])?(''):($layoutList[5]->prize->prize_level_name);?></p>
                            <p><?php echo empty($layoutList[5])?(''):($layoutList[5]->prize->prize_name);?></p>
                          </div>
                          <img src="<?php echo empty($layoutList[5])?(''):($layoutList[5]->prize->prizePictureUrlNoDefault);?>" />
                       </div>
                    </div>
                 </td>
               </tr>
             </tbody>
          </table>
       </div>
       <div class="f_right">
         <p class="list_title">最新中奖用户</p>
         <div class="lotteryer" id="lotteryer">
            <ul>
            </ul>
         </div>
       </div>
      

     </div>
     <div class="topic_part2">
        <div class="part2_ctt">
           <div class="part2_left">
             <div class="p2_l_top">
               <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img4.png'); ?>" width="312" height="98"/>   
               <p>彩生活精选夏威夷小木瓜<br />夏威夷原种、百分百原生态、木瓜中的上品。</p> 
               <a href="http://ckcyds.c.colourlife.com/goods/669" target="_blank" class="atonce_pay">
                 <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/payBtn.jpg'); ?>" width="101" height="30"/>
               </a>         
             </div>
           </div>
           <div class="part2_right">
             <div class="p2_r_top">
               <a href="http://ckcyds.c.colourlife.com/goods/668" target="_blank"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img5.png'); ?>" width="282" height="187"/></a>  
               <p>【品名】：夏威夷小木瓜</p> 
               <p>【规格】：5斤礼品装/10斤礼品装</p>
             </div>
             
           </div>
        </div>
     </div>
     <div class="topic_part2">
        <div class="part3_ctt">
          <h3>天天团.特惠商品推荐</h3>
          <ul>
            <li>
              <h4>江山&nbsp;&nbsp;蜂王礼盒</h4>
              <div class="part3_proimg">
                <a href="http://bslt.c.colourlife.com/goods/378" target="_blank"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img7.png'); ?>" width="188" height="163" class="p3_img" /></a>
                <span class="caozhao"></span>
                <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/price1.png'); ?>" class="priceimg" />
              </div>
            </li>
            <li>
              <h4>富锦&nbsp;&nbsp;山珍海味组合</h4>
              <div class="part3_proimg">
                <a href="http://bslt.c.colourlife.com/goods/401" target="_blank""><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img6.png'); ?>" width="188" height="163" class="p3_img" /></a>
                <span class="caozhao"></span>
                <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/price2.png'); ?>" class="priceimg" />
              </div>
            </li>
            <li class="p3_lastli">
              <h4>富安娜&nbsp;&nbsp;床品四件套（花开茶扉）</h4>
              <div class="part3_proimg">
                <a href="http://bslt.c.colourlife.com/goods/372" target="_blank"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/img8.png'); ?>" width="325" height="210" class="p3_img" /></a>
                <span class="caozhao"></span>
                <img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/price3.png'); ?>" class="priceimg" />
              </div>
            </li>
          </ul> 
        </div>
     </div>
     <div class="bot"><img src="<?php echo F::getStaticsUrl('/common/images/luckyAppWeb/bot.jpg'); ?>" width="974" height="54"/></div>
     
  </div>
  
  

  <div class="opacity_bingo" style="display: none;">
	     <div class="opacity_content">
	       <div id="resultInfo">
	          <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/lotteryAlert.png'); ?>" class="alertBack" />
	          <div class="d_content">
	              <div >
	                  <div>
	                     <img id="bingoPrizeImg" class="lotteryImg" />
	                     <h1>恭喜你 ! 中奖啦,</h1>
	                     <h1>您获得了<span id="prizeLevelName"></span>，<span id="prizeName"></span> !</h1>
	                  </div>
	              </div>
	              <br/>
	              <button class="closeBtn" >关闭</button>
	          </div>
	       </div>
	     </div>
	  </div>
	  
	  <div class="opacity_nochance" style="display: none;">
	     <div class="opacity_content">
	       <div id="resultInfo">
	          <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/lotteryAlert1.png'); ?>" class="alertBack" />
	          <div class="d_content">
	              <div >
	                  <div class="lotteryTitle">
	                     <h1>好桑心 ~ 奖品擦肩而过,</h1>
	                     <h1>再有一次抽奖机会多好啊 !</h1>
	                  </div>
	                  <p>获得更多手气，看看活动细则哦</p>
	              </div>
	              <br/>
	              <button class="closeBtn" >关闭</button>
	          </div>
	       </div>
	     </div>
	  </div>
	  
	  <div class="opacity_chance" style="display: none;">
	     <div class="opacity_content">
	       <div id="resultInfo">
	          <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/lotteryAlert1.png'); ?>" class="alertBack" />
	          <div class="d_content">
	              <div >
	                 <div class="lotteryTitle">
	                     <h1>好桑心~奖品擦肩而过 !</h1>
	                  </div>
	              </div>
	              <br/>
	              <button class="closeBtn" >关闭</button>
	          </div>
	       </div>
	     </div>
	  </div>
	  
	  <div class="opacity_nochance2" style="display: none;">
	     <div class="opacity_content">
	       <div id="resultInfo">
	          <img src="<?php echo F::getStaticsUrl('/common/images/luckyApp/lotteryAlert1.png'); ?>" class="alertBack" />
	          <div class="d_content">
	              <div >
	                  <div class="lotteryTitle">
	                     <h1>好桑心 ~ 没有手气了,</h1>
	                     <h1>再有一次手气该多好啊 !</h1>
	                  </div>
	                  <p>获得更多手气，看看活动细则哦</p>
	              </div>
	              <br/>
	              <button class="closeBtn" >关闭</button>
	          </div>
	       </div>
	     </div>
	  </div>
  

</div>
<script type="text/javascript">
//通知公告滚动代码添加	
$.extend({  
    wordScroll:function(opt,callback){  
   //alert("suc");  
    this.defaults = {  
     objId:"",  
     width:163,  // 每行的宽度  
     height:200, // div的高度  
     liHeight:25,  // 每行高度  
     lines:1,  // 每次滚动的行数  
     speed:1000,  // 动作时间  
     interval:2000,  // 滚动间隔  
     picTimer:0,  // 间隔句柄，不需要设置，只是作为标识使用  
     isHorizontal:false  // 是否横向滚动  
    }  
      
                //参数初始化  
    var opts = $.extend(this.defaults,opt);  
      
    // 纵向横向通用  
    $("#"+opts.objId).css({  
           width:opts.width,  
           height:opts.height,  
           "min-height":opts.liHeight,  
           "line-height":opts.liHeight+"px",  
           overflow:"hidden"  
           });  
      
    $("#"+opts.objId+" li").css({  
           height:opts.liHeight  
           });  
                if(opts.lines==0)   
     opts.lines=1;  
      
    // 横向滚动  
    if(opts.isHorizontal){  
       
     $("#"+opts.objId).css({  
            width:opts.width*opts.lines + "px"  
            });  
       
     $("#"+opts.objId+" li").css({  
            "display":"block",  
            "float":"left",  
            "width":opts.width + "px"  
            });  
       
     $("#"+opts.objId+" ul").css({  
            width:$("#"+opts.objId).find("li").size()*opts.width + "px"  
     });     // 横向使用，不够一屏则不滚动  
     if($("#"+opts.objId).find("li").size()<=opts.lines)  
      return;  
     var scrollWidth = 0 - opts.lines*opts.width;  
       
    }else{  
      
     //如果不够一屏内容 则不滚动  
     if($("#"+opts.objId).find("li").size()<=parseInt($("#"+opts.objId).height()/opts.liHeight,10))  
      return;  
     var upHeight=0-opts.lines*opts.liHeight;  
    }  
      
      
    // 横向滚动  
    function scrollLeft(){  
      $("#"+opts.objId).find("ul:first").animate({  
        marginLeft:scrollWidth  
      },opts.speed,function(){  
        for(i=1;i<=opts.lines;i++){  
         $("#"+opts.objId).find("li:first").appendTo($("#"+opts.objId).find("ul:first"));  
        }  
        $("#"+opts.objId).find("ul:first").css({marginLeft:0});  
      });  
    };  
    // 纵向滚动  
    function scrollUp(){  
      $("#"+opts.objId).find("ul:first").animate({  
        marginTop:upHeight  
      },opts.speed,function(){  
        for(i=1;i<=opts.lines;i++){  
          $("#"+opts.objId).find("li:first").appendTo($("#"+opts.objId).find("ul:first"));  
        }  
        $("#"+opts.objId).find("ul:first").css({marginTop:0});  
      });  
    };  
      
    //鼠标滑上焦点图时停止自动播放，滑出时开始自动播放  
    $("#"+opts.objId).hover(function() {  
     clearInterval(opts.picTimer);  
    },function() {  
     opts.picTimer = setInterval(function() {  
      // 判断执行横向或纵向滚动  
      if(opts.isHorizontal)  
       scrollLeft();  
      else  
       scrollUp();  
     },opts.interval); // 自动播放的间隔，单位：毫秒  
    }).trigger("mouseleave");  
    }          
})  
 //通知公告滚动代码添加


//抽奖 
 var num=0;
var result;
function Trim(str){
    return str.replace(/(^\s*)|(\s*$)/g, ""); 
    }

function GetSide(m,n){
    var arr = [];
    for(var i=0;i<m;i++){
                arr.push([]);
                for(var j=0;j<n;j++){
                                        arr[i][j]=i*n+j;
                                        }
                            }
    var resultArr=[];
    var X=0,
    Y=0,
    direction="Along"
    while(X>=0 && X<m && Y>=0 && Y<n)
    {
    resultArr.push([X,Y]);
            if(direction=="Along"){
                                        if(Y==n-1)
                                                {X++;}
                                        else
                                        {Y++;}
                                        if(X==m-1&&Y==n-1)
                                        direction="Inverse"
                                        }
        else{
                    if(Y==0)
                    X--;
                    else
                    Y--;
                    if(X==0&&Y==0)
                    break;
                    }
    }
    return resultArr;
} 


    var index=0, 
    prevIndex=0,  
    Speed=300, 
    Time,
    arr = GetSide(3,3),
    tb = document.getElementById("tb"), 
    cycle=0,    
    flag=false, 
    quick=0;



function StartGame(){
    $("td").removeClass("playcurr");
    document.getElementById("btn").disabled=true;//
    clearInterval(Time); 
    cycle=0;
    flag=false;
    Time = setInterval(Star,Speed);
}

function Star(){
    if(flag==false){
                    if(quick==5){
                        clearInterval(Time);
                        Speed=50;
                        Time=setInterval(Star,Speed);
                    }
                    if(cycle>=4){
                        clearInterval(Time);
                        Speed=300;
                        flag=true;        
                        Time=setInterval(Star,Speed);
                    }
                }
                
            

    if(index>=arr.length){
        index=0;
        cycle++;
    }

    tb.rows[arr[index][0]].cells[arr[index][1]].className="playcurr";//�������Ȧ����ʽ
        if(index>0)
            prevIndex=index-1;
        else{
            prevIndex=arr.length-1;
        }
    tb.rows[arr[prevIndex][0]].cells[arr[prevIndex][1]].className="playnormal";//�������Ȧ����ʽ
    index++;
    quick++;
    if(flag==true&&cycle>=5&& index==parseInt(num)){ 
                quick=0;
                clearInterval(Time);
                index=0;
                document.getElementById("btn").disabled=false;
                //alert(JSON.stringify(result));
                if(result.success==1 && result.data.bingo==1){
	                //alertText="恭喜您，中得 : "+result.data.result.prize_name+"("+result.data.result.prize_level_name+")";
	                //alert(alertText);
						$("#resultInfo #prizeLevelName").text(result.data.result.prize_level_name);
						$("#resultInfo #prizeName").text(result.data.result.prize_name);
						//$("#resultInfo #prizeImg").attr("alt",result.data.result.prize_name);
						//$("#resultInfo #prizeImg").attr("src",result.data.result.prize_picture);
						//$("#resultInfo #editReceive").attr("onClick","javascript:location.href='/luckyResult/view?id="+result.data.result.cust_result_id+"'");
						$("#bingoPrizeImg").attr("src",result.data.result.prize_picture);
						if(result.data.result.id==9){
							//中奖机会+1
							var postCan=parseInt($("#luckyCan").text());
							$("#luckyCan").text(postCan+1);
						}
						$(".opacity_bingo").show();
					}else if(result.success==1 && result.data.bingo==0){
						var postCan=parseInt($("#luckyCan").text());
						if(postCan<1){
							$(".opacity_nochance").show();
						}else{
							$(".opacity_chance").show();
						}
					}
                
            }
}
 
//抽奖
/*遮罩层宽高*/	 
function coverWH(){ 
  var dheight=$(document).height();
  var dwidth=$(document).width();
  $('.opacity_bingo').css('height',dheight);
  $('.opacity_bingo').css('width',dwidth);
  $('.opacity_nochance').css('height',dheight);
  $('.opacity_nochance').css('width',dwidth);
  $('.opacity_chance').css('height',dheight);
  $('.opacity_chance').css('width',dwidth);
  $('.opacity_nochance2').css('height',dheight);
  $('.opacity_nochance2').css('width',dwidth);
}
  window.onresize=function(){
	  coverWH();
	  }
  window.onload=function(){
	  coverWH();
	  }

  
 $(document).ready(function(){  
      
 
   $.wordScroll({objId:"lotteryer"}); //公告滚动调用

   $('.closeBtn').click(function(){
		$(".opacity_bingo").hide();
		$(".opacity_nochance").hide();
		$(".opacity_chance").hide();
		$(".opacity_nochance2").hide();
		});

	$("#btn").bind("click",function(){
		   $(this).blur();
		   var postCan=parseInt($("#luckyCan").text());
			if(postCan-1<0){
				//alert('没有抽奖机会');
				$(".opacity_nochance2").show();
				return false;
			}
			$("#luckyCan").text(postCan-1);
			StartGame();
			$.ajax({
				   type: "POST",
				   url: "/luckyAppWeb/doLucky",
				   data: "actid=2",
				   dataType:'json',
				   error:function(){
					   clearInterval(Time);
					   $("td").removeClass("playcurr");
						alert('系统异常');
						
				   },
				   success: function(data){
					   if(data.success==0 && data.data.location==1){
						   location.href=data.data.href;
						   return;
					   }
					   if(data.success==0){
							clearInterval(Time);
							$("td").removeClass("playcurr");
							alert(data.data.msg);
						}else{
							num=data.data.result.index;
							result=data;
						}
					}  
			});
		});
		
   

});  


 function getCustResultList(){
 	$.ajax({
 		   type: "POST",
 		   url: "/luckyAppWeb/getUserNewListJson",
 		   dataType:'json',
 		   error:function(){
 				//alert('系统异常');
 		   },
 		   success: function(data){
 			   if(data.success==0 ){
 				   alert(data.data.msg);
 				   return;
 			   }
 			   var list=data.data.list;
 			   if(! list){
 				   return;
 			   }
 			   var listOld=$("#lotteryer ul li");
 			   //alert(listOld.length);
 			   if(listOld.length>30){  //如果li大于20了，去掉前10行来拼加
 				   $("#lotteryer li").remove("#lotteryer li:lt(10)");
 			   }
 			   if(listOld.length<8){  //不到8个，清除掉在拼加
 				   $("#lotteryer li").remove("#lotteryer li");
 			   }
 			   for ( var i = 0; i < list.length; i++) {
 				$("#lotteryer ul").append("<li>"+list[i]['name']+"："+list[i]['prize_level_name']+"</li>");
 			   }
 			   
 			   $.wordScroll({objId:"lotteryer"}); //公告滚动调用
 			}  
 	});
 }
 //定时执行 获取最新中奖用户
//  window.onload=getCustResultList();
//  setInterval(getCustResultList, 10000);
 
 </script>
</body>
</html>
