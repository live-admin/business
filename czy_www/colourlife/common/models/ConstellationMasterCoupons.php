<?php

/**
 * This is the model class for table "constellation_master_coupons".
 *
 * The followings are the available columns in table 'constellation_master_coupons':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $money
 * @property integer $create_time
 */
class ConstellationMasterCoupons extends CActiveRecord
{
	// 商品
 	private $goods_arr = array (
			'1' => array (// 火 (水果)
					32673,
					33387,
					33152,
					33151,
					32674,
					33366,
					32677,
					33438
			),
			'2' => array ( // 水（蓝月亮）
					17148,
					17093,
					17121,
					17197,
					17128,
					17149,
					17239,
					17159,
					17095,
					17207,
					17111,
					17282,
					17251,
					17155,
					17213
			),
			'3' => array (// 土（牛奶）
					13087,
					33173,
					14559,
					14556,
					14282,
					14666,
					14660,
					2248
			),
			'4' => array (// 风（数码）
					33189,
					22056,
					28874,
					28878,
					28882,
					26974,
					32761,
					21168,
					24490,
					24748,
					31793,
					27035,
					22134,
					33193
			)
	); 
/* 	//测试
	private $goods_arr = array (
			'1' => array (// 火 (水果)
					29669,
    				29668,
    				27004,
    				28593,
					29673,
					29667,
					29666,
					29736
			),
			'2' => array ( // 水（蓝月亮）
					26981,
					26980,
					26979,
					26978,
					26977,
					26976,
					26975,
					26974,
					26972,
					26971,
					26970,
					26969,
					26968,
					26967,
					26966
			),
			'3' => array (// 土（牛奶）
					26981,
					26980,
					26979,
					26978,
					26977,
					26976,
					26975,
					26974
			),
			'4' => array (// 风（数码）
					26981,
					26980,
					26979,
					26978,
					26977,
					26976,
					26975,
					26974,
					26972,
					26971,
					26970,
					26969,
					26968,
					26967,
					26966
			)
	); */
	//优惠券
	private $you_hui_quan_arr = array (
			'3' => array (
					'id' => 100000094,
					'total' => 100
			),
			'5' => array (
					'id' => 100000095,
					'total' => 100
			),
			'8' => array (
					'id' => 100000096,
					'total' => 100
			)
	);
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'constellation_master_coupons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, money, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, money, create_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'money' => '优惠券类型（3元,5元,8元）',
			'create_time' => '领取时间',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('money',$this->money);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConstellationMasterCoupons the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取商品
	 */
	public function getProducts($type){
		if (!isset($this->goods_arr[$type])){
			return array();
		}
		$data=array();
		$isCaiTeGong=false;
		$goods_ids=$this->goods_arr[$type];
		if ($type==1){
			$isCaiTeGong=true;
		}
		foreach ($goods_ids as $val){
			$tmp=array();
			$productArr=$this->getProductDetail($val);
			if (empty($productArr)){
				continue;
			}
			$image_arr=array();
			if ($isCaiTeGong){
				$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0',array(':goods_id'=>$val));
				if (empty($cheapArr)){
					continue;
				}
				$id= $cheapArr->id;
				$price=$cheapArr->price;
				$tmp['img_name']=Yii::app()->ajaxUploadImage->getUrl($productArr->good_image);
			}else {
				$id= $val;
				$price=$productArr->customer_price;
				$image_arr=explode(':', $productArr['good_image']);
				if(count($image_arr)>1){
					$tmp['img_name'] = $productArr['good_image'];
				}else{
					$tmp['img_name'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
				}
			}
			$tmp['id']=$id;
			$tmp['name']=$productArr->name;
			$tmp['customer_price']=$price;
			$tmp['market_price']=$productArr->market_price;
			$data[]=$tmp;
		}
		return $data;
	}
	
	/**
	 * 通过资源ID获取商城链接
	 * @param unknown $userId
	 * @param unknown $resourceID
	 * @return boolean|multitype:string
	 */
	public function getUrl($userId,$type){
		if(empty($userId)){
			return false;
		}
		if ($type==1){
			$resourceID=39;  //彩特供url
		}else {
			$resourceID=67; //京东url
		}
		$SetableSmallLoansModel = new SetableSmallLoans();
		$href3 = $SetableSmallLoansModel->searchByIdAndType($resourceID, '', $userId);
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
	 * 获取优惠券
	 */
	public function getYouHuiQuan($userID){
		$data=array();
		$day=date("Ymd");
		$hour=date("H");
		foreach ($this->you_hui_quan_arr as $key=>$val){
			$youhuiquan=YouHuiQuan::model()->findByPk($val['id']);
			if (empty($youhuiquan)){
				continue;
			}
			$tmp=array();
			$youhuiquan_arr=$this->you_hui_quan_arr[$key];
			if ($hour>=10){
				//判断今日是否已领过
				$result=$this->find("customer_id=:cust_id and money=:money and FROM_UNIXTIME(create_time,'%Y%m%d')=:day",array(':cust_id'=>$userID,':day'=>$day,':money'=>$key));
				if (!empty($result)){
					$tmp['isRec']=true;
				}
				//判断今日是否已领完
				$total=$this->count("money=:money and FROM_UNIXTIME(create_time,'%Y%m%d')=:day",array(':money'=>$key,':day'=>$day));
				if ($total>=$val['total']){
					$tmp['isRecOver']=true;
				}
				$tmp['total']=$val['total']-$total;
			}else {
				$tmp['isRec']=false;
				$tmp['isRecOver']=false;
				$tmp['total']=0;
			}
			$tmp['money']=$key;
			$tmp['name']=$youhuiquan->name;
			$tmp['dec']=strip_tags($youhuiquan->dec);
			$tmp['use_start_time']=$youhuiquan->use_start_time;
			$tmp['use_end_time']=$youhuiquan->use_end_time;
			$data[]=$tmp;
		}
		return $data;
	}
	
	/**
	 * 领取优惠券
	 */
	public function addYouHuiQuan($userID,$money){
		if (empty($userID)||!isset($this->you_hui_quan_arr[$money])){
			return -1;
		}
		$hour=date("H");
		if (strpos($hour,'0')===0){
			$hour=str_replace("0","",$hour);
		}
		if (empty($hour)||$hour<10){
			return 0;
		}
		//判断用户信息
		$customer=Customer::model()->findByPk($userID);
		if(empty($customer)||$customer->state==1||$customer->is_deleted==1){
			return -2;
		}
		$youhuiquan=$this->you_hui_quan_arr[$money];
		$day=date("Ymd");
		//判断今日是否已领过
		$result=$this->find("customer_id=:cust_id and money=:money and FROM_UNIXTIME(create_time,'%Y%m%d')=:day",array(':cust_id'=>$userID,':day'=>$day,':money'=>$money));
		if (!empty($result)){
			return -3;
		}
		//判断今日是否已领完
		$total=$this->count("money=:money and FROM_UNIXTIME(create_time,'%Y%m%d')=:day",array(':money'=>$money,':day'=>$day));
		if ($total>=$youhuiquan['total']){
			return -4;
		}
		//数据入库,先写入领取表
		 $model=new self();
		 $model->customer_id=$userID;
		 $model->money=$money;
		 $model->create_time=time();
		 $result=$model->save();
		 if ($result){
		 	//再写入用户优惠券表
		 	$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$youhuiquan['id'],':mobile'=>$customer->mobile));
		 	if(empty($userCouponsArr)){
		 		$uc_model=new UserCoupons();
		 		$uc_model->mobile=$customer->mobile;
		 		$uc_model->you_hui_quan_id=$youhuiquan['id'];
		 		$uc_model->create_time=time();
		 		$result1=$uc_model->save();
		 		if ($result1){
		 			return $money;
		 		}else {
		 			return 0;
		 		}
		 	}else {
		 		return $money;
		 	}
		 }else {
		 	return 0;
		 }
	}
    /*
     * @version 记录日志
     * @param int $customer_id
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addLog($customer_id,$type)
    {
        $log =new ConstellationMasterLog();
        $log->customer_id=$customer_id;
        $log->type=$type;
        $log->create_time=time();
        $result = $log->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}
