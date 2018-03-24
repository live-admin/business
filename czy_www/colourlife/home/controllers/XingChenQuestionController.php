<?php
/*
 * @version 星辰旅游问答
 */
class XingChenQuestionController extends CController{
    private $mobile;
	private $userId;
    public function init(){
        $this->checkLogin();
    }
    /*
     * @version 星辰答题首页
     */
    public function actionIndex()
    {
        $this->renderPartial('index');
    }
    /*
     * @version 提交并判断答案是否正确的逻辑
     */
    public function actionSubmitAnswer(){
        $xingQuestionArr=XingQustion::model()->find('mobile=:mobile',array(':mobile'=>$this->mobile));
        //dump(empty($xingQuestionArr));
        if(empty($xingQuestionArr)){
            $param = (Yii::app()->request->getParam('param'));//BABBAAAAAB
            $q1 =  $param[0];
            if($q1 !=='B'){
                echo json_encode(array('status'=>0,'param'=>'第一题答错啦'));
                return 0;
            };
            $q2 =  $param[1];
            if($q2 !=='A'){
                echo json_encode(array('status'=>0,'param'=>'第二题答错啦'));
                return 0;
            };
            $q3 =  $param[2];
            if($q3 !=='B'){
                echo json_encode(array('status'=>0,'param'=>'第三题答错啦'));
                return 0;
            };
            $q4 =  $param[3];
            if($q4 !=='B'){
                echo json_encode(array('status'=>0,'param'=>'第四题答错啦'));
                return 0;
            };
            $q5 =  $param[4];
            if($q5 !=='A'){
                echo json_encode(array('status'=>0,'param'=>'第五题答错啦'));
                return 0;
            };
            $q6 =  $param[5];
            if($q6 !=='A'){
                echo json_encode(array('status'=>0,'param'=>'第六题答错啦'));
                return 0;
            };
            $q7 =  $param[6];
            if($q7 !=='A'){
                echo json_encode(array('status'=>0,'param'=>'第七题答错啦'));
                return 0;
            };
            $q8 =  $param[7];
            if($q8 !=='A'){
                echo json_encode(array('status'=>0,'param'=>'第八题答错啦'));
                return 0;
            };
            $q9 =  $param[8];
            if($q9 !=='A'){
                echo json_encode(array('status'=>0,'param'=>'第九题答错啦'));
                return 0;
            };
            $q10 =  $param[9];
            if($q10 !=='B'){
                echo json_encode(array('status'=>0,'param'=>'第十题答错啦'));
                return 0;
            };

            $xingModel=new XingQustion();
            $xingModel->mobile=$this->mobile;
            $xingModel->q1=$q1;
            $xingModel->q2=$q2;
            $xingModel->q3=$q3;
            $xingModel->q4=$q4;
            $xingModel->q5=$q5;
            $xingModel->q6=$q6;
            $xingModel->q7=$q7;
            $xingModel->q8=$q8;
            $xingModel->q9=$q9;
            $xingModel->q10=$q10;
            $result=$xingModel->save();
            if(!empty($result)){
                echo json_encode(array('status'=>1,'param'=>'恭喜您全部答对，快去抽取宝石吧！'));
            }else{
                echo json_encode(array('status'=>0,'param'=>'对不起，网络出错了！'));
            }
        }else{
            echo json_encode(array('status'=>0,'param'=>'您已经答过题了！'));
        }
    }
    /*
     * @version 星辰旅游介绍
     */
    public function actionShow()
    {
        $cust_id=$this->userId;
        $this->renderPartial('show',array('cust_id'=>$cust_id));
    }
    /**
	 * 验证登录
	 */
	private function checkLogin(){
		if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
			exit('<h1>用户信息错误，请重新登录</h1>');
		} else {
			$custId = 0;
			if (isset($_REQUEST['cust_id'])) {  //优先有参数的
				$custId = intval($_REQUEST['cust_id']);
				$_SESSION['cust_id'] = $custId;
			} else if (isset($_SESSION['cust_id'])) {  //没有参数，从session中判断
				$custId = $_SESSION['cust_id'];
			}
			$custId=intval($custId);
			$customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
			if (empty($custId) || empty($customer)) {
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
			$this->userId = $custId;
			$this->mobile = $customer->mobile;
		}
	}
    
    
    
    
    
}

