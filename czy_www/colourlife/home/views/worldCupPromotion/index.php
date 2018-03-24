<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
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


       <?php echo $this->renderPartial($form, array('model'=>$model,'promotionid'=>$promotionid)); ?>

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
