<?php
/**
 * Created by PhpStorm.
 * User: Explorer
 * Date: 2016/8/11
 * Time: 16:21
 */

class SignController extends  Controller{
    private $_userId=0;


    private function checkLogin()
    {
        if ((empty($_REQUEST['userid']) && empty($_SESSION['userid'])) && (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id']))) {
            $this->redirect('http://mapp.colourlife.com/m.html');
            exit();
        } else {
            $custId = 0;

            if (isset($_REQUEST['userid'])) {  //优先有参数的
                $custId = intval($_REQUEST['userid']);
                $_SESSION['userid'] = $custId;
            } else if (isset($_SESSION['userid'])) {  //没有参数，从session中判断
                $custId = $_SESSION['userid'];
            }else if (isset($_REQUEST['cust_id'])) {  //优先有参数的
                $custId = intval($_REQUEST['cust_id']);
                $_SESSION['cust_id'] = $custId;
            } else if (isset($_SESSION['cust_id'])) {  //没有参数，从session中判断
                $custId = $_SESSION['cust_id'];
            }
            $custId=intval($custId);
            $customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if (empty($custId) || empty($customer)) {
                $this->redirect('http://mapp.colourlife.com/m.html');
                exit();
            }
            $this->_userId = $custId;
        }
    }

    public function actionIndex(){
        $this->checkLogin();
        $customer_id = $this->_userId;
        $this->renderPartial('index', array('customer_id'=>$customer_id));
    }

    public function actionCommit(){
        $customer_id = Yii::app()->request->getParam('customer_id');
        $address = Yii::app()->request->getParam('address');
        Yii::import('common.models.Sign');

        if($this->isSign($customer_id)){
            echo json_encode(array('status'=>0,'param'=>'您今天已经签到过了！'));
            return 0;
        }
        $time = time();
        $sign = new Sign();
        $sign->customer_id = $customer_id;
        $sign->create_time = $time;
        $sign->address = $address;
        if($sign->save()){
            echo json_encode(array('status'=>1,'param'=>'签到成功！'));
            return 0;
        }else{
            echo json_encode(array('status'=>0,'param'=>'签到失败'));
            return 0;
        }
    }

    protected function isSign($customer_id){
        $tem = false;
        $daystar = strtotime("today");//今日零点的时间戳
        $dayend = $daystar+"86400";//明日零点(今日24点）的时间戳
        Yii::import('common.models.Sign');
        $model = Sign::model();
        $isSign = $model->find('customer_id=:customer_id  and create_time>=:start_time and create_time<:end_time',
            array(':customer_id'=>$customer_id , ':start_time'=>$daystar , ':end_time'=>$dayend));
        if($isSign){
            $tem = true;
        }
        return $tem;

    }
}