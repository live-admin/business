layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		$ = layui.jquery;
	//应用加载
    var single_url;
    if(document.domain=='localhost'){
        single_url='single-czytest.colourlife.com';
    }else{
        single_url=document.domain;
    }
	var url="https://" + single_url + "/backend";
    start();
    var newsData = '';
	function start() {
        $.ajax({
            type:"post",
            url: url+"/application/get/list?access_token="+$.cookie('access_token'),
            data:{
                "name":"",
                "mobile":"",
				"page":"",
				"page_size":100
            },
            dataType:'json',
            success:function(data){
                console.log(data);
                if(data.code==0){
                    newsData =data.content.app_list;
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
    }
	//查询
	$(".search_btn").click(function(){
		if($(".search_input").val() != ''){
			var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
            	$.ajax({
					url :url+"/application/get/list?access_token="+$.cookie('access_token'),
					type : "post",
                    data:{
                        "name":$(".search_input").val(),
                        "mobile":"",
                        "page":"",
                        "page_size":10
                    },
					dataType : "json",
					success : function(data){
						console.log(data);
		            	newsData =data.content.app_list;
		            	newsList(newsData);
					}
				})
                layer.close(index);
            },2000);
		}else{
			layer.msg("请输入需要查询的内容");
		}
	});
	//添加应用
	$(".newsAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加应用",
			type : 2,
			content : "Add.html",
			success : function(layero, index){
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
	});
    //编辑
	$("body").on("click",".news_edit",function(){
        console.log($(this).attr("data-id"));
        layui.data('test', {
            key: 'list_id',
			value:$(this).attr("data-id")
        });
        var index = layui.layer.open({
            title : "编辑应用",
            type :2,
            content : "edit.html",
            success : function(layero, index){
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
    });
	function newsList(that){
		//分页
		var nums = 10; //每页出现的数据量
		if(that){
			newsData = that;
		}
		laypage({
			cont : "page",
			pages : Math.ceil(newsData.length/nums),
			jump : function(obj){
				$(".news_content").html(renderDate(newsData,obj.curr));
				$('.news_list thead input[type="checkbox"]').prop("checked",false);
		    	form.render();
			}
		});
        //渲染数据
        function renderDate(data,curr){
            console.log(data);
            console.log(curr);
            var dataHtml = '';
            if(!that){
                currData = newsData.concat().splice(curr*nums-nums, nums);
            }else{
                currData = that.concat().splice(curr*nums-nums, nums);
            }
            if(currData.length != 0){
                for(var i=0;i<currData.length;i++){
                    dataHtml += '<tr>'
                        // +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
                        +'<td>'+currData[i].id+'</td>'
                        +'<td>'+currData[i].name+'</td>'
                        +'<td>'+currData[i].app_key+'</td>'
                        +'<td>'+currData[i].app_secret+'</td>'
                        +'<td>'+currData[i].mobile+'</td>'
                        +'<td>'+currData[i].state+'</td>'
                        +'<td>'+currData[i].create_at+'</td>'
                        +'<td>'+currData[i].update_at+'</td>'
                        +'<td>'+currData[i].push_key+'</td>'
                        +'<td>'+currData[i].push_secret+'</td>'
                        +'<td>'
                        +  '<a class="layui-btn layui-btn-mini news_edit" data-id="'+currData[i].id+'"><i class="iconfont icon-edit"></i> 编辑</a>'
                        // +  '<a class="layui-btn layui-btn-normal layui-btn-mini news_collect" data-id="'+currData[i].id+'" href="../application/navigation.html"><i class="layui-icon">&#xe64c;</i>查看</a>'
                        +'</td>'
                        +'</tr>';
                }
            }else{
                dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
            }
            return dataHtml;
        }
	}
})
