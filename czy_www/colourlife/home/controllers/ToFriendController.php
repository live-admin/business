<?php
/*
 * @version 推荐给好友
 * @copyright(c) josen 2015-05-08
 */
class ToFriendController extends CController {
//    private $_userId=0;
//    public function actionIndex() {
//        $userid=$this->checkLogin();
//        $re=new SetableSmallLoans();
//        $licai = $re->searchByIdAndType('LICAIYI',1,$userid,false);
//        $this->renderPartial("licai",array('licai_url'=>$licai->completeURL));
//    }
//    private function checkLogin(){
//        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
//            exit('<h1>用户信息错误，请重新登录</h1>');
//        }else {
//            $userId=0;
//            if(isset($_REQUEST['userid'])){
//                $userId=intval($_REQUEST['userid']);
//                $_SESSION['userid']=$userId;
//            }else if(isset($_SESSION['userid'])){
//                $userId=$_SESSION['userid'];
//            }
//            $customer=Customer::model()->findByPk($userId);
//            if(empty($userId) || empty($customer)){
//                exit('<h1>用户信息错误，请重新登录</h1>');
//            }
//            return $this->_userId = $userId;
//        }
//    }
}

