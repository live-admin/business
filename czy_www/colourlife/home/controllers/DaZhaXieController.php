<?php
/*
 * @version 大闸蟹活动
 * @copyright(c) josen 2015-08-27
 */
class DaZhaXieController extends CController {
    private $_userId=0;
    /*
     * @version 大闸蟹首页
     */
    public function actionIndex() {
        $userid=$this->checkLogin();
        //优惠券列表
//        $detailArr=YouHuiQuanWeb::model()->getDetail($userid);

        $this->renderPartial("index",array('userid'=>$userid));
    }
    private function checkLogin(){
        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }else {
            $userId=0;
            if(isset($_REQUEST['userid'])){
                $userId=intval($_REQUEST['userid']);
                $_SESSION['userid']=$userId;
            }else if(isset($_SESSION['userid'])){
                $userId=$_SESSION['userid'];
            }
            $userId=intval($userId);
            $customer=Customer::model()->findByPk($userId);
            if(empty($userId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            return $this->_userId = $userId;
        }
    }
}

