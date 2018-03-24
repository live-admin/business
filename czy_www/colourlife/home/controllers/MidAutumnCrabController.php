<?php
/**
 * 中秋大闸蟹
 * @author gongzhiling
 * @date 2016-9-12 16:44
 */
class MidAutumnCrabController extends ActivityController{
	public $beginTime='2016-09-29 00:00:00';//活动开始时间
	public $endTime='2016-10-31 23:59:59';//活动结束时间
	public $secret = 'mi^da*u^tu%mn^cr`ab';
	private $good_ids = array (
			36568,
			36665,
			36666,
			36667,
			36669
	);
	//测试
	/* private $good_ids = array (
			29669,
			29737,
			29749,
			29751,
			29750
	); */
	private $caifu_youhuiquan=100000107;
	private $youhuiquan=100000108;
	public $layout = false;
	
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'Validity',
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
		$customer = $this->getUserInfo();
		if (empty($customer)){
			exit("参数错误！");
		}
		$userID = $customer->id;
		$isShow = false;
		$isCaiFu = false;
		//判断是否为彩富用户
		$propertyArr = PropertyActivity::model()->find('customer_id=:customer_id and status not in(0,1,98,90)',array(':customer_id'=>$userID));
		if (!empty($propertyArr)){
			$isCaiFu = true;
		}else {
			$appreciationArr = AppreciationPlan::model()->find('customer_id=:customer_id and status not in(0,1,98,90)',array(':customer_id'=>$userID));
			if (!empty($appreciationArr)){
				$isCaiFu = true;
			}
		}
		if (!isset(Yii::app()->session[$userID.'is_caifu']) || Yii::app()->session[$userID.'is_caifu'] !=$isCaiFu){
			Yii::app()->session[$userID.'is_caifu'] = $isCaiFu;
		}
		//是彩富用户，判断是否已领过优惠券
		if ($isCaiFu){
			$youhuiquan = $this->caifu_youhuiquan;
		}else {
			$youhuiquan = $this->youhuiquan;
		}
		$userCouponsArr = UserCoupons::model()->find("you_hui_quan_id=:you_hui_quan_id and mobile=:mobile",array(":you_hui_quan_id" => $youhuiquan,":mobile" => $customer->mobile));
		if (empty($userCouponsArr)){
			$isShow = true;
		}
		//获取商品列表
		$goods_list = $this->getGoodsList();
		//彩特供
		$ctgUrl = $this->getUrl($userID);
		$this->render('/v2016/midAutumnCrab/index',array(
				'goods_list' => json_encode($goods_list),
				'ctgUrl' => $ctgUrl,
				'isShow' => $isShow,
				'isCaiFu' => $isCaiFu
		));
	}
	
	/**
	 * 领取优惠券，只有彩富用户才能领券
	 */
	public function actionReceive(){
		$customer=$this->getUserInfo();
		if (empty($customer)){
			echo json_encode(array('status'=>0));
			exit();
		}
		if (isset(Yii::app()->session[$customer->id.'is_caifu']) && !empty(Yii::app()->session[$customer->id.'is_caifu'])){
			$youhuiquan = $this->caifu_youhuiquan;
		}else {
			$youhuiquan = $this->youhuiquan;
		}
		$result=$this->addYouHuiQuan($customer->mobile,$youhuiquan);
		if ($result){
			echo json_encode(array('status'=>1));
		}else {
			echo json_encode(array('status'=>0));
		}
	}
	/**
	 * 领取优惠券
	 */
	private function addYouHuiQuan($mobile,$youhuiquan){
		if (empty($mobile) || empty($youhuiquan)){
			return false;
		}
		//添加优惠券
		$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(":you_hui_quan_id" => $youhuiquan,":mobile" => $mobile));
		if(empty($userCouponsArr)){
			$uc_model=new UserCoupons();
			$uc_model->mobile=$mobile;
			$uc_model->you_hui_quan_id=$youhuiquan;
			$uc_model->create_time=time();
			$result1=$uc_model->save();
			if ($result1){
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	/**
	 * 获取所有商品
	 */
	private  function getGoodsList(){
		$productList = array();
		$cacheKey = md5('midAutumncrab_goods_list');
		$productList = Yii::app ()->cache->get ( $cacheKey );
		if (isset($_GET['pageCache']) && $_GET['pageCache'] == 'false'){
			$productList = array();
		}
		//Yii::app ()->cache->delete ( $cacheKey );
		if (empty($productList)){
			foreach ($this->good_ids as $val){
				$tmp = array();
				$nameArr = array();
				$cheapArr = CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status = 0',array(':goods_id'=>$val));
				if (empty($cheapArr)){
					continue;
				}
				$productArr = $this->getProductDetail($val);
				if (empty($productArr)){
					continue;
				}
				$tmp['pid'] = $cheapArr->id;
				$tmp['image'] =Yii::app()->ajaxUploadImage->getUrl($productArr['good_image']);
				if (strpos($productArr->name,"(") !== false){
					$productArr->name = str_replace(array("(",")"), array("（","）"), $productArr->name);
				}
				if (strpos($productArr->name,"（") !== false){
					$nameArr = str_replace(array("【彩食惠】","（","）"), " ", $productArr->name);
					$nameArr = explode(" ", $nameArr);
					$nameArr = array_filter($nameArr); //过滤空值
					$nameArr = array_values($nameArr); //重置key
					$tmp['title'] = !isset($nameArr[0])?'':$nameArr[0];
				}else {
					$tmp['title'] = $productArr->name;
				}
				$tmp['desc1'] = !isset($nameArr[1])?'':$nameArr[1];
				$tmp['desc2'] = !isset($nameArr[2])?'':$nameArr[2];
				$tmp['customer_price'] = $cheapArr->price;
				$tmp['market_price'] = $productArr->market_price;
				$productList[] = $tmp;
			}
			Yii::app ()->cache->set ( $cacheKey, $productList, 86400 );
		}
		return $productList;
	}
	
	/*
	 * @version 通过产品id获取产品信息
	* @param int goods_id
	* return array
	*/
	private function getProductDetail($goods_id){
		if(empty($goods_id)){
			return false;
		}
		$productArr=Goods::model()->findByPk($goods_id);
		return $productArr;
	}
	
	/**
	 * 获取商城链接
	 * @param unknown $userId
	 * @return boolean|multitype:string
	 */
	private function getUrl($userID){
		if(empty($userID)){
			return '';
		}
		$SetableSmallLoansModel = new SetableSmallLoans();
		//彩生活特供
		$href3 = $SetableSmallLoansModel->searchByIdAndType(39, '', $userID);
		if ($href3) {
			$tgHref = $href3->completeURL;
		}
		else {
			$tgHref = '';
		}
		return $tgHref;
	}
}
