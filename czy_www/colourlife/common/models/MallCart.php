<?php

/**
 * This is the model class for table "mall_cart".
 *
 * The followings are the available columns in table 'mall_cart':
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $number
 * @property integer $community_id
 * @property integer $create_time
 */
class MallCart extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mall_cart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, goods_id, number, community_id, create_time', 'required'),
			array('user_id, goods_id, number, community_id, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, goods_id, number, community_id, create_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'goods_id' => 'Goods',
			'number' => 'Number',
			'community_id' => 'Community',
			'create_time' => 'Create Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('number',$this->number);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MallCart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 购物车列表
	 */
	public function getList($user_id,$community_id){
		if (empty($community_id) || empty($user_id)){
			return array();
		}
		$data = self::model()->findAll("user_id=:userId",array(
				':userId' => $user_id,
//				':cid' => $community_id
		));
		$list = array();
		if (!empty($data)){
			foreach ($data as $val){
				$goods = Goods::model()->findByPk($val->goods_id);
				if (empty($goods) || $goods->is_on_sale == 0 || $goods->state == 1 || $goods->is_deleted == 1){
					continue;
				}
				$shop = Shop::model()->findByPk($goods->shop_id);
				if (empty($shop) || $shop->state == 1 || $shop->is_deleted == 1){
					continue;
				}
				$tmp = array();
				$tmp['id'] = $val->id;
				$tmp['goods_id'] = $goods->id;
				$tmp['name'] = $goods->name;
				$image_arr=explode(':', $goods->good_image);
				if(count($image_arr)>1){
					$tmp['image'] = $goods->good_image;
				}else{
					$tmp['image'] = $goods->GoodImgUrl;
				}
				$tmp['number'] = $val->number;
				$tmp['fp_price'] = $goods->customer_price; //饭票价
				$tmp['bank_price'] = $goods->market_price; //现金价
				$tmp['checked'] = false;
				$tmp['ku_cun'] = $goods->ku_cun; //商品库存

				$list[$goods->shop_id]['shopId'] = $goods->shop_id;
				$list[$goods->shop_id]['shopName'] = $shop->name;
				$list[$goods->shop_id]['checkedAll'] = false;
				$list[$goods->shop_id]['disabled'] = false;
				$list[$goods->shop_id]['goodsList'][] = $tmp;
			}

		}

		return array_values($list);
	}
	
	/**
	 * 添加商品进购物车
	 */
	public function addCart($user_id,$community_id,$goods_id,$number){
		if (empty($user_id) || empty($goods_id) || empty($number) || empty($community_id)){
			return array(
				'status' => 0,
				'msg' => '参数不能为空'
			);
		}
		$data = self::model()->find("user_id=:userId and goods_id=:goods_id and community_id=:cid",array(
				':userId' => $user_id,
				':goods_id' => $goods_id,
				':cid' => $community_id
		));

		if (empty($data)){
			//商品是否存在
			$goods = Goods::model()->findByPk($goods_id);
			if (empty($goods) || $goods->is_on_sale == 0 || $goods->state == 1 || $goods->is_deleted == 1){
				return array(
					'status' => 0,
					'msg' => '商品不存在'
				);
			}
			//是否该小区商品
//			$param = array('community_id'=>$community_id,'goods_id'=>$goods_id);
//			$sellInfo = ShopCommunityGoodsSell::model()->findByAttributes($param);
//			if(empty($sellInfo)){
//				return array(
//						'status' => 0,
//						'msg' => '商品不存在'
//				);
//			}
			$data = new self();
			$data->goods_id = $goods_id;
			$data->user_id = $user_id;
			$data->community_id = $community_id;
			$data->number = $number;
			$data->create_time = time();
			if ($data->save()){
				return array(
					'status' => 1,
					'msg' => '添加成功'
				);
			}
		}else {
			$data->number +=$number;
			if ($data->save()){
				return array(
					'status' => 1,
					'msg' => '添加成功'
				);
			}
		}
		return array(
				'status' => 0,
				'msg' => '添加失败'
			);
	}
	
	/**
	 * 减少或增加数量
	 */
	public function reduceNum($id,$number){
		if (empty($id) || empty($number) || $number == 0){
			return array(
				'status' => 0,
				'msg' => '参数不能为空'
			);
		}
		$data = self::model()->findByPk($id);
		if (empty($data)){
			return array(
				'status' => 0,
				'msg' => '数据不存在'
			);
		}
		$goods = Goods::model()->findByPk($data->goods_id);
		if (empty($goods)){
			return array(
				'status' => 0,
				'msg' => '商品不存在'
			);
		}
		if ($number > $goods->ku_cun){
			return array(
				'status' => 0,
				'msg' => '数量不能大于库存'
			);
		}
		$data->number = $number;
		if ($data->save()){
			return array(
				'status' => 1,
				'msg' => '增加成功'
			);
		}
		return array(
				'status' => 0,
				'msg' => '添加失败'
			);
	}
	
	/**
	 * 获取商品列表
	 */
	public function getGoodsList($goods_id,$community_id,$user_id,$num = 0,$isCart = false,$shop_id = 0){

		if (empty($goods_id) || empty($community_id) || empty($user_id)){
			return array();
		}
		$list = array();
		if ($isCart){ //购物车
			foreach ($goods_id as $val){
				if (empty($val)){
					continue;
				}
				$tmp = array();
				$data = self::model()->find("user_id=:userId and goods_id=:goods_id",array(
						':userId' => $user_id,
						':goods_id' => intval($val),
//						':cid' => $community_id
				));
				if (empty($data)){
					continue;
				}
				$tmp = $this->goodsDetail(intval($val),$community_id);
				if (empty($tmp) || $tmp['shop_id'] != $shop_id){
					continue;
				}
				$tmp['number'] = $data->number;
				$list[] = $tmp;
			}
		}else {
			$tmp = $this->goodsDetail(intval($goods_id),$community_id);
			
			if (!empty($tmp)){
				$tmp['number'] = $num;
				$list[] = $tmp;
			}
		}
		return $list;
	}
	
	/**
	 * 获取商品信息
	 * @param unknown $goods_id
	 * @return multitype:|multitype:unknown NULL string
	 */
	private function goodsDetail($goods_id,$community_id){
		$goods = Goods::model()->findByPk($goods_id);

		if (empty($goods) || $goods->is_on_sale == 0 || $goods->state == 1 || $goods->is_deleted == 1){

            return array();
		}

		//是否该小区商品
//		 $param = array('community_id'=>$community_id,'goods_id'=>$goods_id);
//		$sellInfo = ShopCommunityGoodsSell::model()->findByAttributes($param);
//		if(empty($sellInfo)){
//			return array();
//		}
		$shop = Shop::model()->findByPk($goods->shop_id);
		if (empty($shop) || $shop->state == 1 || $shop->is_deleted == 1){
			return array();
		}
		$tmp = array();
		$tmp['id'] = $goods->id;
		$tmp['shop_id'] = $goods->shop_id;
		$tmp['name'] = $goods->name;
		$image_arr=explode(':', $goods->good_image);
		if(count($image_arr)>1){
			$tmp['image'] = $goods->good_image;
		}else{
			$tmp['image'] = $goods->GoodImgUrl;
		}
		$tmp['red_packet'] = $goods->customer_price; //饭票价
		$tmp['bank_price'] = $goods->market_price; //现金价
		return $tmp;
	}
	
	/*
	 * @version 拼装直接购买数组
	* @param  array $goodInfo 购物车数组
	*/
	public function shopPingArr($goodInfo){
		if(empty($goodInfo)){
			return false;
		}
		$cart_contents = array();
		$cart_contents[$goodInfo['shop_id']][0]['goods_id']=$goodInfo['goods_id'];
		$cart_contents[$goodInfo['shop_id']][0]['number']=$goodInfo['number'];
		$cart_contents[$goodInfo['shop_id']][0]['customer_price']=$goodInfo['customer_price'];
		$cart_contents[$goodInfo['shop_id']][0]['market_price']=$goodInfo['market_price'];
		$cart_contents[$goodInfo['shop_id']][0]['good_name']=$goodInfo['good_name'];
		$cart_contents[$goodInfo['shop_id']][0]['community_id']=$goodInfo['community_id'];
		$cart_contents[$goodInfo['shop_id']][0]['shop_id']=$goodInfo['shop_id'];
		$cart_contents[$goodInfo['shop_id']][0]['integral_price']=$goodInfo['integral_price'];
		$cart_contents[$goodInfo['shop_id']][0]['integral']=$goodInfo['integral'];
		$cart_contents[$goodInfo['shop_id']][0]['per_customer_amount'] = ($goodInfo['customer_price'] * $goodInfo['number']);
		$cart_contents[$goodInfo['shop_id']][0]['per_market_amount'] = ($goodInfo['market_price'] * $goodInfo['number']);
		return $cart_contents;
	}
	
	/*
	 * @version 拼装直接购买数组
	* @param  array $goodInfo 购物车数组
	*/
	public function cartListInfo($community_id,$good_id_arr,$user_id){
		if(empty($user_id) || empty($community_id) || empty($good_id_arr) || !is_array($good_id_arr)){
			return array();
		}
		$cart_contents = array();
		foreach ($good_id_arr as $val){
			if (empty($val)){
				continue;
			}
			$data = self::model()->find("user_id=:userId and goods_id=:goods_id",array(
					':userId' => $user_id,
					':goods_id' => intval($val),
//					':cid' => $community_id
			));
			if (empty($data)){
				continue;
			}
			$tmp = $this->getGoodsInfo(intval($val),$community_id);
			if (empty($tmp)){
				continue;
			}
			$tmp['community_id'] = $community_id;
			$tmp['number'] = $data->number;
			$tmp['per_customer_amount'] = ($tmp['customer_price'] * $data->number);
			$tmp['per_market_amount'] = ($tmp['market_price'] * $data->number);
			$cart_contents[$tmp['shop_id']][$data->id] = $tmp;
		}
		return $cart_contents;
	}
	
	/**
	 * 商品信息
	 * @param unknown $goods_id
	 * @param unknown $community_id
	 * @return multitype:|multitype:number unknown NULL
	 */
	private function getGoodsInfo($goods_id,$community_id){
		$goods = Goods::model()->findByPk($goods_id);
		if (empty($goods) || $goods['is_on_sale'] == 0 || $goods['state'] == 1 || $goods['is_deleted'] == 1){
			return array();
		}
		$goodInfo = array(
				'goods_id' => $goods_id,
				'customer_price' => $goods['customer_price'],
				'market_price' => $goods['market_price'],
				'good_name' => $goods['name'],
				'shop_id'=>$goods['shop_id'],
				'integral'=>0,
				'integral_price'=>0,
		);
		return $goodInfo;
	}
	
	/*
	 * @version 根据条件清空购物车
	* @param int $community_id 小区id
	* @param array $good_id_arr 产品数组
	*/
	public function destroyGoodIds($community_id,$good_id_arr){

		if(empty($community_id) || empty($good_id_arr)){
			return false;
		}
		foreach($good_id_arr as $key)
		{
			MallCart::model()->deleteAllByAttributes(array('user_id'=>Yii::app()->user->id , 'goods_id'=>$key));
		}
	}

	/*
     * @version 根据商品购物车商品id删除商品
     * @param int id 购物车商品id
     */
	public function deleteProductById($id,$community_id){

		$flag=MallCart::model()->deleteAllByAttributes(array('user_id'=>Yii::app()->user->id,'goods_id'=>$id));
		if($flag){
			return true;
		}else{
			return false;
		}
	}
}
