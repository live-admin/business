<?php

/**
 * 强月饼活动  2015-08-20
 * Class RobMoonCakesController
 */

//class RobMoonCakesController extends CController {
    //private $_userId = 0;

    /**
     * 活动首页
     * @throws CException
     */
/*     public function actionIndex(){

        $this->checkLogin();


        $status = 3;//$this->actStatus();
        if($status==1){
            exit('<center><h1>活动未开始</h1></center>');
        }
        if($status==2){
            exit('<center><h1>活动已经结束</h1></center>');
        }


        $this_act = SetMoonCakes::model()->getThisActivity();
        if($this_act){
            $flag = 1;
            $this_count = $this_act->remaining_number;
        }else{
            $flag = 0;
            $this_count = 0;
        }

        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('market', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';

        $re2 = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re2) {$url2 = $re2->completeURL;} else $url2 = 'error';

        $this->renderPartial("issue2015/index",
            array(
                'newInfo'=> MoonCakesResult::model()->getNewInfo(),  //最新中奖信息
                'remaining' => $this->getRemaining(),  //本场剩余个数
                'this_count' => $this_count,
                'url' => $url,
                'url2' => $url2,
                'flag' => $flag,
            )
        );

    } */

    /**
     * 活动战绩
     * @throws CException
     */
/*     public function actionMyLottery(){
        $this->checkLogin();
        $criteria = new CDbCriteria;
        $criteria->addCondition("customer_id=".$this->_userId." and code_relation_id<>0");
        $criteria->order = "id desc";
        $data=MoonCakesResult::model()->findAll($criteria);
        $list=array();
        foreach ($data as $key => $value){
            $list[$key]['mycode'] = $value->oneCode->code;
            $list[$key]['isUse'] = $value->oneCode->is_use;
            $list[$key]['lucky_date'] = date('m.d H:i',$value->create_time);
        }
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial(
            "issue2015/hdzj",
            array("list"=>$list,'url'=>$url)
        );
    } */

    /**
     * 活动规则
     * @throws CException
     */
/*     public function actionRule(){
        $this->checkLogin();
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial(
            "issue2015/hdgz",
            array('url' => $url)
        );
    } */

    /**
     * 抢操作
     * @throws CHttpException
     */
/*     public function actionRob(){
        if (!Yii::app()->getRequest()->getIsPostRequest())
            throw new CHttpException(405, 'Method Not Allowed');
        $this->checkLogin();
        if(date("H")==10 || date("H")==16 || date("H")==20){
            $this_act = SetMoonCakes::model()->getThisActivity();
            if($this_act){
                $this_act->clicks += 1;
                $this_act->save();
                $res = MoonCakesResult::model()->rob($this_act->id,$this->_userId);
                echo CJSON::encode($res);     //0抢光了;1抢到了;2今天已抢过不能再抢; 5/6/7没抢到
            }else{
                echo CJSON::encode(4);        //4活动暂未开始
            }
        }
        else{
            echo CJSON::encode(4);        //4活动暂未开始
        }

    } */

    //获取本场剩余份数
/*     public function getRemaining(){
        $activity = SetMoonCakes::model()->getThisActivity();
        if($activity){
            if($activity->remaining_number == 5 || $activity->remaining_number == 10){
                $remaining_number = $activity->remaining_number."0";
            }else{
                if($activity->remaining_number == 0){
                    $remaining_number = 0;
                }else{
                    $remaining_number = ($activity->remaining_number>1)?($activity->remaining_number-1).rand(0,9):rand(0,9);
                }
            }
        }else{
            $remaining_number = 0;
        }
        return $remaining_number;
    } */


    //活动进行中  1、未开始  2、已结束  3、进行中
/*     public function actStatus(){
        $startModel = SetMoonCakes::model()->find(array('order'=>'id asc'));
        $lastModel = SetMoonCakes::model()->find(array('order'=>'id desc'));
        if(date("Y-m-d H:i:s") < $startModel->start_time){
            return 1;
        }else if(date("Y-m-d H:i:s") > $lastModel->end_time){
            return 2;
        }else{
            return 3;
        }
    } */

    /**
     * 登录验证
     */
/*     private function checkLogin(){
        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }
        else {
            $custId = 0;
            if(isset($_REQUEST['cust_id'])) {  //优先有参数的
                $custId = intval($_REQUEST['cust_id']);
                $_SESSION['cust_id'] = $custId;
            }
            else if(isset($_SESSION['cust_id'])){  //没有参数，从session中判断
                $custId = $_SESSION['cust_id'];
            }
            $custId=intval($custId);
            $customer = Customer::model()->findByPk($custId);
            if(empty($custId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }

            $this->_userId = $custId;
        }
    } */
//}