var $,tab,skyconsWeather;
layui.config({
    base : "js/"
}).use(['bodyTab','form','element','layer','jquery'],function(){
    var form = layui.form(),
        layer = layui.layer,
        element = layui.element();
    $ = layui.jquery;
    tab = layui.bodyTab();
    var navs = [{
        "title" : "应用管理",
        "icon" : "icon-text",
        "href" : "page/news/newsList.html",
        "spread" : false
    }];
    var single_url;
    if(document.domain=='localhost'){
        single_url='single-czytest.colourlife.com';
    }else{
        single_url=document.domain;
    }
    var url="https://" +single_url+ "/";
    start();
    function start() {
        $.ajax({
            type:"post",
            url: url+"backend/application/get/list?access_token="+$.cookie('access_token'),
            data:{
                "name":"",
                "mobile":"",
                "page":"",
                "page_size":10
            },
            dataType:'json',
            success:function(data){
                console.log(data);
                if(data.code==0){
                    newsData =data.content.app_list;
                    for(var i=0;i<newsData.length;i++){
                    	var array={
                            "title" :newsData[i].name,
                            "icon" : "&#xe61c;",
                            "href" : "",
                            "spread" : false,
                            "children" : [
                                {
                                    "title" : "账号管理",
                                    "icon" : "&#xe612;",
                                    "href" : "page/application/newsList.html",
                                    "spread" : false
                                },{
                                    "title" : "人数统计",
                                    "icon" : "&#xe613;",
                                    "href" : "page/application/main.html",
                                    "spread" : false
                                },{
                                    "title" : "日志查询",
                                    "icon" : "&#xe637;",
                                    "href" : "page/application/linksList.html",
                                    "spread" : false
                                },{
                                    "title" : "数据展示",
                                    "icon" : "&#xe62c;",
                                    "href" : "page/application/linksAdd.html",
                                    "spread" : false
                                }
                            ]
                        }
                        navs.push(array);
					}
                }else{
                    layer.alert(JSON.stringify(data.message), {
                        title: '错误信息'
                    });
                }
            },
            error:function(data){
            }
        });
    }
    return navs;


});
