<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>平台招商申请-彩之云</title>
		<link href="<?php echo F::getStaticsUrl('/common/css/mobile.css'); ?>" rel="stylesheet">
        <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
	</head>
	<body>
		<div class="main">
            <div class="focus_images">
              <div class="sur_ix_by_skyScrollPicBox skyScrollPicBox">
              <?php if(count($adList)>0){?>
                <div class="skyScrollPicBox_btns">
                <?php foreach ($adList as $ad){?>
                  <a class="skyScrollPicBox_btn"></a>
                  <?php }?>
                </div>
                <div class="skyScrollPicBox_pics">
                <?php foreach ($adList as $ad){?>
                   <a href="/platformShopApply/<?php echo $ad->id?>" class="skyScrollPicBox_picLink">
                    <img src="<?php echo $ad->pictureUrl?>" style="width:320px; height:100%" class="skyScrollPicBox_pic">
                  </a>
                  <?php }?>
                </div>
              </div>
              <?php }?>
            </div>
            <dl class="investment_table">
              <?php if(count($typeList)>0){?>
              	<?php foreach ($typeList as $k=>$type){ ?>

                      <dd>
                      <a href="/platformShopApply/<?php echo $type->id?>">
                          <span class="investment_img"><img src="<?php echo $type->pictureUrl;?>" style="width:60px;height:60px;" /></span>
              <span class="investment_txt"><i><?php echo $type->title;?></i>
                  <?php echo $type->content;?>
              </span>
                      </a>
                      </dd>

              	<?php }?>
              <?php }?>
              </dl>

            <a href="tel:075536813739" class="contact_number">
              <span class="investment_txt">
                欲知更多详情：0755-36813739
              </span>
                <span class="investment_img"><img src="<?php echo F::getStaticsUrl('/common/images/commerce/call.jpg'); ?>" /></span>
            </a>
            <p style="font-size:12px; padding-bottom:20px;">欢迎登陆：www.colourlife.com</p>
            
		</div>
        <script>
            (function($) {
                $.fn.cl_skyScrollPicBox = function(opt) {
                    var _opt = $.extend({}, $.fn.cl_skyScrollPicBox.defaults, opt);

                    return this.each(function() {
                        var	_this = $(this),
                            _btn = _this.find(".skyScrollPicBox_btn"),
                            _picBox = _this.find(".skyScrollPicBox_pics"),
                            _pic = _picBox.find(".skyScrollPicBox_pic"),
                            _pic_w = _pic.width(),
                            _pic_h = _pic.height(),
                            _pic_len = _pic.size(),
                            _fn_go,
                            _fn_run,
                            _curIndex = 0,
                            _id,
                            _delay = _opt.delay;

                        function _fn_go(tarIndex) {
                            if (_curIndex === _pic_len - 1) {
                                _curIndex = 0;
                            } else {
                                _curIndex++;
                            }

                            if (!isNaN(tarIndex)) {
                                _curIndex = tarIndex;
                            }

                            _btn.eq(_curIndex).addClass("current").siblings(".current").removeClass("current");

                            _picBox.stop().animate({
                                "margin-left"	: "-" + (_curIndex * _pic_w) + "px"
                            });
                        };

                        function _fn_run() {
                            _fn_go();

                            _id = setTimeout(_fn_run, _delay);
                        };

                        // 初始化
                        (function() {
                            _picBox.css("width", _pic_w * _pic_len + "px");

                            _btn.eq(0).addClass("current");
                        })();

                        // 节点动作
                        (function() {

                            // 切换
                            _btn.click(function() {
                                var __this = $(this),
                                    __index = __this.index();

                                _fn_go(__index);
                            });

                            // 鼠标移入，定时滚动消失
                            _this.mouseenter(function() {
                                clearTimeout(_id);
                            });

                            // 鼠标移出，定时滚动开启
                            _this.mouseleave(function() {
                                _id = setTimeout(_fn_run, _delay);
                            });
                        })();

                        // 函数执行
                        (function() {

                            _id = setTimeout(_fn_run, _delay);
                        })();
                    });
                };

                $.fn.cl_skyScrollPicBox.defaults = {
                    "delay"		: 3000
                };
            })(jQuery);


            // 广告滚动
            $(".sur_ix_by_skyScrollPicBox").cl_skyScrollPicBox({
                "delay": 3000
            });

        </script>
	</body>
</html>
