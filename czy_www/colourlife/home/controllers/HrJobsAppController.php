<?php

class HrJobsAppController extends CController
{

    public $modelName = 'Jobs';
    private $_id; //文章ID
    private $_cate_id;
    
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
    	return array(
    			// captcha action renders the CAPTCHA image displayed on the contact page
    			'captcha' => array(
    					'class' => 'CCaptchaAction',
    					'backColor' => 0xFFFFFF,
    					'maxLength' => '4', // 最多生成几个字符
    					'minLength' => '4', // 最少生成几个字符
    					'height' => '40',
    					'transparent' => true, //显示为透明，当关闭该选项，才显示背景颜色
    			),
    	);
    }

    public function actionIndex($id = 0)
    {
    	$this->pageTitle="工作机会-彩之云";
        $companys = HrCompany::model()->findAll("isdelete=0");	//公司列表
        if(empty($id)){  //传过来的id无效，取第一个有效的展示
        	$id=$companys[0]['id'];
        }
//         $companysNew=array();
//         foreach ($companys as $c){
//         	$companysNew[$c->id]=$c;
//         }
//         $companys=$companysNew;
        //取得该id下的职位列表
        $jobList=HrInvite::model()->findAll("disabled=0 and isdelete=0 order by create_date desc");
        $this->renderPartial('index', array(
            'right' => $companys[0],//没有列表，只有一个
        	'id'=>$id,	//选中的id
        	'jobs'=>$jobList,
        ));
    }

    public function actionView($id)
    {
    	$this->pageTitle="工作机会-彩之云";
    	$id=intval($id);
        //取得该id的信息
        $job=HrInvite::model()->find("id=".$id." and disabled=0 and isdelete=0 ");
        $id=empty($job)?0:$job->comp_id;
        $this->renderPartial('view', array(
        	'id'=>$id,	//选中的id
        	'job'=>$job,
        ));
    }
    
    public function actionApplyApp(){
    	$result=array("success"=>0,'data'=>array("msg"=>'系统异常'));
    	$model = new HrApply();
    	if (isset($_POST['HrApply'])) {
    		$model->setScenario($this->action->id);
    		$model->attributes = $_POST['HrApply'];
    		$model->apply_date=date("Y-m-d H:i:s");
    		if ($model->save()) {
    			$result=array("success"=>1,'data'=>array("msg"=>'提交成功'));
    		}else{
				$errors=$model->getErrors();
				$errorsNew=array();
				foreach($errors as $e){
					$errorsNew[]=$e;
				}
				$errors=$errorsNew;
				$result=array("success"=>0,'data'=>array("msg"=>'系统异常','errors'=>$errors));
    		}
    	}
    	echo json_encode($result);
    }
    
    public function actionToApply(){
    	$id=isset($_REQUEST['id'])?$_REQUEST['id']:0;
    	if(empty($id)){
    		return false;
    	}
    	$this->renderPartial("apply",
    			array("id"=>$id,"model"=>new HrApply()));
    }

}