<?php
class PropertyFeeController extends SsoController{
    public $layout="//";


    protected function isCompatibleMode()
    {
        return false;
    }

    protected function getSsoConfig()
    {
        return array(
                'app_id' => "ICEWYF00-0495-4686-8EF3-AAD0B441871C",
                'token' => "LkbMwVxeDIsN3nIaExIq",
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
    
    public function actionChangeCommunity()
    {
        $this->render('changecommunity');
    }
    
    public function actionPropertyFeeDetail($id)
    {
        $id = intval($id);
        $data = OtherFeesLogic::findByPk($id);
        $this->render("propertyfeedetail", array('data' => $data));
    }    
	
    public function actionAdvanceFeesList($page=1,$pagesize=10){
        if(empty($_GET['community_id'])){
            throw new CHttpException(400,"参数错误");
        }
        
        $advanceFeesList = OtherFeesLogic::getFreeList('AdvanceFees', 
            intval($_GET['community_id']),
            $page,
            $pagesize,
            'op_backend_apiProperty_advanceFeesView', 
            $this->employee_id);
        
        foreach ($advanceFeesList['detail'] as &$fee){
            $fee['create_time_formatted'] = date('Y-m-d', $fee['create_time']);
            $fee['pay_time_formatted'] = date('Y-m-d', $fee['pay_time']);
            $fee['update_time_formatted'] = date('Y-m-d', $fee['update_time']);
            $fee['link_url'] = $this->createUrl('PropertyFeeDetail', array('id'=>$fee['id']));
        }
        
        echo CJSON::encode(array("advanceFeesList"=>$advanceFeesList));
        
    }
    
    public function actionPropertyFeesList($page=1,$pagesize=10){
        if(empty($_GET['community_id'])){
            throw new CHttpException(400,"参数错误");
        }
    
        $propertyFeesList = OtherFeesLogic::getFreeList(
            'PropertyFees',
            intval($_GET['community_id']),
            $page,
            $pagesize,
            'op_backend_apiProperty_propertyFeesView',
            $this->employee_id);
    
        foreach ($propertyFeesList['detail'] as &$fee){
            $fee['create_time_formatted'] = date('Y-m-d', $fee['create_time']);
            $fee['pay_time_formatted'] = date('Y-m-d', $fee['pay_time']);
            $fee['update_time_formatted'] = date('Y-m-d', $fee['update_time']);
            $fee['link_url'] = $this->createUrl('PropertyFeeDetail', array('id'=>$fee['id']));
        }
        echo CJSON::encode(array("propertyFeesList"=>$propertyFeesList));
    }

//  ICE 接入getRegionCommunity方法
	public function actionSearchCommunity()
	{
		$string = empty($_GET['string'])?"":$_GET['string'];
	    $page = empty($_GET['page'])?1:intval($_GET['page']);
	    $pagesize = empty($_GET['pagesize'])?10:intval($_GET['pagesize']);
	    
	    $community = CommunityLogic::getRegionCommunity($this->employee_id, $type = 'search', $string, $page, $pagesize);
	    
	    foreach ($community as &$v){
	        $v['link_url'] = $this->createUrl("PropertyFee/index", array('community_id' => $v['id']));
	    }
	    echo json_encode(array("communities"=>$community));
	}

}
    