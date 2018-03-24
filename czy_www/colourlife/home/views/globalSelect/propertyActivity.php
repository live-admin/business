<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
</head>
<body>

</body>

<script>
    mobileJump('EReduceList');
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
</script>
</html>