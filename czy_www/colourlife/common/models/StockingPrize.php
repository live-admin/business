<?php

/**
 * This is the model class for table "stocking_prize".
 *
 * The followings are the available columns in table 'stocking_prize':
 * @property string $id
 * @property string $name
 * @property integer $chance
 * @property integer $amount
 * @property integer $state
 * @property integer $desc
 * @property integer $type
 * @property integer $code
 */
class StockingPrize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $modelName = '奖品管理';
	//是否启用
	public $_isstate = array (
		'' => '全部',
		'0' => '启用',
		'1' => '禁用'
	);
	//商家类型
	public $_type = array (
		'' => '全部',
		'0' => '谢谢参与',
		'1' => '券',
	);
	public function tableName()
	{
		return 'stocking_prize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, chance, amount', 'required'),
			array('chance, amount, state,type', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('desc', 'length', 'max'=>64),
			array('code', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, chance, amount, state,desc,type,code', 'safe', 'on'=>'search'),
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
			'name' => '奖品名称',
			'chance' => '中奖概率',
			'amount' => '奖品数量',
			'state' => '奖品状态',
			'desc' => '奖品描述',
			'type' => '奖品类型',
			'code' => '券码',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('chance',$this->chance);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('state',$this->state);
		$criteria->compare('desc',$this->desc);
		$criteria->compare('type',$this->type);
		$criteria->compare('code',$this->code);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StockingPrize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 获取上下架名称
	 * @param string $state
	 * @return string
	 */
	public function getIsUpName($isstate = '')
	{
		$return = '';
		switch ($isstate) {
			case '':
				$return = "";
				break;
			case 0:
				$return = '<span class="label label-success">已'.$this->_isstate[0].'</span>';
				break;
			case 1:
				$return = '<span class="label label-error">已'.$this->_isstate[1].'</span>';
				break;
		}
		return $return;
	}

	/*
	 * @version 上架功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=1;
		$model->save();
	}
	/*
	 * @version 下架功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=0;
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
