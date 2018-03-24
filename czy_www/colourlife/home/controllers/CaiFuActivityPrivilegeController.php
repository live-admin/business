<?php
/**
 * 彩富活动特权
 * @author gongzhiling
 *
 */
class CaiFuActivityPrivilegeController extends ActivityController{
	public $secret = 'Pr^iv&il*ege';
	public $layout = false;
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'signAuth',
		);
	}
	
	public function accessRules()
	{
		return array(
				array('allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions' => array(),
						'users' => array('@'),
				),
		);
	}
	/**
	 * 首页
	 */
	public function actionIndex(){
		$heads=array();
		$activity=array();
		$custID=$this->getUserId();
		$data=CaifuActivityPrivilege::model()->getAllDatas(intval($custID));
		$customer=$this->getUserInfo();
		$nickname='';
		if (!empty($customer)&&!empty($customer->name)){
			$nickname=$customer->name;
		}
		//第一部分
		if (isset($data[0])){
			$heads=$data[0];
		}
		//活动部分
		if (isset($data[1])){
			$activity=$data[1];
		}
		$this->renderPartial ( "/v2016/caiFuActivityPrivilege/index", array (
				'heads' => $heads,
				'activitys'=>$activity,
				'nickname'=>$nickname
		) );
	}
}