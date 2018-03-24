<?php

/**
 * This is the model class for table "lucky_entity_envelope".
 *
 * The followings are the available columns in table 'lucky_entity_envelope':
 * @property int $id
 * @property float $money
 * @property int $total_number
 * @property int $surplus_number
 * @property int $lucky_prize_id
 * @property int $parent_id
 * @property int $city_id
 */
class LuckyEntityEnvelope extends CActiveRecord
{
    public $modelName = '抢粽子奖项';
    
    const TYPE_RED_PACKET = 0;  //红包类型
    const TYPE_ENTITY = 1;      //实物

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
    public function relations() {
        return array(
            'riceDumplings' => array(self::BELONGS_TO, 'SetRiceDumplings', 'lucky_prize_id'),
        );
    }

    public function getPrizeName(){
        if($this->riceDumplings){
            return $this->riceDumplings->activity_name;
        }else{
            return "";
        }
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'lucky_entity_envelope';
    }

    
    public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('prize_name, prize_level, prize_genner_time, prize_level_name, prize_count_all, prize_count_now, lucky_act_id, create_username, create_userid, create_date, update_username, update_userid, update_date', 'required'),
			array('money','numerical'),
            array('money','check_money'),
            array('total_number','numerical'),
            array('total_number','check_total_number','on'=>'create'),
            array('surplus_number','numerical'),
            array('surplus_number','check_surplus_number','on'=>'create'),
            array('lucky_prize_id,parent_id,city_id,type','safe'),
		);
	}
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'money' => '单个红包金额',
            'total_number' => '总个数',
            'surplus_number' => '剩余个数',
            'lucky_prize_id' => '奖项Id',
            'PrizeName' => '奖项名称',
            'city' => '城市Id',
            'type' => '类型',
        );
    }

    //检验设置单个红包金额
    public function check_money(){
        if($this->money <= 0){
            $this -> addError('money','输入的红包金额要大于0');
        }
    }
    
    public function check_total_number(){
       if($this->parent_id == 0){
            $redEnvelopes = luckyEntityEnvelope::model()->findAll('lucky_prize_id=:lucky_prize_id and parent_id=0',array(':lucky_prize_id'=>$this->lucky_prize_id));
            $prize = SetRiceDumplings::model()->findByPk($this->lucky_prize_id);
            $last = $prize->total_number;
            foreach($redEnvelopes as $_v){
                $last-=$_v->total_number; 
            }
            if($this->total_number > $last){
                $this->addError('total_number',"输入的总个数不符合");
            }       
       }
    }
    
    //检验设置剩余红包个数
    public function check_surplus_number(){
        if(($this->surplus_number > $this->total_number) || $this->surplus_number<0){
            $this -> addError('surplus_number','输入的红包剩余个数不符合');
        }
    }
    
    
    public function getCityName(){
        return Region::model()->findByPk($this->city_id)->name;
    }
    
    /**
     * 产生用户红包，并减去相应红包数量
     * @param 用户Id $userId
     * @param 红包id $redId
     * @return 红包数量  如果为0，则代表没有产生红包
     */
	public function gennerRedPackage($userId,$redId){
		//获取用户所在的 地市id
		$conn=Yii::app()->db;

		//查询该红包信息
		$sql2="SELECT * FROM `lucky_entity_envelope` WHERE id=".$redId;
		$comm2=$conn->createCommand($sql2);
		$redInfo=$comm2->queryRow();
		if($redInfo['last_total']<$redInfo['minMoney']){ //剩余数量比最小值还小，直接返回没得到
			return 0;
		}
		
		
		//例举该红包分配的所有地市
		$sql3="SELECT id,last_total,city_id FROM `lucky_entity_envelope` WHERE `parent_id`=".$redId;
		$comm3=$conn->createCommand($sql3);
		$redSons=$comm3->queryAll();
		
		$getRed=mt_rand($redInfo['minMoney'],$redInfo['maxMoney']);
		if(empty($redSons)){//如果没有分配地市限制
			//直接产生红包并处理			
			if($getRed <= $redInfo['last_total']){ //当前产生的红包，不比剩余大，则为有效
				$sql4="UPDATE lucky_entity_envelope SET last_total=last_total-".$getRed." WHERE id=".$redId;
				$comm4=$conn->createCommand($sql4);
				if($comm4->execute()){  //更新该红包成功，返回本次得到的结果值
					return $getRed;
				}
			}
			return 0;
		}else{ //有地市限制
			//得到用户地市id
			$sql="SELECT c.`parent_id` FROM `customer` a
				RIGHT JOIN  `community` b ON (a.`community_id`=b.`id`)
				RIGHT JOIN `region` c ON (b.`region_id`=c.`id`)
				WHERE a.`id`=".$userId;
			$comm=$conn->createCommand($sql);
			//$row=$comm->queryRow();
			//$row=$comm->queryColumn();
			$row=$comm->queryScalar();
			$cityId=intval($row);
			
			//当前用户所在的地市是否有限制
// 			$sql5="SELECT * FROM lucky_entity_envelope WHERE `city_id`=".$cityId;
// 			$comm5=$conn->createCommand($sql5);
// 			$cityRed=$comm5->queryRow();
			//之前已经得到了所有的地市，用循环查找出来，就不用继续从数据库查了
			$myCityRed=null;
			foreach ($redSons as $value){
				if($value['city_id']==$cityId){
					$myCityRed=$value;
				}
			}
			if(empty($myCityRed)){  //当前用户在的地市没有限制红包 
				//总红包剩余减去所有该红包地市红包，即为可以产生的余额池
				$all=intval($redInfo['last_total']);
				foreach ($redSons as $value){
					$all-= intval($value['last_total']);
				}
				if($getRed <= $all){ //当前产生的红包，不比剩余大，则为有效
					$sql6="UPDATE lucky_entity_envelope SET last_total=last_total-".$getRed." WHERE id=".$redId;
					$comm6=$conn->createCommand($sql6);
					if($comm6->execute()){  //更新该红包成功，返回本次得到的结果值
						return $getRed;
					}
				}
				return 0;
			}else{ //该用户所在地市 有地市限制
				if($getRed <= $myCityRed['last_total']){ //当前产生的红包，不比剩余大，则为有效
					//当前记录和父记录，都减去相应的值
					$sql7="UPDATE lucky_entity_envelope SET last_total=last_total-".$getRed." WHERE id=".$redId." OR id=".$myCityRed['id'];
					$comm7=$conn->createCommand($sql7);
					if($comm7->execute()){  //更新该红包成功，返回本次得到的结果值
						return $getRed;
					}
					
				}
				return 0;
			}
		}
		
	}

	/**
	 * 产生用户红包，并减去相应红包数量
	 * @param 用户Id $userId
	 * @param 红包id $redId
	 * @return 红包数量  如果为0，则代表没有产生红包
	 */
	public function gennerRedPackage2($userId,$redId){
		//获取用户所在的 地市id
		$conn=Yii::app()->db;
	
		//
		//查询该红包信息(一个红包箱，里面包含其他红包。如:1.8，2.8，5.8)
		$sql1="SELECT id,surplus_number FROM `lucky_entity_envelope` WHERE id=".$redId;
		$comm1=$conn->createCommand($sql1);
		$redInfo=$comm1->queryRow();
		if($redInfo['surplus_number']<=0){ //最顶层的红包已经没有了
			//Yii::log("====最顶层的红包已经没有了==redid=".$redId,CLogger::LEVEL_ERROR);
			return 0;
		}
	
	
		//得到该红包箱里的分配值  如:A红包下，有1.8,5.8,8.8三个红包值
		$sql2="SELECT id,surplus_number,money  FROM `lucky_entity_envelope` WHERE `parent_id`=".$redId;
		$comm2=$conn->createCommand($sql2);
		$redSons=$comm2->queryAll();
	
		//在三个红包值里随机选一个
		if(count($redSons)<=0){
			//没有分配值，属于配置错误
			//Yii::log("====没有分配值，属于配置错误==redid=".$redId,CLogger::LEVEL_ERROR);
			return 0;
		}
		$redChoose=$redSons[rand(0, count($redSons)-1)];
		if($redChoose['surplus_number']<=0){
			//如果所选的结果里，没有可以分配的了，不给红包
			//Yii::log("====如果所选的结果里，没有可以分配的了==redid=".$redId,CLogger::LEVEL_ERROR);
			return 0;
		}
	
		////得到该红包下的分配值  如:1.8红包，对地域进行了分配
		$sql3="SELECT id,surplus_number,city_id FROM `lucky_entity_envelope` WHERE `parent_id`=".$redChoose['id'];
		$comm3=$conn->createCommand($sql3);
		$redChooseSons=$comm3->queryAll();
	
		if(empty($redChooseSons)){//如果没有分配地市限制
			//Yii::log("====没有分配地市限制==redid=".$redId,CLogger::LEVEL_ERROR);
			//直接产生红包并处理
			//红包箱-1，红包箱里的该红包-1
			$sql4="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id=".$redId." OR id =".$redChoose['id'];
			$comm4=$conn->createCommand($sql4);
			if($comm4->execute()){  //更新该红包成功，返回本次得到的结果值
				return ($redChoose['money']);
			}
			//Yii::log("====红包更新失败 ==redid=".$redId,CLogger::LEVEL_ERROR);
			return 0;
		}else{ //有地市限制
			//Yii::log("====有分配地市限制==redid=".$redId,CLogger::LEVEL_ERROR);
			//得到用户地市id
			$sql5="SELECT c.`parent_id` FROM `customer` a
				RIGHT JOIN  `community` b ON (a.`community_id`=b.`id`)
				RIGHT JOIN `region` c ON (b.`region_id`=c.`id`)
				WHERE a.`id`=".$userId;
			$comm5=$conn->createCommand($sql5);
			//$row=$comm->queryRow();
			//$row=$comm->queryColumn();
			$row=$comm5->queryScalar();
			$cityId=intval($row);
	
			//当前用户所在的地市是否有限制
			// 			$sql5="SELECT * FROM lucky_entity_envelope WHERE `city_id`=".$cityId;
			// 			$comm5=$conn->createCommand($sql5);
			// 			$cityRed=$comm5->queryRow();
			//之前已经得到了所有的地市，用循环查找出来，就不用继续从数据库查了
			$myCityRed=null;
			foreach ($redChooseSons as $value){
				if($value['city_id']==$cityId){
					$myCityRed=$value;
				}
			}
			if(empty($myCityRed)){  //当前用户在的地市没有限制红包
				//总红包剩余减去所有该红包地市红包，即为可以产生的余额池
				$all=intval($redInfo['surplus_number']); //所有剩余的数量
				foreach ($redChooseSons as $value){
					$all-= intval($value['surplus_number']);  //减去每个分配的区域的数量，即为当前用户可以分配的数量
				}
				if($all>0){ //还有数量
					$sql6="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id=".$redId." OR id=".$redChoose['id'];
					$comm6=$conn->createCommand($sql6);
					if($comm6->execute()){  //更新该红包成功，返回本次得到的结果值
						return ($redChoose['money']);
					}
				}
				return 0;
			}else{ //该用户所在地市 有地市限制
				if($myCityRed['surplus_number']>0){ //还有数量
					//当前记录和父记录和祖父记录，都减去相应的值
					$sql7="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id=".$redId." OR id=".$redChoose['id']." OR id=".$myCityRed['id'];
					$comm7=$conn->createCommand($sql7);
					if($comm7->execute()){  //更新该红包成功，返回本次得到的结果值
						return ($redChoose['money']);
					}
				}
				return 0;
			}
		}
	
	}
	
	
	/**
	 * 产生用户红包，并减去相应红包数量
	 * @param 用户Id $userId
	 * @param 奖项id $prizeid
	 * @return 红包数量  如果为0，则代表没有产生红包
	 */
	public function gennerRedPackage3($userId,$prizeid){
		//获取用户所在的 地市id
		$conn=Yii::app()->db;
	
		//得到该奖项下的分配值  如:‘平安奖’下，有1.8,5.8,8.8三个红包值
		$sql2="SELECT id,surplus_number,money FROM `lucky_entity_envelope` WHERE lucky_prize_id=".$prizeid ." AND parent_id=0";
		$comm2=$conn->createCommand($sql2);
		$redSons=$comm2->queryAll();
	
	
		//在三个红包值里随机选一个
		if(count($redSons)<=0){
			//没有分配值，属于配置错误
			//Yii::log("====没有分配值，属于配置错误==prizeid=".$prizeid,CLogger::LEVEL_ERROR);
			return 0;
		}
		$redChoose=$redSons[rand(0, count($redSons)-1)];
		if($redChoose['surplus_number']<=0){
			//如果所选的结果里，没有可以分配的了，不给红包
			//Yii::log("====如果所选的结果里，没有可以分配的了==prizeid=".$prizeid,CLogger::LEVEL_ERROR);
			return 0;
		}
	
		////得到该红包下的分配值  如:1.8红包，对地域进行了分配
		$sql3="SELECT id,surplus_number,city_id FROM `lucky_entity_envelope` WHERE `parent_id`=".$redChoose['id'];
		$comm3=$conn->createCommand($sql3);
		$redChooseSons=$comm3->queryAll();
	
		if(empty($redChooseSons)){//如果没有分配地市限制
			//Yii::log("====没有分配地市限制==prizeid=".$prizeid,CLogger::LEVEL_ERROR);
			//直接产生红包并处理
			//红包-1
			$sql4="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id =".$redChoose['id'];
			$comm4=$conn->createCommand($sql4);
			if($comm4->execute()){  //更新该红包成功，返回本次得到的结果值
				return ($redChoose['money']);
			}
			//Yii::log("====红包更新失败 ==prizeid=".$prizeid,CLogger::LEVEL_ERROR);
			return 0;
		}else{ //有地市限制
			//Yii::log("====有分配地市限制==prizeid=".$prizeid,CLogger::LEVEL_ERROR);
			//得到用户地市id
			$sql5="SELECT c.`parent_id` FROM `customer` a
				RIGHT JOIN  `community` b ON (a.`community_id`=b.`id`)
				RIGHT JOIN `region` c ON (b.`region_id`=c.`id`)
				WHERE a.`id`=".$userId;
			$comm5=$conn->createCommand($sql5);
			//$row=$comm->queryRow();
			//$row=$comm->queryColumn();
			$row=$comm5->queryScalar();
			$cityId=intval($row);
	
			//当前用户所在的地市是否有限制
			// 			$sql5="SELECT * FROM lucky_entity_envelope WHERE `city_id`=".$cityId;
			// 			$comm5=$conn->createCommand($sql5);
			// 			$cityRed=$comm5->queryRow();
			//之前已经得到了所有的地市，用循环查找出来，就不用继续从数据库查了
			$myCityRed=null;
			foreach ($redChooseSons as $value){
				if($value['city_id']==$cityId){
					$myCityRed=$value;
				}
			}
			if(empty($myCityRed)){  //当前用户在的地市没有限制红包
				//总红包剩余减去所有该红包地市红包，即为可以产生的余额池
				$all=intval($redChoose['surplus_number']); //所有剩余的数量
				foreach ($redChooseSons as $value){
					$all-= intval($value['surplus_number']);  //减去每个分配的区域的数量，即为当前用户可以分配的数量
				}
				if($all>0){ //还有数量
					$sql6="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id=".$redChoose['id'];
					$comm6=$conn->createCommand($sql6);
					if($comm6->execute()){  //更新该红包成功，返回本次得到的结果值
						return ($redChoose['money']);
					}
				}
				return 0;
			}else{ //该用户所在地市 有地市限制
				if($myCityRed['surplus_number']>0){ //还有数量
					//当前记录和父记录，都减去相应的值
					$sql7="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id=".$redChoose['id']." OR id=".$myCityRed['id'];
					$comm7=$conn->createCommand($sql7);
					if($comm7->execute()){  //更新该红包成功，返回本次得到的结果值
						return ($redChoose['money']);
					}
				}
				return 0;
			}
		}
	
	}
        
        
     /**
	 * 产生实物奖，并减去相应奖项数量
	 * @param 用户Id $userId
	 * @param 奖项id $prizeid
	 * @return 奖品数量  如果为0，则代表没有产生奖
	 */
        public function gennerEntity($userId,$prizeid){
	        //获取用户所在的 地市id
			$conn=Yii::app()->db;
			$sql2="SELECT id,surplus_number,money FROM `lucky_entity_envelope` WHERE lucky_prize_id=".$prizeid ." AND parent_id=0";
			$comm2=$conn->createCommand($sql2);
			$redSons=$comm2->queryAll();
	        if(count($redSons)<=0){
				//没有分配值，属于配置错误
				return 0;
			}
	        $redChoose=$redSons[rand(0, count($redSons)-1)];
			if($redChoose['surplus_number']<=0){
				//如果所选的结果里，没有可以分配的了，不给奖
				return 0;
			}
			$sql3="SELECT id,surplus_number,city_id FROM `lucky_entity_envelope` WHERE `parent_id`=".$redChoose['id'];
			$comm3=$conn->createCommand($sql3);
			$redChooseSons=$comm3->queryAll();
		
			if(empty($redChooseSons)){
				$sql4="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id =".$redChoose['id'];
				$comm4=$conn->createCommand($sql4);
				if($comm4->execute()){  //更新该奖成功，返回本次得到的结果值
					return ($redChoose['money']);
				}
				return 0;
			}else{ //有地市限制
				//得到用户城市id
				$sql5="SELECT c.`parent_id` FROM `customer` a
					RIGHT JOIN  `community` b ON (a.`community_id`=b.`id`)
					RIGHT JOIN `region` c ON (b.`region_id`=c.`id`)
					WHERE a.`id`=".$userId;
				$comm5=$conn->createCommand($sql5);
				$row=$comm5->queryScalar();
				$cityId=intval($row);
				$myCityRed=null;
				foreach ($redChooseSons as $value){
					if($value['city_id']==$cityId){
						$myCityRed=$value;
					}
				}
				if(empty($myCityRed)){  //当前用户在的地市没有限制
					//总剩余数减去所有该奖对应的数量，即为可以产生的剩余池
					$all=intval($redChoose['surplus_number']); //所有剩余的数量
					foreach ($redChooseSons as $value){
						$all-= intval($value['surplus_number']);  //减去每个分配的区域的数量，即为当前用户可以分配的数量
					}
					if($all>0){ //还有数量
						$sql6="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id=".$redChoose['id'];
						$comm6=$conn->createCommand($sql6);
						if($comm6->execute()){  //更新该奖成功，返回本次得到的结果值
							return ($redChoose['money']);
						}
					}
					return 0;
				}else{ //该用户所在城市 有城市限制
					if($myCityRed['surplus_number']>0){ //还有数量
						//当前记录和父记录，都减去相应的值
						$sql7="UPDATE lucky_entity_envelope SET surplus_number=surplus_number-1 WHERE id=".$redChoose['id']." OR id=".$myCityRed['id'];
						$comm7=$conn->createCommand($sql7);
						if($comm7->execute()){  //更新该红包成功，返回本次得到的结果值
							return ($redChoose['money']);
						}
					}
					return 0;
				}
			}
        }
        
}
