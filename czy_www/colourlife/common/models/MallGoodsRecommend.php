<?php

/**
 * This is the model class for table "mall_goods_recommend".
 *
 * The followings are the available columns in table 'mall_goods_recommend':
 * @property integer $id
 * @property integer $goods_id
 * @property integer $sorting
 * @property integer $state
 * @property integer $create_time
 */
class MallGoodsRecommend extends CActiveRecord
{
	public $modelName = '推荐商品';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mall_goods_recommend';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, sorting, create_time', 'required'),
			array('goods_id, sorting, state, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goods_id, sorting, state, create_time', 'safe', 'on'=>'search'),
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
				'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
		);
	}


	//是否生效
	public $_state = array (
			'' => '全部',
			'0' => '失效',
			'1' => '生效'
	);

	public function getStateText($state = '')
	{
		$return = '';
		switch ($state) {
			case '':
				$return = "";
				break;
			case 1:
				$return = '<span class="label label-success">已'.$this->_state[1].'</span>';
				break;
			case 0:
				$return = '<span class="label label-error">已'.$this->_state[0].'</span>';
				break;
		}
		return $return;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'goods_id' => '商品ID',
			'sorting' => '排序ASC',
			'state' => '是否生效',
			'create_time' => '添加时间',
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
		$criteria->compare('sorting',$this->sorting);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MallGoodsRecommend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * @version 生效功能
	*/
	public function up(){
		$model = $this->findByPk($this->getPrimaryKey());
		$model->state = 1;
		$model->save();
	}
	/*
	 * @version 失效功能
	*/
	public function down(){
		$model = $this->findByPk($this->getPrimaryKey());
		$model->state = 0;
		$model->save();
	}
}
