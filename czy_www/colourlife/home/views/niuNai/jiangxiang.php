<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>挤牛奶-获奖历史</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/jquery-1.11.3.js');?>"></script>
		<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/niuNai/js/flexible.js');?>"></script>
		<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/niuNai/');?>css/layout.css">
	</head>
	<body>
		<div class="contaners">
			<div class="top">
				<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/rule_04.jpg"/>
			</div>
			<div class="WH_bg">
				<!--标题-->
					<div class="WH_bg_box">
						<div class="box_prize">
							<p><span>奖项</span><span>|</span></p>
						</div>
						<div class="box_name">
							<p><span>获奖名称</span><span>|</span></p>
						</div>
						<div class="box_time">
							<p>获奖时间</p>
						</div>
					</div>
				<!--各项内容-->
					<div class="WH_other_bg">
						<!--第一条-->
                        <?php if(!empty($prizeArr)){
                            foreach ($prizeArr as $prize){
                            
                        ?>
                            <div class="WH_other_bg_box">
                                <div class="box_other_prize">
                                    <p><?php echo JiNiuNai::model()->jiangXiang[$prize->prize_id]?></p>
                                </div>
                                <div class="box_other_name">
                                    <p><?php echo $prize->prize_name?></p>
                                </div>
                                <div class="box_other_time">
                                    <p><span><?php echo date('Y-m-d H:i:s',$prize->create_time)?></span></p>
                                </div>
                            </div>
                            <?php }?>
                        <?php }?>
					</div>
				</div>
				
					<div class="foot">
						<img src="<?php echo F::getStaticsUrl('/home/niuNai/');?>images/rule_02.jpg"/>
					</div>
		</div>
		
	</body>
</html>
