<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>定投管理</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telphone=no" />
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fundsDetail/');?>js/flexible.js" ></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fundsDetail/');?>js/jquery-1.11.3.js"></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/fundsDetail/');?>css/layout.css" />
	</head>
	<body style="background: #F2F3F4;">
	 	  <div class="contaner">
	 	  		<div class="choose">
	 	  			<div class="choose_po">
	 	  				<ul class="choose_btn">
	 	  					<li class="p1">还款中</li>
		    				<li class="p2">还款明细</li>
	 	  				</ul>
	 	  			</div>
	 	  			<!--换款中页面-->
	 	  			<div class="bar">
	 	  			
	 	  			</div>
	 	  			<!--还款明细页面-->
	 	  			<div class="schedule hide">
	 	  			
	 	  			</div>
	 	  		</div>
	 	  </div>	
	 	  <script type="text/javascript">
	 	  	$(document).ready(function(){
		 	  	<?php if (!empty($fundsDetail)){
		 	  			foreach ($fundsDetail as $key=>$val){
							$ts=time();
							$sign=md5('ts='.$ts.'&k='.$key.'cfrs_agreement');
		 	  		?>
					var str= '<div class="bar_block"><div class="bar_block_box">'+
  					'<div class="bar_block_box_a bar_block_box_a_one"><p>借款人</p></div>'+
  					'<div class="bar_block_box_b bar_block_box_a_two"><p><?php echo $this->getName($val['borrowerName']);?></p>'+
  					'</div></div><hr /><div class="bar_block_box"><div class="bar_block_box_a"><p>借款金额</p></div>'+
  					'<div class="bar_block_box_b"><p><?php echo $val['investedAmount'];?>元</p></div></div><hr />'+
  					'<div class="bar_block_box"><div class="bar_block_box_a"><p>借款期限</p></div>'+
  					'<div class="bar_block_box_b"><p><?php $str='';if($val['loanPeriodUnit']=='月'){$str='个';}echo $val['loanPeriod'].$str.$val['loanPeriodUnit'];?></p>'+
  						'</div></div><hr /><div class="bar_block_box"><div class="bar_block_box_a">'+
  						'<p>借款用途</p></div><div class="bar_block_box_b"><p><?php echo $val['productUsage'];?></p></div></div><hr />';
  	  					<?php if ($val['productStatus']=='还款中'){?>
  	    					str+='<a href="/FundsDetail/agreement?ts=<?php echo $ts;?>&k=<?php echo $key+1;?>&sign=<?php echo $sign;?>"><div class="bar_block_box four_link"><div class="bar_block_box_a four"><p>四方协议</p></div>'+
  	    						'<div class="bar_block_box_b"><img src="<?php echo F::getStaticsUrl('/home/fundsDetail/');?>images/next.png"/></div></div></a>';
  							$(".bar").append(str);
  						<?php }else{?>
  							str+='<div class="bar_block_box"><div class="bar_block_box_a"><p>状态</p></div><div class="bar_block_box_b"><p>已还款</p></div></div>';
  							$(".schedule").append(str);
		 	  	<?php 		}
						}
					}?>
	 	  		//还款中/还款明细切换
	 	  		$(".choose_btn li").click(function(){
	 	  			$(this).addClass("p1").siblings().removeClass();
	 	  			$(".bar").hide().eq($(".choose_btn li").index(this)).show();
	 	  		});
	 	  		
	 	  		//还款中hide/还款明细show
	 	  		$(".p2").click(function(){
	 	  			$(".bar").addClass("hide");
	 	  			$(".schedule").removeClass("hide");
	 	  		});
	 	  		
	 	  		//还款中show/还款明细hide
	 	  		$(".p1").click(function(){
	 	  			$(".bar").removeClass("hide");
	 	  			$(".schedule").addClass("hide");
	 	  			
	 	  		});
	 	  		
	 	  	});
	 	  </script>
	 	  
	</body>
</html>