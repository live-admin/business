layui.config({
    base : "js/"
}).use(['form','layer','jquery'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        laypage = layui.laypage,
        layedit = layui.layedit,
        laydate = layui.laydate,
        $ = layui.jquery;
    var state;
    var url="https://" + document.domain + "/backend";
    var localTest = layui.data('test');
    var index='';
    console.log(localTest.list_id); //获得list_id;
    start();
    top.layer.close(index);
    var newsData='';
    function start() {
        $.ajax({
            type:"post",
            url: url+"/application/detail?access_token="+$.cookie('access_token'),
            data:{
                "app_id":localTest.list_id
            },
            dataType:'json',
            success:function(data){
                if(data.code==0){
                    newsData =data.content.app_list;
                    num_data(newsData);
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
    function num_data(data) {
        $(".Name").attr("value",data.name);
        $(".password").attr("value",data.password);
        $(".mobile").attr("value",data.mobile);
        $(".table_pre").attr("value",data.table_pre);
        $(".Key").attr("value",data.push_key);
        $(".Secret").attr("value",data.push_secret);
        state=data.state;
        if(data.state!=1){
            $(".layui-unselect").addClass("layui-form-onswitch");
            $(".layui-unselect em").html("正常");
        }else{
            $(".layui-unselect").removeClass("layui-form-onswitch");
            $(".layui-unselect em").html("禁用");
        }
    }
    form.on('switch(switchTest)', function(data){
        console.log(this.checked);
        if(this.checked){
            state=0;
        }else{
            state=1;
        }
    });
    // form.on("submit(addying)",function(data){
    $("#sumble").click(function(){
        index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        $.ajax({
            type:"post",
            url: url+"/application/update?access_token="+$.cookie('access_token'),
            data:{
                "app_id":localTest.list_id,
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
                if(data.code == 0){
                    top.layer.close(index);
                    top.layer.msg("应用修改成功！");
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
     // });
    });
});
