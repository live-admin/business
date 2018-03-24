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
class ShopReview extends Review
{
    /**
     * @var string 模型名
     */
    public $modelName = '商家评价';
    public $objectLabel = '商家名称';
    public $objectModel = 'shop';

    public function shopsearch()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('`t`.object_id', Yii::app()->user->id);

        $criteria->compare('`t`.model', 'shop', true); //设置条件为商家
        if (!empty($this->customerName)) {
            $criteria->with[] = 'customer';
            $criteria->compare('customer.name', $this->customerName, true);
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

    public function getShopType($type)
    {
        switch ($type) {
            case 0:
                return "local";
                break;
            case 1:
                return "online";
                break;
            case 2:
                return "seller";
                break;
            case 3:
                return "supplier";
                break;
        }
    }


    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);

//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
//        $branchIds = $employee->mergeBranch;

//          ICE 根据逻辑 这个组织架构用原来的，因为要搜索本地的branch_id。
//         解决了 employee表没有就报错的问题。
        $branchIds = Employee::ICEGetOldMergeBranch();

        $shop_ids = array();
        foreach ($branchIds as $branchId) {
            $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Shop');
            $shop_ids = array_unique(array_merge($shop_ids, $data));
        }
        // $shop_ids = Branch::model()->findByPk($employee->branch_id)->getBranchAllIds('Shop');
        $criteria->addInCondition("`t`.object_id", $shop_ids);
        $criteria->compare('model', $this->objectModel, true); //设置条件为商家
        if ($this->_objectName != '') {
            $criteria->with[] = $this->objectModel;
            $criteria->compare($this->objectModel . '.name', $this->objectName, true);
        }
        $criteria->with[] = 'customer';
        $criteria->compare("`customer`.mobile", $this->mobile, true);
        $criteria->compare("`customer`.username", $this->username, true);
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

}
