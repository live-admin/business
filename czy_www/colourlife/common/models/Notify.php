<?php
//Yii::import('common.components.models.Article');
/**
 * This is the model class for table "notify".
 *
 * The followings are the available columns in table 'notify':
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $contact
 * @property integer $audit
 * @property string $comment
 * @property integer $branch_id
 * @property integer $is_deleted
 */
class Notify extends CActiveRecord
{
	public $modelName = '通知';
	public $belongKey = 'notify';
	public $belongModel = 'NotifyCategory';
	public $community = array(); //小区
	public $region; //地区

	public $province_id;
	public $city_id;
	public $district_id;

	public $communityIds;
	public $branch_id;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contact,contact_html,category_id', 'required', 'on' => 'create, update'),
			array('contact_html', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'create, update'),
			array('audit,comment', 'required', 'on' => 'auditOK, auditNO'),
			array('audit', 'checkAudit', 'on' => 'auditOK, auditNO'), // 审核通过
			array('audit,category_id, branch_id', 'numerical', 'integerOnly' => true),
			array('title', 'length', 'max' => 255, 'on' => 'create, update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('contact,audit,comment,community,region,title, branch_id', 'safe', 'on' => 'search'),
			//			ICE 搜索数据
			array('branch_id,communityIds,province_id,city_id,district_id', 'safe'),
		);
	}

	/**
	 * 检查指审核时是否有审核过的状态
	 * @param $attribute
	 * @param $params
	 */
	public function checkAudit($attribute, $params)
	{
		if (!$this->hasErrors()) {
			if ($this->isAudited) {
				$this->addError($attribute, $this->modelName . '已审核过，无法操作。');
			}
		}
	}

	public function beforeSave()
	{
		if (Yii::app()->config->autoAudit == 1) {
			//echo '111';
			$this->audit = 1;
			$this->comment = '系统自动审核';
		}
		return parent::beforeSave();
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category_id' => '分类',
			'categoryName' => '分类',
			'title' => '标题',
			'contact' => '内容',
			'contact_html' => 'HTML 内容',
			'branch_id' => '管辖部门',
			'branchName' => '管辖部门',
			'create_time' => '创建时间',
			'audit' => '审核',
			'comment' => '审核意见',
			'community_ids' => '服务范围',
			'community' => '小区',
			'region' => '地区',
		);
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => false,
			),
			'AuditBehavior' => array(
				'class' => 'common.components.behaviors.AuditBehavior',
			),
			'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
		);
	}

	public function auditOK()
	{
		$this->updateByPk($this->id, array('audit' => 1));
	}

	public function auditNO()
	{
		$this->updateByPk($this->id, array('audit' => 2));
	}

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
		$criteria->compare('audit', $this->audit);
		$criteria->compare('comment', $this->comment, true);
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
//		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		// 选择的组织架构ID
		if (!empty($this->branch))
		    $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
		else //自己的组织架构的ID
//		    $criteria->addInCondition('branch_id', $employee->getBranchIds());
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
		    $criteria->addInCondition('branch_id', Employee::ICEgetOldBranchIds());

		// if (!empty($this->community)) {//如果有小区
		//     $criteria->distinct = true;
		//     $criteria->join = 'inner join notify_community_relation  n  on n.notify_id=t.id';
		//     $criteria->addInCondition('n.community_id', $this->community);
		// } else if ($this->region != '') {//如果有地区
		//     $criteria->distinct = true;
		//     $criteria->join = 'inner join notify_community_relation n on n.notify_id=t.id';
		//     $criteria->addInCondition('n.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
		// }

		$community_ids = array();
		if (!empty($this->communityIds)) {//如果有小区
			$community_ids = $this->communityIds;
		} else if ($this->province_id) { //如果有地区
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
		}

		if ($community_ids) {
			$criteria->distinct = true;
			$criteria->join = 'inner join notify_community_relation  n  on n.notify_id=t.id';
			$criteria->addInCondition('n.community_id', $community_ids);
		}

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notify';
	}

	public function relations()
	{
		return array(
			'notify_category' => array(self::BELONGS_TO, 'NotifyCategory', 'category_id'),
			'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
		);
	}

	//上一条，
	public function topicPrev($id, $branch_id = null)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("is_deleted = 0");
		$criteria->order = 'create_time DESC';
		$criteria->condition = "id<:id";

		$criteria->params = array(':id' => $id);
		if (!empty($branch_id)) {
			$criteria->addInCondition('branch_id', $branch_id);
		}
		return $this->find($criteria);
	}

	//下一条
	public function topicNext($id, $branch_id = null)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("is_deleted = 0");
		$criteria->order = 'create_time ASC';
		$criteria->condition = "id>:id";
		$criteria->params = array(':id' => $id);
		if (!empty($branch_id)) {
			$criteria->addInCondition('branch_id', $branch_id);
		}
		return $this->find($criteria);
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

	protected function beforeDelete()
	{
		//删除关系表数据
		NotifyCommunityRelation::model()->deleteAllByAttributes(array('notify_id' => $this->id));
		return parent::beforeDelete();
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
		return $branch->getRegionCommunityRelation($this->id, 'Notify');
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
