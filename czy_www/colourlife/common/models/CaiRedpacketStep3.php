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
class CaiRedpacketStep3 extends CActiveRecord
{   
    public $modelName = '彩管家红包创建第三步';
    public $relation_branchId;
    public $step2_relation;
    public $branch_ids = array();
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cai_redpacket_step3';
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
            array('step2_id, is_locked, create_userid, update_userid, state, is_deleted', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>255),
            array('red_packet, get_redpacket, frozen_sum', 'length', 'max'=>10),
            array('create_username, update_username', 'length', 'max'=>100),
            array('name', 'required', 'on' => 'create, update'),
            array('step2_id', 'checkRepeat', 'on' => 'createStep2'),
            array('red_packet, step2_relation', 'checkQuery', 'on' => 'createStep2'),
            // array('branch_ids', 'checkBranch', 'on' => 'createStep2'),           
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, step2_id, name, red_packet, get_redpacket, frozen_sum, is_locked, create_username, create_userid, create_date, update_username, update_userid, update_date, state, is_deleted', 'safe', 'on'=>'search'),
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
            'branch' => array(self::HAS_MANY, 'CairedpacketBranchRelationStep3', 'cai_redpacket_id'),
            'step2' => array(self::BELONGS_TO, 'CaiRedpacketStep2', 'step2_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'step2_id' => '第一步ID',
            'name' => '活动名称',
            'red_packet' => '红包金额(第三步)',
            'is_locked' => '锁定',
            'create_username' => '创建人',
            'create_userid' => '创建人ID',
            'create_date' => '创建时间',
            'update_username' => '修改人',
            'update_userid' => '修改人ID',
            'update_date' => '修改时间',
            'state' => '状态(第三步)',
            'is_deleted' => '删除',
            'branch_ids' => '区域',
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



    public function checkRepeat($attribute, $params)
    {   
        if (!$this->hasErrors() && !empty($this->step2_id) && !empty($this->relation_branchId)) {
            $model = CairedpacketBranchRelationStep3::model()->findAll('step2_id='.$this->step2_id);
            if($model){
                $list = array();
                $str="";
                foreach ($model as $k => $v) {
                    if(in_array($v->branch_id,$this->relation_branchId)){
                        $str .= "【".$v->branch->name."】";
                    }
                }
                if($str!=''){
                    $this->addError($attribute, '增加的区域'.$str.'已经存在，不能重复增加');
                }
            }
        }
    }



     public function checkQuery($attribute, $params)
    {   //var_dump($this->step2_relation);die;
        if (!$this->hasErrors() && !empty($this->red_packet) && !empty($this->step2_relation)) {
            $model = CairedpacketBranchRelationStep2::model()->findByPk($this->step2_relation);
            if($model){
                if(!is_numeric($this->red_packet)||$this->red_packet<0){
                    $this->addError($attribute, '填写的红包金额必须是大于等于0的数字！');
                }else{
                    if($this->red_packet>$model->get_redpacket){
                        $this->addError($attribute, '添加的红包金额超过了可用红包金额！');
                    }
                }
            }else{
                $this->addError($attribute, '未知错误2222');
            }
            
            
        }
    }


    public function checkBranch($attribute, $params)
    {   
        if (!$this->hasErrors() && empty($this->branch_ids)) {
                $this->addError($attribute, '请选择红包发放区域！');
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
        $criteria->compare('step2_id',$this->step2_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('red_packet',$this->red_packet,true);
        $criteria->compare('get_redpacket',$this->get_redpacket,true);
        $criteria->compare('frozen_sum',$this->frozen_sum,true);
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
        //$criteria->with[] = 'branch';
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
        // var_dump($arrayList);die;

        $criteria->join = 'inner join cairedpacket_branch_relation_step2 ebr on ebr.cai_redpacket_id=t.id';
        // $branch = Branch::model()->findByPk($this->branch_id);
        // if (!empty($branch)) {
            //$criteria->addInCondition('ebr.branch_id', $branch->getChildrenIdsAndSelf());
        // }


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



    // public function getBranchTreeData()
 //    {
 //        $list = $data = $branch_Ids = array();
 //        $employee = Employee::model()->findByPk(Yii::app()->user->id);
 //        //var_dump($employee);die;
 //        $data = $employee->getAllBranchIds();

 //        if (!empty($this->branch)) {
 //            $branch_Ids = array_map(function ($record) {
 //                return $record->branch_id;
 //            }, $this->branch);
 //        }
 //        foreach ($data as $treeData) {
 //            $branch = Branch::model()->findByPk($treeData);
 //            $data = array('id' => "$branch->id", 'pId' => "$branch->parent_id", 'name' => "$branch->name", 'open' => true);
 //            if (in_array($branch->id, $branch_Ids))
 //                $data['checked'] = 'true';

 //            $list[] = $data;
 //        }

 //        return $list;
 //    }



    


    public function getBranchTreeData($parent_id)
    {
        $list = $data = $branch_Ids = array();
        //$employee = Employee::model()->findByPk(Yii::app()->user->id);
        //var_dump($employee);die;
        $data = $this->getRedPacket_Step2($parent_id);

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



    //红包第二步，只列出组织架构第一二层
    public function getRedPacket_Step2($parent_id)
    {
        $data = array();
        // $data[] = 1;
        $criteria = new CDbCriteria;
        $criteria->compare('`t`.parent_id',$parent_id);
        $list = Branch::model()->findAll($criteria);

        if (!empty($list) && is_array($list)) {
            foreach ($list as $key => $val) {
                $data[] = $val->id;
            }
           
        }
        return $data;
    }



    public function getBranchTreeDataView()
    {
        $list = $data = $branch_Ids = array();
        //$employee = Employee::model()->findByPk(Yii::app()->user->id);
        //var_dump($employee);die;
        $data = $this->getRedPacket_Step2_View();

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
        // $data[] = 1;
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


}
