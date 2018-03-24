<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=yes;" />
      <title>抽奖</title>
      <link href="<?php echo F::getStaticsUrl('/common/css/lucky/lottery.css?time=123456'); ?>" rel="stylesheet">
      <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>  
      <script src="<?php echo F::getStaticsUrl('/common/js/iscroll.js'); ?>"></script> 
    </head>
    <body style="background:#fff;">
      <div class="lottery_topic" id="wrapper" style="background:#fff;" data-role="content">
      
           <div class="lottery_content" style="width:300px; margin:0 auto;">
            <div class="invite_friends">
              <h3>成功邀请注册</h3>
              <p style="margin:10px 0;">您邀请了好友注册彩之云APP，目前已成功注册
                  <span style="color:#ff7e00;"><?php echo $mycount; ?></span>人。
                  您可获得<span style="color:#ff7e00;"><?php echo $allsum; ?></span>元红包，
                  已发送红包<span style="color:#ff7e00;"><?php echo $mysum?$mysum:0; ?></span>元。<!-- ，
                  您再邀请注册<span style="color:#ff7e00;"><?php //echo $lack; ?></span>人即可再获得<span style="color:#ff7e00;">50</span>元红包，加油哦！ -->
              </p>
              <p style="margin:10px 0;">您获得的红包明细，可以在"我"-"我的红包"中查询。</p>
              <p style="color:#000;">您成功邀请注册的好友信息为：</p>
              <div class="invite_people_box">
                <table class="invite_people" id="add">
                  <thead>
                    <tr>
                      <th style="width:30px;">序号</th>
                      <th style="width:80px;">邀请时间  </th>
                      <th style="width:105px;">好友手机号码  </th>
                      <th>注册时间</th>
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
                        <tr><td colspan="4">您还没有邀请好友注册，活动期间，邀请好友成功注册彩之云APP可获得红包及抽奖机会。赶紧去邀请好友注册吧！</td></tr>
                    <?php } ?>    
                  </tbody>
                </table>
              </div>
              
              <div id="pullUp">
                <span class="pullUpIcon"></span>                
                <span class="pullUpLabel" style="<?php if($mycount > 3){ ?>display:block;<?php }else{ ?>display:none;<?php } ?>">显示更多...</span>
              </div>
              
              <div class="lottery_bottom lb_btn">
                <a href="/invite" style="background:#dcdcdc; color:#505050">返回</a>
              </div>
              
            </div>
           </div>
          
           
          
        
         
      
      
      </div>
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
                        url : "/invite/getSuccessListByAjax",
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
    </body>
	
</html>
