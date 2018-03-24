<?php
/*
 * @version e入伙物业费
 * 
 * @copyright(c) 2015-04-04 josen
 */

class OthersEnterPropertyFees extends OthersFees
{
    /**
     * @var string 模型名
     */
    public $modelName = '缴物业费';
    public $objectLabel = '物业费';
    public $objectModel = 'PropertyFees';

    static $fees_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        // Item::FEES_RECHARGEING => "充值中",
        Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_FAIL => '交易失败',
        Item::FEES_TRANSACTION_REFUND => '退款',
        Item::FEES_TRANSACTION_LACK =>'红包余额不足',
        Item::FEES_CANCEL => "订单已取消",
    );

    public $build;
    public $room;
    public $customer_name;
    public $username;
    public $mobile;
    public $community_name;
    public $startTime;
    public $endTime;
    public $colorcloud_order;
    //以下字段仅供搜索用
    public $communityIds = array(); //小区
    public $region; //地区
    public $community_id;
    public $pay_sn;

    public static function model($className = __class__)
    {
        return parent::model($className);
    }

    public function rules()
    {

        $array = array(
            //array('build_id,community_id,mobile,room', 'required'),
            array(
                'region,communityIds,pay_sn,community,region,username,colorcloud_order,startTime,endTime,build, room,customer_id,customer_name,sn,status,mobile,community_name',
                'safe',
                'on' => 'search'),);
        return CMap::mergeArray(parent::rules(), $array);
    }

    public function attributeLabels()
    {
        $array = array(
            'id' => 'ID',
            'build' => '楼栋号',
            'room' => '房间号',
            'customer_id' => '用户姓名',
            'customer_name' => '业主名',
            'username' => '用户名',
            'community_name' => '小区',
            'colorcloud_building' => '收费系统楼栋ID',
            'colorcloud_unit' => '收费系统单位ID',
            'colorcloud_order' => '收费系统订单号',
            'colorcloud_bills' => '收费系统帐单号',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'communityIds' => '小区',
            'region' => '地区',
            'pay_sn'=>'支付单号'
        );
        return CMap::mergeArray(parent::attributeLabels(), $array);
    }

    public function getPropertyStatus()
    {
        return CMap::mergeArray(array('' => '全部'), self::$fees_status);
    }

    public function getMobile()
    {
        return empty($this->customer) ? "" : $this->customer->mobile;
    }


    public function getColorcloudBills()
    {
        return empty($this->PropertyFees) ? "" : $this->PropertyFees->colorcloud_bills;
    }

    public function getRoomName()
    {
        $model = $this->objectModel;
        return empty($this->$model->room) ? '' : $this->$model->room;
    }

    public function getCommunityId()
    {
        $model = $this->objectModel;
        return empty($this->$model->community_id) ? '' : $this->$model->community_id;
    }

    public function getBuildName()
    {
        $model = $this->objectModel;
        return empty($this->$model->build) ? '' : $this->$model->build;
    }

    public function getCustomer_name()
    {
        $model = $this->objectModel;
        return empty($this->$model->customer_name) ? '' : $this->$model->customer_name;
    }

    public function getColorcloudBuilding()
    {
        $model = $this->objectModel;
        return empty($this->$model->colorcloud_building) ? '' : $this->$model->
            colorcloud_building;
    }

    public function getColorcloudUnit()
    {
        $model = $this->objectModel;
        return empty($this->$model->colorcloud_unit) ? '' : $this->$model->
            colorcloud_unit;
    }

    public function getColorcloudOrder()
    {
        $model = $this->objectModel;
        return empty($this->$model->colorcloud_order) ? '' : $this->$model->
            colorcloud_order;
    }


    /**
     * 创建物业费订单
     * 参数说明：1、feeAttr:OthersFees模型的属性集合，2、propertyAttr:PropertyFees模型的属性集合
     * 返回值 boolean, true:创建成功，false:创建失败
     * yichao by 2013年8月8日 10:37:50
     * */
    public static function createPropertyOrder($feeAttr, $propertyAttr)
    {
        if (empty($feeAttr) || empty($propertyAttr)) {
            OthersPropertyFees::model()->addError('id', "创建物业费订单失败！");
            return false;
        }

        //引入彩之云的接口
        Yii::import('common.api.ColorCloudApi');
        //实例化
        $coloure = ColorCloudApi::getInstance();

        //创建我们的物业费订单前，需要先创建彩之云的订单，
        //创建彩之云订单前写系统日志，记录参数
        Yii::log('彩之云下订单：传出参数  ,colorcloud_id ' . $propertyAttr['colorcloud_id'] .
            '  ,colorcloud_building ' . $propertyAttr['colorcloud_building'] .
            '  ,colorcloud_unit ' . $propertyAttr['colorcloud_unit'] .
            '  ,colorcloud_bills ' . $propertyAttr['colorcloud_bills'],
            CLogger::LEVEL_INFO,
            'colourlife.core.OthersPropertyFees.createPropertyOrder');

        //使用彩之云的接口创建彩之云的缴费订单
        $result = $coloure->callGetOrderCreate($propertyAttr['colorcloud_id'], $propertyAttr['colorcloud_building'],
            $propertyAttr['colorcloud_unit'], $propertyAttr['colorcloud_bills'], '');
        //系统日志记录彩之云订单创建情况
        Yii::log('彩之云下订单返回值：' . var_export($result, true), CLogger::LEVEL_INFO,
            'colourlife.core.OthersPropertyFees.createPropertyOrder');

        //处理彩之云订单返回结果
        if (!isset($result) || $result['data'][0]['state'] != 1) {
            Yii::log('彩之云订单创建失败！返回信息：' . $result['data'][0]['msg'], CLogger::LEVEL_INFO,
                'colourlife.core.OthersPropertyFees.createPropertyOrder');
            OthersPropertyFees::model()->addError('id', $result['data'][0]['msg']);
            return false;
            //throw new CHttpException(400, '彩之云订单创建失败！');
        }
        //如果收费系统返回的金额为0,那么不创建我们的订单。直接报错
        if($result['data'][0]['orderamount']<=0){
            Yii::log('彩之云订单创建成功！彩之云订单号:'.$result['data'][0]['orderid'].'，但返回金额为'.$result['data'][0]['orderamount'].',不在创建我们的订单！',
                CLogger::LEVEL_INFO,'colourlife.core.OthersPropertyFees.createPropertyOrder');
            OthersPropertyFees::model()->addError('id', "彩之云收费系统异常,创建订单失败！".$result['data'][0]['msg']);
            return false;
        }

        //创建我们的订单记录及物业费缴费记录
        $other = new OthersFees();
        //用彩之云返回的订单金额替换我们计算的金额
        $oldAmount = $feeAttr['amount'];

        $feeAttr['amount'] = $result['data'][0]['orderamount'];
        //银行支付金额=收费系统返回的金额-我们使用的红包抵扣金额
        $bank_pay = $result['data'][0]['orderamount']-$feeAttr['red_packet_pay'];
        $feeAttr['bank_pay'] = $bank_pay < 0 ? 0 : $bank_pay;//银行支付不能小于0
        $other->attributes = $feeAttr;

        Yii::log("创建彩之云订单成功，使用彩之云返回金额替换我们订单的金额。物业费订单:{$other->sn},彩之云订单:{$result['data'][0]['orderid']},我们的订单金额	:{$oldAmount},替换后的金额:{$feeAttr['amount']}", CLogger::LEVEL_INFO, 'colourlife.core.propertyfees.create');

        $propertyAttr['colorcloud_order'] = $result['data'][0]['orderid'];
        $model = new PropertyFees();
        $model->attributes = $propertyAttr;

        if ($model->save()) { //先创建物业费记录，得到记录ID再创建订单记录
            $other->object_id = $model->id;
            if (!$other->save()) {
                OthersPropertyFees::model()->addError('id', "创建物业费订单失败！".json_encode($other->getErrors()));
                //如果订单创建失败，删除物业费记录
                @$model->delete();
                return false;
            }
        } else {
            OthersPropertyFees::model()->addError('id', "创建物业费订单失败！".json_encode($model->getErrors()));
            return false;
        }
        //写订单成功日志
        Yii::log("物业费下单：{$other->sn},金额:{$other->amount},用户：{$other->customer_id}",
            CLogger::LEVEL_INFO, 'colourlife.core.propertyfees.create');

        //写订单日志
        OthersFeesLog::createOtherFeesLog($other->id, 'Customer', Item::FEES_AWAITING_PAYMENT, ($other->customer_id . '物业费下单'));

        //返回结果
        return true;

    }
	
   /**
     * 更新历史缴费订单
     * 参数说明：
     * 返回值 boolean, true:创建成功，false:创建失败
     * */
    public static function updateFeeOrder($sn, $feeAttr, $customer)
    {
        //判断参数
        if (empty($feeAttr) || empty($customer)) {
            OthersPropertyFees::model()->addError('id', "修改停车费订单失败！");
            return false;
        }
		$other = OthersPropertyFees::model()->find('sn=:sn',array(':sn'=>$sn));
		
		$amount = $other->amount;
		$sn = $other->sn;
        $other->attributes = $feeAttr;
		if (!$other->update()) {
			//OthersPropertyFees::model()->addError('id', "更新单订单失败！");
			OthersPropertyFees::model()->addError('id', "更新物业费订单失败！".json_encode($other->getErrors()));
			//如果订单创建失败，删除预缴费费记录
			return false;
		}
			
		//写订单成功日志
        Yii::log("物业费下单：{$other->sn},金额:{$other->amount},用户：{$other->customer_id}",
            CLogger::LEVEL_INFO, 'colourlife.core.propertyfees.updateFeeOrder');

        //写订单日志
        OthersFeesLog::createOtherFeesLog($other->id, 'Customer', Item::FEES_AWAITING_PAYMENT, ($other->customer_id . '物业费下单'));	
        return true;
    }
	
	

    public function callColorcloudOrder($order_id, $state, $note)
    {
        $propertyFee = self::model()->findByPk($order_id);
        Yii::import('common.api.ColorCloudApi');
        $colour = ColorCloudApi::getInstance();

        $send_params = '回调函数彩之云订单' . $propertyFee->sn . '发送参数 ColorcloudOrder:' . $propertyFee->colorcloudOrder . ', chantOrderAmt:' . $propertyFee->amount;

        $testCommunityId = Yii::app()->config->testCommunityId; //得到测试小区ID

        if (empty($testCommunityId) || $testCommunityId != $propertyFee->communityId) {
            Yii::log('调用彩之云接口修改彩之云订单' . $propertyFee->sn . '状态，' . $send_params, CLogger::LEVEL_INFO, 'colourlife.core.colorcloud.OthersPropertyFees');
            if ($propertyFee->status == Item::FEES_TRANSACTION_SUCCESS) {
                Yii::log('支付再次回调彩之云订单' . $propertyFee->sn . '函数,当前状态:' . $propertyFee->status, CLogger::LEVEL_INFO,
                    'colourlife.core.colorcloud.OthersPropertyFees');
                return false;
            }

            //彩之云接口
            //$result = $colour->callGetPayOrder($propertyFee->colorcloudOrder, $propertyFee->amount, '彩生活交易号：' . $propertyFee->sn);
            $result = $colour->callGetPayOrder(
                $propertyFee->colorcloudOrder,
                $propertyFee->amount,
                '彩生活交易号：' . $propertyFee->sn,
                $propertyFee->paymentNames,
                $propertyFee->bank_pay,
                $propertyFee->red_packet_pay
            );
            Yii::log('调用彩之云接口修改彩之云订单' . $propertyFee->sn . '状态的返回值：' . var_export($result, true), CLogger::LEVEL_INFO,
                'colourlife.core.colorcloud.OthersPropertyFees');

            if (!isset($result) || $result['total'] <= 0) { //调用接口修改状态成功
                $state = Item::FEES_TRANSACTION_FAIL;
            } else {
                $state = Item::FEES_TRANSACTION_SUCCESS;
                //写入房间号
                PropertyFeeLog::createFeeLog($propertyFee->customer_id,$propertyFee->colorcloudUnit);
            }

            $propertModel = OthersFees::model()->findByPk($order_id);
            if ($propertModel->status == Item::FEES_TRANSACTION_SUCCESS) {
                Yii::log('支付再次回调彩之云订单' . $propertyFee->sn . '函数,当前状态:' . $propertyFee->status . ",已成功，不修改为其他状态！", CLogger::LEVEL_INFO,
                    'colourlife.core.colorcloud.OthersPropertyFees');
                return false;
            }

            //修改我们的物业费订单状态
            OthersFees::model()->updateByPk($order_id, array('status' => $state));
        } else {
            //测试小区不调用接口
            Yii::log("测试小区不调用接口，不修改彩之云订单' . $propertyFee->sn . '状态, 系统订单ID:" . $propertyFee->colorcloudOrder . "缴费金额:" . $propertyFee->amount . " 测试小区ID" . $testCommunityId,
                CLogger::LEVEL_INFO, 'colourlife.core.colorcloud.PayOrder');
        }
        //写我们的订单日志
        OthersFeesLog::createOtherFeesLog($order_id, 'Customer', $state, $note);
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('model', $this->objectModel, true); //设置条件
        $criteria->join = "JOIN property_fees as pf ON (pf.id = t.object_id and  t.model='{$this->objectModel}' )";
        $criteria->compare('sn', $this->sn, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.payment_id', $this->payment_id);
        $criteria->addInCondition('t.status',$this->getMyStatusList());
        if ($this->customer_id != '' || $this->mobile != '' || $this->username != '') {
            $criteria->with[] = 'customer';
            if ($this->mobile != '') {
                $criteria->compare('customer.mobile', $this->mobile);
            }
            if ($this->customer_id != '') {
                $criteria->compare('customer.name', $this->customer_id, true);
            }

            if ($this->username != '') {
                $criteria->compare('customer.username', $this->username, true);
            }

        }

        if ($this->create_time != '') {
            $criteria->compare('t.create_time', $this->create_time);
        }

        if ($this->startTime != '') {

            $criteria->compare("t.create_time", ">=" . strtotime($this->startTime));
        }

        if ($this->endTime != '') {

            $criteria->compare("t.create_time", "< " . strtotime($this->endTime));

        }

        $criteria->with[] = $this->objectModel;

        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        $branchIds = $employee->mergeBranch;
        //选择的组织架构ID
        if ($this->branch_id != '')
            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
        else if (!empty($this->communityIds)) //如果有小区
            $community_ids = $this->communityIds;
        else if ($this->region != '') //如果有地区
            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
        else {
            $community_ids = array();
            foreach ($branchIds as $branchId) {
                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
                $community_ids = array_unique(array_merge($community_ids, $data));
            }
        }

        $criteria->addInCondition($this->objectModel . '.community_id', $community_ids);

        if ($this->room != '') {
            $criteria->compare($this->objectModel . '.room', $this->room, true);
        }

        if ($this->colorcloud_order != '') {
            $criteria->compare($this->objectModel . '.colorcloud_order', $this->colorcloud_order, true);
        }

        if ($this->build != '') {

            $criteria->compare($this->objectModel . '.build', $this->build, true);
        }
        if ($this->customer_name != '') {
            $criteria->compare($this->objectModel . '.customer_name', $this->customer_name, true);
        }

        if ($this->community_name != '') {
            $community = Community::model()->findAll("name like :name", array(':name' => "%" . $this->community_name . "%"));
            $communityArr = array();
            foreach ($community as $key => $value) {
                $communityArr[] = $value->id;
            }
            $criteria->addInCondition($this->objectModel . '.community_id', $communityArr);
        }
        if($this->pay_sn!=""){
            $pay=Pay::model()->getModel($this->pay_sn);
            if(!empty($pay)){
                $pay_id=$pay->id;
                $criteria->compare("`pay_id`",$pay_id);
            }else{
                $criteria->compare("`pay_id`","-1");
            }

        }

        return new ActiveDataProvider($this, array('criteria' => $criteria, 'sort' =>
            array('defaultOrder' => '`t`.create_time desc',)));
    }

    public function getBranchName()
    {
        if (isset($this->customer))
            if (isset($this->customer->community))
                if (isset($this->customer->community->branch)) {
                    //return $this->customer->community->branch->name;
                    return implode(' ', $this->getMyBranch($this->customer->community->branch->id));
                }
    }

    public function getRegionName()
    {
        if (isset($this->customer))
            if (isset($this->customer->community))
                if (isset($this->customer->community->region)) {
                    //return $this->customer->community->region->name;
                    return $this->myRegion($this->customer->community->region->id);
                }
        return "";
    }

    public function myRegion($id)
    {
        return implode(' ', F::getRegion($id));
    }

    public function getMyBranch($id)
    {
        return Branch::model()->getAllBranch($id);
    }

    public function getCommunityHtml()
    {
        return CHtml::tag('span', array('rel' => 'tooltip',
                'data-original-title' => '地域:' . $this->regionName . '  部门:' . $this->branchName),
            $this->communityName);
    }


    public function getMyStatusList(){
        $return  = array();

        if(Yii::app()->user->checkAccess('op_backend_propertyFees_awaiting')){
            $return[] = Item::FEES_AWAITING_PAYMENT;
        }

        if(Yii::app()->user->checkAccess('op_backend_propertyFees_success')){
            $return[] = Item::FEES_TRANSACTION_SUCCESS;
        }

        if(Yii::app()->user->checkAccess('op_backend_propertyFees_payment')){
            $return[] = Item::FEES_TRANSACTION_ERROR;
        }

        if(Yii::app()->user->checkAccess('op_backend_propertyFees_refund')){
            $return[] = Item::FEES_TRANSACTION_REFUND;
        }

        if(Yii::app()->user->checkAccess('op_backend_propertyFees_fail')){
            $return[] = Item::FEES_TRANSACTION_FAIL;
        }

        if(Yii::app()->user->checkAccess('op_backend_propertyFees_cancel')){
            $return[] = Item::FEES_CANCEL;
        }

        if(Yii::app()->user->checkAccess('op_backend_propertyFees_redFail')){
            $return[] = Item::FEES_TRANSACTION_LACK;
        }
        return $return;
    }

   public function getStatusList(){
       $return = array(''=>'全部');
       $data = OthersFeesStatus::StatusList('property');
       
       foreach ($this->getMyStatusList() as $key=>$value ){
           $return[$value] =$data[$value]; 
       }
       return $return;
   }

    /**
     * API计算单元预缴费金额
     * @param $unitid      收费单元ID
     * @param $month        月数，1年=12
     * @return int
     */
    public function getApiPropertyPayfee($unitid,$month){
        if(empty($unitid) || empty($month)){
            OthersAdvanceFees::model()->addError('id', "计算单元预缴费金额失败！");
        }else{
            //引入彩之云的接口
           
            Yii::import('common.api.ColorCloudApi');
            //实例化
            $coloure = ColorCloudApi::getInstance();
            
            $result = $coloure->callGetPropertyPayfee($unitid,$month);
           
            //如果未找到预缴费
            if(empty($result['data'])){
                OthersPropertyFees::model()->addError('id', "计算单元预缴费金额失败！");
                $result['data'] = array();
            }

            return $result['data'];

        }
    }

}
