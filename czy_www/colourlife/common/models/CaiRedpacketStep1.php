<?php

/**
 * This is the model class for table "cai_redpacket_step1".
 *
 * The followings are the available columns in table 'cai_redpacket_step1':
 * @property integer $id
 * @property string $name
 * @property string $red_packet
 * @property integer $is_locked
 * @property string $create_username
 * @property integer $create_userid
 * @property string $create_date
 * @property string $update_username
 * @property integer $update_userid
 * @property string $update_date
 * @property integer $state
 * @property integer $is_deleted
 */
class CaiRedpacketStep1 extends CActiveRecord
{	
	public $modelActName = '彩管家红包活动';
	public $modelName = '彩管家红包第一步';
	public $branch_ids = array();
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cai_redpacket_step1';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_username, create_userid, create_date, update_username, update_userid, update_date', 'required'),
			array('is_locked, create_userid, update_userid, state, is_deleted', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('red_packet, get_redpacket, frozen_sum', 'length', 'max'=>10),
			array('create_username, update_username', 'length', 'max'=>100),
			array('name', 'required', 'on' => 'create, update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, red_packet, is_locked, create_username, create_userid, create_date, update_username, update_userid, update_date, state, is_deleted, get_redpacket, frozen_sum', 'safe', 'on'=>'search'),
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
			'branch' => array(self::HAS_MANY, 'CairedpacketBranchRelation', 'cai_redpacket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '活动名称',
			'red_packet' => '红包金额',
			'is_locked' => '锁定',
			'create_username' => '创建人',
			'create_userid' => '创建人ID',
			'create_date' => '创建时间',
			'update_username' => '修改人',
			'update_userid' => '修改人ID',
			'update_date' => '修改时间',
			'state' => '状态',
			'is_deleted' => '删除',
			'branch_ids' => '部门',
			'get_redpacket' => '可用金额',
			'frozen_sum' => '冻结金额',
		);
	}



	/**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
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
		$criteria->compare('red_packet',$this->red_packet,true);
		$criteria->compare('is_locked',$this->is_locked);
		$criteria->compare('create_username',$this->create_username,true);
		$criteria->compare('create_userid',$this->create_userid);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('update_username',$this->update_username,true);
		$criteria->compare('update_userid',$this->update_userid);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}




	public function search_step2()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.t.red_packet',$this->red_packet,true);
		$criteria->compare('t.is_locked',$this->is_locked);
		$criteria->compare('t.create_username',$this->create_username,true);
		$criteria->compare('t.create_userid',$this->create_userid);
		$criteria->compare('t.create_date',$this->create_date,true);
		$criteria->compare('t.update_username',$this->update_username,true);
		$criteria->compare('t.update_userid',$this->update_userid);
		$criteria->compare('t.update_date',$this->update_date,true);
		$criteria->compare('t.state',$this->state);
		$criteria->compare('t.is_deleted',$this->is_deleted);
		$arrayList = array();
		$model = EmployeeBranchRelation::model()->findAll('employee_id='.Yii::app()->user->id);
        foreach ($model as $k => $v) {
            $arrayList[] = $v->branch_id;
        }
        $criteria->distinct = true;
        $criteria->join = 'inner join cairedpacket_branch_relation ebr on ebr.cai_redpacket_id=t.id';
		$criteria->compare('ebr.branch_id', $arrayList);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CaiRedpacketStep1 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}   


	public function getBranchTreeData()
    {
        $list = $data = $branch_Ids = array();
        $data = $this->getRedPacket_Step1();

        if (!empty($list) && is_array($list)) {
            foreach ($list as $key => $val) {
                $data[] = $val->id;
            }
           
        }

        if (!empty($this->branch)) {
            $branch_Ids = array_map(function ($record) {
                return $record->branch_id;
            }, $this->branch);
        }
        foreach ($data as $treeData) {
            $branch = Branch::model()->findByPk($treeData);
            $data = array('id' => "$branch->id", 'pId' => "$branch->parent_id", 'name' => "$branch->name", 'open' => true);
            if (in_array($branch->id, $branch_Ids))
                $data['checked'] = 'true';

            $list[] = $data;
        }

        return $list;
    }



    //红包第一步，只列出组织架构第一二层
    public function getRedPacket_Step1()
    {
        $data = array();
        $criteria = new CDbCriteria;
        $criteria->compare('`t`.parent_id',1);
        $list = Branch::model()->findAll($criteria);

        if (!empty($list) && is_array($list)) {
            foreach ($list as $key => $val) {
                $data[] = $val->id;
            }
           
        }
        return $data;
    }


    public function getFullString($str, $len)
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
	}

	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->is_deleted=1;
		$model->save();
		CairedpacketBranchRelation::model()->deleteAll("cai_redpacket_id=:cai_redpacket_id",array(':cai_redpacket_id'=>$this->getPrimaryKey())); 
	}

}
