<?php

class YiNengYuanController extends CController
{
    protected $modelName = 'OthersPowerFees';

    public $_userId = 0;
    public $_username = "";
    public $_cust_model = "";
    public $objectLabel = '物业费';
    public $interface_type = "";//判断是新的电表接口还是久的电表接口  1: 久的电表接口 、2: 新的电表接口
    protected $uuid='37b3bd458252452f9bf76f0a928abcf1';

    /**
     * 权限控制
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'log', 'AddPower', 'Pay_Success', 'buy_detail_over', 'buy_detail_over', 'buy_detail_success', 'buy_detail_yfk'), // 列表 查看
                'expression' => '$user->checkAccess("op_backend_powerFees_view")',
            ),
            array('allow',
                'actions' => array('update', 'report', 'fillOrders', 'disposal', 'getRechargeCode'), // 编辑
                'expression' => '$user->checkAccess("op_backend_powerFees_update")',
            ),

            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    //回调函数
    public function actionAjax()
    {
        $error = "";
        $meter = 1;
        if (isset($_POST)) {
            if (!empty($_POST['customer_id']) && !empty($_POST['meter'])) {
                $meter = $_POST['meter'];
                $model = new PowerAddress('apiCreate');
                $model->attributes = $_POST;

                $find = PowerAddress::model()->find("meter=:meter and is_deleted=:is_deleted and customer_id=:customer_id", array(':meter' => $meter, ':is_deleted' => 0, ':customer_id' => (int)$_POST['customer_id']));
                if ($find) {
                    $error = '该卡号已经被绑定！请重新输入电表卡号';
                    echo json_encode(array('code' => 0, 'msg' => $error));
                    exit;
                }

                if(strlen($meter) == 9){          //判断是否为云控电表号
                    Yii::import('common.api.CloudControlElectricMeterApi');
                    $cloudElectric = CloudControlElectricMeterApi::getInstance();
                    $result = $cloudElectric->callWipmDetails($meter);
                    if($result['code'] == '42440'){
                        $interface_type = 3;
                        $model->customer_name = $result['data']['wipm_author'];
                        $model->meter_address = $result['data']['nicename'];
                        echo json_encode(array('code' => 1, 'msg' => $error, 'data' => array('customer_name' => $result['data']['wipm_author'], 'meter_address' => $result['data']['nicename'], 'interface_type' => $interface_type)));
                    }else{
                        $error = '云控电表卡号不存在或获取失败！请重新输入电表卡号';
                        echo json_encode(array('code' => 0, 'msg' => $error));
                        exit;
                    }

                }else{
                    Yii::import('common.api.StarApiNew');
                    $star = StarApiNew::getInstance();
                    $result = $star->callCheckMeterNo($meter);

                    $interface_type = 2;
                    if ($result->CheckMeterNoResult != 0) {
                        Yii::import('common.api.StarApi');
                        $star = StarApi::getInstance();
                        $result = $star->callCheckMeterNo($meter);
                        $interface_type = 1;
                    }

                    if ($result->CheckMeterNoResult != 0) {
                        $error = '电表卡号不存在！请重新输入电表卡号';
                        echo json_encode(array('code' => 0, 'msg' => $error));
                        exit;
                    }

                    //CheckMeterNoResult 0 表示成功返回
                    if (0 == $result->CheckMeterNoResult) {
                        $model->customer_name = $result->CustomerName;
                        $model->meter_address = $result->CustomerAddress;
                        echo json_encode(array('code' => 1, 'msg' => $error, 'data' => array('customer_name' => $result->CustomerName, 'meter_address' => $result->CustomerAddress, 'interface_type' => $interface_type)));
                    }
                }

            }

        }
    }

    //添加电表
    public function actionAddPower()
    {
        if (!empty($_GET['meter']) &&
            !empty($_GET['customer_name']) &&
            !empty($_GET['community_name']) &&
            !empty($_GET['customer_id']) &&
            !empty($_GET['interface_type'])
        ) {
            //电表号
            $meter = $_GET['meter'];
            $customer_name = $_GET['customer_name'];
            $community_name = $_GET['community_name'];
            if(strlen($meter) == 9){  //如果为云控电表，将地址信息修改保存
                Yii::import('common.api.CloudControlElectricMeterApi');
                $cloudElectric = CloudControlElectricMeterApi::getInstance();
                $cloudElectric->callWipmInfoedit($meter, $customer_name, $community_name);
            }
            $customer_id = (int)$_GET['customer_id'];
            $cu_model = Customer::model();
            $result = $cu_model->find('id=:id', array(':id' => $customer_id));

            $community_id = $result->community_id;
            $interface_type = $_GET['interface_type'];

            $model = new PowerAddress();
            $model->customer_id = $customer_id;
            $model->meter_address = $_GET['community_name'];
            $model->community_id = $community_id;
            $model->meter = $meter;
            $model->customer_name = $customer_name;
            $model->interface_type = $interface_type;
            $model->last_time = time();
            $model->save();
            $this->redirect('Buy?cust_id=' . $_GET['customer_id']);
        }

        $this->renderPartial('add');
    }


    //支付成功
    public function actionPay_Success()
    {


        $this->renderPartial('pay_success');
    }

    //商铺买电
    public function actionBuy()
    {
        $cust_id = (int)Yii::app()->request->getParam('cust_id');
        if (!empty($cust_id)) {
            $model = PowerAddress::model();
            $result = $model->findAll('customer_id=:customer_id and is_deleted=:is_deleted', array(':customer_id' => $cust_id, ':is_deleted' => 0));
//dump($result);
            $this->renderPartial('buy', array('data' => $result,
                'cust_id' => $cust_id));
        } else {
            throw new CHttpException('用户id不能为空', 400);
        }
    }

    //删除电表
    public function actionDeletePower()
    {
        $id = (int)Yii::app()->request->getParam('id');
        $model = PowerAddress::model()->find('id=:id', array(':id' => $id));

        if ($model) {
            $model->is_deleted = 1;
            if ($model->update()) {

                echo json_encode(array('code' => 1));
            } else {
                throw new CHttpException(400, $this->errorSummary($model));
            }

        }

    }


    public function checkLogin()
    {
        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        } else {
            $custId = 0;
            if (isset($_REQUEST['cust_id'])) {  //优先有参数的
                $custId = intval($_REQUEST['cust_id']);
                $_SESSION['cust_id'] = $custId;
            } else if (isset($_SESSION['cust_id'])) {  //没有参数，从session中判断
                $custId = $_SESSION['cust_id'];
            }
            $customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if (empty($custId) || empty($customer)) {
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            $this->_userId = $custId;
            return $custId;
        }

    }

    //购电详情--已退款
    public function actionbuy_detail_ytk($sn)
    {


        $connection = Yii::app()->db;
        $sql = "select ot.customer_id,ot.sn,po.meter,po.meter_address,
                po.customer_name,
                ot.create_time as create_time,
                ot.amount,ot.red_packet_pay,
                ot.pay_id,po.recharge_code as token,
                case ot.status
                when 0 then '待付款' 
                when 1 then '已付款'
                when 90 then '已退款'
                when 96 then '交易失败'
                when 97 then '交易失败'
                when 98 then '交易关闭'
                when 99 then '交易成功'
                when 101 then '已付款' end as status
                 from others_fees ot LEFT JOIN power_fees po 
                on ot.object_id=po.id where ot.sn='$sn'";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $interface_type = "";
        $balance = "";
        if (count($result) > 0) {

            $meter = $result[0]['meter'];

            $Address = PowerAddress::model()->find("meter=:meter and is_deleted=:is_deleted", array(':meter' => $meter, ':is_deleted' => 0));
            if ($Address != null) {
                $interface_type = $Address->interface_type;
                if ($interface_type == 2) {
                    $star = OthersPowerFees::model()->ConfirmInterfaceType($meter);
                    $ResultBalance = $star->callGetMeterBalance($meter);
                    $balance = $ResultBalance->MeterBalance;
                }if($interface_type == 3){
                    $star = OthersPowerFees::model()->ConfirmInterfaceType($meter);
                    $ResultBalance = $star->callWipmDetails($meter);
                    $balance = !isset($ResultBalance['data']) ? 0: $ResultBalance['data']['stored_balance'];
                }
            }

        }

        $this->renderPartial('buy_detail_ytk',
            array('data' => $result,
                'interface_type' => $interface_type,
                'balance' => $balance,));
    }

    //购买充值卡
    public function actionbuy_recharge()
    {
        $this->renderPartial('buy_recharge');
    }

    //购电记录
    public function actionbuy_record()
    {
        $meter = Yii::app()->request->getParam('meter');
        $cust_id = (int)Yii::app()->request->getParam('cust_id');


        if (!empty($meter)) {
            $connection = Yii::app()->db;
            $sql = "select ot.sn,ot.customer_id, FROM_UNIXTIME(ot.create_time,'%Y-%m-%d    %H:%i') as create_time,ot.update_time,ot.amount,
           case ot.status
           when 0 then '待付款'
           when 1 then '已付款'
           when 90 then '已退款'
           when 96 then '交易失败'
           when 97 then '交易失败'
           when 98 then '交易关闭'
           when 99 then '交易成功'
           when 101 then '已付款' end as status
           from others_fees ot LEFT JOIN power_fees po on ot.object_id=po.id where po.meter='$meter' AND ot.customer_id=$cust_id order by ot.update_time desc";

            $command = $connection->createCommand($sql);
            $result = $command->queryAll();

            $this->renderPartial('buy_record', array('data' => $result));

        }
    }

    //查看电表卡号
    public function actionselect_ammeter()
    {


        $this->renderPartial('select_ammeter');
    }

    //创建订单
    public function actionCreateOrder()
    {


        $sn = SN::initByPowerFees()->sn;
        $cust_id = (int)Yii::app()->request->getParam('customer_id');
        $amount = Yii::app()->request->getParam('amount');
        $ip = Yii::app()->request->userHostAddress;
        $meter = Yii::app()->request->getParam('meter');
        $customer_name = Yii::app()->request->getParam('customer_name');
        $meter_address = Yii::app()->request->getParam('meter_address');
        //数据验证
        if (!empty($sn) &&
            !empty($cust_id) &&
            !empty($amount) &&
            !empty($ip) &&
            !empty($meter) &&
            !empty($customer_name) &&
            !empty($meter_address)
        ) {

            //实例化模型
            $model_PowerFees = new PowerFees();
            $model_Customer = Customer::model();
            $OthersFees = new OthersFees();


            $result_Customer = $model_Customer->find('id=:id', array(':id' => $cust_id));
            if ($result_Customer) {

                $model_PowerFees->community_id = $result_Customer->community_id;
                $model_PowerFees->build = $result_Customer->build_id;
                $model_PowerFees->room = $result_Customer->room;
                $model_PowerFees->meter = $meter;
                $model_PowerFees->customer_name = $customer_name;
                $model_PowerFees->meter_address = $meter_address;
                if ($model_PowerFees->save()) {

                    $OthersFees->object_id = $model_PowerFees->id;

                    $OthersFees->sn = $sn;
                    $OthersFees->customer_id = $cust_id;
                    $OthersFees->model = "PowerFees";
                    $OthersFees->amount = $amount;
                    $OthersFees->create_ip = $ip;
                    $OthersFees->create_time = time();
                    $OthersFees->bank_pay = 0.00;
                    $OthersFees->red_packet_pay = 0.00;
                    $OthersFees->user_red_packet = 0;
                    $OthersFees->note = "";

                    //2017-02-26软硬入口数据收集埋点
                    EntranceCountLog::model()->writeOperateLog($cust_id , '' , $operation_time=time(), 9 ,$result_Customer->community_id);

                    if ($OthersFees->save()) {

                        echo json_encode(array('status' => 1, 'msg' => '保存成功', 'sn' => $sn));

                    } else {

                        echo json_encode(array('status' => 0, 'msg' => '保存失败'));

                    }
                } else {

                    throw new CHttpException(400, "数据不能为空");

                }

            } else {

                throw new CHttpException(400, "订单保存失败");
            }


        }

    }

    //调用原生支付
    public function actionPayFromHtml5($sn)
    {

        $url = Yii::app()->request->hostInfo . CHtml::normalizeUrl('/YiNengYuan/pay_success');

        $this->render('payFromHtml5', array('sn' => $sn, 'url' => $url));
    }


}