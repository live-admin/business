<?php

/**
 * 3.0 可配置资源 //TODO:kakatool
 */
class FeedActivityType extends CActiveRecord
{


	public $modelName = '活动类型';

	public $community_id;
	public $photoFile;
	public $from_date;
	public $to_date;

	public $is_default;

	/**
	 * @var PublicFunV30
	 */
	protected $publicFunc;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'feed_activity_type';
	}

	public function rules()
	{
		return array(
			array('name, audit, is_deleted, is_default', 'required', 'on' => 'create'),
			array('name, audit, is_deleted, is_default', 'required', 'on' => 'update'),
			array('name, photo, photoFile, audit, is_deleted, creationtime, modifiedtime,is_default', 'safe', 'on' => 'update,create'),
			array('id, name, photo, audit, is_deleted, creationtime, modifiedtime', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'photo' => '图片',
			'photoFile' => '图片',
			'audit' => '审核',
			'is_deleted' => '前台显示',
			'creationtime' => '创建时间',
			'modifiedtime' => '更新时间',
			'community_ids' => '服务范围',
			'community_id' => '小区',
			'is_default' => '全局默认',
		);
	}

	public function behaviors()
	{
		return array(
			//'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
			//'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
			//'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
		);
	}

	protected function getFromDatetime()
	{
		if ($this->from_date) {
			$datetime = strtotime($this->from_date);
			if ($datetime > 0) {
				return strtotime($this->from_date . ' 00:00:00');
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	protected function getToDatetime()
	{
		if ($this->to_date) {
			$datetime = strtotime($this->to_date);
			if ($datetime > 0) {
				return strtotime($this->to_date . ' 23:59:59');
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	public function getIsDefault()
	{
		return $this->is_default == 1 ? '是' : '否';
	}

	public function getIsDeleted()
	{
		return $this->is_deleted && $this->is_deleted  == '1' ? '否' : '是';
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->alias = 't';
		$criteria->compare('t.name', $this->name, true);
		//$criteria->compare('state', $this->state);
		$fromDatime = $this->getFromDatetime();
		$toDatetime = $this->getToDatetime();
		if ($fromDatime && $toDatetime) {
			$criteria->addBetweenCondition('t.creationtime', $fromDatime, $toDatetime);
		} else {
			if ($fromDatime) {
				$criteria->addCondition('t.creationtime >= ' . $fromDatime);
			}

			if ($toDatetime) {
				$criteria->addCondition('t.creationtime <= ' . $toDatetime);
			}
		}

		$criteria->order = 't.id,t.creationtime desc';
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getPhotoUrl()
	{
		if (strstr($this->photo, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($this->photo);
		} else {
			return Yii::app()->ajaxUploadImage->getUrl($this->photo);
		}
	}

	public function getCreationTime()
	{
		if ($this->creationtime) {
			return date('Y-m-d H:i:s', $this->creationtime);
		} else {
			return '';
		}
	}

	public function getModifiedTime()
	{
		if ($this->modifiedtime) {
			return date('Y-m-d H:i:s', $this->modifiedtime);
		} else {
			return '';
		}
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
		if (!empty($this->photoFile)) {
			$this->photo = Yii::app()->ajaxUploadImage->moveSave($this->photoFile, $this->photo);
		}
		if ($this->isNewRecord) {
			$this->creationtime = time();
			$this->audit = '1';
		}
		$this->modifiedtime = time();

		$this->is_deleted = $this->is_deleted && $this->is_deleted == 1 ? '1' : '0';

		return parent::beforeSave();
	}

	public function getCommunityTreeData()
	{
		//默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
		$branch_id = 1;
		$branch = Branch::model()->findByPk($branch_id);
		return $branch->getRegionCommunityRelation($this->id, 'FeedActivityType', false);
	}


	protected function getResourceImage($img = '', $nopic = '/common/images/nopic.png')
	{
		if(!$this->publicFunc){
			$this->publicFunc = new PublicFunV30();
		}
		return $this->publicFunc->setAbleUploadImg($img);
		if (strstr($img, 'v23')) {
			$url = F::getStaticsUrl('/common/' . $img);
		} else if (strstr($img, 'v30')) {
			$url = F::getStaticsUrl('/common/' . $img);
		} else {
			$img = trim($img, '/ ');
			if ($img) {
				$url = F::getUploadsPath('/' . $img);
			} else {
				$url = F::getUploadsPath('/' . $nopic);
			}
		}

		return $url;
	}

	protected function getTypeListData($communityID = 0,
	                                   $offset = 1, $perPage = 30,
	                                   $getTotal = false)
	{
		$communityID = (int)$communityID;
		$offset = (int)$offset;
		$perPage = (int)$perPage;

		if ($getTotal === true) {
			$total = 0;
			$command = Yii::app()->db->createCommand('SELECT
					count(distinct t.id)
				FROM
					feed_activity_type AS t
				LEFT JOIN feed_activity_type_community_relation AS r ON (t.id = r.activity_type_id)
				WHERE
					r.community_id = :community_id
				AND t.audit = 1
				AND t.is_deleted = 0');

			$command->bindParam(':community_id', $communityID, PDO::PARAM_INT);

			$reader = $command->query();

			$reader->bindColumn(1, $total);

			$reader->read();

			return $total;
		} else {
			$command = Yii::app()->db->createCommand('SELECT
					distinct t.id,
					t.`name`,
					t.photo
				FROM
					feed_activity_type AS t
				LEFT JOIN feed_activity_type_community_relation AS r ON (t.id = r.activity_type_id)
				WHERE
					r.community_id IN(:community_id, 0)
				AND t.audit = 1
				AND t.is_deleted = 0
				LIMIT :offset, :limit');

			$command->bindParam(':community_id', $communityID, PDO::PARAM_INT);
			$command->bindParam(':offset', $offset, PDO::PARAM_INT);
			$command->bindParam(':limit', $perPage, PDO::PARAM_INT);

			$reader = $command->query();

			$id = 0;
			$name = '';
			$photo = '';


			$reader->bindColumn(1, $id);
			$reader->bindColumn(2, $name);
			$reader->bindColumn(3, $photo);

			$lists = array();

			while ($reader->read() !== false) {
				$lists[] = array(
					'id' => (int)$id,
					'name' => $name,
					'photo' => $this->getResourceImage($photo),
				);
			}

			return $lists;
		}
	}

	protected function getTypeList($communityID = 0, $page = 1, $perPage = 30)
	{
		$communityID = max($communityID, 0);
		$page = max($page, 1);
		$perPage = max($perPage, 0);
		$offset = $perPage * ($page - 1);

		$lists = $this->getTypeListData($communityID, $offset, $perPage);
		$total = $this->getTypeListData($communityID, $offset, $perPage, true);

		$size = count($lists);
		$currentPage = $page + 1;
		$paged = array(
			'total' => $total, //总数
			'page' => $currentPage, //页号
			'size' => $size, //当前返回数量
			'more' => $total > $perPage * $currentPage ? 1 : 0, //是否还有更多
		);

		return array(
			$lists,
			$paged
		);
	}

	public function getCommunityFeedActivityTypeList($communityID = 0, $page = 1, $perPage = 30)
	{
		return $this->getTypeList($communityID, $page, $perPage);
	}

	public function getDefaultFeedActivityTypeList($page = 1, $perPage = 30)
	{
		return $this->getTypeList(0, $page, $perPage);
	}


	public function getFeedActivityTypeList($communityID = 0, $page = 1, $perPage = 30)
	{
		list($types, $paged) = $this->getCommunityFeedActivityTypeList(
			$communityID,
			$page,
			$perPage
		);
		if (!$types) {
			$typeList = $this->getDefaultFeedActivityTypeList($page, $perPage);
		} else {
			$typeList = array($types, $paged);
		}

		return $typeList;
	}
}
