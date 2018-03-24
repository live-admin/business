<!-- 订单确认页面 -->
<template>
	<div class="confirmation">

		<div class="free_info">
			<p>免物业费地址：<span v-text="free_adress.default_property"></span></p>
			<p>免物业费月份：<span>{{month_arr[0]}} - {{month_arr[month_arr.length-1]}}</span></p>
		</div>
				
		<div class="goods_list">
			<ul>
				<li class="signle" v-if="carInfo.goodsList.length == 1">

					<div class="goods_show">
						<img :src="carInfo.goodsList[0].goods_img" alt="">
					</div>

					<p class="goods_des" v-text="carInfo.goodsList[0].goods_name">Apple iPhone X (A1865) 64GB 深空灰色 移动联通电信4G手机</p>

				</li>
								

				<li @click="goToGoodLists"  v-else>
					<div class="goods_show" v-for="(item,index) in carInfo.goodsList" v-if="index<5">
						<img :src="item.goods_img" alt="">
					</div>
				</li>
			</ul>
			<p>共<span v-text="carInfo.totalAmount"></span>件商品</p>
		</div>

		<div class="consignee" @click="adresslist">
			<div>
				<p>
					<img src="../../static/images/Pin.png" alt="">
					收货人：
					<span v-text="defaultAddress.real_name">ssra</span> 
					<span v-text="defaultAddress.mobile"></span>
				</p>

				<p>收货地址：

					<span v-text="defaultAddress.address"></span>

				</p>
						
			</div>
						
			<img src="../../static/images/right_icon.png" alt="">
		</div>

		<div class="referrer" style="margin-bottom:1.1rem">
			<p>推荐人：
				<input type="text" placeholder="手机号码（选填）"  @blur="checkNum" v-model="Referee">
			</p>
			
			<!-- <img @click="QRCode" src="../../static/images/shaoyishao.png" alt=""> -->
		</div>

		<div class="buy_btn">
			<div>
				<p>合计：
					<span>¥ </span>
					<span>{{carInfo.totalFee | numFormat}}</span>
				</p>
			</div>
			<div @click="goPay">
				去支付
			</div>
		</div>

	</div>

</template>

<script>

function colourlifeScanCodeHandler(response){//colourlifeScanCodeHandler【扫一扫回调方法】
	// var _this=this;
	alert(JSON.stringify(response));
	// Indicator.open();
	// var QRurl=response.qrCode;
	// // alert(this.getStrValue("code",QRurl,"1"));
	// let param = {
	//     access_token:sessionStorage.getItem("access_token"),
	//     code:this.getStrValue("code",QRurl,"1")
	// };
	// scanMobile(param).then(function (data) {
	//     // alert(JSON.stringify(data))
	//     Indicator.close();
	//     if(data.code == '0'){
	//         _this.Referee=data.content.mobile;
	//     }else{
	//         Toast(data.message);
	//     }
	// })
};

function getStrValue(fieldName, str, flag) {
	var fieldIndex = str.indexOf(fieldName + "="); //第一次出现指定字符串的位置
	var fieldRemain = str.substr(fieldIndex + 5, str.length);
	if (flag == "1")
		return fieldRemain.substr(0, fieldRemain.indexOf("&"));
	else {
		return fieldRemain.substr(0, str.length);
	}
};

import { getFeeList, receivAddress, order, scanMobile} from '../api/api';
import { Toast, Indicator } from 'mint-ui';
import Vue from 'vue'
export default {
	name: 'Confirmation',
	data () {
		return {

			free_adress:'',//物业费的信息
			carInfo:'', //已经购买商品的信息
			// 收货地址
			defaultAddress:{
				address_id:'',
				real_name:'',
				mobile:'',
				is_default:"1",
				address:'',
			},
			Referee:'',//推荐人
			reg:/^1\d{10}$/, //判断推荐人
			feeList:[], //账单列表
			bills_id_arr:"", //已经免掉物业费的ID
			month_arr:[], //所免掉物业费的月份

			addlist:[],

			// freeMonthlength:JSON.parse(sessionStorage.getItem('progressInfo')).num,
			freeMonthlength:JSON.parse(sessionStorage.getItem('progressInfo')),
			feelists:[],
			total_money:'',//总金额
			

			
			
			goods_arr:[],

			goodslist:[],
			progressInfo:[],
			goodsImg:[],

			

			goodsublist:[],
		}
	},

	filters:{

        // 数字转换成两位小数点---完成
        numFormat(value){

            return parseFloat(value).toFixed(2);

        },

    },
	created(){

		var _this = this;

		//物业费减免地址
		_this.free_adress = JSON.parse(sessionStorage.getItem('propertyAddress'));

		//购物车信息
		_this.carInfo = JSON.parse(sessionStorage.getItem('carInfo')); 


		//判断是否选择地址
		if( sessionStorage.getItem('Select_address') != null){

			var Select_address = JSON.parse(sessionStorage.getItem('Select_address'));
			_this.defaultAddress = Select_address;
			sessionStorage.removeItem('Select_address');

		}else{

			_this.initPage();//收货地址

		}

		// 获取账单信息
		_this.getFeeList();

		


		// this.feelists = JSON.parse(sessionStorage.getItem('feelists'));
		// this.goodslist = JSON.parse(sessionStorage.getItem('goodslist'));
		// this.progressInfo = JSON.parse(sessionStorage.getItem('progressInfo'));

		// this.total_money=JSON.parse(sessionStorage.getItem('cashtotal')).toFixed(2);//总金额

		// for(var i=0;i<this.goodslist.length;i++){
		// 	if(this.goodslist[i].amount >0){
		// 		this.goodsImg.push({img:this.goodslist[i].goods_img,name:this.goodslist[i].name});
		// 		this.goodsublist.push(this.goodslist[i]);
		// 	}
		// }


		

		// if(this.freeMonthlength > 0){
		// 	for(var i=1;i<this.freeMonthlength+1;i++){
		// 		if(this.freeMonthlength == 1){
		// 			this.freeServiceFeetxt = this.feelists[0].year_month;
		// 		}else{
		// 			this.freeServiceFeetxt = this.feelists[0].year_month+'-'+this.feelists[this.freeMonthlength-1].year_month;
		// 		}
		// 		// 请求参数
		// 		this.bills_id_arr += this.feelists[i-1].bill_id + ',';
		// 		this.month_arr.push(this.feelists[i-1].year_month);
		// 	}
		// }else{
		// 		this.freeServiceFeetxt = '无';
		// }

	},

	methods:{

		// 获取到账单列表---完成
		//当前缴费地址各个月份欠费信息
        getFeeList(){

            var _this = this;

            var param = {

                access_token:sessionStorage.getItem("access_token"),

            };

            getFeeList(param).then(function (data) {

                if(data.code == 0){

                    _this.feeList = data.content.bill_arr;

                    // 给每一个费用加一个进度
                    for(var i = 0; i < _this.feeList.length; i++){

                        _this.feeList[i].money = parseFloat(_this.feeList[i].money);

                       	if( i < _this.carInfo.propertyMounth){

                       		var mounth = _this.feeList[i].year + '年' + _this.feeList[i].month + '月';

                       		if(_this.bills_id_arr){

                       			_this.bills_id_arr = _this.bills_id_arr + ',' +  _this.feeList[i].bill_id;

                       		}else{

                       			_this.bills_id_arr = _this.feeList[i].bill_id;

                       		}

                       		_this.month_arr.push(mounth);

                       	}

                    }


                }else{

                    Toast(data.message);

                }
            });

        },

		// 获取收货地址
		initPage:function(){

			let _this = this;
			
			if(this.$route.query.address_id != undefined){
				_this.defaultAddress.real_name = this.$route.query.real_name;
				_this.defaultAddress.mobile = this.$route.query.mobile;
				_this.defaultAddress.address = this.$route.query.address;
				_this.defaultAddress.address_id = this.$route.query.address_id;
				return;
			}

			Indicator.open();

			let param = {

			 	access_token:sessionStorage.getItem("access_token"),

			};

			receivAddress(param).then(function (data) {
					
				Indicator.close();

				if(data.code == '0'){

					_this.addlist = data.content;

					for(var i = 0;i<_this.addlist.length;i++){

						if(_this.addlist[i].is_default == 1){

							_this.defaultAddress = _this.addlist[i];

						}

					}

				}else{

					Toast(data.message);

				}

			})
		},

		// 跳转到商品列表 ----完成
		goToGoodLists:function(){

			var _this = this;

			_this.$router.push({path:'/goodslist',name:'Goodslist'});//,query:{'id':3}

		},

		// 跳转到添加地址页 ----完成
		adresslist:function(){

			 this.$router.push({path:'/Addresslist',name:'Addresslist'});

		},


		goPay:function(){

			var u = navigator.userAgent;
			var app = navigator.appVersion;
			var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
			var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

			var _this = this;

			if(_this.Referee){

				if(!_this.reg.test(_this.Referee)){

					Toast("请输入正确的推荐人手机号码",1000); return;

				}

			}
				
			Indicator.open();

			var goodsInfo = [];

			for(var i = 0; i < _this.carInfo.goodsList.length; i++){

				var obj = {
					id:_this.carInfo.goodsList[i].goods_id,
					num:_this.carInfo.goodsList[i].curBuyNumber,
				};

				goodsInfo.push(obj);

			};


				
			var param = {

				access_token:sessionStorage.getItem("access_token"),//token
				address_id:_this.defaultAddress.address_id, //默认收货地址的ID
				recommend_mobile:_this.Referee, //推荐人的手机号
				bills_id_arr:_this.bills_id_arr, //所免物业费的账单ID
				goods_arr:JSON.stringify(goodsInfo), //所购买的商品
				month_arr:JSON.stringify(_this.month_arr), //免掉物业费的月份

			};

				
			order(param).then(function (data) {

				Indicator.close();

				sessionStorage.removeItem('goodslist');
				sessionStorage.removeItem('progressInfo');
				sessionStorage.removeItem('feelists');

				if(data.code == '0'){

					if(isiOS){
						_this.iosLink(data.content,base+'/dist/#/Paysucceed?access_token='+sessionStorage.getItem("access_token")+'&colour_sn='+data.content);//苹果调用代码
					}else{
						_this.androidLink(data.content,base+'/dist/#/Paysucceed?access_token='+sessionStorage.getItem("access_token")+'&colour_sn='+data.content);//android调用代码
					}
				}else{
					Toast(data.message);
				}
			})
		},

		//跳转到IOS的支付链接
		iosLink:function(sn,notifyUrl){
			window.location.href="*payFromHtml5*"+sn+"*"+notifyUrl;
		},

		//跳转到安卓的支付链接
		androidLink:function(sn,notifyUrl){
			jsObject.payFromHtml(sn,notifyUrl);
		},

		// 扫一扫，扫推荐人
		QRCode:function(){
				var _this=this;
				var param={'value':'colourlife'};
				if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
						//苹果调用代码
						_this.setupWebViewJavascriptBridge(function(bridge) {
								bridge.callHandler('scanActivity', param, function(response) {
								});
								bridge.registerHandler('colourlifeScanCodeHandler', function (data, responseCallback) {
										_this.colourlifeScanCodeHandler(data);
								});
						});
				} else {
						//android调用代码
						var response=jsObject.scanActivity(JSON.stringify(param));
						_this.colourlifeScanCodeHandler(response);
				}
		},

		setupWebViewJavascriptBridge:function(callback) {//苹果
			if (window.WebViewJavascriptBridge) { return callback(WebViewJavascriptBridge); }
			if (window.WVJBCallbacks) { return window.WVJBCallbacks.push(callback); }
			window.WVJBCallbacks = [callback];
			var WVJBIframe = document.createElement('iframe');
			WVJBIframe.style.display = 'none';
			WVJBIframe.src = 'wvjbscheme://__BRIDGE_LOADED__';
			document.documentElement.appendChild(WVJBIframe);
			setTimeout(function() { document.documentElement.removeChild(WVJBIframe) }, 0)
		},

		colourlifeScanCodeHandler:function(response){//colourlifeScanCodeHandler【扫一扫回调方法】
			var _this=this;
			Indicator.open();
			var QRurl=response.qrCode;
			let param = {
				access_token:sessionStorage.getItem("access_token"),
				code:this.getStrValue("code",QRurl,"1")
			};
			scanMobile(param).then(function (data) {
				// alert(JSON.stringify(data))
				Indicator.close();
				if(data.code == '0'){
					_this.Referee=data.content.mobile;
				}else{
					Toast(data.message);
				}
			})
		},

		getStrValue:function(fieldName, str, flag) {
			var fieldIndex = str.indexOf(fieldName + "="); //第一次出现指定字符串的位置
			var fieldRemain = str.substr(fieldIndex + 5, str.length);
			if (flag == "1")
					return fieldRemain.substr(0, fieldRemain.indexOf("&"));
			else {
					return fieldRemain.substr(0, str.length);
			}
			
		},

		// 检测手机号码是否正确
		checkNum:function(){
					
			if(!this.reg.test(this.Referee) && this.Referee != ''){
				Toast("请输入正确的推荐人手机号码",1000);
			}
		}
	}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
