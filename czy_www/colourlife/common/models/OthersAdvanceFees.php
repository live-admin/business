<?php

/**
 * This is the model class for table "others_fees".
 *
 * The followings are the available columns in table 'others_fees':
 * @property integer $id
 * @property string $sn
 * @property string $model
 * @property integer $object_id
 * @property integer $customer_id
 * @property integer $payment_id
 * @property string $amount
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $pay_time
 * @property integer $status
 * @property integer $pay_rate
 */
class OthersAdvanceFees extends CActiveRecord
{
    static $fees_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_REFUND => '退款',
        Item::FEES_TRANSACTION_LACK =>'红包余额不足',
        Item::FEES_TRANSACTION_FAIL => '交易失败',
        Item::FEES_CANCEL => "订单已取消",
    );

    /**
     * @var string 模型名
     */
    public $modelName = '预缴费订单';
    public $objectLabel = '预缴费订单';
    public $objectModel = 'advanceFee';
    public $branch_id;
    public $build;
    public $room;
    public $customerName;
    public $mobile;
    public $region;
    public $communityIds;
    public $startTime;
    public $endTime;
    public $rate;//测试BUG（报rate未定义）
    public $pay_sn;

	public $province_id;
	public $city_id;
	public $district_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OthersFees the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'others_fees';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('object_id, customer_id, payment_id, create_time,pay_time, status', 'numerical', 'integerOnly' => true),
            array('sn', 'length', 'max' => 32),
            array('model, create_ip', 'length', 'max' => 20),
            array('amount', 'length', 'max' => 10),
            array('amount', 'match', 'pattern' => '/^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/', 'message' => '错误的预缴费金额。'),
            array('bank_pay,red_packet_pay,user_red_packet,note,rate', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('startTime,endTime,model,branch_id,payment_id,customerName,build,room,communityIds,mobile,region,sn,status,rate,pay_sn', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
            'logs' => array(self::HAS_MANY, 'OthersFeesLog', 'others_fees_id'),
            'advanceFee' => array(self::BELONGS_TO, 'AdvanceFee', 'object_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sn' => '订单号',
            'object_id' => "预缴费",
            'payment_id' => '支付方式',
            'amount' => '金额',
            'note' => '备注',
            'create_ip' => '创建IP',
            'create_time' => '创建时间',
            'pay_time' => '支付时间',
            'objectName' => '预缴费',
            'status' => '状态',
            'community_id' => '小区',
            'customerName' => '业主姓名',
            'mobile' => '缴费人手机号',
            'branch_id' => '管辖部门',
            'build' => '楼栋',
            'room' => '房间号',
            'region' => '地区',
            'communityIds' => '小区',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'red_packet_pay' => '红包抵扣',
            'bank_pay' => '实付',
            'pay_rate' => '费率',
            'pay_sn'=>'支付单号'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('model', 'AdvanceFee', true); //设置条件
        $criteria->compare('payment_id', $this->payment_id);

        $criteria->with[] = 'advanceFee';

        if ($this->room != '') {
            $criteria->compare('advanceFee.room', $this->room, true);
        }

        if ($this->build != '') {
            $criteria->compare('advanceFee.build', $this->build, true);
        }
        if ($this->customerName != '') {
            $criteria->compare('advanceFee.customer_name', $this->customerName, true);
        }

        if ($this->startTime != '') {

            $criteria->compare("create_time", ">=" . strtotime($this->startTime));
        }

        if ($this->endTime != '') {

            $criteria->compare("create_time", "< " . strtotime($this->endTime));

        }

        /*$employee = Employee::model()->findByPk(Yii::app()->user->id);
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
        $criteria->addInCondition('advanceFee.community_id', $community_ids);*/
	    if (Yii::app()->user->getId() != 1) {
		    //选择的组织架构ID
		    if ($this->branch_id != '') {
			    $community_ids = ICEBranch::model()->findByPk($this->branch_id)->ICEGetBranchAllCommunity();
		    } else if (!empty($this->communityIds)) {
			    //如果有小区
			    $community_ids = $this->communityIds;
		    } else if ($this->province_id) {
			    //如果有地区
			    if ($this->district_id) {
				    $regionId = $this->district_id;
			    } else if ($this->city_id) {
				    $regionId = $this->city_id;
			    } else if ($this->province_id) {
				    $regionId = $this->province_id;
			    } else {
				    $regionId = 0;
			    }
			    $community_ids = ICERegion::model()->getRegionCommunity(
				    $regionId,
				    'id'
			    );
		    } else {
			    $employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
			    $community_ids = $employee->ICEGetOrgCommunity();
		    }

		    $criteria->addInCondition('advanceFee.community_id', $community_ids);
	    }

        $criteria->compare('sn', $this->sn, true);
        $criteria->compare('status', $this->status);

        if ($this->mobile != '') {
            $criteria->with[] = 'customer';
            $criteria->compare('customer.mobile', $this->mobile, true);
        }
        $criteria->addInCondition('`t`.status',$this->getStatusCompetence());//查看订单权限判断
        if($this->pay_sn!=""){
            $pay=Pay::model()->getModel($this->pay_sn);
            if(!empty($pay)){
                $pay_id=$pay->id;
                $criteria->compare("`pay_id`",$pay_id);
            }else{
                $criteria->compare("`pay_id`","-1");
            }

        }
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => '`t`.create_time desc',)
        ));
    }

    /**
     * 根据彩之云门牌号得到物业地址的可以预缴费项目
     * @param $unitid
     */
    public static function getAdvanceItem($unitid)
    {
        if (empty($unitid)) {
            OthersAdvanceFees::model()->addError('id', "未知的收费项ID！");
        } else {
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            //引入彩之云的接口
            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetAdvanceItem($unitid);
            if (!isset($result) || empty($result['data'])) {
                OthersAdvanceFees::model()->addError('id', "获取预缴费项目列表失败！");
            } else {
                return $result['data'];
            }
        }
    }

    /**
     * API根据彩之云门牌号得到物业地址的可以预缴费项目
     * @param $unitid
     */
    public static function getApiAdvanceItem($unitid){
        if(empty($unitid)){
            OthersAdvanceFees::model()->addError('id', "未知的收费项ID！");
        }else{
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            //引入彩之云的接口
            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetAdvanceItem($unitid);
            //var_dump($result);
            if (!isset($result) || empty($result['data'])) {
                OthersAdvanceFees::model()->addError('id', "获取预缴费项目列表失败！");
            }
            return $result['data'];

        }
    }

    /**
     * @param $unitid
     * @return int|null
     *
     */
    public function getAdvanceFeeByUnit($unitid)
    {
        if (empty($unitid)) {
            OthersAdvanceFees::model()->addError('id', "未知的收费项ID！");
        } else {
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            //引入彩之云的接口
            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetAdvanceFee($unitid);
            if (!isset($result) || $result['data'][0]['state'] != 1) {
                //如果未找到预缴费
                OthersAdvanceFees::model()->addError('id', "获取用户预缴费金额失败！");
            } else {
                return $result['data'];
            }
        }
    }

    /**
     * API计算单元预缴费金额
     * @param $unitid      收费单元ID
     * @param $month        月数，1年=12
     * @return int
     */
    public function getApiAdvancePayfee($unitid,$month, $uuid='' , $source = ''){
        if(empty($unitid) || empty($month)){
            OthersAdvanceFees::model()->addError('id', "计算单元预缴费金额失败！");
        }else{
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            Yii::import('common.api.IceApi');
            $coloure = IceApi::getInstance();
            //兼容银湾物业
            if($source == 'yinwan'){
                $result = $coloure->getYinWanMonthFee($unitid);
            }else{
                $result = $coloure->callGetAdvancePayfee($unitid,$month, $uuid);
            }

            //如果未找到预缴费
            if(empty($result['data'])){
                OthersAdvanceFees::model()->addError('id', "计算单元预缴费金额失败！");
                $result['data'] = array();
            }

            return $result['data'];

        }
    }



    /**
     * API计算单元预缴费金额
     * @param $unitid      收费单元ID
     * @param $month        月数，1年=12
     * @return int
     */
    public function getApiAdvancePayfeeTest($unitid,$month){
        if(empty($unitid) || empty($month)){
            OthersAdvanceFees::model()->addError('id', "计算单元预缴费金额失败！");
        }else{
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetAdvancePayfeeTest($unitid,$month);

            //如果未找到预缴费
            if(empty($result['data'])){
                OthersAdvanceFees::model()->addError('id', "计算单元预缴费金额失败！");
                $result['data'] = array();
            }

            return $result['data'];

        }
    }

    /**
     * 计算单元预缴费金额
     * @param $unitid 收费单元ID
     * @param $month 月数，1年=12
     * @return int
     */
    public function getAdvancePayfee($unitid, $month)
    {
        if (empty($unitid) || empty($month)) {
            OthersAdvanceFees::model()->addError('id', "计算单元预缴费金额失败！");
        } else {
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetAdvancePayfee($unitid, $month);
            if (!isset($result) || $result['data'][0]['state'] != 1) {
                //如果未找到预缴费
                OthersAdvanceFees::model()->addError('id', "计算单元预缴费金额失败！");
            } else {
                return $result['data'][0]['fee'];
            }
        }
    }

    /**
     * 获得单元的收费记录
     * @param $unitid
     * @return mixed
     */
    public function getPayLog($unitid, $pageSize, $pageIndex)
    {
        if (empty($unitid)) {
            OthersAdvanceFees::model()->addError('id', "获取收费记录失败！");
        } else {
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetPayLog($unitid, $pageSize, $pageIndex);
            return $result;
        }
    }

    /**
     *API 获得单元的收费记录
     * @param $unitid
     * @return mixed
     */
    public function getApiPayLog($unitid,$pagesize=NULL,$pageindex=NULL){
        if(empty($unitid)){
            OthersAdvanceFees::model()->addError('id', "获取收费记录失败！");
        }else{
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetPayLog($unitid,$pagesize,$pageindex);

            if (!isset($result['data'])) {
                //如果未找到预缴费
                OthersAdvanceFees::model()->addError('id', "获取收费记录失败！");
                $result['data'] = array();
            }
            // $result['data'];
            $data["data"]= $result['data'];
            $data["total"]  = $result["total"];
            return  $data;
        }
    }

    /**
     * 获得单元的收费记录详情
     * @param $tollid //收据编号ID
     * @return mixed
     */
    public function getPayLogdetail($tollid)
    {
        if (empty($tollid)) {
            OthersAdvanceFees::model()->addError('id', "获取收费记录详情失败！");
        } else {
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetPayLogdetial($tollid);
            if (!isset($result['data'])) {
                //如果未找到预缴费
                OthersAdvanceFees::model()->addError('id', "获取收费记录详情失败！");
            } else {
                return $result['data'];
            }
        }
    }

    /**
     * API获得单元的收费记录详情
     * @param $tollid  //收据编号ID
     * @return mixed
     */
    public function getApiPayLogdetail($tollid){
        if(empty($tollid)){
            OthersAdvanceFees::model()->addError('id', "获取收费记录详情失败！");
        }else{
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            //引入彩之云的接口
            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetPayLogdetial($tollid);
            if (!isset($result['data'])) {
                //如果未找到预缴费
                OthersAdvanceFees::model()->addError('id', "获取收费记录详情失败！");
                $result['data'] = array();
            }
            return $result['data'];
        }
    }

    /**
     * 创建预缴费订单
     * 参数说明：
     * @feeAttr:OthersFees模型的属性集合
     * @advanceAttr:AdvanceFees模型的属性集合
     * 返回值 boolean, true:创建成功，false:创建失败
     * */
    public static function createAdvanceFeeOrder($feeAttr, $advanceAttr)
    {
        //判断参数
        if (empty($feeAttr) || empty($advanceAttr)) {
        	Yii::log("预缴费下单失败，传递feeAttr参数：".json_encode($feeAttr).',传递advanceAttr参数：'.json_encode($advanceAttr),CLogger::LEVEL_ERROR, 'colourlife.core.advancefee.create');
            OthersAdvanceFees::model()->addError('id', "创建预缴费订单失败！");
            return false;
        }

        //创建我们的订单记录及预缴费记录
        $other = new OthersAdvanceFees();
        $other->attributes = $feeAttr;

        $model = new AdvanceFee();
        $model->attributes = $advanceAttr;

        if ($model->save()) { //先创建预缴费详情记录，得到记录ID再创建订单记录
            $other->object_id = $model->id;
            if (!$other->save()) {
            	Yii::log("预缴费下单失败，传递的参数：".json_encode($feeAttr).',错误信息：'.json_encode($other->getErrors()),CLogger::LEVEL_ERROR, 'colourlife.core.advancefee.create');
                OthersAdvanceFees::model()->addError('id', "创建预缴费订单失败！");
                //如果订单创建失败，删除预缴费费记录
                @$model->delete();
                return false;
            } else {
                $note = ($model->customer_name . '(' . $other->customer_id . ')预缴费下单,金额:' . $other->amount);
                //写订单日志
                OthersFeesLog::createOtherFeesLog($other->id, 'Customer', Item::FEES_AWAITING_PAYMENT, $note);
            }
            Yii::log("预缴费下单：{$other->sn},金额:{$other->amount},用户：{$model->customer_name}({$other->customer_id})",
                CLogger::LEVEL_INFO, 'colourlife.core.advancefee.create');
        } else {
        	Yii::log("AdvanceFee保存失败，传递的参数：".json_encode($advanceAttr).',错误信息：'.json_encode($model->getErrors()),CLogger::LEVEL_ERROR, 'colourlife.core.advancefee.create');
            OthersAdvanceFees::model()->addError('id', "创建预缴费订单失败！");
            return false;
        }
        //写订单成功日志
        Yii::log("预缴费下单：{$other->sn},金额:{$other->amount},用户：{$model->customer_name}({$other->customer_id})",
            CLogger::LEVEL_INFO, 'colourlife.core.advancefee.create');

        //返回结果
        return true;
    }

    /**
     * 预缴费订单支付回调，函数将调用彩之云接口创建ERP的预缴费订单
     * @param $order_id 我们的预缴费订单ID,OtherFees的Id
     * @param $state 订单的状态
     * @param $note 备注
     * @return bool
     */
    public function SetAdvanceSavefee($order_id, $state, $note)
    {
        if (empty($order_id) || empty($state)) {
            return false;
        }
        $advanceFee = OthersFees::model()->findByPk($order_id);
        if (empty($advanceFee)) {
            OthersAdvanceFees::model()->addError('id', "未知的预缴费订单！");
            return false;
        }
        $unitid = empty($advanceFee->AdvanceFees) ? null : $advanceFee->AdvanceFees->colorcloud_unit;
        $payfee = $advanceFee->amount;
        $toller = $advanceFee->sn;
        //判断彩之云需要的必填参数
        if (empty($unitid) || empty($payfee) || empty($toller)) {
            OthersAdvanceFees::model()->addError('id', "创建彩之云预缴费订单失败！");
            return false;
        }
        //引入彩之云的接口
        //Yii::import('common.api.ColorCloudApi');
        //实例化
        //$coloure = ColorCloudApi::getInstance();

        //引入彩之云的接口
        Yii::import('common.api.IceApi');
        //实例化
        $coloure = IceApi::getInstance();

        //创建我们的物业费订单前，需要先创建彩之云的订单，
        //创建彩之云订单前写系统日志，记录参数
        Yii::log('创建彩之云ERP预缴费订单:参数  ,unitid: ' . $unitid . ', payfee: ' . $payfee .
            ', Toller:' . $toller . '，Accountant:系统，Cashier:系统, bewrite ' . $note,
            CLogger::LEVEL_INFO, 'colourlife.core.OthersAdvanceFees.createAdvanceFeeOrder');

        $communityId = empty($advanceFee->AdvanceFees) ? null : $advanceFee->AdvanceFees->community_id;
        $comm = ColorcloudCommunity::model()->find('community_id=:community_id', array(':community_id'=>$communityId));
        $uuid = empty($comm) ? null : $comm->color_community_id;

        //创建彩之云ERP预缴费订
        $result = $coloure->callSetAdvanceSavefee(
            $unitid,
            $payfee,
            $toller,
            "系统",
            "系统",
            $note,
            $advanceFee->paymentNames,
            $advanceFee->sn,
            $advanceFee->red_packet_pay,
            $uuid
        );

        // 通知旧收费系统
        if (isset($result['code']) && $result['code'] != 0) {
            $result = $coloure->callSetAdvanceSavefee(
                $unitid,
                $payfee,
                $toller,
                "系统",
                "系统",
                $note,
                $advanceFee->paymentNames,
                $advanceFee->sn,
                $advanceFee->red_packet_pay
            );
        }

        //系统日志记录彩之云订单创建情况
        Yii::log('创建彩之云ERP预缴费订单返回值：' . var_export($result, true), CLogger::LEVEL_INFO,
            'colourlife.core.OthersAdvanceFees.createAdvanceFeeOrder');

        //处理彩之云订单返回结果
        if (!isset($result['code']) || $result['code'] != 0) {
            Yii::log('彩之云订单创建失败！返回信息：' . $result['message'], CLogger::LEVEL_INFO,
                'colourlife.core.OthersAdvanceFees.createAdvanceFeeOrder');
            OthersAdvanceFees::model()->addError('id', $result['message']);
            return false;
        }else{
            //调用ERP成功写入房间号
            PropertyFeeLog::createFeeLog($advanceFee->customer_id,$unitid);
        }

        //修改我们的订单失败
        if (!OthersFees::model()->updateByPk($order_id, array('status' => $state,'user_red_packet'=>1))) {
            Yii::log('已付款，彩之云ERP订单创建成功，回调修改订单失败！', CLogger::LEVEL_INFO,
                'colourlife.core.OthersAdvanceFees.createAdvanceFeeOrder');
            OthersAdvanceFees::model()->addError('id', "彩之云预缴费订单创建成功，更新订单失败！");
            return false;
        }

        OthersFeesLog::createOtherFeesLog($order_id, 'Customer', $state, $note);

        return true;
    }

    public function getStatusNames()
    {
        $status = $this->getStatusCompetence();
        $fees_status = self::$fees_status;
        foreach($fees_status as $key => $val){
            if(!in_array($key,$status)){
                unset($fees_status[$key]);
            }
        }
        return CMap::mergeArray(array('' => '全部'), $fees_status);
    }

    public function getStatusName($html = false)
    {
        $tag = "支付方式:" . (empty($this->payment) ? "" : $this->payment->name);
        $return = '';
        $return .= ($html) ? ('<a href="javascript:void();" class="label label-success" rel="tooltip" data-original-title="' . $tag . '">') : '';
        $return .= self::$fees_status[$this->status];
        $return .= ($html) ? '</a>' : '';
        return $return;
    }

    public function getStatusNameView()
    {
        $return = '<span class="label label-success">' . (self::$fees_status[$this->status]) . '</span>';
        if ($this->status == Item::FEES_AWAITING_PAYMENT || $this->status == Item::FEES_TRANSACTION_ERROR)
            $return .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/advanceFee/update/' . $this->id . '">修改支付状态</a>';
        return $return;
    }

    public function getAmountView()
    {
        $return = '<span>' . $this->amount . '</span>';
        $return .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/advanceFee/history/' . $this->id . '">查看缴费历史</a>';
        return $return;
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'create_ip',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

	public function getParentBranchName()
	{
//		$community = empty($this->advanceFee) ? null : $this->advanceFee->community;
//		if (!empty($community)) {
//			$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
//		} else {
//			$_barchName = "-";
//		}
//		return $_barchName;


		$_barchName = "-";
		if (!empty($this->advanceFee) || !empty($this->advanceFee->community_id)) {
			$community = ICECommunity::model()->findByPk($this->advanceFee->community_id);
			if (!empty($community)) {
				$_barchName = $community->branchString.' - '.$community['name'];
			}
		}

		return $_barchName;
	}

    public function getParentCommunityName()
    {
        $community = empty($this->advanceFee) ? null : $this->advanceFee->community;
        if (!empty($community)) {
            $_regionName = Region::getMyParentRegionNames($community->region_id, true);
        } else {
            $_regionName = "-";
        }
        return $_regionName . '-' . (empty($community) ? "" : $community->name);
    }

    public function getCommunityTag()
    {
        $community = empty($this->advanceFee) ? null : $this->advanceFee->community;
        if (!empty($community)) {
            $_barchName = Branch::getMyParentBranchName($community->branch_id, true);
            $_regionName = Region::getMyParentRegionNames($community->region_id, true);
        } else {
            $_barchName = "未知的部门！";
            $_regionName = "未知的地区！";
        }
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
            'data-original-title' => '所属部门:' . $_barchName . '，所属地区:' . $_regionName),
            empty($community) ? "" : $community->name);
    }

    public function getMobileTag()
    {
        $customer = $this->customer;
        $mobile = $customer?$customer->mobile:"";
        $username = $customer?$customer->username:"";
        $customerName = empty($this->advanceFee) ? "" : $this->advanceFee->customer_name;
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
            'data-original-title' => '姓名:' . $customerName . '，帐号:' . $username),
            $mobile);
    }

    public function getBuildTag()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
            'data-original-title' => '彩之云楼栋:' . (empty($this->advanceFee) ? "" : $this->advanceFee->colorcloud_building)),
            empty($this->advanceFee) ? "" : $this->advanceFee->build);
    }

    public function getRoomTag()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
            'data-original-title' => '彩之云门牌号:' . (empty($this->advanceFee) ? "" : $this->advanceFee->colorcloud_unit)),
            empty($this->advanceFee) ? "" : $this->advanceFee->room);
    }

    public function getStatusCompetence()
    {
        $status = array();
        if(Yii::app()->user->checkAccess('op_backend_advanceFee_awaiting')){
            $status[] = Item::FEES_AWAITING_PAYMENT;
        }
        if(Yii::app()->user->checkAccess('op_backend_advanceFee_success')){
            $status[] = Item::FEES_TRANSACTION_SUCCESS;
        }
        if(Yii::app()->user->checkAccess('op_backend_advanceFee_payment')){
            $status[] = Item::FEES_TRANSACTION_ERROR;
        }
        if(Yii::app()->user->checkAccess('op_backend_advanceFee_refund')){
            $status[] = Item::FEES_TRANSACTION_REFUND;
        }
        if(Yii::app()->user->checkAccess('op_backend_advanceFee_fail')){
            $status[] = Item::FEES_TRANSACTION_FAIL;
        }
        if(Yii::app()->user->checkAccess('op_backend_advanceFee_cancel')){
            $status[] = Item::FEES_CANCEL;
        }
        if(Yii::app()->user->checkAccess('op_backend_advanceFees_redFail')){
            $status[] = Item::FEES_TRANSACTION_LACK;
        }
        return $status;
    }

    /**
     * API余额
     * @param $unitid
     * @return iarray
     *
     */
    public function getApiAdvanceFee($unitid){
        if(empty($unitid)){
            OthersAdvanceFees::model()->addError('id', "未知的收费项ID！");
        }else{
            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();

            //引入彩之云的接口
            Yii::import('common.api.IceApi');
            //实例化
            $coloure = IceApi::getInstance();

            $result = $coloure->callGetAdvanceFee($unitid);
            if (!isset($result) || empty($result['data'])) {
                //如果未找到预缴费
                OthersAdvanceFees::model()->addError('id', "获取用户预缴费金额失败！");
                $result['data'] = array(
                    "state"=>0,
                    "Tenement"=>"",
                    "Balance"=> 0
                );
            }
            if(empty($result['data']["0"]["Balance"])){
                $result['data']["0"]["Balance"]=0;
            }
            return $result['data'];
        }
    }


    /**
     * @return array
     * 检测红包使用
     */
    public function checkOrderFees(){
        if(empty($this->customer)){
            return array('result'=>false,'error'=>"获取用户余额失败");
        }
        $balance = $this->customer->getBalance();//用户红包余额
        $amount = $this->amount;//订单总金额
        $redPackedPay = $this->red_packet_pay;//用户红包支付金额
        //如果红包支付金额大于余额或红包支付金额大于订单总额
        if($redPackedPay > $balance){
            return array('result'=>false,'error'=>"红包余额不足");
        }
        if($redPackedPay > $amount){
            return array('result'=>false,'error'=>"红包金额不能超过订单总额");
        }
        return array('result'=>true,'error'=>"");
    }

    public function getCustomerName(){
        if(!empty($this->customer)){
            return $this->customer->name;
        }else{
            return "";
        }
    }

    public function getCommunityName()
    {
        $model_string = $this->objectModel;
        if (!empty($this->$model_string->community)){
            return $this->$model_string->community->name;
        }else{
            return '';
        }
    }

    public function getPaymentList()
    {
        $model = Payment::model()->online()->findAll();
        if (isset($model)) {
            $payment_list = array();
            foreach ($model as $list) {
                $payment_list[''] = "全部";
                $payment_list[$list->id] = $list->name;
            }
            return $payment_list;
        } else {
            return "";
        }
    }

	protected function beforeSave()
	{
		/**
		 * @var Payment $payment
		 * @var OthersAdvanceFees $oldModel
		 */
		/**
		 * 由于WEB端的物业预缴费是先下订 再选支付方式。
		 * APP是先选支付方式再下单
		 * 所以判断是否有支付方式   有的则不能修改  无的则出现可选择支付方式并计算提成
		 * 这是由王晃确认过的。
		 * */
		if ($this->isNewRecord && !empty($this->payment_id)) {//创建时保存支付费率
			if ($payment = Payment::model()->findByPk($this->payment_id)) {
				$this->pay_rate = $payment->rate;
			}
		} elseif ('update' == $this->getScenario()) {
			$oldModel = self::model()->findByPk($this->id);
			if ($oldModel && empty($oldModel->payment_id) && !empty($this->payment_id)) {//如果下订单时没有选择支付方式 后台可以选择支付方式来计算支付费率
				if ($payment = Payment::model()->findByPk($this->payment_id)) {
					$this->pay_rate = $payment->rate;
				}
			}
		}
		return parent::beforeSave();
	}

	public function ICEGetLinkageRegionDefaultValue()
	{
		$updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
		return $updateDefaults
			? $updateDefaults
			: $this->ICEGetLinkageRegionDefaultValueForSearch();
	}

	protected function ICEGetLinkageRegionDefaultValueForUpdate()
	{
		return array();
	}

	public function ICEGetLinkageRegionDefaultValueForSearch()
	{
		$searchRegion = $this->ICEGetSearchRegionData(isset($_GET[__CLASS__])
			? $_GET[__CLASS__] : array());

		$defaultValue = array();

		if ($searchRegion['province_id']) {
			$defaultValue[] = $searchRegion['province_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['city_id']) {
			$defaultValue[] = $searchRegion['city_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['district_id']) {
			$defaultValue[] = $searchRegion['district_id'];
		} else {
			return $defaultValue;
		}

		return $defaultValue;
	}

	protected function ICEGetSearchRegionData($search = array())
	{
		return array(
			'province_id' => isset($search['province_id']) && $search['province_id']
				? $search['province_id'] : '',
			'city_id' => isset($search['city_id']) && $search['city_id']
				? $search['city_id'] : '',
			'district_id' => isset($search['district_id']) && $search['district_id']
				? $search['district_id'] : '',
		);
	}

}
