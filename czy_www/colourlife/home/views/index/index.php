<script type="text/javascript" >

/*
 * 当前页相关信息
 */
var pId="";
var cId="";
var aId="";
(function() {
	
	// 当前页面配置信息
	pageConfig = {
		"pageItemType"		: 4,	// 当前页面所属的大类
		"mainNavCurIndex"	: 0		// 主栏目中当前栏目的索引值，从1开始，0表示没有当前效果
	}
})();

function showCommunityByCity(obj){
    var _tip = $(".el_tipPop_bg");
    var _bg = $(".posAddressTipPop");
    var currentProvinceName = $("#cmbProvince3 .selected").text();
    var currentCityName = $(obj).text();
    var currentProvinceId = $("#cmbProvince3 .selected").attr("rel");
    var currentCityId = $(obj).attr("rel");
    $(".func_posAddress").attr({"pId":currentProvinceId,"cId":currentCityId}).text(currentProvinceName+","+currentCityName);
    //页面加载区级单位
    GetArea3(currentCityId);
    GetCommunityAll(currentCityId);

    //清理旧数据
    $(".homeDetailPosBox th").show();
    $('.homeDetailPosBox').find(".homeDetailPosBox_th").text("");

    $(_bg).remove();
    $(_tip).remove();        
}

/*
 * 当前页调用
 */
//获取当前URL 的主机部分
var url=window.location.host+"/site/";

//获取默认市级单位
function GetDefaultCity3(obj){
  $.get("http://"+url+"regions?parent_id="+obj,function(defaultCityData3){
	var defaultAreaId3 = defaultCityData3[0].id;
	//构造对应的省级下面的市级单位
	$('#cmbCity3').empty();
	for (var i = 0; i < defaultCityData3.length; i++) {
		var defaultCityname3 = defaultCityData3[i].name;
		var defaultCityid3 = defaultCityData3[i].id;
		$('#cmbCity3').append("<li class='CRselectBoxItem'><a href='javascript:void(0)' rel=" + defaultCityid3 + " onclick='showCommunityByCity(this)'>" + defaultCityname3 + "</a></li>");
	}
	$("#cmbProvince3 li").find("a").each(function(){
		if($(this).attr("rel")==pId){
		    var nametxt=$(this).text();
			$(this).parents("#cmbProvince3").siblings(".CRselectValue").text(nametxt);
			$(this).addClass("selected");
			}
		});
	$("#cmbCity3 li").find("a").each(function(){
		if($(this).attr("rel")==cId){
			var nametxt=$(this).text();
			$(this).parents("#cmbCity3").siblings(".CRselectValue").text(nametxt);
			$(this).addClass("selected");
			}
		});
   }, 'json')
} 
//加载市级单位
function GetCity3(obj) {
	$.get("http://"+url+"regions?parent_id="+$(obj).attr("rel"), function (cityData3) {
		var firstCityId3=cityData3[0].id;
		$('#cmbCity3').empty();
		$('#cmbCity3').append("<li class='CRselectBoxItem'><a href='javascript:void(0);' rel=" + $(obj).attr("rel") + ">请选择市级</a></li>");
        $('#cmbCity3').siblings(".CRselectValue").text("请选择市级");        
		for (var i = 0; i < cityData3.length; i++) {
			var cityname3 = cityData3[i].name;
			var cityid3 = cityData3[i].id;
			$('#cmbCity3').append("<li class='CRselectBoxItem'><a href='javascript:void(0)' rel=" + cityid3 + " onclick='showCommunityByCity(this)'>" + cityname3 + "</a></li>");
		}
                getCommunityByProvince($(obj).attr("rel"));
                GetArea3($(obj).attr("rel"));
	}, 'json');
}; 

//加载区级单位
function GetArea3(obj) {
	$.get("http://"+url+"regions?parent_id="+obj, function (areaData3) {
		$(".el_btn_current").removeClass("el_btn_current")
		//$('.homeSubPosBox').find("a").first().addClass("el_btn_current")
		$('.homeSubPosBox').find("a").remove();
		for (var i = 0; i < areaData3.length; i++) {
			var areaname3 = areaData3[i].name;
			var areaid3 = areaData3[i].id;
			$('.homeSubPosBox').append('<a class="el_btn" href="#" val="'+areaid3+'">'+areaname3+'</a>');
		}
	}, 'json');
};

//页面加载全国所有小区
function GetCommunityTotal(obj) {
	            $.get("http://"+url+"communities?region_id="+obj, function (communitiesDataTotal) {
		        var getNewcommunities = "";
                var getNewcommunitiesBox="";
                $(".homeDetailPosBox th").show();
                $('.homeDetailPosBox').find(".homeDetailPosBox_th").text("");
                //判断小区数据是否为空
                if(communitiesDataTotal.length > 0)
                {
                        $(".dataTable_s8").show();
                        for (var i = 0; i < communitiesDataTotal.length; i++) {
                                var communitiesAlpha3 = communitiesDataTotal[i].alpha;
                                var communitiesName3 = communitiesDataTotal[i].name;
                                var communitiesId3 = communitiesDataTotal[i].id;
                                var communitiesUrl = communitiesDataTotal[i].domain;
                                if($(".homeDetailPosBox th[tag="+communitiesAlpha3+"]").length > 0) //判断字母Box是否存在
                                {

                                        getNewcommunities = '<a href="'+communitiesUrl+'" class="el_btn">'+communitiesName3+'</a>';
                                        $(getNewcommunities).appendTo($(".homeDetailPosBox th div[name="+communitiesAlpha3+"]"));
                                }
                                else
                                {
                                        alert("暂无相应分类，请构造！");
                                }
								
                        }
						
                }
                else
                {	
                        $(".dataTable_s8").hide();
                        //alert("对不起，您所选地区暂无数据！")
                }
                $(".homeDetailPosBox th div").each(function (){
                    if($(this).html() == ""){
                       $(this).parent().hide();    
                    }                    
                });
				$('#scrollbar1').tinyscrollbar();
	}, 'json');
};

//页面加载省份所有小区
function getCommunityByProvince(obj) {
	$.get("http://"+url+"communitiesByProvince?region_id="+obj, function (communitiesDataTotal) {
		var getNewcommunities = "";
                var getNewcommunitiesBox="";
                $(".homeDetailPosBox th").show();
                $('.homeDetailPosBox').find(".homeDetailPosBox_th").text("");
                //判断小区数据是否为空
                if(communitiesDataTotal.length > 0)
                {
                        $(".dataTable_s8").show();
                        for (var i = 0; i < communitiesDataTotal.length; i++) {
                                var communitiesAlpha3 = communitiesDataTotal[i].alpha;
                                var communitiesName3 = communitiesDataTotal[i].name;
                                var communitiesId3 = communitiesDataTotal[i].id;
                                var communitiesUrl = communitiesDataTotal[i].domain;
                                if($(".homeDetailPosBox th[tag="+communitiesAlpha3+"]").length > 0) //判断字母Box是否存在
                                {

                                        getNewcommunities = '<a href="'+communitiesUrl+'" class="el_btn">'+communitiesName3+'</a>';
                                        $(getNewcommunities).appendTo($(".homeDetailPosBox th div[name="+communitiesAlpha3+"]"));
                                }
                                else
                                {
                                        alert("暂无相应分类，请构造！");
                                }
								
                        }
						
                }
                else
                {	
                        $(".dataTable_s8").hide();
                        //alert("对不起，您所选地区暂无数据！")
                }
                $(".homeDetailPosBox th div").each(function (){
                    if($(this).html() == ""){
                       $(this).parent().hide();    
                    }                    
                });
				$('#scrollbar1').tinyscrollbar();
	}, 'json');
};

//加载市级单位下全部小区
function GetCommunityAll(obj) {
	$.get("http://"+url+"communities?region_id="+obj, function (communitiesData3) {
		var getNewcommunities = "";
		var getNewcommunitiesBox="";
		$(".homeDetailPosBox th").show();
                $('.homeDetailPosBox').find(".homeDetailPosBox_th").text("");
		//判断小区数据是否为空
		if(communitiesData3.length > 0)
		{
			$(".dataTable_s8").show();
			for (var i = 0; i < communitiesData3.length; i++) {
				var communitiesAlpha3 = communitiesData3[i].alpha;
				var communitiesName3 = communitiesData3[i].name;
				var communitiesId3 = communitiesData3[i].id;
				var communitiesUrl = communitiesData3[i].domain;
				if($(".homeDetailPosBox th[tag="+communitiesAlpha3+"]").length > 0) //判断字母Box是否存在
				{
					
					getNewcommunities = '<a href="'+communitiesUrl+'" class="el_btn">'+communitiesName3+'</a>';
                                        $(getNewcommunities).appendTo($(".homeDetailPosBox th div[name="+communitiesAlpha3+"]"));
				}
				else
				{
					alert("暂无相应分类，请构造！");
				}
			}
			
		}
		else
		{	
			$(".dataTable_s8").hide();
			//alert("对不起，您所选地区暂无数据！")
		}
                $(".homeDetailPosBox th div").each(function (){
                    if($(this).html() == ""){
                       $(this).parent().hide();    
                    }                    
                });
		$('#scrollbar1').tinyscrollbar();
	}, 'json');
};

//加载区级单位下小区
function GetCommunity3(obj) {
	$.get("http://"+url+"communities?region_id="+obj, function (communitiesData3) {
                var getNewcommunities = "";
                var getNewcommunitiesBox="";
                $(".homeDetailPosBox th").show();
                $('.homeDetailPosBox').find(".homeDetailPosBox_th").text("");
                //判断小区数据是否为空
                if(communitiesData3.length > 0)
                {
                        $(".dataTable_s8").show();
                        for (var i = 0; i < communitiesData3.length; i++) {
                                var communitiesAlpha3 = communitiesData3[i].alpha;
                                var communitiesName3 = communitiesData3[i].name;
                                var communitiesId3 = communitiesData3[i].id;
                                var communitiesUrl = communitiesData3[i].domain;
                                if($(".homeDetailPosBox th[tag="+communitiesAlpha3+"]").length > 0) //判断字母Box是否存在
                                {

                                        getNewcommunities = '<a href="'+communitiesUrl+'" class="el_btn">'+communitiesName3+'</a>';
                                        $(getNewcommunities).appendTo($(".homeDetailPosBox th div[name="+communitiesAlpha3+"]"));
                                }
                                else
                                {
                                        alert("暂无相应分类，请构造！");
                                }
                        }
						
                }
                else
                {	
                        $(".dataTable_s8").hide();
                        //alert("对不起，您所选地区暂无数据！")
                }
                $(".homeDetailPosBox th div").each(function (){
                    if($(this).html() == ""){
                       $(this).parent().hide();    
                    }                    
                });
				$('#scrollbar1').tinyscrollbar();
	}, 'json');
};


//登录&注册提示框
function SimulationAlert(noticeText){
	var SimulationAlert =  '\
		<div class="innerBox">\
		<div class="alertNoticeInfo"style="padding:20px 0 10px 20px;">'+noticeText+'</div>\
		<input type="button" value="确定"id="NoticeInfoBn" style="float:right;margin:0 30px 0 0;cursor:pointer;"/>\
		</div>\
	';
	$.cl_tipPop({
			"$this"		: $(this),
			"title"		: '',
			"type"		: "default",
			"addClass"	: "posAddressTipPop",
			"temp"		: SimulationAlert,
			"needBg"	: true,
			"onlyOne"	: true
		});
	$("#NoticeInfoBn").click(function() {
		colseSimulationAlert();
		clearTimeout(TimeoutClose);
	});
	//提示框5秒后自动关闭
	var TimeoutClose = setTimeout(function() {
		colseSimulationAlert();
	},5000)
}
//登录&注册提示框 关闭方法
function colseSimulationAlert() {
	$(".el_tipPop_bg").remove();
	$(".el_tipPop").remove();
}

				
$(function() {
			   

	//省市区弹出框
	
		//加载省级单位
		$.get("http://"+url+"regions?region_id=0", function(provinceData){
			for(var i=0;i<provinceData.length;i++)
			{
				var value=provinceData[i].id;
				var text=provinceData[i].name;
				//var defaultData3 = provinceData[0].id;
				$('#cmbProvince3').append("<li class='CRselectBoxItem'><a href='javascript:void(0)' rel=" + value + " onclick='GetCity3(this)'>" + text + "</a></li>");
			}
			//加载默认城市
			//alert(pId)
			GetDefaultCity3(pId); 

		}, 'json');

	
	//判断进入页面是否调用省市区弹出框
	var checkAddress = "1"; //值为空时，页面自动调用省市区弹出框
	if(checkAddress === "")
	{
		GetDefaultCity3(pId);
	}
	else
	{
		
	}
	
           
		<?php
                if(isset($city->id)){
                ?>                    
			pId = <?php echo $province->id; ?>;
			cId = <?php echo $city->id;?>;
		<?php }
		else
		{?>
			pId = 19; //广东省
			cId = 233; //深圳市
		<?php } ?>
		
		GetDefaultCity3(pId);
		
		$(this).blur();
	
	
	//区级单位点击动作
	var area_el_btn = $(".homeSubPosBox").find(".el_btn");
	$(area_el_btn).die("click").live("click",function() {
		$(".el_btn_current").removeClass("el_btn_current")
		$(this).addClass("el_btn_current");
		var currentCommunityId = $(this).attr("val")
		if(currentCommunityId != "全部")
		{
			GetCommunity3(currentCommunityId);
		}
		else
		{	
			
			//清理旧数据
			var currentCityId = $(".func_posAddress").attr("cId");
			//页面加载区级单位
			GetCommunityAll(currentCityId);
		}
		
	});
	
	

	// 切换到注册栏
	/*$(".func_toReg").click(function() {
		var regNums = $("#regNums").attr("value");
		if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(regNums)))
		{
			SimulationAlert("请输入正确的手机号码！");
		}
		else
		{
		  $.ajax({
                type:"POST",
                dataType:'json',
                url:"<?php //echo Yii::app()->createUrl("site/checkMobile")."?mobile="; ?>"+regNums,
                success:function(regData){
                    var _result = regData['result'];
                    if(_result){
                        $(".homeFrameTab_rp_innerBox[tabid='reg']").removeClass("fn_none").siblings(".homeFrameTab_rp_innerBox[tabid='log']").addClass("fn_none");
			             $("#CustomerForm_mobile").attr("value",regNums);
                         sendMsg(regNums);
                    }else{
                        SimulationAlert("手机号码已存在！");
                    }
                }
            });
		}
		return false;
	});*/
	
	// 切换到登录栏
	$(".func_toLog").click(function() {
		$(".homeFrameTab_rp_innerBox[tabid='log']").removeClass("fn_none").siblings(".homeFrameTab_rp_innerBox[tabid='reg']").addClass("fn_none");
		
		return false;
	});
	
	//登录
	function loginCheck(username,password,verifyCode){
		$.ajax({
			type: 'post', cache: false, dataType: 'json',
			url: "http://"+window.location.host+"/index/login",
			data:{"username":username,"password":password,"verifyCode":verifyCode},
			async: true,
			success: function (data) {
				if (data!= null && data!= "") {
				//处理
					if(data['ok']==1)
					{
						/*成功后执行*/
						window.location.reload();
						/*成功后执行 end*/
					}
					else
					{
						SimulationAlert(data['ok']);
					}
					return;
				} else {
					SimulationAlert('登录失败，请重试！');
				}
			},
			error: function (result) {

			}
		});
	}
	
	$("#userLogin").click(function() {
		var username = $("#CustomerLoginForm_username").attr("value");
		var password = $("#CustomerLoginForm_password").attr("value");
		
		var verifyCode = $("#CustomerLoginForm_verifyCode").attr("value");
		if( username == '')
		{
			SimulationAlert('用户名不能为空!');
			return false;
		}
		else if( password == '')
		{
			SimulationAlert('密码不能为空!');
			return false;
		}
		else if(verifyCode == '')
		{
			SimulationAlert('验证码不能为空!');
			return false;
		}
		loginCheck(username,password,verifyCode);
	});
	
	//注册
	function regCheck(username,mobile,repeatPwd,password,community_id,build_id,room,code,name){
		$.ajax({
			type: 'post', cache: false, dataType: 'json',
			url: "http://"+window.location.host+"/site/reg",
			data:{"username":username,"mobile":mobile,"repeatPwd":repeatPwd,"password":password,"community_id":community_id,"build_id":build_id,"room":room,"code":code,"name":name},
			async: true,
			success: function (data) {
				if (data!= null && data!= "") {
				//处理
					if(data['ok']==1)
					{
						/*成功后执行*/
                        window.location.reload();
						/*成功后执行 end*/
					}
					else
					{
						SimulationAlert(data['ok']);
					}
					return;
				} else {
					SimulationAlert('登录失败，请重试！');
				}
			},
			error: function (result) {

			}
		});
	}
	$("#userReg").click(function() {
		var username = $("#CustomerForm_username").attr("value");
        var mobile = $("#CustomerForm_mobile").attr("value");
        var name = $("#CustomerForm_name").attr("value");
		var repeatPwd = $("#regRepeatPwd").attr("value");
		var password = $("#regPassword").attr("value");
		var community_id = $("#CustomerForm_community_id").attr("value");
		var build_id = $("#CustomerForm_build_id").attr("value");
		var room = $("#CustomerForm_room").attr("value");
		var code = $("#regCode").attr("value");

        if( username == '')
        {
            SimulationAlert('用户名不能为空!');
            return false;
        }
		if(name == ''){
			  SimulationAlert('真实姓名不能为空!');
	          return false;
		}
		
        if( mobile == '')
        {
            SimulationAlert('手机号码不能为空!');
            return false;
        }
		else if( repeatPwd == '' || password == '')
		{
			SimulationAlert('密码不能为空!');
			return false;
		}
		else if( community_id == '')  //地址标准
		{
			SimulationAlert('地址不能为空!');
			return false;
		}
		else if(code == '')
		{
			SimulationAlert('验证码不能为空!');
			return false;
		}
		regCheck(username,mobile,repeatPwd,password,community_id,build_id,room,code,name);
	});
        
        $('#reg_button').click(function (){
            location.href = "<?php echo F::getPassportUrl('/site/reg'); ?>";
        });
	
});

$(function(){
   <?php  if(isset($city->id)){?>
	var  currentCityId = <?php echo $city->id;?>;
        GetArea3(currentCityId);
	GetCommunityAll(currentCityId);
   <?php  }else { ?>
       GetArea3("233");
       GetCommunityAll("233");
   <?php }?>
	
});
</script>
    
    <body class="home">
        <div class="homeMain el_container">
        	<!-- 头部 start -->
        	<div class="homeHeadBox">
            	<a class="logoLink" href="#"><img src="<?php echo F::getStaticsUrl('/common/images/pic_blank.gif'); ?>" class="logoImg"></a>
                <form action="/community" method="get"  >
                <div class="searchBox">
                	<span class="searchBox_box">
                		<input class="el_inText" value="输入小区名" name="name" defaultTxt="输入小区名">
                        <i class="icon"></i>
                    </span>
                    <button class="el_btn_search el_btn"  id="search_community" type="submot"  ><span class="txt">找小区</span></button>
                </div>
               </form>
                <div class="phoneBox">
                	<i class="icon"></i>
                    <span class="txt">客服热线:4008 893 893</span>
                </div>
            </div>
            <!-- 头部 end -->
            
            <!-- 中部 start -->
            <table class="homeFrameTab" cellpadding="0" cellspacing="0">
            	<tr>
                    <!-- 框架左边 start -->
                	<td class="homeFrameTab_lp">
                    	<!-- 所在地点 start -->
                    	<div class="homePosBox">
							<label class="fn_left">当前所在地点：</label>
							<div class="CRselectBox fn_left">
								<a class="CRselectValue" href="#"></a>
								<ul class="CRselectBoxOptions" id="cmbProvince3"></ul>
							</div>
							<div class="CRselectBox fn_left">
								<a class="CRselectValue" href="#"></a>
								<ul class="CRselectBoxOptions" id="cmbCity3"></ul>
							</div>
							<div class="fn_clear"></div>
							<!--<select id="cmbProvince3"  onchange="GetCity3(this)"></select>
							<select id="cmbCity3" onChange="showCommunityByCity()"></select>-->
                        </div>
                        <!-- 所在地点 end -->
                        
                        <!-- 分区 start -->
                        <div class="homeSubPosBox">
                        	<!--<a class="el_btn el_btn_current" href="#" val="全部">全部</a>-->
                        </div>
                        <!-- 分区 start -->
                        
                        <!-- 楼盘 start -->
                        <div id="scrollbar1">
                                <div class="scrollbar">
                                   <div class="track">
                                          <div class="thumb">
                                          </div>
                                   </div>
                                </div>
                                <div class="viewport">
                                        <div class="homeDetailPosBox lstBox">
                                                <table class="dataTable_s8" cellpadding="0" cellspacing="0">

                                                        <?php $alpha;$prevAlpha='';$i=0; foreach($communityList as $community){
                                                                $alpha=$community->alpha;?>

                                                        <?php if($alpha!=$prevAlpha){?>
                                                                <tr>
                                                                <th tag="<?php echo $alpha; ?>">
                                                                <?php echo $alpha;?>
																<div class="homeDetailPosBox_th  jiange" name="<?php echo $alpha;?>" >
                                                                 </div>    
                                                                </th>
                                                                </tr>
                                                                <?php $i++; ?>
                                                        <?php } ?>                                    
                                                        <?php $prevAlpha=$community->alpha; ?>
                                                        <?php } ?>                            

                                                </table>
                                        </div>
                                </div>
                        </div>
                        <!-- 楼盘 start -->
                    </td>
                    <!-- 框架左边 start -->
                    
                    <!-- 框架右边 start -->
                    <td class="homeFrameTab_rp">
                    	<!-- 框架右边局域元素（登录） start -->
                    	<div class="homeFrameTab_rp_innerBox" tabid="log">
                            <!-- 标题栏 start -->
                            <p class="homeTitBox">
                                <span class="title">老用户？</span>
                            </p>
                            <!-- 标题栏 end -->
                            
                            <!-- 表单区域 start -->
                            <div class="homeFormBox">
                                <?php $this->renderPartial('_login',array('model'=>$model)); ?>
                            </div>
                            <!-- 表单区域 end -->
                            
                            
                        </div>
                        <!-- 框架右边局域元素（登录）end -->
						
						
                            
                            <!-- 标题栏 start -->
						<div class="homeTitBox_box homeFrameTab_rp_innerBox" tabid="log" style="height:100px;">
                            <p class="homeTitBox" style="margin-top:50px;">
                               <span class="title">您还没注册新用户？</span>
                            </p>
                            <!-- 标题栏 end -->
                            
                            <!-- 表单区域 start -->
                            <div class="homeFormBox">
                                <!--<div class="row">
                                    <label class="el_lab">手机号码：</label>
                                    <input type="text" class="el_inText" id="regNums">
                                </div>-->
                                <div class="btns" style="text-align:center">
                                    <button id="reg_button" class="func_toReg el_btn_gray_zxl el_btn">
                                        <span class="txt">马上注册</span>
                                    </button>
                                </div>
                            </div>
						</div>
                            <!-- 表单区域 end -->
                       
                        <!-- 框架右边局域元素（登录）end -->
                        
                        <!-- 框架右边局域元素（注册） start -->
                    	<div class="homeFrameTab_rp_innerBox fn_none" tabid="reg" style="height:440px;">
                            <!-- 标题栏 start -->
                            <p class="homeTitBox">
                                <span class="title">注册新用户</span>
                            </p>
                            <!-- 标题栏 end -->
                            
                            <!-- 表单区域 start -->
                            <div class="homeFormBox">
                                <?php $this->renderPartial('_reg',array('model'=>$model)); ?>
                            </div>
                            <!-- 表单区域 end -->
                        </div>
                        <!-- 框架右边局域元素（注册） end -->
                        
                        <!-- 手机彩之云 start -->
                        <div class="homePhoneBox">
                        	<div class="titBox">手机彩之云</div>
                            <div class="conBox">
                            	<a href="<?php echo FootItems::getAppLink(); ?>" class="link link_aPhone">
                                	<i class="icon"></i>
                                    <span class="txt">andorid phone</span>
                                </a>
                                <a href="<?php echo FootItems::getAppLink(); ?>" class="link link_iPhone">
                                	<i class="icon"></i>
                                    <span class="txt">iphone</span>
                                </a>
                                <a href="<?php echo FootItems::getAppLink(); ?>" class="link link_pad">
                                	<i class="icon"></i>
                                    <span class="txt">andorid pad</span>
                                </a>
                                <a href="<?php echo FootItems::getAppLink(); ?>" class="link link_pad">
                                	<i class="icon"></i>
                                    <span class="txt">ipad</span>
                                </a>
                            </div>
                        </div>
                        <!-- 手机彩之云 end -->
                        
                        <!-- 公司链接 start -->
                        <div class="homeCompanyBox">
                            <?php $data = FootItems::getHomeItems();
                            for ($i = 0; $i < count($data); $i++) {
                            	if($data[$i]["title"]=='投资者关系'){ ?>
                            		<a href="<?php echo $data[$i]['url']; ?>" class="link" target="_blank"
                                   title="<?php echo $data[$i]['title']; ?>"><?php echo $data[$i]['title']; ?></a>
                                <?php }else{ ?>
                                	<a href="<?php echo F::getHomeUrl($data[$i]['url']); ?>" class="link" target="_blank"
                                   title="<?php echo $data[$i]['title'];?>"><?php echo $data[$i]['title']; ?></a>
                                <?php }  ?>



                                 
                                <?php if ($i < (count($data) - 1)) { ?>
                                    <span class="sep">|</span>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <!-- 公司链接 end -->
                        
                        <!-- 版权 start -->
                        <div class="homeCopyrightBox">
                        	<span class="txt"></span>
                           
                            <span class="txt">CopyRight All 彩之云 2012-2020</span>
                        </div>
                        <!-- 版权 end -->
                    </td>
                    <!-- 框架右边 end -->
                </tr>
            </table>
            <!-- 中部 end -->
        </div>
