<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/4/22
 * Time: 16:59
 * E家政宣传
 */

class HouseKeepingController extends Controller{

    private $_userId=0;

    public function init(){
        $this->checkLogin();
    }
    /**
     * 验证登录
     */
    private function checkLogin()
    {
        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
            exit();
        } else {
            $custId = 0;
            if (isset($_REQUEST['userid'])) {  //优先有参数的
                $custId = intval($_REQUEST['userid']);
                $_SESSION['userid'] = $custId;
            } else if (isset($_SESSION['userid'])) {  //没有参数，从session中判断
                $custId = $_SESSION['userid'];
            }
            $custId=intval($custId);
            $customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if (empty($custId) || empty($customer)) {
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            $this->_userId = $custId;
        }
    }

    public function  actionIndex(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('ebaojie', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->renderPartial('index',array('url'=>$url));
    }
}