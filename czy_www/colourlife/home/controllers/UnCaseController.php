<?php

/*
 * @version 非现金项目
 * @copyright(c) josen 2015-07-04
 */
class UnCaseController extends CController {

    
    public function actionIndex() {

        $this->checkLogin();
        $this->renderPartial("uncase");
    }

    public function actionWeiNiZhiFu() {

        $this->checkLogin();
        $this->renderPartial("weinizhifu");
    }
    private function checkLogin(){
        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }else {
            if(isset($_REQUEST['userid'])){
                $userId=intval($_REQUEST['userid']);
                $_SESSION['userid']=$userId;
            }else if(isset($_SESSION['userid'])){
                $userId=$_SESSION['userid'];
            }
            $customer=Customer::model()->findByPk($userId);
            if(empty($userId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
        }
         
    }
}

