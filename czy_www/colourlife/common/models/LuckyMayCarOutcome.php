<?php

/**
 * This is the model class for table "lucky_may_car_outcome".
 *
 * The followings are the available columns in table 'lucky_may_car_outcome':
 * @property string $id
 * @property integer $act_id
 * @property integer $mycode
 * @property string $customer_id
 * @property string $linkman
 * @property string $address
 * @property string $tel
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $is_right
 * @property string $create_time
 * @property string $customer_ip
 */
class LuckyMayCarOutcome extends CActiveRecord
{	
	public $modelName = "获得汽车抽奖码名单";

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
		return 'lucky_may_car_outcome';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('act_id,mycode,customer_id,create_time','required','on' => 'create'),
            array('state,linkman,address,tel','safe','on' => 'update'),
			array('act_id, mycode, state, is_deleted, is_right', 'numerical', 'integerOnly'=>true),
			array('customer_id, create_time', 'length', 'max'=>10),
			array('linkman', 'length', 'max'=>256),
			array('address', 'length', 'max'=>512),
			array('tel', 'length', 'max'=>18),
			array('customer_ip', 'length', 'max'=>20),
            array('type', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
            array('id, act_id, mycode, customer_id, customer_ip, linkman, address, tel, is_deleted, is_right, create_time, state,customer_name,customer_mobile', 'safe', 'on'=>'search'),
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


	/**
	 * @return array relational rules.
	 */
	public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'luckyAct' => array(self::BELONGS_TO, 'LuckyActivity', 'act_id'),
        );
    }



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'act_id' => '幸福活动ID',
			'mycode' => '我的抽奖号码',
			'customer_id' => '业主Id',
			'linkman' => '收件人',
			'address' => '收货地址',
			'tel' => '联系电话',
			'state' => '状态',
			'is_deleted' => '标记删除',
			'is_right' => '是否中奖',
			'create_time' => '创建时间',
			'customer_ip' => 'IP地址',
			'ActivityName' => '活动名称',
            'customer_name' => '业主',
            'customer_mobile' => '业主手机',
            'CustomerName' => '业主',
            'CustomerMobile' => '业主手机号',
            'CustomerAddress'=> '业主地址',
            'activity_name' => '活动名称',
            'type' => '来源',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('act_id',$this->act_id);
		$criteria->compare('mycode',$this->mycode);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('linkman',$this->linkman,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('is_right',$this->is_right);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('customer_ip',$this->customer_ip,true);
        $criteria->compare('type',$this->type,true);
		if($this->customer_name || $this->customer_mobile){
            $criteria->with[]='customer';
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }  

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyMayCarOutcome the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function getActivityName(){
        return $this->luckyAct?$this->luckyAct->name:"";
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


    //生成汽车抽奖码
    public function rob($act_id,$customer_id){
    	$arr = array();
        if($this->isAbleRob($act_id)){

                //指定组织架构(如:体验小区),返回false(已经抢光了)
                $customerModel=Customer::model()->findByPk($customer_id);
                if(!$customerModel){
                	$arr['ok'] = 0;
                    return $arr;    //没抢到
                }
                // if(!$customerModel->community>0){
                //     $arr['ok'] = 0;
                //     return $arr;	//没抢到
                // }

		        // $branchId=$customerModel->community->branch->id;
          //       if(in_array($branchId,Item::$lucky_tiyan_branch_ids)){
          //           $arr['ok'] = 0;
          //           return $arr;    //没抢到
          //       }
                if($customerModel->getBalance()<0.1){
                    $arr['ok'] = 3;
                    return $arr;    //红包余额不足
                }
                if(!$mycode=$this->getCarCode()){
                    $arr['ok'] = 2;
                    return $arr;    //抢光了
                }else{
                    //消费红包
                    $redPacket=new RedPacket();
                    $attr['customer_id']=$customer_id;
                    $attr['type'] = Item::RED_PACKET_TYPE_CONSUME;//设置属性为消费
                    $attr['sum']=0.1;
                    $attr['to_type']=Item::RED_PACKET_TO_TYPE_ROB_CAR_MAY;
                    $attr['sn']='rob_car';
                    $attr['note']= "【抽到汽车抽奖码】消费红包【{$attr['sum']}】元";
                    $balance = ($customerModel->getBalance()-$attr['sum']);
                    $redPacket->setAttributes($attr);
                    $redPacket->validate();

                    $ip = Yii::app()->request->userHostAddress;
                    $type = 'lucky';
                    $insert_sql = "insert into lucky_may_car_outcome (act_id,mycode,customer_id,customer_ip,create_time,type) values ('".
                        $act_id."','".$mycode."','".$customer_id."','".$ip."','".time()."','".$type."');";
                    // $res = Yii::app()->db->createCommand($insert_sql)->execute();

                    if(!$redPacket->save() or !Customer::model()->updateByPk($customer_id,array('balance'=>$balance)) or !Yii::app()->db->createCommand($insert_sql)->execute() ){
                        $arr['ok'] = 0;
                    	return $arr;	//没抢到
                    }else{
                        $arr['ok'] = 1;
                        $arr['code'] = $mycode;
                        return $arr; //抢到了
                    }
                }
                             
                // $ip = Yii::app()->request->userHostAddress;
                // $type = 'lucky';
                // $insert_sql = "insert into lucky_may_car_outcome (act_id,mycode,customer_id,customer_ip,create_time,type) values ('".
                //     $act_id."','".$mycode."','".$customer_id."','".$ip."','".time()."','".$type."');";
                // $res = Yii::app()->db->createCommand($insert_sql)->execute();
     //            if($res){
					// $arr['ok'] = 1;
					// $arr['code'] = $mycode;
     //                return $arr; //抢到了
     //            }else{
     //            	$arr['ok'] = 5;
     //            	return $arr; //没抢到
     //            }
        }else{
            $arr['ok'] = 0;
            return $arr; //没抢到
        }
    }


    
    //判断是否可以抢，返回值true代表能抢  false代表不能抢
    public function isAbleRob($act_id){
       $luckyAct = LuckyActivity::model()->findByPk($act_id);
       if($luckyAct && ($luckyAct->end_date) >= date('Y-m-d') && ($luckyAct->start_date) <= date('Y-m-d')){
            return true;
       }else{
       		return false;
       }
    }


    //生成随机不重复的十位数字号码       
    public function getCarCode(){
        $code='';
        $flag=true;
        $i=0;
        while ($flag && $i<=100) {            
            $code = F::random(10,true);
            $count = LuckyCarCode::model()->find('code=:code', array(':code' => intval($code)));
            if($count && $i!=100){
                $i++;continue;
            }
            if($count && $i==100){
                return false;
            }
            $flag = false;
        }
        $model = new LuckyCarCode();
        $model->code = intval($code);
        $model->save();
        return $code;
    }




    public function is_send($var,$res){
        if(self::IS_RIGHT_YES==$var && self::STATE_WAIT==$res){
            return true;
        }else{
            return false;
        }
    }

    public function getFullDrawCode($code){
    	if(strlen($code)==10){
    		return $code;
    	}else{
    		return sprintf('%010s', $code);
    	}
    }
}
