<?php

/**
 * This is the model class for table "surrounding_content".
 *
 * The followings are the available columns in table 'surrounding_content':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $tab_id
 * @property string $content
 * @property string $app_content
 * @property integer $create_time
 */
class SurroundingContent extends CActiveRecord
{
    public $modelName = '周边优惠内容';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'surrounding_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tab_id, shop_id', 'numerical', 'integerOnly'=>true),
			array('content, app_content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, shop_id, tab_id, content, app_content, create_time', 'safe', 'on'=>'search'),
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
            'category'=>array(self::BELONGS_TO,"SurroundingTab",'tab_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tab_id' => '标签',
            'shop_id' => '商家',
			'content' => '内容',
            'app_content' => 'APP内容',
			'create_time' => '创建时间',
		);
	}

    public function getTabName($tab_id = null, $html = true)
    {
        $name = '';
        if(empty($tab_id)){
            $tab_id = $this->tab_id;
        }

        if($model = SurroundingTab::model()->findByPk($tab_id)){
            $name = $model->name;
            if($html && Yii::app()->user->checkAccess('op_backend_surrounding_view')){
                $name = CHtml::link($model->name, '/surroundingTab/'.$model->id);
            }
        }
        return $name;
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
		$criteria->compare('tab_id',$this->tab_id);
		$criteria->compare('content',$this->content,true);
        $criteria->compare('app_content',$this->app_content,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SurroundingContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class'             => 'zii.behaviors.CTimestampBehavior',
                'createAttribute'   => 'create_time',
                'updateAttribute'   => NULL,
                'setUpdateOnCreate' => TRUE,
            ),
        );
    }

    public function getCateName(){
        return empty($this->category)?"":$this->category->name;
    }

    public function getAppContent(){
        return empty($this->app_content)?"":base64_encode($this->app_content);
    }

}
