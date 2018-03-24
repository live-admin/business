$(function(){
    // alert($.cookie('czy_username'));
    // alert($.cookie('czy_userid'));
    if($.cookie('czy_username')!=""&&$.cookie('czy_username')!==null){
        $("#userInfo").css("display",'block');
         $("#orderInfo").css("display",'block');
        $(".twoczy_username").html($.cookie('czy_username'));

    }else{
        // window.location.href="../index.html"
    }
    //退出
    $(".Sign_out").click(function(){
        $("#userInfo").css("display",'none');
         $("#orderInfo").css("display",'none');
        var cookietime = new Date();
        var timestamp=new Date().getTime();
        cookietime.setTime(timestamp + (60 * 60 * 1000));//coockie保存一小时
        $.cookie("czy_username",'',{expires:cookietime,path:'/'});
        console.log($.cookie('czy_username'));
        window.location.href="../index.html"
    });
   // $(".search").click(function(){
   //     $(".nav ul:nth-child(1)").css("display",'none');
   //     $("#sou_banner").css("display",'block');
   // });
   //  $("#sou_banner>li:nth-child(3)").click(function(){
   //      $(".nav ul:nth-child(1)").css("display",'block');
   //      $("#sou_banner").css("display",'none');
   //  });
    // 搜索
    //$(".sou_banner li:nth-child(3)").click(function(){
    //    $(".sou_banner").css("display","none");
    //    $(".navigation").css("display","block");
    //    $(".header_list").css('display','none')
    //});
    //$(".search").click(function(){
    //    $(".sou_banner").css("display","block");
    //    $(".navigation").css("display","none");
    //    $(".header_list").css('display','none')
    //});
    //$(".search_two").click(function(){
    //    $(".sou_banner").css("display","block");
    //    $(".navigation").css("display","none");
    //    $(".header_list").css('display','none')
    //});
    //$(".colourCommunity").click(function(){
    //    $(".sou_banner").css("display","none");
    //    $(".navigation").css("display","none");
    //    $(".header_list").css('display','block')
    //});
    $(".sou_banner li:nth-child(1)").click(function(){
        $(".header_list").slideUp("slow");
        $(".sou_banner").slideUp("slow");
        $(".navigation").slideDown("slow");
    });
    $(".search").click(function(){
        //$(".header_list").slideUp("slow");
        $(".navigation").slideUp("slow");
        $(".sou_banner").slideDown("slow");
    });
    $(".search_two").click(function(){
        //$(".header_list").slideUp("slow");
        $(".navigation").slideUp("slow");
        $(".sou_banner").slideDown("slow");
    });
    //$(".colourCommunity").click(function(){
    //    $(".sou_banner").slideUp("slow");
    //    $(".navigation").slideUp("slow");
    //    $(".header_list").slideDown("slow");
    //});
    ////彩之云app提示
    //$(".close_czyAPP").click(function(){
    //    $(".czy_app").css("display","none")
    //});
    //$(".app_czydown").click(function(){
    //    $(".czy_app").css("display","block")
    //});
    //联系客服
    $(".close_Customerservice").click(function(){
        $(".czy_Customerservice").css("display","none");
    });
    $(".app_Customer").click(function(){
        $(".czy_Customerservice").css("display","block");
    });
//    了解更多
//    $(".Learn_more").click(function(){
//        $(".czy_app").css("display","block")
//    });
//    $(".View_details").click(function(){
//        $(".czy_app").css("display","block")
//    });
    //关闭彩之云弹窗
    //$(".come_onczy button").click(function(){
    //    $(".czy_app").css("display","none")
    //});
    //$(".sample li").click(function(){
    //    $(".czy_app").css("display","block");
    //});
    //$(".left_arrow").click(function(){
    //    $(".czy_app").css("display","block");
    //});
    //$(".right_arrow").click(function(){
    //    $(".czy_app").css("display","block");
    //})
});