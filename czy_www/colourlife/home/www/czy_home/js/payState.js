// var urlice="http://icetest.colourlife.net:8081/v1/czyprovide";
var urlice="http://iceapi.colourlife.com:8081/v1/czyprovide";
var ts=Math.round(new Date().getTime()/1000).toString();
// var icetoken="3RdasXGBrjx4xJv7O6k3";
var icetoken="r9A0ZSn5b4jOSJEnGc3y";
// var iceappID="ICETEST0-C631-4BA2-B262-E7C17B743701";
var iceappID="ICECZY00-F26F-42B8-988C-27F4AEE3292A";
var icesign=hex_md5(iceappID+ts+icetoken+"false");
var menubar=new Vue({
    el: '.details',
    data:{
        Whether:'true',
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
        //购电吗
        recharge_code:""
    },
    created:function(){
        this.Detail();
    },
    methods: {
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
                    "order_id":this.url("out_trade_no")
                },
                dataType:'json',
                success:function(result){
                    console.log(result);
                    if(result.code ==0){
                        menubar.Single_typelist=result.content.order;
                        var status=result.content.order.status;
                        if(status=="99"||status=="1"){
                            menubar.Whether=true;
                        }else{
                            menubar.Whether=false;
                        }
                        menubar.Relevant_informationlist=result.content.relate_info;
                        menubar.Relevant_informationlist.create_time=menubar.times(result.content.relate_info.create_time);
                        menubar.Relevant_informationlist.pay_time=menubar.times(result.content.relate_info.pay_time);
                        console.log(menubar.Whether);
                        // this.Buy_code();
                    }else{
                        // alert(result.message);
                        menubar.orderlist=[];
                        menubar.error_information=result.message;
                    }
                    console.log(menubar.Single_typelist);
                },
                error:function(result){
                }
            });
        },
        Buy_code:function(){
            var params="/powerFees/orderDetails?ts="+ts+"&sign="+icesign+"&appID="+iceappID;
            $.ajax({
                type:"GET",
                url: urlice+params,
                data:{
                    "order_id":this.url("out_trade_no")
                },
                dataType:'json',
                success:function(result){
                    if(result.code ==0){

                    }else{
                        // alert(result.message);
                        menubar.orderlist=[];
                        menubar.error_information=result.message;
                    }
                    console.log(menubar.Single_typelist);
                },
                error:function(result){
                }
            });
        },
        Goclose:function () {//关闭
            var params="/powerFees/cancelOrder?ts="+ts+"&sign="+icesign+"&appID="+iceappID;
            $.ajax({
                type:"GET",
                url: urlice+params,
                data:{
                    "order_id":menubar.url("listid")
                },
                dataType:'json',
                success:function(result){
                    console.log(result);
                    if(result.code ==0){
                        window.location.href="orderState.html?listid="+menubar.url("listid");
                    }else{
                        menubar.orderlist=[];
                        menubar.error_information=result.message;
                    }
                },
                error:function(result){
                }
            });
        },
        Gopayment:function () {//详情
            window.location.href="payment.html?listid="+menubar.url("listid");
        }
    }
})