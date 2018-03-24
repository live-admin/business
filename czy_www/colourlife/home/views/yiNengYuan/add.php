<!DOCTYPE html>
<html>


	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>添加新电表</title>
                <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/nts.css'); ?>" rel="stylesheet" type="text/css">
                 <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/alert.css'); ?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/yiNengYuan/js/jquery.min.js'); ?>"></script>
	</head>
	<body>
            
            <form id="sub_form" action="AddPower" method="get" >
		<div class="znq">
			<div class="add_content">
				<div class="mar_down"></div>
				<!--描述：间隔-->
				<input  type="number" id="power_card" name="meter"  placeholder="请输入电表卡号" value=""/>
				<p class="ammeter_num"><a href="select_ammeter">电表卡号？</a></p>
				<input type="text" id="power_name" placeholder="请输入签约人姓名" name="customer_name" value=""/>
				<input id="community_name" class="mar_up_down" type="text" placeholder="根据电表卡号判断的小区名称" name="community_name" value=""/>
                                <input type="hidden" id="customer_id" value="<?php echo Yii::app()->request->getParam('cust_id'); ?>"  name="customer_id">
                <input type="hidden" id="interface_type" value=""  name="interface_type">
				<div class="comfirm_btn">确定</div>
			</div>
		</div>
            </form>
           <div class="pop_up" style="display: none;">
			<div class="iphone_pop samsung" >
				
				<!--<p class="hint">温馨提示</p>-->
				
				<div class="select_type">
					很抱歉，您的好友已经注册了，您可以邀请其他好友注册拿红包。</div>
                            <!--每行一个按钮-->
                            <div class="row_one_btn">确定</div>
                            <!--每行两个按钮-->
<!--                            <div class="row_two_btn clearfix">
                                <div class="add_cancel">取消</div>
                                <div class="">确定</div>
                            </div>-->
			</div>
          </div> 
		<script type="text/javascript">

                 var customer_id=<?php echo Yii::app()->request->getParam('cust_id'); ?>;   


   
                       //弹窗调用                       
                        function alert_invoke(prompt){ 
                        $('.select_type').html(prompt);    
                        $('.pop_up').show();    
                        }   
//                        //关闭窗口
                        $('.row_one_btn').click(function() {
                                $('.pop_up').hide();
                        });
                        //关闭窗口
                        $('.row_one_btn').click(function() {
                                $('.pop_up').hide();
                        });
                   

			$(function(){
				//确定按钮
				$('.comfirm_btn').click(function(){
                                if($.trim($('#power_card').val())==""){
                                   alert_invoke('电表卡号不能为空');  
                                }else if($.trim($('#power_name').val())==""){                      
                                  alert_invoke('签约人姓名不能为空');  
                                }else if($.trim($("#community_name").val())==""){
                                     alert_invoke('小区名称不能为空');                                   
                                }else{
                                
                                $("#sub_form").submit();    
                                }   
					
				});
			})
                        
                 $("#power_name").bind('focus', function(){
                   
                    var power_card=$("#power_card").val();
                    if($.trim($('#power_card').val())==""){
                       alert_invoke('电表卡号不能为空');
                    
                    }else{
             
                        $.ajax({
                           type: "POST",
                           url: "/YiNengYuan/Ajax",
                           dataType: "json",
                           data:{
                               meter:power_card,
                               customer_id:customer_id
                            },
                           success: function(data){
                        
                              if(1 == data.code) {
                               
                                  $("#power_name").val(data.data['customer_name']); 
                                  $("#community_name").val(data.data['meter_address']); 
                                  $("#interface_type").val(data.data['interface_type']);
                              }
                              else{
                                alert_invoke(data.msg);                                    
                              } 
                           },
                           error:function(){
                            alert_invoke('你的操作有误');  
                           }
                       })
                 
                    }          
                 });       


 
        
		</script>
	</body>

</html>