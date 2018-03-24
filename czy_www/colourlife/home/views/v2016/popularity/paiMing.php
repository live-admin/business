<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>人气召集令-活动排名榜</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>css/layout.css" />
	</head>
	
	<body>
		<div class="conter">
			<div class="task03">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>images/task_p03.png">
			</div>
			<div class="tab">
				<div class="tab_top">
					<div class="tab_con">排名</div>
					<div class="tab_con">用户名</div>
					<div class="tab_con">人气值</div>
				</div>
				<div class="tab_site">
					<!--自己排名-->
                    <?php if(!empty($paiMing['paiming'])){?>
						 <div class="bar_box">
							<div class="bar_box_number">
								<div class="bar_box_number_bg">
								    <p><?php echo $paiMing['paiming']?></p>
								</div>
							</div>
							<div class="bar_box_num">
	                            <p id="<?php echo $paiMing['mobile']?>"><?php echo $paiMing['mobile']?></p>
							</div>
							<div class="bar_box_ml">
								<p><?php echo $paiMing['summary']?></p>
							</div>
						</div>
						<?php }?>
						<?php if(!empty($paiMing['grow'])){?>
						<div class="bar_other">
						<!--他人排名-->
						<!--第一条-->
						
						  <?php foreach ($paiMing['grow'] as $key=>$grow){?>
		                        <div class="bar_box_other">
		                            <div class="bar_box_other_number">
		                                <div class="bar_box_other_number_bg">
		                                    <p><?php echo $key+1;?></p>
		                                </div>
		                            </div>
		                            <div class="bar_box_other_tel">
		                                <p id="<?php echo $grow['mobile'];?>"><a><?php echo substr_replace($grow['mobile'],'****',3,4)?></a></p>
		                            </div>
		                            <div class="bar_box_other_ml">
		                                <p><?php echo $grow['summary']?></p>
		                            </div>
		                        </div>
	                        <?php }?>
	                    </div>
	                <?php }?>
                    <!--第一条结束-->
					
				</div>
			</div>
			
			<!--<div class="act_prize">
				<p><span><img src="<?php echo F::getStaticsUrl('/activity/v2016/popularity/');?>images/wu.png"/></span><span>活动奖品</span></p>
				<div class="act_prize_op">
					<p>通过完成各项任务获取人气值，人气值将由高至低进行</p>
					<p>排名：</p>
					<p>第1名：Apple watch</p>
					<p>第2-20名（19名）：彩之云定制充电宝10000mAh</p>
					<p>第21-50名（30名）：彩之云定制U盘</p>
					<p>第51-100名（50名）：彩之云定制抱枕</p>
					<p>第101-200名（100名）：彩之云饭票2元</p>
				</div>
			</div>-->
			
			<div class="tab_p">
				<p class="red01">* 彩之云对本次活动保留法律范围内的最终解释权</p>
				<p class="red01" style="text-indent: 0.25rem;">活动由彩之云提供，与设备生产商Apple Inc.公司无关</p>
			</div>
			<div class="footer"></div>
		</div>
		<div class="rule">
			<a href="javascript:void(0);">我的人气值</a>
		</div>
		<script>
			//此条只起到查看后台传来的数据是什么格式，与后面JS无关
			var test=<?php echo json_encode($paiMing);?>;
			
			
			$(function(){
				var common = $(".bar_box").find(".bar_box_num p").attr("id");
				var common_p = $(".bar_box_other");
				$(".bar_box_other:eq(0)").find(".bar_box_other_number_bg").addClass("bar_box_other_number_bg_active01");
				$(".bar_box_other:eq(1)").find(".bar_box_other_number_bg").addClass("bar_box_other_number_bg_active02");
				$(".bar_box_other:eq(2)").find(".bar_box_other_number_bg").addClass("bar_box_other_number_bg_active03");
				common_p.each(function(index) {
					if($(this).find(".bar_box_other_tel p").attr("id") == common) {
						$(this).css("background-color", "#F9D9D7");
						var top = ($(".bar_box_other").height() + 1) * (index - 1);
						$(".bar_other").scrollTop(top)
					}
				});
				
				$(".rule").click(function(){
					location.href = "/Popularity/myRenQi";
				});
    
				
				
				
			});
		</script>
	</body>
</html>



