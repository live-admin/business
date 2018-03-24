<?php

/**
 * This is the model class for table "third_party_account".
 *
 * The followings are the available columns in table 'third_party_account':
 * @property integer $id
 * @property string $name
 * @property string $english_name
 */
class ThirdPartyAccount extends CActiveRecord
{	


	public $modelName = '渠道商';


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'third_party_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('name', 'length', 'max'=>100),
			array('name,english_name', 'required', 'on' => 'create,update'),
			array('name, english_name', 'unique', 'on' => 'create,update'),
			//array('english_name', 'length', 'max'=>25),
			array('id, name, english_name, state, is_deleted, create_time', 'safe', 'on'=>'search'),
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
     * @return array
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'updateAttribute' => null,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
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
			'name' => '渠道商',
			'english_name' => '英文名称',
			'state' => '状态',
			'is_deleted' => '是否禁用',
			'create_time' => '创建时间',
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
        $criteria->compare('english_name',$this->english_name,true);
        $criteria->compare('state',$this->state);
        $criteria->compare('is_deleted',$this->is_deleted);
        $criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ThirdPartyAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function getAccountList($name=true,$extra=false){
        $AccountList = array();
        $criteria = new CDbCriteria;  
		$criteria->condition = 'state=:state and is_deleted=:is_deleted';  
		$criteria->params = array(':state'=>0, ':is_deleted'=>0);   
        $AccountModel = ThirdPartyAccount::model()->findAll($criteria);
        foreach($AccountModel as $_v){
        	if($name){
        		$AccountList[$_v->english_name] = $_v->name;
        	}else{
        		$AccountList[$_v->id] = $_v->name;
        	}  
        }
        if(!$extra){
        	return $AccountList;
        }else{
        	return array_merge(array('All'=>'全部'), $AccountList);
        }
        
    }






    public function getThirdPartyAccountNames($english_name=""){
        if($english_name==""){
            return "N/A";
        }else{
        	$arr = array();
			$criteria = new CDbCriteria;  
			$criteria->condition = 'english_name=:english_name and state=:state and is_deleted=:is_deleted';  
			$criteria->params = array(':english_name'=>$english_name,':state'=>0, ':is_deleted'=>0);   
			$model = ThirdPartyAccount::model()->find($criteria);

			if(!empty($model)){
                return $model->name;
            }else{
                return "N/A";
            }
            
        }
    }
   
}
