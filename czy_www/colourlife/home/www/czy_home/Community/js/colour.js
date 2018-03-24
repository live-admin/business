$(function(){
    if($.cookie('czy_username')!=""&&$.cookie('czy_username')!==null){
        $("#userInfo").css("display",'block');
        $("#orderInfo").css("display",'block');
        $(".twoczy_username").html($.cookie('czy_username'));

    }else{
        // window.location.href="../index.html"
    }
    //搜索
    $(".sou_banner li:nth-child(1)").click(function(){
        $(".sou_banner").slideUp("slow");
        $(".header_list").slideDown("slow");
    });
    $(".search").click(function(){
        $(".header_list").slideUp("slow");
        $(".sou_banner").slideDown("slow");
    });
    //退出
    $(".Sign_out").click(function(){
        $("#userInfo").css("display",'none');
        $("#orderInfo").css("display",'none');
        var cookietime = new Date();
        var timestamp=new Date().getTime();
        cookietime.setTime(timestamp + (60 * 60 * 1000));//coockie保存一小时
        $.cookie("czy_username",'',{expires:cookietime,path:'/'});
        console.log($.cookie('czy_username'));
        window.location.href="./../../index.html"
    });
    //联系客服
    $(".close_Customerservice").click(function(){
        $(".czy_Customerservice").css("display","none");
    });
    $(".app_Customer").click(function(){
        $(".czy_Customerservice").css("display","block");
    });
});