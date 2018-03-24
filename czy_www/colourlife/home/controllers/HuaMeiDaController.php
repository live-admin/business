<?php

/*
 * @version 华美达酒店
 * @copyright(c) josen 2015-09-15
 */
class HuaMeiDaController extends CController {
    private $_userId=0;
    
    public function actionIndex() {
        $userid=$this->checkLogin();
        $re = new SetableSmallLoans();
       // $dayurl=$re->getStartImg(39);
        $dayurl=$re->searchByIdAndType(39, 1, $userid);
        
        if(!empty($dayurl)){
            $sql="select * from cheap_log where goods_id=20883";
            $queryArr=Yii::app()->db->createCommand($sql)->queryAll();
            $cheap_id=$queryArr[count($queryArr)-1]['id'];
            $url = $dayurl->completeURL . '&pid=' . $cheap_id;
        }
        $this->renderPartial("jiuDian",array('url'=>$url));
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

