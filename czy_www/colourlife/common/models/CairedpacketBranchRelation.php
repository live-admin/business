<?php

/**
 * This is the model class for table "cairedpacket_branch_relation".
 *
 * The followings are the available columns in table 'cairedpacket_branch_relation':
 * @property integer $id
 * @property integer $cai_redpacket_id
 * @property integer $branch_id
 */
class CairedpacketBranchRelation extends CActiveRecord
{	

	public $activityName;
	public $updateRedpacket;
	public $name='红包关系表';
	public $modelName = '彩管家红包第一步';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cairedpacket_branch_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cai_redpacket_id, branch_id, is_locked', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('branch_id', 'checkBranchIdExist', 'on' => 'create'),
            array('branch_id','required','on' => 'update'),
            array('amount, get_redpacket, frozen_sum, old_redpacket', 'length', 'max'=>10),
            array('get_redpacket', 'checkQuery', 'on'=>'createRedPacketForArea'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, cai_redpacket_id, branch_id, amount, get_redpacket, frozen_sum, old_redpacket, activityName, is_locked', 'safe', 'on'=>'search'),
            array('id, cai_redpacket_id, branch_id, amount, get_redpacket, frozen_sum, old_redpacket, activityName, is_locked', 'safe', 'on'=>'search_forBranch'),
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
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'cai_redpacket' => array(self::BELONGS_TO, 'CaiRedpacketStep1', 'cai_redpacket_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cai_redpacket_id' => '红包ID',
			'branch_id' => '部门ID',
			'get_redpacket' => '可用金额',
			'activityName' => '活动名称',
			'frozen_sum' => '冻结金额',
			'amount' => '总红包金额',
			'updateRedpacket' => '可用红包',
			'old_redpacket' => '上次红包金额',
			'is_locked' => '锁定',
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
        $criteria->compare('cai_redpacket_id',$this->cai_redpacket_id);
        $criteria->compare('branch_id',$this->branch_id);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('get_redpacket',$this->get_redpacket,true);
        $criteria->compare('frozen_sum',$this->frozen_sum,true);
        $criteria->compare('old_redpacket',$this->old_redpacket,true);
        $criteria->compare('is_locked',$this->is_locked);
		if($this->activityName!=''){
			$criteria->with[] = 'cai_redpacket';
			$criteria->compare('cai_redpacket.name',$this->activityName);
		}

		// $model = EmployeeBranchRelation::model()->findAll('employee_id='.Yii::app()->user->id);
  //       foreach ($model as $k => $v) {
  //           $arrayList[] = $v->branch_id;
  //       }
  //       $criteria->compare('branch_id', $arrayList);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	//按照自己的架构看到相应的红包活动
	public function search_forBranch()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
        $criteria->compare('cai_redpacket_id',$this->cai_redpacket_id);
        //$criteria->compare('branch_id',$this->branch_id);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('get_redpacket',$this->get_redpacket,true);
        $criteria->compare('frozen_sum',$this->frozen_sum,true);
        $criteria->compare('old_redpacket',$this->old_redpacket,true);
        $criteria->compare('is_locked',$this->is_locked);
		if($this->activityName!=''){
			$criteria->with[] = 'cai_redpacket';
			$criteria->compare('cai_redpacket.name',$this->activityName);
		}
		$model = EmployeeBranchRelation::model()->findAll('employee_id='.Yii::app()->user->id);
        foreach ($model as $k => $v) {
            $arrayList[] = $v->branch_id;
        }
        $criteria->compare('branch_id', $arrayList);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}





	public function search_step2()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('cai_redpacket_id',$this->cai_redpacket_id);
		//$criteria->compare('branch_id',$this->branch_id);

		$arrayList = array();
		$model = EmployeeBranchRelation::model()->findAll('employee_id='.Yii::app()->user->id);
        foreach ($model as $k => $v) {
            $arrayList[] = $v->branch_id;
        }
        $criteria->compare('branch_id', $arrayList);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	

	 public function checkQuery($attribute, $params)
    {	
        if (!$this->hasErrors() && !empty($this->get_redpacket) && !empty($this->updateRedpacket)) {
        	if(!is_numeric($this->get_redpacket)||$this->get_redpacket<0){
        		$this->addError($attribute, '填写的红包金额必须是大于等于0的数字！');
        	}else{
        		if($this->get_redpacket>$this->updateRedpacket){
    				$this->addError($attribute, '添加的红包金额超过了可用红包金额！');
    			}
        	}   		
        	
        }
    }


	public function saveAll($cai_redpacket_id, $branchIds = array())
    {
        if (empty($branchIds)) //如果传入为空
            return false;
        foreach ($branchIds as $key => $val) {
            //保存关连关系
            $model = new self;
            $model->branch_id = intval($val);
            $model->cai_redpacket_id = intval($cai_redpacket_id);
            // 已经存在直接跳过
            if (!$this->getCanCreate())
                continue;

            if (!$model->save())
                return false;
        }
        return true;
    }




   private function getCanCreate()
    {
        $criteria = new CDbCriteria;
        $criteria->compare("cai_redpacket_id", $this->cai_redpacket_id);
        $criteria->compare("branch_id", $this->branch_id);
        $branch = CairedpacketBranchRelation::model()->find($criteria);
        if ($branch) {
            return false;
        } else {
            return true;
        }
    }




    private function getActivityStatus($id)
    {
        $model = CaiRedpacketStep1::model()->findByPk($id);
        if ($model&&$model->state==0&&$model->is_deleted==0) {
            return true;
        } else {
            return false;
        }
    }





    public function checkBranchIdExist($attribute, $params)
    {

        if (!$this->hasErrors() && !empty($this->cai_redpacket_id) && !empty($this->branch_id) && !$this->getCanCreate()) {
            $this->addError($attribute, "不可重复部门");
        }
    }

    



	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CairedpacketBranchRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	public function  updateEmployeeBranchRelation($cai_redpacket_id, $branchList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('cai_redpacket_id' => $cai_redpacket_id));
        return $this->saveAll($cai_redpacket_id, $branchList);

    }




    /**
	 * 根据数组，添加布局角度分配layout.
	 * @param 活动id  $actId
	 * @param 布局数组
	 */
	public function saveLayoutCircleArray($actId,$arr){
		if(!$this->getActivityStatus($actId)){exit;}
		if(is_array($arr)){
			$amount = 0;
			foreach ($arr as $key=>$value){
				$prize=CairedpacketBranchRelation::model()->findByPk($value['id']);
				if($prize){
					$amount += $value['get_redpacket'];
					// $prize->get_redpacket=$value['get_redpacket'];
					// $prize->update();
					// var_dump($amount);
				}
			}
			$model = CaiRedpacketStep1::model()->findByPk($actId);
			if($model&&$model->red_packet>=$amount){
				foreach ($arr as $key=>$value){
					$prize=CairedpacketBranchRelation::model()->findByPk($value['id']);
					if($prize){
						//$amount += $value['get_redpacket'];
						$prize->get_redpacket=$value['get_redpacket'];
						$prize->update();
						var_dump($amount);
					}
				}
			}else{
				exit('发放的总金额超过创建金额！');
			}
		}
	}
	


}
