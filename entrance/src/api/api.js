/**
 * Created by jerry on 2017/4/13.
 */
import axios from 'axios'
import qs from 'qs';

let rk_url;
let url=document.domain;
if(url=="entrance.czytest.colourlife.com"){
  rk_url='http://entrance.czytest.colourlife.com';
}else if(url=="entrance.colourlife.com"){
  rk_url ='http://entrance.colourlife.com';
}else if(url=="localhost"){
  rk_url='http://entrance.czytest.colourlife.com';
}else if(url=="entrance.czybeta.colourlife.com"){
  rk_url='http://entrance.czybeta.colourlife.com';
}
//登录接口
let instance1 = axios.create({
  headers: {'content-type': 'application/x-www-form-urlencoded'}
});
axios.interceptors.request.use(
  config => {
    // 这里写死一个token，你需要在这里取到你设置好的token的值
    let user=sessionStorage.getItem('access-user');
    let access_token;
    if (user){
      user = JSON.parse(user);
      access_token=user.access_token;
    }else{
      access_token='';
    }
    const token = access_token;
    if (token) {
      if(config.url.indexOf("access_token")>-1){
        config.url=`${config.url}`;
      }else{
        config.url=`${config.url}?access_token=${token}`;
      }
    }
    return config
  },
  error => {
    return Promise.reject(error)
  });
/////
// http request 拦截器
// axios.interceptors.request.use(
//   config => {
//     console.log(2);
//     let user = JSON.parse(window.sessionStorage.getItem('access-user'));
//     if (user) {  // 判断是否存在token，如果存在的话，则每个http header都加上token
//       user = JSON.parse(user);
//       let access_token=user.access_token;
//       config.headers.Authorization = `token ${access_token}`;
//     }
//     return config;
//   },
//   err => {
//     return Promise.reject(err);
//   });
// // http response 拦截器
// axios.interceptors.response.use(
//   response => {
//     return response;
//   },
//   error => {
//     if (error.response) {
//       switch (error.response.status) {
//         case 401:
//           // 返回 401 清除token信息并跳转到登录页面
//           store.commit(types.LOGOUT);
//           router.replace({
//             path: 'login',
//             query: {redirect: router.currentRoute.fullPath}
//           })
//       }
//     }
//     return Promise.reject(error.response.data)   // 返回接口返回的错误信息
//   });
///////
// axios.interceptors.request.use(function (config) {
//   // Do something before request is sent
//   let user=sessionStorage.getItem('access-user');
//   let access_token;
//   if (user){
//     user = JSON.parse(user);
//     access_token=user.access_token;
//   }else{
//     access_token='';
//   }
//   console.log('开始请求');
//   console.log(config);
//   console.log(`请求地址: ${config.url}?access_token=${access_token}`);
//   return config
// }, function (error) {
//   // Do something with request error
//   console.log('请求失败');
//   return Promise.reject(error);
// })
// axios.interceptors.response.use(function (config) {
//   // Do something before request is sent
//   console.log('接收响应');
//   return config
// }, function (error) {
//   // Do something with request error
//   console.log('响应出错');
//   return Promise.reject(error);
// });
////
export const backLogin = params => { return axios.post(`${rk_url}/employee/login`, qs.stringify(params)).then(res => res.data) };
//软硬入口管理
export const backActivityList = params => { return axios.get(`${rk_url}/backend/activity/list`,params).then(res => res.data) };
//考核基数
export const backOrginfoList = params => { return axios.get(`${rk_url}/backend/orginfo/list`,params).then(res => res.data) };
//编辑日期是否考核
export const backendActivityEdit = params => { return axios.post(`${rk_url}/backend/activity/edit`, qs.stringify(params)).then(res => res.data) };
//修改考核基数
export const orginfoEdit = params => { return axios.post(`${rk_url}/backend/orginfo/edit`, qs.stringify(params)).then(res => res.data) };
//操作日志
export const operationLogList = params => { return axios.get(`${rk_url}/backend/operation/log/list`,params).then(res => res.data) };
//获取大区
export const backendOrgRegion = params => { return axios.get(`${rk_url}/backend/org`,params).then(res => res.data) };
//获取事业部
export const backendOrgBranch = params => { return axios.get(`${rk_url}/backend/org/branch`,params).then(res => res.data) };
//获取小区
export const backendOrgCommunity= params => { return axios.get(`${rk_url}/backend/org/community`,params).then(res => res.data) };
//获取入口
export const backendCategory= params => { return axios.get(`${rk_url}/backend/category`,params).then(res => res.data) };
//导出日志
export const backendOperationLogExport= params => { return axios.get(`${rk_url}/backend/operation/log/export`,params).then(res => res.data) };
//活跃度排行
export const backendActivityTop= params => { return axios.get(`${rk_url}/backend/activity/top`,params).then(res => res.data) };
//活跃度占比
export const backendActivityRate= params => { return axios.get(`${rk_url}/backend/activity/rate`,params).then(res => res.data) };
//获取入口类别列表
export const backendCategoryList= params => { return axios.get(`${rk_url}/backend/category/list`,params).then(res => res.data) };
//添加入口类别列表
export const backendCategoryAdd = params => { return axios.post(`${rk_url}/backend/category/add`, qs.stringify(params)).then(res => res.data) };
//编辑入口类别列表
export const backendCategoryEdit = params => { return axios.post(`${rk_url}/backend/category/edit`, qs.stringify(params)).then(res => res.data) };
//登录员工列表
export const backendEmployeeList = params => { return axios.get(`${rk_url}/backend/employee/list`,params).then(res => res.data) };
//员工编辑
export const backendEmployeeEdit = params => { return axios.post(`${rk_url}/backend/employee/edit`, qs.stringify(params)).then(res => res.data) };
//折线图，柱状图
export const backendActivityOrg = params => { return axios.get(`${rk_url}/backend/activity/org`,params).then(res => res.data) };
