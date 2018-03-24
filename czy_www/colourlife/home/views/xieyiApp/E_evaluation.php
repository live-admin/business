<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>E评价</title>
		<link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl("/home/XieyiApp/css/evaluation.css"); ?>" />
		<script type="text/javascript" src="<?php echo F::getStaticsUrl("/home/XieyiApp/js/jquery.min.js"); ?>"></script>
	</head>

	<body>
	
	            <form  id="sub_form" action="E_evaluation"    method="get"  >
		<div class="evaluation_content">
			<p>感谢您对小区的物业管理和服务做出实事求是、客观水平的评价，我们会根据您的意见和建议不断改进、完善小区管理，为您提供更加满意的服务。</p>

			<div class="row clearfix">
				<h4>1、您对物业服务的总体评价</h4>
				<div>
					<input id="one_one" type="radio" value="很满意" name="evaluation_1" />
					<label for="one_one">很满意</label>
				</div>
				<div>
					<input id="one_two" type="radio" value="满意" name="evaluation_1" />
					<label for="one_two">满意</label>
				</div>
				<div>
					<input id="one_three" type="radio" value="一般" name="evaluation_1" />
					<label for="one_three">一般</label>
				</div>
				<div>
					<input id="one_four" type="radio" value="不满意" name="evaluation_1" />
					<label for="one_four">不满意</label>
				</div>
				<div>
					<input id="one_five" type="radio" value="很不满意" name="evaluation_1" />
					<label for="one_five">很不满意</label>
				</div>
			</div>

			<div class="row clearfix">
				<h4>2、您对小区公共区域的安全感到</h4>
				<div>
					<input id="two_one" type="radio" value="很满意" name="evaluation_2" />
					<label for="two_one">很满意</label>
				</div>
				<div>
					<input id="two_two" type="radio" value="满意" name="evaluation_2" />
					<label for="two_two">满意</label>
				</div>
				<div>
					<input id="two_three" type="radio" value="一般" name="evaluation_2" />
					<label for="two_three">一般</label>
				</div>
				<div>
					<input id="two_four" type="radio" value="不满意" name="evaluation_2" />
					<label for="two_four">不满意</label>
				</div>
				<div>
					<input id="two_five" type="radio" value="很不满意" name="evaluation_2" />
					<label for="two_five">很不满意</label>
				</div>
			</div>

			<div class="row clearfix">
				<h4>3、您对小区公共区域的清洁感到</h4>
				<div>
					<input id="three_one" type="radio" value="很满意" name="evaluation_3" />
					<label for="three_one">很满意</label>
				</div>
				<div>
					<input id="three_two" type="radio" value="满意" name="evaluation_3" />
					<label for="three_two">满意</label>
				</div>
				<div>
					<input id="three_three" type="radio" value="一般" name="evaluation_3" />
					<label for="three_three">一般</label>
				</div>
				<div>
					<input id="three_four" type="radio" value="不满意" name="evaluation_3" />
					<label for="three_four">不满意</label>
				</div>
				<div>
					<input id="three_five" type="radio" value="很不满意" name="evaluation_3" />
					<label for="three_five">很不满意</label>
				</div>
			</div>

			<div class="row clearfix">
				<h4>4、您对小区园林绿化感到</h4>
				<div>
					<input id="four_one" type="radio" value="很满意" name="evaluation_4" />
					<label for="four_one">很满意</label>
				</div>
				<div>
					<input id="four_two" type="radio" value="满意" name="evaluation_4" />
					<label for="four_two">满意</label>
				</div>
				<div>
					<input id="four_three" type="radio" value="一般" name="evaluation_4" />
					<label for="four_three">一般</label>
				</div>
				<div>
					<input id="four_four" type="radio" value="不满意" name="evaluation_4" />
					<label for="four_four">不满意</label>
				</div>
				<div>
					<input id="four_five" type="radio" value="很不满意" name="evaluation_4" />
					<label for="four_five">很不满意</label>
				</div>
			</div>

			<div class="row clearfix">
				<h4>5、您对小区公共设施设备(不含电梯)维修感到</h4>
				<div>
					<input id="five_one" type="radio" value="很满意" name="evaluation_5" />
					<label for="five_one">很满意</label>
				</div>
				<div>
					<input id="five_two" type="radio" value="满意" name="evaluation_5" />
					<label for="five_two">满意</label>
				</div>
				<div>
					<input id="five_three" type="radio" value="一般" name="evaluation_5" />
					<label for="five_three">一般</label>
				</div>
				<div>
					<input id="five_four" type="radio" value="不满意" name="evaluation_5" />
					<label for="five_our">不满意</label>
				</div>
				<div>
					<input id="five_five" type="radio" value="很不满意" name="evaluation_5" />
					<label for="five_five">很不满意</label>
				</div>
			</div>
			<div class="row clearfix">
				<h4>6、您对小区的电梯运行感到</h4>
				<div>
					<input id="six_one" type="radio" value="很满意" name="evaluation_6" />
					<label for="six_one">很满意</label>
				</div>
				<div>
					<input id="six_two" type="radio" value="满意" name="evaluation_6" />
					<label for="six_two">满意</label>
				</div>
				<div>
					<input id="six_three" type="radio" value="一般" name="evaluation_6" />
					<label for="six_three">一般</label>
				</div>
				<div>
					<input id="six_four" type="radio" value="不满意" name="evaluation_6" />
					<label for="six_four">不满意</label>
				</div>
				<div>
					<input id="six_five" type="radio" value="很不满意" name="evaluation_6" />
					<label for="six_five">很不满意</label>
				</div>
			</div>

			<h4 class="footer_advice">您的建议和意见是：</h4>
			<textarea rows="5" cols="40" name="note"></textarea>

			<div class="submit_btn">提交</div>
		</div>
		<form>
		<!--弹出框-->
		<div class="pop_up" style="display: none;">
			<div class="iphone_pop samsung" style="display: none;">
				<div class="close_row clearfix"><img class="close" src="<?php echo F::getStaticsUrl("/home/XieyiApp/img/close.png"); ?>" /></div>
				<div class="select_type">
					评价完毕才能提交哦！</div>
				<div class="know">知道了</div>
			</div>

		</div>
		<script type="text/javascript">
			$(function() {
				var r_len = $('.row').length;
				flag = true;
				$('.submit_btn').click(function() {
					var i = 0;
					while (i < r_len) {
//						alert(i);
						var rdo = $('.row').eq(i).find('input[type=radio]');//5
						rdo.each(function() {
							var chked = $(this).is(':checked');
							if (chked) {
                                                            
								flag = true;
								return false;
							} else {
								flag = false;
							}
						});
						i++;
						if (!flag) {
							alert_();
							break;
						}else{
                                                  $("#sub_form").submit();  
                                                    
                                                }
					}
				})

				function alert_() {
					$('.pop_up').show();
					$(".samsung").show();
				};
				//关闭窗口
				$('.close').click(function() {
					$('.pop_up,.pop_up>div').hide();
				});
				//关闭窗口
				$('.know').click(function() {
					$('.pop_up,.pop_up>div').hide();
				});
				//点击
				//			$('.pop_up').click(function(e) {
				//				var obj = e.srcElement || e.target;
				//				if ($(obj).is('.pop_up')) {
				//					$('.pop_up,.pop_up>div').hide();
				//				}
				//			});
			})
		</script>
	</body>

</html>