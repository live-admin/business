layui.config({
    base : "js/"
}).use(['form','layer','jquery','laypage',],function() {
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        laypage = layui.laypage,
        $ = layui.jquery;
    var single_url;
    if(document.domain=='localhost'){
        single_url='single-czytest.colourlife.com';
    }else{
        single_url=document.domain;
    }
    form.on('submit(formDemo)', function(data){
        $(".layui-btn").css("display",'none');
        $(".loading").css("display",'block');
        if(data.field.role!=1){
            $.ajax({
                type:"post",
                url: "https://" + single_url + "/employee/login",
                data:{
                    "employee_account":data.field.username,
                    "password":hex_md5(data.field.password)
                },
                dataType:'json',
                success:function(data){
                    console.log(data);
                    if(data.code==0){
                        var access_token=data.content.employee.access_token;
                        var realname=data.content.employee.realname;
                        var avatar=data.content.employee.avatar;
                        cookie(access_token,realname,avatar);
                        // window.navigate("index.html");
                        window.location.href="index.html";
                        $(".layui-btn").css("display",'block');
                        $(".loading").css("display",'none');
                    }else{
                        $(".layui-btn").css("display",'block');
                        $(".loading").css("display",'none');
                        layer.alert(JSON.stringify(data.message), {
                            title: '错误信息'
                        });
                    }
                },
                error:function(data){
                }
            });
        }else{
            $.ajax({
                type:"post",
                url: "https://" + single_url + "/application/login",
                data:{
                    "app_key":data.field.username,
                    "app_secret":data.field.password
                },
                dataType:'json',
                success:function(data){
                    console.log(data);
                    if(data.code==0){
                        var access_token=data.content.application.access_token;
                        var name=data.content.application.name;
                        var app_key=data.content.application.app_key;
                        var is_community=data.content.application.is_community;
                        cookie_app(access_token,name,app_key,is_community);
                        window.location.href="application.html";
                        $(".layui-btn").css("display",'block');
                        $(".loading").css("display",'none');
                    }else{
                        $(".layui-btn").css("display",'block');
                        $(".loading").css("display",'none');
                        layer.alert(JSON.stringify(data.message), {
                            title: '错误信息'
                        });
                    }
                },
                error:function(data){
                }
            });
        }
        return false;
    });
    function cookie(access_token,realname,avatar){
        var cookietime = new Date();
        var timestamp=new Date().getTime();
        cookietime.setTime(timestamp + (60 * 60 * 1000*2));//coockie保存二个小时
        $.cookie("avatar", avatar,{expires:cookietime,path:'/'});
        $.cookie("realname",realname,{expires:cookietime,path:'/'});
        $.cookie("access_token",access_token,{expires:cookietime,path:'/'});
    }
    function cookie_app(access_token,name,app_key,is_community) {
        var cookietime = new Date();
        var timestamp=new Date().getTime();
        cookietime.setTime(timestamp + (60 * 60 * 1000*2));//coockie保存二个小时
        $.cookie("app_key",app_key,{expires:cookietime,path:'/'});
        $.cookie("app_name",name,{expires:cookietime,path:'/'});
        $.cookie("app_token",access_token,{expires:cookietime,path:'/'});
        $.cookie("is_community",is_community,{expires:cookietime,path:'/'});
    }

});