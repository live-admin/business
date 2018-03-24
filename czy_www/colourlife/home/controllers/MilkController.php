<?php
class MilkController extends CController {
	private $_userId = 0;
    //private $_username = '';
    private $_usermobile = 0;

    public function actionIndex(){
        $this->checkLogin();
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('market' , 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial("riceOil",array('url'=>$url));
    }

    public function actionHuaMeiDa(){
        $this->checkLogin();       
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('market' , 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial("huameida",array('url'=>$url));
    }



    public function actionMyInviteRecord(){
        $this->checkLogin();       
        $list=MilkInvite::model()->findAll("customer_id=".$this->_userId);
        //$count=MilkInvite::model()->count("customer_id=".$this->_userId);
        $this->renderPartial("record",
                array("list"=>$list)
        );
    }

    public function actionInvite(){
        $this->checkLogin();
        //var_dump($this->_usermobile);
        $this->renderPartial("invite");
    }


    public function actionRule(){
        $this->checkLogin();
        $this->renderPartial("rule");
    }



    public function actionInviteFriend(){
        $this->checkLogin();
        $mobile = isset($_POST['mobile'])?$_POST['mobile']:"";         
        $customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $mobile));
        $MilkInvite_model = MilkInvite::model()->find('mobile=:mobile', array(':mobile' => $mobile));
        if($this->_usermobile==trim($mobile)){
            echo CJSON::encode(array('code' => '您不能自己邀请自己。'));
        }else{
            if(!$MilkInvite_model){
                $sms = Yii::app()->sms;
                $sms->name = $this->_usermobile;
                $sms->setType('milkInvite', array('mobile' => $mobile));
                $smsresult=$sms->sendUserMessage('milkInviteTemplate'); 
                if($smsresult=='name lookup timed out'){
                    $milk_invitecreate_Model = new MilkInvite();
                    $milk_invitecreate_Model->customer_id = $this->_userId;
                    $milk_invitecreate_Model->mobile = $mobile;
                    $milk_invitecreate_Model->create_time = time();                    
                    if($customer){
                        $milk_invitecreate_Model->is_reg = 1;
                    }
                    $milk_invitecreate_Model->save();  
                }             
                echo CJSON::encode(array('code' => 'success'));
            }else{
                if($MilkInvite_model->is_buy==1){
                    echo CJSON::encode(array('code' => '您邀请的好友已经被其他人成功邀请了，您不能再邀请。'));
                }else{
                    if(time()-$MilkInvite_model->create_time>=24*60*60){
                        $sms = Yii::app()->sms;
                        $sms->name = $this->_usermobile;
                        $sms->setType('milkInvite', array('mobile' => $mobile));
                        $smsresult=$sms->sendUserMessage('milkInviteTemplate');
                        if($smsresult=='name lookup timed out'){
                            $milk_invitecreate_Model2 = new MilkInvite();
                            $milk_invitecreate_Model2->customer_id = $this->_userId;
                            $milk_invitecreate_Model2->mobile = $mobile;
                            $milk_invitecreate_Model2->create_time = time();                    
                            if($customer){
                                $milk_invitecreate_Model2->is_reg = 1;
                            }
                            $milk_invitecreate_Model2->save();
                        }
                        echo CJSON::encode(array('code' => 'success'));
                    }else{
                        echo CJSON::encode(array('code' => '您邀请的好友已经被其他人邀请了，24小时内不能再接受邀请。'));
                    }
                }

            }
        }    

    }




    public function actionMilkGoodsList_Simple(){
        $this->checkLogin();
        $this->renderPartial("list_simple");
    }


    public function actionMilkGoodsList_Group(){
        $this->checkLogin();
        $this->renderPartial("list_group");
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
            $this->_usermobile = $customer->mobile;
        }
    }

   

    

    // private function checkLogin(){
    //     if (Yii::app ()->user->isGuest) {
    //         $this->redirect ( Yii::app ()->user->loginUrl );
    //     }else {
    //         $this->_userId = Yii::app ()->user->id;
    //         $this->_username = Yii::app ()->user->name;
    //         $this->_usermobile = Yii::app ()->user->mobile;            
    //     }
    // }
        



}