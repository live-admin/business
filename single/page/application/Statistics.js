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
    // laydate.render({
    //     elem: '#test',
    //     range: true,
    //     done: function(value, date){
    //         layer.alert('你选择的日期是：' + value + '<br>获得的对象是' + JSON.stringify(date));
    //     }
    // });
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
    var register_type='';
    var psges_all='';
    var on=true;
    start(app_key,page_size,page,start_time,end_time,register_type);
    function start(app_key,page_size,page,start_time,end_time,register_type) {
        $.ajax({
            type:"post",
            url: url+"/register/count?access_token="+access_token,
            data:{
                "app_key":app_key,
                "page_size":page_size,
                "page":page,
                "start_time":start_time,
                "end_time":end_time,
                "register_type":register_type
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
    $(".search_btn").click(function(){
        register_type=$("#register_type").val();
        var times=$("#times").val();
        on=true;
        if(register_type!=''||times!=""){
            var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                start(app_key,page_size,page,start_time,end_time,register_type);
                layer.close(index);
            },2000);
        }else{
            layer.msg("请输入需要查询的内容");
        }
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
                start(app_key,page_size,page,start_time,end_time,register_type);
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
                dataHtml += '<tr>'
                    // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
                    +'<td>'+(i+1)+'</td>'
                    +'<td>'+currData[i].register_date+'</td>'
                    +'<td>'+currData[i].number+'</td>'
                    +'</tr>';
            }
        }else{
            dataHtml = '<tr><td colspan="3">暂无数据</td></tr>';
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
    //                 dataHtml += '<tr>'
    //                     // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
    //                     +'<td>'+(i+1)+'</td>'
    //                     +'<td>'+currData[i].register_date+'</td>'
    //                     +'<td>'+currData[i].number+'</td>'
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
