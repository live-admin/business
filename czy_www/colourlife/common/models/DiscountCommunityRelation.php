<?php

/**
 * This is the model class for table "discount_community_relation".
 *
 * The followings are the available columns in table 'discount_community_relation':
 * @property integer $discount_id
 * @property integer $community_id
 * @property integer $update_time
 */
class DiscountCommunityRelation extends CActiveRecord
{

    public function primaryKey()
    {
        // 对于复合主键，要返回一个类似如下的数组
        return array('discount_id', 'community_id');
    }

    /**
     * @var string 模型名
     */
    public $modelName = '优惠卷小区关联';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DiscountCommunityRelation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'discount_community_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('discount_id, community_id, update_time', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array(' discount_id, community_id, update_time', 'safe', 'on' => 'search'),
            //检测优惠卷是否能关联到小区
            //array('community_id','checkCommunity','on'=>'create,update'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'discount' => array(self::BELONGS_TO, 'Discount', 'discount_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'discount_id' => '优惠卷',
            'community_id' => '小区',
            'update_time' => '更新时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('discount_id', $this->discount_id);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('update_time', $this->update_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 创建电子优惠卷和小区的关联
     * @discountAttr:电子优惠卷的相关信息集合,
     * @communityList:小区集合
     * @status:关联状态，默认为 0
     * 返回值:boolean;
     * */
    public function createDiscountCommunityRelation($discount_id, $communityList, $status = Item::DISCOUNT_COMMUNITY_NO)
    {
        if (empty($discount_id)) {
            return false;
        }
        if (empty($communityList)) { //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;
        } else {
            $sql = "INSERT INTO discount_community_relation (discount_id,community_id,update_time,update_employee_id,status) VALUES ";
            $sqlArr = array();
            $i = 0;
            $max = count($communityList);
            $employee_id = Yii::app()->user->id;
            $update_time = time();
            $return = true;
            foreach ($communityList as $key => $value) {
                $i++;
                //拼接sql语句
                array_push($sqlArr, "({$discount_id},{$value},{$update_time},{$employee_id},{$status})");
                //如果有了100条，或者已全部拼接完
                if ($i % 100 == 0 || $i >= $max) {
                    //执行插入语句
                    if (Yii::app()->getDb()->getPdoInstance()->query($sql . trim(implode(',', $sqlArr), ','))) {
                        //成功则情况数组,继续下一次插入
                        $sqlArr = array();
                    } else {
                        //任何一次失败都结束循环
                        $return = false;
                        break;
                    }
                }
            }
            return $return;
        }
    }

    public function  updateDiscountCommunityRelation($discount_id, $communityList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('discount_id' => $discount_id));
        return $this->saveAll($discount_id, $communityList);

    }

    //返回单条信息的状态
    public function getStatusName($html = false)
    {
        $return = '';
        $return .= $html ? '<span class="label label-success">' : "";
        switch ($this->status) {
            case Item::DISCOUNT_COMMUNITY_NO :
                $return .= "未服务";
                break;
            case Item::DISCOUNT_COMMUNITY_YES :
                $return .= "已服务";
                break;
        }
        $return .= $html ? "</span>" : "";
        return $return;
    }

    //返回所有状态集
    public function getStatusNames($select = false)
    {
        $return = array();
        if ($select) {
            $return[''] = '全部';
        }
        $return[Item::DISCOUNT_COMMUNITY_NO] = '未服务';
        $return[Item::DISCOUNT_COMMUNITY_YES] = '已服务';
        return $return;
    }

    public function saveAll($discountId, $communityIds = array())
    {
        $myCommunityIds = array();
        if (empty($communityIds)) //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;

        //获得该组织架构下的所有小区
        $discount = Discount::model()->findByPk($discountId);
        if (!empty($discount->branch)) {
            $myCommunityIds = $discount->branch->getBranchAllIds("Community");
        }

        foreach ($communityIds as $key => $val) {
            //如果要插入的小区ID不属于该组织架构直接跳过
            if (!in_array($val, $myCommunityIds)) {
                continue;
            }
            //保存关连关系
            $model = new self;
            $model->community_id = intval($val);
            $model->discount_id = intval($discountId);

            if (!$model->save())
                return false;
        }
        return true;
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => Null,
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }

}
