<?php
/*
 * 品牌发布会
 */
class HouseConferenceController extends CController {

    private $_username = '';
    private $_userId = '';

    /**
     * 嘉宾页面
     */
    public function actionHonored() {
        $this->checkLogin();
//        $criteria=new CDbCriteria;
//        $criteria->condition='customer_id=:cid';
//        $criteria->params=array(':cid'=>$this->_userId);
//        $criteria->order='id desc';
//        $model=Visit::model()->find($criteria);
//
//        $count=Visit::model()->count($criteria);
        //一元购地址
        $re = new SetableSmallLoans();
        $href = $re->searchByIdAndType(57, '', $this->_userId);
        if ($href)
            $url = $href->completeURL;
        else
            $url = ''; //dump($url);
        $this->renderPartial("honored", array(
            'oneBuyHref' => $url
        ));
    }

    /**
     * 嘉宾页面
     */
    public function actionReport() {
        $this->checkLogin();
        //一元购地址
        $re = new SetableSmallLoans();
        $href = $re->searchByIdAndType(57, '', $this->_userId);
        if ($href)
            $url = $href->completeURL;
        else
            $url = '';
        $this->renderPartial("reporter", array(
            'oneBuyHref' => $url
        ));
    }

    /*
     * 获取码
     */

    function actionGetCode() {
        if (empty($_SESSION['userid'])) {
            echo json_encode(array('suc' => 1));
            exit;
        } else
        //$uid = $_SESSION['userid'];
        $uid=intval($_SESSION['userid']);
        $customer = Customer::model()->findByPk($uid);
        /* $type = Yii::app()->request->getParam('type');
        $code = Yii::app()->request->getParam('code'); */
        $type=intval(Yii::app()->request->getParam('type'));
        $code=intval(Yii::app()->request->getParam('code'));
        if (!$code) {
            echo json_encode(array('suc' => 4));
            exit;
        }
        if ($type == 1)
            $type = 1;
        else
            $type = 2;
        if (!$customer) {
            echo json_encode(array('suc' => 1));
            exit;
        }
        $mobile = $customer->mobile;
        $object = NewsConference::model()->find('code=:code AND type=:type', array(':code' => $code, ':type' => $type));
        //得到未使用码
        $oneYuan = OneYuanBuy::model()->find(array('condition' => 'is_send=:is_send AND is_use=:is_use AND state=:state AND customer_id=:customer_id'
            , 'params' =>array(':is_send'=>0, ':is_use'=>0, 'state'=>0, ':customer_id'=>'')
              ,'limit'=> '1'));//dump($oneYuan);
        if ($object && $oneYuan) {
            if (empty($object->mobile)) {
                $object->mobile = $mobile;
                $object->update_time = time();
                if ($object->update()) {
                    //更新一元购使用码
                    $oneYuan->goods_id = 1619;
                    $oneYuan->is_send = 1;
                    $oneYuan->customer_id = $uid;
                    $oneYuan->send_time = time();
                    $oneYuan->update_time = time();
                    $oneYuan->valid_time = strtotime('2015-06-30 23:59:59');
                    $oneYuan->model = 'news_report';
                    if ($oneYuan->save()){
                        //红包发放 $type == 1发放红包
                        if ($type == 1){
                            $items = array(
                                 'customer_id' => $uid,//业主的ID
                                 'sum' => 500,//红包金额
                                 'sn' => 'news_report',
                                 'from_type' => Item::RED_PACKET_FROM_TYPE_NEWS_REPORT,
                             );
                             $redPacked = new RedPacket();
                             if($redPacked->addRedPacker($items)){
                                echo json_encode(array('suc' => 3, 'msg' => $object->code));
                                exit;
                             }
                        } else {
                              echo json_encode(array('suc' => 3, 'msg' => $object->code));
                              exit;
                        }
                    }
                    echo json_encode(array('suc' => 4));
                } else
                    echo json_encode(array('suc' => 4));
            } else
                echo json_encode(array('suc' => 2));
        } else {
            echo json_encode(array('suc' => 4));
        }
    }
    
    
    /*
     * 检查登录
     */
    private function checkLogin() {
        $cust_id = floatval(Yii::app()->request->getParam('cust_id'));
        if (empty($cust_id)){
            $cust_id = floatval(Yii::app()->request->getParam('csh'));
            $cust_id = ($cust_id+1778)/178;
        }
        if (empty($cust_id) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }

        if (empty($_SESSION['userid'])) $_SESSION['userid'] = $cust_id;
        else  $cust_id = $_SESSION['userid'];
        $customer = Customer::model()->findByPk($cust_id);
        if (empty($customer)) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }
        
        $this->_userId = $cust_id;
        $this->_username = $customer->username;
        //}
    }

}
