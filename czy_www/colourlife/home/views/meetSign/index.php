<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>集团会务签到</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>js/main.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>css/normalize.css">
	</head>
	<body style="background: #F2F3F4;">
		<div class="contanis">
			<div class="contanis_txt">
					<p>欢迎您参与</p>
					<p><?php echo $model->title;?></p>
					<hr style="border:none;background-color:#28A2F0;height:3px;width: 10%;"/>
					<div class="user_put"><?php echo $model->content;?></div>
			</div>
			<div class="top_img">
				<a href="javascript:void(0)">
				<img src="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>images/top.png"/>
				</a>
			</div>
			<div class="know">
				<a href="javascript:void(0)">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>images/know.png"/>
				</a>
			</div>
			<div class="qiandao_btn">
					<a href="javascript:void(0)">签到</a>
			</div>
		
		
		<!--遮罩层开始-->
		</div>
		<div class="mask hide"></div>
		
		
		
		<!--弹窗开始-->
		<!--签到须知-->
		<div class="Pop hide">
				<div class="Pop_know_bg_img">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>images/kn.png"/>
				</div>
				<div class="Pop_know_bg">
					<div class="low">
						<p>签到须知</p>
						<p>1.签到时需要开启GPS定位您的位置</p>
						<p>2.签到时需补充OA账号、手机号</p>
						<p>3.每次签到需要通过扫描二维码进入</p>
						<p>&nbsp;此页面</p>
						<p>4.同一时间段只可签到一次</p>
						<p>5.本次会议签到时间：</p>
					</div>
					<div class="Pop_know_bg_time">
						<?php for($i=0; $i<sizeof($time); $i=$i+2){
							echo $time[0+$i].'~'.$time[1+$i].'</br>';
							};
						?>
					</div>
					<hr style="border: none; height: 1px;width: 100%;background-color: #b2c8d4;" />
					<div class="sur_btn" id="Pop_konw">
					<a href="javascript:void(0)">确定</a>
					</div>
				</div>
		</div>
		
		<!--输入表单弹窗-->
		<!--<form action="/MeetSign/Sign" method="post" >-->
			<div class="Pop_input hide">
					<div class="Pop_know_bg_img">
						<img src="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>images/bu.png"/>
					</div>
					<div class="Pop_know_bg buqian">
						<div class="Pop_know_bg_txt">
							<p>补签到信息</p>
						</div>
						<div class="Pop_buqian">
						<input class="oauser" type="text" placeholder="请输入您的OA账号" name="OA" />
						<input class="cal" type="text" placeholder="请输入您的手机号" name="mobile" />
						<input type="hidden" name="sign_location" value="开发中" class="ads" />
						</div>
						<hr style="border: none; height: 1px;width: 100%;background-color: #b2c8d4;" />
						<div class="sur_btn" id="Pop_biaodan">
						<a href="javascript:void(0)">确定</a>
						</div>
					</div>
			</div>
		<!--</form>-->
		
		<!--签到成功-->
		<div class="Pop_suc hide">
				<div class="Pop_know_bg_img">
					<img src="<?php echo F::getStaticsUrl('/activity/v2016/meetingAttendance/');?>images/suc.png"/>
				</div>
				<div class="Pop_know_bg buqian suc">
					<div class="Pop_know_bg_txt Pop_know_bg_txt_suc">
						<p>您已签到成功！</p>
					</div>
					<hr style="border: none; height: 1px;width: 100%;background-color: #b2c8d4;" />
					<div class="sur_btn" id="Pop_sur">
					<a href="javascript:void(0)">确定</a>
					</div>
				</div>
		</div>
		
		<script type="text/javascript">
				
//		        //失去焦点 (blur)
//		        $(".cal").blur(function(){
//		        	$(".cal").val();
//		        }); 
				$("#Pop_biaodan a").click(function(){
					
				
					
					
				var oa = $(".oauser").val();
				var telphoneNum = $(".cal").val();
				
				//判断OA账号不能为空
				if (oa=="") {
					alert("OA账号不能为空");
					return false;
				}
				
				if(numberCheck(telphoneNum)){
					
					var oa_ = $(".oauser").val();
					var mobile_ = $(".cal").val();
					var sign_location_ = $(".ads").val();
					
					
					var model_id = "<?php echo $model->id;?>";
						//手机号码正确弹窗
//						$("#Pop_biaodan").click(function(){
//						$(".Pop_suc").removeClass("hide");
//						$(".mask").removeClass("hide");	
//					});
  				$.ajax({
		            type:"POST",
		            url:'/MeetSign/Sign',
		            data:"id=<?php echo $model->id;?>&cust_id=<?php echo $cust_id;?>&OA="+oa_+"&mobile="+mobile_+"&sign_location="+sign_location_,
		            dataType:'json',
		            cache:false,
		            success:function(data){
		                if(data.status==1){		                
		                    $(".Pop_suc").removeClass("hide");
							$(".mask").removeClass("hide");
		                }else{		                	
							$(".Pop_know_bg_txt p").text(data.param);               	
		                    $(".Pop_suc").removeClass("hide");
							$(".mask").removeClass("hide");	
		                }
		            } 
		        });
					
					
				}
				else{
					return false;
				}
			});
			
			function numberCheck(temp){
	        	var a=/^0?1[3|4|5|8][0-9]\d{8}$/;

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