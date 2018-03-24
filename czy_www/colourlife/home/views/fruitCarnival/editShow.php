<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $title;?>--编辑页</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>css/layout.css" rel="stylesheet" />
				<link href="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>css/neighbor.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/fruitCarnival/css/webuploader.css'); ?>">
		<script src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>js/jquery-1.11.3.js" type="text/javascript"></script>
		<script src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>js/upload.js" type="text/javascript"></script>
		<script src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>js/webuploader.js" type="text/javascript"></script>
	</head>

	<body>
		<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'post-form',
    'enableAjaxValidation' => false,        //是否启用ajax验证
    'action' => Yii::app()->createUrl('FruitCarnival/EditShow',array('type'=>$type)),   //这里我把action重新指向site控制器的login动作
)); ?>
		<div class="send_out">
			<div class="send_out1a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/page1_bg1.jpg">
			</div>
			<div class="send_out2a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php if ($type=='xyrg'){?>page2<?php }else{?>page1<?php }?>_bg2.jpg">
			</div>
			<div class="send_out3a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>_bg3.jpg">
			</div>
			<div class="send_out4a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/page1_bg04.jpg">
			</div>
			<div class="send_out5a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/page1_bg05.jpg">
			</div>
			<div class="send_out6a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/page1_bg06.jpg">
			</div>
			<div class="send_out7a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/page1_bg07.jpg">
			</div>
			<div class="send_out8a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/page1_bg08.jpg">
			</div>
			<div class="send_out9a">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/page1_bg09.jpg">
			</div>
			<?php
//清除图片
if (Yii::app()->user->hasState('images')) {
    Yii::app()->user->setState('images', null);
}
?>
			<div class="content" style="font-size: 10px;">
				<div class="content_top">
                	<?php echo $form->textArea($model, 'content', array("maxlength" => "150", 'placeholder' => '最多输入150字')); ?>
                </div>
				<div id="uploader" class="uploadimg_box">
                    <div class="queueList">
                        <div id="dndArea" class="placeholder">
                            <div id="filePicker"></div>
                        </div>
                    </div>
                    <div class="statusBar">
                        <div class="progress" style="display:none;">
                            <span class="text">0%</span>
                            <span class="percentage"></span>
                        </div>
                        <div class="info"></div>
                        <div class="btns">
                            <div id="filePicker2" style="display:none;"></div>
                        </div>
                    </div>
   				</div>
    
   			<div class="post_btn"></div>
		    <div class="opacity" style="display:none;font-size: 10px;">
		        <div class="alertcontairn">
		            <div class="textinfo">
		                <p>错误操作</p>
		            </div>
		            <div class="pop_btn"><a href="javascript:void(0)" class="closeOpacity">确定</a></div>
		        </div>
		    </div>
		
		    <!--发帖审核弹出框 start-->
		    <div class="audit" style="display:none;font-size: 10px;">
		        <div class="alertcontairn">
		            <div class="textinfo">
		                <p>发帖成功，内容审核后会显示在首页</p>
		            </div>
		            <div class="pop_btn"><a href="javascript:void(0)" class="closeAudit">确定</a></div>
		        </div>
		    </div>
		    <!--发帖审核弹出框 end-->				
			</div>
			<div class="footer">
				<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/button_send.png">
                <input type="submit" name="submit"/>
			</div>
		</div>
<?php $this->endWidget(); ?>		
<script type="text/javascript">
    $('.closeAudit').click(function(){
        $('.audit').hide();
        $("#post-form").submit();
    })

    function selectTopic(style, that) {
        $('.topic_style_area').text(style);
        $('.select_topic_style dd a').css('color', '#494949');
        that.css('color', '#00f');
        var value1=that.attr('rel');
        $('.group_id').val(value1);

    }

    $('.downstyle').click(function () {
        $('.select_topic_style dd').eq(2).nextAll('dd').slideDown(500);
        $(this).hide();
        $('.upsytle').show();
    });

    $('.upsytle').click(function () {
        $('.select_topic_style dd').eq(2).nextAll('dd').slideUp(500);
        $(this).hide();
        $('.downstyle').show();
    })

    window.onload = function () {
        var style = $('.select_topic_style dd').eq(0).text();
        $('.topic_style_area').text(style);	//话题类型默认显示第一个

        $('.select_topic_style dd').eq(2).nextAll('dd').hide();//第三个以后的隐藏
    }

    function alertDim(str){
        $('.textinfo p').text(str);
        $('.opacity').show();
    }

    $('.closeOpacity').click(function(){
        $('.opacity').hide();

    })
</script>
	</body>
	
</html>

