// var urlice="http://icetest.colourlife.net:8081/v1/czyprovide";
var urlice="http://iceapi.colourlife.com:8081/v1/czyprovide";
var ts=Math.round(new Date().getTime()/1000).toString();
// var icetoken="3RdasXGBrjx4xJv7O6k3";
var icetoken="r9A0ZSn5b4jOSJEnGc3y";
// var iceappID="ICETEST0-C631-4BA2-B262-E7C17B743701";
var iceappID="ICECZY00-F26F-42B8-988C-27F4AEE3292A";
var icesign=hex_md5(iceappID+ts+icetoken+"false");
var menubar = new Vue({
    el:".payment",
    data:{
        //订单详情
        Whether:'',//是否成功true是否false是是
        Single_typelist:{
            category:'',
            type:'',
            sn:'',
            meter:'',
            bank_pay:'',
            discounts:'',
            red_packet_pay:'',
            amount:''
        },//订单详情
        Relevant_informationlist:{
            meter:'',
            create_time:'',
            nickname:'',
            meter_address:''
        },//相关信息
        id:'',
        status:''

    },
    created:function(){
        this.Detail();
    },
    methods:{
        url:function(name){
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            return r?decodeURIComponent(r[2]):null;
        },
        times:function(nS){
            return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');
        },
        Detail:function() {
            var params="/powerFees/orderDetails?ts="+ts+"&sign="+icesign+"&appID="+iceappID;
            $.ajax({
                type:"GET",
                url: urlice+params,
                data:{
                    "order_id":this.url("listid")
                },
                dataType:'json',
                success:function(result){
                    console.log(result);
                    if(result.code ==0){
                        menubar.Single_typelist=result.content.order;
                        if(result.content.order.status="99"){
                            menubar.Whether=true;
                        }else{
                            menubar.Whether=false;
                        }
                        menubar.Relevant_informationlist=result.content.relate_info;
                        menubar.Relevant_informationlist.create_time=menubar.times(result.content.relate_info.create_time);
                        console.log(menubar);
                    }else{
                        // alert(result.message);
                        menubar.orderlist=[];
                        menubar.error_information=result.message;
                    }
                },
                error:function(result){
                }
            });
        },
        Alipay:function(){
            var params="/powerFees/OtherPay?ts="+ts+"&sign="+icesign+"&appID="+iceappID;
            $.ajax({
                type:"POST",
                url: urlice+params,
                data:{
                    "customer_id":$.cookie('czy_userid'),
                    "sn":menubar.Single_typelist.sn
                },
                dataType:'json',
                success:function(result){
                    console.log(result);
                    if(result.code==0){
                        var alipay_list=result.content.payinfo;
                        menubar.alipay_html(alipay_list.WIDout_trade_no,alipay_list.WIDsubject,alipay_list.WIDtotal_amount,alipay_list.WIDbody,alipay_list.sign);
                    }
                },
                error:function(result){
                }
            });
        },
        alipay_html:function(WIDout_trade_no,WIDsubject,WIDtotal_amount,WIDbody,sign){
            // var action = "http://capi-czytest.colourlife.com/PHPpay/pagepay/pagepay.php";
            var action = "http://capi.colourlife.com/PHPpay/pagepay/pagepay.php";
            var form = $('<form></form>');
            form.attr('action', action);
            form.attr('method', 'post');
            // _self -> 当前页面 _blank -> 新页面
            form.attr('target', '_self');
            var input1 = $('<input type="text" name="WIDout_trade_no" />');
            var input2 = $('<input type="text" name="WIDsubject" />');
            var input3 = $('<input type="text" name="WIDtotal_amount" />');
            var input4 = $('<input type="text" name="WIDbody" />');
            var input5 = $('<input type="text" name="sign" />');
            input1.attr('value', WIDout_trade_no);
            input2.attr('value', WIDsubject);
            input3.attr('value', WIDtotal_amount);
            input4.attr('value', WIDbody);
            input5.attr('value', sign);
            // 附加到Form
            form.append(input1);
            form.append(input2);
            form.append(input3);
            form.append(input4);
            form.append(input5);
            form.appendTo("body");
            form.css('display','none');
            form.submit();
        }
    }
});
