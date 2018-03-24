<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>花样游记-游记列表-用户</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/ReFontsize.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>css/layout.css"/>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/jquery-1.11.3.js" ></script>
</head>
<body>
<div class="contaner">
    <!--头部栏-->
    <div class="top">
        <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/banna01.jpg" />
    </div>
    <!--活动内容-->
    <div class="content">

        <?php 
        $package=array();
        foreach($travelShare as $v){
            $is_like=TravelLike::model()->getCheckLike($v->id,22,$user_id);
            if (($v->customer_id==$user_id) && ($v->is_receive==1) && (date("Ymd",$v->create_time)==date("Ymd"))){
				if (!empty($v->prize_name)){
					$package=TravelShare::model()->getPackageData($v->id,$v->prize_name,$mobile);
				}
			}
            ?>
        <div class="one">
            <a href="<?php echo $this->createUrl("ShareDetail", array('id' => $v->id));?>">
                <img src="<?php $img_arr=explode(";",$v->share_img);echo F::getUploadsUrl().$img_arr[0];?>" />
                <p class="one-p1"><?php echo $v->share_title;?></p>
                <p class="one-p2"><?php echo mb_substr($v->share_content,0,100,'utf-8');?></p>
            </a>

            <!--点赞-->
            <div class="dianzan" data-id="<?php echo $v->id;?>" data-flag="<?php echo $is_like?>" data-count="<?php echo $v->share_like;?>">

                <div class="zan">
                    <div class="font-zan">赞</div>
                    <div class="num"><?php echo $v->share_like;?></div>
                </div>
            </div>
        </div>

        <?php }?>


    </div>

    <div class="add">
        <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/add.png" />
        <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/talk.png" />
    </div>
    <div class="add_txt">
        <p>分享游记，点赞最多的十篇将</p>
        <p>获赠免费双人游哦～</p>
    </div>
	
	<!--遮罩层开始-->
	<div class="mask <?php if (empty($package)){?>hide<?php }?>"></div>
	<!--遮罩层结束-->
	<!--领包1开始-->
	<?php if (!empty($package)){
			if ($package['type']==1){
		?>
	<div class="package">
		<div class="package_img">
			<div class="package_p">
				<p>环球精选全场通用满300元减30元</p>
				<p>环球精选全场通用满400元减40元</p>
			</div>
		</div>
		<div class="Popup_p">
			<p>恭喜您获得优惠券礼包,请在个人中心“我的卡券”查看</p>
		</div>
		
	</div>
	<?php }elseif ($package['type']==2){?>
	<div class="package">
		<div class="package_img">
			<div class="package_p">
				<p>彩生活特供满100元减20元</p>
				<p>彩生活特供满200元减50元</p>
			</div>
		</div>
		<div class="Popup_p">
			<p>恭喜您获得优惠券礼包,请在个人中心“我的卡券”查看</p>
		</div>
	</div>
	<?php }
	}?>
	
</div>
</body>
<script type="text/javascript">
    $(document).ready(function(){

    	//关闭弹窗
        $(".mask").click(function(){
            $(".mask").addClass("hide");
            $(".package").addClass("hide");
            /*固定弹窗*/
            var top = $("body").offset().top;

            $("body").css("position","relative");
            $("body").scrollTop(top);
        });
    

        $(".dianzan").each(function(index){
            if($(this).attr("data-count") == 0)
            {
                $(this).children().removeClass("zan");
                $(this).children().addClass("zan-display");
                $(this).children().find("div:eq(0)").removeClass("font-zan");
                $(this).children().find("div:eq(0)").addClass("font-zan-display");
                $(this).children().find("div:eq(1)").remove();
            }
        });


        //发布按钮
        $(".add").click(function(event) {
            window.location.href = "<?php echo $this->createUrl("CreateTravel");?>";
        });

        //点赞
        //ajax获取当前用户是否可以点赞（flag为true的时候表示可以点赞）
        $(".dianzan").click(function(){
            var $this=$(this);
            //ajax获取当前用户是否可以点赞（flag为true的时候表示可以点赞）
            var flag = $this.attr("data-flag");
            if (flag==1)
            {
                var title_id=$(this).attr('data-id');


                $.ajax({
                    type: 'POST',
                    url: '/ColourTravel/ClickLike',
                    data: 'title_id='+title_id+'&type='+'22',
                    dataType: 'json',
                    success: function (result) {
                        if(result.status==1)
                        {
                            if($this.children().hasClass('zan'))
                            {

                                var count = $this.find(".num").text();
                                count++;
                                $this.find(".num").text(count);
                            }
                            else if($this.children().hasClass('zan-display'))
                            {
                                    $this.children().removeClass('zan-display');
                                    $this.children().children().removeClass('font-zan-display');
                                    $this.children().addClass('zan');
                                    $this.children().children().addClass('font-zan');
                                    $this.children().children().after('<div class="num">1</div>');

                            }
                        }else
                        {
                            alert(result.param);
                        }
                    }
                });

            }
            else
            {
                alert('您今天已经点过赞了,明天再来点赞哦！！！');
                return false;
            }
        });
    });

    window.onload = function(){
        setTimeout(hideTips,5000);
    };
    function hideTips(){
        $(".add_txt").addClass('hide');
        $(".add img:eq(1)").addClass('hide');
    }
</script>
</html>
