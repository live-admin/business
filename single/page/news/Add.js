layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','laydate'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		layedit = layui.layedit,
		laydate = layui.laydate,
		$ = layui.jquery;
 	var state;
    var single_url;
    if(document.domain=='localhost'){
        single_url='single-czytest.colourlife.com';
    }else{
        single_url=document.domain;
    }
    var url="https://" + single_url + "/backend";
 	// form.on("submit(addying)",function(data){
 	$("#addying").click(function () {
        if($("input[type='checkbox']").is(':checked')){
            state=1;
        }else{
            state=0;
        }
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        $.ajax({
            type:"post",
            url: url+"/application/add?access_token="+$.cookie('access_token'),
            data:{
                "name":$(".Name").val(),
                "password":$(".password").val(),
                "mobile":$(".mobile").val(),
                "table_pre":$(".table_pre").val(),
                "state":state,
                "push_key":$(".Key").val(),
                "push_secret":$(".Secret").val()
            },
            dataType:'json',
            success:function(data){
                console.log(0);
                if(data.code==0){
                    top.layer.close(index);
                    top.layer.msg("应用添加成功！");
                    layer.closeAll("iframe");
                    //刷新父页面
                    parent.location.reload();
                    return false;
                }else{
                    top.layer.close(index);
                    layer.alert(JSON.stringify(data.message), {
                        title: '错误信息'
                    });
                }
            },
            error:function(data){
            }
        });
    });

 	// });
	
})
