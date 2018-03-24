<?php
class CustomerAuditController extends SsoController{
    public $layout="//";


    protected function isCompatibleMode()
    {
        return false;
    }

    protected function getSsoConfig()
    {
        return array(
                'app_id' => "ICEYZZL0-7947-450E-A3A4-8EA455FBD4A8",
                'token' => "jmGEBDFtRqammfa1uHNf",
        );
    }

    public function init()
    {
        $this->checkLogin();
    }
    public function actionUpdate()
    {
        $id = Yii::app()->request->getPost("id",'0');
        $id = intval($id);
        
        $customer = Customer::model()->findByPk($id);
        if($customer == null)
        {
            echo json_encode(array('ok' => 0, 'msg'=>"业主不存在$id"));
            return ;
        }
        
        $name = Yii::app()->request->getPost("name",'');
        $build_id = Yii::app()->request->getPost("build_id",'0');
        $room = Yii::app()->request->getPost("room",'');
        $type = Yii::app()->request->getPost("type",'0');
               
        if(!$this->buildInCommunity($build_id, $customer->community_id))
        {
            echo json_encode(array('ok' => 0, 'msg'=>'选择的楼栋号错误'));
            return ;
        }
        
        $customer->name = $name;
        $customer->build_id = $build_id;
        $customer->room = $room;
        $customer->name = $name;        
        if($type == '1')
        {
            $customer->audit = 1;
        }
        if($customer->save())
        {
            Yii::log(Yii::app()->user->name 
                . "审核业主{$customer->id}，审核后业主名字为{$customer->name},楼栋为{$customer->getBuildName()},房间号为{$customer->room},审核后状态为'审核通过'", 
            CLogger::LEVEL_INFO, 'colourlife.backendApi.customer.Auditok');
            echo CJSON::encode(array("ok"=>1));
        }
        else
        {
            echo json_encode(array('ok' => 0, 'msg'=>'保存失败'));
        }        
    }
    
    private function buildInCommunity($buildId, $communityId)
    {
        $builds = $this->findBuildByCommunityId($communityId);
        foreach ($builds as $b)
        {
            if($b['id'] == $buildId)
            {
                return true;
            }
        }
        return false;
    }
    public function actionGetBuild($community_id = -1)
    {
        $data = $this->findBuildByCommunityId($community_id);        
        echo json_encode($data);
    }
    
    private function findBuildByCommunityId($communityId)
    {
        $communityId = intval($communityId);
        $communityId =  $communityId < 0 ? 0 : $communityId;
        
        $data = array();
        if ($communityId > 0) {
            $criteria=new CDbCriteria();
            $criteria->compare('community_id', $communityId);
            $criteria->order=' display_order  desc';
            $model = Build::model()->enabled()->findAll($criteria);        
            foreach ($model as $v)
            {
                $data[] = array(
                    'id' => $v->id,
                    'name' => $v->name,
                );
            }
        }
        return $data;
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
    
    public function actionView($id)
    {
        //$customer = Customer::model()->findByPk($id);
    	$customer = Customer::model()->findByPk(intval($id));
        if($customer == null)
        {
            throw new CHttpException(400,"业主不存在");
        }
        $builds = $this->findBuildByCommunityId($customer->community_id);
        $this->render("view", array(
            'builds' => $builds,
            'customer' => $customer,
        ));
    }	
    
    public function actionSearchCustomer($community_id="", $is_audit=0, $page=1, $pagesize=10, $string="")
    {
        if(Yii::app()->getAuthManager()->checkAccess('op_backend_apiProperty_customerAudit', $this->employee_id)){
            $model=new Customer();
            if($community_id===""){
                throw new CHttpException(400,"参数错误");
            }
            $criteria=new CDbCriteria();
            if(isset($string) && !empty($string) && $string!==""){
                $criteria->compare("mobile",trim($string),true);
            }
            $criteria->compare("community_id",$community_id);
            $criteria->compare("audit",$is_audit);
            $criteria->order="create_time DESC";
            $page = intval($page) - 1;
            if ($page < 0) {
                $page = 0;
            }
            //分页
            Yii::import('common.components.ActiveDataProvider');
            $dp = new ActiveDataProvider($model, array(
                'criteria' => $criteria,
                'pagination' => array(
                    'currentPage' => $page,
                    'pageSize' => $pagesize,
                    'validateCurrentPage' => false,
                ),
            ));
            $datas = $dp->getData();
            $result = array();
            foreach($datas as $d)
            {
                $result[] = array(
                    'id' => $d->id,
                    'name' => $d->name,
                    'mobile' => $d->mobile,
                    'create_time' => date('Y-m-d', $d->create_time),
                    'build_id' => $d->build_id,
                    'buildName' => $d->buildName,
                    'room' => $d->room,
                    'total' => $dp->totalItemCount,
                    'state' => $is_audit,
                    'stateText' => $is_audit == "0" ? "待审核" : "已审核",
                    'link_url' => $this->createUrl("view", array('id' => $d->id)),
                );

            }
            echo json_encode($result);
        }else{
            throw new CHttpException(400,"没有权限，请联系后台管理人员开启相关权限");
        }
    }
    
	public function actionSearchCommunity()
	{
		$string = empty($_GET['string'])?"":$_GET['string'];
	    $page = empty($_GET['page'])?1:intval($_GET['page']);
	    $pagesize = empty($_GET['pagesize'])?10:intval($_GET['pagesize']);
	    $community = CommunityLogic::getRegionCommunity($this->employee_id, $type = 'search', $string, $page, $pagesize);
	    foreach ($community as &$v){
	        $v['link_url'] = $this->createUrl("CustomerAudit/index", array('community_id' => $v['id']));
	    }
	    echo json_encode(array("communities"=>$community));
	}
}
    