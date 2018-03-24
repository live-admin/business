layui.config({
    base : "js/"
}).use(['form','layer','jquery','laypage','laydate'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        laypage = layui.laypage,
        $ = layui.jquery;
    laydate = layui.laydate;


    //获取url
    function url_ID(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        return r?decodeURIComponent(r[2]):null;
    }

    //加载页面数据
    // var url="https://" + document.domain + "/backend";
    var url='';
    var single_url;
    if(document.domain=='localhost'){
        single_url='single-czytest.colourlife.com';
    }else{
        single_url=document.domain;
    }
    var app_key='';
    var access_token='';
    console.log($.cookie('app_key'));
    if($.cookie('app_key')==null&&$.cookie('app_key')==undefined){
        url="https://" + single_url + "/backend";
        app_key=url_ID("app_key");
        access_token=$.cookie('access_token');
    }else{
        url="https://" + single_url + "/application";
        app_key=$.cookie('app_key');
        access_token=$.cookie('app_token');
    }
    var newsData = '';
    var page_size='13';
    var page='1';
    var start_time='';
    var end_time='';
    var localTest = layui.data('test');
    var psges_all='';
    var on=true;
    start();
    function start() {
        $.ajax({
            type:"post",
            url: url+"/account/log?access_token="+access_token,
            data:{
                "app_key":app_key,
                "page_size":page_size,
                "page":page,
                "account":localTest.account,
                "start_time":start_time,
                "end_time":end_time
            },
            dataType:'json',
            success:function(data){
                console.log(data);
                if(data.code==0){
                    newsData =data.content.log;
                    psges_all=data.content.count;
                    if(on){
                        paging();
                        on=false;
                    };
                    newsList(newsData);
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

    //查询
    laydate.render({
        elem: '#times',
        range:true,
        done: function(value, date, endDate){
            console.log(value);
            console.log(date);
            console.log(date.year);
            if(date.year==undefined){
                start_time='';
                end_time='';
            }else{
                start_time=time_stamp(date.year+"-"+date.month+"-"+date.date);
                end_time=time_stamp(endDate.year+"-"+endDate.month+"-"+endDate.date);
            }
            console.log(endDate);

        }
    });
    function time_stamp(times){
        var timestamp2 = Date.parse(new Date(times));
        timestamp2 = timestamp2 / 1000;
        return timestamp2;
    }
    $(".search_btn").click(function(){
        var times=$("#times").val();
        on=true;
        if(times!=""){
            var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                start();
                layer.close(index);
            },2000);
        }else{
            layer.msg("请输入需要查询的内容");
        }
    });
    //分页函数
    function paging(){
        var nums = 10; //每页出现的数据量
        //分页
        laypage({
            cont : "page",
            pages : Math.ceil(psges_all/nums),
            jump : function(obj){
                page=obj.curr;
                start();
                $('.news_list thead input[type="checkbox"]').prop("checked",false);
            }
        })
    }
    //操作
    $("body").on("click",".look_but",function(){  //设备详情查看
        console.log($(this).attr("account"));
        for(var i=0;i<currData.length;i++){
            if(currData[i].account==$(this).attr("account")){
                console.log(currData[i]);
                var lookDataList='';
                lookDataList+='<table class="layui-table">';
                $.each(currData[i].device_info, function(key, val) {
                    lookDataList +='<tr><td>'+key+'</td><td>'+val+'</td></tr>';
                });
                lookDataList+='</table>';
                layer.open({
                    title: '设备详细信息'
                    ,content: lookDataList
                });
                return;
            }
        }

    });
    function newsList(that){
        //渲染数据
        currData=that;
        var dataHtml = '';
        if(currData.length != 0){
            for(var i=0;i<currData.length;i++){
                var online_state='';//在线状态
                var login_type='';//登录方式
                var device_type='';//设备
                if(currData[i].type==1){
                    online_state="登录"
                }else{
                    online_state="退出登录"
                }
                if(currData[i].login_type==1){
                    login_type='静默';
                }else if(currData[i].login_type==2){
                    login_type='密码';
                }else if(currData[i].login_type==3){
                    login_type='手势';
                }else if(currData[i].login_type==4){
                    login_type='微信';
                }else{
                    login_type='QQ';
                }
                if(currData[i].device_type==1){
                    device_type="安卓";
                }else{
                    device_type="IOS";
                }
                dataHtml += '<tr>'
                    // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
                    +'<td>'+currData[i].account+'</td>'
                    +'<td>'+online_state+'</td>'
                    +'<td>'+login_type+'</td>'
                    +'<td>'+currData[i].version+'</td>'
                    +'<td>'+currData[i].login_time+'</td>'
                    +'<td>'+currData[i].device_code+'</td>'
                    +'<td>'+currData[i].device_name+'</td>';
                    if(currData[i].device_info=='null'||currData[i].device_info==undefined){
                        //dataHtml +='<td><table border="1"></table></td>';
                        dataHtml +='<td></td>';
                    }else{
                        //dataHtml +='<td><table border="1">';
                        //$.each(currData[i].device_info, function(key, val) {
                        //    // alert(currData[i].device_info[key]);
                        //    dataHtml +='<tr><td>'+key+'</td><td>'+val+'</td></tr>';
                        //
                        //});
                        //dataHtml +='</table></td>';
                        dataHtml +='<td><button class="layui-btn look_but layui-btn-radius layui-btn-normal" account="'+currData[i].account+'">查看</button></td>';
                        $.each(currData[i].device_info, function(key, val) {
                            // alert(currData[i].device_info[key]);
                            //dataHtml +='<tr><td>'+key+'</td><td>'+val+'</td></tr>';

                        });
                    }
                // if(currData[i].device_name=="iPhone"){
                //     dataHtml +='<td><table border="1">'
                //         +'<tr><td>ipAddress</td><td>'+currData[i].device_info.ipAddress+'</td></tr>'
                //         +'<tr><td>networkType</td><td>'+currData[i].device_info.networkType+'</td></tr>'
                //         +'<tr><td>device</td><td>'+currData[i].device_info.device+'</td></tr>'
                //         +'<tr><td>macAddress</td><td>'+currData[i].device_info.macAddress+'</td></tr>'
                //         +'</table>'
                //         +'</td>';
                // }else{
                //     // dataHtml +='<td>'+currData[i].device_info+'</td>';
                //     dataHtml +='<td><table border="1">'
                //         +'<tr><td>MANUFACTURER</td><td>'+currData[i].device_info.MANUFACTURER+'</td></tr>'
                //         +'<tr><td>OsVersionCode</td><td>'+currData[i].device_info.OsVersionCode+'</td></tr>'
                //         +'<tr><td>OsVersionName</td><td>'+currData[i].device_info.OsVersionName+'</td></tr>'
                //         +'<tr><td>ProvidersName</td><td>'+currData[i].device_info.ProvidersName+'</td></tr>'
                //         +'<tr><td>imeiId</td><td>'+currData[i].device_info.imeiId+'</td></tr>'
                //         +'<tr><td>macAddress</td><td>'+currData[i].device_info.macAddress+'</td></tr>'
                //         +'</table>'
                //         +'</td>';
                // }
                dataHtml +='<td>'+currData[i].ip+'</td>'
                    +'<td>'+device_type+'</td>'
                    +'</tr>';
            }
        }else{
            dataHtml = '<tr><td colspan="10">暂无数据</td></tr>';
        }
        $(".news_content").html(dataHtml);
    }
    // function newsList(that){
    //     //渲染数据
    //     function renderDate(data,curr){
    //         var dataHtml = '';
    //         if(!that){
    //             currData = newsData.concat().splice(curr*nums-nums, nums);
    //         }else{
    //             currData = that.concat().splice(curr*nums-nums, nums);
    //         }
    //         if(currData.length != 0){
    //             for(var i=0;i<currData.length;i++){
    //                 var online_state='';//在线状态
    //                 var login_type='';//登录方式
    //                 var device_type='';//设备
    //                 if(currData[i].type==1){
    //                     online_state="登录"
    //                 }else{
    //                     online_state="退出登录"
    //                 }
    //                 if(currData[i].login_type==1){
    //                     login_type='静默';
    //                 }else if(currData[i].login_type==2){
    //                     login_type='密码';
    //                 }else if(currData[i].login_type==3){
    //                     login_type='手势';
    //                 }else if(currData[i].login_type==4){
    //                     login_type='微信';
    //                 }else{
    //                     login_type='QQ';
    //                 }
    //                 if(currData[i].device_type==1){
    //                     device_type="安卓"
    //                 }else{
    //                     device_type="IOS"
    //                 }
    //
    //                 // var device_info=JSON.stringify(currData[i].device_info);
    //                 dataHtml += '<tr>'
    //                     // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
    //                     +'<td>'+currData[i].account+'</td>'
    //                     +'<td>'+online_state+'</td>'
    //                     +'<td>'+login_type+'</td>'
    //                     +'<td>'+currData[i].version+'</td>'
    //                     +'<td>'+currData[i].login_time+'</td>'
    //                     +'<td>'+currData[i].device_code+'</td>'
    //                     +'<td>'+currData[i].device_name+'</td>'
    //                     +'<td>'+currData[i].device_info+'</td>'
    //                     +'<td>'+currData[i].ip+'</td>'
    //                     +'<td>'+device_type+'</td>'
    //                     +'</tr>';
    //             }
    //         }else{
    //             dataHtml = '<tr><td colspan="10">暂无数据</td></tr>';
    //         }
    //         return dataHtml;
    //     }
    //
    //     //分页
    //     var nums = 13; //每页出现的数据量
    //     if(that){
    //         newsData = that;
    //     }
    //     laypage({
    //         cont : "page",
    //         pages : Math.ceil(newsData.length/nums),
    //         jump : function(obj){
    //             $(".news_content").html(renderDate(newsData,obj.curr));
    //             $('.news_list thead input[type="checkbox"]').prop("checked",false);
    //             form.render();
    //         }
    //     })
    // }
})
