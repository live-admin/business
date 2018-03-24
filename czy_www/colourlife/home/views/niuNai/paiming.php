<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>挤牛奶-活动排名</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/flexible.js');?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/jquery-1.11.3.js');?>"></script>
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/ranking.js');?>"></script>
		<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/niuNai/');?>css/layout.css">
	</head>
	<body>
		<div class="contaner_ranking">
			<div class="top">
                <?php if(empty($type)){?>
				<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/rule_03.jpg"/>
                <?php }else{?>
                <img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/rule_03-1.jpg"/>
                <?php }?>
			</div>
			<div class="contan">
				<!--自己排名-->
                    <?php if(!empty($mingValue)){?>
					<div class="bar_box">
						<div class="bar_box_number">
							<div class="bar_box_number_bg">
							    <p><?php echo $mingValue['paiming']?></p>
							</div>
						</div>
						<div class="bar_box_tel">
                            <p id="<?php echo $mingValue['mobile'];?>"><?php if($type) echo "Ta的排名";else echo "我的排名";?></p>
						</div>
						<div class="bar_box_ml">
							<p><span><?php echo $mingValue['summary']?></span><span> ml</span></p>
						</div>
					</div>
				<div class="bar_other">
					<!--他人排名-->
					<!--第一条-->
                    <?php foreach ($mingValue['grow'] as $key=>$grow){?>
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
                                <p><span><?php echo $grow['summary']?></span><span> ml</span></p>
                            </div>
                        </div>
                    <?php }?>
				</div>
                <?php }?>
			</div>
            <!--弹窗优惠卷开始-->
	     	<div class="popup_coupons hide">
	     		<div class="popup_con">
	     			<div class="popup_con_h">
	     				<p>恭喜您获得奖品</p>
	     			</div>
	     			<div class="popup_con_b">
	     				<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/securities.png">
	     				<p>环球精选优惠券：满500减45券</p>
	     			</div>
	     			<div class="popup_con_f">
	     				<a href="javascript:void(0)">
	     					确定
	     				</a>
	     			</div>
	     		</div>
	     	</div>
	     	<!--弹窗优惠卷结束-->
	     	<!--弹窗实物开始-->
	     	<div class="popup_physical hide">
	     		<div class="popup_con">
	     			<div class="popup_con_h">
	     				<p>恭喜您获得奖品</p>
	     			</div>
	     			<div class="popup_physical_con  popup_physical_con_p">
	     				<p>贝瑟斯创意陶瓷马克杯1个</p>
	     				<p>* 一元购码将在活动结束后3个工作日内自动发放到您的彩之云账户</p>
	     			</div>
	     			<div class="popup_con_f">
	     				<a href="javascript:void(0)">
	     					确定
	     				</a>
	     			</div>
	     		</div>
	     	</div>
            <!--弹窗实物结束-->
            <div class="mask hide"></div>
			<div class="foot">
				<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/rule_02.jpg"/>
			</div>
		</div>
        <input type="hidden" id="isTan" value="<?php echo $isTan;?>">
        <input type="hidden" id="paiMing" value="<?php echo $mingValue['paiming'];?>">
        <input type="hidden" id="jp" value="<?php echo $jp;?>">
	</body>
</html>
