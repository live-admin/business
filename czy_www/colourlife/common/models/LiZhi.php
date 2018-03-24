<?php
/*
 * @version 母亲节活动
*/
class LiZhi extends CActiveRecord{
	//奖项配置
	private $prize_arr=array(
			'0' => array('id'=>0,'prize_name'=>'谢谢参与','v'=>95),
			'1' => array('id'=>1,'prize_name'=>'荔枝一元换购码','v'=>5),
	);
	// 所有商品ID,1为省内，2为省外
	private $good_Ids = array (
			/* '1' => array (
					29712,
					29714,
					29708
			), */
			//换新商品
			'1' => array (
					29714,
					31780,
					31782
			),
			//测试
			/* '1' => array (
					29723,
					29724,
					29725,
			), */
			
			/* '2' => array (
					29713,
					29715,
					29711 
			)  */
			//换新商品
			'2' => array (
					29715,
					31781,
					31783 
			)
			//测试
			/* '2' => array (
			 		29723,
					29724,
					29725,
			)  */
	);
	// 秒杀商品
	private $seckill_gIds = array (
			'4' => array (
					30169,
					30170 
			),
			//测试
			/* '4' => array (
					29727,
					29728 
			), */
			'5' => array (
					30171,
					30172 
			),
			'6' => array (
					30173,
					30174 
			),
			'7' => array (
					30175,
					30176 
			),
			'8' => array (
					30177,
					30178 
			),
			'9' => array (
					30179,
					30180 
			),
			'10' => array (
					30181,
					30182 
			),
			'11' => array (
					30183,
					30184 
			),
			'12' => array (
					30185,
					30186 
			),
			'13' => array (
					30187,
					30168 
			) 
	);
	//注册一元购商品
	private $reg_oneyuan_gId = 30152;
	//测试商品
	//private $reg_oneyuan_gId = 29730;
	private $xfzgx=2607; //商家
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 获取省内商品
	 */
	public function getProvinceAllProducts(){
		$goods_arr=$this->good_Ids[1];
		$productList=$this->getProductDetails($goods_arr);
		return $productList;
	}
	
	/**
	 * 获取外省所有商品
	 */
	public function getOtherProvinceAllProducts(){
		$goods_arr=$this->good_Ids[2];
		$productList=$this->getProductDetails($goods_arr);
		return $productList;
	}
	
	/**
	 * 获取商品详细信息
	 */
	private function getProductDetails($goods_arr){
		if (empty($goods_arr)){
			return array();
		}
		$productList=array();
		foreach ($goods_arr as $val){
				$tmp=array();
				$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0',array(':goods_id'=>$val));
				if (empty($cheapArr)){
					continue;
				}
				$productArr=$this->getProductDetail($val);
				if (empty($productArr)){
					continue;
				}
				$tmp['id']= $cheapArr->id;
				$tmp['name']=$productArr['name'];
				if($productArr['ku_cun']<0){
					$productArr['ku_cun']=0;
				}
				$tmp['ku_cun']=$productArr['ku_cun'];
				$tmp['img_name']=Yii::app()->ajaxUploadImage->getUrl($productArr['good_image']);
				$tmp['price']=$productArr['customer_price'];
				$tmp['sales']=$productArr['sales'];
				$productList[]=$tmp;
		}
		return $productList;
	}
	/*
	 * @version 通过产品id获取产品信息
	* @param int goods_id
	* return array
	*/
	public function getProductDetail($goods_id){
		if(empty($goods_id)){
			return false;
		}
		$productArr=Goods::model()->findByPk($goods_id);
		return $productArr;
	}
	
	/**
	 * 获取秒杀商品
	 * @return 
	 */
	public function getSeckillProducts(){
		$productList=array();
		$day=date("j");
		//测试
		//$day=4;
		if (!isset($this->seckill_gIds[$day])){
			return $productList;
		}
		$goods_arr=$this->seckill_gIds[$day];
		if (!empty($goods_arr)){
			foreach ($goods_arr as $key=>$val){
				$tmp=array();
				$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0',array(':goods_id'=>$val));
				if (empty($cheapArr)){
					continue;
				}
				$productArr=$this->getProductDetail($val);
				if (empty($productArr)){
					continue;
				}
				
				$tmp['id']= $cheapArr->id;
				$tmp['name']=$productArr['name'];
				if($productArr['ku_cun']<0){
					$productArr['ku_cun']=0;
				}
				$tmp['ku_cun']=$productArr['ku_cun'];
				$tmp['img_name']=Yii::app()->ajaxUploadImage->getUrl($productArr['good_image']);
				$tmp['price']=$productArr['customer_price'];
				if ($key==0){
					$tmp['date']=date("n月j日 10:00");
				}
				if ($key==1){
					$tmp['date']=date("n月j日 16:00");
				}
				$tmp['sales']=$productArr['sales'];
				$productList[]=$tmp;
			}
		}
		return $productList;
	}
	
	/**
	 * 发放一元购码
	 * 
	 * @param unknown $userID        	
	 * @param unknown $goodID        	
	 * @param unknown $type        	
	 * @return boolean
	 */
	public function sendOneYuanCode($userID, $goodID, $type) {
		if (empty ( $userID ) || empty ( $goodID )) {
			return false;
		}
		$model = OneYuanBuy::model ()->find ( "model=:type and customer_id=:customer_id and goods_id=:gid", array (
				':type' => $type,
				':customer_id' => $userID,
				':gid' => $goodID 
		) );
		if (! empty ( $model )) {
			return false;
		}
		$onecode = OneYuanBuy::sendCode ( $userID, $goodID, 0, $type );
		if ($onecode) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * 抽奖逻辑
	 * @return Ambigous <string, unknown>
	 */
	public function draw($userID){
		if (empty($userID)){
			return false;
		}
		foreach ($this->prize_arr as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}
		$rid=$this->get_rand($arr);
		$prize_name='';
		if ($rid==1){  //祝福语直接返回
			$prize_name=$this->prize_arr[$rid]['prize_name'];
		}
		//抽奖入库
		$result=LizhiRegisterGift::model()->updateByPk($userID,array('is_use'=>1,'prize_name'=>$prize_name,'update_time'=>time()));
		if ($result){
			$tgUrl='';
			//发送一元购码
			if ($rid==1){
				$url=$this->getAllUrl($userID,2);
				$tgUrl=$url['tgHref'].'&pid='.$this->reg_oneyuan_gId;
				$this->sendOneYuanCode($userID, $this->reg_oneyuan_gId, 'lizhi_reg' );
			}
			return array('rid'=>$rid,'prize_name'=>$this->prize_arr[$rid]['prize_name'],'url'=>$tgUrl);
		} else {
			return false;
		}
	}
	/*
	 * @version 概率算法
	* @param array $proArr
	* return array
	*/
	private function get_rand($proArr){
		$result = '';
		//概率数组的总概率精度
		$proSum=0;
		foreach ($proArr as $v){
			$proSum+=$v;
		}
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			}else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
	
	/*
	 * @version 返回url
	* @param $userId
	* return array
	*/
	public function getAllUrl($userId,$type=1){
		if(empty($userId)){
			return false;
		}
		$SetableSmallLoansModel = new SetableSmallLoans();
		if ($type==1){
			//彩生活特供
			$href3 = $SetableSmallLoansModel->searchByIdAndType(39, '', $userId);
		}elseif ($type==2){
			//一元购码
			$href3 = $SetableSmallLoansModel->searchByIdAndType(57, '', $userId);
		}
		
		if ($href3) {
			$tgHref = $href3->completeURL;
		}
		else {
			$tgHref = '';
		}
		return array(
				'tgHref'=>$tgHref,
		);
	}
	
	/**
	 * 获取奖品列表
	 * @return multitype:multitype:NULL
	 */
	public function getPrizeList(){
		$data=array();
		$prizeList=LizhiRegisterGift::model()->findAll("is_use=1 and prize_name!='' order by update_time desc");
		if (!empty($prizeList)){
			foreach ($prizeList as $val){
				$tmp=array();
				$tmp['phone']=substr_replace($val->mobile, '****', 3, 4);
				$tmp['awarderName']=$val->prize_name;
				$data[]=$tmp;
			}
		}
		return $data;
	}
	
	/*
	 * @version 根据产品id和用户id来判断每个人只能购买一个产品,限制一人买一个
	* @param int $goods_id
	* @param int $userId
	* return boolean
	*/
	public function getOrderBuy($goods_id,$userId){
		if(empty($goods_id) || empty($userId)){
			return false;
		}
		$result=$this->getOne($goods_id);
		if($result){
			$sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id where o.seller_id=".$this->xfzgx." and o.`status` in(0,1,3,4,99) and o.buyer_id=".$userId." and ogr.goods_id=".$goods_id;
			$orderArr =Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($orderArr)){
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	/*
	 * @version 限定的产品只能购买一个,数量限制一个
	* @param int $goods_id
	* return boolean
	*/
	public function getOne($goods_id){
		if(empty($goods_id)){
			return false;
		}
		$day=date("j");
		//$day=4;
		if (!isset($this->seckill_gIds[$day])){
			return false;
		}
		$seckill_goods=$this->seckill_gIds[$day];  //当前商品
		if(in_array($goods_id, $seckill_goods)){
			return true;
		}else{
			return false;
		}
	}
}