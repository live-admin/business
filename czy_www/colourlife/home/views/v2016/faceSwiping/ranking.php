<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>刷脸拼颜值排行榜</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no" />
	<script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/flexible.js" ></script>
    <script type="text/javascript" src="<?php echo F::getStaticsUrl('/activity/common/');?>js/jquery-1.11.3.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/common/');?>css/normalize.css" />
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>css/layout.css" />
</head>
<body>
    <div class="content rank_content">
    	<div class="rank_wrap">
            <div class="top_win">
                    <!--<div class="top_item">
                        <p>
                        	<img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/photo.png">
                            <span class="rank_card first_prize"></span>
                        </p>
                        <span class="top_username">徐海乔</span>
                        <span class="top_score"><em>100</em>分</span>
                        <i></i>
                    </div>
                    <div class="top_item">
                        <p>
                        	<img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/photo.png">
                            <span class="rank_card second_prize"></span>
                        </p>
                        <span class="top_username">徐海乔</span>
                        <span class="top_score"><em>90</em>分</span>
                    </div>
                    <div class="top_item">
                        <p>
                        	<img src="<?php echo F::getStaticsUrl('/activity/v2016/faceSwiping/');?>images/photo.png">
                            <span class="rank_card thrid_prize"></span>
                        </p>
                        <span class="top_username">徐海乔</span>
                        <span class="top_score"><em>80</em>分</span>
                    </div>-->
            </div>
            <!--<div class="blank">
            	<p></p>
            </div>-->
            <div class="floor_win">
                <ul>
                    
                    
                </ul>
            </div>
        </div>
        <div class="footer"></div>
	</div>
	
<script>
	
		var rankingList =<?php echo $rankingList;?>;
		//奖牌数组
		var prize=["first_prize","second_prize","thrid_prize"];
			if(rankingList.length < 3){
				//前3名遍历
				for(var i= 0;i<rankingList.length;i++){
					$(".top_win").append('<div class="top_item">'+
		                        '<p>'+
		                        	'<img src="'+rankingList[i].picUrl +'">'+
		                            '<span class="rank_card"></span>'+//first_prize
		                        '</p>'+
		                        '<span class="top_username">'+rankingList[i].nickName+'</span>'+
		                        '<span class="top_score"><em>'+rankingList[i].totalScore+'</em>分</span>'+
		                        '<i></i>'+
		                    '</div>'
								);
					//奖牌样式
					$(".top_win .top_item").eq(i).find(".rank_card").addClass(prize[i]);
					//前3名判断是否是自己	
					if(rankingList[i].isSelf == 1){
						$(".top_win .top_item").eq(i).addClass("self-color");
					}
				}	
			}else if(rankingList.length >= 3){
				//前3名
				for(var i= 0;i<3;i++){
					$(".top_win").append('<div class="top_item">'+
		                        '<p>'+
		                        	'<img src="'+rankingList[i].picUrl +'">'+
		                            '<span class="rank_card"></span>'+//first_prize
		                        '</p>'+
		                        '<span class="top_username">'+rankingList[i].nickName+'</span>'+
		                        '<span class="top_score"><em>'+rankingList[i].totalScore+'</em>分</span>'+
		                        '<i></i>'+
		                    '</div>'
								);
					//奖牌样式
					$(".top_win .top_item").eq(i).find(".rank_card").addClass(prize[i]);
					//前3名判断是否是自己	
					if(rankingList[i].isSelf == 1){
						$(".top_win .top_item").eq(i).addClass("self-color");
					}
				}	
				
				//4-10名
				for(var i= 3;i<rankingList.length;i++){
					if(i < 10){
						$(".floor_win ul").append('<li>'+
			                        '<div class="floor_user left">'+
			                            '<span>'+rankingList[i].rank+'</span>'+
			                          '<p><img src="'+rankingList[i].picUrl+'"></p>'+
			                            '<span>'+rankingList[i].nickName+'</span>'+
			                        '</div>'+
			                        '<div class="floor_score right">'+
			                            '<span><em>'+rankingList[i].totalScore+'</em>分</span>'+
			                        '</div>'+
			                    '</li>'
							)
						
					}else if(i >= 10){
						$(".floor_win ul").append('<li>'+
			                        '<div class="floor_user left">'+
			                            '<span>'+rankingList[i].rank+'</span>'+
			                            '<span>'+rankingList[i].nickName+'</span>'+
			                        '</div>'+
			                        '<div class="floor_score right">'+
			                            '<span><em>'+rankingList[i].totalScore+'</em>分</span>'+
			                        '</div>'+
			                    '</li>'
								)
					}
				}
			
				for(var i= 3;i<rankingList.length;i++){	
				//判断是否是自己	
					if(rankingList[i].isSelf == 1){
						$(".floor_win ul li").eq(i-3).addClass("self-color");
					}
				}			
			}
</script>	
</body>
</html>
