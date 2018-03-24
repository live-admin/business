<?php

/**
 * This is the model class for table "lottery_activity_prize".
 *
 * The followings are the available columns in table 'lottery_activity_prize':
 * @property integer $id
 * @property integer $lottery_activity_id
 * @property string $prize_name
 * @property integer $total_num
 * @property integer $state
 * @property integer $create_time
 * @property integer $update_time
 */
class LotteryActivityPrize extends CActiveRecord
{
	public $_aid;
	public $modelName = '抽奖活动奖项';
	//是否启用
	public $_state = array (
			''=>'全部',
			'0'=>'禁用',
			'1'=>'启用'
	);
	public $_type = array (
			'0'=>'等奖',
			//'1'=>'饭票'
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lottery_activity_prize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lottery_activity_id, total_num, state, type, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('prize_name', 'length', 'max'=>100),
			array('money', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lottery_activity_id, prize_name, total_num, money, state, type, create_time, update_time', 'safe', 'on'=>'search'),
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
			'lottery_activity_id' => '活动名称',
			'prize_name' => '奖项名称',
			'total_num' => '奖项数量',
			'money' => '金额',
			'state' => '是否启用',
			'type' => '奖项类型',
			'create_time' => '创建时间',
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

		if (!empty($this->_aid)){
			$this->lottery_activity_id=$this->_aid;
		}
		$criteria->compare('id',$this->id);
		$criteria->compare('lottery_activity_id',$this->lottery_activity_id);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('total_num',$this->total_num);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('type',$this->type);
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
	 * @return LotteryActivityPrize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 是否启用
	 * @param string $state
	 * @return string
	 */
	public function getStateName($state = '')
	{
		$return = '';
		switch ($state) {
			case '':
				$return = "";
				break;
			case 0:
				$return = '<span class="label label-error">已'.$this->_state[0].'</span>';
				break;
			case 1:
				$return = '<span class="label label-success">已'.$this->_state[1].'</span>';
				break;
		}
		return $return;
	}
	
	/**
	 * 奖项类型
	 * @param string $type 0为等奖，1为饭票
	 * @return string
	 */
	public function getTypeName($type = '')
	{
		return $this->_type[$type];
	}
	
	/**
	 * 获取活动名称
	 * @param unknown $aid
	 * @return string
	 */
	public function getActivityName($aid){
		if (empty($aid)){
			return '';
		}
		$name='';
		$activity=LotteryActivity::model()->findByPk($aid);
		if (!empty($activity)){
			$name=$activity->activity_name;
		}
		return $name;
	}
	/*
	 * @version 启用功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=1;
		$model->update_time=time();
		$result=$model->save();
		if ($result){
			$all = $this->findAll ( 'state=1 and lottery_activity_id=:lottery_activity_id order by update_time desc', array (
					':lottery_activity_id' => $model->lottery_activity_id 
			) );
			if (!empty($all)&&count($all)>1){
				unset($all[0]);
				foreach ($all as $val){
					$this->updateByPk($val->id, array('state'=>0));
				}
			}
		}
	}
	/*
	 * @version 禁用功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=0;
		$model->update_time=time();
		$model->save();
	}
}
