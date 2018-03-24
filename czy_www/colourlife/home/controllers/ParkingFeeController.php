<?php
class ParkingFeeController extends SsoController{
    public $layout="//";


    protected function isCompatibleMode()
    {
        return false;
    }

    protected function getSsoConfig()
    {
        return array(
                'app_id' => "ICETCF00-40A5-48C1-BE83-0A1FCC21FDA4",
                'token' => "mXxnqEKN3XTvAvaHRUJK",
        );
    }

    public function init()
    {
        $this->checkLogin();
    }
    
    public function actionIndex()
    {
        $community = CommunityLogic::getRegionCommunity($this->employee_id, "list", "", 1, 1);
        if(count($community) == 0)
        {
            $community = array(
                'id' => '0', 
                'name' => '',
                'total' => 0,
                'branch' => ''
            );
        }
        else
        {        
            $community_id = isset($_GET['community_id']) ? intval($_GET['community_id']) : 0;
            $commObj = Community::model()->findByPk($community_id);
            $community = $community[0];
            if($commObj != null)
            {
                $community['id'] = $commObj->id;
                $community['name'] = $commObj->name;
            }
        }
        $this->render("index", array(
            'community' => $community
        ));
    }
    
    private function CheckAccess($permission)
    {
        return Yii::app()->getAuthManager()->checkAccess($permission, $this->employee_id);
    }
    
    public function actionSubmitContinueCard()
    {
        if(!$this->checkAccess("op_backend_apiProperty_parkingFeesView")){
            echo json_encode(array("ok"=>"0", 'msg'=> "没有权限操作续卡功能"));
            return;
        }
        if(empty($_POST['parking_id'])){
            echo json_encode(array("ok"=>"0", 'msg'=> "参数错误"));
            return ;
        }
        $parking_id=intval($_POST['parking_id']);
        $note=empty($_POST['note'])?"":$_POST['note'];
        $model=OthersFees::model()->find("object_id=:object_id AND model=:model",array(":object_id"=>$parking_id,":model"=>"ParkingFees"));
        if($model->status==Item::FEES_TRANSACTION_ERROR){
            $logModel = new OthersFeesLog();
            $state = false;
            if( $stateModel = OthersFees::model()->findByPk($model->id) ){
                $state = ( $stateModel->status == 1 ? true : false );
            }
            $logModel->note = empty($model->note) ? "自动备注,状态" : $note;
            $logModel->user_model = "Employee";
            $logModel->user_id = Yii::app()->user->id;
            $logModel->others_fees_id = $model->id;
            $logModel->status =Item::FEES_TRANSACTION_SUCCESS;
        
            $changeStatus = $model->updateByPk($model->id, array('status' => Item::FEES_TRANSACTION_SUCCESS)); //修改状态
            if( $changeStatus && $state ){
                //停车费缴费成功   发送停车费续卡成功短信
                Yii::app()->sms->sendPropertyPaymentMessage( 'continuedSuccessCards', $stateModel->sn, 'parkingFees' ,$title="" );
            }
            if ( $changeStatus && $logModel->save())
            {
                echo json_encode(array("ok"=>"1"));
            }
            else
            {
                echo json_encode(array("ok"=>"0", 'msg'=> "续卡失败，请重试"));
                return ;
            }
        }else{
            echo json_encode(array("ok"=>"0", 'msg'=> "不能续卡"));
            return ;
        }
    }
    
    public function actionChangeCommunity()
    {
        $this->render('changecommunity');
    }
    
    public function actionView($id)
    {
        $id = intval($id);
        $data = OtherFeesLogic::findByPk($id);
        $this->render("view", array('data' => $data));
    }
    
    public function actionParkingFeesList($page=1,$pagesize=10){
        if(empty($_GET['community_id'])){
            throw new CHttpException(400,"参数错误");
        }
        $parkingFee = OtherFeesLogic::getFreeList(
            'ParkingFees', 
            intval($_GET['community_id']),
            $page, 
            $pagesize, 
            'op_backend_apiProperty_parkingFeesView', 
            $this->employee_id);
        
        foreach ($parkingFee['detail'] as &$fee){
            $fee['create_time_formatted'] = date('Y-m-d', $fee['create_time']);
            $fee['pay_time_formatted'] = date('Y-m-d', $fee['pay_time']);
            $fee['update_time_formatted'] = date('Y-m-d', $fee['update_time']);
            $fee['link_url'] = $this->createUrl('view', array('id'=>$fee['id']));
        }
        
        echo CJSON::encode(array("parkingFeesList"=>$parkingFee));
    }
	

    
	public function actionSearchCommunity()
	{
		$string = empty($_GET['string'])?"":$_GET['string'];
	    $page = empty($_GET['page'])?1:intval($_GET['page']);
	    $pagesize = empty($_GET['pagesize'])?10:intval($_GET['pagesize']);
	    $community = CommunityLogic::getRegionCommunity($this->employee_id, $type = 'search', $string, $page, $pagesize);

        $employee = Employee::model()->findByPk($this->employee_id);
        $community_list = $this->getList($employee->username);
        $community = array_merge($community_list , $community);

	    foreach ($community as &$v){
	        $v['link_url'] = $this->createUrl("ParkingFee/index", array('community_id' => $v['id']));
	    }
	    echo json_encode(array("communities"=>$community));
	}

    /*
    * 获取权限列表
    */
    public function getList($oa_username)
    {
        $result = ICEService::getInstance()->dispatch(
            'jurisdiction/account',
            array(
                'username' => $oa_username,
                'app_code' => 'czy'
            ),
            array(),
            'get'
        );
        $list = [];
        if($result)
        {
            foreach($result as $key => $value)
            {
                $re = ColorcloudCommunity::model()->findByAttributes(array('color_community_id' => $value['org_id']));
                if($re)
                {
                    $list[] =
                        [
                            'id' => $re->community_id,
                            'name' => $value['org_name'],
                            'branch' =>  '彩生活服务集团-'.$value['org_name']
                        ];
                }
            }
        }
        return $list;
    }
}
    