/**
 * Created by Administrator on 2017/8/22 0022.
 */
$(function(){
    var url="http://iceapi.colourlife.com:8081/v1/oauth2/oauth/token";
    // var url="http://icetest.colourlife.net:8081/v1/oauth2/oauth/token";
    var llurl="http://icetest.colourlife.net:8081/v1/oauth2/oauth/customerInfo";
    console.log(Math.round(new Date().getTime()/1000).toString());
    // var icesign=hex_md5("ICETEST0-C631-4BA2-B262-E7C17B743701"+Math.round(new Date().getTime()/1000).toString()+"3RdasXGBrjx4xJv7O6k3"+"false");
    var icesign=hex_md5("ICECZY00-F26F-42B8-988C-27F4AEE3292A"+Math.round(new Date().getTime()/1000).toString()+"r9A0ZSn5b4jOSJEnGc3y"+"false");
    console.log(icesign);
    $.ajax({
        type: "post",
        url:url,
        dataType: "json",
        data: {
            "grant_type":"password",
            "client_id":"2",
            "client_secret":"oy4x7fSh5RI4BNc78UoV4fN08eO5C4pj0daM0B8M",
            "username":"17603095322",
            "password":"li123456",
            "type":"1",
            "scope":"",
            "ts":Math.round(new Date().getTime()/1000).toString(),
            "sign":icesign,
            "appID":"ICETEST0-C631-4BA2-B262-E7C17B743701"
        },
        success: function(data){
            console.log(data);
            aa(data.access_token);
            },
        error: function(data){
            console.log(data);
            }

    });
    function aa(token){
        $.ajax({
            type: "get",
            url:llurl,
            dataType: "json",
            data: {
                "ts":"1503390313",
                "sign":"d6c8f3a694c4a4798b9b862cfe82de7c",
                "appID":"ICETEST0-C631-4BA2-B262-E7C17B743701"
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization","Bearer "+token);
            },
            success: function(data){
                console.log(data);
            },
            error: function(data){
                console.log(data);
            }

        });
    }
});