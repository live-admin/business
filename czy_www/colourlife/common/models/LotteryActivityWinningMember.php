<?php

/**
 * This is the model class for table "lottery_activity_winning_member".
 *
 * The followings are the available columns in table 'lottery_activity_winning_member':
 * @property integer $id
 * @property integer $lottery_activity_id
 * @property integer $lottery_activity_prize_id
 * @property integer $customer_id
 * @property string $user_name
 * @property string $mobile
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class LotteryActivityWinningMember extends CActiveRecord
{
	public $_aid;
	public $_ticket; //0等奖，1不中奖，2饭票
	public $modelName = '中奖名单';
	private static $reportFetchRowLimit = 1000;   //一次读取最大行数
	//是否启用
	public $_status = array (
			''=>'全部',
			'0'=>'未领奖',
			'1'=>'已领奖'
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lottery_activity_winning_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lottery_activity_id, lottery_activity_prize_id, customer_id, status, is_show, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('prize_name', 'length', 'max'=>100),
			array('user_name', 'length', 'max'=>255),
			array('mobile', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lottery_activity_id, lottery_activity_prize_id, prize_name, customer_id, user_name, mobile, status, is_show, create_time, update_time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '表ID',
			'lottery_activity_id' => '活动名称',
			'lottery_activity_prize_id' => '奖项ID',
			'prize_name' => '奖项名称',
			'customer_id' => '用户ID',
			'user_name' => '用户姓名',
			'mobile' => '手机号',
			'status' => '状态',
			'is_show' => '是否展示（0展示，1不展示）',
			'create_time' => '添加时间',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		if (!empty($this->_aid)){
			$this->lottery_activity_id=$this->_aid;
		}
		$criteria->compare('lottery_activity_id',$this->lottery_activity_id);
		$criteria->compare('lottery_activity_prize_id',$this->lottery_activity_prize_id);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_show',0);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LotteryActivityWinningMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 是否启用
	 * @param string $state
	 * @return string
	 */
	public function getStatusName($status = '')
	{
		$return = '';
		switch ($status) {
			case '':
				$return = "";
				break;
			case 0:
				$return = '<span class="label label-error">'.$this->_status[0].'</span>';
				break;
			case 1:
				$return = '<span class="label label-success">'.$this->_status[1].'</span>';
				break;
		}
		return $return;
	}
	
	/*
	 * @version 领奖
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->status=1;
		$model->update_time=time();
		$model->save();
	}
	/*
	 * @version 取消领奖
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->status=0;
		$model->update_time=time();
		$model->save();
	}
	/**
	 * 获取活动名称
	 * @param unknown $aid
	 * @return string
	 */
	public function getActivityName($aid){
		if (empty($aid)){
			return '';
		}
		$name='';
		$activity=LotteryActivity::model()->findByPk($aid);
		if (!empty($activity)){
			$name=$activity->activity_name;
		}
		return $name;
	}
	
	/**
	 * 所有中奖名单
	 * @param unknown $aid
	 * @return multitype:|multitype:multitype:mixed NULL
	 */
	public function getWinningList($aid){
		if (empty($aid)){
			return array();
		}
		$data=array();
		$prizeList=$this->findAll("lottery_activity_id=:lottery_activity_id and is_show in (0,2) order by create_time desc", array (
					':lottery_activity_id' => $aid 
			));
		if (!empty($prizeList)){
			foreach ($prizeList as $val){
				$tmp=array();
				$tmp['mobile']=substr_replace($val->mobile, '****', 3, 4);
				$tmp['prize_name']=$val->prize_name;
				$data[]=$tmp;
			}
		}
		return $data;
	}
	
	/**
	 * 获取用户的中奖记录
	 * @param unknown $aid
	 * @param unknown $userID
	 * @return array
	 */
	public function getCustomerWinningList($aid,$userID){
		if (empty($aid)||empty($userID)){
			return array();
		}
		$data=array();
		$criteria = new CDbCriteria;
		$criteria->select = 'prize_name';
		$criteria->addInCondition('is_show', array(0,2));
		$criteria->addCondition("lottery_activity_id=:lottery_activity_id");
		$criteria->params[':lottery_activity_id']=$aid;
		$criteria->addCondition("customer_id=:customer_id");
		$criteria->params[':customer_id']=$userID;
		$criteria->order = 'create_time DESC' ;//排序条件
		$list = $this->findAll ( $criteria );
		if (!empty($list)){
			foreach ($list as $val){
				$data[]['prize_name']=$val->prize_name;
			}
		}
		return $data;
	}
	
	/**
	 * 执行查询
	 * @param array $params 报表需要使用的参数
	 * @param ReportFrameworkVisitor $reportVisitor 报表数据访问者
	 */
	public function ExecuteQuery($aid, $reportVisitor)
	{
		if(empty($aid))
		{
			return array();
		}
		/* if($this->type != 0)
		{
			return array();
		} */
	
		ini_set('memory_limit', '1024M');
		ini_set('max_execution_time', 0);
		set_time_limit(0);
	
		$sql = "SELECT la.activity_name AS '活动名称',lawm.prize_name AS '奖项名称',lawm.user_name AS '用户姓名',lawm.mobile AS '手机号',CASE WHEN lawm.`status`=0 THEN '未领奖' WHEN lawm.`status`=1 THEN '已领奖' END '领奖状态',FROM_UNIXTIME(lawm.create_time) AS '中奖时间' FROM lottery_activity_winning_member AS lawm LEFT JOIN lottery_activity AS la ON la.id=lawm.lottery_activity_id WHERE lawm.lottery_activity_id={$aid} AND lawm.is_show=0";
	
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
