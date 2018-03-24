<?php

/**
 * This is the model class for table "app".
 *
 * The followings are the available columns in table 'app':
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $contact
 * @property integer $branch_id
 * @property integer $is_deleted
 * @property integer $client_count
 * @property string $download_url
 * @property string $developers
 * @property string $pic_title_url
 * @property string $pic_detail_url
 * @property string $pic_contact_url
 */
class App extends CActiveRecord
{
	public $modelName = '精品推荐';
	public $belongKey = 'app';
	public $belongModel = 'AppCategory';
	public $logofile_1;
	public $logofile_2;
	public $logofile_3;
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
		return 'app';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,contact,contact_html', 'required', 'on' => 'create,update'),
			array('contact_html', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'create, update'),
			array('client_count,category_id,branch_id', 'numerical', 'integerOnly' => true, 'on' => 'create,update'),
			array('title,pic_title_url,pic_detail_url,pic_contact_url,download_url,developers', 'length', 'max' => 255, 'on' => 'create,update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('region,community,title, branch_id,developers,category_id', 'safe', 'on' => 'search'),
			array('logofile_1,logofile_2,logofile_3', 'safe', 'on' => 'create,update'),
			//			ICE 搜索数据
			array('communityIds,province_id,city_id,district_id', 'safe'),
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
			$this->belongKey . '_category' => array(self::BELONGS_TO, $this->belongModel, 'category_id'),
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
			'branchName' => '管辖部门',
			'is_deleted' => 'Is Deleted',
			'client_count' => '下载数',
			'category_id' => '分类',
			'categoryName' => '分类',
			'download_url' => 'Url',
			'pic_title_url' => '缩略图',
			'pic_detail_url' => '图片',
			'pic_contact_url' => '图片',
			'developers' => '开发商',
			'pic1' => 'Logo 图一',
			'pic2' => 'Logo 图二',
			'pic3' => 'Logo 图三',
			'logofile_1' => 'Logo 图一',
			'logofile_2' => 'Logo 图二',
			'logofile_3' => 'Logo 图三',
			'community_ids' => '服务范围',
			'region' => '地区',
			'community' => '小区'
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

		$criteria->compare('id', $this->id);
		$criteria->compare('category_id', $this->category_id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('contact', $this->contact, true);
		$criteria->compare('developers', $this->developers, true);

//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
//        $employee = Employee::model()->findByPk(Yii::app()->user->id);

		//选择的组织架构ID
		if (!empty($this->branch))
			$criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
		else //自己的组织架构的ID
//			$criteria->addInCondition('branch_id', $employee->getBranchIds());
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
			$criteria->addInCondition('branch_id', Employee::ICEgetOldBranchIds());

//        if (!empty($this->community)) {
//            $criteria->distinct = true;
//            $criteria->join = 'inner join app_community_relation a on a.app_id=t.id';
//            $criteria->addInCondition('a.community_id', $this->community);
//        } else if ($this->region != '') {
//            $criteria->distinct = true;
//            $criteria->join = 'inner join app_community_relation a on a.app_id=t.id';
//            $criteria->addInCondition('a.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
//        }

		if (!empty($this->communityIds)) {
			$community_ids = $this->communityIds;
			$criteria->distinct = true;
			$criteria->join = 'inner join app_community_relation a on a.app_id=t.id';
			$criteria->addInCondition('a.community_id', $community_ids);
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
			$criteria->join = 'inner join app_community_relation a on a.app_id=t.id';
			$criteria->addInCondition('a.community_id', $community_ids);
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
		if (empty($this->pic_title_url) && !empty($this->logofile_1))
			$this->pic_title_url = '';
		if (empty($this->pic_detail_url) && !empty($this->logofile_2))
			$this->pic_detail_url = '';
		if (empty($this->pic_contact_url) && !empty($this->logofile_3))
			$this->pic_contact_url = '';

		return parent::beforeValidate();
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{

		if (!empty($this->logofile_1)) {
			$this->pic_title_url = Yii::app()->ajaxUploadImage->moveSave($this->logofile_1, $this->pic_title_url);
		}
		if (!empty($this->logofile_2)) {
			$this->pic_detail_url = Yii::app()->ajaxUploadImage->moveSave($this->logofile_2, $this->pic_detail_url);
		}
		if (!empty($this->logofile_3)) {
			$this->pic_contact_url = Yii::app()->ajaxUploadImage->moveSave($this->logofile_3, $this->pic_contact_url);
		}
		return parent::beforeSave();
	}

	protected function beforeDelete()
	{
		//删除关系表数据
		AppCommunityRelation::model()->deleteAllByAttributes(array('app_id' => $this->id));
		return parent::beforeDelete();
	}

	public function getPic1()
	{
		return Yii::app()->imageFile->getUrl($this->pic_title_url);
	}

	public function getPic2()
	{
		return Yii::app()->imageFile->getUrl($this->pic_detail_url);
	}

	public function getBranchTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '所属部门:' .
			Branch::getMyParentBranchName($this->branch_id)), $this->branchName);
	}

	public function getPic3()
	{
		return Yii::app()->imageFile->getUrl($this->pic_contact_url);
	}

	public function behaviors()
	{
		return array(
			'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
		);
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

	public function getFullPic_title_url()
	{
		return Yii::app()->imageFile->getUrl($this->pic_title_url);
	}

	public function getFullPic_detail_url()
	{
		return Yii::app()->imageFile->getUrl($this->pic_detail_url);
	}

	public function getFullPic_contact_url()
	{
		return Yii::app()->imageFile->getUrl($this->pic_contact_url);
	}

	public function getCommunityTreeData()
	{
		$branch = empty($this->branch) ? Branch::model() : $this->branch;
		return $branch->getRegionCommunityRelation($this->id, 'App');
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
