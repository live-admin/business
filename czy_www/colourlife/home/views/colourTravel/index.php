<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>花样游记-首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/ReFontsize.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>css/layout.css" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/jquery-1.11.3.js"></script>
</head>

<body>
<div class="contaner">
    <!--头部栏-->
    <div class="top">
        <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/banna.jpg" />
    </div>
    <!--活动内容-->
    <div class="content">

        <?php foreach($travelIntroduce as $v){
            $is_like=TravelLike::model()->getCheckLike($v->id,11,$user_id);
            ?>

        <div class="one">
            <a href="<?php echo $this->createUrl("TravelDetail", array('id' => $v->id));?>" id="1" >
                <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?><?php echo $v->travel_img;?>" />
                <p class="one-p1"><?php echo $v->travel_title;?></p>
                <p class="one-p2"><?php echo $v->travel_intro;?></p>
            </a>

            <!--点赞-->
            <div class="dianzan" data-id="<?php echo $v->id;?>" data-flag="<?php echo $is_like;?>" data-count="<?php echo $v->travel_like;?>">

                <div class="zan">
                    <div class="font-zan">赞</div>
                    <div class="num"><?php echo $v->travel_like;?></div>
                </div>

            </div>
        </div>
        <?php }?>


    </div>
    <div class="bottom">
        <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/bottom.jpg" /></a>
    </div>
</div>
<!--遮罩层开始-->
<div class="mask hide"></div>
<!--遮罩层结束-->
<!--领包开始-->
<div class="package hide">
    <div class="package_img">
        <div class="package_p">
            <p>彩生活特供满100元减20元</p>
            <p>彩生活特供满200元减50元</p>
            <p>环球精选全场通用满300元减30元</p>
            <p>环球精选全场通用满400元减40元</p>
        </div>
    </div>
    <div class="Popup_p">
        <p>恭喜您获得星晨旅游代金券一张,请在个人中心“我的卡券”查看</p>
    </div>
</div>
<!--领包结束-->
<script type="text/javascript">
    $(document).ready(function($) {
        //点赞

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
                        data: 'title_id='+title_id+'&type='+'11',
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

        //发布自己的游记
        $(".bottom a").click(function() {
            window.location.href = "<?php echo $this->createUrl("shareIndex");?>";
        });

        //用户自己发布游记成功后一定有大礼包
        //release_suceess_prize();

        function release_suceess_prize(){
            $(".mask").removeClass("hide");
            $(".package").removeClass("hide");
            /*固定弹窗*/
            var top = $("body").offset().top;

            $("body").css("position","fixed");
            $("body").scrollTop(top);
        }

        //关闭弹窗
        $(".mask").click(function(){
            $(".mask").addClass("hide");
            $(".package").addClass("hide");
            /*固定弹窗*/
            var top = $("body").offset().top;

            $("body").css("position","relative");
            $("body").scrollTop(top);
        });
    });
</script>
</body>

</html>