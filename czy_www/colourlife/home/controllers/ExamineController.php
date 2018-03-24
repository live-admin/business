<?php
class ExamineController extends CController {

    private $_username = "";
    private $_userId = 0;
	
	public function actionView($id) {
        $this->checkLogin();
        $id=intval($id);
        $examineCategory = ExamineCategory::model()->findByPk($id);
        if(empty($examineCategory) || $examineCategory->state==1){
            exit('<h1>调查问卷已过期！</h1>');
        }
        $models = ExamineQuestion::model()->findAll(array('condition'=>'category_id=:cid AND state=0','params'=>array(':cid' => $id), 'order' => 't.desc ASC'));
		$this->renderPartial("E_evaluation",array(
                'examineCategory' => $examineCategory,
			    'models' => $models,
				)
		);
	}

	
	public function actionSubmit(){
        $this->checkLogin();
		$model=new Examine("create");
		$ret=array("success"=>0,"data"=>array('msg'=>'请求错误100'));
		if(isset($_POST['code'])&&isset($_POST['cid'])&&isset($_POST['note'])){
            //$ifSubmit = Examine::model()->find("category_id=:cid AND customer_id=:uid",array(':cid'=>$_POST['cid'],':uid'=>$this->_userId));
			$ifSubmit = Examine::model()->find("category_id=:cid AND customer_id=:uid",array(':cid'=>intval($_POST['cid']),':uid'=>$this->_userId));
            $code = $_POST['code'];
            sort($code);
            $answers=implode(',',$code);

            $model->answers=$answers;
            //$model->category_id=$_POST['cid'];
            $model->category_id=intval($_POST['cid']);
			$model->note=$_POST['note'];
			$model->customer_id=$this->_userId;
			$model->create_time=time();



			if($model->validate()&&$model->save()){
                if(!$ifSubmit){
                    $this->sendLucky();
                    $ret=array("success"=>1,"data"=>array('msg'=>'已送抽奖次数'));
                }else{
                    $ret=array("success"=>1,"data"=>array('msg'=>'未送抽奖次数'));
                }
			}
			$errors=$model->getErrors();
			$getErrors=array();
			if($errors){
				foreach ($errors as $e){
					$getErrors[]=$e[0];
				}
				$ret=array("success"=>0,"data"=>array('msg'=>'请求错误101',"errors"=>$getErrors));
			}
			
		}else{
			$ret=array("success"=>0,"data"=>array('msg'=>'请求错误102'));
		}
		echo json_encode($ret);
	}

    private function sendLucky(){
        $custGet = new LuckyCustomerGet ();
        $custGet->cust_name = "调查问卷";
        $custGet->cust_id = $this->_userId;
        $custGet->get_action="Examine";
        $custGet->genner_count = 1;
        $custGet->lucky_act_id = Item::LUCKY_ACT_ID;
        $custGet->create_date = date ( "Y-m-d H:i:s" );
        $custGet->save ();
        $exeRet = LuckyCustCan::model ()->updateAll ( array (
            "cust_can" => new CDbExpression ( "cust_can+" . 5 ),
        ), "cust_id=:custId and lucky_act_id=:luckyActId", array (
            ":custId" => $this->_userId,
            ":luckyActId" => Item::LUCKY_ACT_ID
        ) );
        if($exeRet){
            Yii::log("调查问卷送5次抽奖次数成功",CLogger::LEVEL_INFO,'colourlife.core.Examine.sendLucky');
            return true;
        }else{
            Yii::log("调查问卷送5次抽奖次数失败",CLogger::LEVEL_INFO,'colourlife.core.Examine.sendLucky');
            return false;
        }

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
        }
    }

    
    
    //E评价数据录入
    public function actionE_evaluation(){
     

    if(!empty($_GET['evaluation_1'])){

       
       $result1=self::back_answer($_GET['evaluation_1']);
       $result2=self::back_answer($_GET['evaluation_2']);
       $result3=self::back_answer($_GET['evaluation_3']);
       $result4=self::back_answer($_GET['evaluation_4']);
       $result5=self::back_answer($_GET['evaluation_5']);
       $result6=self::back_answer($_GET['evaluation_6']);
       $result7=self::back_answer($_GET['evaluation_7']);
       $results="1".$result1.",2".$result2.",3".$result3.",4".$result4.",5".$result5.",6".$result6.",7".$result7;
       $userId=0;
    
       //$userId=$_GET['userid'];
       $userId=intval($_GET['userid']);
       $model=new Examine('create');
 
       $model->answers=$results;
       $model->customer_id=$userId;
       $model->category_id = 1;
       $model->create_time = time();
       $model->note = $_GET['note'];
  
       $success = $model->save();
//       if($success){
//        echo "保存成功";   
//       }else{
//           dump($model->getErrors());
//        echo "保存失败";   
//       }
//       exit;
//

        //2017-02-26软硬入口数据收集埋点
        EntranceCountLog::model()->writeOperateLog($userId , '' , $operation_time=time(), 7,'');

        
      $this->redirect("Evaluation_result");  
    }    
        
        
        
        
        
        
        $this->renderPartial("E_evaluation");
    }

     //E评价数据提交
       public function actionEvaluation_result(){
        
        
        
        
        
        
        
        
        $this->renderPartial("Evaluation_result");
    }
    
    
    public  function actionError(){
        
        
        
        
      $this->renderPartial("error");   
    }	
    public function back_answer($answer){
        switch ($answer){
            case "非常满意":
                return "a";
            break;
            case "比较满意":
                return "b";
                break;
            case "一般":
                return "c";
                break;
            case "不满意":
                return "d";
                break;
            case "非常不满意":
                return "e";
                break;
        }   
        
        
    }
    

}