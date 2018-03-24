<?php

/**
 * This is the model class for table "lottery_activity_games".
 *
 * The followings are the available columns in table 'lottery_activity_games':
 * @property integer $id
 * @property string $department
 * @property string $account
 * @property integer $games
 * @property integer $is_delete
 * @property integer $create_time
 * @property integer $update_time
 */
class LotteryActivityGames extends CActiveRecord
{
	public $modelName = '抽奖活动场数录入';
	//是否启用
	public $_state = array (
			''=>'全部',
			'0'=>'启用',
			'1'=>'禁用'
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lottery_activity_games';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('games, state, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('department', 'length', 'max'=>125),
			array('account', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, department, account, games, state, create_time, update_time', 'safe', 'on'=>'search'),
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
			'department' => '事业部',
			'account' => '账号',
			'games' => '场数',
			'state' => '是否启用',
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
		$criteria->compare('department',$this->department,true);
		$criteria->compare('account',$this->account,true);
		$criteria->compare('games',$this->games);
		$criteria->compare('state',$this->state);
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
	 * @return LotteryActivityGames the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 是否删除
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
			case 1:
				$return = '<span class="label label-error">已'.$this->_state[1].'</span>';
				break;
			case 0:
				$return = '<span class="label label-success">已'.$this->_state[0].'</span>';
				break;
		}
		return $return;
	}
	
	/*
	 * @version 启用
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=0;
		$model->update_time=time();
		$result=$model->save();
		if ($result){
			$activity=LotteryActivity::model ()->findAll("account=:account and state=1", array (
					':account' => $model->account 
			) );
			if (!empty($activity)){
				foreach ($activity as $val){
					LotteryActivity::model ()->updateByPk($val->id, array('state'=>0));
				}
			}
		}
	}
	/*
	 * @version 禁用
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=1;
		$model->update_time=time();
		$result=$model->save();
		if ($result){
			$activity=LotteryActivity::model ()->findAll("account=:account and state=0", array (
					':account' => $model->account 
			) );
			if (!empty($activity)){
				foreach ($activity as $val){
					LotteryActivity::model ()->updateByPk($val->id, array('state'=>1));
				}
			}
		}
	}
	/**
	 * 获取总场数
	 */
	public function getTotalGames(){
		$sql="select SUM(games) AS total from lottery_activity_games where state=0";
		$totals =Yii::app()->db->createCommand($sql)->queryAll();
		return $totals[0]['total'];
	}
	
	/**
	 * 判断是否已添加了活动
	 * @param string $account
	 */
	public function isGames($account=''){
		if (empty($account)){
			return false;
		}
		$activity=LotteryActivity::model()->find("account=:account",array(':account'=>$account));
		if (!empty($activity)){
			return true;
		}
		return false;
	}
}
