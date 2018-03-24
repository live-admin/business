<?php
/**
 * Created by PhpStorm.
 * User: Roy
 * Date: 2016/10/24
 * Time: 16:23
 */
class ErrorCode {

    // 参数错误
    const PARAM_VALUE_EMPTY = 1001; // 参数为空
    const PARAM_TYPE_ERROR  = 1002; // 参数类型错误
    const PARAM_VALUE_ERROR  = 1003; // 参数值错误

    // 业务逻辑错误
    const LOGIC_ERROR = 2001; 

    // API接口错误
    const API_REQUEST_FAIL     = 3001; // 请求错误
    const API_RESPONSE_FAIL    = 3002; // 响应错误

    // 数据库类型错误
    const DB_SELECT_FAIL = 4001; // 查询数据不存在
    const DB_UPDATE_FAIL = 4002; // 更新数据失败
    const DB_INSERT_FAIL = 4003; // 写入失败
    const DB_DELETE_FAIL = 4004; // 删除失败
}