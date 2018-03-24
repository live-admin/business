<?php

/**
 * This is the model class for table "bao_all_num".
 *
 * The followings are the available columns in table 'bao_all_num':
 * @property integer $id
 * @property integer $register_num
 * @property integer $activity_num
 * @property integer $banner_click_num
 * @property integer $bao_ling_user_num
 * @property integer $bao_chou_user_num
 * @property integer $red_num
 * @property integer $blue_num
 * @property integer $green_num
 * @property integer $yellow_num
 * @property integer $purple_num
 * @property integer $yao_num
 * @property integer $bao_open
 * @property integer $one_yuan_num
 * @property integer $eweixiu_num
 * @property integer $ezufang_num
 * @property integer $youlun_num
 * @property integer $youhuiquan_num
 * @property integer $youhuiquan_use_num
 * @property integer $create_time
 * @property string $day
 */
class BaoAllNum extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bao_all_num';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('register_num, activity_num, banner_click_num, bao_ling_user_num, bao_chou_user_num, red_num, blue_num, green_num, yellow_num, purple_num, yao_num, bao_open, one_yuan_num, eweixiu_num, ezufang_num, youlun_num, youhuiquan_num, youhuiquan_use_num, create_time', 'numerical', 'integerOnly'=>true),
			array('day', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, register_num, activity_num, banner_click_num, bao_ling_user_num, bao_chou_user_num, red_num, blue_num, green_num, yellow_num, purple_num, yao_num, bao_open, one_yuan_num, eweixiu_num, ezufang_num, youlun_num, youhuiquan_num, youhuiquan_use_num, create_time, day', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'register_num' => '注册人数',
			'activity_num' => '活跃人数',
			'banner_click_num' => 'banner页面点击数',
			'bao_ling_user_num' => '领取赠送宝箱用户数',
			'bao_chou_user_num' => '抽取宝石用户',
			'red_num' => '红宝石获得用户数',
			'blue_num' => '蓝宝石获得用户数',
			'green_num' => '绿宝石获得用户数',
			'yellow_num' => '黄宝石获得用户数',
			'purple_num' => '紫宝石获得用户数',
			'yao_num' => '线下摇一摇次数',
			'bao_open' => '打开宝箱用户数',
			'one_yuan_num' => '一元换购用户数',
			'eweixiu_num' => 'E维修优惠券发放数',
			'ezufang_num' => 'E租房优惠券发放数',
			'youlun_num' => '油轮券发放数',
			'youhuiquan_num' => '优惠券发放数',
			'youhuiquan_use_num' => '优惠券使用数',
			'create_time' => '创建时间',
			'day' => '日期',
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
		$criteria->compare('register_num',$this->register_num);
		$criteria->compare('activity_num',$this->activity_num);
		$criteria->compare('banner_click_num',$this->banner_click_num);
		$criteria->compare('bao_ling_user_num',$this->bao_ling_user_num);
		$criteria->compare('bao_chou_user_num',$this->bao_chou_user_num);
		$criteria->compare('red_num',$this->red_num);
		$criteria->compare('blue_num',$this->blue_num);
		$criteria->compare('green_num',$this->green_num);
		$criteria->compare('yellow_num',$this->yellow_num);
		$criteria->compare('purple_num',$this->purple_num);
		$criteria->compare('yao_num',$this->yao_num);
		$criteria->compare('bao_open',$this->bao_open);
		$criteria->compare('one_yuan_num',$this->one_yuan_num);
		$criteria->compare('eweixiu_num',$this->eweixiu_num);
		$criteria->compare('ezufang_num',$this->ezufang_num);
		$criteria->compare('youlun_num',$this->youlun_num);
		$criteria->compare('youhuiquan_num',$this->youhuiquan_num);
		$criteria->compare('youhuiquan_use_num',$this->youhuiquan_use_num);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('day',$this->day,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaoAllNum the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
