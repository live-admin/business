<?php
class WorldCupPromotionController extends CController {
//    //copy from luckyAppController
//
//    private $_username = "";
//    private $_userId = 0;
//
//    public function  isover(){
//        if("2014-07-13 23:59:59" < date("Y-m-d H:i:s") ){
//            return true;
//        }
//        return false;
//    }
//
//
//    /**
//     * index页面，显示抽奖奖项
//     */
//    public function actionIndex() {
//        $this->checkLogin();
//        $isGuest=Yii::app ()->user->isGuest;
//
//        $form = $this->getCurrentForm();
//        //$form['content']='_form';$form['id']=4;
//        $find =CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>$form['id']));
//        if($find){
//            if($form['id']<5)
//                $this->redirect(array('myGuess'));
//            else
//                $this->redirect(array('myWinnerGuess'));
//        }
//        if($form['id']>0){
//            $set=SetTeamsPromotion::model()->findByPk($form['id']);
//            if($form['id']==5)
//                $title='谁是王者？';
//            else
//                $title='谁会进入'.$set->type.'？';
//        }else
//            $title='谁能晋级？';
//        $model =new CustomerPromotion('create');
//
//        $this->renderPartial( "index",
//            array (
//                "custId"=>$this->_userId,
//                "isGuest"=>$isGuest,
//                "href"=>Yii::app ()->user->loginUrl,
//                "isover"=>$this->isover(),
//                'title'=>$title,
//                'form' => $form['content'],
//                'promotionid'=> $form['id'],
//                'model'=>$model,
//            ));
//    }
//
//    public function actionUpdate() {
//        $this->checkLogin();
//        $isGuest=Yii::app ()->user->isGuest;
//
//        $form = $this->getCurrentForm();
//        //$form['content']='_form';$form['id']=4;
//        $model =CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>$form['id']));
//        if(!$model){
//            if($form['id']<5)
//                $this->redirect(array('index'));
//            else
//                $this->redirect(array('worldCupPromotion/index/winner'));
//        }
//        $myguess=explode(",",$model->my_quiz_teams);
//
//        $set=SetTeamsPromotion::model()->findByPk($form['id']);
//        if($form['id']==5)
//            $title='谁是王者？';
//        else
//            $title='谁会进入'.$set->type.'？';
//
//        $this->renderPartial( "update",
//            array (
//                "custId"=>$this->_userId,
//                "isGuest"=>$isGuest,
//                "href"=>Yii::app ()->user->loginUrl,
//                "isover"=>$this->isover(),
//                'title'=>$title,
//                'form' => $form['content'],
//                'promotionid'=> $form['id'],
//                'model'=>$model,
//                'myguess'=>$myguess,
//            ));
//    }
//
//    public function actionMyGuess() {
//        $this->checkLogin();
//        $isGuest=Yii::app ()->user->isGuest;
//
//        $guessList=$quiz=array();
//
//        $params=array(
//            'condition'=>'customer_id=:id AND teams_promotion_id<5',
//            'params'=>array(':id'=>$this->_userId),
//            'order'=>'teams_promotion_id desc',
//        );
//        $findAll=CustomerPromotion::model()->findAll($params);
//        if(!$findAll)
//            $this->redirect(array('index'));
//        foreach ($findAll as $value){
//            $guessList[$value['teams_promotion_id']]=$value;
//        }
//
//        $this->renderPartial( "myguess",
//            array (
//                "custId"=>$this->_userId,
//                "isGuest"=>$isGuest,
//                "href"=>Yii::app ()->user->loginUrl,
//                "isover"=>$this->isover(),
//                'guessList'=>$guessList,
//            ));
//    }
//
//    public function actionMyWinnerGuess() {
//        $this->checkLogin();
//        $isGuest=Yii::app ()->user->isGuest;
//
//        $model =CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>5));
//        if(!$model)
//            $this->redirect(array('worldCupPromotion/index/winner'));
//
//        $this->renderPartial( "myWinnerGuess",
//            array (
//                "custId"=>$this->_userId,
//                "isGuest"=>$isGuest,
//                "href"=>Yii::app ()->user->loginUrl,
//                "isover"=>$this->isover(),
//                'guess'=>$model,
//            ));
//    }
//
//    public function getCurrentForm() {
//        $date=date("Y-m-d H:i:s");
//        if(isset($_GET["id"])&&$_GET["id"]=='winner'){
//            $winnerTime=SetTeamsPromotion::model()->find("id=5");
//            if($winnerTime->start_time < $date &&  $winnerTime->end_time > $date){
//                return array('content'=>'_winner','id'=>5);
//            }else
//                return array('content'=>'_stop','id'=>5);
//        }else{
//            $promotionSet=SetTeamsPromotion::model()->find("start_time<:date AND end_time>:date AND id<5",array(":date"=>$date));
//            if($promotionSet){
//                $id=$promotionSet->id;
//                if($promotionSet->id==1){
//                    $form="_first";
//                }else{
//                    $form="_form";
//                }
//            }else{
//                $id=0;
//                $form="_stop";
//            }
//        }
//        return array('content'=>$form,'id'=>$id);
//    }
//
//    //提交竞猜
//    public function actionSubmitGuess(){
//        $this->checkLogin();
//
//        $code = $_POST['code'];
//        $pid = $_POST['pid'];
//        $inTime = $this->checkInTime($pid);
//        if($code&&$pid&&$inTime){
//            $find = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>$pid));
//            if($find){
//                echo CJSON::encode(0);
//            }else{
//                sort($code);
//                $my_guess=implode(',',$code);
//                $model = new CustomerPromotion('create');
//                $model->teams_promotion_id = $pid;
//                $model->my_quiz_teams = $my_guess;
//                $model->create_time = time();
//                $model->customer_id = $this->_userId;
//                $model->customer_ip = Yii::app()->request->userHostAddress;
//                $model->is_send = 0;
//                $model->is_right = 0;
//                $model->update_times = 0;
//                $model->is_deleted = 0;
//                if($model->save()){
//                    $this->sendLucky();
//                    echo CJSON::encode(1);
//                }else
//                    echo CJSON::encode(0);
//            }
//        }else
//            echo CJSON::encode(0);
//    }
//
//    //修改竞猜
//    public function actionUpdateGuess(){
//        $this->checkLogin();
//
//        $code = $_POST['code'];
//        $pid = $_POST['pid'];
//        $inTime = $this->checkInTime($pid);
//        if($code&&$pid&&$inTime){
//            $find = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>$pid));
//            if(!$find){
//                echo CJSON::encode(0);
//            }else{
//                if($find->update_times>2){
//                    echo CJSON::encode(2);
//                }else{
//                    sort($code);
//                    $my_guess=implode(',',$code);
//                    $find->my_quiz_teams = $my_guess;
//                    $find->update_times+= 1;
//                    if($find->save()){
//                        echo CJSON::encode(1);
//                    }else
//                        echo CJSON::encode(0);
//                }
//            }
//        }else
//            echo CJSON::encode(0);
//    }
//
//    //提交冠军竞猜
//    public function actionSubmitWinner(){
//        $this->checkLogin();
//        $pid = 5;
//        $inTime = $this->checkInTime($pid);
//        $code = $_POST['code'];
//        $find = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>5));
//        if($find||!$inTime||!$code){
//            echo CJSON::encode(0);
//        }else{
//            $model = new CustomerPromotion('create');
//            $model->teams_promotion_id = 5;
//            $model->my_quiz_teams = $code;
//            $model->create_time = time();
//            $model->customer_id = $this->_userId;
//            $model->customer_ip = Yii::app()->request->userHostAddress;
//            $model->is_send = 0;
//            $model->is_right = 0;
//            $model->update_times = 0;
//            $model->is_deleted = 0;
//            if($model->save()){
//              $this->sendLucky();
//              echo CJSON::encode(1);
//            }else
//                echo CJSON::encode(0);
//        }
//    }
//
//    //修改冠军竞猜
//    public function actionUpdateWinner(){
//        $this->checkLogin();
//        $pid = 5;
//        $inTime = $this->checkInTime($pid);
//        if(!$inTime){
//            echo CJSON::encode(0);
//        }else{
//            $find = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>5));
//            if($find->update_times>2){
//                echo CJSON::encode(2);
//            }else{
//                $code = $_POST['code'];
//                if($code==''||!$find){
//                    echo CJSON::encode(0);
//                }else{
//                    $find->my_quiz_teams = $code;
//                    $find->update_times+= 1;
//                    if($find->save()){
//                        echo CJSON::encode(1);
//                    }else
//                        echo CJSON::encode(0);
//                }
//            }
//        }
//    }
//
//
//    private function sendLucky(){
//        $custGet = new LuckyCustomerGet ();
//        $custGet->cust_name = "世界杯晋级竞猜";
//        $custGet->cust_id = $this->_userId;
//        $custGet->get_action="TheWorldCupPromotion";
//        $custGet->genner_count = 1;
//        $custGet->lucky_act_id = Item::LUCKY_ACT_ID;
//        $custGet->create_date = date ( "Y-m-d H:i:s" );
//        $custGet->save ();
//        $exeRet = LuckyCustCan::model ()->updateAll ( array (
//            "cust_can" => new CDbExpression ( "cust_can+" . 1 ),
//        ), "cust_id=:custId and lucky_act_id=:luckyActId", array (
//            ":custId" => $this->_userId,
//            ":luckyActId" => Item::LUCKY_ACT_ID
//        ) );
//    }
//
//
//    private function checkInTime($pid){
//        $date=date("Y-m-d H:i:s");
//        $activeTime=SetTeamsPromotion::model()->findByPk($pid);
//        if($date>$activeTime->start_time && $date<$activeTime->end_time){
//            return true;
//        }else
//            return false;
//    }
//
//
//    private function checkLogin(){
//        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
//            //$this->redirect ( Yii::app ()->user->loginUrl );
////             $result = array ("success" => 0,"data" => array ("location"=>1,"href"=>Yii::app ()->user->loginUrl ) );
//// 			echo CJSON::encode($result);
//// 			exit();
//            exit('<h1>用户信息错误，请重新登录</h1>');
//        }else {
//            $custId=0;
//            if(isset($_REQUEST['cust_id'])){  //优先有参数的
//                $custId=intval($_REQUEST['cust_id']);
//                $_SESSION['cust_id']=$custId;
//            }else if(isset($_SESSION['cust_id'])){  //没有参数，从session中判断
//                $custId=$_SESSION['cust_id'];
//            }
//            $customer=Customer::model()->findByPk($custId);
//            if(empty($custId) || empty($customer)){
//                exit('<h1>用户信息错误，请重新登录</h1>');
//            }
//
//            $this->_userId = $custId;
//            $this->_username =$customer->username;
//        }
//    }
//
//
//    //世界杯第二个活动规则
//    public function actionWorldRuleTwo(){
//        $this->renderPartial("worldRuleTwo");
//    }
//
//    public function actionWorldRuleThree(){
//        $this->renderPartial("worldRuleThree");
//    }
//
//
//

}