<?php

/**
 * This is the model class for table "lottery_activity".
 *
 * The followings are the available columns in table 'lottery_activity':
 * @property integer $id
 * @property string $account
 * @property string $department
 * @property string $activity_name
 * @property string $start_time
 * @property string $end_time
 * @property integer $state
 * @property integer $create_time
 * @property integer $update_time
 */
class LotteryActivity extends CActiveRecord
{
	public $modelName = '抽奖活动';
	private $_chance=15;
	//是否启用
	public $_state = array (
				'' => '全部',
				'0' => '启用',
				'1' => '禁用'
			);
	//中奖概率
	public $prizeArr = array (
					'0' => array (
							'prize_name' => '没有抽中奖项',
							'v' => 40
						),
					'1' => array (
							'prize_name' => '1饭票',
							'price' => 1,
							'v' => 15
						),
					'2' => array (
							'prize_name' => '2饭票',
							'price' => 2,
							'v' => 15
						),
					'3' => array (
							'prize_name' => '5饭票',
							'price' => 5,
							'v' => 15
						),
			);
	/**
	 * 饭票 '金额'=>'总份数'
	 * @var unknown
	 */
	 public $tickets = array (
				'1' => 3000,
				'2' => 1000,
				'5' => 600
			);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lottery_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_time, end_time', 'required'),
			array('state, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('account, activity_name', 'length', 'max'=>64),
			array('department', 'length', 'max'=>125),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, account, department, activity_name, start_time, end_time, state, create_time, update_time', 'safe', 'on'=>'search'),
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
			'account' => '活动账号',
			'department' => '部门',
			'activity_name' => '活动名称',
			'start_time' => '开始时间',
			'end_time' => '结束时间',
			'state' => '是否启用',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'start_time_ymd' => '开始时间年月日',
			'start_time_his' => '开始时间时分秒',
			'end_time_ymd' => '结束时间年月日',
			'end_time_his' => '结束时间时分秒',
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

		if (!empty(Yii::app()->user->username)){
			$this->account=Yii::app()->user->username;
		}
		$criteria->compare('id',$this->id);
		$criteria->compare('account',$this->account,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('activity_name',$this->activity_name,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('state',$this->state);
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
	 * @return LotteryActivity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
    public function afterDelete()
    {
        //删除活动下的奖项
        LotteryActivityPrize::model()->deleteAllByAttributes(array('lottery_activity_id' => $this->id));
        return parent::afterDelete();
    }
	/**
	 * 是否启用
	 * @param string $state
	 * @return string
	 */
	public function getStateName($state = '')
	{
		$return = '';
		switch ($state) {
			case '':
				$return = "";
				break;
			case 1:
				$return = '<span class="label label-error">已'.$this->_state[1].'</span>';
				break;
			case 0:
				$return = '<span class="label label-success">已'.$this->_state[0].'</span>';
				break;
		}
		return $return;
	}
	
	/*
	 * @version 启用功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=0;
		$model->update_time=time();
		$model->save();
	}
	/*
	 * @version 禁用功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=1;
		$model->update_time=time();
		$model->save();
	}
	
	public function getEnd_time_ymd(){
		if($this->end_time){
			return date("Y-m-d",strtotime($this->end_time));
		}else{
			return date("Y-m-d",time());
		}
	}
	public function getEnd_time_his(){
		return date("h:i A",strtotime($this->end_time));
	}
	
	public function getStart_time_ymd(){
		if($this->start_time){
			return date("Y-m-d",  strtotime($this->start_time));
		}else{
			return date("Y-m-d",time());
		}
	}
	public function getStart_time_his(){
		return date("h:i A",  strtotime($this->start_time));
	}
	
	/**
	 * 抽奖过程中,等奖只能中一个，饭票可以多个
	 */
	public function draw($prize,$customer){
		if (empty($prize)||empty($customer)){
			return array('status'=>0,'msg'=>'抽奖失败！');
		}
		//把等奖加进抽奖奖项的列表
		$this->prizeArr['4']=array (
					'prize_name' => $prize->prize_name,
					'v' => $this->_chance 
			);
		//转换数组格式
		foreach ($this->prizeArr as $key=>$val) {
			$arr[$key] = $val['v'];
		}
		$isReset = false;
		$isShow = 0;
		//抽奖
		$rid = $this->get_rand ( $arr );
		$prize_name = $this->prizeArr [$rid] ['prize_name'];
		//判断是否为饭票
		if ($rid==1||$rid==2||$rid==3){  //饭票，限制总数
			$price=$this->prizeArr[$rid]['price'];
			//获取总场数
			$total_games=LotteryActivityGames::model()->getTotalGames();
			if ($total_games>0){
				$ticket_per_game=$this->tickets[$price]/$total_games;  //每场的总份数
			}else {
				$ticket_per_game=$this->tickets[$price];
			}
			
			$ticket_count=LotteryActivityWinningMember::model ()->count ( "lottery_activity_id=:lottery_activity_id and prize_name=:prize_name", array (
					':lottery_activity_id' => $prize->lottery_activity_id,
					':prize_name' => $prize_name 
			) );
			if ($ticket_count >= $ticket_per_game) { // 判断是否已超出限制的份数
				$isReset=true;
				$rid = 0;
			}
			//扣款账户
			$cmobile=20000000005;
			//判断扣款账户余额不足不发
			$redPacketCustomer=Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$cmobile));
			if (empty($customer) || $redPacketCustomer->getBalance()<$price){
				$isReset=true;
				$rid = 0;
			}
		}elseif ($rid==4) { //等奖的只能中一次
			$prize_count=LotteryActivityWinningMember::model ()->count ( "lottery_activity_id=:lottery_activity_id and prize_name=:prize_name", array (
					':lottery_activity_id' => $prize->lottery_activity_id,
					':prize_name' => $prize_name
			) );
			if ($prize_count >= $prize->total_num) { // 判断是否已超出限制的份数
				$isReset=true;
				$rid = 0;
			}else {
				//判断是否还有中过其他等奖奖项
				$activity_prize = LotteryActivityPrize::model ()->findAll ( "lottery_activity_id=:lottery_activity_id", array (
						':lottery_activity_id' => $prize->lottery_activity_id
				) );
				if (!empty($activity_prize)){
					foreach ($activity_prize as $val){
						$prize_name_arr[]="'".$val->prize_name."'";
					}
					$prize_name_str=implode(",", $prize_name_arr);
					$isWinning=LotteryActivityWinningMember::model ()->findAll ( "customer_id={$customer->id} and lottery_activity_id={$prize->lottery_activity_id} and prize_name in ({$prize_name_str})");
					if (count($isWinning)>0){
						$isReset=true;
						$rid = 0;
					}
				}
			}
			
		}
		//重新获取奖项名
		if ($isReset){
			$prize_name=$this->prizeArr[$rid]['prize_name'];
		}
		if ($rid==0){
			$isShow=1; //等于1则不中奖
		}elseif ($rid==1||$rid==2||$rid==3){
			$isShow=2; //等于2 饭票类型
		}
		//抽奖入库
		$winningModel=new LotteryActivityWinningMember();
		$winningModel->lottery_activity_id=$prize->lottery_activity_id;
		$winningModel->lottery_activity_prize_id=$prize->id;
		$winningModel->prize_name=$prize_name;
		$winningModel->customer_id=$customer->id;
		$winningModel->user_name=$customer->name;
		$winningModel->mobile=$customer->mobile;
		$winningModel->is_show=$isShow;
		$winningModel->create_time=time();
		$result=$winningModel->save();
		if ($result) {
			if ($rid==1||$rid==2||$rid==3){ //实时发饭票
				//判断扣款账户余额是否足够
				$redPacketCustomer=Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$cmobile));
				if (!empty($customer)&&$redPacketCustomer->getBalance()>=$this->prizeArr[$rid]['price']){
					$redPacketResult=RedPacketCarry::model()->customerTransferAccounts($redPacketCustomer->id,$customer->id,$this->prizeArr[$rid]['price'],1,$cmobile,$note='2016年中秋晚会活动');
					if ($redPacketResult['status']==1){
						Yii::log('2016年中秋晚会活动'.date('Y-m-d',time()).$cmobile.'账号转饭票给'.$customer->mobile.'成功！转账单号：'.$redPacketResult['msg'],CLogger::LEVEL_INFO,'colourlife.core.2016zhongqiuwanhui');
					}else {
						Yii::log('2016年中秋晚会活动'.date('Y-m-d',time()).$cmobile.'账号转饭票给'.$customer->mobile.'失败！错误信息为：'.$redPacketResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.2016zhongqiuwanhui');
					}
				}else {
					$rid = 0;
					$prize_name = $this->prizeArr[$rid]['prize_name'];
					$results = LotteryActivityWinningMember::model()->updateByPk($winningModel->attributes['id'],array('prize_name'=>$prize_name));
				}
			}
			$data = array(
				'status' => 1,
				'msg' => array(
					'rid' =>$rid,
					'prize_name' => $prize_name
				)
			);
		} else {
			$data = array(
				'status' => 0,
				'msg' => '抽奖失败'
			);
		}
		return $data;
	}
	

	/*
	 * @version 概率算法
	* @param array $proArr
	* return array
	*/
	private function get_rand($proArr){
		$result = '';
		//概率数组的总概率精度
		$proSum=0;
		foreach ($proArr as $v){
			$proSum+=$v;
		}
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			}else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
}