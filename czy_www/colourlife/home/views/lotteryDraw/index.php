<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>彩生活年会抽奖</title>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/lotteryDraw/'); ?>css/style.css"/>
    <script rel="script" src="<?php echo F::getStaticsUrl('/home/lotteryDraw/'); ?>js/jquery-1.11.3.js"></script>
    <script rel="script" src="<?php echo F::getStaticsUrl('/home/lotteryDraw/'); ?>js/ReFontsize.js"></script>
</head>
<body>
<input type="hidden" value="<?php echo $validate;?>" class="confirm"/>
<div class="container">
    <div class="background">
        <img src="<?php echo F::getStaticsUrl('/home/lotteryDraw/'); ?>images/bg.jpg">
    </div>
    <div class="title">
       <?php if (!empty($prize)){?> <span class="title_content">正在抽取：<?php echo $prize->grade_name;?></span><?php }?>
    </div>
    <div class="prize">
    <?php if (!empty($prize->prize_pic_url)){?>
	    	<img src="<?php echo F::getUploadsUrl("/images/" .$prize->prize_pic_url); ?>">
	        <span><?php echo $prize->prize_name;?></span>
    <?php }else{?>
	    	<img src="<?php echo F::getStaticsUrl('/home/lotteryDraw/'); ?>images/default.png">
	        <span></span>
    <?php }?>
        
    </div>
    <div class="button">
        <input type="button" id="action_btn" value="开始抽奖"/>
    </div>
    <span class="winner_list_tip hidden">中奖者名单</span>
    <div class="infos" id="infos">
        <ul id="container" style="top: 300px;">
        </ul>
        <ul id="winners1" style="top: 0px;" class="hidden winner10 winnerContainer">
        </ul>
        <ul id="winners2" style="top: 0px;" class="hidden winnerContainer">
        </ul>
        <ul id="winners3" style="top: 0px;" class="hidden winnerContainer">
        </ul>
        <ul id="winners4" style="top: 0px;" class="hidden winnerContainer">
        </ul>
        <ul id="winners5" style="top: 0px;" class="hidden winnerContainer">
        </ul>
        <div class="mask"></div>
    </div>
</div>
<script>
$(document).ready(function(){
    var datas=<?php echo json_encode($name);?>;
    var container = $(".infos ul:first");//追加数据的容器
    var speed = 5;//轮转速度
    var location;//记录暂停位置

    changeWinHeight();
    
    window.onresize=function(){  
    	changeWinHeight();
   }  
    
    
    
    if(container.children().length == 0){
        init(container);
        start(50);
    }
    $(".infos").mousemove(function(){
        pause();
    });
    $(".infos").mouseout(function(){
        keepon();
    });
    $(".button input").click(function(){
    	var $this = $(this);
    	if($(".title").children().length <= 0){
			alert("还没开始抽奖！");
			return false;
    	}
        if($(this).hasClass("running")){
           // stop
       	 	$(".infos").unbind("mousemove");
         	$(".infos").unbind("mouseout");
            $(this).removeClass("running");
            $(".winner_list_tip ").removeClass("hidden");
            $(this).val("开始抽奖");
            $(".infos").removeClass("disabled");
            document.getElementById("action_btn").disabled = true;
            clearInterval(location);

            $("#container").addClass("hidden");

            autoLayout();
			
        }
        else{
            //action
       	 	$(".infos").unbind("mousemove");
         	$(".infos").unbind("mouseout");
        	var confirm =$(".confirm").val() ;
        	$(this).addClass("running");
            $(".winner_list_tip ").addClass("hidden");
            document.getElementById("action_btn").disabled = true;
            $(this).val("抽奖中...");
            $(".infos").addClass("disabled");
            $("#container").removeClass("hidden");
            $("#winners1").addClass("hidden");
            clearInterval(location);
            startSpeed(2);
            
    	  	$.ajax({
    	          type: 'POST',
    	          url: '/LotteryDraw/Draw',
    	          data: 'validate='+confirm,
    	          dataType: 'json',
    	          success: function (result) {
    		          if(result.status==1){
    			          var winner=result.data;

    			          if((winner.length >= 3)&&(winner.length <= 6)){
    			        	  for(var i=0;i<winner.length;i++){
	        			          var name = winner[i].split("_")[0];
	        			          var info = winner[i].split("_")[1];
	        			          
	        			          $("#winners1").append("<li><span>"+name+"</span><span>("+info+")</span></li>");
	  			              }}
    			          else if((winner.length == 8)||(winner.length == 7))
    			          {
			        	  	for(var i=0;i<winner.length;i++){
        			          var name = winner[i].split("_")[0];
        			          var info = winner[i].split("_")[1];
        			          if(i<4){
        			        	  $("#winners1").append("<li><span>"+name+"</span><span>("+info+")</span></li>");			
	        			          }
        			          else{
        			        	  $("#winners2").append("<li><span>"+name+"</span><span>("+info+")</span></li>");
	        			          }
  			              	}
    			          }
    			          else if((winner.length > 8)&&(winner.length <= 10))
    			          {
			        	  	for(var i=0;i<winner.length;i++){
        			          var name = winner[i].split("_")[0];
        			          var info = winner[i].split("_")[1];
        			          if(i<5){
        			        	  $("#winners1").append("<li><span>"+name+"</span><span>("+info+")</span></li>");			
	        			          }
        			          else{
        			        	  $("#winners2").append("<li><span>"+name+"</span><span>("+info+")</span></li>");
	        			          }
  			              	}
    			          }
    			          else
    			        	  {
	    			          for(var i=0;i<winner.length;i++){
	        			          var name = winner[i].split("_")[0];
	        			          var info = winner[i].split("_")[1];
	        			          if(i<10){
	        			        	  $("#winners1").append("<li><span>"+name+"</span><span>"+info+"</span></li>");
		        			          }
	        			          else if((i>=10)&&(i<20)){
	        			        	  $("#winners2").append("<li><span>"+name+"</span><span>"+info+"</span></li></li>");						
	                			      }
	        			          else if((i>=20)&&(i<30)){
	        			        	  $("#winners3").append("<li><span>"+name+"</span><span>"+info+"</span></li></li>");						
	                			      }
	        			          else if((i>=30)&&(i<40)){
	        			        	  $("#winners4").append("<li><span>"+name+"</span><span>"+info+"</span></li></li>");						
	                			      }
	        			          else if((i>=40)&&(i<50)){
	        			        	  $("#winners5").append("<li><span>"+name+"</span><span>"+info+"</span></li></li>");						
	                			      }
	  			              	}
	  			              }
    			      		}
        			      else
            			      {
    				      		alert(result.msg); 
    				  		}
    		          $this.val("停止抽奖"); 
			          document.getElementById("action_btn").disabled = false;
    	          }
    	        });
        }
    });
	
    function init(container){

        for(var index in datas){
        	var name = datas[index].split("_")[0];
	        var info = datas[index].split("_")[1];
            container.append("<li><span>"+name+"</span><span>"+info+"</span></li></li>");
        }
    };
    function  start(frequency){
        location = setInterval(move,frequency);
    };
    function  startSpeed(frequency){
        location = setInterval(moveSpeed,frequency);
    };

	function moveSpeed(){
	    	
	        var top = parseInt(container[0].style.top.split("px")[0]) - speed;
	        
	        container[0].style.top = top+"px";
			
	        //跑到底部
	        if((datas.length<=6)&&(container.find("li")[0].clientHeight*datas.length + top <= 0)){
	            container[0].style.top = "300px";
	        }
	        else if((datas.length > 6)&&(document.getElementById("container").clientHeight - document.getElementById("container").offsetTop >= document.getElementById("container").scrollHeight)){
	        	container[0].style.top = "300px";
	        }
	    };
    function move(){
    	
        var top = parseInt(container[0].style.top.split("px")[0]) - speed;
        
        container[0].style.top = top+"px";
		
        //跑到底部
        if((datas.length<=6)&&(container.find("li")[0].clientHeight*datas.length + top <= 0)){
            container[0].style.top = "300px";
        }
        else if((datas.length > 6)&&(document.getElementById("container").clientHeight - document.getElementById("container").offsetTop >= document.getElementById("container").scrollHeight)){
        	container[0].style.top = "300px";
        }
    };

    function pause(){
        clearInterval(location);
    };

    function keepon(){
        location = setInterval(move,50);
    };

    function autoLayout(){
        var flag;
        if($(".winnerContainer").children().length <= 10)
            {
				flag = $(".winnerContainer").children().length; 
            }
        else if(($(".winnerContainer").children().length > 10)&&($(".winnerContainer").children().length <= 20))
        	{
        		flag = 11;
        	}
        else if(($(".winnerContainer").children().length > 20)&&($(".winnerContainer").children().length <= 50))
        	{
        		flag = 12;
            }
        switch(flag)
        {
        case 1:
        	{
        	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner1");
	    	$("#winners1").removeClass("hidden");
	    	break;
            }
        case 2:
    		{
        	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner2");
	    	$("#winners1").removeClass("hidden");
        	break;
        	}
        case 3:
    		{
        	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner3");
	    	$("#winners1").removeClass("hidden");
        	break;
        	}
	    case 4:
			{
	    	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner4");
	    	$("#winners1").removeClass("hidden");
	    	break;
	    	}
	    case 5:
    		{
	    	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner5");
	    	$("#winners1").removeClass("hidden");
	    	break;
        	}
	    case 6:
			{
	    	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner6");
	    	$("#winners1").removeClass("hidden");
	    	break;
	    	}
	    case 7:
			{
	    	$("#infos").removeClass("infos");
	    	$("#infos").addClass("infos10");
			
	    	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner7l");
	    	$("#winners2").addClass("winner7r");
	    	$("#winners1").removeClass("hidden");
	    	$("#winners2").removeClass("hidden");
	    	break;
	    	}
	    case 8:
			{
	    	$("#infos").removeClass("infos");
	    	$("#infos").addClass("infos10");
			
	    	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner7l");
	    	$("#winners2").addClass("winner7r");
	    	$("#winners1").removeClass("hidden");
	    	$("#winners2").removeClass("hidden");
	    	break;
	    	}
	    case 9:
			{
	    	$("#infos").removeClass("infos");
	    	$("#infos").addClass("infos10");
			
	    	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner7l");
	    	$("#winners2").addClass("winner7r");
	    	$("#winners1").removeClass("hidden");
	    	$("#winners2").removeClass("hidden");
	    	break;
    		}
	    case 10:
			{
	    	$("#infos").removeClass("infos");
	    	$("#infos").addClass("infos10");
	    	
	    	$("#winners1").removeClass("winner10");
	    	
	    	$("#winners1").addClass("winner7l");
	    	$("#winners2").addClass("winner7r");
	    	$("#winners1").removeClass("hidden");
	    	$("#winners2").removeClass("hidden");
	    	break;
	    	}
	    case 11:
			{
	    	$("#infos").removeClass("infos");
	    	$("#infos").addClass("infos20");
			
	    	$("#winners1").removeClass("winner10");
	    	$("#winners1").addClass("winner20");
	
	    	$("#winners2").addClass("winner20_right");
	
	    	$("#winners1").removeClass("hidden");
	    	$("#winners2").removeClass("hidden");
	    	break;
			}
	    case 12:
			{
	    	$("#infos").removeClass("infos");
	    	$("#infos").addClass("infos50");
	        
	    	$("#winners1").removeClass("winner10");
	    	$("#winners1").addClass("winner50_1");
	
	    	$("#winners2").addClass("winner50_2");
	
	    	$("#winners3").addClass("winner50_3");
	
	    	$("#winners4").addClass("winner50_4");
	
	    	$("#winners5").addClass("winner50_5");
	
	    	$("#winners1").removeClass("hidden");
	    	$("#winners2").removeClass("hidden");
	    	$("#winners3").removeClass("hidden");
	    	$("#winners4").removeClass("hidden");
	    	$("#winners5").removeClass("hidden");
	    	break;
	    	}
		}
	   
    }

    function changeWinHeight() {
        
    	var winHeight =  document.documentElement.clientHeight;
        var winHeightString = winHeight+"px";
    	//alert(winHeight);
    	$(".background img").css("height",winHeightString);
   }
    
});

</script>
</body>
</html>