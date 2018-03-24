<!DOCTYPE html>
<html>
  <head>
      <meta charset="gb2312">
      <meta name="viewport" content="width=device-width, initial-scale=1"> 
      <meta name="format-detection" content="telephone=no" />
      <link rel="stylesheet" href="<?php echo F::getStaticsUrl('/enterred/css/ruhuo.css');?>"></link>
  </head>
  <body style="background:#f6ebe9;">
    <div class="ruhuo">
        <?php
            foreach ($res as $key=>$v){
                $sql='select * from one_yuan_code where code="'.$v['code'].'"';
                $resArr=Yii::app()->db->createCommand($sql)->queryAll();
                $codeSend=date('Y-m-d H:i:s',$resArr[0]['send_time']);
                $codeValid=date('Y-m-d H:i:s',$resArr[0]['valid_time']);
        ?>
      <div class="codebox">
        <dl class="code_ticket">
          <dd>
             <h3><?php echo $v['code'];?></h3>
             <p>（仅限一元购商品使用）</p>
          </dd>
          <dt>
             <h4>使用期限</h4>
             <p><?php echo $codeSend;?></p>
             <p><?php echo $codeValid;?></p>
          </dt>
        </dl>
      </div>
      <?php }?>
        
        <a href="<?php echo $oneyuan_url;?>" class="change_btn" target="_blank">立即换购</a>
      <dl class="warmtip">
        <dt>温馨提示：</dt>
        <dd>获得一元购换购码以后，在彩之云一元购区只需要支付一块钱即可换购您心仪的商品哦，心动不如行动！</dd>
      </dl>
    </div>
  
  </body>
</html>