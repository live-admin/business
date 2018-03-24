<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property integer $id
 * @property integer $category_id
 * @property integer $branch_id
 * @property string $title
 * @property string $content
 * @property string $logo
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $state
 * @property integer $is_deleted
 * @property string $big_pic
 */
class Event extends CActiveRecord
{
    public $modelName = '专题活动';
    public $logoFile;
    public $bigPicFile; //大图
    public $startTime;
    public $endTime;
    public $community = array(); //小区
    public $region; //地区

    public $province_id;
    public $city_id;
    public $district_id;
    public $communityIds = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Notify the static model class
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
        return 'event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, category_id, branch_id, content, startTime, endTime,is_reserve', 'required', 'on' => 'create, update'),
            array('startTime, endTime', 'date', 'format' => 'yyyy-MM-dd', 'on' => 'create, update'),
            //array('content', 'filter', 'filter' => array($obj=new CHtmlPurifier(), 'purify'), 'on' => 'create, update'),
            array('end_time', 'checkEndTime', 'on' => 'create, update'),
            array('start_time,end_time', 'checkCheapTime', 'on' => 'create, update'),
            array('title', 'length', 'max' => 255, 'on' => 'create, update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('community,region,title, branch_id,start_time,end_time,category_id', 'safe', 'on' => 'search'),
            array('logoFile,bigPicFile,url', 'safe', 'on' => 'create, update'),
            //			ICE 搜索数据
            array('communityIds,province_id,city_id,district_id', 'safe'),
        );
    }

    public function afterFind()
    {
        $this->startTime = $this->start_time == 0 ? "" : date('Y-m-d', $this->start_time);
        $this->endTime = $this->end_time == 0 ? "" : date('Y-m-d', $this->end_time);
        return parent::afterFind();
    }


    public function checkEndTime($attribute, $params)
    {
        if (!$this->hasErrors() && $this->end_time <= time()) {
            $this->addError('endTime', '专题活动结束时间不能小于当前时间！');
        }
    }

    public function checkCheapTime($attribute, $params)
    {
        if (!$this->hasErrors() && $this->end_time <= $this->start_time) {
            $this->addError('endTime', '专题活动结束时间不能小于专题活动开始时间！');
        }
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
            'event_category' => array(self::BELONGS_TO, 'EventCategory', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'branch_id' => '管辖部门',
            'category_id' => '专题活动分类',
            'title' => '标题',
            'content' => '内容',
            'logo' => 'Logo',
            'logoFile' => 'Logo 文件',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'is_reserve' => '是否预订',
            'big_pic' => '专题活动大图',
            'bigPicFile' => '专题活动大图',
            'url' => '链接地址',
            'community_ids' => '服务范围',
            'community' => '小区',
            'region' => '地区',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
//        $employee = Employee::model()->findByPk(Yii::app()->user->id);

        $criteria->compare('id', $this->id);
        // $criteria->compare('branch_id', $this->branch_id);

        if ($this->branch_id != '')
            $criteria->addInCondition('branch_id', Branch::model()->findByPk($this->branch_id)->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
//            $criteria->addInCondition('branch_id', $employee->getBranchIds());
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
            $criteria->addInCondition('branch_id', Employee::ICEgetOldBranchIds());

        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('title', $this->title, true);
        //$criteria->compare('start_time', $this->start_time);
        //$criteria->compare('end_time', $this->end_time);

        if ($this->start_time != "") {
            $criteria->addCondition('start_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('end_time<=' . strtotime($this->end_time . " 23:59:59"));
        }


//        if (!empty($this->community)) {
//            $criteria->distinct = true;
//            $criteria->join = 'inner join event_community_relation e on e.event_id=t.id';
//            $criteria->addInCondition('e.community_id', $this->community);
//        } else if ($this->region != '') {
//            $criteria->distinct = true;
//            $criteria->join = 'inner join event_community_relation e on e.event_id=t.id';
//            $criteria->addInCondition('e.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
//        }
//          
        if (!empty($this->communityIds)) {
            $community_ids = $this->communityIds;
            $criteria->distinct = true;
            $criteria->join = 'inner join event_community_relation e on e.event_id=t.id';
            $criteria->addInCondition('e.community_id', $community_ids);
        } else if ($this->province_id) {
            //如果有地区
            if ($this->district_id) {
                $regionId = $this->district_id;
            } else if ($this->city_id) {
                $regionId = $this->city_id;
            } else if ($this->province_id) {
                $regionId = $this->province_id;
            } else {
                $regionId = 0;
            }
            $community_ids = ICERegion::model()->getRegionCommunity(
                $regionId,
                'id'
            );
            $criteria->distinct = true;
            $criteria->join = 'inner join event_community_relation e on e.event_id=t.id';
            $criteria->addInCondition('e.community_id', $community_ids);
        }


        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.id desc',
            )
        ));
    }

    protected function beforeValidate()
    {
        if (empty($this->startTime)) $this->startTime = date('Y-m-d', time());
        if (empty($this->endTime)) $this->endTime = date('Y-m-d', time());
        $this->start_time = @strtotime($this->startTime);
        $this->end_time = @strtotime($this->endTime) + 24 * 60 * 60 - 1;
        if (empty($this->logo) && !empty($this->logoFile))
            $this->logo = '';
        if (empty($this->big_pic) && !empty($this->bigPicFile))
            $this->big_pic = '';
        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->logoFile)) {
            $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
        }
        if (!empty($this->bigPicFile)) {
            $this->big_pic = Yii::app()->ajaxUploadImage->moveSave($this->bigPicFile, $this->big_pic);
        }
        return parent::beforeSave();
    }

    protected function beforeDelete()
    {
        //删除关系表数据
        EventCommunityRelation::model()->deleteAllByAttributes(array('event_id' => $this->id));
        return parent::beforeDelete();
    }

    public function getBranchTag()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '所属部门:' .
            Branch::getMyParentBranchName($this->branch_id)), $this->branchName);
    }

    /**
     * 截取字符并hover显示全部字符串
     * $str string 截取的字符串
     * $len int 截取的长度
     */
    public function getFullString($str, $len)
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
    }

    public function getLogoUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo, '/common/images/nopic-map.jpg');
    }

    public function getBigPic()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->big_pic, '/common/images/nopic-map.jpg');
    }

    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior'
            ),
        );
    }

    /**类型名称
     * @return string
     */
    public function getCategoryName()
    {
        $name = $this->belongKey . '_category';
        return isset($this->$name) ? $this->$name->name : '';
    }

    public function getBranchName()
    {
        return isset($this->branch) ? $this->branch->name : '';
    }

    public function getCommunityTreeData()
    {
        $branch = empty($this->branch) ? Branch::model() : $this->branch;
        return $branch->getRegionCommunityRelation($this->id, 'Event');
    }

    public function ICEGetLinkageRegionDefaultValue()
    {
        $updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
        return $updateDefaults
            ? $updateDefaults
            : $this->ICEGetLinkageRegionDefaultValueForSearch();
    }

    protected function ICEGetLinkageRegionDefaultValueForUpdate()
    {
        return array();
    }

    public function ICEGetLinkageRegionDefaultValueForSearch()
    {
        $searchRegion = $this->ICEGetSearchRegionData(isset($_GET[__CLASS__])
            ? $_GET[__CLASS__] : array());

        $defaultValue = array();

        if ($searchRegion['province_id']) {
            $defaultValue[] = $searchRegion['province_id'];
        } else {
            return $defaultValue;
        }

        if ($searchRegion['city_id']) {
            $defaultValue[] = $searchRegion['city_id'];
        } else {
            return $defaultValue;
        }

        if ($searchRegion['district_id']) {
            $defaultValue[] = $searchRegion['district_id'];
        } else {
            return $defaultValue;
        }

        return $defaultValue;
    }

    protected function ICEGetSearchRegionData($search = array())
    {
        return array(
            'province_id' => isset($search['province_id']) && $search['province_id']
                ? $search['province_id'] : '',
            'city_id' => isset($search['city_id']) && $search['city_id']
                ? $search['city_id'] : '',
            'district_id' => isset($search['district_id']) && $search['district_id']
                ? $search['district_id'] : '',
        );
    }

}
