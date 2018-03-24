<?php

/**
 * This is the model class for table "meeting_sign".
 *
 * The followings are the available columns in table 'meeting_sign':
 * @property integer $id
 * @property integer $from_type
 * @property string $from_account
 * @property string $oa_username
 * @property string $user_mobile
 * @property integer $meeting_id
 * @property integer $sign_time
 * @property string $sign_location
 */
class MeetingSign extends CActiveRecord
{
	public $modelName = '会议签到报表';
	private static $reportFetchRowLimit = 1000;   //一次读取最大行数
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'meeting_sign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from_type, user_mobile, meeting_id', 'required'),
			array('meeting_id, sign_time', 'numerical', 'integerOnly'=>true),
			array('from_account', 'length', 'max'=>100),
			array('from_type', 'length', 'max'=>100),
			array('oa_username', 'length', 'max'=>250),
			array('user_mobile', 'length', 'max'=>15),
			array('sign_location', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, from_type, from_account, oa_username, user_mobile, meeting_id, sign_time, sign_location ', 'safe', 'on'=>'search'),
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
			'meeting' => array(self::HAS_ONE, 'Meeting', array('id'=>'meeting_id')),
			'employeeMeeting' => array(self::HAS_ONE, 'EmployeeMeeting', array('oa_username'=>'oa_username')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from_type' => '扫描途径',
			'from_account' => '来源ID',
			'oa_username' => 'OA账号',
			'user_mobile' => '手机号码',
			'meeting_id' => '会议ID',
			'sign_time' => '签到时间',
			'sign_location' => '签到地点',
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

		$criteria=new CDbCriteria;

		if(isset($this->oa_username)){
			$criteria->with[]='employeeMeeting';
			$criteria->compare('employeeMeeting.department', $this->oa_username, true);
		}
		if(isset($this->meeting_id)){
			$criteria->with[]='meeting';
			$criteria->compare('meeting.title', $this->meeting_id, true);
		}
		//dump($criteria);
		$criteria->compare('id',$this->id);
		$criteria->compare('from_type',$this->from_type);
		$criteria->compare('from_account',$this->from_account,true);
		//$criteria->compare('oa_username',$this->oa_username,true);
		$criteria->compare('user_mobile',$this->user_mobile,true);
		//$criteria->compare('meeting_id',$this->meeting_id);
		$criteria->compare('sign_time',$this->sign_time);
		$criteria->compare('sign_location',$this->sign_location,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function report_search(){
		$criteria=new CDbCriteria;
		if (isset($_GET['MeetingSign']) && !empty($_GET['MeetingSign'])) {
			$_SESSION['MeetingSign'] = array();
			$_SESSION['MeetingSign'] = $_GET['MeetingSign'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['MeetingSign']) && !empty($_SESSION['MeetingSign'])) {
				foreach ($_SESSION['MeetingSign'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		if(isset($this->oa_username)){
			$criteria->with[]='employeeMeeting';
			$criteria->compare('employeeMeeting.department', $this->oa_username, true);
		}
		if(isset($this->meeting_id)){
			$criteria->with[]='meeting';
			$criteria->compare('meeting.title', $this->meeting_id, true);
		}
		$criteria->compare('id',$this->id);
		$criteria->compare('from_type',$this->from_type);
		$criteria->compare('from_account',$this->from_account,true);
		//$criteria->compare('oa_username',$this->oa_username,true);
		$criteria->compare('user_mobile',$this->user_mobile,true);
		//$criteria->compare('meeting_id',$this->meeting_id);
		$criteria->compare('sign_time',$this->sign_time);
		$criteria->compare('sign_location',$this->sign_location,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MeetingSign the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//获取会议名称
	public function getMeetName(){
		if(!empty($this->meeting)){
			return $this->meeting->title;
		}
	}

	//获取签到时间
	public function getSignTime()
	{
		return date("Y-m-d H:i:s", $this->sign_time);
	}

	//获取职位
	public function getJobName(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->job_name;
		}
	}

	//获取部门
	public function getDepartment(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->department;
		}
	}

	//获取姓名
	public function getOaName(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->name;
		}
	}

	//获取组织架构1
	public function getLevelOne(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->org_level_one;
		}
	}

	//获取组织架构2
	public function getLevelTwo(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->org_level_two;
		}
	}

	//获取组织架构3
	public function getLevelThree(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->org_level_three;
		}
	}

	//获取组织架构4
	public function getLevelFour(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->org_level_four;
		}
	}

	//获取组织架构5
	public function getLevelFive(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->org_level_five;
		}
	}

	//获取组织架构6
	public function getLevelSix(){
		if(!empty($this->employeeMeeting)){
			return $this->employeeMeeting->org_level_six;
		}
	}

	public function getTypeName(){
		if($this->from_type == 'caiguanjia'){
			return '彩管家';
		}else if ($this->from_type == '1'){
			return '微信';
		}else if ($this->from_type == '3'){
			return '彩之云';
		}else{
			return '其他';
		}
	}

	public function ExecuteQuery($aid, $reportVisitor)
	{
		if(empty($aid))
		{
			return array();
		}
		ini_set('memory_limit', '1024M');
		ini_set('max_execution_time', 0);
		set_time_limit(0);

		$sql = "SELECT
					me.title AS 会议名称,
					ms.oa_username AS OA账号,
					CASE
						WHEN ms.from_type='caiguanjia' THEN '彩管家'
						WHEN ms.from_type=3 THEN '彩之云'
						WHEN ms.from_type=1 THEN '微信'
					END 扫描途径,
					ms.from_account AS 扫描账号,
					ms.user_mobile AS 手机号码,
					FROM_UNIXTIME(ms.sign_time) AS 签到时间,
					ms.sign_location AS 签到地点,
					em.`name` AS 姓名,
					em.job_name AS 职位,
					em.department AS 部门,
					em.org_level_one AS 公司,
					em.org_level_two AS 总部或事业部,
					em.org_level_three AS 大区,
					em.org_level_four AS 事业部,
					em.org_level_five AS 片区,
					em.org_level_six AS 小区
				FROM meeting_sign ms
				LEFT OUTER JOIN meeting me ON ms.meeting_id=me.id
				LEFT OUTER JOIN employee_meeting em ON ms.oa_username=em.oa_username
				WHERE ms.meeting_id={$aid}";

		if(!is_a($reportVisitor, "ReportFrameworkVisitor"))
		{
			throw new Exception("传入参数错误ReportFramework::ExecuteQuery");
		}

		$offset = 0;
		$rows = self::ExecuteReportForSqlQuery($sql, self::$reportFetchRowLimit, $offset);
		$reportVisitor->begin(count($rows) > 0);

		if(count($rows) > 0)
		{
			$header = array_keys($rows[0]);
		}
		else
		{
			$header = array();
		}

		$reportVisitor->buildHeader($header);

		while (count($rows) > 0)
		{
			$reportVisitor->buildBody($rows, $offset);
			if(count($rows) < self::$reportFetchRowLimit) break;
			$offset += count($rows);
			$rows = self::ExecuteReportForSqlQuery($sql, self::$reportFetchRowLimit, $offset);
		}

		$reportVisitor->end();
	}

	private static function ExecuteReportForSqlQuery($sql, $limit = -1, $offset = -1)
	{
		$cmd = Yii::app()->db->createCommand();

		if($limit != -1)
		{
			$sql .= " LIMIT $limit ";
		}

		if($offset != -1)
		{
			$sql .= " OFFSET $offset " ;
		}

		$cmd->setText($sql);
		return $cmd->queryAll();
	}
}
