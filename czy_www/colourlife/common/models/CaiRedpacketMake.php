<?php

/**
 * This is the model class for table "cai_redpacket_make".
 *
 * The followings are the available columns in table 'cai_redpacket_make':
 * @property integer $id
 * @property integer $employee_id
 * @property string $red_packet
 * @property integer $step3_id
 * @property integer $step3_relation_id
 * @property integer $create_time
 * @property integer $state
 * @property integer $is_deleted
 */
class CaiRedpacketMake extends CActiveRecord
{	

	public $allRedpacket;
	public $modelName = '彩管家红包发放记录';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cai_redpacket_make';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('employee_id, step3_id, step3_relation_id, create_time, state, is_deleted, create_userid, is_send', 'numerical', 'integerOnly'=>true),
            array('red_packet', 'length', 'max'=>10),
            array('create_username', 'length', 'max'=>100),
            array('note', 'safe'),
            array('red_packet', 'checkQuery','on'=>'redPacketMake'),            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, employee_id, red_packet, step3_id, step3_relation_id, create_time, state, is_deleted, create_username, create_userid, note, is_send', 'safe', 'on'=>'search'),
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
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'step3' => array(self::BELONGS_TO, 'CaiRedpacketStep3', 'step3_id'),
            'relation3' => array(self::BELONGS_TO, 'CairedpacketBranchRelationStep3', 'step3_relation_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'employee_id' => '员工ID',
			'red_packet' => '红包',
			'step3_id' => '活动ID',
			'step3_relation_id' => '红包关系ID',
			'create_time' => '创建时间',
			'state' => '状态',
			'is_deleted' => '删除',
			'create_username' => '创建者',
            'create_userid' => '创建人ID',
            'note' => '备注',
            'allRedpacket' => '可用红包',
            'is_send' => '发放状态',
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
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }



     public function checkQuery($attribute, $params)
    {	
        if (!$this->hasErrors() && !empty($this->red_packet) && !empty($this->allRedpacket)) {
        	if(!is_numeric($this->red_packet)||$this->red_packet<0){
        		$this->addError($attribute, '填写的红包金额必须是大于等于0的数字！');
        	}else{
        		if($this->red_packet>$this->allRedpacket){
    				$this->addError($attribute, '添加的红包金额超过了可用红包金额！');
    			}
        	}   		
        	
        }
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
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('red_packet',$this->red_packet,true);
		$criteria->compare('step3_id',$this->step3_id);
		$criteria->compare('step3_relation_id',$this->step3_relation_id);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('state',$this->state);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('create_username',$this->create_username,true);
        $criteria->compare('create_userid',$this->create_userid);
        $criteria->compare('note',$this->note,true);
        $criteria->compare('is_send',$this->is_send);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CaiRedpacketMake the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
