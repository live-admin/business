<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $model->share_title;?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/ReFontsize.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>css/layout.css"/>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>js/jquery-1.11.3.js" ></script>
    <script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/lanternFestival/');?>js/ShareSDK.js"></script>
    <script language="javascript" type="text/javascript">
    function showShareMenuClickHandler()
    {
        
        var u = navigator.userAgent, app = navigator.appVersion;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        
         if(isAndroid){
             try{
                 var version = jsObject.getAppVersion();
//                 alert(version);
             }catch(error){
                  //alert(version);
                  if(version !="4.3.6.2"){
                    alert("请到App--我--检查更新,进行更新");
                    return false;
                }
             }finally {}
            $sharesdk.open("85f512550716", true);
        }
        
        if(isiOS){
            try{
                if(getAppVersion && typeof(getAppVersion) == "function"){
                     getAppVersion();
                     var vserion = document.getElementById('vserion').value;
                    }
                }catch(e){
                    
                }
           
            if(vserion){
                //alert(vserion);
                $sharesdk.open("62a86581b8f3", true); 
            }else{  
                alert("请到App--我--检查更新,进行更新");
                return false;
             }  
            }
        
        var params = {
            "text" : "花样游记活动",
            "imageUrl" : "http://cc.colourlife.com/common/images/logo.png",
            "url":"<?php echo F::getHomeUrl('/ColourTravel/TravelSharePage/?ts_id='.$model->id.'&s=1'); ?>",
            "title" : "花样游记",
            "titleUrl" : "<?php echo F::getHomeUrl('/ColourTravel/TravelSharePage/?ts_id='.$model->id.'&s=1'); ?>",
            "description" : "描述",
            "site" : "彩之云",
            "siteUrl" : "<?php echo F::getHomeUrl('/ColourTravel/TravelSharePage/?ts_id='.$model->id.'&s=1'); ?>",
            "type" : $sharesdk.contentType.WebPage
        };
       $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {
        }); 
         
    };
    </script>
</head>
<body>
<div class="conter">
    <div class="user">
        <div class="user_header">
            <div class="user_header_box1a">
                <p><?php echo $model->share_title;?></></p>
                <span>作者：<?php echo $name;?></span>
                <span>创建时间：<?php echo date('Y.m.d', $model->create_time);?></span>
                <div class="clear"></div>
            </div>
            <!-- <div class="user_header_box2a praise">
                <a href="javascript:void(0)" class="user_header_box2a_round">
                    <span>赞</span>
                    <span>999</span>
                </a>
            </div> -->
            <?php $is_like=TravelLike::model()->getCheckLike($model->id,22,$user_id);

                if($model->share_like==0){?>

            <div class="user_header_box2a_none praise" data-count="<?php echo $model->share_like;?>" data-id="<?php echo $model->id;?>"  data-flag="<?php echo $is_like;?>">
                <a href="javascript:void(0)" class="user_header_box2a_round_none">
                    <span>赞</span>
                </a>
            </div>
            <?php }else{?>
	        <div class="user_header_box2a_none praise" data-count="<?php echo $model->share_like;?>" data-id="<?php echo $model->id;?>" data-flag="<?php if($is_like){echo $is_like;}else{echo '4444';} ?>">
	        <a href="javascript:void(0)" class="user_header_box2a_round">
	            <span>赞</span>
	            <span><?php echo $model->share_like; ?></span>
	        </a>
	        </div>
            <?php }?>
            <div class="user_header_box3a">
                <!-- url是客户端邀请好友页面 -->
                <a href="javascript:mobileJump('Invite');" class="user_header_box3a_img">
                    <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/invitation.png">
                </a>
            </div>
        </div>
        <div class="user_banner_top">
            <p><?php echo $model->share_content;?></p>
            <?php $img_arr=explode(";",$model->share_img);foreach($img_arr as $v){
            		if (empty($v)){
						continue;
					}
            	?>
            <img src="<?php echo F::getUploadsUrl().$v;?>">
            <?php }?>
        </div>
        <div class="user_footer">
            <a href="javascript:void(0)">
                <img src="<?php echo F::getStaticsUrl('/home/colourTravel/'); ?>images/share_01.png">
            </a>
        </div>
    </div>
    <input style="display: none" id="vserion" />
</div>
<div class="mask hide" ></div>
<!--彩生活特供优惠卷开始-->
<div class="Popup hide">
    <div class="Popup_img">
        <div class="Popup_img_box1a">
            <p>￥<span>30</span></p>
        </div>
        <div class="Popup_img_box2a">
            <p>彩生活特供优惠卷</p>
            <p>满100减30</p>
        </div>
    </div>
    <div class="Popup_p">
        <p>恭喜您获得星晨旅游代金券一张,请在个人中心“我的卡券”查看</p>
    </div>
</div>
<!--彩生活特供优惠卷结束-->
<!--星晨旅游优惠卷开始-->
<div class="Popup_stars hide">
    <div class="Popup_stars_img">
        <div class="Popup_stars_img_box1a">
            <p>￥<span>200</span></p>
        </div>
        <div class="Popup_stars_img_box2a">
            <p>星晨旅游邮轮代金券</p>
            <p>200元</p>
        </div>
    </div>
    <div class="Popup_stars_p">
        <p>恭喜您获得星晨旅游代金券一张,请在个人中心“我的卡券”查看</p>
    </div>
</div>
<!--星晨旅游优惠卷结束-->
<script>

    function mobileJump(cmd)
    {
        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
            var _cmd = "http://www.colourlife.com/*jumpPrototype*colourlife://proto?type=" + cmd;
            document.location = _cmd;
        } else if (/(Android)/i.test(navigator.userAgent)) {
            var _cmd = "jsObject.jumpPrototype('colourlife://proto?type=" + cmd + "');";
            eval(_cmd);
        } else {

        }
    }

    $(document).ready(function(){

        //分享按钮
        $(".user_footer").click(function(event) {
            //alert("分享按钮");
            showShareMenuClickHandler();
            $.ajax({
                type: 'POST',
                url: '/ColourTravel/Share',
                data:'type=22&title_id='+'<?php echo $model->id;?>',
                dataType: 'json',
                success: function (result) {
                    if(result.status==1)
                    {
                        //alert(result.param);
                        var str='<p>'+result.param+'</p><p>'+result.price+'</p>';
                        $(".Popup_img_box2a").html(str);
                        $(".Popup_img_box1a").html('<p>￥<span>'+result.num+'</span></p>');
                        $(".Popup_p").html('<p>恭喜您获得'+result.param+'一张,请在个人中心“我的卡券”查看</p>');


                        setTimeout(share_suceess_prize,3000);
                    }
                }
            });
        });

        //share_suceess_prize();
        //分享成功后有几率获得礼券
        function share_suceess_prize(){
            $(".mask").removeClass("hide");
            $(".Popup").removeClass("hide");
            /*固定弹窗*/
            var top = $("body").offset().top;

            $("body").css("position","fixed");
            $("body").scrollTop(top);
        }

        //关闭弹窗
        $(".mask").click(function(){
            $(".mask").addClass("hide");
            $(".Popup").addClass("hide");
            /*固定弹窗*/
            var top = $("body").offset().top;

            $("body").css("position","relative");
            $("body").scrollTop(top);
        });


        // 	//已经有人点过赞----点赞
        // $(".user_header_box2a").click(function(){

        // 	var count = $(this).find("a span:eq(1)").text();
        // 	count++;
        // 	$(this).find("span:eq(1)").text(count);

        // });
        // //没有人点过赞----点赞
        // $(".user_header_box2a_none").click(function(){

        // 	$(this).addClass('hide');
        // 	$(".user_header_box2a").removeClass('hide');
        // 	var count = $(this).find("span:eq(1)").text();
        // 	count++;
        // 	$(this).find("span:eq(1)").text(count);

        // });



        //ajax获取当前用户是否可以点赞（flag为true的时候表示可以点赞）

            $(".praise").click(function(){
                var $this=$(this);
                //ajax获取当前用户是否可以点赞（flag为true的时候表示可以点赞）
                var flag = $this.attr("data-flag");
                if(flag=='1'){
                    var title_id = $this.attr('data-id');
                    $.ajax({
                        type: 'POST',
                        url: '/ColourTravel/ClickLike',
                        data: 'title_id='+title_id+'&type='+'22',
                        dataType: 'json',
                        success: function (result){
                            if(result.status==1){
                                if($this.hasClass('user_header_box2a'))
                            {
                                var count = $this.children().find("span:eq(1)").text();
                                count++;
                                $this.children().find("span:eq(1)").text(count);
                            }
                            else if($this.hasClass('user_header_box2a_none'))
                            {
                            	$this.removeClass('user_header_box2a_none');
                            	$this.addClass('user_header_box2a');
                            	$this.children().removeClass('user_header_box2a_round_none');
                            	$this.children().addClass('user_header_box2a_round');
                            	$this.children().children().after('<span>1</span>');
                            }
                        }else {
                            alert(result.param);
                            return false;
                        }
                        }

                    });
                }else{
                    alert('您今天已经点过赞了,明天再来点赞哦！！！');
                    return false;
                }

            });
    });
</script>
</body>
</html>

