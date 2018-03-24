<?php

/**
 * This is the model class for table "stocking_seckill_goods".
 *
 * The followings are the available columns in table 'stocking_seckill_goods':
 * @property string $id
 * @property integer $goods_id
 * @property integer $shop_type
 * @property integer $is_up
 * @property integer $sort
 * @property string $activity_name
 * @property integer $amount
 * @property integer $type
 */
class StockingSeckillGoods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $modelName = '活动秒杀商品';
	//秒杀区域类型类型
	public $_type = array (
		'' => '全部',
		'1' => '10:00:00',
		'2' => '14:00:00',
		'3' => '16:00:00',
		'4' => '20:00:00'
	);
	//活动类型
	public $_activityType = array (
		'' => '--请选择--',
		'stocking' => '年货庆典',
        'yuanxiao' => '元宵盛典',
        'siyue' => '四月团购',
        'lizhijie'=>'荔枝节'
	);
	//是否上架
	public $_isUp = array (
		'' => '全部',
		'0' => '下架',
		'1' => '上架'
	);
	//商家类型
	public $_shopType = array (
		'' => '全部',
		'0' => '彩特供',
		'1' => '京东',
	);
	public function tableName()
	{
		return 'stocking_seckill_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, sort, activity_name, amount, type', 'required'),
			array('goods_id, shop_type, is_up, sort, amount, type', 'numerical', 'integerOnly'=>true),
			array('activity_name', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goods_id, shop_type, is_up, sort, activity_name, amount, type', 'safe', 'on'=>'search'),
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
			'goods_id' => '商品ID',
			'shop_type' => '商城类型',
			'is_up' => '是否上架',
			'sort' => '排序',
			'activity_name' => '活动名称',
			'amount' => '商品总数量',
			'type' => '秒杀时间段',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('shop_type',$this->shop_type);
		$criteria->compare('is_up',$this->is_up);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('activity_name',$this->activity_name,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StockingSeckillGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 获取商品
	 * @param unknown $activityName
	 * @param $isType
	 * @param $isLimit
	 * @return multitype:
	 */
	public function getProducts($activityName,$isType=false, $isLimit=false){
		if (empty($activityName)){
			return array();
		}
		$data=array();
		$criteria = new CDbCriteria() ;
		$criteria -> condition = 'is_up=1 and activity_name=:activity_name';
		$criteria -> order = 'sort asc';
		$criteria ->params = array(':activity_name'=>$activityName);

		if ($isLimit)
			$criteria -> limit = 2;

		$goodIDs=$this->findAll($criteria);

		if (!empty($goodIDs)){
			foreach ($goodIDs as $val){
				$tmp=array();
				$productArr=$this->getProductDetail($val->goods_id);
				//获取用户已抢数量
//				$number=$this->getProductNumber($val->goods_id);
				
				if (empty($productArr)){
					continue;
				}
				$image_arr=array();
				if ($val->shop_type==1){ //饭票商城
					$tmp['pid']= $val->goods_id;
					$price=$productArr->customer_price;
					$image_arr=explode(':', $productArr['good_image']);
					if(count($image_arr)>1){
						$tmp['img_name'] = $productArr['good_image'];
					}else{
						$tmp['img_name'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
					}
				}elseif ($val->shop_type==2){ //一元购
					$tmp['pid']= $val->goods_id;
					$price=$productArr->cheap_price;
					$tmp['img_name'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
				}else{
					$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status = 0',array(':goods_id'=>$val->goods_id));
					if (empty($cheapArr)){
						continue;
					}
					$tmp['pid']= $cheapArr->id;
					$price=$cheapArr->price;
					$tmp['img_name']=Yii::app()->ajaxUploadImage->getUrl($productArr->good_image);
				}
				$tmp['gid']= $val->goods_id;
				$tmp['shop_type']=$val->shop_type;
				$tmp['name']=$productArr->name;
				$tmp['customer_price']=$price;
				$tmp['market_price']=$productArr->market_price;
				$tmp['amount']=$val->amount;
//				$tmp['number']=$number;
				//是否按照专区分类
				if ($isType){
					$data[$val->type][]=$tmp;
				}else {
					$data[]=$tmp;
				}
			}
			unset($goodIDs);
		}
		return $data;
	}

	/**
	 * 获取商品详情
	 * @param unknown $goods_id
	 * @return array
	 */
	public function getProductDetail($goods_id){
		if(empty($goods_id)){
			return false;
		}
		$productArr=Goods::model()->findByPk($goods_id);
		return $productArr;
	}

	/**
	 * 获取商品已下定单数量
	 * @param unknown $goods_id
	 * @return array
	 */
	public function getProductNumber($goods_id){
		if(empty($goods_id)){
			return false;
		}
		//$number=OrderGoodsRelation::model()->countBySql("select sum(count) as number from order_goods_relation where state=0 and is_lock=0 and goods_id=:goods_id ",array(':goods_id'=>$goods_id));
		$startDay='2017-02-09 00:00:00';
		$endDay='2017-02-28 23:59:59';
		$start_time=strtotime($startDay);
		$end_time=strtotime($endDay);
		$sqlSelect="SELECT  sum(ogr.count) as number 
						from stocking_seckill_goods AS ag 
						LEFT JOIN order_goods_relation AS ogr ON ag.goods_id=ogr.goods_id 
						LEFT JOIN `order` AS o ON o.id=ogr.order_id 
						WHERE ag.activity_name='yuanxiao' AND ag.is_up=1 AND  ogr.state=0 and ogr.is_lock=0 and ogr.goods_id='{$goods_id}' AND o.create_time>={$start_time} AND o.create_time <={$end_time}";
		$query = Yii::app()->db->createCommand($sqlSelect);
		$arr = $query->queryAll();
		$number=$arr[0]['number'];
		if(!$number){
			return 0;
		}
		return $number;
	}

	/**
	 * 获取商家链接
	 */
	public function getShopUrl($userID,$type=0){
		$id = 0;
		if ($type == 1){ //京东
			$id = 67;
		}elseif ($type == 2){ //一元购
			$id = 57;
		}elseif ($type == 0) { //彩特供
			$id = 39;
		}
		if (empty($id)||empty($userID)){
			return '';
		}
		$homeConfig=new HomeConfigResource();
		$href=$homeConfig->getResourceByKeyOrId($id,1,$userID);
		if ($href){
			$url=$href->completeURL;
		}else {
			$url='';
		}
		return $url;
	}

	/**
	 * 获取上下架名称
	 * @param string $state
	 * @return string
	 */
	public function getIsUpName($isUp = '')
	{
		$return = '';
		switch ($isUp) {
			case '':
				$return = "";
				break;
			case 1:
				$return = '<span class="label label-success">已'.$this->_isUp[1].'</span>';
				break;
			case 0:
				$return = '<span class="label label-error">已'.$this->_isUp[0].'</span>';
				break;
		}
		return $return;
	}
	/**
	 * 获取商家名称
	 * @param string $state
	 * @return string
	 */
	public function getShopName($shopType = '')
	{
		return $this->_shopType[$shopType];
	}
	/*
	 * @version 上架功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->is_up=1;
		$model->save();
	}
	/*
	 * @version 下架功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->is_up=0;
		$model->save();
	}
	/**
	 * 获取秒杀时间段名称
	 * @param string $state
	 * @return string
	 */
	public function getTypeName($type)
	{
		return $this->_type[$type];
	}
}
