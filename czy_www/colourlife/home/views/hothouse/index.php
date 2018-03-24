<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" />
<title>热门房屋</title>
<link href="<?php echo F::getStaticsUrl('/common/css/bieyangcheng1.css'); ?>" rel="stylesheet">



</head>

<body>
   <?php if(count($house_list)>0){?>
   		<?php foreach ($house_list as $house){?>
   		<div class="bieyangcheng_topic">
   			<a href="<?php echo $house->app_url;?>">
   			<div class="topic_content">
    		 <h3><?php echo $house->title;?></h3>
    		 <div class="part2">
     		  <img src="<?php echo $house->appPictureUrl;?>" />
    		 </div>
    		 <p><?php echo $house->description;?></p>
 			  </div>
 			</a>
 		 </div>
   		<?php }?>
   <?php }?>


</body>
</html>
