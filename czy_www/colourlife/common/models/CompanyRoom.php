<?php

/**
 * This is the model class for table "company_room".
 *
 * The followings are the available columns in table 'company_room':
 * @property string $id
 * @property integer $build_id
 * @property string $room
 * @property integer $state
 */
class CompanyRoom extends CActiveRecord
{
    public $modelName='房间号';
    public $community_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company_room';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('build_id, room', 'required'),
			array('build_id, state', 'numerical', 'integerOnly'=>true),
			array('room', 'length', 'max'=>200),
            array('room','checkRoom','on'=>'create,update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, build_id, room, state', 'safe', 'on'=>'search'),
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
            'build'=>array(self::BELONGS_TO,"Build","build_id")
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'build_id' => '楼栋',
			'room' => '房间号',
			'state' => '状态',
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
		$criteria->compare('build_id',$this->build_id);
		$criteria->compare('room',$this->room,true);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyRoom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //获取园区
    public function  getCommunityByType(){
        $data=array(""=>"请选择园区");
        $model=Community::model()->findAll("type=2 AND is_deleted=0 AND state=0");
        if(!empty($model)){
            foreach($model as $m){
                $id=$m->id;
                $data[$id]=$m->name;
            }
        }
        return $data;
    }

    //获取楼栋
    public function getBuild($community_id){
        $model=Build::model()->findAll("community_id=:community_id AND state=0 AND is_deleted=0",array(":community_id"=>$community_id));
        return $model;
    }

    public function behaviors()
    {
        return array(
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

    public function afterFind(){
        if(isset($this->build_id)){
            $build=Build::model()->findByPk($this->build_id);
            $this->community_id=$build->community_id;
        }
    }


    public function getBuildName(){
        if(isset($this->build)){
            return $this->build->name;
        }
        return "";
    }

    public function getCommunityName(){
        if(isset($this->build)){
            if($this->build->community){
                return $this->build->community->name;
            }
        }
        return "";
    }

    public function getStatteName(){
        if($this->state==0){
            return "启用";
        }else if($this->state==1){
            return "禁用";
        }
    }

    public function checkRoom($attribute,$params){
        if(!$this->hasErrors()&&!empty($this->build_id)&& !empty($this->room)) {
            $companyRoom=CompanyRoom::model()->find("build_id=:build_id AND room=:room",array(":build_id"=>$this->build_id,":room"=>trim($this->room)));
            if(!$this->hasErrors()&&!empty($companyRoom)){
                $this->addError($attribute,"该园区下的房间号已存在！");
            }
        }
    }
}
