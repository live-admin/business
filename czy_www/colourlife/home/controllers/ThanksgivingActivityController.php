<?php
/*
 * 630感恩大促
 */
class ThanksgivingActivityController extends CController {

//    private $_username = '';
//    private $_userId = '';
//    private $_goods_id = 9845;
//    public $sfStart;
//    public $sfStartTime;
//    public $sfEndTime;
//    public $anshiStartTime;
//    public $anshiEndTime;
//    public $anshiValidTime;
//    public $anshiStart;
//    private $_eStartTime = '2015-06-29 00:00:00';
//    private $_eEndTime = '2015-06-30 23:59:59';
//
//    public function getConfig(){
//        $re = Config::model()->findByPk(52);
//        $over = null;
//        if ($re){
//            $over = unserialize($re->value);//dump($over);
//            foreach($over as $k => $v){
//                $this->{$k} = $v;
//            }
//        }
//    }
//    /**
//     * 活动首页面
//     */
//    public function actionIndex() {
//        $this->checkLogin();
//        //一元购地址
//        $re = new SetableSmallLoans();
//        $href = $re->searchByIdAndType(57, '', $this->_userId);
//        if ($href)
//            $oneBuyHref = $href->completeURL;
//        else $oneBuyHref = '';
//        //e理财地址
//        $href = $re->searchByIdAndType(12, '', $this->_userId);
//        if ($href)
//            $liCaiHref = $href->completeURL;
//        else $liCaiHref = '';
//        //上市周年庆投资送好礼
//        $href = $re->searchByIdAndType(76, '', $this->_userId);
//        if ($href)
//            $toFriendHref = $href->completeURL;
//        else $toFriendHref = '';
//        //京东
//        $href = $re->searchByIdAndType(67, '', $this->_userId);
//        if ($href)
//            $jdHref = $href->completeURL;
//        else $jdHref = '';
//        //拼图领红包
//        $href = $re->searchByIdAndType(40, '', $this->_userId);
//        if ($href)
//            $pintuHref = $href->completeURL;
//        else $pintuHref = '';
//        $this->renderPartial("index", array(
//            'oneBuyHref' => $oneBuyHref,
//            'liCaiHref' => $liCaiHref,
//            'toFriendHref' => $toFriendHref,
//            'jdHref' => $jdHref,
//            'pintuHref' => $pintuHref
//        ));
//    }
//
//    /**
//     * 顺风页面
//     */
//    public function actionSf() {
//        $this->checkLogin();
//        //超市顺风优惠码
//        $re = new SetableSmallLoans();
//        $href = $re->searchByIdAndType(42, '', $this->_userId);
//        if ($href)
//            $marketurl = $href->completeURL;
//        else
//            $marketurl = '';
//        //顺风连接
//        $href = $re->searchByIdAndType(44, '', $this->_userId);
//        if ($href)
//            $sfurl = $href->completeURL;
//        else
//            $sfurl = '';
//        $this->getConfig();
//        $endTime = strtotime($this->sfEndTime);
//        $startTime = strtotime($this->sfStartTime);
//        $ctime = time();
//        if ($ctime <= $endTime && $ctime >= $startTime) {
//            //活动开始 隐藏
//            $time = 1;
//            $displayNone = 1; //隐藏
//            //取得配置是否开始
//            $over = $this->sfStart;
//            if (!$over) $displayNone = 0; //提示没数量
//        }else {
//           $displayNone = 0;
//           $time = 0;
//           $over = 1;
//        }
//        $this->renderPartial("sf", array(
//            'marketurl' => $marketurl,
//            'sfurl' => $sfurl,
//           'over' => $over,
//           'time' => $time,
//           'displayNone' => $displayNone
//        ));
//    }
//
//
//    /**
//     * 海外直购
//     */
//    public function actionAnshi() {
//        $this->checkLogin();
//        //海外直购地址
//        $re = new SetableSmallLoans();
//        $href = $re->searchByIdAndType(38, '', $this->_userId);
//        if ($href)
//            $url = $href->completeURL;
//        else
//            $url = '';
//        $this->getConfig();
//        $oneYuanCode = null;
//        $endTime = strtotime($this->anshiEndTime);
//        $startTime = strtotime($this->anshiStartTime);
//        $ctime = time();
//        if ($ctime >= $startTime && $ctime <= $endTime) {
//            //活动开始 隐藏
//            $time = 1;
//            $displayNone = 1; //隐藏
//            if ($this->anshiStart){
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
//        //$re = new SetableSmallLoans();
//        $href = $re->searchByIdAndType(57, '', $this->_userId);
//        if ($href)
//            $oneBuyHref = $href->completeURL;
//        else $oneBuyHref = '';
//        $this->renderPartial("anshi", array(
//           'anshiHref' => $url,
//           'oneYuanCode' => $oneYuanCode,
//           'time' => $time,
//           'oneBuyHref' => $oneBuyHref,
//           'displayNone' => $displayNone
//        ));
//    }
//
//    /**
//     * jd
//     */
//    public function actionJd() {
//        //$this->checkLogin();
//        //海外直购地址
//        $re = new SetableSmallLoans();
//        $href = $re->searchByIdAndType(67, '', $this->_userId);
//        if ($href)
//            $jdurl = $href->completeURL;
//        else
//            $jdurl = '';
//        $this->renderPartial("jd", array(
//           'jdurl' => $jdurl
//        ));
//    }
//
//    /*
//     * 海外直购抢码
//     */
//    function actionAnshiCode(){
//        if (empty($_SESSION['userid'])) {
//            echo json_encode(array('suc' => 1));
//            exit;
//        } else
//        $uid = $_SESSION['userid'];
//        $goods_id = $this->_goods_id;
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
//        $oneYuan = OneYuanBuy::model()->find(array('condition' => 'goods_id=:goods_id AND is_send=:is_send AND is_use=:is_use AND state=:state AND customer_id=:customer_id'
//            , 'params' =>array(':goods_id'=>$goods_id, ':is_send'=>1, ':is_use'=>0, 'state'=>0, ':customer_id'=>0)
//            ));//dump($oneYuan);
//        if (empty($oneYuan)){
//            echo json_encode(array('suc' => 5)); //领完了
//            exit;
//        }
//        $time = time();
//        //判断时间
//        $this->getConfig();
//        $endTime = strtotime($this->anshiEndTime);
//        $startTime = strtotime($this->anshiStartTime);
//        $ctime = time();
//        if ($ctime < $startTime && $ctime > $endTime) {
//            echo json_encode(array('suc' => 6)); //时间已经过期了
//            exit;
//        }
//        //更新一元购使用码
//        $oneYuan->customer_id = $uid;
//        $oneYuan->send_time = $time;
//        $oneYuan->update_time = $time;
//        $oneYuan->valid_time = strtotime($this->anshiValidTime);
//        $oneYuan->model = 'anshi_code';
//        if ($oneYuan->save()){
//                Yii::log("海外直购1元购商品抢一元码，用户ID[{$this->_userId}]，码：[{$oneYuan->code}]", CLogger::LEVEL_INFO, 'colourlife.home.luckyApp.doWeiXiuJuan');
//                echo json_encode(array('suc' => 3, 'msg' => $oneYuan->code));
//                exit;
//            // }
//        } else echo json_encode(array('suc' => 4));
//    }
//
//    /*
//     * 检查登录
//     */
//    private function checkLogin() {
//        $cust_id = floatval(Yii::app()->request->getParam('cust_id'));
//        if (empty($cust_id) && empty($_SESSION['userid'])) {
//            exit('<h1>用户信息错误，请重新登录</h1>');
//        }
//
//        if (empty($_SESSION['userid'])) $_SESSION['userid'] = $cust_id;
//        else  $cust_id = $_SESSION['userid'];
//        $customer = Customer::model()->findByPk($cust_id);
//        if (empty($customer)) {
//            exit('<h1>用户信息错误，请重新登录</h1>');
//        }
//        $this->_userId = $cust_id;
//        $this->_username = $customer->username;
//    }
//
//     var $key;
//     function Crypt3Des($key){
//        $this->key = $key;
//     }
//
//     function encrypt($input){
//         $size = mcrypt_get_block_size(MCRYPT_3DES,'ecb');
//         $input = $this->pkcs5_pad($input, $size);
//         $key = str_pad($this->key,24,'0');
//         $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
//         $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
//         @mcrypt_generic_init($td, $key, $iv);
//         $data = mcrypt_generic($td, $input);
//         mcrypt_generic_deinit($td);
//         mcrypt_module_close($td);
//         //    $data = base64_encode($this->PaddingPKCS7($data));
//         $data = base64_encode($data);
//              return $data;
//     }
//
//     function decrypt($encrypted){
//         $encrypted = base64_decode($encrypted);
//         $key = str_pad($this->key,24,'0');
//         $td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');
//         $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
//         $ks = mcrypt_enc_get_key_size($td);
//         @mcrypt_generic_init($td, $key, $iv);
//         $decrypted = mdecrypt_generic($td, $encrypted);
//         mcrypt_generic_deinit($td);
//         mcrypt_module_close($td);
//         $y=$this->pkcs5_unpad($decrypted);
//              return $y;
//     }
//
//     function pkcs5_pad ($text, $blocksize) {
//         $pad = $blocksize - (strlen($text) % $blocksize);
//         return $text . str_repeat(chr($pad), $pad);
//     }
//
//     function pkcs5_unpad($text){
//         $pad = ord($text{strlen($text)-1});
//         if ($pad > strlen($text)) {
//             return false;
//         }
//         if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){
//             return false;
//         }
//               return substr($text, 0, -1 * $pad);
//     }
//
//     function PaddingPKCS7($data) {
//         $block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
//         $padding_char = $block_size - (strlen($data) % $block_size);
//         $data .= str_repeat(chr($padding_char),$padding_char);
//         return $data;
//     }
//
//
//    //E维修代金劵
//    public function actionDoWeiXiuJuan()
//    {
//        $this->checkLogin();
//        $date = date('Y-m-d H:i:s');
//        if($date>=$this->_eStartTime && $date<=$this->_eEndTime){
//            $model = Customer::model()->enabled()->findByPk($this->_userId);
//            if (!isset($model)) {
//                // throw new CHttpException(400, '用户不存在或被禁用!');
//                echo CJSON::encode ( 0 );
//            }else{
//                if($model->community_id==585){
//                    // throw new CHttpException(400, '活动用户不含体验区!');
//                    echo CJSON::encode ( 1 );
//                }else{
//                    if($model->is_lingqu_weixiu==1){
//                        echo CJSON::encode ( 6 );//代金劵已经领取,不能重复领取
//                    }else{
//                        $url="http://m.eshifu.cn/business/sendcoupons?mobile=".$model->mobile;
//                        $return = Yii::app()->curl->get($url);
//                        $result = json_decode($return,true);
//                        if($result["code"]==0&&empty($result["message"])){
//                            Yii::log("630感恩大促用户获得20元E维修代金劵，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.lThanksgivingActivityController.actionDoWeiXiuJuan');
//                            if (!Customer::model()->updateByPk($model->id, array('is_lingqu_weixiu'=>1))) {
//                                echo CJSON::encode ( 9 );//领取失败
//                            }
//                            echo CJSON::encode ( 4 );//领取成功
//                        }else if($result["code"]==-1&&$result["message"]=='无效的用户手机号码'){
//                            echo CJSON::encode ( 3 );//无效的用户手机号码
//                        }else if($result["code"]==-1&&$result["message"]=='数据操作异常'){
//                            echo CJSON::encode ( 7 );//数据操作异常
//                        }else if($result["code"]==-1&&$result["message"]=='代金券发放时间已过期'){
//                            echo CJSON::encode ( 8 );//代金券发放时间已过期
//                        }else{
//                            echo CJSON::encode ( 9 );//领取失败
//                        }
//                    }
//
//                }
//            }
//        }else{
//            // throw new CHttpException(400, '活动失效');
//            echo CJSON::encode ( 5 );
//        }
//    }
//
//
//    //E维修代金劵
//    public function actionDoZuFanJuan()
//    {
//        $this->checkLogin();
//        $date = date('Y-m-d H:i:s');
//        if($date>=$this->_eStartTime && $date<=$this->_eEndTime){
//            $model = Customer::model()->enabled()->findByPk($this->_userId);
//            if (!isset($model)) {
//                // throw new CHttpException(400, '用户不存在或被禁用!');
//                echo CJSON::encode ( 0 );
//            }else{
//                $array = array(
//                    'userid'=>$model->id,
//                    'mobile'=>$model->mobile
//                    //'code'=>''
//                );
//                $json = json_encode($array);
//                $this->key = $key = 'SDFL#)@F';
//                $Params = $this->encrypt($json);
//                //sign
//                $md5 = '';
//                foreach ($array as $k=>$v){
//                   $md5 .= $k.$v;
//                }
//                $md5 = $key . "Methodset.coupon.actParams{$json}{$key}";
//                // var_dump($md5);
//
//                $sign = strtoupper(md5($md5));
//                //var_dump($Params);
//                $re = new PublicFunV23();
//                $p = $re->arrayToString($array).'&code=';
//                $url = "http://a.lokea.cn/?Method=set.coupon.act&Params={$json}&sign={$sign}";
//
//                $re->method = 'POST';
//                $re->typeConnect= 'curl';
//                $return = $re->contentMethod($url, array());
//
//                $json = json_decode($return);
//                if (!$json->verification){
//                    echo CJSON::encode ( 4 );//验证失败
//                } elseif ($json->verification == true && !$json->total){
//                    echo CJSON::encode ( 6 ); //已领取过
//                } elseif ($json->verification == true && $json->total && $json->msg == '租房券绑定成功'){
//                    echo CJSON::encode ( 4 );//领取成功
//                    Yii::log("630感恩大促E租房代金劵100元，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.ThanksgivingActivityController.actionDoZuFanJuan');
//                }else {
//                    echo CJSON::encode ( 7 );//数据操作异常
//                }
//            }
//        }else{
//            // throw new CHttpException(400, '活动失效');
//            echo CJSON::encode ( 5 );
//        }
//    }
//
//
//
//
//

}
