<?php

/**
 * This is the model class for table "travel_share".
 *
 * The followings are the available columns in table 'travel_share':
 * @property integer $id
 * @property string $share_title
 * @property string $share_img
 * @property string $share_content
 * @property string $create_time
 * @property integer $customer_id
 * @property integer $share_like
 * @property integer $update_time
 */
class TravelShare extends CActiveRecord
{

	//优惠券数组
	private $prize_quan_arr=array(
		'0' => array('id'=>1,'code'=>100000069,'prize_name'=>'星晨旅游邮轮代金券','price'=>'200元','num'=>'200','v'=>15),
		'1' => array('id'=>2,'code'=>100000064,'prize_name'=>'环球精选优惠券','price'=>'满300元减30元','num'=>'30','v'=>5.83),
		'2' => array('id'=>3,'code'=>100000065,'prize_name'=>'环球精选优惠券','price'=>'满400元减40元','num'=>'40','v'=>5.83),
		'3' => array('id'=>4,'code'=>100000066,'prize_name'=>'环球精选优惠券','price'=>'满500元减50元','num'=>'50','v'=>5.84),
		'4' => array('id'=>5,'code'=>100000067,'prize_name'=>'环球精选优惠券','price'=>'满600元减60元','num'=>'60','v'=>5.84),
		'5' => array('id'=>6,'code'=>100000062,'prize_name'=>'彩生活特供优惠券','price'=>'满100元减20元','num'=>'20','v'=>5.83),
		'6' => array('id'=>7,'code'=>100000063,'prize_name'=>'彩生活特供优惠券','price'=>'满200元减50元','num'=>'50','v'=>5.83),
		'7' => array('id'=>8,'code'=>100000000,'prize_name'=>'未中奖','price'=>'nothing','num'=>'0','v'=>50),
	);


	//发布必得优惠券组合
	private $prize_create_arr=array(
		'0' => array('id'=>1,'code'=>'zuhe01','prize_name'=>'环球精选全场通用满300元减30元+彩生活特供满100元减20元','v'=>50),
		'1' => array('id'=>2,'code'=>'zuhe02','prize_name'=>'环球精选全场通用满400元减40元+彩生活特供满200元减50元','v'=>50),
	);


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

	/**
	 * 分享后得优惠券
	 */
	public  function shareLater($mobile)
	{

		if(empty($mobile)){
			return false;
		}
		if(!($this->checkOver())){
			return false;
		}
		foreach ($this->prize_quan_arr as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}
		$rid = $this->get_rand($arr); //根据概率获取奖项id
		$start_time = strtotime(date('Y-m-d'));
		$end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
		$sharePraiseCount = UserCoupons::model()->findAll('mobile=:mobile and create_time>=:start_time and create_time<:end_time',
			array(':start_time'=>$start_time , ':end_time'=>$end_time , ':mobile'=>$mobile)); //判断当天已获得优惠券张数

		$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',
			array(':you_hui_quan_id'=>$this->prize_quan_arr[$rid-1]['code'],':mobile'=>$mobile)); //获取是否已获得该优惠券

		//dump((count($sharePraiseCount)==0 || count($sharePraiseCount)==2));

//		$isNothing = UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile and create_time>=:start_time and create_time<:end_time',
//			array(':start_time'=>$start_time , ':end_time'=>$end_time , ':mobile'=>$mobile, ':you_hui_quan_id'=>100000000)); //判断当天是否获得未中奖的code券

		if(empty($userCouponsArr) && (count($sharePraiseCount)==0 || count($sharePraiseCount)==2) &&  !($rid==8)){
			//return $this->prize_quan_arr[$rid-1];
			$uc_model = new UserCoupons();
			$uc_model->mobile = $mobile;
			$uc_model->you_hui_quan_id = $this->prize_quan_arr[$rid - 1]['code'];
			$uc_model->create_time = time();
			$result = $uc_model->save();
			if ($result) {
				return $this->prize_quan_arr[$rid - 1];
			} else {
				return false;
			}
		}else {
			return false;
		}
	}



	/*
	 * 检查优惠券是否发完
	 * */
	public function checkOver()
	{
		$userCouponsArr=UserCoupons::model();
		$colourCount = count($userCouponsArr->findAll('you_hui_quan_id>=:start_id and you_hui_quan_id<=:end_id',array(':start_id'=>100000062, ':end_id'=>100000067)));
		$shipCount = count($userCouponsArr->findAll('you_hui_quan_id=:ship_id and create_time>:create_time',array(':ship_id'=>100000069, ':create_time'=>1457755200)));
		if(($colourCount+$shipCount)>500){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 获取礼包
	 * @return array
	 */
	public function getPackage(){
		$pkey=array_rand($this->prize_create_arr);
		return $this->prize_create_arr[$pkey];
	}

	/*
	 * @version 优惠券全部插入
	* @param string $mobile
	* return boolean
	*/
	public function getPackageData($id,$code,$mobile){
		if(empty($id)||empty($mobile)||empty($code)){
			return array();
		}
		$type='';
		$code_arr=array();
		if ($code=='zuhe01'){   //组合1
			$code_arr=array(100000064,100000062);
			$type=1;
		}elseif ($code=='zuhe02'){  //组合2
			$code_arr=array(100000065,100000063);
			$type=2;
		}
		if (empty($code_arr)){
			return array();
		}
		$str='';
		$create_time=time();
		foreach ($code_arr as $v){
			$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$v,':mobile'=>$mobile));
			if(!empty($userCouponsArr)){
				continue;
			}else{
				$str .= "('" .$mobile."',".$v.",0,0,".$create_time."),";
			}
		}
		if($str!=''){
			$str = trim($str, ',');
			$sql="insert into user_coupons(mobile,you_hui_quan_id,is_use,num,create_time) values {$str}";
			$res = Yii::app()->db->createCommand($sql)->execute();
			if($res){
				TravelShare::model()->updateByPk($id, array('is_receive'=>2));
				return array('type'=>$type);
			}else{
				return array();
			}
		}else{
			return array();
		}
	
	}


	/*获取用户手机号码*/
	public function getMobile($customer_id){
		$customer = Customer::model();
		$mobile = $customer->findByPk($customer_id)->mobile;
		$mobile=substr_replace($mobile,"****",3,4);
		return $mobile;
	}
	


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'travel_share';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('share_img, share_content', 'required'),
			array('create_time, customer_id, share_like, update_time, is_receive', 'numerical', 'integerOnly'=>true),
			array('share_title', 'length', 'max'=>255),
			array('prize_name', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, share_title, share_img, share_content, create_time, customer_id, share_like, update_time, prize_name, is_receive', 'safe', 'on'=>'search'),
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
			'id' => '游记分享主键ID',
			'share_title' => '标题',
			'share_img' => '图片',
			'share_content' => '内容',
			'create_time' => '创建时间',
			'customer_id' => '用户ID',
			'share_like' => '点赞总数',
			'update_time' => '点赞更新时间',
			'prize_name' => '礼包',
			'is_receive' => '是否已领取（0为没礼包，1为未领取，2为已领取）',
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
		$criteria->compare('share_title',$this->share_title,true);
		$criteria->compare('share_img',$this->share_img,true);
		$criteria->compare('share_content',$this->share_content,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('share_like',$this->share_like);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('is_receive',$this->is_receive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TravelShare the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
