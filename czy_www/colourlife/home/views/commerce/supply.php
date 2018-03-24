<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    	<title>彩之云客户端</title>
        <link href="<?php echo F::getStaticsUrl('/common/css/mobile.css'); ?>" rel="stylesheet">
	<script src="<?php echo F::getStaticsUrl('/common/js/jquery.min.js'); ?>"></script>
       
	<style>
a{text-decoration:none; color:#9c9b9b;}
.rm_list{ text-align:left;}
.cross_line{height:1px; line-height:0; margin:0; padding:0; background:#c0c0c0;}
.rm_list dl{overflow:hidden; clear:both;}
.rm_list dt{width:60px; float:left; margin-right:5px;}
.rm_list dd{ float:left; width:230px; padding-left:0; margin-left:0;line-height: 160%;word-spacing: -4px;color:#9c9b9b;}
.rm_list dt img,.contact a img{width:100%;}
.rm_list h3{color:#ff7e00; font-size:16px; font-weight:lighter; margin:0 0 5px 0;}
.rm_index h3{color:#000;}

.goback{display:block; width:100%; margin:20px auto; padding:10px 0; border:none; background:#ff7e00; color:#fff; font-size:16px; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px; font-size:16px; cursor:pointer;}
.h3_p{font-size:16px; color:#000; margin:10px 0;}
.details{padding:2px 15px 15px 15px;}
.contact{width:95%; margin:20px auto; overflow:hidden; border:1px solid #b0b0b0; border-radius:8px; -webkit-border-radius:8px; -moz-border-radius:8px; background:#fff; text-align:left;}
.contact_content{padding:15px 15px 10px;display:block; overflow:hidden; clear:both;}
.contact span{display:block; width:30px; float:right; margin-top:-7px}
	</style>
	</head>
	<body>

	<div class="main">
            
	<div class="RecruitMerchants" id="investment_list">
		 <?php foreach($list as $_v){ ?> 
		<div class="rm_list">
			<dl>
				<dt><a href="/commerce/view/<?php echo $_v->id; ?>"><img src="<?php echo $_v->logoImgUrl; ?>"></a></dt>
				<dd>
					<a href="/commerce/view/<?php echo $_v->id; ?>">
					<h3><?php echo $_v->title;?></h3>
					</a>
					<p>
						<?php echo $_v->introduction; ?>
					</p>
				</dd>
			</dl>
			<div class="clr"></div>
			<div class="details"><?php echo $_v->details; ?></div>
		</div>
		<div class="cross_line"></div>
		<?php } ?>




        <div>
            <a href="/commerce/category" class="goback" style="text-decoration:none;">返回</a>
        </div>

	</div>     
	</div>
       
        <script type="text/javascript">
//            var myScroll;
//            var pullDownEl;
//            var pullDownOffset;
//            var pullUpEl;
//            var pullUpOffset;
//            var count = 0;
//            var pageIndex = 1;
//
//            var type = 0;
//
//            function pullUpAction() {//下拉事件
//                var el, div, i;
//                el = document.getElementById('investment_list');
//                //ajax获取数据
//                pageIndex++;
//                $.ajax({
//                        type : "POST",
//                        url : "/commerce/dataByAjax",
//                        data : {'pageIndex':pageIndex,'type':type},
//                        dataType:'json',
//                        async : false,
//                        success : function(result){
//                            if(result.list.length < 3){
//                                $('#pullUp').hide();
//                            }
//                            if(result.list.length > 0){
//                                for (var i=0; i < result.list.length; i++ ) {
//                                    div = document.createElement('div');
//                                    div.className='rm_list';
//                                    div.innerHTML =  "<dl><dt><a href="+result.list[i]['href']+"><img src='"+result.list[i]['logoImgUrl']+"' /></a></dt><dd><a href="+result.list[i]['href']+"><h3>"+result.list[i]['title']+"</h3></a><p>"+result.list[i]['introduction']+"</p></dd></dl><div class=\"clr\"></div><p class=\"plike_h4 span_label\">"+result.list[i]['details']+"</p>";
//                                    el.appendChild(div, el.childNodes[0]);
//                                }
//                            }
//                        }
//                });
//                myScroll.refresh();
//            }
        </script>
	</body>
</html>
