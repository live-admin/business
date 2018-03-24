<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>植树节-话费种子</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="telephone=no" name="format-detection">
	    <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/zhiShu/js/ReFontsize.js');?>"></script>
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/zhiShu/');?>css/layout.css">
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/zhiShu/');?>css/normalize.css">
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/zhiShu/js/jquery-1.11.3.js');?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/');?>js/ShareSDK.js"></script>
        <script language="javascript" type="text/javascript">
	    function showShareMenuClickHandler()
	    {
	        
	        var u = navigator.userAgent, app = navigator.appVersion;
	        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
	        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
	        
	         if(isAndroid){
	             try{
	                 var version = jsObject.getAppVersion();
	//                 alert(version);
	             }catch(error){
	                  //alert(version);
	                  if(version !="4.3.6.2"){
	                    alert("请到App--我--检查更新,进行更新");
	                    return false;
	                }
	             }finally {}
	            $sharesdk.open("85f512550716", true);
	        }
	        
	        if(isiOS){
	            try{
	                if(getAppVersion && typeof(getAppVersion) == "function"){
	                     getAppVersion();
	                     var vserion = document.getElementById('vserion').value;
	                    }
	                }catch(e){
	                    
	                }
	           
	            if(vserion){
	                //alert(vserion);
	                $sharesdk.open("62a86581b8f3", true); 
	            }else{  
	                alert("请到App--我--检查更新,进行更新");
	                return false;
	             }  
	            }
	        
	        var params = {
	            "text" : "我获得了一颗神秘种子，听说可以结出百万大奖，快来帮我浇水吧~",
	            "imageUrl" : "http://cc.colourlife.com/common/images/logo.png",
	            "url":"<?php echo F::getHomeUrl('/ZhiShu/FenWeb?cust_id='.$cust_id); ?>",
	            "title" : "我获得了一颗神秘种子，快来帮我浇水吧~",
	            "titleUrl" : "<?php echo F::getHomeUrl('/ZhiShu/FenWeb?cust_id='.$cust_id); ?>",
	            "description" : "描述",
	            "site" : "彩之云",
	            "siteUrl" : "<?php echo F::getHomeUrl('/ZhiShu/FenWeb?cust_id='.$cust_id); ?>",
	            "type" : $sharesdk.contentType.WebPage
	        };
	       $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {}); 
	    };
	    </script>
    </head>
    
	<body>
		<div class="bg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_01.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_02.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_03.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_04.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_05.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_06.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_07.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_08.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_09.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_10.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/bg_11.jpg">
		</div>
		<div class="t_p_seeds">
			<div class="t_p_seeds_con">
				<div id="progressBar">
					<div>
						<span id="progress" style="width:0%"></span>
					</div>
					<span>1</span>
					<span>2</span>
					<span>3</span>
					<span>4</span>
					<span>5</span>
                    
                    <p id="actualGrowth"><?php echo $chengZhangValue;?></p>
				</div>
				
				<div class="progressBar_p">
						<span>种子</span>
						<span>树苗</span>
						<span>开花</span>
						<span>结果</span>
						<span>果实</span>
				</div>
				<div class="progressBar_banner">
					<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_01.png">
				</div>
				<div class="button">
					<a href="javascript:void(0)">浇水</a>
				</div>
			</div>
            
            		 <!--活动规则开始-->
	    <div class="huodong">
	    	<a href="/ZhiShu/Rule">
	    	<p class="huodong-p">活动规则</p>
	    	</a>
	    </div>
	   


		</div>
		<div class="mask hide"></div>
        <div class="mask1 hide"></div>
		<!--彩富用户第一次打开弹出框开始-->
		<div class="Popup_CaiFupresent hide">
			<div class="Popup_CaiFupresent_header">
				<p>您是尊贵的彩富人生用户，彩之云送您100成长值</p>
			</div>
			<div class="Popup_CaiFupresent_footer">
				<a href="javascript:void(0)">确定</a>
			</div>
		</div>
		<!--彩富用户第一次打开弹出框结束-->
		<!--非彩富用户第一次打开弹出框开始-->
		<div class="Popup_NoCaiFupresent hide">
			<div class="Popup_NoCaiFupresent_header">
				<p>参加彩富人生可获赠100成长值<a href="javascript:void(0)">去看看</a></p>
			</div>
			<div class="Popup_NoCaiFupresent_footer">
				<a href="javascript:void(0)">确定</a>
			</div>
		</div>
		<!--非彩富用户第一次打开弹出框结束-->
        
        <!--邀请注册弹窗开始-->
		<div class="register hide">
			<div class="register_header">
				<p>邀请朋友注册彩之云</p>
                <p>每天最高可获得50成长值</p>
			</div>
			<div class="register_footer">
				<a href="javascript:mobileJump('Invite');">邀请注册</a>
			</div>
		</div>
		<!--邀请注册弹窗结束-->
        
        
		<!--邀请朋友浇水弹窗开始-->
		<div class="Popup_friend hide">
			<div class="Popup_friend_header">
				<p>咕噜咕噜~种子茁壮成长中…</p>
                <p class="hide">明天再来浇水可以获得<span id="gainGrowthPopupFriend"></span>点成长值哟！</p>
			</div>
			<div class="Popup_friend_footer">
				<a href="javascript:void(0)">邀请朋友浇水最高可获35成长值</a>
			</div>
		</div>
		<!--邀请朋友浇水弹窗结束-->
		<!--今天浇水水弹窗开始-->
		<div class="Popup_today hide">
			<div class="Popup_friend_header">
				<p>今天已经浇过水了哦~</p>
				<p class="hide">明天再来浇水可以获得<span id="gainGrowthPopupToday"></span>点成长值哟！</p>
			</div>
			<div class="Popup_friend_footer">
				<a href="javascript:void(0)">邀请朋友浇水最高可获35成长值</a>
			</div>
		</div>
	   <!--今天浇水水弹窗结束-->
	   	<!--饭票弹窗开始-->
        <?php if(!empty($prizeArr) && $prizeArr['prize_id']!=1){ ?>
        <div class="mask"></div>
        <div class="meal_ticket">
             <div class="meal_ticket_img">
                 <div class="meal_ticket_img_box1a">
                     <p>￥<span><?php echo $prizeArr['price']?></span></p>
                 </div>
                 <div class="meal_ticket_img_box2a">
                     <p><?php echo $prizeArr['price']?>元彩之云饭票</p>
                 </div>
             </div>
             <div class="meal_ticket_p">
                 <p>恭喜您获得<?php echo $prizeArr['price']?>元饭票，奖品将于活动结束后10个工作日内发放。</p>
             </div>
         </div>
        <?php }?>
	   <!--饭票弹窗结束-->
	   <!--优惠卷开始-->
       <?php if(!empty($prizeArr) && $prizeArr['prize_id']==1){ ?>
       <div class="mask"></div>
	   <div class="coupons">
	   		<div class="coupons_img">
                <?php  
                    $arr=explode(',', $prizeArr['prize_name']);
                    if(!empty($arr) && count($arr)>=2){
                    foreach ($arr as $v){
                    ?>
                    <p><?php echo $v?></p>
                    <?php }?>
                <?php }else{?>
                <p><?php echo $prizeArr['prize_name'];?></p>
                <?php }?>    
	   		</div>
	   		<div class="coupons_p">
	   			<p><?php echo $prizeArr['zhang_name']?></p>
	   		</div>
	   </div>
       <?php }?>
	   <!--优惠卷结束-->
	   <!--大礼包开始-->
<!--	   <div class="package hide">
	   		<div class="package_img">
	   			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/libao.png">
	   		</div>
	   		<div class="package_p">
	   			<p>恭喜你获得商家礼券大礼包！快到我的卡券里看看都有什么吧~</p>
	   		</div>
	   </div>-->
	   <!--大礼包结束-->
       <input type="hidden" id="isCaiFuUser" value="<?php echo $isCaiFuUser;?>">
       <input type="hidden" id="isNoCaiFuUser" value="<?php echo $isNoCaiFuUser;?>">
       <input type="hidden" id="mobile" value="<?php echo $mobile;?>">
       <input type="hidden" id="isJiaoShui" value="<?php echo $isJiaoShui;?>">
       <input type="hidden" id="chengZhangTomorrow" value="<?php echo $chengZhangTomorrow;?>">
       <input style="display: none" id="vserion" />
       <script>
			$(document).ready(function(){
				//ajax获取植物生长值
                
                var Growth = $("#actualGrowth").text();
                initAnimate(Growth);
                
                var isCaiFuUser=$('#isCaiFuUser').val();//彩富人生用户
                var isNoCaiFuUser=$('#isNoCaiFuUser').val();//非彩富人生用户

				//如果用户第一次打开此活动：1.彩富用户赠送100生长值；2.非彩富用户提醒
                if (isCaiFuUser){
                    $(".mask1").removeClass("hide");
                    $(".Popup_CaiFupresent").removeClass("hide");
                }else if(isNoCaiFuUser){
                    $(".mask1").removeClass("hide");
                    $(".Popup_NoCaiFupresent").removeClass("hide");
                }
                //确定领取彩富人生成长值100(确定)
                $(".Popup_CaiFupresent_footer a").click(function(){
                    var mobile=$('#mobile').val();
                    var url='/ZhiShu/GetCai';
                    $.ajax({
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                location.reload();
                            }else{
                                alert(data.msg);
                                return false;
                            }
                        } 
                    });
                });
                //非彩富人生用户(确定)
                $(".Popup_NoCaiFupresent_footer a,.Popup_NoCaiFupresent_header a").click(function(){
                    var mobile=$('#mobile').val();
                    var url='/ZhiShu/GetNoCai';
                    $.ajax({
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                location.reload();
                            }else{
                                return false;
                            }
                        } 
                    });
                });
                //非彩富人生用户(去看看)
                $(".Popup_NoCaiFupresent_header a").click(function(){
                    var mobile=$('#mobile').val();
                    var url='/ZhiShu/GetNoCai';
                    $.ajax({
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                window.location.href = "/advertisement/wealthLife";
                            }else{
                                return false;
                            }
                        } 
                    });
                });
				//自己浇水按钮
				$(".button").find("a").click(function(){
					//ajax获取今天是否已经浇过水等后台数据
					var action = $('#isJiaoShui').val();//是否可以浇水
                    var chengZhangTomorrow=$('#chengZhangTomorrow').val();
                    if(!action){
                        $(".mask").removeClass("hide");
						$(".Popup_today").removeClass("hide");
                        $("#gainGrowthPopupToday").text(chengZhangTomorrow);
                        return false;
                    }
                    var mobile=$('#mobile').val();
                    var url='/ZhiShu/MyJiao';
                    $.ajax({
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                $(".mask").removeClass("hide");
                                $(".Popup_friend").removeClass("hide");
                                $("#gainGrowthPopupFriend").text(data.value);
                                
                            }else{
                                return false;
                            }
                        } 
                    });
				});
                
                function changePopContent()
                {
                    $(".Popup_friend").addClass("hide");
                    $(".Popup_today").addClass("hide");
                    $(".register").removeClass("hide");
                    $(".mask").removeAttr("style");
                }
               
                //分享按钮
                $(".Popup_friend_footer a").click(function() {
                	var mobile=$('#mobile').val();
                    var url='/ZhiShu/FenXiang';
                    $(".mask").css("pointer-events","none");
                    setTimeout(changePopContent,3000);
                    
                    $.ajax({
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                showShareMenuClickHandler();
                                
                            }else{
                                alert(data.msg);
                                return false;
                            }
                        } 
                    });

                	//ajax当前的生长值&&是否可以浇水(分享到朋友圈+20<每天限1次>)
//					gainGrowth = 20;
//					progressBarAnimate(actualGrowth,gainGrowth);
                });
				//关闭邀请朋友烧水弹窗
				$(".mask").click(function(){
					$(".mask").addClass("hide");
					$(".Popup_friend").addClass("hide");
					$(".Popup_today").addClass("hide");
                    $(".meal_ticket").addClass("hide");
                    $(".coupons").addClass("hide");
                                
                    location.reload();
					
					//$(".Popup_NoCaiFupresent").addClass("hide");
				});
				//彩富用户弹窗确定按钮（关闭功能）
				$(".Popup_CaiFupresent_footer a").click(function() {
					$(".mask1").addClass("hide");
					$(".Popup_CaiFupresent").addClass("hide");
				});
                
                //非彩富用户弹窗确定按钮（关闭功能）
                $(".Popup_NoCaiFupresent_footer a").click(function() {
					$(".mask1").addClass("hide");
					$(".Popup_NoCaiFupresent").addClass("hide");
                    
				});
                
				
                function initAnimate(Growth)
                {
                    if (Growth>=1 && Growth<300) 
					{
						var afterProgress = parseFloat((25/300).toFixed(5))*parseFloat(Growth)+"%";
						
						//$("#progressBar>div span").animate({width:afterProgress},3000);
                        $("#progressBar>div span").css("width",afterProgress);
						$("#actualGrowth").text(Growth);
						$("#actualGrowth").css("left",afterProgress);
						$(".progressBar_banner").children().remove();
						$(".progressBar_banner").append('<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_01.png">');
                       
                        $("#progressBar span:nth-child(2)").addClass("p_color");
                            
					}
                    else if(Growth>=300 && Growth<500)
					{
						var afterProgress = parseFloat((25/200).toFixed(5))*(parseFloat(Growth-300))+25+"%";
						
						$("#progressBar>div span").css("width",afterProgress);
						$("#actualGrowth").text(Growth);
						$("#actualGrowth").css("left",afterProgress);
						$(".progressBar_banner").children().remove();
						$(".progressBar_banner").append('<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_02.png">');
                        $("#progressBar span:nth-child(2)").addClass("p_color");
                        $("#progressBar span:nth-child(3)").addClass("p_color");
                            
					}
					else if(Growth>=500 && Growth<800)
					{
						var afterProgress = parseFloat((25/300).toFixed(5))*(parseFloat(Growth-500))+50+"%";
//						alert(afterProgress);
						$("#progressBar>div span").css("width",afterProgress);
						$("#actualGrowth").text(Growth);
						$("#actualGrowth").css("left",afterProgress);
						$(".progressBar_banner").children().remove();
						$(".progressBar_banner").append('<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_03.png">');
                        $("#progressBar span:nth-child(2)").addClass("p_color");
                        $("#progressBar span:nth-child(3)").addClass("p_color");
                        $("#progressBar span:nth-child(4)").addClass("p_color");
                        
					}
					else if(Growth>=800 && Growth<1000)
					{
						var afterProgress = parseFloat((25/200).toFixed(5))*(parseFloat(Growth-800))+75+"%";
						
						$("#progressBar>div span").css("width",afterProgress);
						$("#actualGrowth").text(Growth);
						$("#actualGrowth").css("left",afterProgress);
						$(".progressBar_banner").children().remove();
						$(".progressBar_banner").append('<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_04.png">');
                        $("#progressBar span:nth-child(2)").addClass("p_color");
                        $("#progressBar span:nth-child(3)").addClass("p_color");
                        $("#progressBar span:nth-child(4)").addClass("p_color");
                        $("#progressBar span:nth-child(5)").addClass("p_color");
					}
					else if (Growth >= 1000) 
					{
						
						$("#progressBar>div span").css("width","100%");
						$("#actualGrowth").text(1000);
						$("#actualGrowth").css("left","100%");
						$(".progressBar_banner").children().remove();
						$(".progressBar_banner").append('<img src="<?php echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_05.png">');
                        $("#progressBar span:nth-child(2)").addClass("p_color");
                        $("#progressBar span:nth-child(3)").addClass("p_color");
                        $("#progressBar span:nth-child(4)").addClass("p_color");
                        $("#progressBar span:nth-child(5)").addClass("p_color");
                        $("#progressBar span:nth-child(6)").addClass("p_color");
					}
					else
					{
						return false;
					}
                }
			});
            function mobileJump(cmd)
                {
                    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
                        var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
                        document.location = _cmd;
                    } else if (/(Android)/i.test(navigator.userAgent)) {
                        var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
                        eval(_cmd);
                    } else {

                    }
                }
		</script>
	</body>
</html>
