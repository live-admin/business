layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','laydate'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		layedit = layui.layedit,
		laydate = layui.laydate,
		$ = layui.jquery;
    //获取url
    function url_ID(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        return r?decodeURIComponent(r[2]):null;
    }
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
    var date_type='1';//类型：1周，2月,默认1
    var type='1';//1登录，2注册，默认1
    var community_name='';//小区/片区/大区/事业部名称
    PieChart();//饼状图
    function PieChart() {
        $.ajax({
            type:"post",
            url: url+"/version/list/count?access_token="+access_token,
            data:{
                "app_key":app_key
            },
            dataType:'json',
            success:function(data){
                if(data.code==0){
                	var data_L=[];
                	var data_S=[];
                	for(var i=0;i<data.content.number.length;i++){
                        data_L.push(data.content.number[i].version);
                        data_S.push({
                            value:data.content.number[i].number,
							name:data.content.number[i].version
						})
					}
                    PieChart_chart(data_L,data_S);
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
    function PieChart_chart(L,S) {
        //饼状图
        var myChart02 = echarts.init(document.getElementById('PieChart'));
        var option02 = {
            title : {
                text: '版本人数统计图',
                subtext: '单位（个）',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data:L
            },
            series : [
                {
                    name: '人数统计',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:S,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };
        myChart02.setOption(option02);
    }
    //柱状图
    Histogram(app_key,date_type,type,community_name);
	function Histogram(app_key,date_type,type,community_name) {
        $.ajax({
            type:"post",
            url: url+"/community/date/count?access_token="+access_token,
            data:{
                "app_key":app_key,
				"date_type":date_type,//类型：1周，2月,默认1
				"type":type,//1登录，2注册，默认1
				"community_name":community_name//小区/片区/大区/事业部名称
            },
            dataType:'json',
            success:function(data){
                console.log(data);
                if(data.code==0){
                    var data_L=[];
                    var data_S=[];
                    var data_p=[];
                    for(var i=0;i<data.content.login_list.length;i++){
                        data_S.push(data.content.login_list[i].count);
                    }
                    for(var i=0;i<data.content.register_list.length;i++){
                        data_L.push(data.content.register_list[i].date);
                        data_p.push(data.content.register_list[i].count);
                    }
                    Histogram_chart(data_L,data_S,data_p);

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
    $(".search_btn").click(function(){
        date_type=$("#date_type").val();
        type=$("#type").val();
        community_name=$("#community_name").val();
        if(date_type!=''||type!=''||community_name!=''){
            var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                Histogram(app_key,date_type,type,community_name);
                layer.close(index);
            },2000);
        }else{
            layer.msg("请输入需要查询的内容");
        }
    });
	//模糊搜索
    //弹出列表框
    $("#community_name").click(function () {
        $("#div_items").css('display', 'block');
        return false;
    });
    //隐藏列表框
    $("body").click(function () {
        $("#div_items").css('display', 'none');
    });
    //文本框输入
    $("#community_name").keyup(function () {
        $("#div_items").css('display', 'block');//只要输入就显示列表框

        if ($("#community_name").val().length <= 0) {
            $(".div_item").css('display', 'block');//如果什么都没填，跳出，保持全部显示状态
            return;
        }
        $(".div_item").css('display', 'none');//如果填了，先将所有的选项隐藏
        //请求接口
        $.ajax({
            type:"post",
            url: url+"/community/page?access_token="+access_token,
            data:{
                "app_key":app_key,
                "keyword":$("#community_name").val()//关键字
            },
            dataType:'json',
            success:function(data){
                $("#div_items").empty();
                if(data.code==0){
                    if(data.content.list.list.length>0){
                        for(var i=0;i<data.content.list.list.length;i++){
                            var lists="<div class='div_item'>"+data.content.list.list[i].name+"</div>";
                            $("#div_items").append(lists);
                            childer();
                        }
                    }else{
                        var lists="<div class='div_item'>未搜索到对应资源</div>";
                        $("#div_items").append(lists);
                    }
                }else{
                    layer.alert(JSON.stringify(data.message), {
                        title: '错误信息'
                    });
                }
            },
            error:function(data){
            }
        });

        // for (var i = 0; i < $(".div_item").length; i++) {
        //     //模糊匹配，将所有匹配项显示
        //     if ($(".div_item").eq(i).text().substr(0, $("#txt1").val().length) == $("#txt1").val()) {
        //         $(".div_item").eq(i).css('display', 'block');
        //     }
        // }
    });
    childer();
    function childer(){
        //移入移出效果
        $(".div_item").hover(function () {
            $(this).css('background-color', '#1C86EE').css('color', 'white');
        }, function () {
            $(this).css('background-color', 'white').css('color', 'black');
        });

        //项点击
        $(".div_item").click(function () {
            $("#community_name").val($(this).text());
        });
    }
    ////
    // Histogram_chart();
    function Histogram_chart(l,s,p) {
        //柱状图
        var myChart01 = echarts.init(document.getElementById('Histogram'));
        var option01 = {
            title : {
                text: '登录/注册人数柱状图'
                // subtext: '纯属虚构'
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['登录数量','注册数量']
            },
            toolbox: {
                show : true,
                feature : {
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    data :l
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'登录数量',
                    type:'bar',
                    data:s,
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            {type : 'average', name: '平均值'}
                        ]
                    }
                },
                {
                    name:'注册数量',
                    type:'bar',
                    data:p,
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            {type : 'average', name : '平均值'}
                        ]
                    }
                }
            ]
        };
        myChart01.setOption(option01);
    }
    //
    //实时折线图
    var times=[];
    var online_num=[];
    LineChart();
    tines_array();
    function tines_array(){
        // LineChart();
        var myDate = new Date();
        //获取当前年
        var year=myDate.getFullYear();
        //获取当前月
        var month=myDate.getMonth()+1;
        //获取当前日
        var date=myDate.getDate();
        var h=myDate.getHours();       //获取当前小时数(0-23)
        var m=myDate.getMinutes();     //获取当前分钟数(0-59)
        var s=myDate.getSeconds();
        var now=year+'-'+p(month)+"-"+p(date)+" "+p(h)+':'+p(m)+":"+p(s);
        var time_list=p(h)+':'+p(m)+":"+p(s);
        console.log(times.length);
        if(times.length>9){
            times.shift();
        }
        times.push(time_list);
    }
    setInterval(function () {
        tines_array();
        LineChart();
        // console.log(now);
    }, 30000);
    function p(s) {
        return s < 10 ? '0' + s: s;
    }
    //获取随机数
    function GetRandomNum(Min,Max)
    {
        var Range = Max - Min;
        var Rand = Math.random();
        return(Min + Math.round(Rand * Range));
    }
    function LineChart(){
        $.ajax({
            type:"post",
            url: url+"/online/count?access_token="+access_token,
            data:{
                "app_key":app_key
            },
            dataType:'json',
            success:function(data){
                if(data.code==0){
                    if(online_num.length>9){
                        online_num.shift();
                    }
                    // var nums=GetRandomNum(0,40)-data.content.online_num;
                    var nums=data.content.online_num;
                    online_num.push(nums);
                    LineChart_chart();
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
    function LineChart_chart(){
        var myChart = echarts.init(document.getElementById('LineChart'));
        option = {
            title: {
                text: '实时在线数',
                subtext: '单位(人)'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data:['人数']
            },
            toolbox: {
                show: true,
                feature: {
                    dataZoom: {
                        yAxisIndex: 'none'
                    },
                    // dataView: {readOnly: false},
                    // magicType: {type: ['line', 'bar']},
                    // restore: {},
                    // saveAsImage: {}
                }
            },
            xAxis:  {
                type: 'category',
                boundaryGap: false,
                data:times
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: '{value}'
                }
            },
            series: [
                {
                    name:'人数',
                    type:'line',
                    data:online_num,
                    markPoint: {
                        data: [
                            {type: 'max', name: '最大值'},
                            {type: 'min', name: '最小值'}
                        ]
                    },
                    markLine: {
                        data: [
                            {type: 'average', name: '平均值'}
                        ]
                    }
                }
            ]
        };
        myChart.setOption(option);
    }

});
