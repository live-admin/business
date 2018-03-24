<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2017/3/1
 * Time: 10:04
 * 三八节线下活动
 */

class WomenController extends ActivityController
{
//    public $beginTime = '2017-03-01 00:00:00';//活动开始时间
//    public $endTime = '2017-03-26 23:59:59';//活动结束时间

    public $beginTime = '2017-03-07 00:00:00';//活动开始时间
    public $endTime = '2017-03-10 23:59:59';//活动结束时间

    public $secret = 'wom^**en';
    public $layout = false;

    public function filters()
    {
        return array(
            'accessControl',
            'Validity',
            'signAuth - Draw',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }


    public function actionIndex(){
        //是否抽过奖
        $num = 1;
        $customer_id = $this->getUserId();
        $women_model_prize = Women::model()->findByAttributes(array('customer_id'=>$customer_id , 'is_send'=>2));
        if($women_model_prize){
            $num = 0;
        }
        $this->renderPartial('/v2017/women/index', array('num'=>$num));
    }

    public function actionDraw(){

        if(isset($_SERVER['HTTP_USER_AGENT'])&&strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') == true){  //微信打开
            $error_url=F::getHomeUrl('/site/error');

            if(!isset($_GET['openid']) ||empty($_GET['openid'])){
                $url = urlencode(F::getHomeUrl('/Women/Draw'));
                $this->redirect('http://m.aparcar.cn/service/wxauth/authorize?scope=snsapi_userinfo&backurl='.$url);
            }
            $userInfo = $_GET;
            if (!empty($userInfo)){
                $openid = $userInfo['openid'];
                //$register_url = urlencode(F::getMUrl('/Home/register?source=women&openid=').$openid.'&sign='.md5($openid.'colourlife'));
                //测试地址
                //$this->redirect('http://m.czytest.colourlife.com/Home/register?source=women&openid='.$openid.'&sign='.md5($openid.'colourlife'));
                //正式地址
                $this->redirect('http://m.colourlife.com/Home/register?source=women&openid='.$openid.'&sign='.md5($openid.'colourlife'));
            }else {
                $this->redirect($error_url);
            }

        }else{
            $this->redirect('http://m.colourlife.com/Home/register');
        }
    }


    //抽奖ajax请求
    public function actionLuckDraw(){

        $customer_id = $this->getUserId();
        $women_model = Women::model()->findByAttributes(array('customer_id'=>$customer_id));
        if(empty($women_model)){
            $arr = array(
                'state'=>0,
                'msg'=>'谢谢参与！',
                'prize_id'=>6
            );
            $this->output($arr);
        }

        $openid = $women_model->openid;
        $result = Women::model()->luckDraw($customer_id , $openid);
        if($result){
            $this->output($result);
        }else{
            $arr = array(
                'state'=>0,
                'msg'=>'谢谢参与！',
                'prize_id'=>6
            );
            $this->output($arr);
        }
    }

    //活动规则
    public function actionRules(){
        $this->renderPartial('/v2017/women/rules');
    }

    //中奖记录
    public function actionRecord(){
        $record = Women::model()->getRecord();
        $this->renderPartial('/v2017/women/record',array('record'=>$record));
    }
}
