<?php
$flag="true";
$date = date('Y-m') . '-01';
$hour = date('H:i:s');
$month = 1;
$end_date =  date('Y-m-d', strtotime($date."+{$month} month -1 day"));
$start_date = strtotime($date . ' 00:00:00');
$end_date = strtotime($end_date . ' 23:59:59');
$re = Examine::model()->find("customer_id=:uid AND create_time>=:start_date AND create_time<=:end_date",array(':uid'=>$_GET['cust_id'], ':start_date'=>$start_date, ':end_date'=>$end_date));
if (!empty($re)) {

    //echo json_encode(array("success"=>0,"data"=>array('msg'=>'一个月只能评论一次哦',"errors"=>array('一个月只能评论一次哦'))));
//                   $userid=$_GET['userid'];
//                   $host=$_SERVER['SERVER_NAME'];
//                   $url="http://$host/examine/error?cust_id=$userid";
//                    header("location: $url");
    // echo "一个月只能评论一次哦";

    $flag="false";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="MobileOptimized" content="320" />
    <title>满意度调查</title>
    <link rel="stylesheet" type="text/css" href="<?php echo F::getStaticsUrl("/home/XieyiApp/css/evaluation.css"); ?>" />
    <script type="text/javascript" src="<?php echo F::getStaticsUrl("/home/XieyiApp/js/jquery.min.js"); ?>"></script>
</head>

<body>

<form  id="sub_form" action="SatisfactionSurvey/postAnswer" method="post"  >
    <div class="evaluation_content">
        <p>请您对本月物业管理和服务进行评价，我们会根据您的意见和建议不断改进，为您提供更加满意的服务。</p>

        <div class="row clearfix">
            <h4>1、请问你对小区的管理，总体印象感到？</h4>
            <div>
                <input id="one_one" type="radio" value="非常满意" name="evaluation_1" />
                <label for="one_one">非常满意</label>
            </div>
            <div>
                <input id="one_two" type="radio" value="比较满意" name="evaluation_1" />
                <label for="one_two">比较满意</label>
            </div>
            <div>
                <input id="one_three" type="radio" value="一般" name="evaluation_1" />
                <label for="one_three">一般</label>
            </div>
            <div>
                <input id="one_four" type="radio" value="不满意" name="evaluation_1" />
                <label for="one_four">不满意</label>
            </div>
            <div>
                <input id="one_five" type="radio" value="非常不满意" name="evaluation_1" />
                <label for="one_five">非常不满意</label>
            </div>
        </div>

        <div class="row clearfix">
            <h4>2、请问您对管理处人员的服务态度感到？</h4>
            <div>
                <input id="two_one" type="radio" value="非常满意" name="evaluation_2" />
                <label for="two_one">非常满意</label>
            </div>
            <div>
                <input id="two_two" type="radio" value="比较满意" name="evaluation_2" />
                <label for="two_two">比较满意</label>
            </div>
            <div>
                <input id="two_three" type="radio" value="一般" name="evaluation_2" />
                <label for="two_three">一般</label>
            </div>
            <div>
                <input id="two_four" type="radio" value="不满意" name="evaluation_2" />
                <label for="two_four">不满意</label>
            </div>
            <div>
                <input id="two_five" type="radio" value="非常不满意" name="evaluation_2" />
                <label for="two_five">非常不满意</label>
            </div>
        </div>

        <div class="row clearfix">
            <h4>3、请问您对小区的保安感到？</h4>
            <div>
                <input id="three_one" type="radio" value="非常满意" name="evaluation_3" />
                <label for="three_one">非常满意</label>
            </div>
            <div>
                <input id="three_two" type="radio" value="比较满意" name="evaluation_3" />
                <label for="three_two">比较满意</label>
            </div>
            <div>
                <input id="three_three" type="radio" value="一般" name="evaluation_3" />
                <label for="three_three">一般</label>
            </div>
            <div>
                <input id="three_four" type="radio" value="不满意" name="evaluation_3" />
                <label for="three_four">不满意</label>
            </div>
            <div>
                <input id="three_five" type="radio" value="非常不满意" name="evaluation_3" />
                <label for="three_five">非常不满意</label>
            </div>
        </div>

        <div class="row clearfix">
            <h4>4、请问您对小区的公共设备（不含电梯）感到？</h4>
            <div>
                <input id="four_one" type="radio" value="非常满意" name="evaluation_4" />
                <label for="four_one">非常满意</label>
            </div>
            <div>
                <input id="four_two" type="radio" value="比较满意" name="evaluation_4" />
                <label for="four_two">比较满意</label>
            </div>
            <div>
                <input id="four_three" type="radio" value="一般" name="evaluation_4" />
                <label for="four_three">一般</label>
            </div>
            <div>
                <input id="four_four" type="radio" value="不满意" name="evaluation_4" />
                <label for="four_four">不满意</label>
            </div>
            <div>
                <input id="four_five" type="radio" value="非常不满意" name="evaluation_4" />
                <label for="four_five">非常不满意</label>
            </div>
        </div>

        <div class="row clearfix">
            <h4>5、请问您对小区的清洁卫生感到？</h4>
            <div>
                <input id="five_one" type="radio" value="非常满意" name="evaluation_5" />
                <label for="five_one">非常满意</label>
            </div>
            <div>
                <input id="five_two" type="radio" value="比较满意" name="evaluation_5" />
                <label for="five_two">比较满意</label>
            </div>
            <div>
                <input id="five_three" type="radio" value="一般" name="evaluation_5" />
                <label for="five_three">一般</label>
            </div>
            <div>
                <input id="five_four" type="radio" value="不满意" name="evaluation_5" />
                <label for="five_our">不满意</label>
            </div>
            <div>
                <input id="five_five" type="radio" value="非常不满意" name="evaluation_5" />
                <label for="five_five">非常不满意</label>
            </div>
        </div>
        <div class="row clearfix">
            <h4>6、请问您对小区的绿化感到？</h4>
            <div>
                <input id="six_one" type="radio" value="非常满意" name="evaluation_6" />
                <label for="six_one">非常满意</label>
            </div>
            <div>
                <input id="six_two" type="radio" value="比较满意" name="evaluation_6" />
                <label for="six_two">比较满意</label>
            </div>
            <div>
                <input id="six_three" type="radio" value="一般" name="evaluation_6" />
                <label for="six_three">一般</label>
            </div>
            <div>
                <input id="six_four" type="radio" value="不满意" name="evaluation_6" />
                <label for="six_four">不满意</label>
            </div>
            <div>
                <input id="six_five" type="radio" value="非常不满意" name="evaluation_6" />
                <label for="six_five">非常不满意</label>
            </div>
        </div>
        <div class="row clearfix">
            <h4>7、请问您对小区的电梯运行感到？</h4>
            <div>
                <input id="seven_one" type="radio" value="非常满意" name="evaluation_7" />
                <label for="seven_one">非常满意</label>
            </div>
            <div>
                <input id="seven_two" type="radio" value="比较满意" name="evaluation_7" />
                <label for="seven_two">比较满意</label>
            </div>
            <div>
                <input id="seven_three" type="radio" value="一般" name="evaluation_7" />
                <label for="seven_three">一般</label>
            </div>
            <div>
                <input id="seven_four" type="radio" value="不满意" name="evaluation_7" />
                <label for="seven_four">不满意</label>
            </div>
            <div>
                <input id="seven_five" type="radio" value="非常不满意" name="evaluation_7" />
                <label for="seven_five">非常不满意</label>
            </div>
        </div>
        <h4 class="footer_advice">您的建议和意见是：</h4>
        <textarea rows="5" cols="40" name="note"></textarea>
        <input type="hidden" value="<?php echo $userid; ?>" name="userid">
        <div class="submit_btn">提交</div>
    </div>
    <form>

        <!--弹出框-->
        <div class="pop_up" style="display: none;">
            <div class="iphone_pop samsung" style="display: none;">
                <div class="close_row clearfix"><img class="close" src="<?php echo F::getStaticsUrl("/home/XieyiApp/img/close.png"); ?>" /></div>
                <div class="select_type">
                    评价完毕才能提交哦！</div>
                <div class="know">知道了</div>
            </div>

            <div class="iphone_pop onlyone" style="display: none;">
                <div class="close_row clearfix"  id="close_row"><img class="close" src="<?php echo F::getStaticsUrl("/home/XieyiApp/img/close.png"); ?>" /></div>
                <div class="select_type">
                    一个月只能评价一次哦！</div>
                <div class="know"  id="only">知道了</div>
            </div>

        </div>


</body>


<script type="text/javascript">
    //判断一个月只能评价一次
    $(function() {
        var error_flag='<?php echo $flag; ?>';
        if(error_flag=="false"){
            alert_onlyone();
        }
        var r_len = $('.row').length;
        flag = true;
        $('.submit_btn').click(function() {
            var i = 0;
            var j=0;
            while (i < r_len) {
//						alert(i);
                var rdo = $('.row').eq(i).find('input[type=radio]');//5
                rdo.each(function() {
                    var chked = $(this).is(':checked');
                    if (chked) {
                        j++;
                        flag = true;
                        return false;
                    } else {
                        flag = false;
                    }
                });
                i++;
                if (!flag) {
                    alert_();
                    break;
                }
                if(j==6 && error_flag=="true")    $("#sub_form").submit();
                if(error_flag=="false" && j==6)  alert_onlyone();
            }

        })

        function alert_() {
            $('.pop_up').show();
            $(".samsung").show();
        };
        //关闭窗口
        $('.close').click(function() {
            $('.pop_up,.pop_up>div').hide();
        });
        //关闭窗口
        $('.know').click(function() {
            $('.pop_up,.pop_up>div').hide();
        });

        $("#close_row").click(function(){
            closeBrowser();
        });
        $("#only").click(function(){
            $('.pop_up').hide();
            $(".onlyone").hide();
        });
        function closeBrowser(){
            mobileCommand("closeBrowser");
        }
        function mobileCommand(cmd)
        {
            if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
                var _cmd = "http://colourlifecommand/" + cmd;
                document.location = _cmd;
            } else if (/(Android)/i.test(navigator.userAgent)) {
                var _cmd = "jsObject." + cmd + "();";
                eval(_cmd);
            } else {

            };
        }


        function alert_onlyone(){
            $('.pop_up').show();
            $(".onlyone").show();
        }
    })

</script>
</body>

</html>