<?php

/**
 * This is the model class for table "company".
 *
 * The followings are the available columns in table 'company':
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $business_number
 * @property string $legal_person
 * @property string $bank
 * @property string $bank_account
 * @property integer $state
 * @property string $create_time
 * @property integer $community_id
 * @property string $logo
 */
class Company extends CActiveRecord
{
	public $modelName='企业';
	public $logoFile;
	public $region;
    public $mobile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, username, password,build_id,build_id,room_id,room_id, community_id', 'required'),
			array('state, build_id,room_id,community_id,', 'numerical', 'integerOnly'=>true),
			array('name, username, business_number, legal_person, bank, bank_account, create_time, logo', 'length', 'max'=>200),
			array('password', 'length', 'max'=>256),
			array('logoFile,note', 'safe', 'on' => 'create,update'),
            array('username', 'unique', 'on' => 'create'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, username,note, build_id,room_id,password, business_number, legal_person, bank, bank_account, state, create_time, community_id, logo', 'safe', 'on'=>'search'),
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
            'companyRoom'=>array(self::BELONGS_TO,"CompanyRoom","room_id"),
            'community'=>array(self::BELONGS_TO,"Community","community_id"),
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
			'name' => '企业名称',
			'username' => '用户名',
			'password' => '密码',
			'business_number' => '营业号',
			'legal_person' => '法人代表',
			'bank' => '银行',
            'build_id'=>'楼栋',
            'room_id'=>'房间号',
			'bank_account' => '银行账号',
			'state' => '状态',
			'create_time' => '创建时间',
			'community_id' => '园区',
			'logo' => '企业图片',
			'logoFile' => '企业图片',
			'region' => '地区',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('business_number',$this->business_number,true);
		$criteria->compare('legal_person',$this->legal_person,true);
		$criteria->compare('bank',$this->bank,true);
		$criteria->compare('bank_account',$this->bank_account,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('logo',$this->logo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Company the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeValidate()
	{
		if (empty($this->logo) && !empty($this->logoFile))
			$this->logo = '';

		return parent::beforeValidate();
	}

	public function getLogoUrl()
	{
		return Yii::app()->imageFile->getUrl($this->logo);
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{

		if (!empty($this->logoFile)) {
			$this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
		}
		return parent::beforeSave();
	}


	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
			),
			/*'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),*/
			'StateBehavior' => array(
				'class' => 'common.components.behaviors.StateBehavior',
			),
			'UserBehavior' => array(
				'class' => 'common.components.behaviors.UserBehavior',
			),
		);
	}

    public function getRoom(){
        if(isset($this->companyRoom)){
            return $this->companyRoom->room;
        }
        return 0;
    }

    public function getCommunity(){
        if(isset($this->community)){
            return $this->community->name;
        }
        return "";
    }

    public function getBuild(){
        if(isset($this->build)){
            return $this->build->name;
        }
        return 0;
    }
}
