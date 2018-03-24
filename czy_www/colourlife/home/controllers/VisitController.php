<?php
class VisitController extends CController {

    private $_username = '';
    private $_userId = '';

    /**
     * index页面
     */
    public function actionIndex() {
        $this->checkLogin();
        //$isGuest=Yii::app ()->user->isGuest;

        $criteria=new CDbCriteria;
        $criteria->condition='customer_id=:cid';
        $criteria->params=array(':cid'=>$this->_userId);
        $criteria->order='id desc';
        $model=Visit::model()->find($criteria);
//        if(!$model)
//        {
//            exit('<h1>用户信息错误，请返回</h1>');
//        }
        $count=Visit::model()->count($criteria);

        $this->renderPartial( "index",
            array (
                'model'=>$model,
                'count'=>$count,
            ));
    }

    public function actionView($id) {
        $this->checkLogin();

        $criteria=new CDbCriteria;
        $criteria->condition='id=:id AND customer_id=:cid';
        $criteria->params=array(':id'=>$id,':cid'=>$this->_userId);
        $model=Visit::model()->find($criteria);
        if(!$model)
        {
            exit('<h1>用户信息错误，请返回</h1>');
        }

        $this->renderPartial( "view",
            array (
                'model'=>$model,
            ));
    }

    public function actionHistory() {
        $this->checkLogin();
        //$isGuest=Yii::app ()->user->isGuest;

        $criteria=new CDbCriteria;
        $criteria->condition='customer_id=:cid';
        $criteria->params=array(':cid'=>$this->_userId);
        $criteria->order='id desc';
        $models=Visit::model()->findAll($criteria);
        if(!$models)
        {
            exit('<h1>用户信息错误，请返回</h1>');
        }

        $this->renderPartial( "history",
            array (
                'models'=>$models,
            ));
    }

    public function actionAccept($id) {
        $id = intval($id);
        $this->checkLogin();
        $model=Visit::model()->findByPk($id);
        if(!$model)
        {
            exit('<h1>用户信息错误，请返回</h1>');
        }

        $this->renderPartial( "accept",
            array (
                'model'=>$model,
            ));
    }

    public function actionReject($id) {
        $id = intval($id);
        $this->checkLogin();
        $model=Visit::model()->findByPk($id);
        if(!$model)
        {
            exit('<h1>用户信息错误，请返回</h1>');
        }

        $this->renderPartial( "reject",
            array (
                'model'=>$model,
            ));
    }

    public function actionEvaluation($id) {
        $this->checkLogin();
        $model=Visit::model()->findByPk($id);
        if(!$model)
        {
            exit('<h1>用户信息错误，请返回</h1>');
        }

        $this->renderPartial( "evaluation",
            array (
                'model'=>$model,
            ));
    }

    public function actionComplain($id) {
        $id = intval($id);
        $this->checkLogin();
        $model=Visit::model()->findByPk($id);
        if(!$model)
        {
            exit('<h1>用户信息错误，请返回</h1>');
        }

        $this->renderPartial( "complain",
            array (
                'model'=>$model,
            ));
    }


    public function actionUpdate() {
        $this->checkLogin();
        //$code = str_shuffle('1234567890');
		//$code = '123456';
        if(!$_POST)
            exit('<h1>用户信息错误，请返回</h1>');

        if($_POST['type']=='accept'){
            $model=Visit::model()->findByPk($_POST['mid']);
            if($model->status==Visit::STATUS_UNTREATED){
                $model->invite_visit_time=$_POST['time'];
                $model->invite_visit_hour=$_POST['hour'];
                $model->reply_time=time();
				//$model->code = $code;
                $model->status=Visit::STATUS_AGREE;
                if($model->save()){
                    echo CJSON::encode(1);
                }else
                    echo CJSON::encode(0);
            }else
                echo CJSON::encode(0);
        }elseif($_POST['type']=='reject'){
            $model=Visit::model()->findByPk($_POST['mid']);
            if($model->status==Visit::STATUS_UNTREATED){
                $model->refuse=$_POST['refuse'];
                $model->reply_time=time();
				//$model->code = $code;
                $model->status=Visit::STATUS_REFUSE;
                if($model->save()){
                    echo CJSON::encode(1);
                }else
                    echo CJSON::encode(0);
            }else
                echo CJSON::encode(0);
        }elseif($_POST['type']=='evaluation'){
            $model=Visit::model()->findByPk($_POST['mid']);
            if($model->status==Visit::STATUS_NOCOMMENTS AND $model->is_complain==Visit::COMPLAIN_NO){
                $model->evaluation_id=$_POST['eid'];
                $model->evaluation=$_POST['content'];
                $model->evaluation_time=time();
				//$model->code = $code;
                $model->status=Visit::STATUS_EVALUATION;
                if($model->save()){
                    echo CJSON::encode(1);
                }else
                    echo CJSON::encode(0);
            }else
                echo CJSON::encode(0);
        }elseif($_POST['type']=='complain'){
            $model=Visit::model()->findByPk($_POST['mid']);
            if($model->status==Visit::STATUS_NOCOMMENTS OR $model->status==Visit::STATUS_EVALUATION){
                $model->is_complain=Visit::COMPLAIN_YES;
				//$model->code = $code;
                if($model->save()){
                    echo CJSON::encode(1);
                }else
                    echo CJSON::encode(0);
            }else
                echo CJSON::encode(0);
        }

    }





    private function checkLogin(){
	    if (isset($_GET['code'])) return $this->checkCode();
        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
            //$this->redirect ( Yii::app ()->user->loginUrl );
//             $result = array ("success" => 0,"data" => array ("location"=>1,"href"=>Yii::app ()->user->loginUrl ) );
// 			echo CJSON::encode($result);
// 			exit();
            exit('<h1>用户信息错误，请重新登录</h1>');
        }else {
            $userId=0;
            if(isset($_REQUEST['userid'])){  //优先有参数的
                $userId=intval($_REQUEST['userid']);
                $_SESSION['userid']=$userId;
            }else if(isset($_SESSION['userid'])){  //没有参数，从session中判断
                $userId=$_SESSION['userid'];
            }
            $customer=Customer::model()->findByPk($userId);
            if(empty($userId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }

            $this->_userId = $userId;
            $this->_username =$customer->username;
        }
    }


       private function checkCode(){
			$code = Yii::app()->request->getParam('code');
			$code = preg_replace('/[\W_]*/', '', $code);
			if (empty($code) && !empty($_SESSION['code'])) $userId = $_SESSION['code'];
			if (empty($code)) exit('<h1>用户信息错误，请重新登录</h1>');
			$visit=Visit::model()->findAll('code=:code',array(':code' => $code));
			$visit = current($visit);
			if(count($visit)<=0){
				exit('<h1>编码有误，请重新登录</h1>');
			}
			$this->_userId = $visit['customer_id'];
			$customer=Customer::model()->findByPk($this->_userId);
			if(count($customer)<=0){
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
			$_SESSION['userid'] = $this->_userId;
			$this->_username =$customer->username;
    }

}