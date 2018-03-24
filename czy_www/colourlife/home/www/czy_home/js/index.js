$(function(){
    //鼠标
    var min_entry=0;
    //var error_num=4;
    var Verification_Code=0;
    //手机号码匹配
    var match = /^1[34578]\d{9}$/;
    //服务协议默认勾选为1，不勾选为0
    var flag = 1;
    //oauth2.0接口地址
      //测试环境
     var oauth_url="http://icetest.colourlife.net:8081/v1";
    //var oauth_url="http://iceapi.colourlife.com:8081/v1";
    var ts=Math.round(new Date().getTime()/1000).toString();
    //环境测试
    // var icetoken="3RdasXGBrjx4xJv7O6k3";
    // var appID="ICETEST0-C631-4BA2-B262-E7C17B743701";
    //正式环境
    var icetoken='r9A0ZSn5b4jOSJEnGc3y';
    var appID='ICECZY00-F26F-42B8-988C-27F4AEE3292A';
    var icesign=hex_md5(appID+ts+icetoken+"false");
    $('body').on('mousewheel', function(event) {
        //console.log(event.deltaX, event.deltaY, event.deltaFactor);
        if(min_entry==0){
            up_down()
        }
    });
    // using the event helper
    $('body').mousewheel(function(event) {
        //console.log(event.deltaX, event.deltaY, event.deltaFactor);
        if(min_entry==0){
            up_down()
        }
    });

    //var url="http://cmobile-czytest.colourlife.com";
    var url="http://cmobile.colourlife.com";
    //刚刚加载
    console.log($.cookie('czy_username'));
    if($.cookie('czy_username')!=""&&$.cookie('czy_username')!==null){
        $("#userInfo").css("display",'block');
        $("#orderInfo").css("display",'block');
        $(".twoczy_username").html($.cookie('czy_username'));
        min_entry=1;
        $(".upper_home").css("display","none");
        $(".register").css("display",'none');
    }
    function up_down(){
        var aa=$('.swiper-wrapper').css('transform').replace(/[^0-9\-,]/g,'').split(',');
        var calss=$(".swiper-wrapper>div:nth-child(1)").attr("class").split(' ');
        for(var i=0;i<calss.length;i++){
            if(calss[i]=="swiper-slide-visible"||calss[i]=="swiper-slide-active"){
                $(".upper_home").css("display","none")
                $(".arrow").css("display","block")
            }
        }
        //var calss2=$(".swiper-wrapper>div:nth-child(2)").attr("class").split(' ');
        //for(var i=0;i<calss2.length;i++){
        //    if(calss2[i]=="swiper-slide-visible"||calss2[i]=="swiper-slide-active"){
        //        $(".upper_home").css("display","block")
        //        $(".arrow").css("display","block")
        //    }
        //}
        // var calss3=$(".swiper-wrapper>div:nth-child(2)").attr("class").split(' ');
        // for(var i=0;i<calss3.length;i++){
        //     if(calss3[i]=="swiper-slide-visible"||calss3[i]=="swiper-slide-active"){
        //         $(".upper_home").css("display","block");
        //         $(".arrow").css("display","none")
        //     }
        // }
    }
    //滑动验证
    var slider = new SliderUnlock("#slider",{
        successLabelTip : "验证成功,欢迎访问彩之云官网!"
    },function(){
         Verification_Code=1;
    });
    slider.init();
    //登录
    $("#entry_czyend").click(function(){
       var phone=$("#dl_czyphone").val();
       var passwords=$("#passwords").val();
       if(phone==""){
           $(".entry_czyhome").css("display","none");
           $(".ljd_alert").html('手机号码不能为空');
           $(".number_error").css("display","block");
        return;
       }else if(passwords==''){
           $(".entry_czyhome").css("display","none");
           $(".ljd_alert").html('请输入您的彩之云密码');
           $(".number_error").css("display","block");
        return;
       }else if (Verification_Code!=1) {
           $(".entry_czyhome").css("display","none");
           $(".ljd_alert").html("请手动拖动滑块验证");
           $(".number_error").css("display","block");
            return;
       }else{
           //if(error_num>0){
           //     Login(phone,passwords);
           $("#entry_czyend p").css("display","none");
           $("#loading").css("display","block");
            Login_oauth(phone,passwords);
           //}else{
           //    alert("请重新刷新登录！")
           //    slider.init();
           //}
       }
    });
    //Oauth2.0登录
    function Login_oauth(phone,passwords){
        console.log(phone+passwords);
        $.ajax({
            type: "post",
            url:oauth_url+"/oauth2/oauth/token",
            dataType: "json",
            data: {
                "grant_type":"password",
                "client_id":"2",
                "client_secret":"oy4x7fSh5RI4BNc78UoV4fN08eO5C4pj0daM0B8M",
                "username":phone,
                "password":passwords,
                "type":"1",
                "scope":"",
                "ts":ts,
                "sign":icesign,
                "appID":appID
            },
            success: function(data){
                console.log(data);
                if(data.token_type==undefined){
                    slider.init();
                    $("#labelTip").html("拖动滑块验证");
                    $("#label").css("left","0");
                    $("#labelTip").css("color",'#787878');
                    $("#slider_bg").css("width",'0px');
                    Verification_Code=0;
                    $(".ljd_alert").html("账号或密码错误！");
                    //error_num--;
                    $(".entry_czyhome").css("display","none");
                    $(".number_error").css("display","block");
                    //$(".error_num").html(error_num);
                    // $("#dl_czyphone").val("");
                    // $("#passwords").val("");
                    $("#password").css("display","block");
                    $("#passwords").css("display","none");
                    $("#entry_czyend p").css("display","block");
                    $("#loading").css("display","none");
                }else if(data.token_type=="Bearer"){
                    user_information(data.access_token);
                }
            },
            error: function(data){
                console.log(data);
            }

        });
    }
    //获取用户信息
    console.log(Math.round(new Date().getTime()/1000).toString());
    function user_information(access_token) {
        $.ajax({
            type: "get",
            url:oauth_url+"/oauth2/oauth/customerInfo",
            dataType: "json",
            data: {
                "ts":ts,
                "sign":icesign,
                "appID":appID
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization","Bearer "+access_token);
            },
            success: function(data){
                console.log(data);
                if(data.code==0){
                    let username=data.content.nickname;
                    let mobile=data.content.mobile;
                    let customer_id=data.content.customer_id;
                    let community_name=data.content.community_name;
                    $(".mask").css("display","none");
                    $(".mask ul:nth-child(1)").css("display","block");
                    $(".mask ul:nth-child(2)").css("display","none");
                    cookie(username,customer_id,community_name,access_token);
                    min_entry=1;
                    $(".upper_home").css("display","none");
                    $(".register").css("display",'none');
                    $("#entry_czyend p").css("display","block");
                    $("#loading").css("display","none");
                }else{
                    slider.init();
                    $("#entry_czyend p").css("display","block");
                    $("#loading").css("display","none");
                    $("#labelTip").html("拖动滑块验证");
                    $("#label").css("left","0");
                    $("#labelTip").css("color",'#787878');
                    $("#slider_bg").css("width",'0px');
                    Verification_Code=0;
                    $(".ljd_alert").html("账号或密码错误！");
                    //error_num--;
                    $(".entry_czyhome").css("display","none");
                    $(".number_error").css("display","block");
                    //$(".error_num").html(error_num);
                    // $("#dl_czyphone").val("");
                    // $("#passwords").val("");
                    $("#password").css("display","block");
                    $("#passwords").css("display","none");
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    }
    function Login(phone,passwords){
        var times=Math.round(new Date().getTime()/1000);
        console.log(times);
        //与服务端对时
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: ""+url+"/1.0/ts",
            data: {"ts":times},
            error: function (data) {
                console.log(data);
            },
            success: function (data) {
                console.log(data);
                var diff=data.diff;
                //获取认证标识和秘钥
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: ""+url+"/1.0/auth",
                    error: function (data) {
                        console.log(data);
                    },
                    success: function (data) {
                        console.log(data);
                        var times_two=Math.round(new Date().getTime()/1000);
                        var ts_two=times_two+diff;
                        var key=data.key;
                        var secret=data.secret;
                        var username=phone;
                        var password=passwords;
                        var sign=hex_md5("/1.0/customer/companyLogin?key="+key+"&ts="+ts_two+"&ve=1.0.0&secret="+secret+"");
                        $.ajax({
                            type: "POST",
                            dataType: "JSON",
                            url:""+url+"/1.0/customer/companyLogin?key="+key+"&ts="+ts_two+"&ve=1.0.0&sign="+sign+"",
                            data: {
                                "username":username,
                                "reg_type":"",
                                "device_info":"",
                                "password":password
                            },
                             error: function (data) {
                                 console.log(data.responseText);
                                 var obj = JSON.parse(data.responseText);
                                 console.log(obj);
                                 if(obj.code==400){
                                     slider.init();
                                     $("#labelTip").html("拖动滑块验证");
                                     $("#label").css("left","0");
                                     $("#labelTip").css("color",'#787878');
                                     $("#slider_bg").css("width",'0px');
                                     Verification_Code=0;
                                     $(".ljd_alert").html(obj.message);
                                     //error_num--;
                                     $(".entry_czyhome").css("display","none");
                                     $(".number_error").css("display","block");
                                     //$(".error_num").html(error_num);
                                     // $("#dl_czyphone").val("");
                                     // $("#passwords").val("");
                                     $("#password").css("display","block");
                                     $("#passwords").css("display","none");
                                 }
                             },
                            success: function (data) {
                                console.log(data);
                                if(data.ok==1){
                                    $(".mask").css("display","none")
                                    $(".mask ul:nth-child(1)").css("display","block");
                                    $(".mask ul:nth-child(2)").css("display","none");
                                    cookie(data.name,data.id,key,secret);
                                    //cookie(data.id);
                                    min_entry=1;
                                    $(".upper_home").css("display","none");
                                    $(".register").css("display",'none');
                                }
                            }
                        });
                    }
                });
            }
        });
    }
    //用户登录成功
    function cookie(username,id,key,access_token){
        $("#userInfo").css("display",'block');
        $("#orderInfo").css("display",'block');
        $(".twoczy_username").html(username);
        var cookietime = new Date();
        var timestamp=new Date().getTime();
        cookietime.setTime(timestamp + (60 * 60 * 1000*2));//coockie保存二个小时
        $.cookie("czy_username", username,{expires:cookietime,path:'/'});
        $.cookie("czy_userid", id,{expires:cookietime,path:'/'});
        $.cookie("czy_userkey",key,{expires:cookietime,path:'/'});
        $.cookie("czy_usersecret",access_token,{expires:cookietime,path:'/'});
        //传给第三方网站
        var frm=document.createElement("iframe");
        frm.id="Ateon-SetParent-iframe";
        frm.style.display="none";
        frm.src="http://www-czytest.colourlife.com?access_token="+access_token;
        document.body.appendChild(frm);
        console.log($.cookie('czy_username'));
        console.log($.cookie('czy_userid'));
        console.log($.cookie('czy_userkey'));
        console.log($.cookie('czy_usersecret'));
        // console.log($.cookie('czy_userts'));
        // console.log($.cookie('czy_usersign'));
        //console.log(username,id,key,ts,sign);
        //if($.cookie('czy_username')!=""&&$.cookie('czy_username')!==null){
        //    $(".Customers>li:nth-child(2)").css("display",'block');
        //    $(".twoczy_username").html($.cookie('czy_username'));
        //}
    }
    //退出
    $(".Sign_out").click(function(){
        $("#userInfo").css("display",'none');
        $("#orderInfo").css("display",'none');
        $.cookie("czy_username",null,{path:'/'});
        $.cookie("czy_userid",null,{path:'/'});
        $.cookie("czy_userkey",null,{path:'/'});
        $.cookie("czy_usersecret",null,{path:'/'});
        //$.cookie("czy_username",null);
        alert("退出成功");
        window.location.href="index.html"
    })

    //省市区三级联动
    // $.each(provinceJson, function(k, p) {
    //     var option = "<option value='" + p.id + "'>" + p.province + "</option>";
    //     $("#selProvince").append(option);
    // });
    // $("#selProvince").change(function() {
    //     var selValue = $(this).val();
    //     $("#selCity option:gt(0)").remove();
    //     $.each(cityJson, function(k, p) {
    //         // 直辖市处理.|| p.parent == selValue，直辖市为当前自己
    //         if (p.id == selValue || p.parent == selValue) {
    //             var option = "<option value='" + p.id + "'>" + p.city + "</option>";
    //             $("#selCity").append(option);
    //         }
    //     });
    // });
    // $("#selCity").change(function() {
    //     var selValue = $(this).val();
    //     $("#selDistrict option:gt(0)").remove();
    //     $.each(countyJson, function(k, p) {
    //         if (p.parent == selValue) {
    //             var option = "<option value='" + p.id + "'>" + p.county + "</option>";
    //             $("#selDistrict").append(option);
    //         }
    //     });
    // });


    //新密码
  //  var tx = document.getElementById("new"), pwd = document.getElementById("news");
  //  tx.onfocus = function(){
  //      if(this.value != "新密码") return;
  //      this.style.display = "none";
  //      pwd.style.display = "";
  //      pwd.value = "";
  //      pwd.focus();
  //  }
  //  pwd.onblur = function(){
  //      if(this.value != "") return;
  //      this.style.display = "none";
  //      tx.style.display = "";
  //      tx.value = "新密码";
  //  }
  ////再次输入密码
  //  var txs = document.getElementById("nextnew"), pwds = document.getElementById("nextnews");
  //  txs.onfocus = function(){
  //      if(this.value != "请再次输入一次新密码") return;
  //      this.style.display = "none";
  //      pwds.style.display = "";
  //      pwds.value = "";
  //      pwds.focus();
  //  }
  //  pwds.onblur = function(){
  //      if(this.value != "") return;
  //      this.style.display = "none";
  //      txs.style.display = "";
  //      txs.value = "请再次输入一次新密码";
  //  }
    //输入密码
    var password = document.getElementById("password"), passwords = document.getElementById("passwords");
    password.onfocus = function(){
        if(this.placeholder != "密码") return;
        this.style.display = "none";
        passwords.style.display = "";
        passwords.value = "";
        passwords.focus();
    }
    passwords.onblur = function(){
        if(this.value != "") return;
        this.style.display = "none";
        password.style.display = "";
        password.placeholder = "密码";
    }
    //关闭遮罩
    $(".entry_close").click(function(){
        $(".mask").css("display","none")
         $(".mask ul:nth-child(1)").css("display","block");
        $(".mask ul:nth-child(2)").css("display","none");
    })
    $(".back_close").click(function(){
        $(".mask").css("display","none")
        $(".mask ul:nth-child(1)").css("display","block");
        $(".mask ul:nth-child(2)").css("display","none");
    })
    //点击登录注册
    $(".czy_entry").click(function(){
        $(".mask").css("display","block")
    });
    $(".home_page").click(function(){
        $(".czy_register").css("display","block")
    });
    $(".close_czyregister").click(function(){
        $(".czy_register").css("display","none")
    });
    $(".czy_twoentry").click(function(){
        $(".mask").css("display","block")
    });
    $(".home_twopage").click(function(){
        $(".czy_register").css("display","block")
    });
   //彩之云APP
   // $(".close_czyAPP").click(function(){
   //     $(".czy_app").css("display","none")
   // });
   // $(".Customers>li:nth-child(2)").click(function(){
   //     $(".czy_app").css("display","block")
   // });
    //联系客服
    $(".close_Customerservice").click(function(){
        $(".czy_Customerservice").css("display","none")
    });
    $(".Customers>li:last-child").click(function(){
        $(".czy_Customerservice").css("display","block")
    });
    //关闭注册成功返回页面
    $(".close_successful").click(function(){
        $(".czy_successful").css("display","none")
         $(".mask").css("display","none");
    });
    //用户注册
       //手机号码获取焦点
    $("#two_password").focus(function(){
         $(".zc_none").css("display",'none');
         $(".zctwo_none").css("display",'none');
    });
    $("#one_password").focus(function(){
         $(".zc_none").css("display",'none');
         $(".zctwo_none").css("display",'none');
    });
    $("#zcczy_yzm").focus(function(){
         $(".zc_code").css("display",'none');
    });
    //手机号码失去焦点
    // $("#one_password").blur(function(){
    //       with(document.all){
    //         if(two_password.value==""){
    //             return;
    //         }else if(one_password.value!=two_password.value){
    //              $(".zctwo_none").css("display",'block');
    //             $(".zctwo_none").html("两次密码不一致，请重新输入！")
    //             one_password.value = "";
    //             two_password.value = "";
    //             return;
    //             // if(one_password.value!=two_password.value){
    //             // // alert("两次密码不一致，请重新输入！")
    //             // $(".zctwo_none").css("display",'block');
    //             // $(".zctwo_none").html("两次密码不一致，请重新输入！")
    //             // one_password.value = "";
    //             // two_password.value = "";
    //             // return;
    //             // }else if(one_password.value==""){
    //             //     $(".zc_none").css("display",'block');
    //             //     $(".zc_none").html("密码不能为空！")
    //             //     // alert("密码不能为空！");
    //             //     return;
    //             // }else if(two_password.value==""){
    //             //     // alert("密码不能为空！");
    //             //     $(".zctwo_none").css("display",'block');
    //             //     $(".zctwo_none").html("密码不能为空！")
    //             //     return;
    //             // }

    //         }
    //         else alert("提交");
    //         }  
    // })

    // 手机号码失去焦点（登录）
    $("#dl_czyphone").blur(function(){
      var val = $(this).val();
      if(!match.test(this.value) && val != ''){
           $(".entry_czyhome").css("display","none");
           $(".ljd_alert").html('手机号码格式不正确');
           $(".number_error").css("display","block");
      }else if(match.test(this.value)){
          $(".number_error").css("display","none");
          $(".entry_czyhome").css("display","block");
      }else if(val == ''){
          $(".number_error").css("display","block");
          $(".entry_czyhome").css("display","none");
          $(".ljd_alert").html('手机号码不能为空');
      }
    });
    //手机号码获取焦点（登录）
    $("#dl_czyphone").focus(function(){
       $(this).val("");
    });


    //手机校验（注册）
    $("#zcczy_phone").blur(function(){
        var val = $("#zcczy_phone").val();
        if(!match.test(val) && val != ''){
            $(".zc_phone").css("display",'block');
            $(".zc_phone").html("手机号码格式不正确");
        }else if(match.test(this.value)){
            $(".zc_phone").css("display","none");
        }else if(val == ''){
            $(".zc_phone").css("display", "block");
            $(".zc_phone").html("手机号码不能为空");
        }
    });

    //获取验证码接口（注册）
    $(".btn_Code").click(function(){
          sms();
    });
       var wait=60;
      //获取短信的按钮提示
      function time(o,p) {//o为按钮的对象，p为可选，这里是60秒过后，提示文字的改变
            //时间 
            if (wait == 0) { 
              o.removeAttr("disabled"); 
              o.val("点击重新发送验证码");//改变按钮中value的值 
              //p.css("display","block");
              //p.html("如果您在1分钟内没有收到验证码，请检查您填写的手机号码是否正确或重新发送");
              wait = 60; 
            } else { 
              o.attr("disabled", true);//倒计时过程中禁止点击按钮 
              o.val(wait + "s");//改变按钮中value的值 
              wait--; 
              setTimeout(function() { 
              time(o,p);//循环调用 
              }, 1000) 
            } 
          }
    //验证码校验（注册）
    $("#zcczy_yzm").blur(function(){
        var code_len = $(this).val().length;
         if(this.value == ""){
            $(".zc_code").css("display","block");
            $(".zc_code").text("验证码不能为空");
         }else if(code_len != 4){
             $(".zc_code").css("display","block");
             $(".zc_code").text("验证码格式不正确");
         }
    });

    //第一次输入密码（注册）
    // $("#zcone_password").focus(function(){
    //   $(this).css("display","none");
    //   $("#one_password").css("display","block");
    // });
    // $("#one_password").focus(function(){
    //   alert(1);
    //   if($(this).value==""){
    //     $(this).css("display","none");
    //     $("#zcone_password").css("display","block");
    //   }
    // });

    //  $("#zcone_password").blur(function(){
    //   $(this).css("display","block");
    //   $("#one_password").css("display","none");
    // });

    //第二次输入密码（注册）
    // $("#zctwo_password").focus(function(){
    //   $(this).css("display","none");
    //   $("#two_password").css("display","block");
    // });

    //  $("#zctwo_password").blur(function(){
    //   $(this).css("display","block");
    //   $("#two_password").css("display","none");
    // });
    // 注册输入密码
    var zcone_password = document.getElementById("zcone_password"), one_password = document.getElementById("one_password");
    zcone_password.onfocus = function(){
        if(this.placeholder != "密码位8-30位字母加数字") return;
        this.style.display = "none";
        one_password.style.display = "";
        one_password.value = "";
        one_password.focus();
    };
    one_password.onblur = function(){
        if(this.value != "") return;
        this.style.display = "none";
        zcone_password.style.display = "";
      
    };
    var zctwo_password = document.getElementById("zctwo_password"), two_password = document.getElementById("two_password");
    zctwo_password.onfocus = function(){
        if(this.placeholder != "密码位8-30位字母加数字") return;
        this.style.display = "none";
        two_password.style.display = "";
        two_password.value = "";
        two_password.focus();
    };
    two_password.onblur = function(){
        if(this.value != "") return;
        this.style.display = "none";
        zctwo_password.style.display = "";
        zctwo_password.placeholder = "密码位8-30位字母加数字";
    };

    //注册输入密码zcone_password
    var zc_num01=1;
    var zc_num02=1;
    $("#one_password").blur(function(){
        var pattern = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,30}$/;
        if(pattern.test(one_password.value)){
            zc_num01=0;
        }else{
            $(".zc_none").css("display",'block');
            $(".zc_none").html("密码位8-30位字母加数字")
        }
    });
    $("#two_password").blur(function(){
        $(".error_call").css("display","none");
        var pattern = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,30}$/;
        if(pattern.test(one_password.value)){
            zc_num02=0;
            check();
        }else{
            $(".zctwo_none").css("display",'block');
            $(".zctwo_none").html("密码位8-30位字母加数字");
        }

    });
    function check(){
        //with(document.all){
        if(one_password.value!=two_password.value){
            // alert("两次密码不一致，请重新输入！")
            $(".zctwo_none").css("display",'block');
            $(".zctwo_none").html("两次密码不一致，请重新输入！");
            // one_password.value = "";
            // two_password.value = "";
            return;
        }else if(one_password.value==""){
            $(".zc_none").css("display",'block');
            $(".zc_none").html("密码不能为空！");
            // alert("密码不能为空！");
            return;
        }else if(two_password.value==""){
            // alert("密码不能为空！");
            $(".zctwo_none").css("display",'block');
            $(".zctwo_none").html("密码不能为空！");
            return;
        }
       
    }
    //用户注册
    var diff,key,secret;
    //与服务端对时
    function sms(){
        var times= Math.round(new Date().getTime()/1000);
        console.log(times);
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: ""+url+"/1.0/ts",
            data: {"ts":times},
            error: function (data) {
                var obj = JSON.parse(data.responseText);
                console.log(obj);
                if(obj.code==400){
                    $(".error_call").css("display","block");
                    $(".error_calls").html(obj.message)
                }
            },
            success: function (data) {
                console.log(data);
                diff=data.diff;
                two_sms(times,diff);
            }
        });
    }
    //获取认证标识和秘钥
    function two_sms(){
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: ""+url+"/1.0/auth",
            error: function (data) {
                var obj = JSON.parse(data.responseText);
                console.log(obj);
                if(obj.code==400){
                    $(".error_call").css("display","block");
                    $(".error_calls").html(obj.message)
                }
            },
            success: function (data) {
                console.log(data);
                key=data.key;
                secret=data.secret;
                three_sms(key,secret)
            }
        });
    }
    //发送短信获取验证码
    function three_sms(key,secret){
        var ts=Math.round(new Date().getTime()/1000)+diff;
        var czy_phone=$("#zcczy_phone").val();
        var sign=hex_md5("/1.0/sms?key="+key+"&ts="+ts+"&ve=1.0.0&secret="+secret+"");
        //短信验证码
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url:""+url+"/1.0/sms?key="+key+"&ts="+ts+"&ve=1.0.0&sign="+sign+"",
            data: {
                "mobile":czy_phone,
                "type": 0
            },
            error: function (data) {
                console.log(data);
                var obj = JSON.parse(data.responseText);
                console.log(obj);
                if(obj.code==400){
                    $(".zc_phone").css("display",'block');
                    $(".zc_phone").html(obj.message);
                }
            },
            success: function (data) {
                console.log(data.ok);
                if(data.ok==1){
                    $(".zc_code").css("display","block");
                    $(".zc_code").html("发送成功");
                    time($(".btn_Code"));
                }
            }
        });
    }
    //获取短信token
    function sms_token(czy_phone,czy_yzm,two_czypassword){
        var tss=Math.round(new Date().getTime()/1000)+diff;
        var sign_token=hex_md5("/1.0/sms?key="+key+"&ts="+tss+"&ve=1.0.0&secret="+secret+"");
        $.ajax({
            type: "PUT",
            dataType:"JSON",
            url:""+url+"/1.0/sms?key="+key+"&ts="+tss+"&ve=1.0.0&sign="+sign_token+"",
            data: {
                "mobile":czy_phone,
                "code":czy_yzm
            },
            error: function (data) {
                var obj = JSON.parse(data.responseText);
                if(obj.code==400){
                    $(".error_call").css("display","block");
                    $(".error_calls").html(obj.message);
                    $("#loading_register").css("display",'none');
                    $(".btn .completed").css("display",'block');
                }
            },
            success: function (data_token) {
                console.log(data_token.ok);
                if(data_token.ok==1){
                    var token=data_token.token;
                    user_goin(token,czy_phone,two_czypassword)
                }
            }
        })
    };

    //彩之云服务协议
    $(".label").click(function(){
      if(flag == 1){
        $(this).removeClass("active");
        flag = 0;
      }else{
        $(this).addClass("active");
        flag = 1;
      }
     
    })

    //用户注册
    function user_goin(token,czy_phone,two_czypassword){
        var ts_zc=Math.round(new Date().getTime()/1000)+diff;
        var sign_usergoin=hex_md5("/1.0/customer/company?key="+key+"&ts="+ts_zc+"&ve=1.0.0&secret="+secret+"");
                    $.ajax({
                        type: "post",
                        dataType: "JSON",
                        url: "" + url + "/1.0/customer/company?key=" + key + "&ts=" +ts_zc + "&ve=1.0.0&sign=" +sign_usergoin+ "",
                        data: {
                            "mobile":czy_phone,
                            "reg_type": 3,
                            "channel": "Colourlife",
                            "token":token,
                            "device_info": '',
                            "name": '',
                            "nickname": '',
                            "password": two_czypassword,
                            "invite_code": '',
                            "gender": 0
                        },
                        error: function (data) {
                            console.log(data);
                            var obj = JSON.parse(data.responseText);
                            console.log(obj);
                            if(obj.code==400){
                                $(".error_call").css("display","block");
                                $(".error_calls").html(obj.message);
                                $("#loading_register").css("display",'none');
                                $(".btn .completed").css("display",'block');
                            }
                        },
                        success: function (data) {
                            console.log(data);
                            if(data.ok==1){
                                //alert("注册成功");
                                $(".czy_register").css("display","none");
                                //$(".czy_successful").css("display","block");
                                //$(".czy_username").html(data.mobile);
                                cookie(data.username);
                                min_entry=1;
                                $(".upper_home").css("display","none");
                                $(".register").css("display",'none');
                                $("#loading_register").css("display",'none');
                                $(".btn .completed").css("display",'block');
                            }
                        }
                    });
    };
    $(".btn").click(function(){
        enroll()
    });
    function enroll(){
        var czy_phone=$("#zcczy_phone").val();
        var czy_yzm=$("#zcczy_yzm").val();
        var one_czypassword=$("#one_password").val();
        var two_czypassword=$("#two_password").val();
        console.log(one_czypassword+"第二"+two_czypassword);
        if(czy_phone=="" || czy_phone=="请输入您的手机号码"){
          $(".zc_phone").css("display","block")
          $(".zc_phone").html("请输入手机号码")
        }else if(czy_yzm=="" || czy_yzm=="验证码是4位数"){
          $(".zc_code").css("display","block")
          $(".zc_code").html("请输入验证码")
        }else if(one_czypassword==""){
           $(".zc_none").css("display",'block');
           $(".zc_none").html("密码不能为空！")
        }else if(two_czypassword==""){
          $(".zctwo_none").css("display",'block');
          $(".zctwo_none").html("密码不能为空！")
        }else if(one_czypassword!=two_czypassword){
          $(".zctwo_none").css("display",'block');
          $(".zctwo_none").html("两次密码不一致，请重新输入！")
        }else if(flag == 1){
            var pattern = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,30}$/;
            if(pattern.test(one_czypassword)){
                if(pattern.test(two_czypassword)){
                    $("#loading_register").css("display",'block');
                    $(".btn .completed").css("display",'none');
                    sms_token(czy_phone,czy_yzm,two_czypassword);
                }else{
                    $(".zctwo_none").css("display",'block');
                    $(".zctwo_none").html("密码位8-30位字母加数字")
                }
            }else{
                $(".zc_none").css("display",'block');
                $(".zc_none").html("密码位8-30位字母加数字")
            }
        }else if(flag == 0){
            $(".error_call").css("display","block");
            $(".error_calls").html("请先阅读协议");
        }
    }
    //注册成功后
    $(".come_onczy").click(function(){
      $(".czy_successful").css("display",'none');
    });
    // 忘记密码
    //$(".forgot_password").click(function(){
    //    $(".mask ul:nth-child(1)").css("display","none");
    //    $(".mask ul:nth-child(2)").css("display","block");
    //})
    // 快速注册
    $(".czy_mfzc").click(function(){
        $(".mask").css("display","none");
        $(".czy_register").css("display","block");
    });
    // 搜索
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
    //选项卡
    //var $tab_li = $('.navigation  li');
    //console.log($tab_li);
    //$tab_li.hover(function(){
    //    $(this).attr('id','selected').siblings().removeAttr("id");
    //});
    ////////////////////////用户登录成功
    
    // $.cookie('the_cookie', 'the_value'); // 存储 cookie 
    // $.cookie('czy_username', '李建东'); // 存储一个带7天期限的 cookie 

    // console.log($.cookie('czy_username')); // 读取 cookie 
    // $.cookie('the_cookie', '', { expires: -1 }); // 删除 cookie

    //var cookietime = new Date();
    //var timestamp=new Date().getTime();
    //cookietime.setTime(timestamp + (60 * 60 * 1000));//coockie保存一小时
    //if($.cookie('czy_username')!=""&&$.cookie('czy_username')!==null){
    //    $(".Customers>li:nth-child(3)").css("display",'block');
    //    $(".twoczy_username").html($.cookie('czy_username'));
    //}
    ////退出
    //$(".Sign_out").click(function(){
    //    $(".Customers>li:nth-child(3)").css("display",'none');
    //    $.cookie("czy_username",null);
    //    alert("退出成功");
    //    window.location.href="index.html"
    //})
    ///////

      // 找回密码
    // $("#zhmi_czy").blur(function(){
    //      if(this.value==""){
    //         this.placeholder='请输入您的手机号码'
    //      }
    //   return; 
    // });

    // var dt = new Date();   
    // dt.setSeconds(dt.getSeconds() + 60);   
    // document.cookie = "cookietest=1; expires=" + dt.toGMTString();   
    // var cookiesEnabled = document.cookie.indexOf("cookietest=") != -1;   
    // if(!cookiesEnabled) {   
    //     //没有启用cookie   
    //     alert("没有启用cookie ");  
    // } else{  
    //     //已经启用cookie   
    //     alert("已经启用cookie ");  
    // }  
});