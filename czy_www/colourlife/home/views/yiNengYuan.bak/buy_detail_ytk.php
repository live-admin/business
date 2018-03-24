<!DOCTYPE html>
<html>


	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>购电详情--<?php if(isset($data[0]['status']))echo $data[0]['status']; ?></title>
		  <link href="<?php echo F::getStaticsUrl('/home/yiNengYuan/css/nts.css'); ?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo F::getStaticsUrl('/home/yiNengYuan/js/jquery.min.js'); ?>"></script>
               
	</head>

	<body>
		<div class="znq">
			<div class="content">
				<div class="mar_down"></div>
				<!--描述：间隔-->
				<p class="no_payment"><?php if(isset($data[0]['status']))echo $data[0]['status']; ?></p>
				<div class="no_payment_content">
				<p>充值号码</p>
                                <div><span class=""><?php
                                if($data[0]['status']=='交易成功'){
                                  if(isset($data[0]['token']))echo $data[0]['token'];       
                               
                                }else if($data[0]['status']=='已付款'){
                                  if(!empty($data[0]['token']))echo $data[0]['token']; else echo "等待生成充值号码";     
                                }else if($data[0]['status']=='交易失败'){
                                   echo "没有生成充值号码";
                                } else{
                                    echo "订单支付后生成";    
                                }
                                
                                
                               
                               
                                
                                ?></span></div>
				</div>
				<p class="no_payment_remark">使用此号码直接在对应电表上进行充电即可</p>
				
				<p class="order_detail">订单详情</p>
				<div class="order_content">
                            
					<p>订单号：<?php if(isset($data[0]['sn'])) echo $data[0]['sn']; ?></p>
					<p>电表卡号：<?php if(isset($data[0]['meter'])) echo $data[0]['meter']; ?></p>
					<p>签约人：<?php if(isset($data[0]['customer_name'])) echo $data[0]['customer_name']; ?></p>
					<p>电表地址：<?php if(isset($data[0]['meter_address'])) echo $data[0]['meter_address']; ?></p>
					<p>下单时间：<?php if(isset($data[0]['create_time'])) echo date("Y-m-d H:i", $data[0]['create_time']); ?></p>
					<p>订单总额：<?php if(isset($data[0]['amount'])) echo $data[0]['amount'];  ?>元</p>
                                        <span style="display:<?php 
                                        if($data[0]['status']=='待付款' || $data[0]['status']=='交易关闭' || $data[0]['status']=='交易失败'){
                                         echo "none";   
                                        }
                                        
                                        
                                        ?>" >
					<p>红包抵扣：<?php if(isset($data[0]['red_packet_pay'])) echo $data[0]['red_packet_pay']; ?>元</p>
					<p>实付金额：<?php 
                                       
                                       if(isset($data[0]['pay_id'])){
                                        $id=$data[0]['pay_id'];   
                                        $connection=Yii::app()->db;
                                        $sql="select  * from pay where id=$id";
                                        $command = $connection->createCommand($sql);
                                        $result = $command->queryAll();
                                       if(isset($result[0]['amount'])) echo $result[0]['amount']; else echo 0;   
                                           
                                           
                                       };
                                        ?>元</p>
                                        </span>
				</div>
                            
				<span id="status1"  style="display:none ">
                                    
                                       
                              
                                     <div class="comfirm_recharge"  onclick="href2()">再次购电</div>
                         
                                </span>    
                                
                                <span  id="status2" style="display:none">
                                          
                                        
                              
                                    <div class="comfirm_recharge"  onclick="href('<?php if(isset($data[0]['sn'])) echo $data[0]['sn']; ?>');" >立即支付</div>
                        
                                </span>  
                                
			</div>
		</div>

	</body>
       <script type="text/javascript">
           var url='buy_recharge?cust_id=<?php  echo F::ParamFilter($data[0]['customer_id']);  ?>&meter=<?php echo F::ParamFilter($data[0]['meter']); ?>&customer_name=<?php echo F::ParamFilter($data[0]['customer_name']); ?>&meter_address=<?php echo F::ParamFilter($data[0]['meter_address']); ?>&interface_type=<?php echo $interface_type; ?>&MeterBalance=<?php echo $balance; ?>';
             function href2(){
             document.location=url;  
             }
           function href(sn){
             document.location='PayFromHtml5?sn='+sn;  
               
               
               
           }
           
           
                  var status='<?php if(isset($data[0]['status']))echo $data[0]['status']; ?>';
               
                        $(function(){
                            if(status=='交易成功'){
                            $('#status1').show();      
                            }else if(status=="交易失败"){

                            }else if(status=="待付款"){
                              $('#status2').show();    
                            }else if(status=="交易关闭"){

                            }else if(status=="已退款"){
                            $('#status1').show();  

                            }
                         
                         $('.comfirm_recharge').click(function(){
                             
                         });

                        });
        
        
        </script>  

</html>