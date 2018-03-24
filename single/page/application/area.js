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
    var page_size='15';
    var page='1';
    var start_time='';
    var end_time='';
    var type='';
    var community_name='';
    var area_name='';
    var region_name='';
    var branch_name='';
    var psges_all='';
    var on=true;
    start(app_key,page_size,page,start_time,end_time,type,community_name,area_name,region_name,branch_name);
    function start(app_key,page_size,page,start_time,end_time,type,community_name,area_name,region_name,branch_name) {
        $.ajax({
            type:"post",
            url: url+"/community/count?access_token="+access_token,
            data:{
                "app_key":app_key,
                "page_size":page_size,
                "page":page,
                "start_time":start_time,
                "end_time":end_time,
                "type":type,
                "community_name":community_name,
                "area_name":area_name,
                "region_name":region_name,
                "branch_name":branch_name
            },
            dataType:'json',
            success:function(data){
                console.log(data);
                if(data.code==0){
                    newsData =data.content.list;
                    psges_all=data.content.list_count;
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
    //搜索
    $(".search_btn").click(function(){
        area_name=$("#area_name").val();//片区名称
        region_name=$("#region_name").val();//事业部名称
        community_name=$("#community_name").val();//小区名称
        branch_name=$("#branch_name").val();//大区名称
        type=$("#type").val();
        var times=$("#times").val();
        on=true;
        if(area_name!=''||region_name!=''||community_name!=''||branch_name!=''||type!=''||times!=""){
            var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                start(app_key,page_size,page,start_time,end_time,type,community_name,area_name,region_name,branch_name);
                layer.close(index);
            },2000);
        }else{
            layer.msg("请输入需要查询的内容");
        }
    });
    //导出
    $(".export").click(function(){
        // window.location=url+"backend/excel/community/count?access_token="+$.cookie('access_token')+"&app_key="+app_key+"&start_time="+start_time+"&end_time="+end_time+"&type="+type+"&community_name="+community_name+"&area_name="+area_name+"&region_name="+region_name+"&branch_name="+branch_name;
        location.href=url+"/excel/community/count?access_token="+$.cookie('access_token')+"&app_key="+app_key+"&start_time="+start_time+"&end_time="+end_time+"&type="+type+"&community_name="+community_name+"&area_name="+area_name+"&region_name="+region_name+"&branch_name="+branch_name;
    });
    //分页函数
    function paging(){
        var nums = 15; //每页出现的数据量
        //分页
        laypage({
            cont : "page",
            pages : Math.ceil(psges_all/nums),
            jump : function(obj){
                page=obj.curr;
                start(app_key,page_size,page,start_time,end_time,type,community_name,area_name,region_name,branch_name);
                $('.news_list thead input[type="checkbox"]').prop("checked",false);
            }
        })
    }
    //渲染函数
    function newsList(that){
        //渲染数据
        currData=that;
        var dataHtml = '';
        if(currData.length != 0){
            for(var i=0;i<currData.length;i++){
                var types="";
                if(currData[i].type==1){
                    types="登录";
                }else{
                    types="注册";
                }
                dataHtml += '<tr>'
                    // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
                    +'<td>'+currData[i].id+'</td>'
                    +'<td>'+currData[i].date+'</td>'
                    // +'<td>'+currData[i].community_uuid+'</td>'
                    +'<td>'+currData[i].community_name+'</td>'
                    // +'<td>'+currData[i].area_uuid+'</td>'
                    +'<td>'+currData[i].area_name+'</td>'
                    // +'<td>'+currData[i].branch_uuid+'</td>'
                    +'<td>'+currData[i].region_name+'</td>'
                    // +'<td>'+currData[i].region_uuid+'</td>'
                    +'<td>'+currData[i].branch_name+'</td>'
                    +'<td>'+types+'</td>'
                    +'<td>'+currData[i].count+'</td>'
                    +'</tr>';
            }
        }else{
            dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
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
    //                 var types="";
    //                 if(currData[i].type==1){
    //                     types="登录";
    //                 }else{
    //                     types="注册";
    //                 }
    //                 dataHtml += '<tr>'
    //                     // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
    //                     +'<td>'+currData[i].id+'</td>'
    //                     +'<td>'+currData[i].date+'</td>'
    //                     +'<td>'+currData[i].community_uuid+'</td>'
    //                     +'<td>'+currData[i].community_name+'</td>'
    //                     +'<td>'+currData[i].area_uuid+'</td>'
    //                     +'<td>'+currData[i].area_name+'</td>'
    //                     +'<td>'+currData[i].branch_uuid+'</td>'
    //                     +'<td>'+currData[i].branch_name+'</td>'
    //                     +'<td>'+currData[i].region_uuid+'</td>'
    //                     +'<td>'+currData[i].region_name+'</td>'
    //                     +'<td>'+types+'</td>'
    //                     +'<td>'+currData[i].count+'</td>'
    //                     +'</tr>';
    //             }
    //         }else{
    //             dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
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
