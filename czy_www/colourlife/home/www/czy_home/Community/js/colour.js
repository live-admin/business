$(function(){
    if($.cookie('czy_username')!=""&&$.cookie('czy_username')!==null){
        $("#userInfo").css("display",'block');
        $("#orderInfo").css("display",'block');
        $(".twoczy_username").html($.cookie('czy_username'));

    }else{
        // window.location.href="../index.html"
    }
    //����
    $(".sou_banner li:nth-child(1)").click(function(){
        $(".sou_banner").slideUp("slow");
        $(".header_list").slideDown("slow");
    });
    $(".search").click(function(){
        $(".header_list").slideUp("slow");
        $(".sou_banner").slideDown("slow");
    });
    //�˳�
    $(".Sign_out").click(function(){
        $("#userInfo").css("display",'none');
        $("#orderInfo").css("display",'none');
        var cookietime = new Date();
        var timestamp=new Date().getTime();
        cookietime.setTime(timestamp + (60 * 60 * 1000));//coockie����һСʱ
        $.cookie("czy_username",'',{expires:cookietime,path:'/'});
        console.log($.cookie('czy_username'));
        window.location.href="./../../index.html"
    });
    //��ϵ�ͷ�
    $(".close_Customerservice").click(function(){
        $(".czy_Customerservice").css("display","none");
    });
    $(".app_Customer").click(function(){
        $(".czy_Customerservice").css("display","block");
    });
});