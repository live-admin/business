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
    var page_size='10';
    var page='1';
    var state='';
    var account='';
    var device_code='';
    var version='';
    var device_name='';
    var create_stime='';
    var create_etime='';
    var update_stime='';
    var update_etime='';
    var ip='';
    var login_type='';
    var community_uuid='';
    var community_name='';
    var param='';
    var psges_all='';
    var on=true;
    start(app_key,page_size,page,state,account,device_code,version,device_name,create_stime,create_etime,update_stime,update_etime,ip,login_type,community_uuid,community_name,param);
    function start(app_key,page_size,page,state,account,device_code,version,device_name,create_stime,create_etime,update_stime,update_etime,ip,login_type,community_uuid,community_name,param) {
        $.ajax({
            type:"post",
            url: url+"/online/account?access_token="+access_token,
            data:{
                "app_key":app_key,
                "page_size":page_size,
                "page":page,
                "state":state,
                "account":account,
                "device_code":device_code,
                "version":version,
                "device_name":device_name,
                "create_stime":create_stime,
                "create_etime":create_etime,
                "update_stime":update_stime,
                "update_etime":update_etime,
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
                    newsData =data.content.account_list;
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
            update_etime=time_stamp(date.year+"-"+date.month+"-"+date.date)
            if(date.year==undefined){
                update_stime='';
                update_etime='';
            }else{
                update_stime=time_stamp(date.year+"-"+date.month+"-"+date.date);
                update_etime=time_stamp(endDate.year+"-"+endDate.month+"-"+endDate.date);
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
        state=$("#state").val();
        account=$("#account").val();
        version=$("#version").val();
        ip=$("#ip").val();
        community_name=$("#community_name").val();
        device_name=$("#device_name").val();
        device_code=$("#device_code").val();
        login_type=$("#login_type").val();
        var times=$("#times").val();
        on=true;
        if(state!=''||account!=''||version!=''||ip!=''||community_name!=''||device_name!=""||device_code!=""||login_type!=""||times!=""){
            var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                start(app_key,page_size,page,state,account,device_code,version,device_name,create_stime,create_etime,update_stime,update_etime,ip,login_type,community_uuid,community_name,param);
                layer.close(index);
            },2000);
        }else{
            layer.msg("请输入需要查询的内容");
        }
	});
	//导出
    $(".export").click(function(){
        location.href=url+"/excel/online/account?access_token="+access_token+"&app_key="+app_key+"&state="+state+"&account="+account+"&device_code="+device_code+"&version="+version+"&device_name="+device_name+"&create_stime="+create_stime+"&create_etime="+create_etime+"&update_stime="+update_stime+"&update_etime="+update_etime+"&ip="+ip+"&login_type="+login_type+"&community_uuid="+community_uuid+"&community_name="+community_name+"&param="+param;
    });
	//操作
	$("body").on("click",".news_edit",function(){  //下线
        console.log($(this).attr("device_code"));
        console.log($(this).attr("account"));
        console.log($(this).attr("community_uuid"));
        $.ajax({
            type:"post",
            url: url+"/force/logout?access_token="+access_token,
            data:{
                "app_key":app_key,
                "device_code":$(this).attr("device_code"),
                "account":$(this).attr("account"),
                "community_uuid":$(this).attr("community_uuid"),
                "param":param
            },
            dataType:'json',
            success:function(data){
                console.log(data);
                if(data.code==0){
                    newsData =data.content.account_list;
                    layer.alert('下线成功',{icon:6, title:'账号下线'});
                    newsList();
                }else{
                    layer.alert(JSON.stringify(data.message), {
                        title: '错误信息'
                    });
                }
            },
            error:function(data){
            }
        });
	});
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
	//账号登录日志查看
    $("body").on("click",".news_collect",function(){  //编辑
        // layer.alert('您点击了文章编辑按钮，由于是纯静态页面，所以暂时不存在编辑内容，后期会添加，敬请谅解。。。',{icon:6, title:'文章编辑'});
        console.log($(this).attr("account"));
        layui.data('test', {
            key: 'account',
            value:$(this).attr("account")
        });
        var index = layui.layer.open({
            title : "账号登录日志查询",
            type :2,
            content : "Login_log.html?app_key="+app_key,
            success : function(layero, index){
                console.log(layero);
                console.log(index);
                layui.layer.tips('点击此处返回应用列表', '.layui-layer-setwin .layui-layer-close', {
                    tips: 3
                });
            }
        });
        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
        $(window).resize(function(){
            layui.layer.full(index);
        });
        layui.layer.full(index);
        /////
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
                start(app_key,page_size,page,state,account,device_code,version,device_name,create_stime,create_etime,update_stime,update_etime,ip,login_type,community_uuid,community_name,param);
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
            console.log(currData);
            for(var i=0;i<currData.length;i++){
                var online_state='';//在线状态
                var login_type='';//登录方式
                if(currData[i].online_state==0){
                    online_state="离线"
                }else{
                    online_state="在线"
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
                //console.log(currData[i].device_info);
                // var device_info=JSON.stringify(currData[i].device_info);
                dataHtml += '<tr>'
                    // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
                    +'<td>'+currData[i].account+'</td>'
                    +'<td>'+online_state+'</td>'
                    +'<td>'+login_type+'</td>'
                    +'<td>'+currData[i].version+'</td>'
                    +'<td>'+currData[i].login_time+'</td>'
                    +'<td>'+currData[i].device_code+'</td>'
                    +'<td>'+currData[i].device_name+'</td>';
                //console.log(currData[i]);
                //str = currData[i].device_info.replace(/"(\w+)":/g, "$1:");
                // str = str.replace(/"(\w+)"(\s*:\s*)/g, "$1$2");
                if(currData[i].device_info=='null'||currData[i].device_info==undefined){
                    dataHtml +='<td></td>';
                }else{
                    dataHtml +='<td><button class="layui-btn look_but layui-btn-radius layui-btn-normal" account="'+currData[i].account+'">查看</button></td>';
                    $.each(currData[i].device_info, function(key, val) {
                        // alert(currData[i].device_info[key]);
                        //dataHtml +='<tr><td>'+key+'</td><td>'+val+'</td></tr>';

                    });
                    //dataHtml +='';
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
                    +'<td>'+currData[i].community_name+'</td>'
                    +'<td>'
                    +  '<a class="layui-btn layui-btn-mini news_edit" device_code="'+currData[i].device_code+'" account="'+currData[i].account+'" community_uuid="'+currData[i].community_uuid+'"><i class="iconfont icon-edit"></i>下线</a>'
                    +  '<a class="layui-btn layui-btn-normal layui-btn-mini news_collect" account="'+currData[i].account+'"><i class="layui-icon">&#xe64c;</i>登录日志</a>'
                    +'</td>'
                    +'</tr>';
            }
        }else{
            dataHtml = '<tr><td colspan="11">暂无数据</td></tr>';
        }
        $(".news_content").html(dataHtml);
    }
	// function newsList(that){
	// 	//渲染数据
	// 	function renderDate(data,curr){
	// 		var dataHtml = '';
	// 		if(!that){
	// 			currData = newsData.concat().splice(curr*nums-nums, nums);
	// 		}else{
	// 			currData = that.concat().splice(curr*nums-nums, nums);
	// 		}
	// 		if(currData.length != 0){
	// 			for(var i=0;i<currData.length;i++){
	// 				var online_state='';//在线状态
	// 				var login_type='';//登录方式
	// 				if(currData[i].online_state==0){
     //                    online_state="离线"
	// 				}else{
     //                    online_state="在线"
	// 				}
	// 				if(currData[i].login_type==1){
     //                    login_type='静默';
	// 				}else if(currData[i].login_type==2){
     //                    login_type='密码';
	// 				}else if(currData[i].login_type==3){
     //                    login_type='手势';
     //                }else if(currData[i].login_type==4){
     //                    login_type='微信';
     //                }else{
     //                    login_type='QQ';
     //                }
     //                // var device_info=JSON.stringify(currData[i].device_info);
	// 				dataHtml += '<tr>'
	// 		    	// +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
     //                +'<td>'+currData[i].account+'</td>'
	// 		    	+'<td>'+online_state+'</td>'
	// 		    	+'<td>'+login_type+'</td>'
     //                +'<td>'+currData[i].version+'</td>'
     //                +'<td>'+currData[i].login_time+'</td>'
     //                +'<td>'+currData[i].device_code+'</td>'
     //                +'<td>'+currData[i].device_name+'</td>';
     //                //console.log(currData[i]);
     //                //str = currData[i].device_info.replace(/"(\w+)":/g, "$1:");
     //                // str = str.replace(/"(\w+)"(\s*:\s*)/g, "$1$2");
     //                console.log(currData[i].device_info);
     //                if(currData[i].device_info.ipAddress!=undefined){
     //                dataHtml +='<td><table border="1">'
     //                        +'<tr><td>ipAddress</td><td>'+currData[i].device_info.ipAddress+'</td></tr>'
     //                        +'<tr><td>networkType</td><td>'+currData[i].device_info.networkType+'</td></tr>'
     //                        +'<tr><td>device</td><td>'+currData[i].device_info.device+'</td></tr>'
     //                        +'<tr><td>macAddress</td><td>'+currData[i].device_info.macAddress+'</td></tr>'
     //                        +'</table>'
     //                    +'</td>';
     //                }else{
     //                  dataHtml +='<td>'+currData[i].device_info+'</td>';
     //                }
     //                dataHtml +='<td>'+currData[i].ip+'</td>'
     //                +'<td>'+currData[i].community_uuid+'</td>'
	// 		    	+'<td>'
	// 				+  '<a class="layui-btn layui-btn-mini news_edit" device_code="'+currData[i].device_code+'" account="'+currData[i].account+'" community_uuid="'+currData[i].community_uuid+'"><i class="iconfont icon-edit"></i>下线</a>'
	// 				+  '<a class="layui-btn layui-btn-normal layui-btn-mini news_collect" account="'+currData[i].account+'"><i class="layui-icon">&#xe64c;</i>登录日志</a>'
	// 		        +'</td>'
	// 		    	+'</tr>';
	// 			}
	// 		}else{
	// 			dataHtml = '<tr><td colspan="11">暂无数据</td></tr>';
	// 		}
	// 	    return dataHtml;
	// 	}
    //
	// 	//分页
	// 	var nums = 13; //每页出现的数据量
	// 	if(that){
	// 		newsData = that;
	// 	}
	// 	laypage({
	// 		cont : "page",
	// 		pages : Math.ceil(newsData.length/nums),
	// 		jump : function(obj){
	// 			$(".news_content").html(renderDate(newsData,obj.curr));
	// 			$('.news_list thead input[type="checkbox"]').prop("checked",false);
	// 	    	form.render();
	// 		}
	// 	})
	// }
    function my_json_decode(str) {        //
        str = preg_replace('/"(\w+)"(\s*:\s*)/is', '$1$2', str);   //去掉key的双引号
        return str;
    }
});
