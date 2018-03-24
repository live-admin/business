<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"/>
        <meta name="format-detection" content="telephone=no" />
        <meta name="MobileOptimized" content="320"/>
		    <title>纯甄调查问卷</title>
        <link href="<?php echo F::getStaticsUrl('/common/css/lucky/newExamine/survey.css'); ?>" rel="stylesheet">
        <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
	</head>
	


	<body>
		<div class="survey">
          <div class="head"><img src="<?php echo F::getStaticsUrl('/common/images/lucky/newExamine/head.jpg');?>" class="lotteryimg" /></div>
          <div class="survey_content">
            <form action="" method="post" onsubmit="return false" id="examineForm">
            <h3>海淘消费者调查问卷</h3>
            <dl>
              <dt>１.是否有海淘经历</dt>
              <dd>
                <input type="radio" name="qst1" id="slct1" value="1"/>
                <label for="slct1">是</label>
              </dd>
              <dd>
                <input type="radio" name="qst1" id="slct2" value="2"/>
                <label for="slct2">否</label>
              </dd>
            </dl>
            <dl>
              <dt>２.哪些原因让您对海淘感兴趣？ [多选题]</dt>
              <dd>
                <input type="checkbox" name="qst2" id="slct3" value="1"/>
                <label for="slct3">与商场进口商品差价大</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst2" id="slct4" value="2"/>
                <label for="slct4">国际品牌高质量保障</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst2" id="slct5" value="3"/>
                <label for="slct5">无须自己千里长征，海淘送货上门</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst2" id="slct6" value="4"/>
                <label for="slct6">国际大牌高品质保障</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst2" id="slct7" value="5"/>
                <label for="slct7">退换保障制度健全</label>
              </dd>
              
            </dl>
            <dl>
              <dt>３.哪些原因影响您的海淘体验？[多选题]</dt>
              <dd>
                <input type="checkbox" name="qst3" id="slct8" value="1"/>
                <label for="slct8">支付问题</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst3" id="slct9" value="2"/>
                <label for="slct9">国际配送周期长</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst3" id="slct10" value="3"/>
                <label for="slct10">有效期等质量问题</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst3" id="slct11" value="4"/>
                <label for="slct11">是否正品保障</label>
              </dd>
            </dl>
            <dl id="mulit">
              <dt>４.对哪些海淘商品最感兴趣？  [最多选3项]</dt>
              <dd>
                <input type="checkbox" name="qst4" id="slct12" value="1"/>
                <label for="slct12">母婴食品</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct13" value="2"/>
                <label for="slct13">母婴用品</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct14" value="3"/>
                <label for="slct14">保健品</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct15" value="4"/>
                <label for="slct15">生活日用</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct16" value="5"/>
                <label for="slct16">服装</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct17" value="6"/>
                <label for="slct17">化妆品</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct18" value="7"/>
                <label for="slct18">数码产品</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct19" value="8"/>
                <label for="slct19">箱包</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct20" value="9"/>
                <label for="slct20">鞋类</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst4" id="slct21" value="10"/>
                <label for="slct21">奢侈品</label>
              </dd>
            </dl>
            <dl>
              <dt>５.每个月海淘开销</dt>
              <dd>
                <input type="radio" name="qst5" id="slct22" value="1"/>
                <label for="slct22">100元以下</label>
              </dd>
              <dd>
                <input type="radio" name="qst5" id="slct23" value="2"/>
                <label for="slct23">100-500</label>
              </dd>
              <dd>
                <input type="radio" name="qst5" id="slct24" value="3"/>
                <label for="slct24">500-1000</label>
              </dd>
              <dd>
                <input type="radio" name="qst5" id="slct25" value="4"/>
                <label for="slct25">1000以上</label>
              </dd>
            </dl>
            <dl>
              <dt>６.您在海淘中遇到或者很困扰的问题？ [多选题]</dt>
              <dd>
                <input type="checkbox" name="qst6" id="slct26" value="1"/>
                <label for="slct26">到货时长</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst6" id="slct27" value="2"/>
                <label for="slct27">商品品质</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst6" id="slct28" value="3"/>
                <label for="slct28">尺寸不符</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst6" id="slct29" value="4"/>
                <label for="slct29">售后服务</label>
              </dd>
              <dd>
                <input type="checkbox" name="qst6" id="slct30" value="5"/>
                <label for="slct30">快递服务差</label>
              </dd>
            </dl>
            <div class="submit_box">
              <p class="showRet" style="color:red"></p>
              <button class="survey_btn">提交问卷</button>
            </div>
            <p>★注：彩生活享有本次活动的最终解释权 </p>
          </form>
          </div>
		</div>
        
      <script type="text/javascript">
		    $('#mulit input[type=checkbox]').click(function() {
            $("input[name='qst4']").attr('disabled', true);
            if ($("input[name='qst4']:checked").length >= 3) {
                $("input[name='qst4']:checked").attr('disabled', false);
            } else {
                $("input[name='qst4']").attr('disabled', false);
            }
        });

        $(".survey_btn").click(function(){
            var dl=$('.survey_content dl');
            var dllen=dl.length;
            var i,j,flag=0;
            //检查单选框是否选完
            for(i=0;i<dllen;i++){
                var radio=dl.eq(i).find('input');
                var radiolen=radio.length;
                for(j=0;j<radiolen;j++){
                    if(radio.eq(j).attr('checked')){break}
                }
                if(j>=radiolen){
                    flag=1;
                    break;
                }

            }

            if(flag==1){
                $('.showRet').text('有问题选项未勾选!');
            }

            if(flag==0){
                var qst1=new Array(),qst2=new Array(),qst3=new Array(),qst4=new Array(),qst5=new Array(),qst6=new Array();
                $("input[name='qst1']").each(function(index){
                    if($(this).attr('checked')){
                      qst1.push($(this).val());
                    }
                });
                $("input[name='qst2']").each(function(index){
                    if($(this).attr('checked')){
                      qst2.push($(this).val());
                    }
                });
                $("input[name='qst3']").each(function(index){
                    if($(this).attr('checked')){
                      qst3.push($(this).val());
                    }
                });
                $("input[name='qst4']").each(function(index){
                    if($(this).attr('checked')){
                      qst4.push($(this).val());
                    }
                });
                $("input[name='qst5']").each(function(index){
                    if($(this).attr('checked')){
                      qst5.push($(this).val());
                    }
                });
                $("input[name='qst6']").each(function(index){
                    if($(this).attr('checked')){
                      qst6.push($(this).val());
                    }
                });
                
                $.post(
                    '/newExamine/submit',
                    {'ans1':qst1,'ans2':qst2,'ans3':qst3,'ans4':qst4,'ans5':qst5,'ans6':qst6},
                    function (ret){
                        if(ret.success==1){
                            $(".showRet").text("提交成功，谢谢您的反馈！");
                            $("#survey_btn").remove();
                            window.location.href='/newExamine/success';
                        }else{
                            for(var key in ret.data.errors){
                                $(".showRet").text(ret.data.errors[key]);
                            }
                            $("#survey_btn").remove();
                        }
                    }
                    ,'json');
            }
    });
		
		</script>
	</body>

</html>