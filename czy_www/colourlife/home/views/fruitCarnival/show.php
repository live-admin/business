<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		
	<title><?php echo $title;?></title>
	<link href="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>css/page1.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>js/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>js/post.js"></script>
</head>

<body>

<div class="container">
	<input type="hidden" value="<?php echo $user_id;?>" id="uid"/>
	<div class="background">
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg01.jpg"/>
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg02.jpg"/>
   		<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg03.jpg"/>
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg04.jpg"/>
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg05.jpg"/>
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg06.jpg"/>
   		<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg07.jpg"/>
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg08.jpg"/>
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg09.jpg"/>
    	<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/<?php echo $type;?>/bg10.jpg"/>
    </div>
    <div class="info_block">
        <?php 
	    if (count($dataList)>0){ 
	    	foreach ($dataList as $v){
   		?>
    	<!--info_detail开始-->
    	<div class="info_detail">
            <div class="info_content">
             <?php $customerInfo=$v->getCustomerInfo($v->user_id);?>
                <div class="head_photo">
                    <img src="<?php echo $customerInfo['image']; ?>"/>
                </div>
                    
                <div class="infos">
                    <div class="id">
                        <a><span><?php echo $customerInfo['name']; ?></span></a>
                    </div>
                    <div class="information">
                        <p><?php echo $v->content;?></p>
                    </div>
             <?php if(!empty($v->image_url)){ ?>
                    <div class="my_pic">
                        
                   <?php if (strpos($v->image_url, ";")!==false){
							$image=explode(";", $v->image_url);
							foreach ($image as $img){
						
                    ?>
                    <img src="<?php echo F::getUploadsUrl($img); ?>"/>
                    <?php }
					}else {?>
					<img src="<?php echo F::getUploadsUrl($v->image_url); ?>"/>
					<?php }?>
                        <div class="clear"></div>
                    </div>
              <?php }?>
               </div>
              
            </div>
            <div class="discuss_content">
                <div class="discuss_btn_block">
                	<!-- <div class="discuss_time">1小时前</div> -->
                    <?php $dz=$v->getUserDianZan($v->id,$user_id);if (!empty($dz)&&$dz->is_praised==2){?>
                    <div class="praise_btn cancel_praise_btn">
                        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/ba_cancel.png"/>
                        <a href="javascript:void(0);" data-tzid="<?php echo $v->id;?>" data-type="N"></a>
                    </div>
                    <?php }else{?>
                    <div class="praise_btn">
                        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/praise_btn.png"/>
                        <a href="javascript:void(0);" data-tzid="<?php echo $v->id;?>" data-type="Y"></a>
                    </div>
                    <?php }?>
                    <div class="discuss_btn">
                        <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/discuss_btn.png"/>
                        <a href="javascript:void(0);" data-tzid=<?php echo $v->id;?>></a>
                    </div>
                </div>
                <div class="all_discuss_list">
                    <div class="praise_list">
                    <img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/praise_mark.png"/>
                    <?php 
                    $dianZanInfo=$v->getDianZanList($v->id);
                    if (count($dianZanInfo)>0){
                    	foreach ($dianZanInfo as $val){
                       			$username=$v->getCustomerInfo($val->user_id,false);
                       			if (empty($username['name'])){
									continue;
								}
                       	?>
                        <span id="<?php echo $val->user_id;?>"><?php echo $username['name'];?></span>
                       <?php }
					    }?>
                    </div>
                    <div class="discuss_dividing_line"></div>
                    <div class="discuss_list">
                    <?php $pingLunInfo=$v->getPingLunList($v->id);
                		  //if (count($pingLunInfo)>0){
							foreach ($pingLunInfo as $pinglun){?>
                        <div class="discuss_detail">
                        	<a class="reply_name" data-id="<?php echo $pinglun->id;?>" data-rid="" data-uid="<?php echo $pinglun->user_id; ?>"><?php $username=$v->getCustomerInfo($pinglun->user_id,false);echo $username['name']?>：</a>
                        	<span><?php echo $pinglun->content;?></span>
                        	<?php if ($pinglun->user_id==$user_id){?>
                        	<a class="discuss_close" data-id="<?php echo $pinglun->id;?>" data-type="C">
                        		<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/ba_Close.png"width="100%;">
                        	</a>
                        	<?php }
                        	$replyInfo=$v->getReplyList($pinglun->id);
                         	//if (count($replyInfo)>0){
										foreach ($replyInfo as $reply){
							?>
                        	<div class='discuss_detail_answer'>
                        	<a class='reply_name' data-id="<?php echo $pinglun->id;?>" data-rid="<?php echo $reply->id;?>" data-uid="<?php echo $reply->user_id;?>"><?php $username=$v->getCustomerInfo($reply->user_id,false);echo $username['name']?></a>
                        	<span>回复</span>
                        	<a class='reply_name' style='margin-left: 0%;' data-id="<?php echo $pinglun->id;?>" data-rid="<?php echo $reply->id;?>" data-uid="<?php echo $reply->to_user_id;?>"><?php $username=$v->getCustomerInfo($reply->to_user_id,false);echo $username['name']?>：</a>
                        	<?php 
	                        	echo $reply->content;
	                        	if ($reply->user_id==$user_id){
							?>
                        	<a class='discuss_close'  data-id="<?php echo $reply->id;?>" data-type="R"><img src='<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/ba_Close.png'></a>
                        	<?php }?>
                        	</div>
							<?php }
							//}?>
                        </div>
                    <?php }
					//}?>
                    </div>
                </div>
                <div class="dividing_queren">
            		<div class="dividing_queren_top">
            			<textarea placeholder="最多输入100字" maxlength="100" class="comment"></textarea>
            		</div>
            		<div class="dividing_queren_bottom">
            			<a href="javascript:void(0);"></a>
            			<img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/button_queren.png">
            		</div>
            	</div>
            </div>
            <div class="dividing_line"></div>
            
    	</div>
    		<!--info_detail结束-->
    	    <?php 
			}
		}
	?>
    </div>
   
    <div class="edit_btn">
    	
        <a href="<?php echo $this->createUrl("EditShow",array('type'=>$type)); ?>"><img src="<?php echo F::getStaticsUrl('/home/fruitCarnival/'); ?>images/edit_btn.png"/></a>
    </div>
</div>
</body>
</html>