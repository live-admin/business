<?php
class CrabController extends CController {
	private $_userId = 0;

    public function actionIndex(){
        $this->checkLogin();
        $this->renderPartial("index");
    }


    private function checkLogin(){
        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }else {
            $custId=0;
            if(isset($_REQUEST['cust_id'])){  //优先有参数的
                $custId=intval($_REQUEST['cust_id']);
                $_SESSION['cust_id']=$custId;
            }else if(isset($_SESSION['cust_id'])){  //没有参数，从session中判断
                $custId=$_SESSION['cust_id'];
            }
            $custId=intval($custId);
            $customer=Customer::model()->findByPk($custId);
            if(empty($custId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
                $this->_userId = $custId; 
        }
    }



}