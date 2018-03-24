/**
 * Created by jerry on 2017/4/13.
 */
import axios from 'axios'
import qs from 'qs';

let base;
let url=document.domain;

if(url == "evisit.czytest.colourlife.com"){//测试
  base = 'http://evisit.czytest.colourlife.com';
}else if(url == "evisit.colourlife.com"){//正式
  base = 'http://evisit.colourlife.com';
}else if(url=="localhost"){//本地
  base = 'http://evisit.czytest.colourlife.com';
}else if(url == "evisit-czybeta.colourlife.com"){//预发
  base = 'http://evisit-czybeta.colourlife.com';
}

export default base
//登录接口
export const eJF_Login = params => { return axios.post(`${base}/backend/login`, qs.stringify(params)).then(res => res.data) }
//1.1、员工列表
export const employeeList = params => { return axios.get(`${base}/backend/employee/list`, { params: params }).then(res => res.data)}
//1.2、员工详情
export const employeeDetail = params => { return axios.get(`${base}/backend/employee/details`, { params: params }).then(res => res.data)}
//1.3、员工授权
export const privilege = params => { return axios.post(`${base}/backend/employee/privilege`, qs.stringify(params)).then(res => res.data) }
//1.4 获取员工可见小区列表
export const getCommunity = params => { return axios.get(`${base}/backend/employee/community`, { params: params }).then(res => res.data)}
//1.5、增加员工可见小区
export const addVisibleCommunity = params => { return axios.post(`${base}/backend/employee/add/community`, qs.stringify(params)).then(res => res.data) }
//1.6、删除员工可见小区
export const delVisibleCommunity = params => { return axios.post(`${base}/backend/employee/del/community`, qs.stringify(params)).then(res => res.data) }

//2.1、oa绑定彩之云账号列表
export const oaBindList = params => { return axios.get(`${base}/backend/oa/list`, { params: params }).then(res => res.data)}
//2.2、oa绑定彩之云账号详情
export const oaBindDetail= params => { return axios.get(`${base}/backend/oa/details`, { params: params }).then(res => res.data)}
//2.3、解绑OA彩之云账号)
export const oaUnbind = params => { return axios.post(`${base}/backend/oa/unbind`, qs.stringify(params)).then(res => res.data) }
//2.4、导出OA绑定彩之云账号列表
// export const oaExport= params => { return axios.get(`${base}/backend/oa/export`, { params: params }).then(res => res.data)}

//3.1、获取绑定专属客户经理列表
export const managerList= params => { return axios.get(`${base}/backend/manager/list`, { params: params }).then(res => res.data)}
//3.2、获取绑定专属客户经理详情
export const managerDetail= params => { return axios.get(`${base}/backend/manager/details`, { params: params }).then(res => res.data)}
//3.3、解绑/绑定专属客户经理
export const managerUnbind = params => { return axios.post(`${base}/backend/manager/unbind`, qs.stringify(params)).then(res => res.data) }
// 3.4、导出绑定专属客户经理列表
// export const export= params => { return axios.get(`${base}/backend/manager/export`, { params: params }).then(res => res.data)}

//4.1、获取小区业主评价列表
export const commentList= params => { return axios.get(`${base}/backend/comment/list`, { params: params }).then(res => res.data)}
//4.2、获取小区业主评价详情
export const commentDetail= params => { return axios.get(`${base}/backend/comment/details`, { params: params }).then(res => res.data)}

//5.1、搜索组织架构（根据父节点）
export const org= params => { return axios.get(`${base}/backend/org`, { params: params }).then(res => res.data)}
//5.2、搜索小区
export const community= params => { return axios.get(`${base}/backend/community`, { params: params }).then(res => res.data)}



