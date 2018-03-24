var $,tab,skyconsWeather;
layui.config({
	base : "js/application/"
}).use(['bodyTab','form','element','layer','jquery'],function(){
	var form = layui.form(),
		layer = layui.layer,
		element = layui.element();
		$ = layui.jquery;
		tab = layui.bodyTab();

	$(document).on('keydown', function() {
		if(event.keyCode == 13) {
			$("#unlock").click();
		}
	});
    //获取cookie
    Begin_loading();
    function Begin_loading(){
        console.log($.cookie('app_token'));
        console.log($.cookie('app_name'));
        if($.cookie('app_token')==""||$.cookie('app_token')==null){
             window.location.href="login.html"
        }else{
            $(".userName").html($.cookie('app_name'));
            //$(".layui-circle").attr("src",$.cookie('avatar'))
        }

    }
    //token失效处理
    //点击退出
    $(".Sign_out").click(function(){
        $.cookie("app_token",null,{path:'/'});
        $.cookie("app_key",null,{path:'/'});
        $.cookie("app_name",null,{path:'/'});
        layer.alert(JSON.stringify("退出成功"), {
            title: '信息提示'
        });
        window.location.href="login.html"
    });
	// 添加新窗口
	$(".layui-nav .layui-nav-item a").on("click",function(){
		addTab($(this));
		$(this).parent("li").siblings().removeClass("layui-nav-itemed");
	});
	//刷新后还原打开的窗口
	if(window.sessionStorage.getItem("menu") != null){
		menu = JSON.parse(window.sessionStorage.getItem("menu"));
		curmenu = window.sessionStorage.getItem("curmenu");
		var openTitle = '';
		for(var i=0;i<menu.length;i++){
			openTitle = '';
			if(menu[i].icon.split("-")[0] == 'icon'){
				openTitle += '<i class="iconfont '+menu[i].icon+'"></i>';
			}else{
				openTitle += '<i class="layui-icon">'+menu[i].icon+'</i>';
			}
			openTitle += '<cite>'+menu[i].title+'</cite>';
			openTitle += '<i class="layui-icon layui-unselect layui-tab-close" data-id="'+menu[i].layId+'">&#x1006;</i>';
			element.tabAdd("bodyTab",{
				title : openTitle,
		        content :"<iframe src='"+menu[i].href+"' data-id='"+menu[i].layId+"'></frame>",
		        id : menu[i].layId
			})
			//定位到刷新前的窗口
			if(curmenu != "undefined"){
				if(curmenu == '' || curmenu == "null"){  //定位到后台首页
					element.tabChange("bodyTab",'');
				}else if(JSON.parse(curmenu).title == menu[i].title){  //定位到刷新前的页面
					element.tabChange("bodyTab",menu[i].layId);
				}
			}else{
				element.tabChange("bodyTab",menu[menu.length-1].layId);
			}
		}
	}

})
//打开新窗口
function addTab(_this){
	tab.tabAdd(_this);
}