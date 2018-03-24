<?php

/**
 * This is the model class for table "perfect_crab_outcome".
 *
 * The followings are the available columns in table 'perfect_crab_outcome':
 * @property string $id
 * @property integer $crab_id
 * @property string $mycode
 * @property string $customer_id
 * @property string $customer_ip
 * @property string $linkman
 * @property string $address
 * @property string $tel
 * @property integer $is_deleted
 * @property integer $is_right
 * @property string $create_time
 * @property integer $state
 * @property string $express_company
 * @property string $tracking_number
 */
class PerfectCrabOutcome extends CActiveRecord
{	
	public $modelName = "完美蟹逅结果名单";

	public $customer_name;
    public $customer_mobile;
    public $activity_name;
    
    const STATE_WAIT = 0;      //等待发货
    const STATE_SEND = 1;      //已发货
    const STATE_RECEIVED = 2;   //已收货

    const IS_RIGHT_UNKNOWN = 0;   //未知
    const IS_RIGHT_YES = 1;       //竞猜正确
    const IS_RIGHT_NO = 2;      //竞猜错误


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'perfect_crab_outcome';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('crab_id, is_deleted, is_right, state', 'numerical', 'integerOnly'=>true),
			// array('mycode', 'length', 'is'=>5),
			array('customer_id, create_time', 'length', 'max'=>10),
			array('customer_ip', 'length', 'max'=>20),
			array('linkman', 'length', 'max'=>256),
			array('address', 'length', 'max'=>512),
			array('tel', 'length', 'max'=>18),
			array('express_company', 'length', 'max'=>128),
			array('tracking_number', 'length', 'max'=>32),
			array('crab_id,mycode,customer_ip,customer_id,create_time','required','on' => 'create'),
            array('express_company,tracking_number,state,linkman,address,tel','safe','on' => 'update'),
			array('id, crab_id, mycode, customer_id, customer_ip, linkman, address, tel, is_deleted, is_right, create_time, state, express_company, tracking_number,customer_name,customer_mobile', 'safe', 'on'=>'search'),
		);
	}



	public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }
    
    
    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'set_perfect_crab' => array(self::BELONGS_TO, 'SetPerfectCrab', 'crab_id'),
        );
    }



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'crab_id' => '完美蟹逅活动期数id',
			'mycode' => '我的抽奖号码',
			'customer_id' => '业主Id',
			'customer_ip' => 'IP地址',
			'linkman' => '收件人',
			'address' => '收货地址',
			'tel' => '联系电话',
			'is_deleted' => '标记删除',
			'is_right' => '是否中奖',//0未知（未出结果）1中奖 2未中奖
			'create_time' => '创建时间',
			'state' => '发货状态',  //（发货状态）0待发货 1已发货 2已收货
			'express_company' => '快递公司',
			'tracking_number' => '快递单号',
			'ActivityName' => '完美蟹逅活动',
            'customer_name' => '业主',
            'customer_mobile' => '业主手机',
            'CustomerName' => '业主',
            'CustomerMobile' => '业主手机号',
            'CustomerAddress'=> '业主地址',
            'activity_name' => '活动期数',
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
		$criteria->compare('crab_id',$this->crab_id);
		$criteria->compare('mycode',$this->mycode,true);
		$criteria->compare('linkman',$this->linkman,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('tel',$this->tel,true);
		if($this->customer_name || $this->customer_mobile){
            $criteria->with=array('customer');
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }        
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('is_right',$this->is_right);
		$criteria->compare('state',$this->state);
		$criteria->compare('express_company',$this->express_company,true);
		$criteria->compare('tracking_number',$this->tracking_number,true);
		$criteria->order = 't.id DESC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function getActivityName(){
        return $this->set_perfect_crab?$this->set_perfect_crab->activity_name:"";
    }
    
    public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }
    
    
    
    public function getSTATENames(){
        return array(
            self::STATE_WAIT => '等待发货',
            self::STATE_SEND => '已发货',
            self::STATE_RECEIVED => '已收货',
        );
    }    


    
    public function getSTATEName($html = false){
        $res = '';
        $res .= ($html) ? '<span class="label label-success">' : '';
        $res .= $this->STATENames[$this->state];
        $res .= ($html) ? '</span>' : '';
        return $res;        
    }
    
    public function getCustomerAddress(){
        if($this->customer){
            return $this->customer->CommunityAddress."-".$this->customer->BuildName."-".$this->customer->room;
        }else{
            return "";
        }
    }


    public function getRightSTATUSNames(){
        return array(
            self::IS_RIGHT_UNKNOWN => '未开奖',
            self::IS_RIGHT_YES => '中奖',
            self::IS_RIGHT_NO => '未中奖',
        );
    }



    //中奖状态
   	public function getRightSTATUS($html = false){
        switch ($this->is_right){
            case self::IS_RIGHT_UNKNOWN:
                return ($html) ? "未开奖" : "<span class='label label-important'>未开奖</span>";
                break;
            case self::IS_RIGHT_NO:
                return ($html) ? "没有中奖" : "<span class='label label-important'>没有中奖</span>";
                break;
            case self::IS_RIGHT_YES:
                return ($html) ? "中奖" : "<span class='label label-success'>中奖</span>";
                break;
            default :
                return ($html) ? "N/A" : "<span class='label label-important'>N/A</span>";
                break;
        }
    }


    //完美蟹逅
    public function rob($set_perfectcrab_id,$customer_id){
    	$arr = array();
        if($this->isAbleRob($set_perfectcrab_id)){         
                //指定组织架构(如:体验小区)，返回false(已经抢光了)
                $customerModel=Customer::model()->findByPk($customer_id);
                if(!$customerModel){
                	$arr['ok'] = 0;
                    return $arr;    //没抢到
                }
                if(!$customerModel->community>0){
                    $arr['ok'] = 0;
                    return $arr;	//没抢到
                }
		        // $branchId=$customerModel->community->branch->id;
          //       if(in_array($branchId,Item::$lucky_tiyan_branch_ids)){
          //           $arr['ok'] = 0;
          //           return $arr;    //没抢到
          //       }
                if($customerModel->getBalance()<0.1){
                    $arr['ok'] = 3;
                    return $arr;    //红包余额不足
                }
                if(!$mycode=$this->getCode()){
                    $arr['ok'] = 2;
                    return $arr;    //抢光了
                }else{
                    //消费红包
                    $redPacket=new RedPacket();
                    $attr['customer_id']=$customer_id;
                    $attr['type'] = Item::RED_PACKET_TYPE_CONSUME;//设置属性为消费
                    $attr['sum']=0.1;
                    $attr['to_type']=Item::RED_PACKET_TO_TYPE_ROB_PERFECT_CRAB;
                    $attr['sn']=4;
                    $attr['note']= "【完美蟹逅活动】消费红包【{$attr['sum']}】元";

                    $balance = ($customerModel->balance-$attr['sum']);
                    $redPacket->setAttributes($attr);
                    $redPacket->validate();
                    if(!$redPacket->save() or !Customer::model()->updateByPk($customer_id,array('balance'=>$balance)) ){
                        $arr['ok'] = 0;
                    	return $arr;;	//没抢到
                    }
                }
                             
                $ip = Yii::app()->request->userHostAddress;
                $insert_sql = "insert into perfect_crab_outcome (crab_id,mycode,customer_id,customer_ip,create_time) values ('".
                    $set_perfectcrab_id."','".$mycode."','".$customer_id."','".$ip."','".time()."');";
                $res = Yii::app()->db->createCommand($insert_sql)->execute();
                if($res){
					$arr['ok'] = 1;
					$arr['code'] = $mycode;
                    return $arr; //抢到了
                }else{
                	$arr['ok'] = 5;
                	return $arr; //没抢到
                }
        }else{
            $arr['ok'] = 0;
            return $arr; //没抢到
        }
    }
    
    //判断是否可以抢，返回值true代表能抢  false代表不能抢
    public function isAbleRob($set_perfectcrab_id){
       $setperfectcrab = SetPerfectCrab::model()->findByPk($set_perfectcrab_id);
       if($setperfectcrab && strtotime($setperfectcrab->end_time) >= time()){
            return true;
       }else{
       		return false;
       }
    }


    //生成随机不重复的五位数字号码       
    public function getCode(){
        $code = '';
        $flag = true;
        $i=0;
        while ($flag && $i<=200) {            
            $code = F::random(5,true);
            $count = DrawCode::model()->find('code=:code', array(':code' => $code));                    
            if($count && $i!=200){
                $i++;
                continue;   
            }

            if($count && $i==200){
                return false;                          
            }

            // $drawcode = $code;
            $flag = false;

        }
        $model = new DrawCode();
        $model->code = $code;
        $model->save();
        return $code;
    }


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PerfectCrabOutcome the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function is_send($var,$res){
        if(self::IS_RIGHT_YES==$var && self::STATE_WAIT==$res){
            return true;
        }else{
            return false;
        }
    }

    public function getFullDrawCode($code){
    	if(strlen($code)==5){
    		return $code;
    	}else{
    		return sprintf('%05s', $code);
    	}
    }
}
