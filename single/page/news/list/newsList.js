layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		$ = layui.jquery;
	//应用加载
	var url="https://" + document.domain + "/";
    start();
    var newsData = '';
	function start() {
        $.ajax({
            type:"post",
            url: url+"backend/application/get/list?access_token="+$.cookie('access_token'),
            data:{
                "name":"",
                "mobile":"",
				"page":"",
				"page_size":10
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
		var newArray = [];
		if($(".search_input").val() != ''){
			var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
            	$.ajax({
					url :url+"backend/application/get/list?access_token="+$.cookie('access_token'),
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
	})
	//添加应用
	$(".newsAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加应用",
			type : 2,
			content : "newsAdd.html",
			success : function(layero, index){
				layui.layer.tips('点击此处返回应用列表', '.layui-layer-setwin .layui-layer-close', {
					tips: 3
				});
			}
		})
		//改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
		$(window).resize(function(){
			layui.layer.full(index);
		});
		layui.layer.full(index);
	});

	//推荐文章
	$(".recommend").click(function(){
		var $checkbox = $(".news_list").find('tbody input[type="checkbox"]:not([name="show"])');
		if($checkbox.is(":checked")){
			var index = layer.msg('推荐中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                layer.close(index);
				layer.msg("推荐成功");
            },2000);
		}else{
			layer.msg("请选择需要推荐的文章");
		}
	})

	//审核文章
	$(".audit_btn").click(function(){
		var $checkbox = $('.news_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.news_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			var index = layer.msg('审核中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
            	for(var j=0;j<$checked.length;j++){
            		for(var i=0;i<newsData.length;i++){
						if(newsData[i].newsId == $checked.eq(j).parents("tr").find(".news_del").attr("data-id")){
							//修改列表中的文字
							$checked.eq(j).parents("tr").find("td:eq(3)").text("审核通过").removeAttr("style");
							//将选中状态删除
							$checked.eq(j).parents("tr").find('input[type="checkbox"][name="checked"]').prop("checked",false);
							form.render();
						}
					}
            	}
                layer.close(index);
				layer.msg("审核成功");
            },2000);
		}else{
			layer.msg("请选择需要审核的文章");
		}
	})

	//批量删除
	$(".batchDel").click(function(){
		var $checkbox = $('.news_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.news_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
				var index = layer.msg('删除中，请稍候',{icon: 16,time:false,shade:0.8});
	            setTimeout(function(){
	            	//删除数据
	            	for(var j=0;j<$checked.length;j++){
	            		for(var i=0;i<newsData.length;i++){
							if(newsData[i].newsId == $checked.eq(j).parents("tr").find(".news_del").attr("data-id")){
								newsData.splice(i,1);
								newsList(newsData);
							}
						}
	            	}
	            	$('.news_list thead input[type="checkbox"]').prop("checked",false);
	            	form.render();
	                layer.close(index);
					layer.msg("删除成功");
	            },2000);
	        })
		}else{
			layer.msg("请选择需要删除的文章");
		}
	})

	//全选
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});

	//通过判断文章是否全部选中来确定全选按钮是否选中
	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked');
		if(childChecked.length == child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	})

	//是否展示
	form.on('switch(isShow)', function(data){
		var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            layer.close(index);
			layer.msg("展示状态修改成功！");
        },2000);
	})
 
	//操作
	$("body").on("click",".news_edit",function(){  //编辑
		// layer.alert('您点击了文章编辑按钮，由于是纯静态页面，所以暂时不存在编辑内容，后期会添加，敬请谅解。。。',{icon:6, title:'文章编辑'});
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
	$("body").on("click",".news_del",function(){  //删除
        layer.open({
            content: '感谢您参与本次活动！',
            btn: ['确认', '取消'],
            yes: function(index, layero) {
                window.location.href='../application';
            },
            btn2: function(index, layero) {

            }
            ,
            cancel: function() {
                //右上角关闭回调

            }
        });
        //window.location.href="aplication.html";
		// var _this = $(this);
		// console.log(this);
		// layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
		// 	//_this.parents("tr").remove();
		// 	for(var i=0;i<newsData.length;i++){
		// 		 console.log(this.attr("data-id"));
		// 		if(newsData[i].newsId == _this.attr("data-id")){
		// 			newsData.splice(i,1);
		// 			newsList(newsData);
		// 		}
		// 	}
		// 	layer.close(index);
		// });
	})

	function newsList(that){
		alert(1);
		//分页
		var nums = 13; //每页出现的数据量
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
                        +'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
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
                        +  '<a class="layui-btn layui-btn-normal layui-btn-mini news_collect" data-id="'+currData[i].id+'" href="../application/navigation.html"><i class="layui-icon">&#xe64c;</i>查看</a>'
                        // +  '<a class="layui-btn layui-btn-danger layui-btn-mini news_del" data-id="'+currData[i].id+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
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
