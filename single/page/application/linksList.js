layui.config({
    base : "js/"
}).use(['form','layer','jquery','laypage','laydate'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        laypage= layui.laypage,
        $ = layui.jquery;
        laydate = layui.laydate;


    //获取url
    function url_ID(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        return r?decodeURIComponent(r[2]):null;
    }

    //加载页面数据
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
    var start_time='';
    var end_time='';
    var page='1';
    var page_size='10';
    var account='';
    var device_code='';
    var type='';
    var version='';
    var device_name='';
    var ip='';
    var community_uuid='';
    var community_name='';
    var login_type='';
    var param='';
    var psges_all='';
    var on=true;
    start(app_key,start_time,end_time,page,page_size,account,device_code,type,version,device_name,ip,login_type,community_uuid,community_name,param);
    function start(app_key,start_time,end_time,page,page_size,account,device_code,type,version,device_name,ip,login_type,community_uuid,community_name,param) {
        $.ajax({
            type:"post",
            url: url+"/date/account/list?access_token="+access_token,
            data:{
                "app_key":app_key,
                "start_time":start_time,
                "end_time":end_time,
                "page":page,
                "page_size":page_size,
                "account":account,
                "device_code":device_code,
                "type":type,
                "version":version,
                "device_name":device_name,
                "ip":ip,
                "login_type":login_type,
                "community_uuid":community_uuid,
                "community_name":community_name,
                "param":param
            },
            dataType:'json',
            success:function(data){
                console.log(data);
                if(data.code==0){
                    newsData =data.content.account;
                    psges_all=data.content.count;
                    if(on){
                      paging();
                      on=false;
                    }
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
    $(".search_btn").click(function(){
        type=$("#state").val();//类型
        account=$("#account").val();//账号
        version=$("#version").val();//版本号
        ip=$("#ip").val();//ip地址
        community_name=$("#community_name").val();//小区UUID
        device_name=$("#device_name").val();//设备名称
        device_code=$("#device_code").val();//设备唯一ID
        login_type=$("#login_type").val();//登录方式
        var times=$("#times").val();
        on=true;
        if(type!=''||account!=''||version!=''||ip!=''||community_name!=''||device_name!=""||device_code!=""||login_type!=""||times!=""){
            var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                start(app_key,start_time,end_time,page,page_size,account,device_code,type,version,device_name,ip,login_type,community_uuid,community_name,param);
                layer.close(index);
            },2000);
        }else{
            layer.msg("请输入需要查询的内容");
        }
    });
    function time_stamp(times){
        var timestamp2 = Date.parse(new Date(times));
        timestamp2 = timestamp2 / 1000;
        return timestamp2;
    }
    //数据导出
    //导出
    $(".export").click(function(){
        location.href=url+"/excel/date/account/list?access_token="+access_token+"&app_key="+app_key+"&type="+type+"&account="+account+"&device_code="+device_code+"&version="+version+"&device_name="+device_name+"&start_time="+start_time+"&end_time="+end_time+"&ip="+ip+"&login_type="+login_type+"&community_uuid="+community_uuid+"@community_name="+community_name+"&param="+param;
    });
    //分页函数
    function paging(){
        var nums = 10; //每页出现的数据量
        // if(that){
        //     newsData = that;
        // }
        //分页
        laypage({
            cont : "page",
            pages : Math.ceil(psges_all/nums),
            jump : function(obj){
                console.log(obj);
                page=obj.curr;
                start(app_key,start_time,end_time,page,page_size,account,device_code,type,version,device_name,ip,login_type,community_uuid,community_name,param);
                // $(".news_content").html(renderDate(newsData,obj.curr));
                $('.news_list thead input[type="checkbox"]').prop("checked",false);
                // form.render();
            }
        })
    }
    function newsList(that){
        //渲染数据
        currData=that;
        // function renderDate(data,curr){
            var dataHtml = '';
            // if(!that){
            //     currData = newsData.concat().splice(curr*nums-nums, nums);
            // }else{
            //     currData = that.concat().splice(curr*nums-nums, nums);
            // }
            if(currData.length != 0){
                for(var i=0;i<currData.length;i++){
                    var login_type='';
                    var type='';
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
                    if(currData[i].type==1){
                        type="登录";
                    }else {
                        type="退出";
                    }
                    dataHtml += '<tr>'
                        // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
                        +'<td>'+currData[i].account+'</td>'
                        +'<td>'+currData[i].login_time+'</td>'
                        +'<td>'+currData[i].device_code+'</td>'
                        +'<td>'+type+'</td>'
                        +'<td>'+currData[i].version+'</td>'
                        +'<td>'+currData[i].device_name+'</td>'
                        +'<td>'+currData[i].ip+'</td>'
                        +'<td>'+login_type+'</td>'
                        +'<td>'+currData[i].community_name+'</td>';
                    if(currData[i].device_info=='null'||currData[i].device_info==undefined){
                        dataHtml +='<td><table border="1"></table></td>';
                    }else{
                        dataHtml +='<td><table border="1">';
                        $.each(currData[i].device_info, function(key, val) {
                            // alert(currData[i].device_info[key]);
                            dataHtml +='<tr><td>'+key+'</td><td>'+val+'</td></tr>';

                        });
                        dataHtml +='</table></td>';
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
                        // +'<td>'+currData[i].device_info+'</td>'
                    dataHtml +='</tr>';
                }
            }else{
                dataHtml = '<tr><td colspan="10">暂无数据</td></tr>';
            }
            $(".news_content").html(dataHtml);
        // }

        //分页
        // var nums = 13; //每页出现的数据量
        // if(that){
        //     newsData = that;
        // }
        // //分页
        // laypage({
        //     cont : "page",
        //     pages : Math.ceil(psges_all/nums),
        //     layout: ['count', 'prev', 'page', 'next', 'limit', 'skip'],
        //     jump : function(obj){
        //         console.log(obj);
        //         $(".news_content").html(renderDate(newsData,obj.curr));
        //         $('.news_list thead input[type="checkbox"]').prop("checked",false);
        //         form.render();
        //     }
        // })
    }
    //时间戳转换为时间
    function getLocalTime(nS) {
        return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
    }
});
