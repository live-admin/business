<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>员工专享</title>
    <meta name="viewpoint" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <script src="http://dajidali.test.colourlife.com/js/jquery-1.10.1.min.js"></script>
    <style>
        body{
            margin:0;
            padding:0;
            background:#F2F3F4;
        }
        .box{
            width:100%;
            height:auto;
            overflow: hidden;
        }
        .list_box{
            width:94.7%;
            margin:0 auto;
        }
        .list_box li{
            width:100%;
            margin-top:12px;
        }
        .list_box li a{
            display: block;
            width:100%;
        }
        .list_box li a img{
            width:100%;
        }
        ul,li{
            list-style:none;
            margin:0;
            padding:0;
        }
    </style>
</head>
<body>
  <div class="box">
      <ul class="list_box">
      </ul>
  </div>
</body>
<script>
    $(function(){
        var url;
        var local_url=document.domain;
        console.log(local_url);
        if(local_url=="www-czytest.colourlife.com"){
          url='http://colourhome-czytest.colourlife.com';
        }else if(local_url=="www.colourlife.com"){
          url ='http://colourhome.colourlife.com';
        }else if(local_url=="localhost"){
          url='http://colourhome-czytest.colourlife.com';
        }
        $.ajax({
            type: "GET",
            url:url+"/app/home/employee/private",
            dataType: "json",
            data: {
                cust_id:getUrlParam('cust_id'),
            },
            success: function(data){
                //alert(JSON.stringify(data));
                if(data.code==0){
                    var listLI=''
                    for(var i=0;i<data.content.length;i++){
                        listLI+="<li><a href="+data.content[i].url+"><img src="+data.content[i].img+" alt=''/></a></li>"
                    }
                    $(".list_box").append(listLI);
                }else{
                    alert(data.message);
                }
            },
            error: function(data){
                console.log(data);
            }
        });
        function getUrlParam(name){
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg); //匹配目标参数
            if (r != null) return unescape(r[2]); return null; //返回参数值
        }
    })
</script>
</html>