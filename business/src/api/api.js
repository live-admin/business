/**
 * Created by jerry on 2017/4/13.
 */
import axios from 'axios'
import qs from 'qs';
//商户接口
// let sh_url ='http://check.account.colourlife.com';
// let sh_url='http://check.account.czytest.colourlife.com';
let sh_url,fz_url;
let url=document.domain;
console.log(url);
if(url=="check.account.czytest.colourlife.com"){
  sh_url='http://check.account.czytest.colourlife.com';
  fz_url="http://fzsvr-czytest.colourlife.com";
  //fz_url='http://account.allot.test.colourlife.com'
}else if(url=="check.account.colourlife.com"){
  sh_url ='http://check.account.colourlife.com';
  fz_url='http://account.allot.colourlife.com';
  //fz_url="http://fzsvr-czytest.colourlife.com";
}else if(url=="localhost"){
  sh_url='http://check.account.czytest.colourlife.com';
  fz_url="http://fzsvr-czytest.colourlife.com";
  //fz_url='http://account.allot.test.colourlife.com'
}
//sh_url='http://business.admin.czylocal.com';
//fz_url="http://splitbill.czylocal.com";
//分账url
// let fz_url='http://account.allot.colourlife.com';
// let fz_url="http://fzsvr-czytest.colourlife.com";
let instance = axios.create({
  headers: {'content-type': 'application/x-www-form-urlencoded'}
});
//订单搜索
export const backendOrderOrg = params => { return instance.get(`${sh_url}/backend/order/org`,params).then(res => res.data) };
//列表导出
export const backendOrderExcel = params => { return instance.get(`${sh_url}/backend/order/excel`,params).then(res => res.data) };
//用户登录
export const SH_Login = params => { return instance.post(`${sh_url}/employee/login`, qs.stringify(params)).then(res => res.data) };
//获取子列表权限
export const backendRolePrivilegeRole = params => { return instance.post(`${sh_url}/backend/role/privilege/role`, qs.stringify(params)).then(res => res.data) };
//支付列表
export const backendPaymentList = params => { return instance.post(`${sh_url}/backend/payment/list`, qs.stringify(params)).then(res => res.data) };
//获取商户类目
export const backendBusinessGeneralList = params => { return instance.post(`${sh_url}/backend/business/general/list`, qs.stringify(params)).then(res => res.data) };
//支付——新增
export const paymentAdd = params => { return instance.post(`${sh_url}/payment/add`, qs.stringify(params)).then(res => res.data) };
//支付——修改支付方式
export const paymentEdit = params => { return instance.post(`${sh_url}/payment/edit`, qs.stringify(params)).then(res => res.data) };
//提现列表
export const backendWithdrawsList = params => { return instance.post(`${sh_url}/backend/withdraws/list`, qs.stringify(params)).then(res => res.data) };
//提现——详情
export const adminWthdrawsView = params => { return instance.post(`${sh_url}/admin/withdraws/view`, qs.stringify(params)).then(res => res.data) };
//提现通知
export const adminNotify = params => { return instance.post(`${sh_url}/admin/withdraws/notify`, qs.stringify(params)).then(res => res.data) };
//订单通知
export const adminOrderNotify = params => { return instance.post(`${sh_url}/admin/order/notify`, qs.stringify(params)).then(res => res.data) };
//业务通知
export const adminOrderCallback = params => { return instance.post(`${sh_url}/admin/order/callback`, qs.stringify(params)).then(res => res.data) };
//提现接口获取金额
export const adminWithdrawsBalance = params => { return instance.post(`${sh_url}/admin/withdraws/balance`, qs.stringify(params)).then(res => res.data) };
//提现接口操作提现
export const adminWithdrawsIndexindex = params => { return instance.post(`${sh_url}/admin/withdraws/index`, qs.stringify(params)).then(res => res.data) };
//退款列表接口
export const backendRefundListd = params => { return instance.post(`${sh_url}/backend/refund/list`, qs.stringify(params)).then(res => res.data) };
//退款接口
export const adminRefund = params => { return instance.post(`${sh_url}/admin/refund`, qs.stringify(params)).then(res => res.data) };
//退款查看【新增】
export const adminQuery = params => { return instance.post(`${sh_url}/admin/query`, qs.stringify(params)).then(res => res.data) };
//权限列表
export const backendPrivilegeList = params => { return instance.post(`${sh_url}/backend/privilege/list`, qs.stringify(params)).then(res => res.data) };
//角色管理列表
export const backendRoleList = params => { return instance.post(`${sh_url}/backend/role/list`, qs.stringify(params)).then(res => res.data) };
//角色新增
export const backendRoleAdd = params => { return instance.post(`${sh_url}/backend/role/add`, qs.stringify(params)).then(res => res.data) };
//模糊搜索员工
export const EmployeeApiSearch = params => { return instance.post(`${sh_url}/backend/employee/api/search`, qs.stringify(params)).then(res => res.data) };
//员工OA账号绑定与角色（删除oa账号修改）
export const employeeUpdateRole = params => { return instance.post(`${sh_url}/backend/employee/update/role`, qs.stringify(params)).then(res => res.data) };
//绑定oa账号
export const employeeAddEmployee = params => { return instance.post(`${sh_url}/backend/employee/add/employee`, qs.stringify(params)).then(res => res.data) };
//查看已绑定角色OA账号
export const employeeGetRole = params => { return instance.post(`${sh_url}/backend/employee/get/role`, qs.stringify(params)).then(res => res.data) };
//删除员工角色绑定
export const employeeUpdateStatus = params => { return instance.post(`${sh_url}/backend/employee/update/status`, qs.stringify(params)).then(res => res.data) };
export const backendEmployeeDel = params => { return instance.post(`${sh_url}/backend/employee/del`, qs.stringify(params)).then(res => res.data) };
//查看已绑定商户接口
export const rolePrivilegeBusiness = params => { return instance.post(`${sh_url}/backend/role/privilege/business`, qs.stringify(params)).then(res => res.data) };
//查看已绑定权限列表
export const RolePrivilegeBusinessRole = params => { return instance.post(`${sh_url}/backend/role/privilege/business/role`, qs.stringify(params)).then(res => res.data) };
//角色搜索
export const backendRoleSearch = params => { return instance.post(`${sh_url}/backend/role/search`, qs.stringify(params)).then(res => res.data) };
//审核不通过
//模糊获取组织架构
export const backendRolePrivilegeOrg = params => { return instance.post(`${sh_url}/backend/role/privilege/org`, qs.stringify(params)).then(res => res.data) };
export const AdminBusinessExamine = params => { return instance.post(`${sh_url}/admin/business/examine`, qs.stringify(params)).then(res => res.data) };
//角色和商户ID绑定
export const RolePrivilegeAdd = params => { return instance.post(`${sh_url}/backend/role/privilege/add`, qs.stringify(params)).then(res => res.data) };
//删除角色商户绑定关系
export const rolePrivilegeDel = params => { return instance.post(`${sh_url}/backend/role/privilege/del`, qs.stringify(params)).then(res => res.data) };
//父级获取子级权限
export const backendPrivilegeParent = params => { return instance.post(`${sh_url}/backend/privilege/parent`, qs.stringify(params)).then(res => res.data) };
//商户模糊搜索
export const businessSearch = params => { return instance.post(`${sh_url}/backend/business/search`, qs.stringify(params)).then(res => res.data) };
//商户管理列表
export const backendBusinessList = params => { return instance.post(`${sh_url}/backend/business/list`, qs.stringify(params)).then(res => res.data) };
//商户状态更改接口admin/business/status
export const adminBusinessStatus = params => { return instance.post(`${sh_url}/admin/business/status`, qs.stringify(params)).then(res => res.data) };
//商户详情
export const AdminBusinessView = params => { return instance.post(`${sh_url}/admin/business/view`, qs.stringify(params)).then(res => res.data) };
//查看商户 启用，审核通过 列表供分账系统用
export const BackendBusinessStatusList = params => { return instance.post(`${sh_url}/backend/business/status/list`, qs.stringify(params)).then(res => res.data) };
//订单管理列表
export const OrderList = params => { return instance.post(`${sh_url}/backend/order/list`,qs.stringify(params)).then(res => res.data)};
//订单详情
export const AdminOrderView = params => { return instance.post(`${sh_url}/admin/order/view`,qs.stringify(params)).then(res => res.data)};
//支付列表
export const backendPayList = params => { return instance.post(`${sh_url}/backend/pay/list`, qs.stringify(params)).then(res => res.data) };
//GMV看板
export const backendOrderSum = params => { return instance.post(`${sh_url}/backend/order/sum`, qs.stringify(params)).then(res => res.data) };
//2018年3月5日新增接口【商户】
//商户编辑接口
export const BusinessEdit = params => { return instance.post(`${sh_url}/admin/business/edit`, qs.stringify(params)).then(res => res.data) };
//提现审核接口
export const WithdrawsAuditing = params => { return instance.post(`${sh_url}/backend/withdraws/auditing`, qs.stringify(params)).then(res => res.data) };
//退款审核接口
export const RefundAuditing = params => { return instance.post(`${sh_url}/backend/refund/auditing`, qs.stringify(params)).then(res => res.data) };
//商户对账接口
export const TransactionBill = params => { return instance.post(`${sh_url}/backend/transaction/bill`, qs.stringify(params)).then(res => res.data) };
//商户账单信息接口
export const TransactionBalance = params => { return instance.post(`${sh_url}/backend/transaction/balance`, qs.stringify(params)).then(res => res.data) };
//2018年3月17日添加提现接口
export const backendWithdrawsMeal = params => { return instance.post(`${sh_url}/backend/withdraws/meal`, qs.stringify(params)).then(res => res.data) };
export const backendWithdrawsCash = params => { return instance.post(`${sh_url}/backend/withdraws/cash`, qs.stringify(params)).then(res => res.data) };
//【分账部分接口】
//分账规则--列表--查看
export const RuleList = params => { return instance.post(`${fz_url}/backend/rule/list`, qs.stringify(params)).then(res => res.data) };
//科目列表
export const TagList = params => { return instance.post(`${fz_url}/backend/tag/list`, qs.stringify(params)).then(res => res.data) };
//分账规则新增
export const RuleAdd = params => { return instance.post(`${fz_url}/backend/rule/add`, qs.stringify(params)).then(res => res.data) };
//分账规则修改禁用
export const RuleEdit = params => { return instance.post(`${fz_url}/backend/rule/edit`, qs.stringify(params)).then(res => res.data) };
//岗位--列表
export const JobList = params => { return instance.post(`${fz_url}/backend/job/list`, qs.stringify(params)).then(res => res.data) };
//组织架构--列表--模糊搜索
export const OrgList = params => { return instance.post(`${fz_url}/backend/org/list`, qs.stringify(params)).then(res => res.data) };
//分成方--新增
export const SplitAdd = params => { return instance.post(`${fz_url}/backend/split/add`, qs.stringify(params)).then(res => res.data) };
//分成方--修改、禁用
export const SplitEdit = params => { return instance.post(`${fz_url}/backend/split/edit`, qs.stringify(params)).then(res => res.data) };
//分成方--列表--查看
export const SplitList = params => { return instance.post(`${fz_url}/backend/split/list`, qs.stringify(params)).then(res => res.data) };
//分成记录--列表--查看（根据商户类目、具体商户查询）
export const SplitLogList = params => { return instance.post(`${fz_url}/backend/split/log/list`, qs.stringify(params)).then(res => res.data) };
export const TagOrderList = params => { return instance.post(`${fz_url}/backend/tag/order/list`, qs.stringify(params)).then(res => res.data) };
//商户列表
export const BusinessList = params => { return instance.post(`${fz_url}/backend/business/list`, qs.stringify(params)).then(res => res.data) };
//商户已创建规则的科目列表（用于分账规则--新增--选择已有规则）
export const BusinessTagList = params => { return instance.post(`${fz_url}/backend/business/tag/list`, qs.stringify(params)).then(res => res.data) };
//兑换记录--列表--查看
export const backendWithdrawalsList = params => { return instance.get(`${fz_url}/backend/withdrawals/list`,params).then(res => res.data) };
//兑换详情
export const backendWithdrawals = params => { return instance.get(`${fz_url}/backend/withdrawals`,params).then(res => res.data) };
//兑换记录--列表--导出

//兑换记录--审核
export const backendHandlewithdrawals = params => { return instance.post(`${fz_url}/backend/handlewithdrawals`, qs.stringify(params)).then(res => res.data) };
//兑换记录--备注
export const backendWithdrawalsRemark = params => { return instance.post(`${fz_url}/backend/withdrawals/remark`, qs.stringify(params)).then(res => res.data) };
//分成记录--订单--列表--查看
export const backendOrderList = params => { return instance.get(`${fz_url}/backend/order/list`,params).then(res => res.data) };
//兑换搜索商户类目
export const backendAppdetail = params => { return instance.get(`${fz_url}/backend/appdetail`,params).then(res => res.data) };

//获取对私订单列表
export const privateList= params => { return instance.get(`${fz_url}/backend/private/list`,params).then(res => res.data) };
//获取对用对私订单对应的分配记录
export const privateLog= params => { return instance.get(`${fz_url}/backend/private/log`,params).then(res => res.data) };
