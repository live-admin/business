<?php

/**
 * This is the model class for table "community_apply".
 *
 * The followings are the available columns in table 'community_apply':
 * @property string $id
 * @property integer $community_id
 * @property string $name
 * @property string $icon
 * @property string $url
 * @property integer $position
 * @property integer $status
 */
class CommunityApply extends CActiveRecord
{
    public $modelName='园区应用';
    public $iconFile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'community_apply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('community_id, url', 'required'),
			array('community_id, state, is_sign', 'numerical', 'integerOnly'=>true),
			array('act,img,title,type,proto_android,proto_ios,mobileType', 'length', 'max'=>200),
			array('url', 'length', 'max'=>512),
            array('logoFile,logo', 'safe', 'on' => 'create,update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, community_id, name, icon, url, position, state,proto_android,proto_ios, is_sign, secret', 'safe', 'on'=>'search'),
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
            'community'=>array(self::BELONGS_TO,"Community","community_id")
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'community_id' => '园区',
			'name' => '名字',
			'logo' => '图标',
            'logoFile'=>'图标',
            'proto_android'=>'安卓',
            'proto_ios'=>'苹果',
			'url' => 'Url',
			'position' => '排序',
			'state' => '状态',
            'mobileType'=>'类型',
            'is_sign'=>'是否需要签名',
            'secret'=>'签名key'
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
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommunityApply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function beforeValidate()
    {
        if (empty($this->icon) && !empty($this->iconFile))
            $this->icon = '';

        return parent::beforeValidate();
    }


    public function getImgUrl()
    {
        return Yii::app()->imgFile->getUrl($this->img);
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
         /*   'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),*/
            /*'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),*/
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

    public function getCommunityName(){
        if(isset($this->community)){
            return $this->community->name;
        }
        return "";
    }

    /*
     * 图片处理
     */

    public  function getImg() {
        if(isset($this->logo)){
            if (strstr($this->logo, 'v23')) {
                $url = F::getStaticsUrl('/common/' . $this->logo);
            } else{
                $url = F::getUploadsPath( $this->logo);
            }
            return $url;
        }
    }

    public function getProto(){
        if($this->mobileType==1){
            return $this->proto_ios;
        }else if($this->mobileType==2){
            return $this->proto_android;
        }
    }

}
