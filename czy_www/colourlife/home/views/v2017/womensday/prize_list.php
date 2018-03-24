<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>三八节福利转盘</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css">
	</head>
	<body>
		<div class="conter">
			<!--空记录开始-->
			<div class="null_record hide">
				<img src="<?php echo F::getStaticsUrl('/activity/v2017/womensday/');?>images/gift.png">
				<p>暂无中奖记录</p>
			</div>
			<!--空记录结束-->
			<!--记录开始-->
			<div class="record">
				<div class="record_title">
					<p>奖品名称</p>
					<p>时间</p>
					<p>操作</p>
					<i class="bg_title01"></i>
					<i class="bg_title02"></i>
				</div>
				<div class="record_ban">
					
					<!--<div class="record_site">
						<p>京东券一张</p>
						<p>
							<span>2017-03-08</span>
							<span>10:30:27</span>
						</p>
						<p>
							<a href="javascript:">兑换</a>
						</p>
					</div>-->
				</div>
			</div>
			<!--记录结束-->
			<!--遮罩层开始-->
			<div class="mask hide"></div>
			<!--遮罩层结束-->
			<!--弹窗开始-->
			<div class="record_pop hide">
				<div class="record_pop_title">
					<p>请填写您的兑换信息</p>
				</div>
				<div class="record_pop_site">
					<p>姓名</p>
					<p>
						<input type="text" / class="name">
					</p>
				</div>
				<div class="record_pop_site">
					<p>联系电话</p>
					<p>
						<input type="text" / class="phone">
					</p>
				</div>
				<div class="record_pop_site">
					<p>事业部名称</p>
					<p>
						<input type="text" / class="career">
					</p>
				</div>
				<div class="record_pop_site">
					<p>事业部总部地址</p>
					<p>
						<textarea rows="3" cols="20" class="address"></textarea>
					</p>
				</div>
				<div class="record_pop_botton">
					确定
				</div>
			</div>
			<!--弹窗结束-->
			<span class="p">*本次活动最终解释权归彩之云所有</span>
		</div>
		<script>
			var data=<?php echo json_encode($prize);?>;

	
			if(data.length==0){
				$(".null_record").removeClass("hide");
				$(".record").addClass("hide");
				$(".conter .p").css("bottom","-0.60rem");
			}else{
				$(".conter .p").css("bottom","0.80rem");
				for(var i=0;i<data.length;i++){
					$(".record_ban").append(
							'<div class="record_site" id="'+data[i].id+'" type="'+data[i].type+'"  url="'+data[i].url+'">'+
								'<p>'+data[i].name+'</p>'+
								'<p>'+
									'<span>'+data[i].day+'</span>'+
									'<span>'+data[i].ctime+'</span>'+
								'</p>'+
								'<p>'+
									'<a href="javascript:void(0);">兑换</a>'+
								'</p>'+
							'</div>'
					);
					if(data[i].is_change == "N"){
						$(".record_site p a").addClass("active");
					}else if(data[i].is_change == "Y"){
						$(".record_site p a").addClass("dui_active");
						$(".record_site p a").removeClass("active");

					}
				}
			}
			
			
			
			//关闭弹窗
			$(".mask").click(function(){
				$(".record_pop").addClass("hide");
				$(".mask").addClass("hide");
			});
			//确认
			$(".record_pop_botton").click(function(){
				var name=$(".name").val();
				var career=$(".career").val();
				var address	=$(".address").val();
				var id=$(".record_site").attr("id")
				var pho = $(".phone").val();
				console.log(pho);
				if($(".name").val() != "" && (/^1(3|4|5|7|8)\d{9}$/.test($(".phone").val())) && pho != "" && $(".career").val() != "" && $(".address").val()){
					$.ajax({
						type:"POST",
						url:"/Womensday/Change",
						data:{'pid':id,'uname':name,'mobile':pho,'department':career,'address':address},
						dataType:"json",
						cache:false,
						success:function(data){
							if(data.status==1){
								$(".record_pop").addClass("hide");
								$(".mask").addClass("hide");
								$(".record_site p a").removeClass("active");
								$(".record_site p a").addClass("dui_active");
								$(".record_site p a").removeAttr('href');
							}else if(data.status==0){
								alert(data.msg);
							}
						}
					})
				}
			});
			$(".record_ban .record_site p a").click(function(){
				var type01=$(this).parents('.record_site').attr("type");
				var url01=$(this).parents('.record_site').attr("url");
				if(	type01 == 'jd'){
					window.location.href="/Womensday/GoodsList";
				}else if(type01 == 'exihu'){
					window.location.href=url01;
				}else{
					if($(this).hasClass("active")){
						
						$(".record_pop").removeClass("hide");
						$(".mask").removeClass("hide");
					}
				}
				
			});

		</script>
	</body>
</html>


