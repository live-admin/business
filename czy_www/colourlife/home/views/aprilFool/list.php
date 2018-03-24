<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>愚人节-留言</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no"/>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/flexible.js" ></script>
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>css/layout.css" />
	    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>css/normalize.css">
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/jquery-1.11.3.js"></script>
	    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>js/iscroll.js"></script>
	    <script type="text/javascript">

			var myScroll,
				pullDownEl, pullDownOffset,
				pullUpEl, pullUpOffset,pageNum;

				pageNum = 1;
			
			function pullDownAction () {
				<?php if (isset($_GET['s'])&&$_GET['s']==1){?>
						var param='s=1&page='+pageNum;
				<?php }else{?>
						var param='page='+pageNum;
				<?php }?>
					$.ajax({
	                    type: 'POST',
	                    url: '/AprilFool/PageList',
	                    data: param,
	                    dataType: 'json',
	                    success: function (data) {
	                        if(data.status==1){
	                        	pageNum = data.page;
	                        	var infos = data.list;
	                        	
	                        		for (i=0; i<infos.length; i++) {
		        						$("#thelist").prepend('<div class="leave_banner_p">'+
		        				   	   			'<div class="leave_banner_p_box1a">'+
		        				   	   				'<img src="'+infos[i].iconUrl+'">'+
		        				   	   			'</div>'+
		        				   	   			'<div class="leave_banner_p_box2a">'+
		        				   	   				'<div class="leave_banner_p_title">'+
		        				   	   					'<span>'+infos[i].name+'</span>'+
		        				   	   					'<span>'+infos[i].time+'</span>'+
		        				   	   					'<div class="clear"></div>'+
		        				   	   				'</div>'+
		        				   	   				'<p>'+infos[i].content+'</p>'+
		        				   	   			'</div>'+
		        			   	   			'</div>');
		        					}
		        					
		        					myScroll.refresh();		// Remember to refresh when contents are loaded (ie: on ajax completion)
		                       
	                        }else{
	                        	myScroll.refresh();	
	                        	return false;
		                     }
	                    }
	                });	
				
			}

			function pullUpAction () {
				<?php if (isset($_GET['s'])&&$_GET['s']==1){?>
						var param='s=1&page='+pageNum;
				<?php }else{?>
						var param='page='+pageNum;
				<?php }?>
				$.ajax({
                    type: 'POST',
                    url: '/AprilFool/PageList',
                    data: param,
                    dataType: 'json',
                    success: function (data) {
                        if(data.status==1){
                        	pageNum = data.page;
                        	var infos = data.list;
                        	
                        		for (i=0; i<infos.length; i++) {
	        						$("#thelist").append('<div class="leave_banner_p">'+
	        				   	   			'<div class="leave_banner_p_box1a">'+
	        				   	   				'<img src="'+infos[i].iconUrl+'">'+
	        				   	   			'</div>'+
	        				   	   			'<div class="leave_banner_p_box2a">'+
	        				   	   				'<div class="leave_banner_p_title">'+
	        				   	   					'<span>'+infos[i].name+'</span>'+
	        				   	   					'<span>'+infos[i].time+'</span>'+
	        				   	   					'<div class="clear"></div>'+
	        				   	   				'</div>'+
	        				   	   				'<p>'+infos[i].content+'</p>'+
	        				   	   			'</div>'+
	        			   	   			'</div>');
	        					}
	        					
	        					myScroll.refresh();		// Remember to refresh when contents are loaded (ie: on ajax completion)
	                       
                        }else{
                        	myScroll.refresh();	
                        	return false;
	                     }
                    }
                });	
			}

			function loaded() {
				pullDownEl = document.getElementById('pullDown');
				pullDownOffset = pullDownEl.offsetHeight;
				pullUpEl = document.getElementById('pullUp');	
				pullUpOffset = pullUpEl.offsetHeight;
				
				myScroll = new iScroll('wrapper', {
					useTransition: true,
					topOffset: pullDownOffset,
					onRefresh: function () {
						if (pullDownEl.className.match('loading')) {
							pullDownEl.className = '';
							pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
						} else if (pullUpEl.className.match('loading')) {
							pullUpEl.className = '';
							pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Pull up to load more...';
						}
					},
					onScrollMove: function () {
						if (this.y > 5 && !pullDownEl.className.match('flip')) {
							pullDownEl.className = 'flip';
							pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Release to refresh...';
							this.minScrollY = 0;
						} else if (this.y < 5 && pullDownEl.className.match('flip')) {
							pullDownEl.className = '';
							pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
							this.minScrollY = -pullDownOffset;
						} else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
							pullUpEl.className = 'flip';
							pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Release to refresh...';
							this.maxScrollY = this.maxScrollY;
						} else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
							pullUpEl.className = '';
							pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Pull up to load more...';
							this.maxScrollY = pullUpOffset;
						}
					},
					onScrollEnd: function () {
						if (pullDownEl.className.match('flip')) {
							pullDownEl.className = 'loading';
							pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Loading...';				
							pullDownAction();	// Execute custom function (ajax call?)
						} else if (pullUpEl.className.match('flip')) {
							pullUpEl.className = 'loading';
							pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Loading...';				
							pullUpAction();	// Execute custom function (ajax call?)
						}
					}
				});
				
				setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
			}

			document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

			document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
		</script>
	</head>
	<body>
		<input type="hidden" value="<?php echo $page;?>" id="pageNum"/>
		<div class="conter leave">
			<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg01.jpg" />
			<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg02.jpg" />
			<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg03.jpg" />
			<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg04.jpg" />
			<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg05.jpg" />
			<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/bg06.jpg" />
	   </div>
	   <div class="april">
	   	   <div class="april_logo"></div>
	   	   <div class="leave_banner" id="wrapper">
	   	   		<div id="scroller">
	   	   			<div id="pullDown">
						<span class="pullDownIcon"></span><span class="pullDownLabel">Pull down to refresh...</span>
					</div>
					<ul id="thelist">
		   	   			<!--1-->
		   	   			<?php foreach ($list as $val){
				   	   		if (!empty($val->customer_id)){
				   	   			$userInfo=AprilFool::model()->getUserInfo($val->customer_id);
				   	   		}else {
								$userInfo['portrait']='';
								$userInfo['nickname']=substr_replace($val->mobile,"****",3,4);
							}
				   	   	?>
				   	   		<div class="leave_banner_p">
				   	   			<div class="leave_banner_p_box1a">
				   	   				<img src="<?php if(!empty($userInfo['portrait'])){echo F::getUploadsUrl("/images/" . $userInfo['portrait']);}else {echo F::getStaticsUrl('/common/images/nopic.png');}?>">
				   	   			</div>
				   	   			<div class="leave_banner_p_box2a">
				   	   				<div class="leave_banner_p_title">
				   	   					<span><?php echo $userInfo['nickname'];?></span>
				   	   					<span><?php echo date("Y.m.d H:i",$val->create_time);?></span>
				   	   					<div class="clear"></div>
				   	   				</div>
				   	   				<p><?php echo $val->content;?></p>
				   	   			</div>
				   	   		</div>
			   	   		<?php }?>
			   	   	<!--1-->
		   	   		</ul>

					<div id="pullUp">
						<span class="pullUpIcon"></span><span class="pullUpLabel">Pull up to refresh...</span>
					</div>

	   	   		</div>
	   	   </div>
	   
	   	   
	   </div>
	   <div class="rule">
				<a href="<?php if (isset($_GET['s'])){echo $this->createUrl('rule',array('s'=>1));}else {echo $this->createUrl('rule');}?>">
					<img src="<?php echo F::getStaticsUrl('/home/aprilFool/'); ?>images/rule_button.png">
				</a>
	 </div>

	</body>
</html>
