<?php
class PlatformShopApplyController extends CController {
	/**
	 * index页面，显示抽奖奖项
	 */
	public function actionIndex() {
		$platformShop=new PlatformShopCategory();
		$typeList=$platformShop->findAll("type=1 and isdelete=0 order by seq asc");
		$adList=$platformShop->findAll("type=0 and isdelete=0 order by seq asc");
		$typeListNew=array();
		foreach ($typeList as $type){
			$typeListNew[]=$type;
		}
		$typeList=$typeListNew;
		
		$this->renderPartial("index",array(
					'typeList'=>$typeList,
					'adList'=>$adList,	
				)
		);
	}
	
	/**
	 * 测试 产生抽奖机会
	 */
	public function actionTest() {
// 		// 测试订单产生抽奖机会
// 		$luckyOper = new LuckyOperation ();
// 		$orderId = 365;
// 		$result = $luckyOper->custGetLuckyNum ( $this->_username, $this->_userId, false, $this->_luckyActId );
		
// 		var_dump ( $result );
// 		exit ();
		//$result=PayLib::order_paid('2030555130708195907788',1);
// 		$result=PayLib::order_paid('2030555130708210207920',1);  //此单号的商品，加入到“幸福中国行”活动商品
// 		$customer_id=Yii::app()->user->isGuest ?  0 : Yii::app()->user->id;
// 		var_dump($customer_id);
	}

	public function actionView($id)
	{
		$model = $this->loadModel(intval($id));
		if($model->type==0){
			$view="adview";
		}else{
			$view="view";
		}
		$this->renderPartial($view,array(
				'model'=>$model,
		));
	}

    public function actionAdView($id)
    {
        $model = $this->loadModel(intval($id));
        if($model->type==0){
            $view="adview2";
        }else{
            exit('<h1>链接错误，请返回</h1>');
        }
        $this->renderPartial($view,array(
            'model'=>$model,
        ));
    }
	
	public function actionShowApply(){
		$cateNo=empty($_REQUEST['cate_no'])?0:$_REQUEST['cate_no'];
		$cate=PlatformShopCategory::model()->find('no=:no',array(':no'=>$cateNo));
        if(!$cate)
            exit('<h1>链接错误，请返回</h1>');
        switch ($cate->no )
        {
            case 'SPKD':
                $apply='_apply_spkd';
                break;
            case 'XNFW':
                $apply='_apply_xnfw';
                break;
            case 'BMFW':
                $apply='_apply_bmfw';
                break;
            case 'ZBSH':
                $apply='_apply_zbsh';
                break;
            case 'GGTF':
                $apply='_apply_ggtf';
                break;
            default:
                $apply='_apply_spkd';
        }
		$this->renderPartial($apply,array("cate"=>$cate));
	}
	
	public function actionApply(){
		$model=new PlatformShopApply();
		$ret=array("success"=>0,"data"=>array('msg'=>'请求错误'));
		if(isset($_POST['apply'])){
			$model->attributes=$_POST['apply'];
			$model->ip_address=Yii::app()->request->userHostAddress;
			$model->create_date=date("Y-m-d H:i:s");
			if($model->validate()&&$model->save()){
				$ret=array("success"=>1,"data"=>array('msg'=>''));
			}
			$errors=$model->getErrors();
			$getErrors=array();
			if($errors){
				foreach ($errors as $e){
					$getErrors[]=$e[0];
				}
				$ret=array("success"=>0,"data"=>array('msg'=>'请求错误',"errors"=>$getErrors));
			}
			
		}else{
			$ret=array("success"=>0,"data"=>array('msg'=>'请求错误'));
		}
		echo json_encode($ret);
	}

    public function actionGetRegionList(){
        $pid = intval($_POST['pid']);
        $regionList = Region::model()->enabled()->orderByGBK()->findAll("parent_id=:pid",array(":pid"=>$pid));
        $list = array();
        if (!empty($regionList)) {
            foreach ($regionList as $region) {
                array_push($list, array('id' => $region->id, 'pId' => $region->parent_id, 'name' => $region->name));
            }
            echo CJSON::encode($list);
        }else
            echo CJSON::encode(0);

    }
	
	
	public function loadModel($id)
	{
		$model=PlatformShopCategory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}