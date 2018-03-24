<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title><?php echo $this->pageTitle;?></title>
		<link href="<?php echo F::getStaticsUrl('/common/css/paltformshop.mobile.css'); ?>" rel="stylesheet">
<style>
	a{text-decoration:none; color:#9c9b9b;}
	.position{width: 95%;margin: 10px auto;}
	.span_label{color:#000;}
	.position_info{ text-align:left;}
	.position_info div{margin-top:15px;}
	.position_info p{ line-height:180%; margin:0;}
	.goback{width:100%; display:block; padding:10px 0; background:#cecece; color:#505050; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; font-size:16px;}
	.submit_button{display:block; width:100%; padding:10px 0; border:none; background:#ff7e00; color:#fff; font-size:16px; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; font-size:16px; cursor:pointer;}
	.submitcontairn{padding-top:20px;}
</style>
</head>
	<body>
		<div class="main">
            
            <div class="position">
              <a href="/hrJobsApp" class="goback">返回列表</a>
							<?php if(!empty($job)){?>
              <div class="position_info">
                <div>
                  <span class="span_label">职务：</span>
                  <span><?php echo $job->position;?></span>
                </div>
                <div>
                  <span class="span_label">部门：</span>
                  <span><?php echo $job->department;?></span>
                </div>
                <div>
                  <span class="span_label">时间：</span>
                  <span><?php echo $job->start_date;?> 至 <?php echo $job->end_date;?></span>
                </div>
                <div>
                  <span class="span_label">工作地点：</span>
                  <span><?php echo $job->work_place;?></span>
                </div>
                <div>
                  <span class="span_label">工作职责：</span>
                  <p><?php echo $job->work_content;?></p>
                </div>
                <div>
                  <span class="span_label">工作内容：</span>
                  <p><?php echo $job->work_need;?></p>
                </div>
                <div>
                  <span class="span_label">招聘人数：</span>
                  <span><?php echo $job->need_person;?> 人</span>
                </div>
              </div>
							<?php }?>
              <div class="submitcontairn">
                <a href="/hrJobsApp/toApply?id=<?php echo $job->id;?>" class="submit_button">我要申请</a>
              </div>
            </div>        
		</div>

	</body>
</html>
