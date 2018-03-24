<?php
/**
 * 约惠中秋活动
 * @author gongzhiling
 *
 */
class JingDongCrazyController extends ActivityController{
	public $beginTime='2016-11-07';//活动开始时间
	public $endTime='2017-12-31 23:59:59';//活动结束时间
	public $secret = 'pre&fere^nti*al';
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
    //10元购
//    private $good_ids = array (
//        22370,
//        26519,
//        26520,
//        26683,
//        26690,
//        26721,
//        15091,
//        15012,
//        15114,
//        26673,
//        15046,
//        15053,
//        11242,
//        29144,
//        35336,
//        11817,
//        10479,
//        11887,
//        13266,
//        13363,
//        16303,
//        17124,
//        17154,
//        17541,
//        22353,
//        14541,
//        14545,
//        22994,
//        23025,
//        22363,
//        25170,
//        25179,
//        23796,
//        25838,
//        25842,
//        25853,
//        25200,
//        25216,
//        29716,
//        22425,
//        22304,
//        22346,
//        23044,
//        37682,
//        36300,
//        14866,
//        14869,
//        15014,
//        34806,
//        34883,
//        34947,
//	);
    //年货
    private $good_ids = array (
        39985,
        39986,
        39987,
        39988,
        39989,
        23745,
        21058,
        15149,
        14285,
        39990,
        39991,
        39992,
        39993,
        22876,
        39994,
        39995,
        39996,
        39997,
        30154,
        39884,
        39890,
        39885,
        39912,
        39998,
        39913,
        39914,
        39916,
        39999,
        40000,
        40001,
        40002,
        14229,
        14234,
        14571,
        40003,
        40004,
        40005,
        39924,
        40006,
	);
	//测试
// 	private $good_ids = array (
//			26974,
//			26932,
//			26931,
//			26930,
//			26929,
//			26928,
//			26927,
//			26926,
//			26925,
//			26924,
//			26923,
//			26922,
//			26921,
//			26920,
//			26919,
//			20769,
//			20768,
//			20767,
//			20766,
//			20765,
//			26918,
//	); 
	/**
	 * 首页
	 */
	public function actionIndex(){
		//获取所有商品
		$cacheKey = md5 ( 'goodsList' );
		$goodsList=$this->getGoodsList($cacheKey,$this->good_ids);
		//获取京东url
		$SetableSmallLoansModel = new SetableSmallLoans();
		$jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->getUserId(),false);
		$this->renderPartial("/v2016/jingdongCrazy/index",array('jd_url'=>$jingdong->completeURL,'goodsList'=>json_encode($goodsList)));
	}
	/**
	 * 获取所有商品
	 */
	private  function getGoodsList($cacheKey,$goodsArr){
		if (empty($goodsArr)){
			return array();
		}
		$productList=array();
		$productList=Yii::app ()->cache->get ( $cacheKey );
		if (isset($_GET['pageCache'])&&$_GET['pageCache']=='false'){
			$productList=array();
		}
		//Yii::app ()->cache->delete ( $cacheKey );
		if (empty($productList)){
			foreach ($goodsArr as $key=>$val){
				$tmp=array();
				$productArr=Goods::model()->findByPk($val);
				if (empty($productArr)){
					continue;
				}
				$tmp['id']= $val;
				$tmp['name']=$productArr['name'];
				if($productArr['ku_cun']<0){
					$productArr['ku_cun']=0;
				}
				$tmp['ku_cun']=$productArr['ku_cun'];
				$image_arr=explode(':', $productArr['good_image']);
				if(count($image_arr)>1){
					$tmp['img_name'] = $productArr['good_image'];
				}else{
					$tmp['img_name'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
				}
				$tmp['price']=$productArr['customer_price'];
				$productList[]=$tmp;
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
}

