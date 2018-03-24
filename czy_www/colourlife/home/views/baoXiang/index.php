<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>猴赛雷宝箱</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <script src="<?php echo F::getStaticsUrl('/home/baoXiang/js/ReFontsize.js');?>"></script>
        <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/baoXiang/');?>css/layout.css">
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/baoXiang/js/jquery-1.11.3.js');?>"></script>
		<style>
				body{
					background-image: url(<?php echo F::getStaticsUrl('/home/baoXiang/');?>/images/banner_bg.jpg) !important;  
				}
       </style>
	</head>
	<body>
		<div class="header">
			<div class="banner">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/banner.jpg">
			</div>
			<div class="bg_footer">
				<div class="bg_footer1a">
					<div class="bg_footer2a">
						<p>您有<span class="p1"><?php echo $lingCount;?></span>次领取宝石的机会！</p>
						<p>抽取宝石</p>
					</div>
					<div class="bg_footer3a">
						<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest.png">
					</div>
					<div class="bg_footer4a">
						<ul class="bg_footer4a_bs">
                            <?php if($redCount){?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr01.png"><span>红宝石</span><i class="p2">x<?php echo $redCount?></i></li>
                            <?php }else{?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_01.png"><span>红宝石</span><i class="p2 hide">x<?php echo $blueCount?></i></li>
                            <?php }?>
                            <?php if($blueCount){?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr02.png"><span>蓝宝石</span><i class="p2">x<?php echo $blueCount?></i></li>
							<?php }else{?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_01.png"><span>蓝宝石</span><i class="p2 hide">x<?php echo $redCount?></i></li>
                            <?php }?>
                            <?php if($popupCount){?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr03.png"><span>紫宝石</span><i class="p2">x<?php echo $popupCount?></i></li>
                            <?php }else{?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_01.png"><span>紫宝石</span><i class="p2 hide">x<?php echo $popupCount?></i></li>
                            <?php }?>
                            <?php if($yellowCount){?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr04.png"><span>黄宝石</span><i class="p2">x<?php echo $yellowCount?></i></li>
                            <?php }else{?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_01.png"><span>黄宝石</span><i class="p2 hide">x<?php echo $yellowCount?></i></li>
                            <?php }?>
                            <?php if($greenCount){?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr05.png"><span>绿宝石</span><i class="p2">x<?php echo $greenCount?></i></li>
                            <?php }else{?>
                                <li><img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_01.png"><span>绿宝石</span><i class="p2 hide">x<?php echo $greenCount?></i></li>
                            <?php }?>
                        </ul>
					</div>
					<div class="ba_button">
						<a href="#">
							开启宝箱
						</a>
					</div>
					<div class="ba_button1a">
						<div class="ba_button1a_lt">
							<a href="#">寻宝攻略</a>
						</div>
						<div class="ba_button1a_rg">
							<a href="/BaoXiang/QueryTreasure">查询宝藏</a>
						</div>
                        <div class="ba_button1a_rg">
							<a href="/BaoXiang/Rule">活动规则</a>
						</div>
					</div>
				</div>
			</div>
            <?php
                if($isYao){
                    $url="/BaoXiang/YaoYiYao";
                }else{
                    $url="javascript:alert('据说每天的14:00~15:00及19:00~20:00可以使用摇一摇获得紫宝石和礼品呢！')";
                }
            ?>
			<div class="yaoyiyao">
                <a href=<?php echo $url;?>>
					<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/yaoyiyao.png" />
				</a>
			</div>
			<div class="mask hide"></div>
		</div>
		<!--弹窗开始-->
		<!--紫色-->
		<div class="g_popup hide stone_block">
			<p>恭喜您获得1个紫宝石</p>
			<div class="g_popup1a">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/purple.png">
			</div>
		</div>
		<!--蓝色-->
		<div class="g_bule hide stone_block">
			<p>恭喜您获得1个蓝宝石</p>
			<div class="g_popup1a">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/blue.png">
			</div>
		</div>
		<!--红色-->
		<div class="g_red hide stone_block">
			<p>恭喜您获得1个红宝石</p>
			<div class="g_popup1a">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/red.png">
			</div>
		</div>
		<!--黄色-->
		<div class="g_yellow hide stone_block">
			<p>恭喜您获得1个黄宝石</p>
			<div class="g_popup1a">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/yellow.png">
			</div>
		</div>
		<!--绿色-->
		<div class="g_green hide stone_block">
			<p>恭喜您获得1个绿宝石</p>
			<div class="g_popup1a">
				<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/green.png">
			</div>
		</div>
		<!--弹窗结束-->
		<!--领包开始-->
		<div class="g_bag hide">
			<div class="g_bag1a">
				<!--<p>澳门旅游酒店礼券</p>-->
				<!--<p>E维修“猴塞雷”彩包</p>-->
			</div>
		</div>
		<!--领包结束-->
		<!--没有聚集完开始-->
		<div class="not_the_whole hide">
			<div class="not_the_whole1a">
                <p>哎呀<i class="ba_ku"></i>要集齐5类宝石才可以把宝箱打开呢快来寻宝攻略吧！</p>
			</div>
		</div>
		<!---没有聚集完结束-->
		<!--寻宝攻略开始-->
		<div class="treasure_hunt_strategy hide">
			<div class="treasure_hunt_strategy1a">
				<h4>寻宝攻略</h4>
                <p>1.用户通过邀请注册、<a href="/XingChenQuestion?cust_id=<?php echo $cust_id;?>" class="p_a">星晨旅游问答</a>、充值话费、商城消费(彩生活特供、环球精选)、E缴费、E停车、邻里发帖、E家访有机会获得宝石或商家折扣券。</p>
				<p>2.星晨旅游问答答案就在首页 “星晨旅游” 介绍里哦！</p>
				<p>3.在邻里发帖机率更高哟！~</p>
				<p>4.成功邀请注册满3人获得宝石几率更大哟。</p>
			</div>
		</div>
        <input type="hidden" id="mobile" value="<?php echo $mobile;?>">
        <input type="hidden" id="isCan" value="<?php echo $isCan;?>">
        <input type="hidden" id="isOpen" value="<?php echo $isOpen;?>">
        <input type="hidden" id="lingCount" value="<?php echo $lingCount;?>">
		<!--寻宝攻略结束-->
		<script>
			$(document).ready(function(){

				//寻宝攻略btn
				$(".ba_button1a_lt").click(function(){
					$(".mask").removeClass("hide");
					$(".treasure_hunt_strategy").removeClass("hide");
				});
				//寻宝攻略关闭&&关闭宝石/奖券弹窗
				$(".mask").click(function(){
					$(".mask").addClass("hide");
					$(".treasure_hunt_strategy").addClass("hide");//寻宝攻略关闭
                    $(".not_the_whole").addClass("hide");
					$(".g_bag").addClass("hide");//奖券弹窗关闭

					$(".stone_block").each(function(){
						if(!$(this).hasClass("hide")){
							$(this).addClass("hide");
						}
					});
					$(".bg_footer4a_bs li i").each(function(){
						if(!$(this).hasClass("hide")){
							$(this).addClass("hide");
							$(this).prev().prev().remove();
							$(this).prev().before('<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_01.png">');
						}
					});
                    location.reload();
				});
				//抽取宝箱btn
				$(".bg_footer2a").find("p:nth-of-type(2)").click(function(){
                    var lingCount=$('#lingCount').val();
                    var mobile=$('#mobile').val();
                    var url='/BaoXiang/LingQu';
                    if(lingCount==0){
                        alert('您当前可抽取机会为0，试试看邀请好友参加，或查看寻宝攻略吧！');
                        return false;
                    }
					//ajax从后台获取奖品信息
                    $.ajax({ 
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                var prize_type=data.lei;
                                var stone_type=data.type;
                                var prize_content = data.name;
                                if(prize_type == 0){
                                switch(stone_type){
                                    case 1:
                                    {
                                        $(".g_red").removeClass("hide");
                                        $(".mask").removeClass("hide");
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(1)").find("img").remove();
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(1)").find("span").before('<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr01.png">');
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(1)").find("i").removeClass("hide");
                                        break;
                                    }
                                    case 2:
                                    {
                                        $(".g_bule").removeClass("hide");
                                        $(".mask").removeClass("hide");
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(2)").find("img").remove();
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(2)").find("span").before('<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr02.png">');
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(2)").find("i").removeClass("hide");
                                        break;
                                    }
                                    case 3:
                                    {
                                        $(".g_green").removeClass("hide");
                                        $(".mask").removeClass("hide");
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(5)").find("img").remove();
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(5)").find("span").before('<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr04.png">');
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(5)").find("i").removeClass("hide");
                                        break;
                                    }
                                    case 4:
                                    {
                                        $(".g_yellow").removeClass("hide");
                                        $(".mask").removeClass("hide");
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(4)").find("img").remove();
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(4)").find("span").before('<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr03.png">');
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(4)").find("i").removeClass("hide");
                                        break;
                                    }
                                    case 5:
                                    {
                                        $(".g_popup").removeClass("hide");
                                        $(".mask").removeClass("hide");
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(3)").find("img").remove();
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(3)").find("span").before('<img src="<?php echo F::getStaticsUrl('/home/baoXiang/');?>images/chest_cr05.png">');
//                                        $(".bg_footer4a_bs").find("li:nth-of-type(3)").find("i").removeClass("hide");
                                        break;
                                    }
                                    default :
                                        return false;
                                }
					}
					else if(prize_type == 1)
					{
						processPrize(prize_content);
					}
                    else if(prize_type == 2)
					{
						processPrize(prize_content);
					}
                                
                            }else{
                                return false;
                            } 
                        } 
                    });

				});
				//开启宝箱btn
				$(".ba_button a").click(function(){
					//ajax获取是否可以打开宝箱权限,以及获得的奖项内容
                    var mobile=$('#mobile').val();
                    var url='/BaoXiang/OpenBox';
                    var right = $('#isCan').val();
                    var right2 = $('#isOpen').val();
                    if(!right2){
                        //已经开启
                        alert('宝箱已经开启了')
						return false;
                    }
                    if(!right){
                        $(".not_the_whole").removeClass("hide");
                        $(".mask").removeClass("hide");
						return false;
                    }
                    $.ajax({ 
                        type:"POST",
                        url:url,
                        data:'mobile='+mobile,
                        dataType:'json',
                        cache:false,
                        success:function(data){
                            if(data.status==1){
                                var prize_content=data.name;
                                processPrize(prize_content);
                            }else{
                                return false;
                            } 
                        } 
                    });
				});
				
				//查询宝箱
// 				$(".ba_button1a_rg a").click(function(){
// 					//ajax从服务器获取是否有中奖纪录，以及确定页面跳转
// 					var isWinner = false;
					
// 					if(isWinner)
// 					{
// 						window.location.href = "/BaoXiang/QueryTreasure"
// 					}
// 					else
// 					{
// 						window.location.href = "/BaoXiang/QueryTreasure/query_the_treasure_chest_null.html"
// 					}
// 				});
				function processPrize(prize_content){
					var temp = [];
					temp = prize_content.split("#");

					$(".g_bag1a").empty();
					if(temp.length == 1){
						for( var i =0; i<temp.length; i++){
							$(".g_bag1a").append('<p class="one">'+temp[i]+'</p>');
						}
					}
					else if(temp.length == 2){
						for( var i =0; i<temp.length; i++){
							$(".g_bag1a").append('<p class="two">'+temp[i]+'</p>');
						}
					}
					else if(temp.length == 3){
						for( var i =0; i<temp.length; i++){
							$(".g_bag1a").append('<p class="three">'+temp[i]+'</p>');
						}
					}

					$(".g_bag").removeClass("hide");
					$(".mask").removeClass("hide");
				}
			});
		</script>
	</body>
</html>