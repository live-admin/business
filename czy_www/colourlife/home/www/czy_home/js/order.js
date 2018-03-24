// var url = "http://cmobile-czytest.colourlife.com";
var url = "http://cmobile.colourlife.com";
// var urlice="http://icetest.colourlife.net:8081/v1/czyprovide";
var urlice="http://iceapi.colourlife.com:8081/v1/czyprovide";
var ts=Math.round(new Date().getTime()/1000).toString();
// var icetoken="3RdasXGBrjx4xJv7O6k3";
var icetoken="r9A0ZSn5b4jOSJEnGc3y";
// var iceappID="ICETEST0-C631-4BA2-B262-E7C17B743701";
var iceappID="ICECZY00-F26F-42B8-988C-27F4AEE3292A";
var icesign=hex_md5(iceappID+ts+icetoken+"false");
// alert($.cookie('czy_username'));
// alert($.cookie('czy_userid'));
var menubar = new Vue({
		el:".container",
		data:{
			//获取初始化数据
			orderlist:[],

			timeInfo:[],
			timeCurrentIndex:1,
			time:2,

			menuInfo:[],
			menuCurrentIndex:0,
			menu:-1,

			categoryInfo:[],
			categoryCurrentIndex:0,
			category:-1,


			startDate:'',//formatDate(new Date())
			endDate:'',//formatDate(new Date())
			currentpage:'',
			pageSize:'',

			name:$.cookie('czy_username'),
			id:$.cookie('czy_userid'),
			key:$.cookie('czy_userkey'),//7673919 secret:$.cookie('czy_usersecret'),
			secret:"Z80yGx0GMgMxPwMg",

			page:{
				pageSize:'',
				currentpage:'',
				count:''
			},

			detail:false,
			currentdetail:-1,
            error_information:'',
			//订单详情
            Whether:'0',//是否成功0是否1是是
            Single_typelist:[],//订单详情
            Relevant_informationlist:[]//相关信息

		},
		created:function(){
			this.initPage();
		},
		methods:{
			initPage:function(){
				// var ts = Date.parse(new Date()) / 1000;
				var params="/powerFees/myOrder?ts="+ts+"&sign="+icesign+"&appID="+iceappID;
			    //var param = '/1.0/power/myOrder?customerId='+this.id+'&page='+this.page.currentpage+'&pageSize='+this.page.pageSize+'&time='+this.time+'&startTime='+this.startDate+'&endTime='+this.endDate+'&status='+this.menu+'&category='+this.category+'&key='+this.key+'&ts='+ ts +'&ve=1.0.0';
			    //var sign = hex_md5(param + '&secret=' + this.secret);
			    //var requestUrl = url+param+ '&sign=' + sign;
			    console.log(urlice+params);
	            $.ajax({
	                type:"GET",
	                url: urlice+params,
	                data:{
	                	"customerId":this.id,
						"page":this.page.currentpage,
						"pageSize":this.page.pageSize,
						"time":this.time,
						"startTime":this.startDate,
						"endTime":this.endDate,
						"status":this.menu,
						"category":this.category
					},
	                dataType:'json',
	                success:function(result){
	                	console.log(result);
                        if(result.code ==0){
		                    menubar.orderlist = result.content.data;
		                    menubar.menuInfo = result.content.status;
		                    menubar.categoryInfo = result.content.category;
		                    menubar.timeInfo = result.content.time;
		                    menubar.page.pageSize = result.content.pageSize;
		                    menubar.page.currentpage = result.content.nowPage;
		                    menubar.page.count = result.content.count;
		                    // console.log(menubar.orderlist.length);
                        }else{
	                    	// alert(result.message);
                            menubar.orderlist=[];
	                    	//console.log(menubar.orderlist.length);
                            menubar.error_information=result.message;
						}
	                },
	                error:function(result){
	                }
	            });
			},
            Detail:function(id) {
                var params="/powerFees/orderDetails?ts="+ts+"&sign="+icesign+"&appID="+iceappID;
                $.ajax({
                    type:"GET",
                    url: urlice+params,
                    data:{
                        "order_id":id
					},
                    dataType:'json',
                    success:function(result){
                    	console.log(result);
                        if(result.code ==0){
                            this.Single_typelist=result.content.order;
                            this.Relevant_informationlist=result.content.order.relate_info;
								console.log(menubar);
                        }else{
                            // alert(result.message);
                            menubar.orderlist=[];
                            menubar.error_information=result.message;
                        }
                    },
                    error:function(result){
                    }
                });
            },
			dateSelect:function(){
				this.time = 0;
				this.timeCurrentIndex = -1;
			},
			timeSelect:function(value,index){
				this.time = value;
				this.timeCurrentIndex = index;
				console.log(value+"ppp"+index);
                this.initPage();
				$(".starDate").val("请输入开始时间");
				$(".endDate").val("请输入结束时间")
			},
			categorySelect:function(value,index){
				this.category = value;
				this.categoryCurrentIndex = index;
			},
			menuSelect:function(value,index){
				this.menu = value;
				this.menuCurrentIndex = index;
                this.initPage();
			},
			search:function(){
				if(this.time == 0){
					if($(".starDate").val()!=""&&$(".endDate").val()!=""){
						$(".search_btn").css("display",'none');
                        $(".loader").css("display",'block');
                        this.startDate = $(".starDate").val();
                        this.endDate = $(".endDate").val();
                        setTimeout(this.initPage(),3000);
                        $(".search_btn").css("display",'block');
                        $(".loader").css("display",'none');
					}else{
						alert("请输入时间段")
					}
				}else{
                    alert("请输入时间段");
					this.startDate = '';
					this.endDate = '';
				}

			},
			// starDateSelect:function(){
			// 	this.startDate = $(".starDate").val();
			// 	// alert(this.startDate);
			// 	if(this.startDate != formatDate(new Date()) || this.endDate != formatDate(new Date())){
			// 		menubar.timeCurrentIndex = -1;
			// 	}
			// },
			// endDateSelect:function(){
			// 	this.endDate = $(".endDate").val();
			// 	// alert( this.endDate);
			// 	if(this.endDate != formatDate(new Date()) || this.startDate != formatDate(new Date())){
			// 		menubar.timeCurrentIndex = -1;
			// 	}
			// },
			getIndexPage:function(){
				if(this.page.currentpage == 1){
					alert("当前已经第一页了");
				}else{
					this.page.currentpage = 1;
					this.initPage();
				}
			},
			getPerPage:function(){
				if(this.page.currentpage == 1){
					alert("当前已经第一页了");
				}else{
					this.page.currentpage--;
					this.initPage();
				}
				
			},
			getNextPage:function(){
				if(this.page.currentpage == Math.ceil(this.page.count/8)){
					alert("当前已经是最后一页了");
				}else{
					this.page.currentpage++;
					this.initPage();
				}
			},
			getLastPage:function(){
				if(this.page.currentpage == Math.ceil(this.page.count/8)){
					alert("当前已经最后一页了");
				}else{
					this.page.currentpage = Math.ceil(this.page.count/8);
					this.initPage();
				}
			},
			initNumber:function(){
				var pageNum = Math.ceil($(".turnpage").val());
				if(pageNum < 1){
					alert("不能为负数");
					$(".turnpage").val(1);
				}else if(pageNum > Math.ceil(this.page.count/8)){
					alert("不能超过最大页数");
					$(".turnpage").val(1);
				}else if(isNaN(pageNum)){
					alert("请输入数字");
					$(".turnpage").val(1);
				}
			},
			turnPage:function(){
				this.page.currentpage = $(".turnpage").val();
				this.initPage();
			},
			formatDate:function(date){
				// 初始化时间
				var date = new Date();
			    var y = date.getFullYear();  
			    var m = date.getMonth() + 1;  
			    m = m < 10 ? '0' + m : m;  
			    var d = date.getDate();  
			    d = d < 10 ? ('0' + d) : d; 
			    return y + '-' + m + '-' + d;  
			},
            dowmArrow:function(i){
                $("tr:eq("+(i+1)+") td .detail").addClass("into");
                $("tr:eq("+(i+1)+") td .detail .go_buy .down_arrow").css("display","none");
			},
            comeout:function(i){
                $("tr:eq("+(i+1)+") td .detail").removeClass("into");
                $("tr:eq("+(i+1)+") td .detail .go_buy .down_arrow").css("display","block");
			},
            details:function(id){//获取详情交易成功或者失败
                window.location.href="orderState.html?listid="+id;
			},
            Gopayment:function (id) {//详情
                window.location.href="payment.html?listid="+id;
            },
            Godetails:function (id) {//等待付款
                window.location.href="waitPay.html?listid="+id;
            },
            Goclose:function (id) {//关闭
                var params="/powerFees/cancelOrder?ts="+ts+"&sign="+icesign+"&appID="+iceappID;
                $.ajax({
                    type:"GET",
                    url: urlice+params,
                    data:{
                        "order_id":id
                    },
                    dataType:'json',
                    success:function(result){
                        console.log(result);
                        if(result.code ==0){
                            window.location.href="orderState.html?listid="+id;
                        }else{
                          menubar.orderlist=[];
                          menubar.error_information=result.message;
                        }
                    },
                    error:function(result){
                    }
                });
            }
		}
	});

	

