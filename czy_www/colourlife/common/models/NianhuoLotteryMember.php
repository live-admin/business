<?php

/**
 * This is the model class for table "nianhuo_lottery_member".
 *
 * The followings are the available columns in table 'nianhuo_lottery_member':
 * @property integer $id
 * @property string $mobile
 * @property integer $prize_id
 * @property string $prize_name
 * @property integer $type
 * @property integer $day
 * @property integer $create_time
 * @property string $back_amount
 * @property integer $back_status
 * @property string $back_sn
 */
class NianhuoLotteryMember extends CActiveRecord
{
    public $modelName = '名单';
    public $type_arr=array(1=>'采花中奖',2=>'免单大奖',3=>'送送送');//是否补发
    public $back_status_arr=array(1=>'待发送',2=>'发送成功',97=>'发送失败');//是否启用(0禁用，1启用)
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nianhuo_lottery_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, day, create_time,update_time, back_status', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>15),
            array('prize_id', 'length', 'max'=>32),
			array('prize_name', 'length', 'max'=>100),
			array('back_amount', 'length', 'max'=>10),
			array('back_sn', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, prize_id, prize_name, type, day, create_time,update_time,back_amount, back_status, back_sn', 'safe', 'on'=>'search'),
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
			'mobile' => '用户手机号',
			'prize_id' => '奖品ID',
			'prize_name' => '奖品名称',
			'type' => '类型',
			'day' => '年月日',
			'create_time' => '添加时间',
            'update_time' => '修改时间',
			'back_amount' => '返还金额',
			'back_status' => '返回饭票状态',
			'back_sn' => '返回饭票对应的订单号',
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
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('day',$this->day);
		$criteria->compare('create_time',$this->create_time);
        $criteria->compare('update_time',$this->update_time);
		$criteria->compare('back_amount',$this->back_amount,true);
		$criteria->compare('back_status',$this->back_status);
		$criteria->compare('back_sn',$this->back_sn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NianhuoLotteryMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /*
     * @version 获取类型
     */
    public function getType()
    {
        return $this->type_arr[$this->type];
    }
    /*
     * @version 获取状态
     */
    public function getBackStatus()
    {
        return $this->back_status_arr[$this->back_status];
    }
    /*
     * @version 返回饭票
     */
    public function back(){
		$model=$this->findByPk($this->getPrimaryKey());
        $mobile=$model->mobile;
        $custArr=Customer::model()->find('mobile=:mobile and state=0 and is_deleted=0',array(':mobile'=>$mobile));
        $amount=$model->back_amount;
        $items = array(
            'customer_id' =>$custArr['id'],
            'from_type' => Item::RED_PACKET_FROM_TYPE_NINA_MIANDAN,
            'sum' => $amount,
            'sn' =>'red_packet_from_type_nian_miandan',
        );
        $redPacked = new RedPacket();
        $execute=$redPacked->addRedPacker($items);
        if($execute){
            $model->back_status=2;
            if($model->save()){
                $title="年货免单通知";
                $content="尊敬的用户，您通过【免单/送送送】获取饭票【".$amount."】元";
                PushInformation::model()->createNian($mobile,$title,$content);
            }
            
        }
	}
}
