<?php
/**
 * This is the model class for table "lottery_member".
 *
 * The followings are the available columns in table 'lottery_member':
 * @property integer $id
 * @property integer $customer_id
 * @property string $uname
 * @property string $mobile
 * @property integer $type
 * @property integer $lottery_type
 * @property integer $create_time
 * @property integer $update_time
 */
class LotteryMember extends CActiveRecord
{
    public $modelName = '抽奖用户';
    public $type_arr=array(1=>'员工',2=>'嘉宾');
    public $lottery_type_arr=array(0=>'未中奖',1=>'已中奖');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lottery_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, type, lottery_type, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('uname', 'length', 'max'=>255),
			array('mobile', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, uname, mobile, type, lottery_type, create_time, update_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '彩之云账号',
			'uname' => '姓名',
			'mobile' => '手机号',
			'type' => '类型',
			'lottery_type' => '中奖状态',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
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
		$criteria->compare('uname',$this->uname,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('lottery_type',$this->lottery_type);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LotteryMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取用户
	 * @param unknown $lm_id
	 */
	public function getLotteryMember($lm_id){
		if (empty($lm_id)){
			return array();
		}
		$lotteryMember=LotteryMember::model()->find("id=:id",array(':id'=>$lm_id));
		return $lotteryMember;
	}
    /*
     * @version 员工或者嘉宾
     */
    public function getType(){
        return $this->type_arr[$this->type];
    }
    /*
     * @version 
     */
    public function getLotteryType(){
        return $this->lottery_type_arr[$this->lottery_type];
    }
    
}
