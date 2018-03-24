<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>历史记录</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>css/normalize.css">
	</head>
	<body>
		<div class="contanis_history"> 
			<div class="contanis_history_bar">
				<div class="contanis_history_box">
					<div class="contanis_history_time">
						<p>时间</p>
					</div>
					<div class="contanis_history_name">
						<p>奖品名称</p>
					</div>
				</div>
				<div class="contanis_history_detail"> 
					<?php if(empty($prizeMobileArr)){
						}else {
							foreach($prizeMobileArr as $key=>$val){
        		    ?>
					<div class="contanis_history_detail01">
						<div class="contanis_history_detail_box">
							<div class="contanis_history_detail_time">
								<p><?php echo date('Y-m-d',$val['create_time'])?>
									<span style="color: #7C868E;"><?php echo date('H:i:s',$val['create_time'])?></span></p>
							</div>
							<div class="contanis_history_detail_name" id="<?php echo $val['chance_id']?>">
								<p><?php echo $val['prize_name']?></p>
							</div>
						</div>
					</div>
					<?php 
				        }
				    }?>
				</div>
			</div>
		</div>
		<!--遮罩层开始-->
		<div class="mask hide"></div>
		<div class="mask1 hide"></div>
		<!--遮罩层结束-->
		
		<!--流量领取弹窗-->
		<div class="liuliang_Popup hide">
			<div class="liuliang_Popup_round">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/luckdraw/');?>images/gift.png">
			</div>
			<div class="liuliang_Popup_con">
				<div class="liuliang_Popup_con_b">
					<p>恭喜你抽中奖品</p>
					<p>随机流量</p>
					<input type="text" id="mobile" placeholder="请输入手机号"/>
				</div>
				<div class="liuliang_Popup_con_footer">
					<a href="javascript:void(0)">
						领取
					</a>
				</div>
			</div>
		</div>
		
		<div class="flow_pop hide">
			<div class="flow_round">
				<p>你已经领取过了</p>
			</div>
			<div class="flow_footer">
				<a href="javascript:void(0)">
					确定
				</a>
			</div>
		</div>
		
		<script type="text/javascript">
		var index = 0;
		var chance_id = "";
		
		
			$(".contanis_history_detail_name p:contains('随机流量')").css({"color":"#1F92ED","text-decoration":"underline"});
			
			$(".contanis_history_detail_name p:contains('随机流量')").click(function(){
				
				$(".liuliang_Popup").removeClass("hide");
				$(".mask").removeClass("hide");
				
				chance_id = $(this).parent().attr("id");
			});
			
			$(".mask, .mask1, .flow_footer").click(function(){
				$(".liuliang_Popup").addClass("hide");
				$(".flow_pop").addClass("hide");
				$(".mask").addClass("hide");
				$(".mask1").addClass("hide");
				window.location.reload();
			});
			
			
			//手机号码确定
			$(".liuliang_Popup_con_footer").click(function(){
				if ($(this).hasClass("active")) {
					return false;
				} 
				var _this = $(this);
				$(this).addClass("active");
				var telphoneNum = $(".liuliang_Popup_con_b input").val();
				if(numberCheck(telphoneNum)){
			   	var mobile_user = $("#mobile").val();
			   	
				$.ajax({
				async:true,
				type:"POST",
				url:"/SiQingChou/LingQu",
				data:"chance_id="+chance_id+"&mobile="+mobile_user,
				dataType:'json',
				success:function(data){
					if(data.status == 1){  
						$(".flow_round p").text("恭喜你成功领取"+data.msg+"流量");
						$(".flow_pop").removeClass("hide");
						$(".mask1").removeClass("hide");
						_this.removeClass("active");
						
					}
					else {
						$(".flow_round p").text(data.msg);
						$(".flow_pop").removeClass("hide");
						$(".mask1").removeClass("hide");
						_this.removeClass("active");
					}
				} 
			});
		}
			});
			//号码校验
			function numberCheck(temp){
	        	var a=/^[1]{1}[0-9]{10}$/;

	        	if(!a.test(temp))
	            {
	            	alert("手机号码输入格式不对");
	                return false;
	            }

	            return true;
	        }
			
		</script>
		
	</body>
</html>
