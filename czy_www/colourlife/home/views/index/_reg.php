<script type="text/javascript">
/*
 * 当前页相关信息
 */
//获取当前URL 的主机部分
var url=window.location.host+"/site/";
var pId=""
var cId=""
var aId=""
		
 //获取默认市级单位
function GetDefaultCity(obj){

  $.get("http://"+url+"regions?parent_id="+obj,function(defaultCityData){
	//var defaultAreaId = defaultCityData[0].id;
	//构造对应的省级下面的市级单位 并将第一个设置为选中默认
	for (var i = 0; i < defaultCityData.length; i++) {
		var defaultCityname = defaultCityData[i].name;
		var defaultCityid = defaultCityData[i].id;
		$('#cmbCity').append("<option value=" + defaultCityid + ">" + defaultCityname + "</option>");
	}
	GetDefaultArea(cId);
	$("#cmbCity").val(cId);
   }, 'json')
  
}


//获取默认市级单位下的地区
function GetDefaultArea(obj){

	$.get("http://"+url+"regions?parent_id="+obj,function (defaultAreaData){
		//构造对应地区的 下拉框
		for (var i = 0; i < defaultAreaData.length; i++) {
			var defaultAreaname = defaultAreaData[i].name;
			var defaultAreaid = defaultAreaData[i].id;
			$('#cmbArea').append("<option value=" + defaultAreaid + ">" + defaultAreaname + "</option>");
		}
		$("#cmbArea").val(aId);
		GetCommunity(aId);
	}, 'json')
	//GetCommunity(obj);
}
 
 
 //加载市县
function GetCity(obj) {
	$.get("http://"+url+"regions?parent_id="+$(obj).find("option:selected").val(), function (cityData) {
			var firstCityId=cityData[0].id;
			$('#cmbCity').empty();
			$('#cmbArea').empty();
			for (var i = 0; i < cityData.length; i++) {
				var cityname = cityData[i].name;
				var cityid = cityData[i].id;
				$('#cmbCity').append("<option value=" + cityid + ">" + cityname + "</option>");
			}
			//加载默认地区
			GetDefaultArea(firstCityId); 
	}, 'json');
};
//加载地区
function GetArea(obj) {
	$.get("http://"+url+"regions?parent_id="+$(obj).find("option:selected").val(), function (areaData) {
			$('#cmbArea').empty();
			for (var i = 0; i < areaData.length; i++) {
				var areaname = areaData[i].name;
				var areaid = areaData[i].id;
				$('#cmbArea').append("<option value=" + areaid + ">" + areaname + "</option>");
			}
	}, 'json');
};

//加载小区
function GetCommunity(obj) {
	$.get("http://"+url+"communities?region_id="+obj, function (communitiesData) {
			var newA = "";
			var newABox="";
			$('.dataTable_s8').find(".alphaBox").remove();
			$('.dataTable_s8').find("p").remove();
			//判断小区数据是否为空
			if(communitiesData.length > 0)
			{
				for (var i = 0; i < communitiesData.length; i++) {
					var communitiesAlpha = communitiesData[i].alpha;
					var communitiesName = communitiesData[i].name;
					var communitiesId = communitiesData[i].id;
					if($("div[id="+communitiesAlpha+"]").length > 0) //判断字母Box是否存在
					{
						newA = '<a class="el_btn" val="'+communitiesId+'">'+communitiesName+'</a>';
						$("div[id="+communitiesAlpha+"]").find("h1").after(newA);
					}
					else
					{
						newABox ='<div class="alphaBox" id="'+communitiesAlpha+'"><h1>'+communitiesAlpha+'</h1><a class="el_btn" val="'+communitiesId+'">'+communitiesName+'</a></div>'
						$(".innerBox .dataTable_s8 .fn_clear").before(newABox);
					}
				}
			}
			else
			{
				$('.dataTable_s8').find("p").remove();
				$(".innerBox .dataTable_s8 .fn_clear").before("<p style='padding:20px 0;'>对不起，您所选地区下暂无数据！</p>");
			}
	}, 'json');
};

//加载楼栋
function GetBuilds(obj) {
	$.get("http://"+url+"builds?community_id="+obj, function (buildData) {
		$(".address_buildingBox").empty();
		//判断楼栋数据是否为空
		if(buildData.length > 0)
		{
			for (var i = 0; i < buildData.length; i++) {
				var buildname = buildData[i].name;
				var buildid = buildData[i].id;
				var newBuilds = '<a class="el_btn" val="'+buildid+'">'+buildname+'</a>';
				$(newBuilds).appendTo(".address_buildingBox");
			}
		}
		else
		{
			$("<p style='padding:20px 0;'>对不起，您所选小区下暂无楼栋数据！</p>").appendTo(".address_buildingBox");
		}
	}, 'json');
}



/*
 * 当前页调用
 */

$(function() {
	
	// 选择楼宇弹出框主要模板内容
	var addressTemp2 = '\
		<div class="innerBox" innerid="position">\
			<div class="dataTable_s8">\
				<div class="fn_clear"></div>\
			</div>\
		</div>\
		<div class="innerBox" innerid="building">\
			<div class="address_buildingBox">\
			</div>\
			<div class="btns">\
				<button class="el_btn_back el_btn_gray_xl el_btn"><span class="txt">上一步</span></button>\
			</div>\
		</div>\
		<div class="innerBox" innerid="house">\
			<div class="address_formBox">\
				<label class="el_lab">房间号：</label>\
				<input class="el_inText_house el_inText" type="text">\
			</div>\
			<div class="btns">\
				<button class="el_btn_finish el_btn_gray_xl el_btn"><span class="txt">完成</span></button>\
				<button class="el_btn_back el_btn_gray_xl el_btn"><span class="txt">上一步</span></button>\
			</div>\
		</div>\
	';
	
	
	
	// focus 开始
	$(".func_address").focus(function() {
		
		var pId_0 = $("#province_id").attr("value");
		var cId_0 = $("#city_id").attr("value");
		var aId_0 = $("#area_id").attr("value");
		if(pId_0 != "" && cId_0 != "" && aId_0 != "")
		{
			pId = pId_0;
			cId = cId_0;
			aId = aId_0;
		}
		else
		{
			pId = 19; //广东省
			cId = 233; //深圳市
			aId = 2148; //罗湖区
		}
		
		
		//alert(pId)
		var titleHtml="<select id='cmbProvince'  onchange='GetCity(this)'>";
		/*首先加载所有的省级单位*/
		$.get("http://"+url+"regions?region_id=0", function(provinceData){
			 for(var i=0;i<provinceData.length;i++)
			 {
				var value=provinceData[i].id;
				var text=provinceData[i].name;
				 
				titleHtml+="<option value="+value+">"+text+"</option>";		
			 }
			 
			 titleHtml+="</select><select id='cmbCity'onchange='GetArea(this)'></select><select id='cmbArea'></select><input type='button' value='确定'id='addressOk'/>";
			 
			 //加载默认城市
			 GetDefaultCity(pId);
			 


			 $.cl_tipPop({
			"$this"		: $(this),
			"title"		: '当前所在地点：'+titleHtml,
			"type"		: "default",
			"addClass"	: "addressTipPop",
			"temp"		: addressTemp2,
			"needBg"	: true,
			"onlyOne"	: true,
			"callback"	: function($tipWin, $this) {
				var _tipWin = $tipWin,
					_this = $this,
					_positionInner = _tipWin.find('.innerBox[innerid="position"]'),
					_pos_btn = _positionInner.find(".el_btn"),
					_buindingInner = _tipWin.find('.innerBox[innerid="building"]'),
					_build_btn = _buindingInner.find(".el_btn[val]"),
					_houseInner = _tipWin.find('.innerBox[innerid="house"]'),
					_house_inText = _houseInner.find('.el_inText_house'),
					_house_finishBtn = _houseInner.find(".el_btn_finish"),
					_backBtn = _tipWin.find(".el_btn_back"),
					_get_position = "",
					_get_building = "",
					_get_house = "";
					
				// 返回按钮
				_backBtn.click(function() {
					$(this).parents(".innerBox").hide().prev().show();
				});
				_positionInner.css({"height":"370px","overflow":"auto"});
				_buindingInner.find(".address_buildingBox").css({"height":"245px","overflow":"auto"});
				_houseInner.css({"height":"370px","overflow":"auto"});
				_houseInner.find(".address_formBox").css({"padding":"100px 0"});
				// 楼盘选项
				_pos_btn.die("click").live("click",function() {
					_get_position = $(this).attr("val");
					_get_positionName = $(this).text();
					//点击调用加载
					GetBuilds(_get_position);
					$(this).parents(".innerBox").hide().next().show();
				});
				
				// 楼栋选项
				_build_btn.die("click").live("click",function() {
					_get_building = $(this).attr("val");
					_get_buildingName = $(this).text();
					$(this).parents(".innerBox").hide().next().show();
				});
				
				// 完成按钮
				_house_finishBtn.click(function() {
					_get_house = _house_inText.val();
					
					if (_get_position === "") {
						_get_position = _this.siblings(".func_position").val();
					}
					//打印所选数据
					//alert("你选择的楼盘：" + _get_position + "；楼栋：" + _get_building + "；房号：" + _get_house + "；");
					$("#province_id").attr("value",$("#cmbProvince option:selected").attr("value"));
					$("#city_id").attr("value",$("#cmbCity option:selected").attr("value"));
					$("#area_id").attr("value",$("#cmbArea option:selected").attr("value"));
					$("#CustomerForm_community_id").attr("value",_get_position);
					$("#CustomerForm_build_id").attr("value",_get_building);
					$("#CustomerForm_room").attr("value",_get_house);
					
					$(".func_address").attr("value",_get_positionName+_get_buildingName+_get_house);
					
					_this.val(_get_position + "-" + _get_building + "-" + _get_house);
					
					_tipWin.find(".el_tipPop_h_i .close").click();
				});
				
				
				// 楼盘innerBox出现
				_positionInner.show();
				
			}

		}); 

		$("#cmbProvince").val(pId);

		}, 'json');
		
		//点击确定获取地区id
		
		$("#addressOk").die("click").live("click",function() {
			$(".innerBox").eq(0).siblings().hide();
			$(".innerBox").eq(0).show();
			var getAddressData = $("#cmbArea option:selected").val();
			GetCommunity(getAddressData);
		})
		
		$(this).blur();
	});

});
</script>          
							<div class="homeFormBox">
                                <div class="row">
                                    <label class="el_lab">用 户 名：</label>
                                    <input id="CustomerForm_username"  type="text" class="el_inText">
                                </div>
                                
                                <div class="row">
                                    <label class="el_lab">真实姓名：</label>
                                    <input id="CustomerForm_name"  type="text" class="el_inText">
                                </div>
                                <div class="row">
                                    <label class="el_lab">手机号码：</label>
                                    <input id="CustomerForm_mobile"  type="text" class="el_inText">
                                </div>
                                <div class="row">
                                    <label class="el_lab">密&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
                                    <input id="regPassword" type="password" class="el_inText">
                                </div>
                                <div class="row">
                                    <label class="el_lab">重复密码：</label>
                                    <input id="regRepeatPwd" type="password" class="el_inText">
                                </div>
                                <div class="row">
                                    <label class="el_lab">地&nbsp;&nbsp;&nbsp;&nbsp;址：</label>
                                    <input type="text" class="func_address func_position el_inText" value="请选择地址">
                                    <?php #echo $form-> error($model,'room',array('style'=>'color:red;')); ?>
									<input type="hidden" id="province_id"/>
									<input type="hidden" id="city_id"/>
									<input type="hidden" id="area_id"/>
									<input type="hidden" id="CustomerForm_community_id"/>
									<input type="hidden" id="CustomerForm_build_id"/>
									<input type="hidden" id="CustomerForm_room"/>
                                </div>
                                <div class="row">
                                    <label class="el_lab">验&nbsp;证&nbsp;码：</label>
                                    <input id="regCode" class="el_inText el_inText_ssl">
                                    <input type="button" id="btnSend" class="code_by_el_btn el_btn" value="获取验证码">
                                </div>
                                <div class="btns">
                                	<a class="func_toLog fpwLink" href="#">返回登录</a>
                                    <input type="button" class="el_btn_orange_zxl el_btn" id="userReg" value="确定注册"/>
                                </div>
                            </div>

                            <script>
                                //发送短信验证码
                                function sendMsg(mobile)
                                {
                                    $.ajax({
                                        type: 'get', cache: false, dataType: 'json',
                                        url: '<?php  echo $this->createUrl('site/SendMsg'); ?>',
                                        data:{"mobile":mobile,"type":0},
                                        contentType: "application/json; charset=utf-8",
                                        async: true,
                                        success: function (data) {
                                            if (data!= null && data!= "") {
                                                //处理
                                                if(data['ok']==1)
                                                {
                                                    /*成功后执行*/
                                                    document.getElementById("btnSend").disabled=true;
                                                    $("#btnSend").css({"background":"none","cursor":"default"});
                                                    var SysSecond =60;       //倒计时的起始时间
                                                    //将时间减去1秒，分、秒
                                                    function SetRemainTime() {
                                                        if (SysSecond > 0) {
                                                            SysSecond = SysSecond - 1;
                                                            var second = Math.floor(SysSecond % 60);             // 计算秒
                                                            var minite = Math.floor((SysSecond / 60) % 60);      //计算分

                                                            $("#btnSend").val(minite + "分" + second + "秒").css("color","#ffffff");
                                                        } else {
                                                            //剩余时间小于或等于0的时候，就停止间隔函数
                                                            clearInterval(InterValObj);
                                                            //这里可以添加倒计时时间为0后需要执行的事件
                                                            $("#btnSend").val("获取验证码").css("color","#ffffff");
                                                            document.getElementById("btnSend").disabled=false;
                                                            $("#btnSend").css({"background":"url('http://cc.colourlife.tk/common/images/bg_repeat_x.png') repeat-x scroll 0 -151px transparent","cursor":"pointer"});

                                                        }
                                                    }
                                                    var  InterValObj = setInterval(function(){
                                                        SetRemainTime();
                                                    }, 1000); //间隔函数，1秒执行
                                                    /*成功后执行*/
                                                }
                                                else
                                                {
                                                    alert(data['ok'])
                                                }
                                                return;
                                            } else {
                                                alert('发送失败，请重试！');
                                            }
                                        },
                                        error: function (result) {

                                        }
                                    });
                                }



                                $(function () {
                                        $('#btnSend').click(function () {
                                                var mobile=$('#CustomerForm_mobile').val();
                                                if(mobile=='')
                                                {
                                                    alert('手机号码不能为空!');
                                                    return false;
                                                }
                                                sendMsg(mobile);
                                            }
                                        );
                                    }
                                );

                            </script>