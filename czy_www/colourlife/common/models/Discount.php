<?php
Yii::import('common.components.models.Article');
/**
 * This is the model class for table "discount".
 *
 * The followings are the available columns in table 'discount':
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $contact
 * @property integer $branch_id
 * @property integer $is_deleted
 * @property integer $create_time
 * @property integer $effective_time
 * @property string $address
 * @property string $tel
 * @property string $pic_title_url
 * @property string $pic_detail_url
 * @property string $pic_contact_url
 * @property string $is_auto_chance_community
 */
class Discount extends Article
{
    public $modelName = '电子优惠券';
    public $belongKey = 'branch';
    public $belongModel = 'Branch';
    public $logofile;
    public $community_ids = array();
    //以下字段仅供搜索用
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
        return 'discount';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('contact, contact_html, title', 'required', 'on' => 'create, update'),
            array('contact_html', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'create, update'),
            array('category_id, branch_id,is_auto_chance_community', 'numerical', 'integerOnly' => true, 'on' => 'create, update'),
            array('title', 'length', 'max' => 255, 'on' => 'create, update'),
            array('logofile', 'safe', 'on' => 'create, update'), // 增加和编辑图片文件
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('community,region,title, branch_id', 'safe', 'on' => 'search'),
            //检测优惠卷是否能发布到部门
            array('branch_id', 'checkBranch', 'on' => 'create,update'),
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
            'discount_category' => array(self::BELONGS_TO, 'DiscountCategory', 'category_id'),
            'discoutPicture' => array(self::HAS_MANY, 'DiscountPicture', 'discount_id'),
            'communities' => array(self::HAS_MANY, 'DiscountCommunityRelation', '',
                'on' => 'communities.discount_id=discount_id'),
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
            'effective_time' => '有效时间',
            'address' => '地址',
            'tel' => '电话',
            'pic_title_url' => '缩略图',
            'pic_detail_url' => '图片',
            'pic_contact_url' => '图片',
            'logofile' => 'Logo 图',
            'pic' => 'Logo',
            'is_auto_chance_community' => '是否自动增加服务小区范围',
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
        $employee = Employee::model()->findByPk(Yii::app()->user->id);

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('contact', $this->contact, true);

        //选择的组织架构ID
        if ($this->branch_id != '')
            $criteria->addInCondition('branch_id', Branch::model()->findByPk($this->branch_id)->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
            $criteria->addInCondition('branch_id', $employee->getBranchIds());

        if (!empty($this->community)) {
            $criteria->distinct = true;
            $criteria->join = 'inner join discount_community_relation d on d.discount_id=t.id';
            //$criteria->compare('d.community_id', $this->community);
            $criteria->addInCondition('d.community_id', $this->community);
        } else if ($this->region != '') {
            $criteria->distinct = true;
            $criteria->join = 'inner join discount_community_relation d on d.discount_id=t.id';
            $criteria->addInCondition('d.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
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
        if (empty($this->pic_title_url) && !empty($this->logofile))
            $this->pic_title_url = '';

        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->logofile)) {
            $this->pic_title_url = Yii::app()->ajaxUploadImage->moveSave($this->logofile, $this->pic_title_url);
        }
        return parent::beforeSave();
    }

    //清除关联关系
    public function beforeDelete()
    {
        return parent::beforeDelete();
    }

    public function getPic()
    {
        return Yii::app()->imageFile->getUrl($this->pic_title_url);
    }

    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    /**类型名称
     * @return string
     */
    public function  getCategoryName()
    {
        $name = 'discount_category';
        return isset($this->$name) ? $this->$name->name : '';
    }

    public function delete()
    {
        //删除图片库的图片文件和数据库记录
        foreach ($this->discoutPicture as $pic) {
            Yii::app()->ajaxUploadImage->delete($pic->url);
            $pic->updateByPk($pic->id, array('is_deleted' => Item::DELETE_YES));
        }
        //删除自己的相关图片
        Yii::app()->ajaxUploadImage->delete($this->pic_title_url);
        Yii::app()->ajaxUploadImage->delete($this->pic_detail_url);
        Yii::app()->ajaxUploadImage->delete($this->pic_contact_url);
        //删除数据
        $this->updateByPk($this->id, array('is_deleted' => Item::DELETE_YES));

        //删除关系表数据
        DiscountCommunityRelation::model()->deleteAllByAttributes(array('discount_id' => $this->id));
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

    public function getFullPic_title_url()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->pic_title_url);
    }

    public function getFullPic_detail_url()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->pic_detail_url);
    }

    public function getFullPic_contact_url()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->pic_contact_url);
    }

    public function getPhotos()
    {
        return isset($this->discoutPicture) ? $this->discoutPicture : null;
    }

    public function checkBranch($attribute, $params)
    {
        if (empty($this->branch_id)) { //组织架构ID不能为空
            $this->addError('branch_id', '组织架构不能为空');
            return false;
        }
        //得到操作优惠所在的组织架构
        $user = Employee::model()->enabled()->findByPk(Yii::app()->user->id);
        $branchIds = $user->getBranchIds();

        if (!$this->hasErrors() && !in_array($this->branch_id, $branchIds)) {
            $this->addError('branch_id', '关联组织架构失败！');
        }
    }

    public function getCommunityTreeData()
    {
        $branch = empty($this->branch) ? Branch::model() : $this->branch;
        return $branch->getRegionCommunityRelation($this->id, 'Discount');
    }

}
