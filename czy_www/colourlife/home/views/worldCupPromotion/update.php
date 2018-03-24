<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="<?php echo F::getStaticsUrl('/common/css/lucky/june/lottery.css'); ?>" rel="stylesheet">
<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>

<?php if($isGuest){?>
<script type="text/javascript">
	var isGuest=1;
	var loginHref="<?php echo $href;?>";
</script>
<?php }else{?>
<script type="text/javascript">
	var isGuest=0;
</script>
<?php }?>


</head>

<body>

<div class="lottery_topic">
    <div class="eight_mychoice">

        <div class="eight_top">
            <h3 class="greenfont"><?php echo $title;?></h3>
            <div class="greenline"></div>

       <?php echo $this->renderPartial($form, array('model'=>$model,'promotionid'=>$promotionid,'myguess'=>$myguess)); ?>


            <!--弹出框 start-->
            <div class="opacity" style="display:none;">
                <div class="alertcontairn" style="margin-top:60%;">
                    <div class="alertcontairn_content">
                        <div class="textinfo">
                            <p id="tips">
                                竞猜成功！<br />
                            </p>
                        </div>
                        <div class="pop_btn">
                            <a href="javascript:void(0);" class="closeOpacity"><span>确定</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--弹出框 end-->

         </div>
    </div>

</div>
<script type="text/javascript">
    function tips(i,e){
        $('#tips').text(i);
        $('.opacity').show();
        var alertheight=$(".alertcontairn").height()/2;
        alertpos=e.pageY-alertheight;
        $(".alertcontairn").css('marginTop',alertpos+'px');
    }

    $('.closeOpacity').click(function(){
        $('.opacity').hide();
    });
</script>
</body>
</html>
