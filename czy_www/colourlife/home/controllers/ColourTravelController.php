<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2016/2/29
 * Time: 10:39
 */

class ColourTravelController extends CController
{
    public $user_id;
    public $_username;
    private $_mobile;
    public $endTime=1458180000;

    public function init()
    {
    	$is_share=Yii::app()->request->getParam('s');
    	if ($is_share!='1'){
    		$this->checkLogin();
    	}
        
    }


	public function actionTest(){
        $TravelShare = TravelShare::model();
        $result = $TravelShare->findAll(array('order'=>'share_like desc'));
        $this->renderPartial('result', array('result'=>$result));
	}

    /**
     * 验证登录
     */
    private function checkLogin()
    {
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
            $this->user_id = $custId;
            $this->_username = $customer->nickname;
            $this->_mobile=$customer->mobile;
        }
    }




    /*活动首页攻略列表*/
    public function actionIndex(){
        $this->addShareLog(0,33,0);    //记录首页打开次数
        $model = TravelIntroduce::model();
        $travelIntroduce = $model->findAll();

        $TravelShare = TravelShare::model();
        $result = $TravelShare->findAll(array('order'=>'share_like desc'));
        $this->renderPartial('result', array('result'=>$result));
        //$this->renderPartial('index',array("travelIntroduce" => $travelIntroduce, "user_id" =>$this->user_id));


    }

    /*攻略详情页*/
    public function actionTravelDetail(){
        $traverId = Yii::app()->request->getQuery("id", 0);
        $traverId = floatval($traverId);

        $this->addShareLog(0,11,$traverId);    //记录攻略详情在ＡＰＰ打开次数

        $model = TravelIntroduce::model()->findByPk($traverId);
        $this->renderPartial('travelDetail',array('model' => $model,"user_id" =>$this->user_id));

    }

    /*游记分享列表*/
    public function actionShareIndex(){
        $model = TravelShare::model();
        $travelShare = $model->findAll(array('order'=>'create_time desc'));
        $this->renderPartial('shareIndex',array("travelShare" => $travelShare, "user_id" =>$this->user_id,"mobile"=>$this->_mobile));
    }

    /*游记详情*/
    public function actionShareDetail(){
        $shareId = Yii::app()->request->getQuery("id", 0);
        $shareId = floatval($shareId);
        $this->addShareLog(0,22,$shareId);    //记录游记详情在ＡＰＰ打开次数
        $model = TravelShare::model()->findByPk($shareId);
        $customer_name='未知';
        if (!empty($model)&&!empty($model->customer_id)){
        	$customer = Customer::model();
        	$customerModel = $customer->findByPk($model->customer_id);
        	if (!empty($customerModel->nickname)){
        		$customer_name=$customerModel->nickname;
        	}
        }

        $this->renderPartial('shareDetail',array('model' => $model,'name' => $customer_name,"user_id" =>$this->user_id));
    }

    /*发布游记*/
    public function actionCreateTravel(){
    	$this->renderPartial('createTravel');
    }

    /*分享得券*/
    public function  actionShare(){
        $customer = Customer::model();
        $customerModel = $customer->findByPk($this->user_id);
        $mobile=$customerModel->mobile;

        $title_id = intval(Yii::app()->request->getParam('title_id'));
        //dump($title_id);
        $type = intval(Yii::app()->request->getParam('type'));
        $customer_id=$this->user_id;

        $shareLogResult = $this->addShareLog($customer_id,$type,$title_id);//记录分享记录

        $travelShare = TravelShare::model()->shareLater($mobile);
        if(empty($travelShare) || $travelShare['num']=='0' || !$shareLogResult){
            echo json_encode(array('status'=>0,'param'=>'nothing'));
            return 0;
        }else {
            echo json_encode(array('status' => 1, 'param' => $travelShare['prize_name'],'price'=>$travelShare['price'],'num'=>$travelShare['num']));
            return 0;
        }
    }

    /*
     * 记录分享*/
    public function addShareLog($customer_id,$type,$title_id)
    {
        $shareLog =new TravelShareLog;
        $shareLog->customer_id=$customer_id;
        $shareLog->type=$type;
        $shareLog->title_id=$title_id;
        $shareLog->create_time=time();
        $result = $shareLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }


    /*点赞*/
    public function actionClickLike()
    {
        $id = intval(Yii::app()->request->getParam('title_id'));
        $type = intval(Yii::app()->request->getParam('type'));
        $start_time = strtotime(date('Y-m-d'));
        $end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
        $travelLike = TravelLike::model();
        $shareLike = $travelLike->findAll('customer_id=:customer_id and title_id=:title_id and type=:type and create_time>=:start_time and create_time<:end_time',
                 array(':customer_id'=>$this->user_id ,':title_id'=>$id, ':type'=>$type , ':start_time'=>$start_time , ':end_time'=>$end_time));
        //dump(count($shareLike));
            if(count($shareLike)>0){
                 echo json_encode(array('status'=>0,'param'=>'你今天已经给这个点过赞了！明天再来吧！'));
                 exit;
                 }
            $likeCount =$travelLike->findAll('customer_id=:customer_id and type=:type and create_time>=:start_time and create_time<:end_time',
                array(':customer_id'=>$this->user_id , ':type'=>$type , ':start_time'=>$start_time , ':end_time'=>$end_time));
            if(count($likeCount) >= 5){
                echo json_encode(array('status'=>0,'param'=>'每天只能点5次赞哦！明天再来吧！'));
                exit;
            }
            /*点赞加1及记录详情*/
            $likeResult = $this->addLog($this->user_id , $id , $type);
            if( $likeResult){
                echo json_encode(array('status'=>1,'param'=>'+1'));
                return 0;
            }else {
                echo json_encode(array('status'=>0,'param'=>'网络不给力哦！'));
                return 0;
            }
    }

    /*点赞统计*/
    private function addLog($customer_id , $title_id , $type)
    {
        $transaction = Yii::app()->db->beginTransaction();
        $travelLike = new TravelLike();
        $travelLike->customer_id = $customer_id;
        $travelLike->title_id = $title_id;
        $travelLike->like = 1;
        $travelLike->type = $type;
        $travelLike->create_time = time();
        try
        {
            $travelLike_result=$travelLike->save();
            if($type==11){
                $travelModel = TravelIntroduce::model();
                $like_num='travel_like';
            }
            elseif($type==22){
                $travelModel = TravelShare::model();
                $like_num='share_like';
            }
            $travelResult = $travelModel->updateAll ( array (
                $like_num => new CDbExpression ( $like_num."+1" )
            ), "id=".$title_id);
            if(empty($travelLike_result) || empty($travelResult)){
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        }
        catch(Exception $e)
        {
            $transaction->rollBack();
            return false;
        }

    }

    /**
     * 上传图片
     */
    public function actionFileupload()
    {
    	//dump($_FILES);
    	if (!empty($_FILES['fileList'])){
    		$day = date('Ymd',time());
    		$baseDir = dirname(__FILE__).'/../../uploads/colourTravel/';
    		$uploadDir = $baseDir.$day;
    		$savePath = '/colourTravel/'.$day;
    		if (!file_exists($baseDir)) {
    			@mkdir($baseDir);
    		}
    		// Create target dir
    		if (!file_exists($uploadDir)) {
    			@mkdir($uploadDir);
    		}
    		$file_arr=$_FILES['fileList'];
    		if( Yii::app()->session[$this->user_id.'img']) {
    			$userImages = Yii::app()->session[$this->user_id.'img'];
    		} else {
    			$userImages = array();
    		}
    		foreach ($file_arr['name'] as $k=>$v){
    			//更改图片名称
    			$img_name=explode(".", $v);
    			$uploadFileName=time().rand(0,10000).'.'.$img_name[1];

    			$uploadFileTemp=$file_arr['tmp_name'][$k];
    			$img_result=move_uploaded_file($uploadFileTemp,$uploadDir.'/'.$uploadFileName);
    			
    			if ($img_result){
    				//压缩图片
    				$conversion = new ImageConversion($uploadDir .'/'. $uploadFileName);
    				$conversion->conversion($uploadDir .'/'. $uploadFileName, array(
    						'w' => 800,   // 结果图的宽
    						'h' => 600,   // 结果图的高
    						't' => 'resize ,clip', // 转换类型
    				));
    				$userImages[]= array(
    						"path" => $uploadDir .'/'. $uploadFileName,
    						'savePath'=>$savePath.'/'. $uploadFileName,
    				);
    			}
    		}
    		Yii::app()->session[$this->user_id.'img']=$userImages;
    	}
    }

    /**
     * 游记发布操作
     */
    public function actionShareTravel(){
    	if (empty($_POST['title'])||empty($_POST['content'])||empty(Yii::app()->session[$this->user_id.'img'])){
    		echo json_encode(array('status'=>0,'msg'=>'参数不能为空！'));
    		exit();
    	}
    	$title=FormatParam::formatGetParams($_POST['title']);
    	$content=FormatParam::formatGetParams($_POST['content']);
    	$str_save_path='';
    	$path_arr=array();
    	if (!empty(Yii::app()->session[$this->user_id.'img'])){
    		$img_arr=Yii::app()->session[$this->user_id.'img'];
    		foreach ($img_arr as $val){
    			if (!file_exists($val['path'])){  //剔除不存在的图片
    				continue;
    			}
    			$str_save_path.=$val['savePath'].';';
    			$path_arr[]=$val['path'];
    		}
    		unset(Yii::app()->session[$this->user_id.'img']);
    	}
    	//判断是否为第一次发布
    	$tmodel=TravelShare::model()->find("customer_id=:customer_id and FROM_UNIXTIME(create_time,'%Y%m%d')=:create_day",array(':customer_id'=>$this->user_id,':create_day'=>date("Ymd")));
    	//dump($str_save_path);
    	$shareTravel=new TravelShare();
    	if (empty($tmodel)){
    		$package=TravelShare::model()->getPackage();  //获得礼包
    		$shareTravel->prize_name=$package['code'];
    		$shareTravel->is_receive=1;
    	}else {
    		$shareTravel->prize_name='';
    		$shareTravel->is_receive=0;
    	}
    	$shareTravel->customer_id=$this->user_id;
    	$shareTravel->share_title=$title;
    	$shareTravel->share_content=$content;
    	$shareTravel->share_like=0;
    	$shareTravel->share_img=$str_save_path;
    	$shareTravel->create_time=time();
    	$shareTravel->update_time=time();
    	if ($shareTravel->save()){
    		
    		$this->redirect('ShareIndex');
    	}else {
    		//上传失败后删除图片
    		if (!empty($path_arr)){
    			foreach ($path_arr as $k=>$v){
    				unset($path_arr[$k]);
    				@unlink($v);
    			}
    		}
    		$this->redirect('CreateTravel');
    	}

    }

    /**
     * 攻略分享页
     */
    public function actionStrategySharePage(){
    	$tid=intval(Yii::app()->request->getParam('t_id'));

        $this->addShareLog(0,111,$tid);    //记录攻略分享页在ＡＰＰ外打开次数

    	$model = TravelIntroduce::model()->findByPk($tid);
    	$this->renderPartial('strategyShare',array('model'=>$model));
    }

    /**
     * 游记分享页
     */
    public function actionTravelSharePage(){
    	$ts_id=intval(Yii::app()->request->getParam('ts_id'));

        $this->addShareLog(0,222,$ts_id);    //记录攻略分享页在ＡＰＰ外打开次数

        $model = TravelShare::model()->findByPk($ts_id);
    	$name='未知';
    	if (!empty($model)&&!empty($model->customer_id)){
    		$cust_model=Customer::model()->findByPk($model->customer_id);
    		if (!empty($cust_model)&&!empty($cust_model->nickname)){
    			$name=$cust_model->nickname;
    		}
    	}
    	$this->renderPartial('travelSharePage',array('model'=>$model,'name'=>$name));
    }
}

/*底部*/



