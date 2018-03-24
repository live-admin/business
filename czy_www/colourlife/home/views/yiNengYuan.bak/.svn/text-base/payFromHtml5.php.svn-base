<?php
/**
 * Created by PhpStorm.
 * User: sunny
 * Date: 2015/3/31
 * Time: 15:38
 */
?>
<script src="<?php echo F::getStaticsUrl('/jd/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script>
    $(function(){
        payFromHtml5('<?php echo $sn;?>','<?php echo $url;?>');
    });

    function payFromHtml5(sn,url)
    {
        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
            //alert(navigator.userAgent);
            loadURL("*payFromHtml5*"+sn+"*"+url);
            //document.location="*payFromHtml5*"+sn+"*"+url;  //cmd代表objective-c中的的方法名，parameter1自然就是参数了
        } else if (/(Android)/i.test(navigator.userAgent)) {
            //alert(navigator.userAgent);
            jsObject.payFromHtml(sn,url);
        } else {
            window.location.href ='<?php echo CHtml::normalizeUrl(array('/pay/index', 'sn' => $sn))?>';
        };
    }

    function loadURL(url) {
        var iFrame;
        iFrame = document.createElement("iframe");
        iFrame.setAttribute("src", url);
        iFrame.setAttribute("style", "display:none;");
        iFrame.setAttribute("height", "0px");
        iFrame.setAttribute("width", "0px");
        iFrame.setAttribute("frameborder", "0");
        document.body.appendChild(iFrame);
        // 发起请求后这个iFrame就没用了，所以把它从dom上移除掉
        iFrame.parentNode.removeChild(iFrame);
        iFrame = null;
    }
</script>
