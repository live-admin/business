<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>广场舞大赛首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telphone=no" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/dance/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/v2016/dance/');?>js/PCASClass.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/dance/');?>css/layout.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/dance/');?>css/normalize.css">
</head>
<body>
<body>
<div class="conter">
    <div class="conter_ban"></div>
    <form method="post" action="/SquareDance/Save" >
    <div class="banner">
        <div class="banner_top">
            <p>参赛类型</p>
            <p class="choose choose_buttom">个人参赛</p>
            <p class="choose">团队参赛</p>
        </div>
        <input type="hidden" name="type" value="1" />

        <div class="banner_bottom">

            <!--个人报名-->
            <div class="banner_bottom1a">
                <!-- 团队开始-->
                <p style="margin-left: -0.49rem;" class="p1 hide">
                    <label>团队人数</label>
                    <select name="number">
                        <?php for($i=2;$i<25;$i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </p>
                <!-- 团队结束-->

                <p id="name">
                    <label>姓名</label>
                    <input type="texe" name="name" value="<?php echo $name; ?>" placeholder="填写真实姓名">
                </p>
                <p>
                    <label>性别</label>
                    <select name="sex">
                        <option value="女">女</option>
                        <option value="男" <?php echo $sex == '男' ? 'selected' : ''; ?>>男</option>
                    </select>
                </p>


                <p style="width: 100%; padding:0.2rem 0rem 0.2rem 1.0rem!important;">
                    <label>地区</label>
                    <select style="width:1.1rem;font-size: 0.20rem;" name="province"></select>
                    <select style="width:1.1rem;font-size: 0.20rem;" name="city"></select>
                    <select style="width:1.1rem;font-size: 0.20rem;" name="area"></select><br>
                </p>
                <p style="margin-left: -0.49rem;">
                    <label>手机号码</label>
                    <input class="cal" type="number" name="tel" value="<?php echo $tel; ?>" placeholder="填写手机号码">
                </p>
                <p style="margin-left: -0.75rem;">
                    <label>身份证号码</label>
                    <input class="identity" type="texe" name="card_no" placeholder="填写身份证号码">
                </p>

                <p style="margin-left: -0.49rem;" class="p2">
                    <label>愿做领舞</label>
                    <select name="team_leader">
                        <option value="1">是</option>
                        <option calue="0">否</option>
                    </select>
                </p>
                <p >
                    <input type="button" value="提交" / class="btn">
                </p>
                <p style="text-align: right;padding-top: 0;">
                    <a href="/SquareDance/Rule" style="font-size: 0.24rem;color: #0779AD;margin-left: 3.3rem;display: block;width: 1.2rem;text-decoration: underline;">活动规则</a>
                </p>
            </div>
        </div>
    </div>
    </form>
</div>
</div>
<script>
    //省、市、区选择
    new PCAS("province","city","area","<?php echo $province; ?>","<?php echo $city; ?>","<?php echo $area; ?>");

    //个人、团队报名切换
    $(document).ready(function(){
        //号码校验
        function numberCheck(temp){
            var a=/^[1]{1}[0-9]{10}$/;

            if(!a.test(temp))
            {
                alert("请填写正确的手机号码");
                return false;
            }

            return true;
        }

        //身份证号确认
        $(".btn").click(function(){
            var intity = $(".identity").val();
            var telphoneNum = $(".cal").val();
            if(isCardNo(intity) && numberCheck(telphoneNum)){
                $.ajax({
                    url: "/SquareDance/Save",              //请求地址
                    type: "POST",                       //请求方式
                    data: $("form").serialize(),        //请求参数
                    dataType: "json",
                    success: function (data) {
                        // 此处放成功后执行的代码
                        if (1 == data.retCode) {
                            alert(data.data);
                        }
                        else {
                            alert(data.retMsg);
                        }
                    },
                    error: function (status) {
                        // 此处放失败后执行的代码
                        alert('系统异常，请联系管理员');
                    }
                });

            }
        });

        //身份证校验
        function isCardNo(card)
        {
            // 身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
            var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
            if(reg.test(card) === false)
            {
                alert("请填写正确的身份证号码");
                return  false;
            }

            return true;
        }

        //切换
        $(".banner_top p:eq(1)").click(function(){
            $(".banner_top p:eq(1)").addClass("choose_buttom");
            $(".banner_top p:eq(2)").removeClass("choose_buttom");
            $(".p1").addClass("hide");
            $(".p2").removeClass("hide");
            $('input[name="type"]').val('1');
        });
        $(".banner_top p:eq(2)").click(function(){
            $(".banner_top p:eq(1)").removeClass("choose_buttom");
            $(".banner_top p:eq(2)").addClass("choose_buttom");
            $(".p1").removeClass("hide");
            $(".p2").addClass("hide");
            $('input[name="type"]').val('0');
        });
    });
</script>
</body>
</html>