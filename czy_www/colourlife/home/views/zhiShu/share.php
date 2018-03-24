<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>神奇的种子</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="telephone=no" name="format-detection">
		<script src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>js/ReFontsize.js"></script>
		<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>css/layout.css" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>js/jquery-1.11.3.js"></script>
	</head>

	<body>
		<div class="bg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_01.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_02.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_03.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_04.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_05.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_06.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_07.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_08.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_09.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_10.jpg">
			<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/bg_11.jpg">
		</div>
		<div class="t_p_seeds">
			<div class="share_p">
				<p>我获得了一颗神秘种子，听说可以结出百万大奖，快来帮我浇水吧~</p>
			</div>
			<div class="t_p_seeds_con share_t_p_seeds_con">
				
				<div id="progressBar">
					<div>
						<span class="pro"></span>
					</div>
					<span class="pc1">1</span>
					<span class="pc2">2</span>
					<span class="pc3">3</span>
					<span class="pc4">4</span>
					<span class="pc5">5</span>
                    
                    <p id="actualGrowth"><?php echo $chengZhangValue;?></p>
                    
				</div>
				
				<div class="progressBar_p">
					<span>种子</span>
					<span>树苗</span>
					<span>开花</span>
					<span>结果</span>
					<span>果实</span>
				</div>
				<div class="progressBar_banner share">
					<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/tree_04.png">
				</div>
				<div class="button">
					<a href="javascript:void(0)" data-num="<?php echo $num;?>" date-val="<?php echo $validate;?>">帮他浇水(<?php echo $num;?>/15)</a>
				</div>
			</div>
            
                  
            		 <!--活动规则开始-->
	    <div class="huodong">
	    	<a href="/ZhiShu/Rule">
	    	<p class="huodong-p">活动规则</p>
	    	</a>
	    </div>
	   
		</div>
		<div class="footer">
			<div class="footer_con">
				<div class="footer_con_box1a">
					<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/logo.png" />
				</div>
				<div class="footer_con_box2a">
					<a href="javascript:void(0)">
						我也要玩
					</a>
				</div>
				<div class="footer_con_box3a">
					<a href="javascript:void(0)">
						<img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/delete.png" />
					</a>

				</div>
			</div>
		</div>
        <div class="mask hide"></div>
        <div class="download hide">
            <img src="<?php echo F::getStaticsUrl('/home/zhiShu/'); ?>images/download_ios.png" />
        </div>
		<script>
			$(document).ready(function(){
                
               
				if(navigator.userAgent.match(/android/i)){
					$(".footer").remove();
				}
				//ajax获取植物生长值等相关信息
                var Growth = $("#actualGrowth").text();
                initAnimate(Growth);

				//帮他浇水
				$(".button a").click(function() {
					$this=$(this);
					var num=$this.attr("data-num");
					if(num>=15){
						alert("今天已经浇够啦!明天再来吧");
						return false;
					}
					var val=$this.attr("date-val");
                    $.ajax({
                        type: 'POST',
                        url: '/ZhiShu/OtherJiaoShui',
                        data: 'val='+val,
                        dataType: 'json',
                        success: function (result) {
                            if(result.status==1){
                                alert(result.msg);
                                initAnimate(result.czvalue);
//                                if(result.czvalue>=1000){
//                                    $("#actualGrowth").text(1000);
//                                }else{
//                                    $("#actualGrowth").text(result.czvalue);
//                                }
                                $this.attr("data-num",result.num);
                                $this.text("帮他浇水("+result.num+"/15)");
//                                if(result.czvalue>=1000){
//                                    $(".pro").attr("style","width:100%;");
//                                    $(".pc1").addClass("pc1 p_color");
//                                    $(".pc2").addClass("pc2 p_color");
//                                    $(".pc3").addClass("pc3 p_color");
//                                    $(".pc4").addClass("pc4 p_color");
//                                    $(".pc5").addClass("pc5 p_color");
//                                    $(".progressBar_banner").children().remove();
//                                    $(".progressBar_banner").append('<img src="<?php //echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_05.png">');
//                                }else if(result.czvalue>=800){
//                                    $(".pro").attr("style","width:100%;");
//                                    $(".pc1").addClass("pc1 p_color");
//                                    $(".pc2").addClass("pc2 p_color");
//                                    $(".pc3").addClass("pc3 p_color");
//                                    $(".pc4").addClass("pc4 p_color");
//                                    $(".progressBar_banner").children().remove();
//                                    $(".progressBar_banner").append('<img src="<?php //echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_04.png">');
//                                }else if(result.czvalue>=500){
//                                    $(".pc1").addClass("pc1 p_color");
//                                    $(".pc2").addClass("pc2 p_color");
//                                    $(".pc3").addClass("pc3 p_color");
//                                    $(".progressBar_banner").children().remove();
//                                    $(".progressBar_banner").append('<img src="<?php //echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_03.png">');
//                                }else if(result.czvalue>=300){
//                                    $(".pc1").addClass("pc1 p_color");
//                                    $(".pc2").addClass("pc2 p_color");
//                                    $(".progressBar_banner").children().remove();
//                                    $(".progressBar_banner").append('<img src="<?php //echo F::getStaticsUrl('/home/zhiShu/');?>images/tree_02.png">');
//                                }
                                //location.reload();
                            }else{
                                alert(result.msg);
                                location.reload();
                                return false;
                            }
                        }
                    });				
				});

				//隐藏底部
				$(".footer_con_box3a").click(function(){
					$(".footer").addClass("hide");
				});

				//打开app
				$(".footer_con_box2a a").click(function(){
                    var ua = window.navigator.userAgent.toLowerCase();
                    if(ua.match(/MicroMessenger/i) == 'micromessenger')
                    {
                        $(".download").removeClass("hide");
                        $(".mask").removeClass("hide");
                    }
                    else
                    {
                     isInstalled();   
                    }
				});
                //关闭mask
                $(".download").click(function(){
                    
                   $(".download").addClass("hide");
                   $(".mask").addClass("hide"); 
                });
                function initAnimate(Growth)
                {
                    if (Growth>=1 && Growth<300) 
					{
						var afterProgress = parseFloat((25/300).toFixed(5))*parseFloat(Growth)+"%";
						
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
				function isInstalled(){
                if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
                    var loadDateTime = new Date();
                    window.setTimeout(function() {
                      var timeOutDateTime = new Date();
                      if (timeOutDateTime - loadDateTime > 5000) {
                        window.location = "http://dwz.cn/8YPIv";/***下载app的地址***/
                      } else {
                        window.close();
                      }
                    },
                    25);
                    window.location = "colourlife://";
              } else if (navigator.userAgent.match(/android/i)) {
                var state = null;
                try {
                    
//                  state = window.open("colourlifeAndroid://splash?", '_blank');
                    $('body').append("<iframe id='ifr' style='display:none'></iframe>");
                    $('#ifr').attr("src", "colourlife://splash");
//                  window.location.href="colourlifeAndroid://";
					window.setTimeout(function(){
					    document.body.removeChild(ifr);
					    window.location.href = "http://dwz.cn/8YPIv"; /***下载app的地址***/
					},2000);
                } catch(e) {}
//              
              }
            }
			});
		</script>
	</body>

</html>