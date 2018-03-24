/**
 * Created by jerry on 2017/4/13.
 */
import axios from 'axios'
import qs from 'qs';

let base = ''

export const requestLogin = params => { return axios.post(`${base}/login`, params).then(res => res.data) }

export const reqSaveUserProfile = params => { return axios.post(`${base}/user/profile`, params).then(res => res.data) }

export const reqGetUserList = params => { return axios.get(`${base}/user/list`, { params: params }) }

export const reqGetBookListPage = params => { return axios.get(`${base}/book/list`, { params: params }) }

export const reqDeleteBook = params => { return axios.get(`${base}/book/delete`, { params: params }) }

export const reqEditBook = params => { return axios.get(`${base}/book/edit`, { params: params }) }

export const reqBatchDeleteBook = params => { return axios.get(`${base}/book/batchdelete`, { params: params }) }

export const reqAddBook = params => { return axios.get(`${base}/book/add`, { params: params }) }

let rk_url='http://entrance.czytest.colourlife.com';
//登录接口
let instance = axios.create({
  headers: {'content-type': 'application/x-www-form-urlencoded'}
});
let user=sessionStorage.getItem('access-user');
let access_token;
if (user){
  user = JSON.parse(user);
  access_token=user.access_token;
}else{
  access_token='';
}
export const backLogin = params => { return instance.post(`${rk_url}/employee/login?access_token=`+access_token, qs.stringify(params)).then(res => res.data) };
//软硬入口管理
export const backActivityList = params => { return instance.get(`${rk_url}/backend/activity/list?access_token=`+access_token,params).then(res => res.data) };
//考核基数
export const backOrginfoList = params => { return instance.get(`${rk_url}/backend/orginfo/list?access_token=`+access_token,params).then(res => res.data) };
//修改考核基数
export const orginfoEdit = params => { return instance.post(`${rk_url}/backend/orginfo/edit?access_token=`+access_token, qs.stringify(params)).then(res => res.data) };
//操作日志
export const operationLogList = params => { return instance.get(`${rk_url}/backend/operation/log/list?access_token=`+access_token,params).then(res => res.data) };
//获取大区
export const backendOrgRegion = params => { return instance.get(`${rk_url}/backend/org?access_token=`+access_token,params).then(res => res.data) };
//获取事业部
export const backendOrgBranch = params => { return instance.get(`${rk_url}/backend/org/branch?access_token=`+access_token,params).then(res => res.data) };
//获取小区
export const backendOrgCommunity= params => { return instance.get(`${rk_url}/backend/org/community?access_token=`+access_token,params).then(res => res.data) };
//获取入口
export const backendCategory= params => { return instance.get(`${rk_url}/backend/category?access_token=`+access_token,params).then(res => res.data) };
//导出日志
export const backendOperationLogExport= params => { return instance.get(`${rk_url}/backend/operation/log/export?access_token=`+access_token,params).then(res => res.data) };
//活跃度排行
export const backendActivityTop= params => { return instance.get(`${rk_url}/backend/activity/top?access_token=`+access_token,params).then(res => res.data) };
//活跃度占比
export const backendActivityRate= params => { return instance.get(`${rk_url}/backend/activity/rate?access_token=`+access_token,params).then(res => res.data) };

