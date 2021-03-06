<?php

/**
 * This is the model class for table "activity_goods".
 *
 * The followings are the available columns in table 'activity_goods':
 * @property integer $id
 * @property integer $goods_id
 * @property integer $type
 * @property integer $shop_type
 * @property integer $is_up
 * @property integer $sort
 * @property string $activity_name
 * @property integer $create_time
 * @property integer $update_time
 * @property decimal $rebate_prize
 */
class ActivityGoods extends CActiveRecord
{
	public $modelName = '活动商品';
	//专区类型
	public $_type = array (
        '0'	=>'全部',
        '1' => '月饼专区',
        '2' => '酒水专区',
        '3' => '香茗专区',
        '4' => '数码家电',
        '5' => '一元换购',
        '6' => '洗护清洁',
        '7' => '粮油副食',
        '8' => '特惠饮品',
        '9' => '休闲零食',
        '10' => '新鲜水果',
        '11' => '手机数码',
        '12' => '家具清洁',
        '13' => '个护化妆',
        '14' => '全球尖货',
        '15' => '旅游产品',
        '16' => '满返专场',
        '17' => '全球尖货(元宵节)',
        '18' => '吃货狂欢(元宵节)',
        '19' => '满汉全席(元宵节)',
        '20' => '美丽聚焦(元宵节)',
        '21' => '数码清单(元宵节)',
        '22' => '智能风暴(元宵节)',
        '23' => '家有萌娃(元宵节)',
        '24' => '焕新生活(元宵节)',
        '25' => '爆款TOP(元宵节)',
        '26' => '大礼包价更低(元宵节)',
        '27' => '生鲜区(元宵节)',
        '28' => '美酒盛宴(元宵节)',
        '29' => '2.14浪漫专场(元宵节)',
        '30' => '满减专场(元宵节)',
        '31' => '吃货狂欢(3.8)',
        '32' => '必抢尖货(3.8)',
        '33' => '爆款直降(3.8)',
        '34' => '省钱大动作(3.8)',
        '35' => '呵护姨妈(3.8)',
        '36' => '女王的美妆(3.8)',
        '37' => '颜值来了(3.8)',
        '38' => '休闲零食(四月团购)',
        '39' => '数码家电(四月团购)',
        '40' => '个护洗护(四月团购)',
        '41' => '发现好货(母亲节)',
        '42' => '手机数码(母亲节)',
        '43' => '口碑零食(母亲节)',
        '44' => '纸品狂欢(母亲节)',
        '46' => '美丽秘诀(母亲节)',
        '47' => '温馨家电(母亲节)',
        '48' => '超值特惠(母亲节)',
        '49' => '居家必备(父亲节)',
        '50' => '送礼佳品(母亲节)',
        '51' => '吃货必备(母亲节)',
        '52' => '热销机型(母亲节)',
        '53' => '智慧生活(母亲节)',
        '54' => '箱包伴行(母亲节)',
        '55' => '运动装(母亲节)',
	);
	//活动类型
	public $_activityType = array (
			'' => '--请选择--',
			'midautumn' => '中秋月饼活动',
			'profitShop'    => '彩富商城',
			'singlesDay' => '双十一电商专惠',
		    'stocking' => '年货庆典',
            'yuanxiao' => '元宵盛典',
            'womeneight' => '3.8妇女节',
            'siyue' => '四月团购',
            'muqin' => '母亲节',
            'fuqin' => '父亲节',
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
			'1' => '饭票商城',
			'2' => '一元购'
	);
	//是否返利
	public $_isRebate = array (
			'' => '全部',
			'0' => '不返利',
			'1' => '返利'
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, type, shop_type, is_up, sort, is_rebate, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('activity_name,rebate_prize', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goods_id, type, shop_type, is_up, sort, activity_name, is_rebate, create_time, update_time,rebate_prize', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'goods_id' => '商品ID',
			'type' => '专区类型',
			'shop_type' => '商城类型',
			'is_up' => '是否上架',
			'sort' => '排序',
			'activity_name' => '活动名称',
			'is_rebate' => '是否返利',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
			'rebate_prize' => '满返价格',
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
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('shop_type',$this->shop_type);
		$criteria->compare('is_up',$this->is_up);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('activity_name',$this->activity_name,true);
		$criteria->compare('is_rebate',$this->is_rebate);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('rebate_prize',$this->rebate_prize);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivityGoods the static model class
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
				$tmp['rebate_prize']= $val->rebate_prize;
				$tmp['name']=$productArr->name;
				$tmp['customer_price']=$price;
				$tmp['market_price']=$productArr->market_price;
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
		}elseif ($type == 3) { //花礼网
			$id = 3;
		}elseif ($type == 4) { //千喜珠宝
			$id = 552;
		}elseif ($type == 5) { //拼团
			$id = 555;
		}elseif ($type == 6) { //彩食惠
			$id = 651;
		}elseif ($type == 7) { //环球精选
			$id = 551;
		}elseif ($type == 8) { //饭票商城
			$id = 854;
		}elseif ($type == 9) { //彩住宅
			$id = 87;
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
	 * 获取专区类型名称
	 * @param string $state
	 * @return string
	 */
	public function getTypeName($type)
	{
		return $this->_type[$type];
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
	
	/**
	 * 是否返利
	 * @param string $state
	 * @return string
	 */
	public function getIsRebateName($isRebate = '')
	{
		return $this->_isRebate[$isRebate];
	}
	/*
	 * @version 上架功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->is_up=1;
		$model->update_time=time();
		$model->save();
	}
	/*
	 * @version 下架功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->is_up=0;
		$model->update_time=time();
		$model->save();
	}
	
	/*
	 * @version 记录日志
	* @param int $customer_id
	* @param string $open_id
	* @param int $type
	* return boolean
	*/
	public function addShareLog($customer_id,$open_id,$type,$goodID=0)
	{
		$shareLog =new MidautumnShareLog();
		$shareLog->customer_id=$customer_id;
		$shareLog->open_id=$open_id;
		$shareLog->type=$type;
		$shareLog->goods_id=$goodID;
		$shareLog->create_time=time();
		$result = $shareLog->save();
		if($result){
			return true;
		}else{
			return false;
		}
	}
}
