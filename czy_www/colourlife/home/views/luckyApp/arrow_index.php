<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title>jQuery大转盘抽奖特效代码- JS代码网</title>
<style>
.rotary { position: relative; width: 854px; height: 504px; margin: 0 auto; background: #d71f2e url("<?php echo F::getStaticsUrl('/common/images/lucky/arrow/bg1.png')?>");}
.rotaryArrow { position: absolute; left: 181px; top: 104px; width: 294px; height: 294px; cursor: pointer; background-image: url("<?php echo F::getStaticsUrl('/common/images/lucky/arrow/arrow.png')?>");}

.list { position: absolute; right: 48px; top: 144px; width: 120px; height: 320px; overflow: hidden;}
.list h3 { display: none;}
.list ul { list-style-type: none;}
.list li { height: 37px; font: 14px/37px "Microsoft Yahei"; color: #ffea76; text-indent: 25px; background: url("<?php echo F::getStaticsUrl('/common/images/lucky/arrow/user.png')?>") 0 no-repeat;}

.result { display: none; position: absolute; left: 130px; top: 190px; width: 395px; height: 118px; background-color: rgba(0,0,0,0.75); filter: alpha(opacity=90);}
.result a { position: absolute; right: 5px; top: 5px; width: 25px; height: 25px; text-indent: -100px; background-image: url("<?php echo F::getStaticsUrl('/common/images/lucky/arrow/close.png')?>"); overflow: hidden;}
.result p { padding: 45px 15px 0; font: 16px "Microsoft Yahei"; color: #fff; text-align: center;}
.result em { color: #ffea76; font-style: normal;}
</style>
</head>

<body>
<h1>抽奖效果演示</h1>

<!-- Demo start  -->
<div class="rotary">
	<div class="rotaryArrow" id="rotaryArrow"></div>
	<div class="list">
		<h3>中奖名单</h3>
		<ul>
			<li>js-css.cn</li>
			<li>1569****851</li>
			<li>1515****206</li>
			<li>1550****789</li>
			<li>1370****627</li>
			<li>1828****215</li>
			<li>1589****572</li>
			<li>1583****825</li>
			<li>1396****805</li>
			<li>1332****261</li>
			<li>1884****863</li>
			<li>1384****955</li>
			<li>1897****137</li>
			<li>1342****973</li>
			<li>1558****071</li>
			<li>1554****168</li>
			<li>1562****018</li>
			<li>1805****856</li>
			<li>1354****809</li>
			<li>1383****364</li>
		</ul>
	</div>
	<div class="result" id="result">
		<p id="resultTxt"></p>
		<a href="javascript:" id="resultBtn" title="关闭">关闭</a>
	</div>
</div>
<!-- Demo end -->
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/arrow/jquery-1.8.3.min.js'); ?>"></script>
<script src="<?php echo F::getStaticsUrl('/common/js/lucky/arrow/jquery.rotate.min.js'); ?>"></script>

<!-- <script src="js/jquery-1.8.3.min.js"></script>
<script src="js/jquery.rotate.min.js"></script> -->
<script>
$(function(){
	var $rotaryArrow = $('#rotaryArrow');
	var $result = $('#result');
	var $resultTxt = $('#resultTxt');
	var $resultBtn = $('#result');

	$rotaryArrow.click(function(){
		var data = [0, 1, 2, 3, 4, 5, 6, 7];
		data = data[Math.floor(Math.random()*data.length)];
		switch(data){
			case 1: 
				rotateFunc(1,87,'恭喜您获得了 <em>1</em> 元代金券');
				break;
			case 2: 
				rotateFunc(2,43,'恭喜您获得了 <em>5</em> 元代金券');
				break;
			case 3: 
				rotateFunc(3,134,'恭喜您获得了 <em>10</em> 元代金券');
				break;
			case 4: 
				rotateFunc(4,177,'很遗憾，这次您未抽中奖，继续加油吧');
				break;
			case 5: 
				rotateFunc(5,223,'恭喜您获得了 <em>20</em> 元代金券');
				break;
			case 6: 
				rotateFunc(6,268,'恭喜您获得了 <em>50</em> 元代金券');
				break;
			case 7: 
				rotateFunc(7,316,'恭喜您获得了 <em>30</em> 元代金券');
				break;
			default:
				rotateFunc(0,0,'很遗憾，这次您未抽中奖，继续加油吧');
		}
	});

	var rotateFunc = function(awards,angle,text){  //awards:奖项，angle:奖项对应的角度
		$rotaryArrow.stopRotate();
		$rotaryArrow.rotate({
			angle: 0,
			duration: 5000,
			animateTo: angle + 1440,  //angle是图片上各奖项对应的角度，1440是让指针固定旋转4圈
			callback: function(){
				$resultTxt.html(text);
				$result.show();
			}
		});
	};

	$resultBtn.click(function(){
		$result.hide();
	});
});
</script>










<!-- 以下是统计及其他信息，与演示无关，不必理会 -->
<style>
* { margin: 0; padding: 0;}
body { font-family: Consolas,arial,"宋体";}
h1 { width: 900px; margin: 40px auto; font: 32px "Microsoft Yahei"; text-align: center;}
.explain, .dowebok-explain { margin-top: 20px; font-size: 14px; text-align: center; color: #f50;}

.vad { margin: 50px 0 5px; font-family: Consolas,arial,宋体; text-align:center;}
.vad a { display: inline-block; height: 36px; line-height: 36px; margin: 0 5px; padding: 0 50px; font-size: 14px; text-align:center; color:#eee; text-decoration: none; background-color: #222;}
.vad a:hover { color: #fff; background-color: #000;}
.thead { width: 728px; height: 90px; margin: 0 auto; border-bottom: 40px solid #fff;}

.code { position: relative; margin-top: 100px; padding-top: 41px;}
.code h3 { position: absolute; top: 0; z-index: 10; width: 100px; height: 40px; font: 16px/40px "Microsoft Yahei"; text-align: center; cursor: pointer;}
.code .cur { border: 1px solid #f0f0f0; border-bottom: 1px solid #f8f8f8; background-color: #f8f8f8;}
.code .h31 { left: 0;}
.code .h32 { left: 102px;}
.code .h33 { left: 204px;}
.code .h34 { left: 306px;}
.code { width: 900px; margin-left: auto; margin-right: auto;}
pre { padding: 15px 0; border: 1px solid #f0f0f0; background-color: #f8f8f8;}
.f-dn { display: none;}
</style>
<script type="text/javascript">
    /*左边侧栏广告*/
var cpro_id = "u1715385";
</script>
<script src="http://cpro.baidustatic.com/cpro/ui/f.js" type="text/javascript"></script>

<div style="display:none">
	<script type="text/javascript">
	var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
	document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F6f798e51a1cd93937ee8293eece39b1a' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_5718743'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s9.cnzz.com/stat.php%3Fid%3D5718743%26show%3Dpic2' type='text/javascript'%3E%3C/script%3E"));</script>
</div>
</body>
</html>
