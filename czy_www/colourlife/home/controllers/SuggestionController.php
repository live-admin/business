<?php
class SuggestionController extends CController {

    private $_username = "";
    private $_userId = 0;
	
	public function actionIndex() {
        $this->checkLogin();
		$model = Customer::model()->findByPk($this->_userId);
		$this->renderPartial("index",array(
			    'model' => $model,
				)
		);
	}

    public function actionPraise() {
        $this->checkLogin();
        $model = Customer::model()->findByPk($this->_userId);
        $this->renderPartial("praise",array(
                'model' => $model,
            )
        );
    }
	
	public function actionApply(){
        $this->checkLogin();
		$model=new Suggestion("create");
		$ret=array("success"=>0,"data"=>array('msg'=>'请求错误100'));
		if(isset($_POST['suggestion'])){
			$model->attributes=$_POST['suggestion'];
			$model->user_id=$this->_userId;
			$model->create_time=time();
			if($model->validate()&&$model->save()){
				$ret=array("success"=>1,"data"=>array('msg'=>''));
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
	
	

}