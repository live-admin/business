<?php
/*
 * 星巴克活动
 */
class XBKActivityController extends CController {

//    private $_username = '';
//    private $_userId = '';
//    private $_goods_id = 9852;
//    public $xbkStartTime;
//    public $xbkEndTime;
//    public $xbkValidTime;
//    public $xbkStart;
//    public $xbk09Count;
//    public $xbk10Count;
//    public $xbk11Count;
//    public $xbk12Count;
//
//    public function getConfig(){
//        $re = Config::model()->findByPk(53);
//        // var_dump($re->className);die;
//        $over = null;
//        if ($re){
//            $over = unserialize($re->value);//dump($over);die;
//            foreach($over as $k => $v){
//                $this->{$k} = $v;
//            }
//        }
//        return $re;
//    }
//
//
//    public function setConfig($val){
//        $re = Config::model()->findByPk(53);
//        $over = null;
//        if ($re){
//            $over = unserialize($re->value);
//            foreach($over as $k => $v){
//                $this->{$k} = $v;
//            }
//            $aa = intval($this->{$val})-1;
//            $arr = array();
//            $arr['xbkStartTime'] = $this->xbkStartTime;
//            $arr['xbkEndTime'] = $this->xbkEndTime;
//            $arr['xbkValidTime'] = $this->xbkValidTime;
//            $arr['xbkStart'] = $this->xbkStart;
//            if($val=='xbk09Count') {
//                $arr['xbk09Count'] = $aa;
//                $arr['xbk10Count'] = $this->xbk10Count;
//                $arr['xbk11Count'] = $this->xbk11Count;
//                $arr['xbk12Count'] = $this->xbk12Count;
//            }elseif($val=='xbk10Count') {
//                $arr['xbk09Count'] = $this->xbk09Count;
//                $arr['xbk10Count'] = $aa;
//                $arr['xbk11Count'] = $this->xbk11Count;
//                $arr['xbk12Count'] = $this->xbk12Count;
//            }elseif($val=='xbk11Count') {
//                $arr['xbk09Count'] = $this->xbk09Count;
//                $arr['xbk10Count'] = $this->xbk10Count;
//                $arr['xbk11Count'] = $aa;
//                $arr['xbk12Count'] = $this->xbk12Count;
//            }elseif($val=='xbk12Count') {
//                $arr['xbk09Count'] = $this->xbk09Count;
//                $arr['xbk10Count'] = $this->xbk10Count;
//                $arr['xbk11Count'] = $this->xbk11Count;
//                $arr['xbk12Count'] = $aa;
//            }
//            $over2 = serialize($arr);
//            $re->value = $over2;
//            $re->save();
//        }
//        // return $re;
//    }
//
//    /**
//     * 活动页面
//     */
//    public function actionXbk() {
//        $this->checkLogin();
//        $this->getConfig();
//        $oneYuanCode = null;
//        $endTime = strtotime($this->xbkEndTime);
//        $startTime = strtotime($this->xbkStartTime);
//        $ctime = time();
//        if ($ctime >= $startTime && $ctime <= $endTime) {
//            //活动开始 隐藏
//            $time = 1;
//            $displayNone = 1; //隐藏
//            if ($this->xbkStart){
//                $oneYuanCode = OneYuanBuy::model()->find(array('condition' => 'goods_id=:goods_id AND is_send=:is_send AND is_use=:is_use AND state=:state AND customer_id=:customer_id'
//                , 'params' =>array(':goods_id'=>$this->_goods_id, ':is_send'=>1, ':is_use'=>0, 'state'=>0, ':customer_id'=>0)
//                ));
//            } else $oneYuanCode = null;
//            if (!$oneYuanCode) $displayNone = 0; //提示没数量
//        }else {
//           $displayNone = 0;
//           $time = 0;
//           $oneYuanCode = 1;
//        }
//        //一元购地址
//        $re = new SetableSmallLoans();
//        $href = $re->searchByIdAndType(57, '', $this->_userId);
//        if ($href)
//            $oneBuyHref = $href->completeURL;
//        else $oneBuyHref = '';
//        $this->renderPartial("xbk", array(
//           'oneYuanCode' => $oneYuanCode,
//           'time' => $time,
//           'oneBuyHref' => $oneBuyHref,
//           'displayNone' => $displayNone
//        ));
//    }
//
//
//
//    /**
//     * 活动页面
//     */
//    public function actionIndex() {
//        $this->checkLogin();
//        //一元购地址
//        $re = new SetableSmallLoans();
//        $href = $re->searchByIdAndType(57, '', $this->_userId);
//        if ($href)
//            $oneBuyHref = $href->completeURL;
//        else $oneBuyHref = '';
//        $this->renderPartial("xbk", array(
//           'oneBuyHref' => $oneBuyHref,
//        ));
//    }
//
//    public function setVal($val)
//    {
//        $this->_val = $val;
//        $this->value = serialize($this->_val);
//    }
//
//
//    /*
//     * 活动抢码
//     */
//    function actionXBKCode(){
//        if (empty($_SESSION['userid'])) {
//            echo json_encode(array('suc' => 1));
//            exit;
//        } else
//        $uid = $_SESSION['userid'];
//        $goods_id = $this->_goods_id;
//
//        $time = time();
//        //判断时间
//        $this->getConfig();
//        $endTime = strtotime($this->xbkEndTime);
//        $startTime = strtotime($this->xbkStartTime);
//        $ctime = time();
//        if ($ctime < $startTime && $ctime > $endTime) {
//            echo json_encode(array('suc' => 6)); //时间已经过期了
//            exit;
//        }
//
//        $oneYuan = OneYuanBuy::model()->find(array('condition' => 'goods_id=:goods_id AND customer_id=:customer_id'
//            , 'params' =>array(':goods_id'=>$goods_id, ':customer_id'=>$uid)
//            ));
//        if ($oneYuan){
//            echo json_encode(array('suc' => 2, 'is_use'=>$oneYuan->is_use)); //已经领过了
//            exit;
//        }
//
//        //得到未使用码
//        $oneYuan = OneYuanBuy::model()->find(array('condition' => 'is_send=:is_send AND is_use=:is_use AND state=:state AND customer_id=:customer_id'
//            , 'params' =>array(':is_send'=>0, ':is_use'=>0, 'state'=>0, ':customer_id'=>0)
//            ));//dump($oneYuan);
//
//        if (empty($oneYuan)){
//            echo json_encode(array('suc' => 5)); //领完了
//            exit;
//        }
//
//        if(date('Y-m-d')=='2015-07-09'){
//            if (intval($this->xbk09Count)<=0){
//                echo json_encode(array('suc' => 5)); //领完了
//                exit;
//            }else{
//                $this->setConfig('xbk09Count');
//            }
//        }else if(date('Y-m-d')=='2015-07-10'){
//            if (intval($this->xbk10Count)<=0){
//                echo json_encode(array('suc' => 5)); //领完了
//                exit;
//            }else{
//                $this->setConfig('xbk10Count');
//            }
//        }else if(date('Y-m-d')=='2015-07-11'){
//            if (intval($this->xbk11Count)<=0){
//                echo json_encode(array('suc' => 5)); //领完了
//                exit;
//            }else{
//                $this->setConfig('xbk11Count');
//            }
//        }else if(date('Y-m-d')=='2015-07-12'){
//            if (intval($this->xbk12Count)<=0){
//                echo json_encode(array('suc' => 5)); //领完了
//                exit;
//            }else{
//                $this->setConfig('xbk12Count');
//            }
//        }
//
//        //更新一元购使用码
//        $oneYuan->goods_id = $goods_id;
//        $oneYuan->is_send = 1;
//        $oneYuan->customer_id = $uid;
//        $oneYuan->send_time = $time;
//        $oneYuan->update_time = $time;
//        $oneYuan->valid_time = strtotime($this->xbkValidTime);
//        $oneYuan->model = 'xbk_code';
//        if ($oneYuan->save()){
//                Yii::log("海外直购1元购商品抢一元码，用户ID[{$this->_userId}]，码：[{$oneYuan->code}]", CLogger::LEVEL_INFO, 'colourlife.home.luckyApp.doWeiXiuJuan');
//                echo json_encode(array('suc' => 3, 'msg' => $oneYuan->code));
//                exit;
//            // }
//        } else echo json_encode(array('suc' => 4));
//    }
//
//    // /*
//    //  * 检查登录
//    //  */
//    // private function checkLogin() {
//    //     $cust_id = floatval(Yii::app()->request->getParam('cust_id'));
//    //     if (empty($cust_id) && empty($_SESSION['userid'])) {
//    //         exit('<h1>用户信息错误，请重新登录</h1>');
//    //     }
//
//    //     if (empty($_SESSION['userid'])) $_SESSION['userid'] = $cust_id;
//    //     else  $cust_id = $_SESSION['userid'];
//    //     $customer = Customer::model()->findByPk($cust_id);
//    //     if (empty($customer)) {
//    //         exit('<h1>用户信息错误，请重新登录</h1>');
//    //     }
//    //     $this->_userId = $cust_id;
//    //     $this->_username = $customer->username;
//    // }
//
//
//
//     /*
//     * 检查登录
//     */
//    private function checkLogin() {
//        $cust_id = floatval(Yii::app()->request->getParam('cust_id'));
//        if (empty($cust_id)){
//            $cust_id = floatval(Yii::app()->request->getParam('csh'));
//            $cust_id = ($cust_id+1778)/178;
//        }
//        if (empty($cust_id) && empty($_SESSION['userid'])) {
//            exit('<h1>用户信息错误，请重新登录s</h1>');
//        }
//
//        if (empty($_SESSION['userid'])) $_SESSION['userid'] = $cust_id;
//        else  $cust_id = $_SESSION['userid'];
//        $customer = Customer::model()->findByPk($cust_id);
//        if (empty($customer)) {
//            exit('<h1>用户信息错误，请重新登录2</h1>');
//        }
//
//        $this->_userId = $cust_id;
//        $this->_username = $customer->username;
//    }

}
