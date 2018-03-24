<?php

/**
 * This is the model class for table "lucky_cust_result".
 *
 * The followings are the available columns in table 'lucky_cust_result':
 * @property integer $id
 * @property string $cust_name
 * @property string $receive_name
 * @property integer $cust_id
 * @property string $address
 * @property string $moblie
 * @property integer $prize_id
 * @property string $lucky_date
 * @property integer $isget
 * @property string $get_date
 * @property string $comment
 * @property integer $deal_state
 * @property integer $deal_userid
 * @property string $deal_username
 * @property string $deal_date
 * @property string $express_company
 * @property string $express_number
 * @property string $deal_remark
 * @property string $deal_remark_log
 */
class LuckyCustResult extends CActiveRecord
{
	public $modelName = '中奖结果';
	
	public $branch_id;
	public $region;
	public $get_start_time;
	public $get_end_time;
	public $count;
	public $count1;
	public $count2;
	public $entityList = array(89,90,91,92,93,94,95,96,97,98,106,107,108,109,110,111,112,113,114,115,125,126,127,128,129,130,131,132,133,136,153,154,155,156,157.158,159,160);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_cust_result';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cust_id, address, moblie, lucky_date', 'required'),
			array('cust_id, prize_id, isget, deal_state, deal_userid,isgivered', 'numerical', 'integerOnly'=>true),
			array('cust_name, receive_name, deal_username', 'length', 'max'=>50),
			array('address, deal_remark', 'length', 'max'=>500),
			array('moblie', 'length', 'max'=>15),
			array('comment', 'length', 'max'=>1000),
			array('express_company, express_number', 'length', 'max'=>200),
			array('get_date, deal_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,lucky_act_id, branch_id,community_id,region,cust_name, receive_name, cust_id, address, moblie, prize_id, lucky_date, isget, get_date, comment, deal_state, deal_userid, deal_username, deal_date,isgivered,isred', 'safe', 'on'=>'search'),
			array('id,lucky_act_id, branch_id,community_id,region,cust_name, receive_name, cust_id, address, moblie, prize_id, lucky_date, isget, get_date,get_start_time,get_end_time, comment, deal_state, deal_userid, deal_username, deal_date', 'safe', 'on'=>'total_report_search'),
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
			'prize' => array(self::BELONGS_TO, 'LuckyPrize', 'prize_id'),
			'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
			'luckyAct' => array(self::BELONGS_TO, 'LuckyActivity', 'lucky_act_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'cust_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'community_id' => '所属小区',
			'cust_name' => '客户信息',
			'receive_name' => '收货人姓名',
			'cust_id' => '客户id',
			'address' => '收获地址',
			'moblie' => '手机号',
			'prize_id' => '获得奖品',
			'lucky_date' => '抽奖时间',
			'isget' => '是否收到货物',
			'isred' => '是否红包',
			'rednum' => '红包金额',
			'isgivered' => '红包是否发放',
			'get_date' => '收货时间',
			'comment' => '客户评论',
			'deal_state' => '处理状态', //(0:尚未处理,1:正在发送,2:已送达)
			'deal_userid' => '处理人id',
			'deal_username' => '处理人',
			'deal_date' => '处理时间',
			'express_company' => '快递公司',
			'express_number' => '快递单号',
			'deal_remark' => '备注说明',
			'deal_remark_log' => '备注日志',
			'branch_id' => '所属部门',
			'lucky_act_id' => '活动名称',
			'get_start_time'=>'中奖起始时间',
			'get_end_time'=>'中奖结束时间',
			'region' => '地区',
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
		
		//查询用户 
		$criteria = new CDbCriteria;
		$community_ids = array();
		
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$branchIds = $employee->mergeBranch;
		//判断小区权限
		if (!empty($employee->branch)) {
			foreach ($branchIds as $branchId) {
				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
				$community_ids = array_unique(array_merge($community_ids, $data));
			}
			$criteria->addInCondition('community_id', $community_ids);
		}
		if ($this->branch_id != '') {
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		
			$criteria->addInCondition('community_id', $community_ids);
		}  else if ($this->region != '') //如果有地区
        {
            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
            $criteria->addInCondition('community_id', $community_ids);
        }  
		$criteria->compare('id',$this->id);
		$criteria->compare('community_id', $this->community_id);
		$criteria->compare('lucky_act_id',$this->lucky_act_id);
		$criteria->compare('cust_name',$this->cust_name,true);
		$criteria->compare('receive_name',$this->receive_name,true);
		$criteria->compare('cust_id',$this->cust_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('moblie',$this->moblie,true);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('lucky_date',$this->lucky_date,true);
		$criteria->compare('isget',$this->isget);
		$criteria->compare('isgivered',$this->isgivered);
		$criteria->compare('isred',$this->isred);
		if($this->isgivered==="0" ||$this->isgivered==="1" ){
			$criteria->compare('isred',1);
		}
		$criteria->compare('get_date',$this->get_date,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('deal_state',$this->deal_state);
		$criteria->compare('deal_userid',$this->deal_userid);
		$criteria->compare('deal_username',$this->deal_username,true);
		$criteria->compare('deal_date',$this->deal_date,true);
		$criteria->compare('express_company',$this->express_company,true);
		$criteria->compare('express_number',$this->express_number,true);
		$criteria->compare('deal_remark',$this->deal_remark,true);
		$criteria->compare('deal_remark_log',$this->deal_remark_log,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
					'defaultOrder' => 'id DESC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyCustResult the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function report_search()
	{
	    $criteria = new CDbCriteria;
// 	    $criteria->select="prize_level_name";
// 	    $criteria->select="prize_name";
// 	    $criteria->select="cust_name";
// 	    $criteria->select="receive_name";
// 	    $criteria->select="address";
// 	    $criteria->select="moblie";
// 	    $criteria->select="lucky_date";
// 	    $criteria->select=array(
// 	        "prize_level_name",
// 	        "prize_name",
// 	        "cust_name",
// 	        "receive_name",
// 	        "receive_name",
// 	        "address",
// 	        "moblie",
// 	        "lucky_date"
// 	    );
// 	    $criteria->select="prize_level_name,prize_name,cust_name,receive_name,address,moblie,lucky_date";
	    
	    $params=array();
	    if (isset($_REQUEST['startDate']) ) {
	    	$startDate=$_REQUEST['startDate'];
	    	$criteria->condition="lucky_date>=:startDate";
	    	$params[":startDate"]=$startDate;
	    }
	    if(isset($_REQUEST['endDdate'])){
	    	$endDate=$_REQUEST['endDdate'];
	    	$criteria->condition="lucky_date<=:endDdate";
	    	$params[":endDdate"]=$endDate;
	    }
// 	    $criteria->join = 'inner join lucky_prize p on p.id=t.prize_id';
	    $criteria->params=$params;
	    
	    $criteria->limit=50;
	    
	    return new ActiveDataProvider($this, array(
	        'criteria' => $criteria,
	    ));
	}
	
	public function report_search_sql($luckyActId,$page,$startDate,$endDate,$isRed=false,$pageSize=200)
	{
	   $conn=Yii::app()->db;
// 	   $sql="select id,cust_name,receive_name,address,moblie,public_info,lucky_date 
// 	       from lucky_cust_result  
// 	       order by id asc 
// 	       limit ".($page-1)*$pageSize.",".$pageSize;
// 	   if(empty($startDate) || empty($endDate)){
// 		   	$sql= "SELECT l.id,cust_name,receive_name,address,moblie,prize_level_name,prize_name,lucky_date ,deal_state
// 	            FROM lucky_cust_result   l
// 	            INNER JOIN lucky_prize p ON (l.prize_id=p.id)
// 		       order by id asc
// 		       limit ".($page-1)*$pageSize.",".$pageSize;
// 	   }else{
// 		  $sql= "SELECT l.id,cust_name,receive_name,address,moblie,prize_level_name,prize_name,lucky_date ,deal_state
// 	            FROM lucky_cust_result   l
// 	            INNER JOIN lucky_prize p ON (l.prize_id=p.id)
// 		  		WHERE lucky_date>='".$startDate."' AND lucky_date<='".$endDate."'
// 		       order by id asc 
// 		       limit ".($page-1)*$pageSize.",".$pageSize;
// 	   }
	   $sql= "SELECT l.id,a.name as luckyAct,cust_name,receive_name,address,moblie,prize_level_name,prize_name,lucky_date ,deal_state
	            FROM lucky_cust_result   l
	            INNER JOIN lucky_prize p ON (l.prize_id=p.id)
				INNER JOIN lucky_activity a ON (l.lucky_act_id=a.id)
	   			where 1=1 ";
	   if($luckyActId){
	  	 	$sql.=" and l.lucky_act_id=$luckyActId";
	   }
	   if($isRed!==false){
	   		$sql.=" and l.isred=$isRed";
	   }
	   if($startDate && $endDate){  //有时间筛选
	   		$sql.=" and lucky_date>='".$startDate."' AND lucky_date<='".$endDate."'";
	   }
	   
	   $sql.=" order by id asc limit ".($page-1)*$pageSize.",".$pageSize;
	  //Yii::log($sql,CLogger::LEVEL_ERROR);
	   $command=$conn->createCommand($sql);
	   $rows=$command->queryAll();
	   return $rows;   
   	}

   	
   	/*
   	 * 汇总中奖结果报表搜索方法
   	* */
   	public function total_report_search()
   	{
   		if (isset($_GET['exportAction']) && $_GET['exportAction'] == "exportTotalReport") {
   			$luckyCustReq=$_GET['LuckyCustResult'];
   			if(!empty($luckyCustReq['branch_id'])){
   				$this->branch_id=intval($luckyCustReq['branch_id']);
   			}
   			if(!empty($luckyCustReq['get_start_time'])){
   				$this->get_start_time=$luckyCustReq['get_start_time'];
   			}
   			if(!empty($luckyCustReq['get_end_time'])){
   				$this->get_end_time=$luckyCustReq['get_end_time'];
   			}
   			
   		}
   		
   		$criteria = new CDbCriteria;
   		$community_ids = array();
   		$branchParentId = F::getBranchParentId();
   		$regionId = F::getRegionId();
   		
   		
   		$employee = Employee::model()->findByPk(Yii::app()->user->id);
   		$branchIds = $employee->mergeBranch;
   		
   		
   		//判断小区权限
   		if (!empty($employee->branch)) {
   			foreach ($branchIds as $branchId) {
   				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
   				$community_ids = array_unique(array_merge($community_ids, $data));
   			}
   			$criteria->addInCondition('community_id', $community_ids);
   			$select = "COUNT(*) count,SUM(isred=0) count1,SUM(isred=1) count2," . $branchParentId . ",t.community_id";
   			$criteria->select = array($select);
   			$criteria->group = "`t`.community_id";
   		}
   	
   		if ($this->branch_id != '') {
   			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
   			$criteria->addInCondition('community_id', $community_ids);
   			
   				$select = "COUNT(*) count,SUM(isred=0) count1,SUM(isred=1) count2," . $branchParentId . ",t.community_id";
   				$criteria->select = array($select);
   				$criteria->group = "`t`.community_id";
   			
   		} else if ($this->region != '') //如果有地区
   		{
   			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');
   			$criteria->addInCondition('community_id', $community_ids);
   			
   				$select = "COUNT(*) count,SUM(isred=0) count1,SUM(isred=1) count2," . $regionId . ",`t`.community_id";
   				$criteria->select = array($select);
   				$criteria->group = "`t`.community_id";
   			
   		}
   		$community_ids[]=$this->community_id;
   		$criteria->addInCondition('community_id', $community_ids);
   		//$criteria->addInCondition('community_id', $this->community_id);
   		$criteria->compare('id', $this->id);
   		//$criteria->compare('community_id', $this->community_id);
   		if ($this->get_start_time != "") {
   			$criteria->addCondition('lucky_date>="' . $this->get_start_time.'"');
   		}
   		if ($this->get_end_time != "") {
   			$criteria->addCondition('lucky_date<="' . $this->get_end_time . ' 23:59:59"');
   		}
   	
   		//var_dump($criteria);exit();
   		return new ActiveDataProvider($this, array(
   				'criteria' => $criteria,
   		));
   	}
   	
   	
   	public function getCustomerAddress(){
        if($this->customer){
            return $this->customer->CommunityAddress.$this->customer->BuildName.$this->customer->room;
        }else{
            return "";
        }
    }



   	public function getBranchNames()
   	{
   		$branchName = '';
   		if (!empty($this->community) && !empty($this->community->branch)) {
   			$branchName = $this->community->branch->getMyParentBranchName($this->community->branch_id,true);
   		}
   		return $branchName;
   	}
   	//获得小区所在的地区，isself=true，包括小区
   	public function getCommunityBelongRegion($isself = false)
   	{
   		$community = Community::model()->findByPk($this->community_id);
   		if (!empty($community)) {
   			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
   			$_regionName = $community->ICEGetCommunityRegionsNames();
   		} else {
   			$_regionName = "";
   		}
   		if ($isself) {
   			$_regionName .= '-' . $community->name;
   		}
   		return $_regionName;
   	}
   	
        
        //获取中了电信卡，但是没填手机号码
        public function getTelecom(){
            if($this->prize->prize_name == "中国电信话费"){
                $model = Telecom::model()->find("lucky_cust_result_id=".$this->id);
                if($model){
                    if(empty($model['mobile'])){
                        return true;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }


        //获取中了泰康人寿，但是没填手机号码
        public function getTaikangLife($id){
            if($this->prize->prize_name == "泰康人寿"){
                $model = TaikangLife::model()->find("lucky_result_id=".$id);
                if($model){
                    if(empty($model['identity']) && time()-$model["create_time"]<=86400 && $model["status"]==0){
                        return 1;	//可以填写
                    }else if(empty($model['identity']) && time()-$model["create_time"]>=86400 && $model["status"]==0){
                    	return 3; 	//已过期               	
                    }else{
                    	return 2; 	//已提交
                    }
                }else{
                    return 0;	//不显示
                }
            }else{
                return 0;	//不显示
            }
        }


        //获取中了泰康人寿，但是没填手机号码
        public function getLuckyShopCode($id){
            if(in_array($this->prize->id,$this->entityList)){
                $model = LuckyEntity::model()->find("lucky_result_id=".$id);
                if($model){
                   	return $model->code;
                }else{
                    return false;	//不显示
                }
            }else{
                return false;
            }
        }

}
