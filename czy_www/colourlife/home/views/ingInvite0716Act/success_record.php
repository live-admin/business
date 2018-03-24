<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="MobileOptimized" content="320" />
		<title>成功邀请</title>
		<script src="<?php echo F::getStaticsUrl('/common/js/lucky/jquery.min.js'); ?>"></script>
    <script src="<?php echo F::getStaticsUrl('/common/js/iscroll.js'); ?>"></script> 
		<link href="<?php echo F::getStaticsUrl('/common/css/lucky/ingInvite0716Act/xrl.css'); ?>" rel="stylesheet">
	</head>

	<body>
    <div id="wrapper">
		<div class="xrl">
			<div class="head">
				<img src="<?php echo F::getStaticsUrl('/common/images/lucky/ingInvite0716Act/head.jpg');?>" class="lotteryimg" />
			</div>
			<div class="content">
				<p class="tishi big_text">成功邀请</p>

			</div>
			<div class="footer">
				<p>您已邀请<span class=""><?php echo $mycount; ?></span>位好友成功注册彩之云，已领取<span><?php echo $mysum?$mysum:0; ?></span>饭票，待领取<span><?php echo $diff; ?></span>元饭票。</p>
				<p>再邀请<span class=""><?php echo $lack; ?></span>为好友可以再领取50元饭票，加油哦！</p>
				<div class="m_bottom"></div>
				<p>您邀請的好友注册詳情如下：</p>
				<div class="table_data">
					<table id="add">
						<thead>
							<tr>
								<th class="xh">序号</th>
                <th class="yqsj">邀请时间</th>
								<th>手机号码</th>
								<th>注册状态</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; ?>
							<?php if(!empty($records)){ ?>  
							  <?php $i=1; ?>  
							  <?php foreach($records as $_v){ ?>   
							  <tr>
							    <td><?php echo $i; ?></td>
							    <td><?php echo date("Y-m-d H:i:s",$_v->create_time); ?></td>
							    <td><?php echo $_v->mobile; ?></td>
							    <td><?php echo date("Y-m-d H:i:s",$_v->getRegisterTime()); ?></td>
							  </tr>
							    <?php $i++; ?>
							  <?php } ?>  
							<?php }else{ ?>  
							  <tr><td colspan="4">您还没有邀请好友注册，活动期间，邀请好友成功注册彩之云APP可获得饭票及抽奖机会。赶紧去邀请好友注册吧！</td></tr>
							<?php } ?>
					    </tbody>
					</table>
				</div>
				<div id="pullUp">
			      <span class="pullUpIcon"></span>                
			      <span class="pullUpLabel" style="<?php if($mycount > 3){ ?>display:block;<?php }else{ ?>display:none;<?php } ?>">显示更多...</span>
			    </div>
			</div>
			<a href="javascript:history.back();">
				<div class="return">返回</div>
			</a>
			<p class="botp">★注：彩之云享有本次活动的最终解释权 </p>
		</div>
</div>
	</body>
	<script type="text/javascript">
            var col_i = <?php echo $i; ?>;
            var pageIndex=1;
            var myScroll;
            var pullUpEl;
            var pullUpOffset;
            var count = 0;
            
            function pullUpAction() {//下拉事件
                    var el, tr;
                    el = document.getElementById('add');
                    pageIndex++;                    
                    $.ajax({
                        type : "POST",
                        url : "/ingInvite0716Act/getSuccessListByAjax",
                        data : {'pageIndex':pageIndex},
                        dataType:'json',
                        async : false,
                        success : function(result){
                            if(result.code == "error"){
                                $('#pullUp').hide();
                            }else{
                                if(result.records.length < 3){
                                    $('#pullUp').hide();
                                }                            
                                if(result.records.length > 0){
                                    for (var i=0; i < result.records.length; i++ ) {
                                                                                
                                        tr = document.createElement('tr');
                                        tr.innerHTML = "<td>"+col_i+"</td><td>"+result.records[i]['create_time']+"</td><td>"+result.records[i]['mobile']+"</td><td>"+result.records[i]['registerTime']+"</td>";
                                        el.appendChild(tr, el.childNodes[0]);
                                        col_i++;
                                    }
                                }
                            }
                        }
                });
                    myScroll.refresh();
            }
            
            
          function loaded() {
         
     pullUpEl = document.getElementById('pullUp');
     pullUpOffset = pullUpEl.offsetHeight;
     myScroll = new iScroll('wrapper', {
      scrollbarClass: 'myScrollbar', /* 重要样式 */
      useTransition: false,
      onRefresh: function () {
       if (pullUpEl.className.match('loading')) {
        pullUpEl.className = '';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
       }
      },
      onScrollMove: function () {
       if (this.y < (this.maxScrollY - 50) && !pullUpEl.className.match('flip')) {
        pullUpEl.className = 'flip';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始更新...';
        this.maxScrollY = this.maxScrollY;
       } else if (this.y > (this.maxScrollY + 50) && pullUpEl.className.match('flip')) {
        pullUpEl.className = '';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
        this.maxScrollY = pullUpOffset;
       }
      },
      onScrollEnd: function () {
       if (pullUpEl.className.match('flip')) {
        pullUpEl.className = 'loading';
        pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
        pullUpAction(); // Execute custom function (ajax call?)
       }
      }
     });
     setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
    }
    //初始化绑定iScroll控件
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', loaded, false);
            
    </script>  
</html>