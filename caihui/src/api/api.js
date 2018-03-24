/**
 * Created by jerry on 2017/4/13.
 */
import axios from 'axios'
import qs from 'qs';

let base;
let url=document.domain;


if(url == "caihui.test.colourlife.com"){//测试
  	base = 'http://caihui.test.colourlife.com';
}else if(url == "caihui.colourlife.com"){//正式
  	base = 'http://caihui.colourlife.com';
}else if(url=="localhost"){//本地
  	base = 'http://caihui.test.colourlife.com';
}else if(url == "evisit-czybeta.colourlife.com"){//预发
  	base = 'http://caihui.beta.colourlife.com';
}

export default base

//1、首页商品列表----完成
export const goodsList = params => { return axios.get(`${base}/goods/list`, { params: params }).then(res => res.data)}

//2.默认的缴费地址/是否有未支付订单接口---完成
export const homeInfo = params => { return axios.get(`${base}/home/information`, { params: params }).then(res => res.data)}

//3、获取物业缴费地址列表 
export const addresslist = params => { return axios.get(`${base}/property/address`, { params: params }).then(res => res.data)}

//4、选择默认缴费地址-----完成
export const propertyChoose = params => { return axios.get(`${base}/property/choose`, { params: params }).then(res => res.data)}

//5、商品详情
export const goodsDetail = params => { return axios.get(`${base}/goods/detail`, { params: params }).then(res => res.data)}

//6、当前缴费地址各个月份欠费信息----完成
export const feesInfo = params => { return axios.get(`${base}/property/fees`, { params: params }).then(res => res.data)}

//7、收货地址列表
export const receivAddress = params => { return axios.get(`${base}/receiving/address`, { params: params }).then(res => res.data)}

//7.1、收货地址详情
export const receivAddressdetail = params => { return axios.get(`${base}/receiving/address/detail`, { params: params }).then(res => res.data)}

// 8、省市区镇街道接口
export const region = params => { return axios.get(`${base}/region`, { params: params }).then(res => res.data)}

// 9、新增收货地址
export const addAddress = params => { return axios.post(`${base}/address/add`, qs.stringify(params)).then(res => res.data) }

// 10、修改收货地址
export const editAddress = params => { return axios.post(`${base}/address/edit`, qs.stringify(params)).then(res => res.data) }

// 11、下单
export const order = params => { return axios.post(`${base}/order`, qs.stringify(params)).then(res => res.data) }

//12、查询订单支付详情
export const orderCheck = params => { return axios.get(`${base}/order/check`, { params: params }).then(res => res.data)}

// 13、订单列表
export const orderList = params => { return axios.get(`${base}/order/list`, { params: params }).then(res => res.data)}

// 14、订单详情
export const orderDetail = params => { return axios.get(`${base}/order/detail`, { params: params }).then(res => res.data)}

// 15、取消订单
export const cancelOrder = params => { return axios.post(`${base}/order/cancel`, qs.stringify(params)).then(res => res.data) }

// 16、订单物流详情
export const deliverDetail = params => { return axios.get(`${base}/order/deliver`, { params: params }).then(res => res.data)}

// 17、子订单商品信息
export const subList = params => { return axios.get(`${base}/sub/order`, { params: params }).then(res => res.data)}

//删除订单
export const delOrder = params => { return axios.post(`${base}/order/delete`, qs.stringify(params)).then(res => res.data) }

//21删除地址
export const delAddress = params => { return axios.post(`${base}/address/delete`, qs.stringify(params)).then(res => res.data) }

//22、根据code获取手机号码
export const scanMobile = params => { return axios.get(`${base}/scan/mobile`, { params: params }).then(res => res.data)}

//23.获取欠费信息
export const getFeeList = params => { return axios.get(`${base}/property/fees`, { params: params }).then(res => res.data)}

//24.获取商品列表
export const getGoodsList = params => { return axios.get(`${base}/goods/list`, { params: params }).then(res => res.data)}

