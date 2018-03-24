<?php

/**
 * This is the model class for table "entrance_count_log".
 *
 * The followings are the available columns in table 'entrance_count_log':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $category_id
 * @property integer $community_id
 * @property integer $operation_time
 * @property integer $create_time
 */
class EntranceCountLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'entrance_count_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('customer_id, category_id, community_id', 'required'),
				array('customer_id, category_id, community_id, operation_time, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
				array('id, customer_id, category_id, community_id, operation_time, create_time', 'safe', 'on'=>'search'),
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
				'id' => 'ID',
				'customer_id' => 'Customer',
				'category_id' => 'Category',
				'community_id' => 'Community',
				'operation_time' => 'Operation Time',
				'create_time' => 'Create Time',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('operation_time',$this->operation_time);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EntranceCountLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 内部触发操作
	 * @param string $customer_id
	 * @param string $cid
	 * @param string $operation_time
	 * @param string $category_id
	 * @param string $community_id
	 */
	public function writeOperateLog($customer_id='' , $cid='' , $operation_time='', $category_id='' , $community_id=''){
		try{
			if(empty($community_id)){
				$Customer = Customer::model()->findByPk($customer_id);
				$community_id = $Customer->community_id;
			}
			if(empty($category_id) && !empty($cid)){
				switch($cid){
					case 9: $category_id = 11; break;    //E绿化
					case 35: $category_id = 32; break;   //E停车
					case 116: $category_id = 34; break;  //充电桩
					case 103: $category_id = 4; break;  //E租房
					case 112: $category_id = 8; break;  //E装修
					case 123: $category_id = 10; break;    //E入伙
					case 119: $category_id = 20; break;    //太平洋保险
					case 124: $category_id = 24; break;    //手机充值
					case 107: $category_id = 26; break;    //环球精选
					case 121: $category_id = 27; break;    //深航机票
					case 106: $category_id = 28; break;    //良食网
					case 110: $category_id = 29; break;    //千禧珠宝
					case 101: $category_id = 30; break;    //花礼网
					default:$category_id = 31; break;      //默认活动
				}
			}
			$model = new EntranceCountLog();
			$model->customer_id = $customer_id;
			$model->community_id = $community_id;
			$model->category_id = $category_id;
			$model->operation_time = $operation_time;
			$model->create_time = time();
			$model->save();
		}catch (Exception $e) {
			Yii::log('记录日志'.'用户id'.$customer_id.'操作类别'.$category_id.'操作时间'.$operation_time , CLogger::LEVEL_INFO, 'colourlife.logFile.writeOperateLog.failLog.message');
		}

	}


	public function writeMysql($pid,$level){
		$result = array();
		try {
			$result = ICEService::getInstance()->dispatch(
					'orgs',
					array(
							'pid' => $pid,
							'isall' => 0
					),
					array(),
					'get'
			);
		} catch (Exception $e) {
			Yii::log(
					sprintf(
							'获取 pid %s[%s] 下级信息失败',
							$pid,
							$e->getMessage(), $e->getCode()
					),
					CLogger::LEVEL_ERROR,
					'colourlife.logFile.entrance-get-community'
			);
		}
		if($result){
			foreach($result as $key=>$value){
				$region_name = $value['name'];
				$create_time = date('Y-m-d H:i:s', time());
				$insert_sql = "insert into `entrance_orgs` (`region_uuid`, `region_name`, `org_type`, `level`, `dn`, `parent_id`, `create_time`)   VALUES ";
				$insert_sql .= " ('" . $value['uuid'] . " ' , '" . $region_name . "' , '" . $value['orgType'] . "' , '" . $level . "' , '" . $value['dn'] . "' , '" . $pid . "' , '" . $create_time . "');";
				$result = Yii::app()->db->createCommand($insert_sql)->execute();
				if($level==2){
					$this->writeMysql($value['uuid'],3);
				}
			}
		}
	}

	//通过任意架构uuid（包括事业部uuid）直接获取下属所有小区列表
	public function subs($uuid){

		$result = array();
		try {
			$result = ICEService::getInstance()->dispatch(
					'community/subs',
					array(
							'pid' => $uuid
					),
					array(),
					'get'
			);
		} catch (Exception $e) {
			Yii::log(
					sprintf(
							'获取 $uuid %s[%s] 下级信息失败',
							$uuid,
							$e->getMessage(), $e->getCode()
					),
					CLogger::LEVEL_ERROR,
					'colourlife.logFile.entrance-get-community-subs'
			);
		}
		if($result){
			$result = explode(',', $result);
			foreach ($result as $row){
				$community = array();
				try {
					$community = ICEService::getInstance()->dispatch(
							'community',
							array(
									'id' => trim($row,"'")
							),
							array(),
							'get'
					);
				} catch (Exception $e) {
					Yii::log(
							sprintf(
									'获取 $row %s[%s] 信息失败',
									$row,
									$e->getMessage(), $e->getCode()
							),
							CLogger::LEVEL_ERROR,
							'colourlife.logFile.entrance-get-community-info'
					);
				}
				if($community && $community['status']!='撤场' && $community['status']!='未入伙' && $community['status']!='前期'){
					try {
						$community_info = ICEService::getInstance()->dispatch(
								'org',
								array(
										'uuid' => trim($row,"'")
								),
								array(),
								'get'
						);
					} catch (Exception $e) {
						Yii::log(
								sprintf(
										'获取 $row %s[%s] 信息失败',
										$row,
										$e->getMessage(), $e->getCode()
								),
								CLogger::LEVEL_ERROR,
								'colourlife.logFile.entrance-get-community-info'
						);
					}
					if($community_info){

						$region_name = $community_info['name'];
						$create_time = date('Y-m-d H:i:s', time());
						$insert_sql = "insert into `entrance_orgs` (`region_uuid`, `region_name`, `org_type`, `level`, `dn`, `parent_id`, `create_time`)   VALUES ";
						$insert_sql .= " ('" . $community_info['uuid'] . " ' , '" . $region_name . "' , '" . $community_info['orgType'] . "' , '4' , '" . $community_info['dn'] . "' , '" . $uuid . "' , '" . $create_time . "');";
						$result = Yii::app()->db->createCommand($insert_sql)->execute();

					}
				}
			}
		}
	}

	//获取小区户数
	public function getCommunityHousehold($community_uuid){

		$response_arr = array(
				'commercial_number'=>0,
				'civilian_number'=>0,
				'always_number'=>0,
		);
		$response_arr_redis = Yii::app()->rediscache->get('cmobile:cache:entrance:communityhousehold:' . $community_uuid);
		//判断缓存中是否存在该小区信息，无则请求ice接口
		if(empty($response_arr_redis)){
			$result = array();
			try {
				$result = ICEService::getInstance()->dispatch(
						'community',
						array(
								'id' => $community_uuid,
						),
						array(),
						'get'
				);
			} catch (Exception $e) {
				Yii::log(
						sprintf(
								'获取 id %s[%s] 小区信息失败',
								$community_uuid,
								$e->getMessage(), $e->getCode()
						),
						CLogger::LEVEL_ERROR,
						'colourlife.logFile.entrance-get-community-household'
				);
			}
			if($result){
				$always_num = $result['stay_house_num']+$result['stay_other_num']+$result['stay_store_num'];
				$response_arr['civilian_number'] = $result['vdef5'];
				$response_arr['commercial_number'] = $result['vdef7'];
				$response_arr['always_number'] = $always_num;
				//写入缓存
				Yii::app()->rediscache->set('cmobile:cache:entrance:communityhousehold:' . $community_uuid, json_encode($response_arr), 86400);
			}

		}else{
			$response_arr = json_decode($response_arr_redis, true);
		}

		return $response_arr;
	}


	//獲取小區數量
	private function getTotalNumber($pid ){
		$total_num = 1 ;
		$result = array();
		try {
			$result = ICEService::getInstance()->dispatch(
					'community/search',
					array(
							'pid' => $pid,
							'size' => 1,
							'page' => 1
					),
					array(),
					'get'
			);
		} catch (Exception $e) {
			Yii::log(
					sprintf(
							'获取 pid %s[%s] 下级信息失败',
							$pid,
							$e->getMessage(), $e->getCode()
					),
					CLogger::LEVEL_ERROR,
					'colourlife.logFile.entrance-get-community'
			);
		}
		if(!empty($result)){
			$total_num = $result['totalNum'];
		}
		return $total_num;

	}

	//獲取名稱
	public function getRegionName($dn,$level){
		if(empty($dn)){ return '';}
		$arr = explode(",", $dn);
		$org = array();
		foreach($arr as $v){
			$v = substr(strrchr($v, "="), 1);
			$org[]=$v;
		}
		$org = array_reverse(array_slice($org , 0 , count($org)-4 ));
		$result = array_pad($org, 6, '');
		return $result[$level];
	}


	/**
	 * 获取后台管理首页数据
	 */
	public function getIndexData($pid , $category_id, $date_type=''){

		$response_arr_redis = Yii::app()->rediscache->get('backend:cache:entrance:indexdata:' . $pid . $category_id .$date_type);
		if($response_arr_redis){
			$redis_data = json_decode($response_arr_redis, true);
			if($redis_data['date'] == date('Ymd',time())){
				return $redis_data['region_info'];
			}
		}

		$category = EntranceCategory::model()->findByPk($category_id);
		if(empty($category)){ return array();}
		$table_mame = $category['table_name'];

		$region_info = EntranceOrgs::model()->findAllByAttributes(array('parent_id'=>$pid));
		if(empty($region_info)){
			$region_info = EntranceOrgs::model()->findAllByAttributes(array('region_uuid'=>$pid));
		}
		foreach ($region_info as $k => $row){
			$region_info[$k] = $row->getAttributes();
		}

		if(!empty($region_info)){
			foreach($region_info as $key=>&$value){
				$total_active_number = 0;
				$day = 1;

				//当月数据
				if($date_type == 'month'){
					$last_time = date('Y-m-01', strtotime(date("Y-m-d")));
					$end_time = date("Y-m-d",strtotime("-1 day"));
					$value['data_time'] = date('Y-m', strtotime(date("Y-m")));
					$day = date('d',strtotime("-1 day"));
				}
				//上个月
				elseif($date_type == 'last_month'){
					$last_time = date('Y-m-01', strtotime("-1 month"));
					$end_time = date('Y-m-d', strtotime("$last_time +1 month -1 day"));
					$value['data_time'] = date('Y-m', strtotime("-1 month"));
					$day = date('d', strtotime("$last_time +1 month -1 day"));
				}
				//年度数据
				elseif($date_type == 'year'){
					$last_time = date('Y-01-01', strtotime(date("Y-m-d")));
					$end_time = date("Y-m-d",strtotime("-1 day"));
					$value['data_time'] = date('Y', strtotime(date("Y")));
					$day = date("z");
				}
				//昨日数据
				else{
					$last_time = $end_time = date("Y-m-d",strtotime("-1 day"));
					$value['data_time'] = date("Y-m-d",strtotime("-1 day"));
				}


				//计算活跃度、使用人次，剔除不需要考核的小区，剔除指定日期不需要考核的小区
				$sql = "SELECT t1.date_time,t1.commercial_number,t1.civilian_number,t3.always_num,active_number ";
				$sql .= "FROM {$table_mame} AS t1 LEFT JOIN `entrance_orgs` AS t2 ON t1.community_uuid=t2.region_uuid ";
				$sql .= "LEFT JOIN `entrance_community_count` AS t3 ON t1.community_uuid=t3.community_uuid ";
				$sql .= "WHERE UNIX_TIMESTAMP(t1.date_time)>=UNIX_TIMESTAMP('{$last_time}') AND UNIX_TIMESTAMP(t1.date_time)<=UNIX_TIMESTAMP('{$end_time}') AND (area_uuid='{$value['region_uuid']}' OR t1.branch_uuid='{$value['region_uuid']}' OR t1.region_uuid='{$value['region_uuid']}' OR t1.community_uuid='{$value['region_uuid']}') AND t2.is_check=1 AND t3.is_check=1 AND t1.date_time=t3.date_time ";
				$sql .= "ORDER BY t1.date_time";


				$result = Yii::app()->db->createCommand($sql)->queryAll();

				$t = [];
				$c = [];
				$tc = [];
				foreach ($result as $k => $row){
					$c[$row['date_time']] = empty($c[$row['date_time']]) ? $row['active_number'] : $c[$row['date_time']]+$row['active_number'];

					//小区项目日活跃度=使用人次/（常住户数*2*折算比例）
					//事业部层面日活跃度=Σ各项目使用人次/Σ（各项目常住户数*2*折算比例）
					//小区项目月活跃度=Σ小区项目日活跃度/当月考核天数    当月考核天数=自然月天数-当月剔除的天数
					//事业部月活跃度=Σ事业部日活跃度/自然月天数
					//折算比例=(住宅户数+商业户数/15)/（住宅户数+商业户数）*100%
					$commercial_number = $row['commercial_number']==0 ? 0 : $row['commercial_number']/15;
					$rate = ($row['civilian_number']+$commercial_number)>0 ? ($row['civilian_number']+$commercial_number)/($row['commercial_number']+$row['civilian_number'])*1 : 0;
					$always_num = $row['always_num'];
					$t[$row['date_time']] = empty($t[$row['date_time']]) ? ($always_num*2*$rate) : $t[$row['date_time']]+($always_num*2*$rate);

					$tc[$row['date_time']]['total_active_number'] = $c[$row['date_time']];
					$tc[$row['date_time']]['rate'] = $t[$row['date_time']];
				}

				$active_rate = 0;
				if($tc){
					foreach ($tc as $v){
						$total_active_number += $v['total_active_number'];
						if($v['total_active_number']==0 || $v['rate']==0){
							$active_rate += 0;
						}else{
							$active_rate += ($v['total_active_number']/$v['rate']);
						}
					}

				}

				$tc_day_num = 0;
				if($value['org_type']=='小区'){
					$day_sql = "SELECT COUNT(*) AS day_num FROM `entrance_community_count` WHERE (date_time)>=('{$last_time}') AND (date_time)<=('{$end_time}') AND community_uuid='{$value['region_uuid']}' AND is_check=0";
					$tc_day_num = Yii::app()->db->createCommand($day_sql)->queryScalar();
				}

				if($date_type=='month'){
					$days = date('d',time())-1;
					if($tc_day_num)
						$days = $days - $tc_day_num;
					$value['active_rate'] = $active_rate>0 ? $active_rate/$days : 0;
				}
				elseif($date_type=='last_month'){
					$days = date('d', strtotime("$last_time +1 month -1 day"));
					if($tc_day_num)
						$days = $days - $tc_day_num;
					$value['active_rate'] = $active_rate>0 ? $active_rate/$days : 0;
				}elseif($date_type=='year'){
					$days = 365;
					if($tc_day_num)
						$days = 365 - $tc_day_num;
					$value['active_rate'] = $active_rate>0 ? $active_rate/$days : 0;
				}else{
					$value['active_rate'] = $active_rate;
				}

				$value['active_rate'] = round($value['active_rate']*100,2).'%';
				$value['total_active_number'] = round($total_active_number/$day);

				$sum_sql = "SELECT SUM(t1.always_num) as total_always_num FROM `entrance_community_count` AS t1 LEFT JOIN `entrance_orgs` AS t2 ON t1.community_uuid=t2.region_uuid WHERE (date_time)>=('{$last_time}') AND (date_time)<=('{$end_time}') AND (t1.branch_uuid='{$value['region_uuid']}' OR t1.region_uuid='{$value['region_uuid']}' OR t1.community_uuid='{$value['region_uuid']}') AND t1.is_check=1 AND t2.is_check=1 ";
				$total_always_num = Yii::app()->db->createCommand($sum_sql)->queryScalar();
				$value['always_num'] = $total_always_num ? round($total_always_num/$day) : 0;

				//小区户数
				$query = "SELECT COUNT(*) as total_num FROM `entrance_community_count` WHERE UNIX_TIMESTAMP(date_time)=UNIX_TIMESTAMP('{$last_time}') AND (branch_uuid='{$value['region_uuid']}' OR region_uuid='{$value['region_uuid']}' OR community_uuid='{$value['region_uuid']}') ";
				$total_num = Yii::app()->db->createCommand($query)->queryScalar();
				$value['total_num'] = $total_num;
			}
		}

		$data = array('region_info' => $region_info, 'date' => date('Ymd',time()));

		//写入缓存
		Yii::app()->rediscache->set('backend:cache:entrance:indexdata:' . $pid . $category_id .$date_type, json_encode($data), 86400);
		return $region_info;
	}

	//获取周/月折线图/柱状图数据
	public function getLine($category_id , $pid ,$type ,$percent=0){
		if(!($type == 'week' || $type == 'month')){ return false;}

		$category = EntranceCategory::model()->findByPk($category_id);
		if(empty($category)){ return array();}
		$table_mame = $category['table_name'];
		$data = array();
		//获取7天前日期数组
		if($type == 'week'){
			$last_time = date("Y-m-d", strtotime("-1 week"));
			for($i = 7 ; $i>0 ; $i--){
				$date_time = date("Y-m-d",time()-86400*$i);
				$data[$date_time] = 0;
			}
		}

		//获取当月1号起的30天的数组
		if($type == 'month'){
			$last_time = date('Y-m-01', strtotime(date("Y-m-d")));
			for($i = 0 ; $i<31 ; $i++){
				$date_time = date("Y-m-d",strtotime($last_time)+86400*$i);
				$data[$date_time] = 0;
			}
		}

		//计算活跃度、使用人次，剔除不需要考核的小区，剔除指定日期不需要考核的小区
		$sql = "SELECT t1.date_time,t1.commercial_number,t1.civilian_number,t3.always_num,active_number ";
		$sql .= "FROM {$table_mame} AS t1 LEFT JOIN `entrance_orgs` AS t2 ON t1.community_uuid=t2.region_uuid ";
		$sql .= "LEFT JOIN `entrance_community_count` AS t3 ON t1.community_uuid=t3.community_uuid ";
		$sql .= "WHERE UNIX_TIMESTAMP(t1.date_time)>=UNIX_TIMESTAMP('{$last_time}') AND (area_uuid='{$pid}' OR t1.branch_uuid='{$pid}' OR t1.region_uuid='{$pid}' OR t1.community_uuid='{$pid}') AND t2.is_check=1 AND t3.is_check=1 AND t1.date_time=t3.date_time ";
		$sql .= "ORDER BY t1.date_time";

		$result = Yii::app()->db->createCommand($sql)->queryAll();

		$t = [];
		$tc = [];
		foreach ($result as $k => $row){
			$data[$row['date_time']] = empty($data[$row['date_time']]) ? $row['active_number'] : $data[$row['date_time']]+$row['active_number'];

			//小区项目日活跃度=使用人次/（常住户数*2*折算比例）
			//事业部层面日活跃度=Σ各项目使用人次/Σ（各项目常住户数*2*折算比例）
			//小区项目月活跃度=Σ小区项目日活跃度/当月考核天数    当月考核天数=自然月天数-当月剔除的天数
			//事业部月活跃度=Σ事业部日活跃度/自然月天数
			//折算比例=(住宅户数+商业户数/15)/（住宅户数+商业户数）*100%
			$commercial_number = $row['commercial_number']==0 ? 0 : $row['commercial_number']/15;
			$rate = ($row['civilian_number']+$commercial_number)>0 ? ($row['civilian_number']+$commercial_number)/($row['commercial_number']+$row['civilian_number'])*1 : 0;
			$always_num = $row['always_num'];
			$t[$row['date_time']] = empty($t[$row['date_time']]) ? ($always_num*2*$rate) : $t[$row['date_time']]+($always_num*2*$rate);

			$tc[$row['date_time']]['total_active_number'] = $data[$row['date_time']];
			$tc[$row['date_time']]['rate'] = $t[$row['date_time']];
		}

		if($percent == 1){
			if($tc){
				foreach ($tc as $k => $v){
					if($v['total_active_number']==0 || $v['rate']==0){
						$data[$k] = 0;
					}else{
						$data[$k] = round(($v['total_active_number']/$v['rate'])*100,2);
					}
				}

			}
		}


		//数组类型转换为二维数组
		$data_arr = array();
		foreach($data as $key1=>$value1){
			$data_arr[] = array('data'=>$key1 , 'number'=>$value1);
		}

		//月份日期改为连续自增数字
		if($type == 'month'){
			foreach($data_arr as $key2=>&$value2){
				$value2['data'] = $key2+1;
			}
		}

		return $data_arr;
	}

	/*
	 * 导出事业部数据
	 */
	public function exportRegion($category_id){
		$pid = '760d5ff3-136f-445f-b9df-f01d0943a9e0';

		$region_all = EntranceOrgs::model()->findAllByAttributes(array('parent_id'=>$pid));
		foreach ($region_all as $k => $row){
			$region_all[$k] = $row->getAttributes();
		}

		$region =  array();
		foreach($region_all as $key=>$value){
			$region[] = EntranceCountLog::model()->getIndexData($value['region_uuid'], $category_id);
		}
		return $this->rebuild_arrays($region);
	}

	public function rebuild_arrays($arr){
		static $tmp=array();
		foreach($arr as $key=>$val){
			foreach($val as $k=>$v){
				$tmp[] = $v;
			}
		}
		return $tmp;
	}


	/**
	 * 导出小区
	 * @param $pid
	 * @param $category_id
	 * @param string $date_type
	 * @return array
	 */
	public function exportCommunity($pid , $category_id, $date_type=''){


		$response_arr_redis = Yii::app()->rediscache->get('backend:cache:entrance:export:community:' . $pid . $category_id .$date_type);
		if($response_arr_redis){
			$redis_data = json_decode($response_arr_redis, true);
			if($redis_data['date'] == date('Ymd',time())){
				return $redis_data;
			}
		}

		$category = EntranceCategory::model()->findByPk($category_id);
		$table_mame = $category['table_name'];

		if($pid){
			//获取该地区下的所有小区
			$community_list = array();
			$org_info = EntranceOrgs::model()->findByAttributes(array('region_uuid'=>$pid));
			if($org_info){
				if($org_info->level == 2){
					$shiyebu_list = EntranceOrgs::model()->findAllByAttributes(array('parent_id'=>$org_info->region_uuid));
					foreach ($shiyebu_list as $row){
						$xiaoqu_list = EntranceOrgs::model()->findAllByAttributes(array('parent_id'=>$row->region_uuid));
						$community_list = array_merge($community_list, $xiaoqu_list);
					}
					$name = $org_info['region_name'].'下所有小区项目'.$category['name'].'数据EXCEL导出';
				}elseif($org_info->level == 3){
					$xiaoqu_list = EntranceOrgs::model()->findAllByAttributes(array('parent_id'=>$org_info->region_uuid));
					$community_list = array_merge($community_list, $xiaoqu_list);
					$name = $org_info['region_name'].'下所有小区项目'.$category['name'].'数据EXCEL导出';
				}else{
					$community_list[] = $org_info;
					$name = $org_info['region_name'].'项目'.$category['name'].'数据EXCEL导出';
				}


			}else{
				echo '没有小区'; exit;
			}

			foreach ($community_list as $k => $row){
				$community_list[$k] = $row->getAttributes();
			}
		}else{
			//所有小区
			$sql = "SELECT * FROM `entrance_orgs` WHERE org_type='小区'";
			$community_list = Yii::app()->db->createCommand($sql)->queryAll();
			$name = "所有小区项目数据EXCEL导出";
		}

		foreach ($community_list as $key=>&$value){

			$total_active_number = 0;

			//当月数据
			if($date_type == 'month'){
				$last_time = date('Y-m-01', strtotime(date("Y-m-d")));
				$end_time = date("Y-m-d",strtotime("-1 day"));
				$value['data_time'] = date('Y-m', strtotime(date("Y-m")));
			}
			//上个月
			elseif($date_type == 'last_month'){
				$last_time = date('Y-m-01', strtotime("-1 month"));
				$end_time = date('Y-m-d', strtotime("$last_time +1 month -1 day"));
				$value['data_time'] = date('Y-m', strtotime("-1 month"));
			}
			//年度数据
			elseif($date_type == 'year'){
				$last_time = date('Y-01-01', strtotime(date("Y-m-d")));
				$end_time = date("Y-m-d",strtotime("-1 day"));
				$value['data_time'] = date('Y', strtotime(date("Y")));
			}
			//昨日数据
			else{
				$last_time = $end_time = date("Y-m-d",strtotime("-1 day"));
				$value['data_time'] = date("Y-m-d",strtotime("-1 day"));
			}
			//判断是否进行数据汇总计算考核，否：该项目的常住户数和使用人次和日活跃度显示为0
			if($value['is_check']){

				$sql = "SELECT t1.date_time,t1.commercial_number,t1.civilian_number,always_num,active_number ";
				$sql .= "FROM {$table_mame} AS t1 LEFT JOIN `entrance_community_count` AS t2 ON t1.community_uuid=t2.community_uuid ";
				$sql .= "WHERE  UNIX_TIMESTAMP(t1.date_time)>=UNIX_TIMESTAMP('{$last_time}') AND UNIX_TIMESTAMP(t1.date_time)<=UNIX_TIMESTAMP('{$end_time}') AND t1.community_uuid='{$value['region_uuid']}' AND t2.is_check=1 AND t1.date_time=t2.date_time ";
				$sql .= "ORDER BY t1.date_time";

				$result = Yii::app()->db->createCommand($sql)->queryAll();

				$t = [];
				$c = [];
				$tc = [];
				$total = 0;
				if($result){
					$total = count($result);
					foreach ($result as $k => $row) {
						$c[$row['date_time']] = empty($c[$row['date_time']]) ? $row['active_number'] : $c[$row['date_time']] + $row['active_number'];

						//小区项目日活跃度=使用人次/（常住户数*2*折算比例）
						//小区项目月活跃度=Σ小区项目日活跃度/当月考核天数    当月考核天数=自然月天数-当月剔除的天数
						//折算比例=(住宅户数+商业户数/15)/（住宅户数+商业户数）*100%
						$commercial_number = $row['commercial_number'] == 0 ? 0 : $row['commercial_number'] / 15;
						$rate = ($row['civilian_number'] + $commercial_number) > 0 ? ($row['civilian_number'] + $commercial_number) / ($row['commercial_number'] + $row['civilian_number']) * 1 : 0;
						$always_num = $row['always_num'];
						$t[$row['date_time']] = empty($t[$row['date_time']]) ? ($always_num * 2 * $rate) : $t[$row['date_time']] + ($always_num * 2 * $rate);

						$tc[$row['date_time']]['total_active_number'] = $c[$row['date_time']];
						$tc[$row['date_time']]['rate'] = $t[$row['date_time']];

					}
				}


				$active_rate = 0;
				if($tc){
					foreach ($tc as $v){
						$total_active_number += $v['total_active_number'];
						if($v['total_active_number']==0 || $v['rate']==0){
							$active_rate += 0;
						}else{
							$active_rate += ($v['total_active_number']/$v['rate']);
						}
					}

				}

				$day_sql = "SELECT COUNT(*) AS day_num FROM `entrance_community_count` WHERE UNIX_TIMESTAMP(date_time)>=UNIX_TIMESTAMP('{$last_time}') AND UNIX_TIMESTAMP(date_time)<=UNIX_TIMESTAMP('{$end_time}') AND community_uuid='{$value['region_uuid']}' AND is_check=0";
				$tc_day_num = Yii::app()->db->createCommand($day_sql)->queryScalar();

				if($date_type=='month'){
					$days = date('d',time())-1;
					if($tc_day_num)
						$days = $days - $tc_day_num;
					$value['active_rate'] = $active_rate>0 ? $active_rate/$days : 0;
				}
				elseif($date_type=='last_month'){
					$days = date('d', strtotime("$last_time +1 month -1 day"));
					if($tc_day_num)
						$days = $days - $tc_day_num;
					$value['active_rate'] = $active_rate>0 ? $active_rate/$days : 0;
				}elseif($date_type=='year'){
					$days = 365;
					if($tc_day_num)
						$days = 365 - $tc_day_num;
					$value['active_rate'] = $active_rate>0 ? $active_rate/$days : 0;
				}else{
					$days = 1;
					$value['active_rate'] = $active_rate;
				}

				$value['active_rate'] = round($value['active_rate']*100,2).'%';
				$value['total_active_number'] = $total ? round($total_active_number/$total) : 0;

				$sum_sql = "SELECT SUM(t1.always_num) as total_always_num,SUM(t1.civilian_number) as total_civilian_number,SUM(t1.commercial_number) as total_commercial_number FROM `entrance_community_count` AS t1 LEFT JOIN `entrance_orgs` AS t2 ON t1.community_uuid=t2.region_uuid WHERE UNIX_TIMESTAMP(date_time)>=UNIX_TIMESTAMP('{$last_time}') AND UNIX_TIMESTAMP(date_time)<=UNIX_TIMESTAMP('{$end_time}') AND t1.community_uuid='{$value['region_uuid']}' AND t1.is_check=1 AND t2.is_check=1 ";
				$res = Yii::app()->db->createCommand($sum_sql)->queryAll();

				$value['always_num'] = $res[0]['total_always_num'] ? round($res[0]['total_always_num']/$days) : 0;
				$value['civilian_number'] = $res[0]['total_civilian_number'] ? round($res[0]['total_civilian_number']/$days) : 0;
				$value['commercial_number'] = $res[0]['total_commercial_number'] ? round($res[0]['total_commercial_number']/$days) : 0;

			}else{
				$value['civilian_number'] = 0;
				$value['commercial_number'] = 0;
				$value['always_num'] = 0;
				$value['active_rate'] = '0%';
				$value['total_active_number'] = '0';
			}

		}

		$data = array('community_list' => $community_list, 'date' => date('Ymd',time()), 'name' => $name);

		//写入缓存
		Yii::app()->rediscache->set('backend:cache:entrance:export:community:' . $pid . $category_id .$date_type, json_encode($data), 86400);
		return $data;

	}


}
