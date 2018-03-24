<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>客服拜访-彩之云 </title>
		<link href="<?php echo F::getStaticsUrl('/common/css/visit.css'); ?>" rel="stylesheet">
		<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
	</head>
	<body>

<div class="main">

    <div class="invite">

      <?php if($model){ ?>

        <p class="font_h4">为提高物业服务品质，现有物业<span style="color:#ff7e00;">客户经理<?php echo $model->EmployeeName;?></span>就"<span style="color:#ff7e00;"><?php echo $model->visit_content;?></span>"对您进行预约上门拜访。</p>
        <div class="cross_line"></div>
        <dl class="manager_visite_list">
            <dt><img src="<?php echo F::getStaticsUrl('/common/images/visit/visit9.jpg');?>" /></dt>
            <dd>
                <h4>预约拜访时间：</h4>
                <p><?php echo $model->invite_visit_time.' '.$model->invite_visit_hour;?></p>
            </dd>
            <?php

            if($model->status==Visit::STATUS_REFUSE){
                echo '
            <div class="clear"></div>
            <dt><img src="'.F::getStaticsUrl('/common/images/visit/visit9.jpg').'" /></dt>

            <dd>
                <h4>我拒绝了客户经理'.$model->EmployeeName.'的拜访<br />拒绝理由是：'.$model->refuse.'</h4>
                <p>'.date("Y-m-d H:i",$model->reply_time).'</p>
            </dd>
                ';
            }elseif($model->status!=Visit::STATUS_UNTREATED){
                echo '
            <div class="clear"></div>
            <dt><img src="'.F::getStaticsUrl('/common/images/visit/visit9.jpg').'" /></dt>

            <dd>
                <h4>我同意客户经理'.$model->EmployeeName.'的拜访</h4>
                <p>'.date("Y-m-d H:i",$model->reply_time).'</p>
            </dd>
                ';
                if($model->status==Visit::STATUS_NOCOMMENTS OR $model->status==Visit::STATUS_EVALUATION){
                    echo '
                    <div class="clear"></div>
                    <dt><img src="'.F::getStaticsUrl('/common/images/visit/visit9.jpg').'" /></dt>
                    <dd>
                        <h4>客户经理'.$model->EmployeeName.'上门拜访了您，邀请您对本次拜访评价，请点击下面按钮对本次服务评分。</h4>
                        <p>'.date("Y-m-d H:i",$model->visit_time).'</p>
                    </dd>
                    ';
                    if($model->status==Visit::STATUS_EVALUATION)
                    {
                        echo '
                    <div class="clear"></div>
                    <dt><img src="'.F::getStaticsUrl('/common/images/visit/visit9.jpg').'" /></dt>
                    <dd>
                        <h4>我对客户经理'.$model->EmployeeName.'的上门拜访进行了评价，评价为 '.$model->getEvaluationName(false).'。<br />评价内容是：'.$model->evaluation.'</h4>
                        <p>'.date("Y-m-d H:i",$model->evaluation_time).'</p>
                    </dd>
                    ';
                    }
                }
            }

            if($model->is_complain==1)
            {
                echo '
                    <div class="clear"></div>
                    <dt><img src="'.F::getStaticsUrl('/common/images/visit/visit9.jpg').'" /></dt>
                    <dd>
                        <h4>我对客户经理'.$model->EmployeeName.'进行了投诉。</h4>
                    </dd>
                    ';
            }

            ?>

        </dl>
        <div class="clear" style="margin-bottom:15px;"></div>
        <div class="cross_line"></div>
        <?php
        if($model->status==Visit::STATUS_UNTREATED)
        {
            echo '
             <div class="btn_contairn_three">
            <a href="' . F::getHomeUrl() . 'visit/accept/'.$model->id.'" class="green_btn floatL">接受</a>
            <a href="' . F::getHomeUrl() . 'visit/reject/'.$model->id.'" class="red_btn floatR">拒绝</a>
            </div>
            <p style="text-align:center">注:如需调整拜访时间请点击“接受”后修改。</p>
            ';
        }
        if($model->status==Visit::STATUS_NOCOMMENTS AND $model->is_complain!=1)
        {
            echo '
            <div class="btn_contairn_three">
            <a href="' . F::getHomeUrl() . 'visit/evaluation/'.$model->id.'" class="green_btn" style="width:100%;">评价</a>
            </div>
            ';
        }

        if($count>1)
        {
            echo '
            <div class="cross_line"></div>
            <div class="btn_contairn" style="margin:15px 0 50px;">
                <a href="/visit/history" class="orange_btn">查看历史邀请清单</a>
            </div>
            ';
        }

        ?>
      <?php }else{ ?>
        <p class="font_h4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;尊敬的业主，为更好地提升物业服务品质，我们将定期安排客户经理为您上门提供专业服务，欢迎您对客户经理的服务品质进行监督，我们将秉承“为客户创造价值”的核心理念，把社区服务做到家！</p>
      <?php } ?>


    </div>

</div>


	</body>
</html>
