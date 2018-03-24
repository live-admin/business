<?php
/**
 * 活动入口控制器.
 * User: Joy
 * Date: 2015/7/29
 * Time: 14:08
 */

class ActivityController extends CController {

    private $_userId = '';
    private $_username;
    private $_community_id;


    /**
     * 七夕活动 活动
     * @throws CException
     */
    public function actionQixi()
    {
        $this->checkLogin();

        $SetableSmallLoansModel = new SetableSmallLoans();
        $href = $SetableSmallLoansModel->searchByIdAndType(38, '', $this->_userId);

        if ($href) {
            $goodsHref = $href->completeURL;
        }
        else {
            $goodsHref = '';
        }

        $href = $SetableSmallLoansModel->searchByIdAndType(67, '', $this->_userId);
        if ($href) {
            $jdHref = $href->completeURL;
        }
        else {
            $jdHref = '';
        }

        $this->renderPartial("qixi", array(
            'goodsHref' => $goodsHref,
            'jdHref' => $jdHref,
            "custId" => $this->_userId,
        ));
    }



    /**
     * 一起"蜂"一夏 活动
     * @throws CException
     */
    public function actionFeng()
    {
        $this->checkLogin();

        $SetableSmallLoansModel = new SetableSmallLoans();
        $href = $SetableSmallLoansModel->searchByIdAndType(38, '', $this->_userId);
        if ($href) {
            $goodsHref = $href->completeURL;
        }
        else {
            $goodsHref = '';
        }

        $this->renderPartial("feng", array(
            'goodsHref' => $goodsHref
        ));
    }

    /*
     * 检查登录
     */
    private function checkLogin() {
        $cust_id = floatval(Yii::app()->request->getParam('cust_id'));
        if (empty($cust_id) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }

        if (empty($_SESSION['userid'])) {
            $_SESSION['userid'] = $cust_id;
        }
        if (empty($cust_id)) {
            $cust_id = $_SESSION['userid'];
        }

        $customer = Customer::model()->findByPk($cust_id);
        if (empty($customer)) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }

        $this->_userId = $cust_id;
        $this->_username = $customer->username;
        $this->_community_id = $customer->community_id;
    }

    /**
     * 云海天城世家
     * 注册邀请送饭票
     */
    private $inviteStartTime = '2015-08-05 00:00:00';       //活动开始时间
    private $inviteEndTime = '2015-10-31 23:59:59';           //活动结束时间
    private $inviteCommunityID= 1475; // 活动小区
    private $inviteNum = 800;


    /**
     * 活动首页
     * @throws CException
     */
    public function actionInviteIndex()
    {
        $this->checkLogin();
        $this->renderPartial("invite/index",array(
            'cust_id' => $this->_userId
        ));
    }

    public function actionInvite()
    {
        $this->checkLogin();
        $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : "";

        if (!preg_match('/^(1[3456789])[0123456789]{9}$/', $mobile)) {
            echo CJSON::encode(array('code' => '您输入的手机号码有误。'));
        }
        else {
            $check = $this->inviteCheck();
            if ( true !== $check) {
                echo CJSON::encode($check);
            }
            else {
                $customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $mobile));
                if (!isset($customer)) {
                    $invite = Invite::model()->find('mobile=:mobile and model="customer" and create_time<=:create_time  and valid_time>=:valid_time', array(':mobile' => $mobile,
                        ':create_time' => time(), ':valid_time' => time()));

                    if (isset($invite)&&$invite->customer_id!=$this->_userId) {
                        echo CJSON::encode(array('code' => '您邀请的好友已经被其他人邀请了，暂时不能邀请。'));
                    } else {
                        if(!isset($invite)){
                            $sms = Yii::app()->sms;
                            $sms->name = $this->_username;
                            $sms->setType('invite', array('mobile' => $mobile));
                            $sms->sendUserMessage('inviteTemplate');

                            $model = Invite::model()->find('mobile=:mobile and model = "customer" and customer_id=:customer_id',
                                array(':mobile' => $mobile, ':customer_id' => $this->_userId));
                            if(!$model){
                                $model = new Invite();
                            }
                            $model->customer_id = $this->_userId;
                            $model->mobile = $mobile;
                            $model->create_time = time();
                            $model->valid_time = time() + intval(Yii::app()->config->invite['validTime']);
                            if(!$model->save()){
                                echo CJSON::encode(array('code' => '您的邀请没有正常发出，请重试。'));
                            }else{
                                echo CJSON::encode(array(
                                    'code' => 'success'
                                ));
                            }
                        }else{
                            if($invite->create_time <= (time() - 120)){
                                $invite->create_time = time();
                                $invite->save();
                                $sms = Yii::app()->sms;
                                $sms->name = $this->_username;
                                $sms->setType('invite', array('mobile' => $mobile));
                                $sms->sendUserMessage('inviteTemplate');
                                echo CJSON::encode(array('code' => 'success'));
                            }else{
                                echo CJSON::encode(array('code' => '您已经邀请过好友了，如果您的好友未收到短信，请2分钟后重试或邀请其他好友注册拿红包。'));
                            }
                        }
                    }
                }
                else {
                    echo CJSON::encode(array('code' => '很抱歉，您的好友已经注册了，您可以邀请其他好友注册拿红包。'));
                }
            }
        }
    }

    /**
     * 邀请记录
     */
    public function actionInviteLog()
    {
        $this->checkLogin();

        $records = Invite::model()->findAll("customer_id=:customer_id and model='customer' and create_time >= '".strtotime($this->inviteStartTime)."' and create_time <= '".strtotime($this->inviteEndTime)."' order by create_time desc",
            array(':customer_id'=>$this->_userId));

        $this->renderPartial("invite/yqjl", array(
            'cust_id' => $this->_userId,
            'records' => $records
        ));

    }

    /**
     * 成功邀请记录
     */
    public function actionInviteSuccess()
    {
        $this->checkLogin();

        $records = Invite::model()->findAll("customer_id=:customer_id and model='customer' and status=1 and create_time >= '".strtotime($this->inviteStartTime)."' and create_time <= '".strtotime($this->inviteEndTime)."' order by create_time desc",
            array(':customer_id'=>$this->_userId));

        $this->renderPartial("invite/cgyq", array(
            'cust_id' => $this->_userId,
            'records' => $records
        ));
    }

    /**
     * 活动规则
     * @throws CException
     */
    public function actionInviteInfo()
    {
        $this->checkLogin();
        $this->renderPartial("invite/hdgz");
    }

    private function inviteCheck()
    {
        if (intval($this->_community_id) !== $this->inviteCommunityID) {
            return array('code' => '您不属于活动区域，不能参数本次活动。');
        }

        if (time() > strtotime($this->inviteEndTime) || time() < strtotime($this->inviteStartTime)) {
            return array('code' => '不在活动时间内');
        }

        $count = Invite::model()->count("status=1 and effective=1 and model='customer' and state=1 and
             create_time >= '".strtotime($this->inviteStartTime)."' and create_time <= '".strtotime($this->inviteEndTime)."' order by create_time asc");

        if ($count >= $this->inviteNum) {
            return array('code' => '本次活动已达到邀请人数限制。');
        }

        return true;
    }
}