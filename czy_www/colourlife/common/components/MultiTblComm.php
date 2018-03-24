<?php
class MultiTblComm
{
    static private $instance;
  
    protected $bPoolUseful = false;
    
    protected  $type_pool = array();
    protected  $eProduct_pool = array();
    protected $oaJobs = array();
    
    //版本号
    protected $version = "v1.2";
    
    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $type 订单类型
     * @return array
     * 获得域奖励相关的表结构信息
     */
    public function getRelaTblDes($type){
        
        if(empty($type)){
            return array();
        }

     	$connection = Yii::app()->db;
        $sql = "select * FROM reward_comm_def where is_deleted =0 and type= '".$type."' limit 1";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        
        if (count($result) > 0) {
        	return $result[0];
        }
        
        return array();
    }

    /**
     * 根据理财产品类来获得产品类型
     * @param string $type  理财产品
     * @return int product_type
     */
    public function getEproductIType($type = '')
    { 
        $product_type = 0;
        
        if (empty($type)){
        	return $product_type;
        }
        if ($this->bPoolUseful){
	        if (!empty($this->type_pool[$type])){
	        	return $this->type_pool[$type];
	        }
        }
                
        $connection = Yii::app()->db;
        $sql = "select product_type FROM reward_product_type where is_deleted =0 and type= '".$type."'";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        if (count($result) > 0) {
        	foreach ($result as $k1 => $v1) {
        		
                    $product_type = $v1['product_type'];
           }
           $this->type_pool[$type] = $product_type;     
        }

        return $product_type;
    }  
    
    /**
     * 根据理财产品类来获得产品信息
     * @param string $type  理财产品
     * @return array
     */
    public function getEproductInfo($type = '')
    { 
        if (empty($type)){
        	
        	$connection = Yii::app()->db;
	        $sql = "select * FROM reward_product_type where is_deleted =0";
	        $command = $connection->createCommand($sql);
	        $result = $command->queryAll();
	        if (count($result) > 0) {
	        	return $result;
	        }
        }
        else 
        {
	        if ($this->bPoolUseful){
		        if (!empty($this->eProduct_pool[$type])){
		        	return $this->eProduct_pool[$type];
		        }
	        }
	                
	        $connection = Yii::app()->db;
	        $sql = "select * FROM reward_product_type where is_deleted =0 and type= '".$type."' limit 1";
	        $command = $connection->createCommand($sql);
	        $result = $command->queryAll();
	
	        if (count($result) > 0) {
	        	
	           $this->eProduct_pool[$type] = $result[0];  
	           return $this->eProduct_pool[$type];
	        }
        }
        
        return array();
    }  
    
    /**
     * 获得OA系统的所有职位信息
     *
     * @return jobs
     */
    public function getOAJobs()
    { 
    
        if (!empty($this->oaJobs)){
        	return $this->oaJobs;
        }
        
        Yii::import('common.api.SsoSystemApi');
        $data = SsoSystemApi::getInstance()->getOAJobs();
        if ($data){
	        if (!empty($data)){
	        	$this->oaJobs = $data;
	        }
        }
       
        return $this->oaJobs;
    }  

    /**
     * 拼接字符串
     * @param $smval
     * @param $valname
     * @param $valvlue
     * @return string
     */
    function appendParam($smval,$valname,$valvlue){
        if($valvlue == ""){
            $smval .= "";
        }else{
            $smval.=($valname.'='.$valvlue.'&');
        }
        return $smval;
    }
    
	private function doOkMark_new($type, $id, $sn, $remark = '')
    {
    	if (empty($id) && empty($sn)){
    		return false;
    	}
    	
    	if (empty($remark)){
    		$remark = "订单数据已处理！";
    	}
    	$db = Yii::app()->db;
    	
    	$insert_sql = "insert into reward_import_cases (type, relaId, sn, remark, kind, create_time) values ('".
                        $type."',".$id.",'".$sn."','".$remark."', 8,'".time()."');";
                        
        $res2 = $db->createCommand($insert_sql)->execute();
        if ($res2){
        	$sqlUp= "update reward_import_cases set kind=((kind|0x200)&0xFFE) where sn='".$sn."' 
        		and type='".$type."' and relaId=".$id." and (kind&1)=1";
        
    		$db->createCommand($sqlUp)->execute();
        }
        //kind 为 8时，表明是成功处理的数据
	}
    
	/*
	 * 创建通用订单奖励数据
	 * 供倒数进程使用
	 */
	public function doCreateCommon($mAtt){
        
        if(!empty($mAtt)){
            ///if(empty($mAtt["rela_sn"])) {throw new CHttpException(400,"订单ID不能为空");}
            if(empty($mAtt["customer_id"])) {throw new CHttpException(400,"彩之云用户ID不能为空");}
            //if(empty($mAtt["name"])) {throw new CHttpException(400,"投资人姓名不能为空");}
            if(empty($mAtt["mobile"])) {throw new CHttpException(400,"投资人手机号不能为空");}
            if(empty($mAtt["type"])) {throw new CHttpException(400,"理财产品类型");}
            if(empty($mAtt["amount"])) {throw new CHttpException(400,"投资金额不能为空");}           
            
            if (empty($mAtt["name"])){
            	$mAtt["name"] ='访客';
            }
            
            $model = new Rewards;
        	$sn = SN::initByRewards()->sn;

            $orderData = $mAtt;
            $orderData["sn"] = $sn;
            $orderData["rela_sn"] = $mAtt["rela_sn"];
            $orderData["amount"] = isset($mAtt['amount'])?F::priceFormat($mAtt['amount']):0.00;
            //$orderData["create_time"] = time();
            if (!isset($orderData["create_time"])){
            	$orderData["create_time"] = time();
            }
            $orderData['rewardparam'] = 0;
            
            if(empty($orderData["rela_sn"])){
            	if(!empty($orderData["rxh_sn"])){
            		$orderData["rela_sn"] = $orderData["rxh_sn"];
            	}
            }
            if(empty($orderData["rela_sn"])){
            	throw new CHttpException(400,"订单ID不能为空");
            }
            
            if ($orderData['amount']<=0){
            	throw new CHttpException(400,"订单金额不能为空或订单金额格式不正确");
            }
            
            $cu = Customer::model()->findByPK($mAtt["customer_id"]);
            if(!$cu) {throw new CHttpException(400,"提供的彩之云用户ID无效");}
            
        	$rJobs = RewardJobs::model()->getRewardJobs($mAtt["type"], '');
            if(0 == count($rJobs))
            {
            	throw new CHttpException(400,"没有找到与此类订单相关奖励参数.");
            }

            $rs=Rewards::model()->find('rela_sn=:rela_sn and type=:type', array(':rela_sn'=>$orderData["rela_sn"],':type'=>$orderData["type"]));
            if($rs){
                $arr[]=array("ok"=>0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> $rs->sn,'Message'=>'投标ID数据已经存在,不需重复添加');
                return CJSON::encode($arr);
            }

            $orderData["community_id"] = $cu["community_id"];

        	$product_rate = $this->getProductRate($orderData["type"], 'rate');

			$rate1 = isset($product_rate)?F::price_formatNew($product_rate):0.00;
        	if($rate1 < 0.01){
				$arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0, 'Message'=>'奖金参数为零,暂不处理.');
				return CJSON::encode($arr);
			}
			
			//$orderData['allot_all'] = F::price_formatNew($orderData['amount']* $rate1 / 100);
			$orderData['allot_all'] = $this->getAnnualizedAllot($rate1, $orderData);
        	if ($orderData['allot_all'] <0.01) //奖励项没钱
			{
				$bk_model = new RewardComm;
				$bk_model->backUpOrder($orderData);
				
				$arr[]=array( "ok" => 1,'Sn' => $orderData["rela_sn"],'Status' => 0, 'Message'=>'奖金极低,暂不处理.');
				return CJSON::encode($arr);
			}
            
            Yii::import('common.api.SsoSystemApi');
        	$checkData = SsoSystemApi::getInstance()->getEmployee('tangxb');
			if (empty($checkData)){
				$arr[]=array("ok"=>0,'Sn' => $orderData["rela_sn"],'Status' => 0,'Message'=>'第三方平台有异常，请稍后再提交.');
                return CJSON::encode($arr);
			}
            
        	if (!empty($orderData["community_id"])){
        		
        		if (strnatcmp(strtoupper($orderData['community_id']),'585')==0)  //体验小区
        		{
        			if(isset($orderData["object_id"])){
        				if(!empty($orderData["object_id"])){
        					$advFee = AdvanceFee::model()->findByPk($orderData["object_id"]);
        					if(!empty($advFee)){
        						$orderData["community_id"] = $advFee->community_id;
        					}
        				}
        			}
        		}
            	
        		try{
	        		$data_bm = SsoSystemApi::getInstance()->getBmManager(trim($orderData["community_id"]));
		        	if(!empty($data_bm)){
		        		$orderData['region_4bm'] = isset($data_bm[0]['region'])? $data_bm[0]['region']: '';
			            $orderData['region_id_4bm'] = isset($data_bm[0]['region_id'])? $data_bm[0]['region_id']: '';
			            $orderData['branch_4bm'] = isset($data_bm[0]['branch'])? $data_bm[0]['branch']: '';
			            $orderData['branch_id_4bm'] = isset($data_bm[0]['branch_id'])? $data_bm[0]['branch_id']: '';
		        	}
        		}catch(Exception $e){
        		}
            }
            
        	if(empty($orderData['inviter_mobile'])){//如果业主没有填写推荐人手机号码，则通过该业主是否绑定了 专属客服经理（通过专属客服经理绑定表对照）
	            if($cu){
	            	$bind = CustomerBindManager::model()->find('proprietor_id=:uid and state=1',array(':uid'=>$cu->id));
		        	if($bind&&!empty($bind->manager)){
		            	$bind2 = EmployeeBindCustomer::model()->find('customer_id=:uid and state=1',array(':uid'=>$bind->manager_id));
		            	if($bind2&&!empty($bind2->employee)){
		                	$orderData["inviter_id"]=$bind2->employee_id;
		                    //$orderData["send_type"]=1;
		               	}else{
		                	$orderData["inviter_id"]=0;
		                }
		             }else{
	             		$orderData["inviter_id"]=0;
	             	 }
	            }else{
					$orderData["inviter_id"]=0;
	            }
			}else{//如果 业主填写了推荐人手机号码，首先优先判断该手机号码(认为填写的是彩之云手机号)是否存在对应的oa账户（通过彩之云绑定表对照）
                        
	        	$c1= Customer::model()->find('mobile=:mobile',array(':mobile'=>$orderData['inviter_mobile']));
	         	if($c1){
	            	$bind = EmployeeBindCustomer::model()->find('customer_id=:uid and state=1',array(':uid'=>$c1->id));
	            	if($bind&&!empty($bind->employee)){
	                	$orderData["inviter_id"]=$bind->employee_id;
	                }else{
	                	$orderData["inviter_id"]=0; 
	                }
	            }else{
	            	$orderData["inviter_id"]=0;
	            }
			}
			$orderData["inviter"]= $orderData["inviter_id"];
			
			if (intval($orderData["inviter_id"]) != 0){ 

				$mEp = Employee::model()->findByPk($orderData["inviter_id"]);
				if($mEp){
					$orderData["inviter_oa"]=$mEp->oa_username; 
					$orderData["inviter_name"]=$mEp->name; 
					$orderData["inviter_mobile"]=$mEp->mobile; 
				}
			}

        	if (strnatcmp(strtoupper($orderData['type']),'RXH')==0)  //RXH产品类型
    		{
    			$tb_model = new Reward_rxh;
    		
    		}
    		else if (strnatcmp(strtoupper($orderData['type']),strtoupper('hhn_eload'))==0)
    		{
    			$tb_model = new Reward_loan;
	    		
    		}else{	
    			$tb_model = new RewardComm;
    		}
    		
    		$isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
 			$transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
	        try 
	        {
		        if(!$tb_model->createOrder($orderData)){
		        	
		        	(!$isTransaction)?$transaction->rollback():'';
	            	$arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> '','Message'=>'接收数据失败,基数');
	            	return CJSON::encode($arr);
	            }

	            if($model->createOrder($orderData)){
	            	if ($tb_model->isExistSame($orderData['sn'], $orderData['type'], $orderData['rela_sn'])
	            		|| $model->isExistSame($orderData["sn"], $orderData['type'], $orderData["rela_sn"])){
	            			
	            		(!$isTransaction)?$transaction->rollback():'';
	                
	                	$arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> '','Message'=>'投标ID数据已经存在,多进程');
	            	}else{
	            		(!$isTransaction)?$transaction->commit():'';
	            	
		                // $other = SN::findContentBySN($sn);
		                $arr[]=array( "ok" => 1,'Sn' => $orderData["rela_sn"],'Status' => 99,'ColourSn'=> $sn,'Message'=>'接收数据成功');
	            	}
	            }else{
	                (!$isTransaction)?$transaction->rollback():'';
	                
	                $arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> '','Message'=>'接收数据失败,奖励数');
	            }
	            return  CJSON::encode($arr);
	        }catch(Exception $e){
	            (!$isTransaction)?$transaction->rollback():'';
	            
	            $arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> '','Message'=>$e->getMessage());
	        }
            
            return CJSON::encode($arr);
        }else{
            throw new CHttpException(400,"参数不正确");
        }
    }
    
	public function doBackUpCommon($mAtt){
        
        if(!empty($mAtt)){
            ///if(empty($mAtt["rela_sn"])) {throw new CHttpException(400,"订单ID不能为空");}
            if(empty($mAtt["customer_id"])) {throw new CHttpException(400,"彩之云用户ID不能为空");}
            //if(empty($mAtt["name"])) {throw new CHttpException(400,"投资人姓名不能为空");}
            //if(empty($mAtt["mobile"])) {throw new CHttpException(400,"投资人手机号不能为空");}
            if(empty($mAtt["type"])) {throw new CHttpException(400,"理财产品类型");}
            if(empty($mAtt["amount"])) {throw new CHttpException(400,"投资金额不能为空");}           
            
            $sn = SN::initByRewards()->sn;
            
            $orderData = $mAtt;
            $orderData["sn"] = $sn;
            $orderData["rela_sn"] = $mAtt["rela_sn"];
            $orderData["amount"] = isset($mAtt['amount'])?F::priceFormat($mAtt['amount']):0.00;
            
            if(empty($orderData["rela_sn"])){
            	if(!empty($orderData["rxh_sn"])){
            		$orderData["rela_sn"] = $orderData["rxh_sn"];
            	}
            }
            if(empty($orderData["rela_sn"])){
            	throw new CHttpException(400,"订单ID不能为空");
            }
            
            if ($orderData['amount']<=0){
            	throw new CHttpException(400,"订单金额不能为空或订单金额格式不正确");
            }
            $orderData['rewardparam'] = 0;
            
            $cu = Customer::model()->findByPK($mAtt["customer_id"]);
            if($cu){ 
            	$orderData["community_id"] = $cu["community_id"];
            }
            
        	$product_rate = $this->getProductRate($orderData["type"], 'rate');
			
			$rate1 = isset($product_rate)?F::price_formatNew($product_rate):0.00;

			//$orderData['allot_all'] = F::price_formatNew($orderData['amount']* $rate1 / 100);
			$orderData['allot_all'] = $this->getAnnualizedAllot($rate1, $orderData);

			$bk_model = new RewardComm;
			$bk_model->backUpOrder($orderData);
			
			$model = new Rewards;
			
			if($model->backUpOrder($orderData)){
                // $other = SN::findContentBySN($sn);
                $arr[]=array( "ok" => 1,'Sn' => $orderData["rela_sn"],'Status' => 99,'ColourSn'=> '','Message'=>'备份数据成功');
            }else{
               
                $arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> '','Message'=>'备份数据失败');
            }
            return CJSON::encode($arr);
        }else{
            throw new CHttpException(400,"参数不正确");
        }
    }
    
    
	public function doCreateSingle($mAtt){
        
        if(!empty($mAtt)){
            if(empty($mAtt["rela_sn"])) {throw new CHttpException(400,"订单ID不能为空");}
        	if(empty($mAtt["sn"])) {throw new CHttpException(400,"sn不能为空");}
            if(empty($mAtt["customer_id"])) {throw new CHttpException(400,"彩之云用户ID不能为空");}
            //if(empty($mAtt["name"])) {throw new CHttpException(400,"投资人姓名不能为空");}
            //if(empty($mAtt["mobile"])) {throw new CHttpException(400,"投资人手机号不能为空");}
            if(empty($mAtt["type"])) {throw new CHttpException(400,"理财产品类型");}
            if(empty($mAtt["amount"])) {throw new CHttpException(400,"投资金额不能为空");} 
        	if(empty($mAtt["reward_userid"])) {throw new CHttpException(400, "reward_userid不能为空");}
            
            $orderData = $mAtt;
            $orderData["amount"] = isset($mAtt['amount'])?F::priceFormat($mAtt['amount']):0.00;
            
            $orderData["allot_all"] = isset($mAtt['allot_all'])?F::priceFormat($mAtt['allot_all']):0.00;
            $orderData["reward"] = isset($mAtt['reward'])?F::priceFormat($mAtt['reward']):0.00;
            
            if ($orderData['amount']<=0){
            	throw new CHttpException(400,"订单金额不能为空或订单金额格式不正确");
            }
            if(!isset($orderData["rewardparam"])){
            	$orderData['rewardparam'] = 0x8000;
            }
            if(empty($orderData["rewardparam"])){
            	$orderData['rewardparam'] = 0x8000;
            }

        	$sql="SELECT * FROM rewards WHERE sn ='".$mAtt['sn']."' and rela_sn ='".$mAtt['rela_sn']."' and reward_userid ='".$mAtt['reward_userid']."' and (rewardparam&".$orderData['rewardparam']."=".$orderData['rewardparam'].")";
								
			$mdfModels = Rewards::model()->findAllBySql($sql);
			if(empty($mdfModels)){
				$model = new Rewards;
				
				if($model->createSingle($orderData)){
	                // $other = SN::findContentBySN($sn);
	                $arr[]=array( "ok" => 1,'Sn' => $orderData["rela_sn"],'Status' => 99,'ColourSn'=> '','Message'=>'生成成功');
	            }else{
	               
	                $arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> '','Message'=>'生成失败');
	            }
			}else{
				$arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> '','Message'=>'生成失败,已经存在');
			}
            
            return CJSON::encode($arr);
        }else{
            throw new CHttpException(400,"参数不正确");
        }
    }
    
	/**
     * 根据理财产品来获得产品提成比例
     * @param string $type  理财产品
     * @param string $rate_name  字段名称
     * @return int product_type
     */
    public function getProductRate($type = '', $rate_name = 'rate')
    { 
        $rate = 0.1; //缺省为 1/10
        
        if (empty($type)){
        	return $rate;
        }
        
    	if (empty($rate_name)){
        	$rate_name = 'rate';
        }
                
        $connection = Yii::app()->db;
        $sql = "select ".$rate_name." from reward_product_type where is_deleted =0 and type= '".$type."' limit 1";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
            
        if (count($result) > 0) {
        	foreach ($result as $k1 => $v1) {
        		
                    $rate = $v1[$rate_name];
                }
        }

        return $rate;
    }  
    
    public function getAnnualizedAllot($rate, $mAtt){
    	$rtn = 0.0;
    	if (empty($mAtt)|| empty($rate)){
    		return $rtn;
    	}
    	if($rate == 0.0){
    		return $rtn;
    	}
    	if (empty($mAtt['amount'])){
    		return $rtn;
    	}
    	if (empty($mAtt['type'])){
    		return $rtn;
    	}
    	$amount = isset($mAtt['amount'])?F::price_formatNew($mAtt['amount']):0.00;
    	$smonth = 0;
    	
    	if (strnatcmp(strtoupper($mAtt['type']),'RXH')==0)  //RXH产品类型
    	{
    		$sday = 0;
    		if(!empty($mAtt['licai_time_month'])){
    			$smonth = $mAtt['licai_time_month'];
    		}
    		if(!empty($mAtt['licai_time_day'])){
    			$sday = $mAtt['licai_time_day'];
    		}

    		if (intval($smonth)> 0){
    			$rtn = $amount * $rate * $smonth / 1200;
    		}else if (intval($sday)> 0){
    			$rtn = $amount * $rate * $sday / 36500;
    		}else{
    			//传入参数有误
    		}
    		
    	}else if (strnatcmp(strtoupper($mAtt['type']),strtoupper('hhn_eload'))==0){
    		if(!empty($mAtt['month'])){
    			$smonth = $mAtt['month'];
    		}
    		if (intval($smonth)> 0){
    			$rtn = $amount * $rate * $smonth / 1200;
    		}else{
    			//传入参数有误
    		}
    	}else if (strnatcmp(strtoupper($mAtt['type']),strtoupper('elicai'))==0){
    		if(!empty($mAtt['month'])){
    			$smonth = $mAtt['month'];
    		}
    		if (intval($smonth)> 0){
    			$rtn = $amount * $rate * $smonth / 1200;
    		}else{
    			//传入参数有误
    		}
    	}else if ((strnatcmp(strtoupper($mAtt['type']),strtoupper('ParkingFees2'))==0)
    			||(strnatcmp(strtoupper($mAtt['type']),strtoupper('PropertyFees2'))==0)
    			||(strnatcmp(strtoupper($mAtt['type']),strtoupper('AdvanceFees'))==0)){
    		
    			$rtn = $amount * $rate / 100;
    			
    	}else if ((strnatcmp(strtoupper($mAtt['type']),strtoupper('PropertyActivity'))==0)
    			||(strnatcmp(strtoupper($mAtt['type']),strtoupper('PropertyFees'))==0)
    			||(strnatcmp(strtoupper($mAtt['type']),strtoupper('ParkingFeesMonth'))==0)
    			||(strnatcmp(strtoupper($mAtt['type']),strtoupper('ParkingFees'))==0)){
    				
    			
    			$rate_id = 0;
	    		if(!empty($mAtt['rate_id'])){
	    			$rate_id = $mAtt['rate_id'];
	    		}
	    		if (intval($rate_id) == 2){	
	    			$smonth = 6;
	    		}else if (intval($rate_id) == 3){
	    			$smonth = 24;
	    		}else{	
	    			$smonth = 12;
	    		}	
    		
    			$rtn = $amount * $rate * $smonth / 1200;
    			
		}else if (strnatcmp(strtoupper($mAtt['type']),strtoupper('zzhplan'))==0){
    				
    			$rate_id = 0;
    			$user_rate = 0.0;
    			
	    		if(!empty($mAtt['rate_id'])){
	    			$rate_id = $mAtt['rate_id'];
	    		}
	    		
				if(!empty($mAtt['user_rate'])){
	    			$user_rate = $mAtt['user_rate'];
	    		}

    			if (intval($rate_id) == 1){
	    			$smonth = 12;

	    		}else if (intval($rate_id) == 2){
	    			$smonth = 6;
	
	    		}else if (intval($rate_id) == 3){
	    			$smonth = 3;

	    		}else if (intval($rate_id) == 4){
	    			$smonth = 1;

	    		}else{
	    			$smonth = 0;
	    		}
	    		$rtn = $amount * $rate * $smonth/1200;

    	}
    	
    	return $rtn;
    }
    
    /**
     * 根据Oa信息来获得彩生活的员工信息
     * @param string $skey  关键字段名
     * @param string $sval  字段值
     * @return array()
     */
    public function getEmployeeByOa($skey , $sval, $rtnKey ='')
    { 
        $rtn = array();
        
        if (empty($skey) || empty($sval)){
        	return $rate;
        }
        
        try 
        {
	        $connection = Yii::app()->db;
	        $sql = "select * FROM employee where is_deleted =0 and ".$skey."= '".$sval."' limit 1";
	        
	        $command = $connection->createCommand($sql);
	        $result = $command->queryAll();
	        
	        if (count($result) > 0) {
	        	$rtn = $result[0];
	        }
	        if (!empty($rtnKey)){
	        	if(empty($rtn[$rtnKey])){
	        		$rtn = '';
	        	}else{
	        		$rtn = $rtn[$rtnKey];
	        	}
	        }
        } catch (Exception $e) {
        	if (!empty($rtnKey)){
	        	$rtn = '';
	        }
        }    
        
        return $rtn;
    }  
    
	public static function getArrayVal4Key($v_ary, $key)
    {
    	if (empty($v_ary)){
    		return '';
    	}
    	try{
	    	if (array_key_exists($key, $v_ary)){
	            return $v_ary[$key];
	        }	
	    }catch(Exception $e){
	    }
        
        return '';
    }
}
?>