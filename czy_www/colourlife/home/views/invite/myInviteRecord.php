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
      
           <div class="lottery_content" style="width:300px; margin:10px auto 0;">
            <div class="invite_friends">
              <h3>我的邀请记录</h3>
              <h4 style="color:#000;">您邀请了<span style="color:#ff7e00;"><?php echo $mycount; ?></span>位好友注册彩之云APP，详情如下：</h4>
              <div class="invite_people_box">
                <table class="invite_people" id="add">
                  <thead>
                    <tr>
                      <th style="width:30px;">序号</th>
                      <th style="width:80px;">邀请时间  </th>
                      <th style="width:105px;">好友手机号码  </th>
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
                          <td>
                                <?php if($_v->status == 1 && $_v->effective == 1){ ?>
                                       已注册
                                <?php }else if($_v->status == 0 && (time() <= $_v->valid_time)){ ?>
                                      注册中<br/><a href="javascript:void();" class="invite_it">重新邀请</a>
                                <?php }else if($_v->status == 0 && (time() > $_v->valid_time)){ ?>
                                        已失效<br/><a href="javascript:void();" class="invite_it">重新邀请</a>
                                <?php }else{ ?>
                                        邀请无效
                                <?php } ?>
                        </td>
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
              <dl>
                <dt>注册状态说明</dt>
                <dd>注册中：好友受邀请后，尚未注册成功的。</dd>
                <dd>已注册：成功注册的受邀好友。</dd>
                <dd>已失效：已经失效的注册邀请，需要重新邀请注册。</dd>
                <dd>邀请无效：未按照活动说明邀请注册的注册用户。</dd>
              </dl>
              <div class="lottery_bottom lb_btn">
                <a href="/invite" style="background:#dcdcdc; color:#505050; margin:5px auto 30px;">返回</a>
              </div>
              
            </div>
           </div>  
          <!--弹出框 start-->
        <div class="opacity" style="display: none;">
          <div class="tips_contairn" style="margin:0;">

              <h3>温馨提示</h3>
              <div class="textinfo">
                <p style="word-spacing: -4px;"> 
                 您已经成功邀请了好友，短信发送内容为：您的好友詹秋凤邀请您注册彩之云,下载地址http://dwz.cn/8YPIv。
                </p>
              </div>
              <div class="pop_btn">
                <a href="javascript:void(0);" class="closeOpacity">确定</a>
              </div>

          </div>
        </div>
      <!--弹出框 end-->   
      </div>
      
       
        
    <script type="text/javascript">
            var username = "<?php echo $username;  ?>"; 
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
                        url : "/invite/getMyInviteRecordByAjax",
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
                                        tr.innerHTML = "<td>"+col_i+"</td><td>"+result.records[i]['create_time']+"</td><td>"+result.records[i]['mobile']+"</td><td>"+result.records[i]['status']+"</td>";
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
          
          function reinvite(mobile){
              $.post(
                '/invite/inviteFriend',
                {'mobile':mobile},
                function (data){
                   if(data.code == "success"){
                       $('.textinfo p').text("您已经成功邀请了好友，短信发送内容为：您的好友"+username+"邀请您注册彩之云,下载地址http://dwz.cn/8YPIv。");
                       $('.opacity').show();
                       center($('.tips_contairn'));
                       $('.send_number').show(); 
                   }else{
                       $('.textinfo p').text(data.code);
                       $('.opacity').show();
                       center($('.tips_contairn'));
                       $('.send_number').show();
                   } 
                }
                ,'json');
          }
          
          $('.closeOpacity').click(function(){
                $('.opacity').hide();	  
         }); 
         
         function center(obj) {
	  var screenWidth = $(window).width();
	  var screenHeight = $(window).height(); //当前浏览器窗口的 宽高
	  var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度
	  var objLeft = (screenWidth - obj.width())/2 ;
	  var objTop = (screenHeight - obj.height())/2 + scrolltop;
	  obj.css({left: objLeft + 'px', top: objTop + 'px','display': 'block'});
	  //浏览器窗口大小改变时
	  $(window).resize(function() {
	  screenWidth = $(window).width();
	  screenHeight = $(window).height();
	  scrolltop = $(document).scrollTop();
	  objLeft = (screenWidth - obj.width())/2 ;
	  objTop = (screenHeight - obj.height())/2 + scrolltop;
	  obj.css({left: objLeft + 'px', top: objTop + 'px'});
	});
         }
         
         $('.invite_it').live('click',function (){
             var mobile = $(this).parent().prev().html();
             $(this).parent().html("已重新邀请")
             $.post(
                '/invite/inviteFriend',
                {'mobile':mobile},
                function (data){
                   if(data.code == "success"){
                       $('.textinfo p').text("您已经成功邀请了好友，短信发送内容为：您的好友"+username+"邀请您注册彩之云,下载地址http://dwz.cn/8YPIv。");
                       $('.opacity').show();
                       center($('.tips_contairn'));
                       $('.send_number').show(); 
                   }else{
                       $('.textinfo p').text(data.code);
                       $('.opacity').show();
                       center($('.tips_contairn'));
                       $('.send_number').show();
                   } 
                }
                ,'json');
         });
    </script>
        
    </body>	
</html>
