<?php
Yii::import('common.components.models.Article');
/**
 * This is the model class for table "notify".
 *
 * The followings are the available columns in table 'notify':
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $contact
 * @property integer $branch_id
 * @property integer $is_deleted
 * @property string $address
 * @property integer $tel
 * @property string $desc
 * @property string $logo
 */
class Facility extends Article
{
    public $modelName = '黄页信息';
    public $logofile;
    public $community = array(); //小区
    public $region; //地区

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
        return 'facility';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('logo', 'file','allowEmpty'=>true,'types'=>'jpg, gif, png','maxSize'=>100 * 100 * 1, // 1MB'tooLarge'=>'The file was larger than 1MB. Please upload a smaller file.',)
            array('contact,contact_html,tel', 'required', 'on' => 'create, update'),
            array('contact_html', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'create, update'),
            array('category_id, branch_id,is_vip', 'numerical', 'integerOnly' => true, 'on' => 'create, update'),
            array('title, address,logo', 'length', 'max' => 255, 'on' => 'create, update'),
            array('logofile', 'safe', 'on' => 'create, update'), // 增加和编辑图片文件
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('community,region,category_id,title, branch_id,is_vip,order', 'safe', 'on' => 'search'),
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
            'facility_category' => array(self::BELONGS_TO, 'FacilityCategory', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category_id' => '分类',
            'title' => '标题',
            'contact' => '内容',
            'contact_html' => 'HTML 内容',
            'branch_id' => '管辖部门',
            'address' => '地址',
            'tel' => '电话',
            'desc' => '详细说明',
            'logo' => 'Logo',
            'pic' => 'Logo',
            'logofile' => 'Logo 图片',
            'is_vip' => '是否签约商家',
            'community_ids' => '服务范围',
            'community' => '小区',
            'region' => '地区',
            'order' => '排序',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('contact', $this->contact, true);
        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        //选择的组织架构ID
        if (!empty($this->branch))
            $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
            $criteria->addInCondition('branch_id', $employee->getBranchIds());

        $criteria->compare('is_vip', $this->is_vip);

        if (!empty($this->community)) {
            $criteria->distinct = true;
            $criteria->join = 'inner join facility_community_relation f on f.facility_id=t.id';
            $criteria->addInCondition('f.community_id', $this->community);
        } else if ($this->region != '') {
            $criteria->distinct = true;
            $criteria->join = 'inner join facility_community_relation f on f.facility_id=t.id';
            $criteria->addInCondition('f.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
        }
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.id desc',
            )
        ));

        // return new ActiveDataProvider($this, array(
        //    'criteria' => $criteria,
        // ));
    }

    protected function beforeValidate()
    {
        //$this->logofile = CUploadedFile::getInstance($this, 'logofile');
        if (empty($this->logo) && !empty($this->logofile))
            $this->logo = '';

        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->logofile)) {
            $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logofile, $this->logo);
        }
        return parent::beforeSave();
    }

    public function getBranchTag()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '所属部门:' .
            Branch::getMyParentBranchName($this->branch_id)), $this->branchName);
    }

    /**
     * 截取字符并hover显示全部字符串
     * $str string  截取的字符串
     * $len int 截取的长度
     */
    public function getFullString($str, $len)
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
    }

    protected function beforeDelete()
    {
        //删除关系表数据
        FacilityCommunityRelation::model()->deleteAllByAttributes(array('facility_id' => $this->id));
        return parent::beforeDelete();
    }

    public function getPic()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo, '/common/images/nopic-logo.jpg');
    }

    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    public function getVipNames($select = false)
    {
        $return = array();
        if ($select) {
            $return[''] = '全部';
        }
        $return[1] = '是';
        $return[0] = '否';
        return $return;
    }

    /**类型名称
     * @return string
     */
    public function  getCategoryName()
    {
        $name = 'facility_category';
        return isset($this->$name) ? $this->$name->name : '';
    }


    public function getCommunityTreeData()
    {
        $branch = empty($this->branch) ? Branch::model() : $this->branch;
        return $branch->getRegionCommunityRelation($this->id, 'Facility');
    }

    public function getLogoImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo, '/common/images/nopic-logo.jpg');
    }
}
