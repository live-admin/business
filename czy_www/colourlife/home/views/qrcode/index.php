<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>跳转</title>
    <style>
        body{
            background:url("<?php echo F::getStaticsUrl('/qicai/images/bg.jpg');?>")no-repeat;
            background-size:100% 100%;
            margin:0;
            padding-bottom: 1em;
        }
        .top{
            width:100%;
            height:8em;
            /*display: table-cell;*/
            vertical-align: middle;
            text-align: center;
        }
        .top img{
            margin-top:4em;
        }
        ul,li{
            list-style:none;
            margin:0;
            padding:0;
        }
        .box{
            width:100%;
            height:auto;
        }
        .box li{
            width: 80%;
            height: 2.5em;
            border: 1px solid #ccc;
            margin: 1em auto;
            text-align: center;
            border-radius: 0.5em;
        }
        .box li a{
            width: 100%;
            height:100%;
            line-height: 2.5em;
            display: block;
            text-decoration:none;
            color:#000;
        }
    </style>
</head>
<body>
 <div class="top">
     <img src="<?php echo F::getStaticsUrl('/qicai/images/logo.png');?>" />
 </div>
 <ul class="box">
     <?php foreach($url as $k=>$value):?>
     <li>
         <a href="<?php echo $value['redirect']?>"><?php echo $value['name']?></a>
     </li>
     <?php endforeach;?>
 </ul>
</body>
</html>