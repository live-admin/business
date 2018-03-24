<?php

class  SpecialShop extends Shop
{
    public $modelName = '特约商家';

    public $columnName;

    public $lifeCateName;

    public $telPhone;//联系方式

    public $community;

    public $region;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {                                                                                                                                                                                                                                                                                                                                    
        $array = array(
            array("id,$this->columnName", 'required'),
            array("id,$this->columnName,benefit_display_order,house_display_order,educate_display_order,breakfast_display_order,
                    is_benefit,is_house,is_educate,is_breakfast,is_rabbit,rabbit_display_order", 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array("id,name,lifeCateName,mobile,tel,contact,telPhone,community,region", 'safe', 'on' => 'search'),
        );
        return CMap::mergeArray(parent::rules(), $array);
    }
    
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
      		'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
        	'servers'=>array(self::HAS_MANY,'Server','shop_id'),
        	'reserve'=>array(self::HAS_MANY,'Reserve','shop_id'),
            'cateName' => array(self::BELONGS_TO, 'LifeCategory', 'life_cate_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $array = array(
            'life_cate_id'=>'周边优惠分类',
            'life_display_order' => '优惠优先级',
      		'is_benefit'=>'是否是便民',
        	'benefit_display_order'=>'便民优先级',
        	'is_breakfast'=>'是否是营养早餐',
        	'breakfast_display_order'=>'优先级',
        	'is_educate'=>'是否是教育',
        	'educate_display_order'=>'教育优先级',
        	'is_house'=>'是否是房屋',
        	'house_display_order'=>'房屋优先级',
        	'is_rabbit'=>'是否是小白兔',
        	'rabbit_display_order'=>'优先级',
            'shopName'=>'商家名称',
        	'lifeCateName'=>'推荐类型',
            'telPhone'=>'联系电话',
            'community' => '小区',
            'region' => '地区',
        );
        return CMap::mergeArray(parent::attributeLabels(), $array);
    }

    public function delete()
    {
        return $this->updateByPk($this->id, array($this->columnName => '0'));
    }

    public function search()
    {

        $criteria = new CDbCriteria;
        $criteria->addCondition($this->columnName . '>0');

        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        //选择的组织架构ID
        if (!empty($this->branch))
            $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
            $criteria->addInCondition('branch_id', $employee->getBranchIds());

        if (!empty($this->community)) {
            $criteria->distinct = true;
            $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
            $criteria->addInCondition('s.community_id', $this->community);
        } else if ($this->region != '') {
            $criteria->distinct = true;
            $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
            $criteria->addInCondition('s.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
        }

        if ($this->name != '') {
            $criteria->compare('t.name', $this->name, true);
        }
        if($this->telPhone!=''){
            $criteria->addCondition('t.mobile like "%'.$this->telPhone.'%" OR t.tel like "%'.$this->telPhone.'%"');
        }
        $criteria->compare('t.contact', $this->contact, true);
        //$criteria->compare('t.mobile', $this->mobile, true);
        //$criteria->compare('t.tel', $this->tel, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getBranchTag()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '所属部门:' .
        Branch::getMyParentBranchName($this->branch_id)), $this->branch->name);
    }

    public function getTelPhone(){
        $telStr = empty($this->mobile)?(empty($this->tel)?"未提供联系方式":"固定电话:{$this->tel}"):("手机号码:{$this->mobile} ".(empty($this->tel)?"":"固定电话:{$this->tel}"));
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' =>$telStr), empty($this->mobile)?(empty($this->tel)?"未提供联系方式":$this->tel):$this->mobile);
    }

}