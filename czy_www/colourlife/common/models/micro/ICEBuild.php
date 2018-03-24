<?php

/**
 * This is the model class for table "build".
 *
 * The followings are the available columns in table 'build':
 * @property integer $id
 * @property string $name
 * @property integer $community_id
 * @property integer $state
 * @property integer $is_deleted
 */
class ICEBuild extends CActiveRecord
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '楼栋';

	public $director;

	public $community;

	public $uuid;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Build the static model class
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
		return 'build';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('community_id', 'checkEnable', 'on' => 'create'),
			array('name', 'required', 'on' => 'create, update'),
			array('state', 'required', 'on' => 'create'),
			array('state', 'checkEnable', 'on' => 'enable'),
			array('state', 'checkDisable', 'on' => 'disable'),
			array('is_deleted', 'checkDelete', 'on' => 'delete'),
			array('community_id, state, is_deleted,employee', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 50, 'on' => 'create, update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, state,employee', 'safe', 'on' => 'search'),
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
			//'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
			'customersCount' => array(self::STAT, 'Customer', 'build_id', 'condition' => 't.is_deleted=0'),
			'enabledCustomersCount' => array(self::STAT, 'Customer', 'build_id', 'condition' => 't.is_deleted=0 AND t.state=0'),
			'employees' => array(self::BELONGS_TO, 'Employee', 'employee'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '楼栋',
			'community_id' => '小区',
			'employee' => '楼栋经理',
			'employeeName' => '楼栋经理',
			'director' => '楼栋经理',
			'state' => '状态',
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

		$criteria->compare('name', $this->name, true);
		$criteria->compare('community_id', $this->community_id);
		$criteria->compare('state', $this->state);
		$criteria->compare('employee', $this->employee);
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),
			'StateBehavior' => array(
				'class' => 'common.components.behaviors.StateBehavior',
			),
		);
	}

	public function checkEnable($attribute, $params)
	{
		if (!$this->hasErrors() && (empty($this->community) || $this->community->isDisabled)) {
			//$this->addError($attribute, '指定的小区不存在或被禁用，无法创建、修改或启用' . $this->modelName);
		}
	}

	public function checkDisable($attribute, $params)
	{
		if (!$this->hasErrors() && (!empty($this->enabledCustomersCount))) {
			$this->addError($attribute, '该' . $this->modelName . '存在业主用户，不能被禁用。');
		}
	}

	public function checkDelete($attribute, $params)
	{
		if (!$this->hasErrors() && (!empty($this->customersCount))) {
			$this->addError($attribute, '该' . $this->modelName . '存在业主用户，不能被删除。');
		}
	}

	public function getBuildName($build_id = "")
	{
		if ($build_id == "") {
			return "";
		} else {
			$model = Build::model()->findByPk($build_id);
			if (!empty($model)) {
				return $model->name;
			} else {
				return "";
			}
		}
	}

	public function getEmployeeName()
	{
		return (empty($this->employees)) ? "" : $this->employees->name;
	}

	public function ICEGetBuildingList($communityId = 0, $cache = true)
	{
		if (!$communityId) {
			return null;
		}
		$community = ICECommunity::model()->findByPk($communityId);
		if (!$community || !$community->uuid) {
			return null;
		}

		$cacheKey = 'cache:cwy:ice:building:coummunity:' . $community->uuid;

		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
			if ($result) {
				return $result;
			}
		}

		$result = array();
		try {
			$result = ICEService::getInstance()->dispatch(
				'building/list',
				array(
					'smallarea_uuid' => $community->uuid
				),
				array(),
				'get'
			);
		} catch (Exception $e) {
			Yii::log(
				sprintf('获取ICE楼栋失败。uuid(czy_id): %s', $community->uuid),
				CLogger::LEVEL_ERROR, 'colourlife.core.ICEBuild.ICEGetBuildingList'
			);
		}

		if ($result && isset($result['housetype']) && $result['housetype']) {
			$result = $this->parseRMSBuildingToColourlife($communityId, $result['housetype']);
		} else {
			$result = array();
		}

		if ($result) {
			Yii::app()->rediscache->set(
				$cacheKey,
				$result,
				ICEService::GetCacheExpire()
			);
		}

		return $result;
	}

	protected function parseRMSBuildingToColourlife($communityId = 0, $builds = array())
	{
		if (!$communityId || !$builds) {
			return array();
		}

		$colourlifeBuilds = array();
		foreach ($builds as $uuid => $name) {
			$colourlifeBuild = Build::model()->find(
				'community_id = :community_id AND name = :name',
				array(
					':community_id' => $communityId,
					':name' => $name,
				)
			);
			if (!$colourlifeBuild) {
				$model = new Build();
				$model->community_id = $communityId;
				$model->name = $name;
				$model->state = '0';
				$model->is_deleted = '0';
				$model->display_order = '1';
				$model->employee = '0';
				$model->uuid = $uuid;
				$model->save();
				$buildId = $model->id;
			} else {
				if ($colourlifeBuild->uuid != $uuid) {
					$colourlifeBuild->uuid = $uuid;
					$colourlifeBuild->save();
				}
				$buildId = $colourlifeBuild->id;
			}

			$colourlifeBuilds[] = array(
				'id' => $buildId,
				'name' => $name
			);
		}
		return $colourlifeBuilds;
	}
}
