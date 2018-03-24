<?php

/**
 * This is the model class for table "entrance_orgs".
 *
 * The followings are the available columns in table 'entrance_orgs':
 * @property string $id
 * @property string $uuid
 * @property string $name
 * @property string $org_type
 * @property string $parent_id
 * @property integer $is_check
 * @property string $create_time
 */
class EntranceOrgs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'entrance_orgs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time', 'required'),
			array('is_check', 'numerical', 'integerOnly'=>true),
			array('uuid, name, org_type, parent_id', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uuid, name, org_type, parent_id, is_check, create_time', 'safe', 'on'=>'search'),
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
			'uuid' => 'Uuid',
			'name' => 'Name',
			'org_type' => 'Org Type',
			'parent_id' => 'Parent',
			'is_check' => 'Is check',
			'create_time' => 'Create Time',
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
		$criteria->compare('uuid',$this->uuid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('org_type',$this->org_type,true);
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('is_check',$this->is_check);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EntranceOrgs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function tableParams($uuid, $params){

        if(empty($uuid) || empty($params) || !is_array($params)){
            return false;
        }else{
            $model = self::model()->find('uuid=:uuid', array('uuid' => $uuid));
            if($model){
                $model->setAttributes($params);
                if($model->update())
                    return true;
                else
                    return false;
            }else{
                $model = new self();
                $model->isNewRecord=true;
                $model->setAttributes($params);
                if($model->save()){
                    $model->id=0;
                    return true;
                }
                else
                    return false;
            }
        }

    }
}
