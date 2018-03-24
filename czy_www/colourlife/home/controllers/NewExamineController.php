<?php
class NewExamineController extends CController {

    private $_username = "";
    private $_userId = 0;
    private $_cust_model = "";
	
	public function actionIndex(){
        $this->checkLogin();
        $r = MilkExamine::model()->find('customer_id=:uid',array(':uid'=>$this->_userId));
        if(!empty($this->_cust_model->community_id)&&$this->_cust_model->community_id==34){
            if($r){
                $this->renderPartial("success");
            }else{
                $this->renderPartial("index");
            }
        }else{
            echo '此区还没有开通';
            exit; 
        }
	}



    public function actionSuccess() {
        $this->checkLogin();
        $this->renderPartial("success");
    }

	
	public function actionSubmit(){
        $this->checkLogin();
		$model=new MilkExamine("create");
		$ret=array("success"=>0,"data"=>array('msg'=>'请求错误100'));
		if(isset($_POST)&&!empty($_POST)){
            $code2 = $_POST['ans2'];
            $code3 = $_POST['ans3'];
            $code4= $_POST['ans4'];
            $code6 = $_POST['ans6'];
            $answers2=implode(',',$code2);
            $answers3=implode(',',$code3);
            $answers4=implode(',',$code4);
            $answers6=implode(',',$code6);

            $model->answers1=$_POST['ans1'][0];
            $model->answers2=$answers2;
            $model->answers3=$answers3;
            $model->answers4=$answers4;
            $model->answers5=$_POST['ans5'][0];
            $model->answers6=$answers6;
			$model->customer_id=$this->_userId;
			$model->create_time=time();
			if($model->validate()&&$model->save()){                
                $ret=array("success"=>1,"data"=>array('msg'=>'问卷调查提交成功'));               
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
            $this->_cust_model = $customer;
        }
    }
	
	

}