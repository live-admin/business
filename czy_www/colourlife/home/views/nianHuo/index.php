<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>年货活动</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>js/ReFontsize.js"></script>
    <script src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>js/jquery-1.11.3.js"></script>
    <script src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>js/snow.js"></script>
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/nianHuo/');?>css/style.css">
    <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/home/nianHuo/');?>css/lanrenzhijia.css">
	<script language="javascript" type="text/javascript" src="<?php echo F::getStaticsUrl('/home/nianHuo/js/ShareSDK.js');?>"></script>
    <script language="javascript" type="text/javascript">
        
        function showShareMenuClickHandler()
        {
            
            var u = navigator.userAgent, app = navigator.appVersion;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
            
             if(isAndroid){
                 try{
                     var version = jsObject.getAppVersion();
//                     alert(version);
                 }catch(error){
                      //alert(version);
                      if(version !="4.3.6.2"){
                        alert("请到App--我--检查更新,进行更新");
                        return false;
                    }
                 }finally {}
                $sharesdk.open("85f512550716", true);
            }
            
            if(isiOS){
                try{
                    if(getAppVersion && typeof(getAppVersion) == "function"){
                         getAppVersion();
                         var vserion = document.getElementById('vserion').value;
                        }
                    }catch(e){
                        
                    }
               
                if(vserion){
                    //alert(vserion);
                    $sharesdk.open("62a86581b8f3", true); 
                }else{  
                    alert("请到App--我--检查更新,进行更新");
                    return false;
                 }  
                }
            
            var params = {
                "text" : "新年送好礼，拆“弹”送红酒，下载彩之云来玩玩呗",
                "imageUrl" : "<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/box.png",
                "url":"http://dwz.cn/8YPIv",
                "title" : "彩之云",
                "titleUrl" : "http://dwz.cn/8YPIv",
                "description" : "描述",
                "site" : "彩之云",
                "siteUrl" : "http://dwz.cn/8YPIv",
                "type" : $sharesdk.contentType.WebPage
            };
           $sharesdk.showShareMenu([$sharesdk.platformID.WeChatSession,$sharesdk.platformID.WeChatTimeline,$sharesdk.platformID.QQ], params, 100, 100, $sharesdk.shareMenuArrowDirection.Any, function (platform, state, shareInfo, error) {
//                alert("state = " + state + "\nshareInfo = " + shareInfo + "\nerror = " + error);
            }); 
        };
     

    </script>
</head>
<body>
<div class="container">
    <div class="banner">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/banner01.png">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/banner02.png">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/banner03.png">
    </div>
    <div class="acvtity01_block">
        <div class="acvtity01_block1a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/image01.png">
        </div>
        <div class="acvtity01_block2a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/title01.png">
        </div>
        <div class="acvtity01_block2a_box">
            <div class="acvtity01_block2a_box1a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/box.png"></a>
                <a href="javascript:void(0)" class="hidden"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/box_open.png"></a>
                <p>彩礼1</p>
            </div>
            <div class="acvtity01_block2a_box2a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/box.png"></a>
                <a href="javascript:void(0)" class="hidden"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/box_open.png"></a>
                <p>彩礼2</p>
            </div>
            <div class="acvtity01_block2a_box3a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/box.png"></a>
                <a href="javascript:void(0)" class="hidden"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/box_open.png"></a>
                <p>彩礼3</p>
            </div>
        </div>
        <div class="acvtity01_block3a">
            <p>产自法国或西班牙纳瓦拉的志鹏酒业红酒等你来拿</p>
            <p>（彩富人生客户有双倍抽奖机会呢&#8764）</p>
            <a href="javascript:void(0)">活动详情</a>
        </div>

    </div>
    <div class="acvtity02_block">
        <div class="acvtity02_block1a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/image01.png">
        </div>
        <div class="acvtity02_block2a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/title02.png">
        </div>
        <div class="acvtity02_block3a">
            <p>平台每日提供交易订单免单名额</p>
            <p>快快来参与</p>
            <a href="javascript:void(0)">活动详情</a>
        </div>
    </div>
    <div class="acvtity03_block">
        <div class="acvtity03_block1a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/image01.png">
        </div>
        <div class="acvtity03_block2a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/title03.png">
        </div>
        <div class="acvtity03_block3a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/activity3_01.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/activity3_02.jpg">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/activity3_03.jpg">
            <a href="javascript:void(0)">活动详情</a>
        </div>
    </div>
    <div class="links_box">
         <div class="links_box1a">
             <a href="<?php echo $daytuan_url;?>">
                 <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/link01.png">
             </a>
         </div>
         <div class="links_box2a">
             <a href="<?php echo $jd_url;?>">
                 <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/link02.png">
             </a>
         </div>
         <div class="links_box3a">
             <a href="<?php echo $huanqiu_url;?>">
                 <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/link03.png">
             </a>
         </div>
    </div>
    <div class="winner_list_btn">
        <a><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_query_btn.png"></a>
    </div>
    <input style="display: none" id="vserion" />
    <div class="result01 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result02 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_1.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result03 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_2.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result04 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_3.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result05 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_4.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result06 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_5.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result07 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_6.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result08 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_7.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result09 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result01a">
                <a href="javascript:void(0)"><img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/volume_8.png"></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result10 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color" style="height: 3.0rem;">
            <div class="result01a">
                <a href="javascript:void(0)"><p>亨特庄园梅洛红葡萄酒一瓶</p></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="result11 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color" style="height: 3.0rem;">
            <div class="result01a">
                <a href="javascript:void(0)"><p>施洛维赤霞珠一瓶</p></a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>    
    <div class="result12 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/winner_title.png">
        <div class="b_color">
            <div class="result12a">
                <a href="javascript:void(0)">
                	<p>谢谢参与</p>
                	<p>彩富人生感谢你的参与</p>
                </a>
            </div>
            <div class="result02a">
                <a href="javascript:void(0)">分享</a>
                <p>分享后可额外获得一次彩花机会哦</p>
            </div>
            <a href="javascript:void(0)" class="close">
                <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
            </a>
        </div>
    </div>
    <div class="rule01 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/rule_1.png">
        <a href="javascript:void(0)" class="close1a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
        </a>
    </div>
    <div class="rule02 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/rule_2.png">
        <a href="javascript:void(0)" class="close1a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
        </a>
    </div>
    <div class="rule03 hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/rule_3.png">
        <a href="javascript:void(0)" class="close1a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
        </a>
    </div>
    <?php 
    $mem=array();
    if (!empty($lottery_mem)){
    	foreach ($lottery_mem as $val){
			$tmp=array();
			$tmp['date_time']=date("Y.m.d",$val->create_time);
			$tmp['mobile']=$val->mobile;
			$tmp['name']=$val->prize_name;
			$mem[$val->type][]=$tmp;
		}
    }?>
    <div class="winner_list hidden">
        <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/rule_4.png">
        <div class="winner_lista">
               <div class="winner_lista_tab">
                   <ul>
                       <li class="active">彩花中奖</li>
                       <li>免单大奖</li>
                       <li>送送送</li>
                   </ul>
                   <div class="clear"></div>
               </div>
              <div class="winner_lista_conter_tab">
	               <!--彩花中奖开始-->
	             
	               <div class="winner_lista_conter">
	                    <table border="0" width="100%" cellpadding="0" cellspacing="0" class="winner_lista_conter_tab winner_01">
	                        <tr>
	                            <th class="tab">时间</th>
	                            <th class="tab">中奖人</th>
	                            <th class="tab1a">奖品</th>
	                        </tr>
	                        <?php 
	                        if (!empty($mem)&&!empty($mem[1])){
	                        	foreach ($mem[1] as $val){	
	                        ?>
	                        <tr>
	                            <td class="tab"><?php echo $val['date_time'];?></td>
	                            <td class="tab"><?php echo $val['mobile'];?></td>
	                            <td class="tab1a"><?php echo $val['name'];?></td>
	                        </tr>
	                        <?php 
								}
							}?>
	                    </table>
	               		
	               </div>
	               <!--彩花中奖结束-->
	               <!--免单大奖开始-->
	                <div class="winner_lista_conter1a" style="display: none;">
	                    <table border="0" width="100%" cellpadding="0" cellspacing="0" class="winner_lista_conter_tab1a winner_02">
	                        <tr>
	                            <th class="tab">时间</th>
	                            <th class="tab">中奖人</th>
	                            <th class="tab1a">奖品</th>
	                        </tr>
	                        <?php 
	                        if (!empty($mem)&&!empty($mem[2])){
	                        	foreach ($mem[2] as $val){	
	                        ?>
	                        <tr>
	                            <td class="tab"><?php echo $val['date_time'];?></td>
	                            <td class="tab"><?php echo $val['mobile'];?></td>
	                            <td class="tab1a"><?php echo $val['name'];?></td>
	                        </tr>
	                        <?php 
								}
							}?>
	                       
	                    </table>
	                </div>
	                <!--免单大奖结束-->
	                 <!--送送送开始-->
	                 <div class="winner_lista_conter2a" style=" display:none;">
	               
	                    <table border="0" width="100%" cellpadding="0" cellspacing="0" class="winner_lista_conter_tab2a winner_03">
	                        <tr>
	                            <th class="tab">时间</th>
	                            <th class="tab">中奖人</th>
	                            <th class="tab1a">奖品</th>
	                        </tr>
	                        <?php 
	                        if (!empty($mem)&&!empty($mem[3])){
	                        	foreach ($mem[3] as $val){	
	                        ?>
	                        <tr>
	                            <td class="tab"><?php echo $val['date_time'];?></td>
	                            <td class="tab"><?php echo $val['mobile'];?></td>
	                            <td class="tab1a"><?php echo $val['name'];?></td>
	                        </tr>
	                        <?php 
								}
							}?>
	                       
	                    </table>
	                 </div>
	               <!--送送送结束-->
               </div>
               
              
        </div>
        <a href="javascript:void(0)" class="close1a">
            <img src="<?php echo F::getStaticsUrl('/home/nianHuo/');?>images/close.png">
        </a>
    </div>
    <div class="mask hidden"></div>
	<input type="hidden" class="url_img" value="<?php echo F::getStaticsUrl('/home/nianHuo/');?>" />
	<input type="hidden" value="<?php echo $validate;?>" class="confirm"/>
	<input type="hidden" value="<?php echo $you_hui_quan;?>" id="url_id"/>
</div>
<script>
    $(document).ready(function(){
        var t_num=<?php echo $c_total;?>;
        <?php 
        if ($c_total>0){?>
    		var flag = false;
    	<?php }else{?>
    		var flag = true;
    	<?php }?>
    //活动一
        
        function getResult(){
       	 <?php if ($userType==1){?>
  			var type = 1;
	  	<?php }else{?>
	  		var type = 0;
	  	<?php }?>
        	$.ajax({
    	          type: 'POST',
    	          url: '/NianHuo/Lottery',
    	          data: 'u_type='+type,
    	          dataType: 'json',
    	          success: function (result) {
        	             if(result.status==1){  
        	                 switch(result.r_id){
        	                     case 1:
        	                         $(".result01").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 2:
        	                         $(".result02").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 3:
        	                         $(".result03").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 4:
        	                         $(".result04").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 5:
        	                         $(".result05").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 6:
        	                         $(".result06").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 7:
        	                         $(".result07").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 8:
        	                         $(".result08").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 9:
        	                         $(".result09").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 10:
        	                         $(".result10").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 11:
        	                         $(".result11").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;
        	                     case 12:
        	                         $(".result12").removeClass("hidden");
        	                         $(".mask").removeClass("hidden");
        	                         break;                           
        	                     default :
        	                             return false;
        	                 }
        	                 t_num-=1;
        	                if(t_num>0){
        	                	flag = false;
                	        } 
                	        $(".winner_lista_conter table").append('<tr><td class="tab">'+result.dt+'</td><td class="tab">'+result.m+'</td><td class="tab1a">'+result.pn+'</td></tr>');
            	         }     				  
    	          }
    	        });
        }
        /*点击彩花1*/
        $(".acvtity01_block2a_box1a a:eq(0)").click(function(){
            if(!flag){
                $(this).addClass("hidden");
                $(this).next().removeClass("hidden");
                createSnow('', 60);
                flag = true;
                //ajxa获取中奖结果
                getResult();
            }else{
				alert("别太贪心哦，明天再来吧！");
            }
           
        });
        /*点击彩花2*/
        $(".acvtity01_block2a_box2a a:eq(0)").click(function(){
            if(!flag){
                $(this).addClass("hidden");
                $(this).next().removeClass("hidden");
                createSnow('', 60);
                flag = true;
                //ajxa获取中奖结果
                getResult();
            }else{
				alert("别太贪心哦，明天再来吧！");
            }
            
        });
        /*点击彩花3*/
        $(".acvtity01_block2a_box3a a:eq(0)").click(function(){
            if(!flag){
                $(this).addClass("hidden");
                $(this).next().removeClass("hidden");
                createSnow('', 60);
                flag = true;
                //ajxa获取中奖结果
                getResult();
            }else{
				alert("别太贪心哦，明天再来吧！");
            }
            
        });
        /*中奖结果页面关闭关闭*/
        $(".b_color").find("a:last").click(function(){
            $(this).parent().parent().addClass("hidden");
            $(".mask").addClass("hidden");
        });
        /*响应分享事件*/
        $(".b_color").find(".result02a").find("a").click(function(){
            //alert("响应分享事件");
        	showShareMenuClickHandler();
        	
        	var confirm =$(".confirm").val() ;
      	  	$.ajax({
    	          type: 'POST',
    	          url: '/NianHuo/Share',
    	          data: 'validate='+confirm,
    	          dataType: 'json',
    	          success: function (result) {
        	           if(result.status==1){
        	        	   flag = false;
            	       }       				  
    	          }
    	        });
        });
        $(".acvtity01_block3a a").click(function(){
            $(".mask").removeClass("hidden");
            $(".rule01").removeClass("hidden");
        });
        /*活动一详情关闭按钮*/
        $(".rule01").click(function(){
            $(".rule01").addClass("hidden");
            $(".mask").addClass("hidden");
        });


    //活动二
        /*活动二详情*/
        $(".acvtity02_block3a a").click(function(){
            $(".mask").removeClass("hidden");
            $(".rule02").removeClass("hidden");
        });
        /*活动二详情关闭按钮*/
        $(".rule02").click(function(){
            $(".rule02").addClass("hidden");
            $(".mask").addClass("hidden");
        });

    //活动三
        /*活动二详情*/
        $(".acvtity03_block3a a").click(function(){
            $(".mask").removeClass("hidden");
            $(".rule03").removeClass("hidden");
        });
        /*活动二详情关闭按钮*/
        $(".rule03").click(function(){
            $(".rule03").addClass("hidden");
            $(".mask").addClass("hidden");
        });

     //中奖者名单
         /*中奖者名单按钮*/
        $(".winner_list_btn a").click(function(){
            $(".winner_list").removeClass("hidden");
            $(".mask").removeClass("hidden");
            $(".winner_lista_conter").css("overflow-y","scroll");
            var top = $("body").offset().top;
            
           	$("body").css("position","fixed");
           	$("body").scrollTop(top);
           
        });
        //中奖者名单关闭
        $(".winner_list>a").click(function(){
            $(".winner_list").addClass("hidden");
            $(".mask").addClass("hidden");
		     var top = $("body").offset().top;
            
           	$("body").css("position","relative");
           	$("body").scrollTop(top);
           	$(".winner_lista_conter").css("overflow","hidden");
        });

        /*中奖者名单切换*/
        $(".winner_lista_tab ul li").click(function(){
            $(this).addClass("active");
            $(this).siblings().removeClass("active");
            
            $(".winner_lista_conter_tab > div").hide().eq($('.winner_lista_tab ul li').index(this)).show();
        });

       //跳至详情页
        $(".result01a a").click(function(){
			var url = $("#url_id").val();
			self.location = url;
            });
        
        $(".result12a a").click(function(){
			var url = $("#url_id").val();
			self.location = url;
            });
    });
</script>
</body>
</html>