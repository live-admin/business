<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title><?php echo $this->pageTitle;?></title>
		<link href="<?php echo F::getStaticsUrl('/common/css/paltformshop.mobile.css'); ?>" rel="stylesheet">
        <script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js?time=New Date()'); ?>"></script>
<style>
	.hiringlist_table table{border-collapse:collapse; text-align:center; width:100%; margin-top:10px;}
	.hiringlist_table table th{ padding:10px 0; background:#e1e1e1; color:#000; font-size:16px; border:1px solid #c0c0c0;}
	.hiringlist_table table td{border:1px solid #c0c0c0; padding:10px 0; width:33%;}
	.hiringlist_table table td a{color:#9c9b9b; text-decoration:none;}
</style>
</head>
	
	<body>
		<div class="main">
            <div class="hiringlist_table">
            <table>
							<tr>
								<th colspan="3">招聘列表</th>
							</tr>
							<tr>
             	<?php foreach ($jobs as $key => $job){?>
             		<td><a href="/hrJobsApp/<?php echo $job->id;?>"><?php echo $job->position;?></a></td>
             	<?php if(($key+1)%3==0)echo '</tr><tr>';}?>
             </tr>
            </table>
		</div>
	</body>
</html>
