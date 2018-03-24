<?php

/**
 * This is the model class for table "review".
 *
 * The followings are the available columns in table 'review':
 * @property integer $id
 * @property string $model
 * @property integer $object_id
 * @property integer $customer_id
 * @property string $content
 * @property string $reply
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $audit
 * @property integer $is_deleted
 * @property string $score
 */
class GoodsReview extends Review
{
    /**
     * @var string 模型名
     */
    public $modelName = '商品评价';
    public $objectLabel = '商品名称';
    public $objectModel = 'goods';


    public function shopsearch()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('model', $this->objectModel, true); //设置条件为商家
        //$criteria->compare('model', $this->objectModel, true);//设置条件为商家
        if (!empty($this->customerName)) {
            $criteria->with[] = 'customer';
            $criteria->compare('customer.name', $this->customerName, true);
        }

        $criteria->join .= " left join `goods` on `goods`.`id`=`t`.`object_id`";
        $criteria->compare('goods.shop_id', Yii::app()->user->id);
        $criteria->compare('goods.name', $this->goodsName, true);

        $criteria->compare('`t`.content', $this->content, true);
        $criteria->compare('`t`.reply', $this->reply, true);
        $criteria->compare('`t`.create_time', $this->create_time);
        $criteria->compare('`t`.create_ip', $this->create_ip, true);
        $criteria->compare('`t`.update_time', $this->update_time);
        $criteria->compare('`t`.update_ip', $this->update_ip, true);
        $criteria->compare('`t`.audit', $this->audit, true);
        $criteria->compare('`t`.score', $this->score, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
        $criteria->with[] = 'goods_s';
        $criteria->compare('goods_s.object_id', $this->object_id);
//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
//        $branchIds = $employee->mergeBranch;
//        ICE 组织架构用表里面的
        $branch = EmployeeBranchRelation::model()->findAllByAttributes(array('employee_id'=>YII::app()->user->id));
        $branchIds = Employee::ICEGetOldMergeBranch();

        /*if(!empty($employee->branch_id)){
            $shop_ids = Branch::model()->findByPk($employee->branch_id)->getBranchAllIds('Shop');
            $criteria->addInCondition('`goods_s`.shop_id', $shop_ids);
        }*/
//        if (!empty($employee->branch)) {
//       ICE 组织架构用表里面的  
        if (!empty($branch)) {
            $shop_ids = array();
            foreach ($branchIds as $branchId) {
                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Shop');
                $shop_ids = array_unique(array_merge($shop_ids, $data));
            }
            $criteria->addInCondition('`goods_s`.shop_id', $shop_ids);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('model', $this->objectModel, true); //设置条件为商家
        if ($this->_objectName != '') {
            $criteria->with[] = $this->objectModel;
            $criteria->compare($this->objectModel . '.name', $this->objectName, true);
        }
        $criteria->with[] = 'customer';
        $criteria->compare("`customer`.username", $this->username, true);
        $criteria->compare("`customer`.mobile", $this->mobile, true);

        if ($this->customerName != '') {
            $criteria->compare('customer.name', $this->customerName, true);
        }
        if ($this->start_time != "") {
            $criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }
        $criteria->compare('`t`.content', $this->content, true);
        $criteria->compare('`t`.reply', $this->reply, true);
        $criteria->compare('`t`.create_time', $this->create_time);
        $criteria->compare('`t`.create_ip', $this->create_ip, true);
        $criteria->compare('`t`.update_time', $this->update_time);
        $criteria->compare('`t`.update_ip', $this->update_ip, true);
        $criteria->compare('`t`.audit', $this->audit, true);
        $criteria->compare('`t`.score', $this->score, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getAllGoodsBranchIds()
    {

    }

}
