<?php

/**
 * This is the model class for table "topic_group".
 *
 * The followings are the available columns in table 'topic_group':
 * @property string $id
 * @property string $title
 * @property string $logo
 * @property string $desc
 * @property integer $user_id
 * @property integer $sort
 * @property string $num
 * @property string $create_time
 * @property string $update_time
 * @property integer $is_show
 * @property integer $state
 */
class TopicGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'topic_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, logo, user_id', 'required'),
			array('user_id, sort, is_show, state', 'numerical', 'integerOnly'=>true),
			array('title, num, create_time, update_time', 'length', 'max'=>100),
			array('logo', 'length', 'max'=>300),
			array('desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, logo, desc, user_id, sort, num, create_time, update_time, is_show, state', 'safe', 'on'=>'search'),
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
			'title' => '标题',
			'logo' => 'LOGO的URL',
			'desc' => '描述',
			'user_id' => '创建人',
			'sort' => '排序',
			'num' => '加入人数',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'is_show' => '是否前台显示，默认1,0-不显示，1=显示',
			'state' => '是否可以删除，默认1,1=可删除，0=不可删除',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('num',$this->num,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TopicGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    //判断是否有关注此圈子
    public static function isFocus($id){
        $model=TopicGroupCustomerRelation::model()->find("customer_id=:customer_id AND community_id=:community_id AND group_id=:group_id",array(":customer_id"=>Yii::app()->user->id,":community_id"=>Yii::app()->user->cid,":group_id"=>$id));
        if(!empty($model)){
            return true;
        }
        return false;
    }

    public function getShortTitle($n){
        return mb_strlen($this->title,'utf-8')>$n?F::msubstr($this->title,0,$n).'...':$this->title;
    }

    public function getShortDesc(){
        return mb_strlen($this->desc,'utf-8')>30?F::msubstr($this->desc,0,30).'...':$this->desc;
    }
    public function getLogoPic()
    {
        return Yii::app()->imageFile->getUrl($this->logo);
    }
}
