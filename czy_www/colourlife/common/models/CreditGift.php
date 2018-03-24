<?php

/**
 * This is the model class for table "credit_gift".
 *
 * The followings are the available columns in table 'credit_gift':
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $cost_price
 * @property integer $exchange_credit
 * @property integer $num
 * @property integer $display_order
 * @property integer $state
 * @property string $desc
 * @property string $app_desc
 * @property integer $create_time
 */
class CreditGift extends CActiveRecord
{
    public $start_time;
    public $end_time;
    public $modelName = '积分礼品';
    public  $url;
    public  $images = array();
    public  $urlFile;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'credit_gift';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, category_id', 'required'),
			array('category_id, exchange_credit, num, display_order, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('cost_price', 'length', 'max'=>10),
			array('desc, app_desc, url, images, urlFile', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, category_id, cost_price, exchange_credit, num, display_order, state, desc, create_time, start_time, end_time', 'safe', 'on'=>'search'),
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

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '礼品名称',
			'category_id' => '礼品分类',
			'cost_price' => '成本价格',
			'exchange_credit' => '兑换积分',
			'num' => '可兑换数量',
			'display_order' => '排序',
			'state' => '状态',
			'desc' => '详细介绍',
            'app_desc' => 'APP详细介绍',
			'create_time' => '创建时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('exchange_credit',$this->exchange_credit);
		$criteria->compare('num',$this->num);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('state',$this->state);
		$criteria->compare('desc',$this->desc,true);
        $criteria->compare('app_desc',$this->app_desc,true);
		$criteria->compare('create_time',$this->create_time);
        if ($this->start_time != "") {
            $criteria->addCondition('create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditGift the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function beforeValidate() {
        if(empty($this->url) && !empty($this->urlFile))
            $this->url = '';
        return parent::beforeValidate();
    }

    public function saveCreditGift(){
        if($this->save()){
            if (!empty($this->images)) {
                //保存上传的图片路径
                $crieria = new CDbCriteria();
                $crieria->addInCondition('id', $this->images);
                $attr = array(
                    'model' => 'creditGift',
                    'object_id' => $this->id,
                );
                return Picture::model()->updateAll($attr, $crieria);
            }else{
                return true;
            }
        }
        return false;
    }

    public static function getAllCreditGift()
    {
        $cdb = new CDbCriteria();
        $cdb->addCondition('num > 0')->addCondition('state = 0')->order = 'display_order DESC';
        return static::model()->findAll($cdb);
    }

    //根据不同的类型得到图片
    public function getTypePics($modelName)
    {
        $pic = array();
        if($modelName=="")
            return $pic;
        $CDbCriteria = new CDbCriteria();
        $CDbCriteria->compare("model", $modelName);
        $CDbCriteria->compare("object_id", $this->id);
        $picture = Picture::model()->findAll($CDbCriteria);
        if (empty($picture)) {
            return $pic;
        } else {

            foreach ($picture as $val) {
                $pic[] = Yii::app()->imageFile->getUrl($val->url);
            }
            return $pic;
        }
    }


    //获取当前图片
    public function getPics()
    {
        $pic = array();
        $CDbCriteria = new CDbCriteria();
        $CDbCriteria->compare("model", get_class($this));
        $CDbCriteria->compare("object_id", $this->id);
        $picture = Picture::model()->findAll($CDbCriteria);
        if (empty($picture)) {
            return $pic;
        } else {

            foreach ($picture as $val) {
                $pic[] = Yii::app()->imageFile->getUrl($val->url);
            }
            return $pic;
        }
    }

    public function getCategoryName($id = null, $html = true)
    {
        $model = $name = '';
        if(empty($id)){
            $id = $this->category_id;
        }
        if($model = CreditGiftCategory::model()->findByPk($id)){
            $name = $model->name;
        }
        if($html && $model){
            $name = CHtml::link($name, '/creditGiftCategory/'.$model->id, array('target' => '_blank'));
        }
        return $name;
    }

    //获取我现有的积分
    public function getMyCredit(){
        $model=Customer::model()->findByPk(Yii::app()->user->id);
        if(empty($model)){
            return 0;
        }
        return $model->credit;
    }

    //APP的详情介绍base64加密
    public function getAppDesc(){
        return base64_encode($this->app_desc);
    }


}
