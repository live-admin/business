<?php

/**
 * This is the model class for table "event_community_relation".
 *
 * The followings are the available columns in table 'event_community_relation':
 * @property integer $event_id
 * @property integer $community_id
 * @property integer $update_time
 */
class EventCommunityRelation extends CActiveRecord
{
    public function primaryKey()
    {
        // 对于复合主键，要返回一个类似如下的数组
        return array('event_id', 'community_id');
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'event_community_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('event_id, community_id, update_time', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('event_id, community_id, update_time', 'safe', 'on' => 'search'),
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
            'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'event_id' => '专题活动',
            'community_id' => '小区',
            'update_time' => '更新时间',
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

        $criteria = new CDbCriteria;

        $criteria->compare('event_id', $this->event_id);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('update_time', $this->update_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return EventCommunityRelation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function createEventCommunityRelation($event_id, $communityList)
    {
        if (empty($event_id)) {
            return false;
        }
        if (empty($communityList)) { //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;
        } else {
            $sql = "INSERT INTO event_community_relation (event_id,community_id,update_time) VALUES ";
            $sqlArr = array();
            $i = 0;
            $max = count($communityList);
            $update_time = time();
            $return = true;
            foreach ($communityList as $key => $value) {
                $i++;
                //拼接sql语句
                array_push($sqlArr, "({$event_id},{$value},{$update_time})");
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

    public function  updateEventCommunityRelation($event_id, $communityList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('event_id' => $event_id));
        return $this->saveAll($event_id, $communityList);

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

    public function saveAll($eventId, $communityIds = array())
    {
        $myCommunityIds = array();
        if (empty($communityIds)) //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;

        //获得该组织架构下的所有小区
        $event = Event::model()->findByPk($eventId);
        if (!empty($event->branch)) {
            $myCommunityIds = $event->branch->getBranchAllIds("Community");
        }

        foreach ($communityIds as $key => $val) {
            //如果要插入的小区ID不属于该组织架构直接跳过
            if (!in_array($val, $myCommunityIds)) {
                continue;
            }
            //保存关连关系
            $model = new self;
            $model->community_id = intval($val);
            $model->event_id = intval($eventId);

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
