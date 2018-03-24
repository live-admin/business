<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>按住一秒</title>
		<link href="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>css/gedc.css" rel="stylesheet" type="text/css">
		<!--<link href="css/common.css" rel="stylesheet" type="text/css" />-->

		<!--<script src="http://www.fz222.com/weixin/share/share.php" type="text/javascript"></script>-->
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jweixin-1.0.0.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/weixin.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/jquery.lazyload.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/other.js"></script>
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>js/gunDong.js"  charset="utf-8"></script>
	</head>

	<body>
		<div class="gedc">
			<div class="top_bg">
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/top_bg.png" />
				<!--<div class="top_img"><img src="images/top.png" /></div>-->
				<!--<p class="title">你能精确的按出<span class="red_txt">1秒</span>钟吗？</p>-->
			</div>


			<!--提示页-->
			<div class="prompt" style="display: block;">
				<div class="lotteryList" style="position:relative;"> 
					<dl id="ticker">
                        <?php foreach($listResutl as $result){ ?>
                            <dt><?php echo $result['msg']; ?></dt>
                        <?php } ?>
					</dl>
				</div>
				<table>
					<tr>
						<td>抽奖时间</td>
						<td>奖品</td>
						<td>领奖情况</td>
					</tr>
                    <?php foreach ($list as $value): ?>
                    <tr>
                        <td>
                            <?php echo $value->lucky_date ;?>
                        </td>
                        <td>
                            <?php if( in_array($value->prize_id, array(89,106,125,153))): ?>
                                <!--华美达酒店-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingHuameida">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(90,107,126,156))):  ?>
                                <!--趣园私人酒店公寓-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingQuyuan">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(91,108,127,157))): ?>
                                <!--清风仙境-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingWonderland">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(92,109,128,158))): ?>
                                <!--海岛三角洲-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingSanjiaozhou">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(93,110,136,160))): ?>
                                <!--苏州太湖天成-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingTaihutiancheng">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(94,111,129,154))): ?>
                                <!--海陵岛-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingHailingdao">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(95,112,130,159))): ?>
                                <!--惠州丽兹公馆-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingLizigongguan">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(96,113,121))): ?>
                                <!--皓雅养生度假酒店-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingHaoyahotel">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(97,114,132,155))): ?>
                                <!--罗浮山彩别院-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingFlyvilla">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php elseif(in_array($value->prize_id, array(98,114,133))): ?>
                                <!--凤池岛-->
                                <a class="acolor" href="/luckyApp/christmasShuoMingFengchidao">
                                    <?php echo $value->prize->prize_name; ?>
                                </a>
                            <?php else: ?>
                                <?php echo $value->prize->prize_name; ?>
                            <?php endif; ?>
                        </td>
                        <td <?php echo ($value->deal_state==0)?("class='not_getit'"):(''); ?> >

                            <!-- 55000大奖红包 -->
                            <?php if($value->prize->prize_name == "5000大奖红包"){
                                if($value->deal_state!=2){ ?>
                                    待审核
                                <?php }else{ ?>
                                    已领奖
                                <?php } ?>
                                <!-- 5000大奖红包 -->

                                <!-- 泰康人寿 -->
                            <?php }elseif($value->prize->prize_name=="泰康人寿"){
                                if($value->getTaikangLife($value->id)==2){ ?>
                                    已提交
                                <?php } else if($value->getTaikangLife($value->id)==3){?>
                                    已过期
                                <?php } ?>
                                <?php if($value->deal_state!=2 && $value->getTaikangLife($value->id)==1){ ?>
                                    未领奖
                                    <!-- <div class="modify"><a class="modifybtn" href="javascript:lingqu(<?php //echo $value->id; ?>);"><span>修改</span></a></div> -->
                                    <div class="modify"><a class="modifybtn" href="<?php echo $this->createUrl('luckyApp/taikanglingqu',array('id'=>$value->id));?>"><span>修改</span></a></div>
                                <?php }?>
                                <!-- 泰康人寿 -->


                                <!-- 其余红包 -->
                            <?php }else{
                                if($value->deal_state!=2){ ?>
                                    未领奖
                                <?php }else{ ?>
                                    已领奖<?php if(in_array($value->prize_id,$value->entityList)&&$value->getLuckyShopCode($value->id)!=false){ echo '<br/>优惠码：'.$value->getLuckyShopCode($value->id);}?>
                                <?php } }?>
                            <!-- 其余红包 -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
				</table>

				<p class="activity_title">温馨提示</p>
				<p>1、泰康人寿奖品说明：</p>
				<p>(1)"飞常保"和"铁定保"只能二选一，投保前请仔细阅读《泰康人寿投保须知》。</p>
				<p>(2)理赔和保险责任由泰康保险公司承担，时效为一年，中奖后请注意填写完整的投保信息。</p>
				<p>(3)中奖人可以为自己或亲朋好友领取此保险，但每一位身份证ID和手机只能领取一份保险名额。</p>
				<p>(4)投保信息提交成功后，泰康人寿将会在7个工作日内对投保信息进行审核处理；如果投保成功，泰康保险将会发送成功短信至投保人手机，并发送电子保单至投保人邮箱。</p>
				<p>(5)领奖情况状态说明：</p>
				<p>未领取：中奖用户未及时提交投保信息，可在中奖后24小时后内有一次机会完善信息领取。</p>
				<p>已过期：中奖用户未及时提交投保信息，并超过24小时。</p>
				<p>已提交：中奖用户提交投保信息成功。</p>
				<p>领取成功：投保成功，获得一年泰康人寿免费意外险。</p>
				<p>领取失败：投保失败，填写的投保信息已经投保过，不能重复领取；或者填写的投保信息不满足投保条件。</p>
				<p>(6)本保险的最终解释权归泰康人寿保险股份有限公司。</p>
				<p>2、景点或酒店电子优惠劵，点击下面对应优惠商家查看奖品详情和使用说明：</p>

				<div class="href_txt">
                    <p><a href="/luckyApp/christmasShuoMingHuameida">&gt;&gt;&nbsp;深圳豪派特华美达酒店</a></p>
                    <p><a href="/luckyApp/christmasShuoMingQuyuan">&gt;&gt;&nbsp;深圳趣园私人酒店公寓</a></p>
                    <p><a href="/luckyApp/christmasShuoMingSanjiaozhou">&gt;&gt;&nbsp;惠州巽寮湾海岛三角洲</a></p>
                    <p><a href="/luckyApp/christmasShuoMingLizigongguan">&gt;&gt;&nbsp;惠州丽兹公馆</a></p>
                    <p><a href="/luckyApp/christmasShuoMingFlyvilla">&gt;&gt;&nbsp;惠州罗浮山彩别院</a></p>
                    <p><a href="/luckyApp/christmasShuoMingHailingdao">&gt;&gt;&nbsp;阳江颐景花园彩悦皓雅度假公寓</a></p>
                    <p><a href="/luckyApp/christmasShuoMingTaihutiancheng">&gt;&gt;&nbsp;苏州太湖天城酒店公寓</a></p>
                    <p><a href="/luckyApp/christmasShuoMingWonderland">&gt;&gt;&nbsp;江西婺源清风仙境</a></p>
				</div>

				<p>3、饭票中奖后立刻发放到“我的饭票”。</p>
				<p>饭票可用于：缴纳物业费和停车费，预缴物业费，商品交易，手机充值。</p>
				<p>您可以在"我——我的饭票"中查看饭票余额。</p>
				<p>4、彩之云享有本次活动在法律范围内的最终解释权。</p>

				<div class="return_btn">返 回</div>
			</div>
			<div class="top_bg">
				<img src="<?php echo F::getStaticsUrl('/home/oneSecond/'); ?>images/footer_bg.png" />
				<!--<p class="footer_advice">★注：彩之云在法律规定的范围内具有最终解释权</p>-->
			</div>
		</div>

		<script type="text/javascript">
		$(function(){
				$('.return_btn').click(function(){
					window.history.back();
				})
		})
		</script>
	</body>

</html>