<?php
include('ExamineController.php');
class XieyiAppController extends Controller {

    public function actionIndex() {
        $this->layout = 'xeiyi';
        $this->render('xieyi');
    }

    //停车费有成功单时的跳转
    public function actionProtocol() {
        $this->layout = 'xeiyi';
        $this->render('protocol');
    }

    //停车费有成功单时的跳转
    public function actionUploads() {
        $this->layout = 'xeiyi';
        $this->render('files');
    }

    /*
     * html5支付测试
     */
    public function actionHtml5(){
        $this->layout = 'xeiyi';
        $this->render('html5test');
    }

    /*
    * html5支付测试
    */
    public function actionOk(){
        ThirdFeesLog::createOtherFeesLog(111111, 11, 1, 1111,428354);



        echo 'okok,成功付款了';
    }

    public function actionReponse(){
        $var = var_export($_POST, true);
        Yii::log("我接收到回调了哈哈～～～～！，参数" . $var, CLogger::LEVEL_INFO, 'colourlife.core.XieyiAppController.actionReponse');
        echo json_encode(array('success'=>1));
    }

    public function actionTest() {
//
        //  var_dump(Yii::app()->request->userAgent);
        $userAgent = Yii::app()->request->userAgent;

        if (preg_match("/(iPod|iPad|iPhone)/", $userAgent))
        {
            echo 'ios'; //IOS客户端
        }
        elseif (preg_match("/WP/", $userAgent))
        {
            echo 'wp'; //WinPhone客户端
        }
        elseif (preg_match("/android/i", $userAgent)) {
            echo 'android'; //android客户端
        } else {
            echo 'web';
        }




        exit;


        foreach(Yii::app()->request as $k => $v){
            var_dump($v);
        }


        $re = new SmsComponent();
        $re->init();
        $re->sendMsg('kkkk' . time());

        exit;

        $re = new PublicFunV23();
        //  $re->typeConnect = 'curl';
        // $json = $re->contentMethod('http://www.5ker.com:6888/XieyiApp/reponse');
        $re->method = 'POST';
        $time = round(microtime(true) * 1000);

        //企业Id+用户Id+用户密码+时间戳

        $key = md5("360szcaishCaish360{$time}");
        echo "360szcaish{$time}";

        $url = "http://client.sms10000.com/api/webservice";
        $data = "cmd=send&eprId=360&userId=szcaish&key={$key}&timestamp={$time}&"
            . "format=json&mobile=18688998103&msgId=1&content=content";


        //  echo $data;
        parse_str($data, $data_1);var_dump($data_1);exit;
        $json = $re->contentMethod($url, $data_1);













        $json = json_decode($json);
        var_dump($json);
        exit;
        $test = new SmsComponent();
        $test->sendThirdPaymentMessage('successfulPayment', '90020000150412002104434', $title = "第三方缴费通知");
        dump($test);

        //  $t = new SetableSmallLoans();
        // dump($t->searchByPhone('proto_ios', 1212321310));
        $time = date('Y-m-d H:i:s');
        Yii::log('测试1' . $time, CLogger::LEVEL_INFO, 'colourlife.core.XieyiAppController.test');

        $time = date('Y-m-d H:i:s');
        // Yii::log('测试1'. $time);

        if (ThirdFeesLog::createOtherFeesLog(1, 'Employee', 1, "{$time}后台手动修改第三方单据状态！输入的备注:")) {

            //   for($i=1; $i< 17200000; $i++) {};$time = date('Y-m-d H:i:s');
            $time = date('Y-m-d H:i:s');
            Yii::log('测试2' . $time, CLogger::LEVEL_INFO, 'colourlife.core.XieyiAppController.test');
        }
        //  for($i=1; $i< 17200000; $i++) {};



        exit;
        // $sql = "select * from virtual_recharge where id = (select DISTINCT object_id from others_fees where sn='3000000130720130607337') and income_price >= expend_price";
        //$result = Yii::app()->db->createCommand($sql)->queryAll();
        // var_dump($result);
        //$re = new OneYuanBuy();
        // echo SN::initByThirdPaymentOrder('002', 9000);
        //
        //
        //
        //ECHO F::getHomeUrl();;
        //var_dump($re->getMyCode(153805,1));
        //$code, $user_id, $c_id, $g_id
        // 0016926322
//getMyCode($user_id, $type = '')
        // var_dump($re->checkCode('0016926322',123,1,1));
        //searchCode($user_id, $c_id, $g_id)
        //  var_dump($re->searchCode(123,1,12));
        //useCode($code, $user_id, $c_id, $g_id)
        //  var_dump($re->useCode('0016926322', 123,1,12));
        $re = new PublicFunV23();
        $server_url = F::getHomeUrl() . 'xieyiapp/test';
        $server_url = 'www.baiddu.';
        $post = $re->arrayToString(array('ok' => '小样我来了'));
        // $re->typeConnect = 'jjj';
        $ok = $re->contentMethod($server_url, array('ok' => '小样我来了'));
        var_dump(json_decode($ok));
        echo json_encode(array('ok' => 'jjjjjjjjjjjjjjj'));
        exit;
    }

    public function actionExamine() {

    }

    //E评价
    public function actionEdata() {
        $start1 = $start = Yii::app()->request->getParam('start');
        $end1 = $end = Yii::app()->request->getParam('end');
        // $start = '2015-02-01 00:00:00';
        //$end = '2015-02-28 23:59:59';
        if (strlen($start) == 19 && strlen($end) == 19) {
            $start = strtotime($start);
            $end = strtotime($end);
            $msg = '';
            if ($start && $end) {
                $sql = "select
					branch4.name 大区,
					branch3.name 城市事业部,
                    community.name 项目名称,
          	        customer.room 房号,
					customer.name 姓名,
					customer.mobile 电话号码,	
					examine.answers ac,
					FROM_UNIXTIME(examine.create_time)'电访时间',
					examine.note 备注					
					from examine
					LEFT JOIN customer on customer.id=examine.customer_id
					LEFT JOIN community on community.id=customer.community_id
					LEFT JOIN branch branch1 on branch1.id=community.branch_id
					LEFT JOIN branch branch2 on branch2.id=branch1.parent_id
					LEFT JOIN branch branch3 on branch3.id=branch2.parent_id
					LEFT JOIN branch branch4 on branch4.id=branch3.parent_id
					LEFT JOIN region region0 on region0.id=community.region_id
					LEFT JOIN region region1 on region1.id=region0.parent_id
					LEFT JOIN region region2 on region2.id=region1.parent_id
					where examine.create_time>='{$start}' and examine.create_time<='{$end}'";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                //dump($result);
                $_vs = array();

                foreach ($result as $key => $value) {
                    // "1b,2b,3b,4b,5b,6b" ===== 1,4,7,10,13,16

                    $str1 = substr($value['ac'], 1, 1);

                    if ($str1 == 'a') {
                        $score1 = 5;
                    } else if ($str1 == 'b') {
                        $score1 = 4;
                    } else if ($str1 == 'c') {
                        $score1 = 3;
                    } else if ($str1 == 'd') {
                        $score1 = 2;
                    } else {
                        $score1 = 1;
                    }

                    $str2 = substr($value['ac'], 4, 1);

                    if ($str2 == 'a') {
                        $score2 = 5;
                    } else if ($str2 == 'b') {
                        $score2 = 4;
                    } else if ($str2 == 'c') {
                        $score2 = 3;
                    } else if ($str2 == 'd') {
                        $score2 = 2;
                    } else {
                        $score2 = 1;
                    }


                    $str3 = substr($value['ac'], 7, 1);

                    if ($str3 == 'a') {
                        $score3 = 5;
                    } else if ($str3 == 'b') {
                        $score3 = 4;
                    } else if ($str3 == 'c') {
                        $score3 = 3;
                    } else if ($str3 == 'd') {
                        $score3 = 2;
                    } else {
                        $score3 = 1;
                    }


                    $str4 = substr($value['ac'], 10, 1);
                    if ($str4 == 'a') {
                        $score4 = 5;
                    } else if ($str4 == 'b') {
                        $score4 = 4;
                    } else if ($str4 == 'c') {
                        $score4 = 3;
                    } else if ($str4 == 'd') {
                        $score4 = 2;
                    } else {
                        $score4 = 1;
                    }

                    $str5 = substr($value['ac'], 13, 1);
                    if ($str5 == 'a') {
                        $score5 = 5;
                    } else if ($str5 == 'b') {
                        $score5 = 4;
                    } else if ($str5 == 'c') {
                        $score5 = 3;
                    } else if ($str5 == 'd') {
                        $score5 = 2;
                    } else {
                        $score5 = 1;
                    }



                    $str6 = substr($value['ac'], 16, 1);
                    if ($str6 == 'a') {
                        $score6 = 5;
                    } else if ($str6 == 'b') {
                        $score6 = 4;
                    } else if ($str6 == 'c') {
                        $score6 = 3;
                    } else if ($str6 == 'd') {
                        $score6 = 2;
                    } else {
                        $score6 = 1;
                    }

                    $str7 = substr($value['ac'], 19, 1);
                    if ($str7 == 'a') {
                        $score7 = 5;
                    } else if ($str7 == 'b') {
                        $score7 = 4;
                    } else if ($str7 == 'c') {
                        $score7 = 3;
                    } else if ($str7 == 'd') {
                        $score7 = 2;
                    } else if ($str7 == 'e') {
                        $score7 = 1;
                    } else {
                        $score7 = '';
                    }

                    $_v['t1'] = $score1;
                    $_v['t2'] = $score2;
                    $_v['t3'] = $score3;
                    $_v['t4'] = $score4;
                    $_v['t5'] = $score5;
                    $_v['t6'] = $score6;
                    $_v['t7'] = $score7;
                    $_v['other'] = $value;
                    $_vs[] = $_v;
                }
            } else {
                $_vs = array();
                $msg = '长度不够';
            }
        } else {
            $_vs = array();
            $msg = '日期有误/日期为空';
        }

        $this->renderPartial("Edata", array('data' => $_vs, 'end' => $end1, 'start' => $start1, 'msg' => $msg));
    }


    //E评价数据录入
    public function actionE_evaluation(){

//       if(!empty($_REQUEST['userid'])){
//       $user_id=$_REQUEST['userid'];
//
//       }else{
//        $user_id=$_SESSION['userid'];
//       }
        if(!empty($_GET['evaluation_1'])){
            echo  $_GET['evaluation_1'];

            $result1=self::back_answer($_GET['evaluation_1']);
            $result2=self::back_answer($_GET['evaluation_2']);
            $result3=self::back_answer($_GET['evaluation_3']);
            $result4=self::back_answer($_GET['evaluation_4']);
            $result5=self::back_answer($_GET['evaluation_5']);
            $result6=self::back_answer($_GET['evaluation_6']);
            $results="1".$result1.",2".$result2.",3".$result3.",4".$result4.",5".$result5.",6".$result6;
            echo $results;
            $model=new Examine('create');
            $model->answers=$results;
            $model->customer_id=196574;
            $model->category_id = 1;
            $model->create_time = time();
            $model->note = $_GET['note'];

            $success = $model->save();
            if($success){
                echo "保存成功";
            }else{
                dump($model->getErrors());
                echo "保存失败";
            }
            exit;


            $this->redirect("Evaluation_result");
        }






        $this->renderPartial("E_evaluation");
    }

    //E评价数据提交
    public function actionEvaluation_result(){








        $this->renderPartial("Evaluation_result");
    }
    public function back_answer($answer){
        switch ($answer){
            case "很满意":
                return "a";
                break;
            case "满意":
                return "b";
                break;
            case "一般":
                return "c";
                break;
            case "不满意":
                return "d";
                break;
            case "很不满意":
                return "e";
                break;
        }


    }




    //充值
    public function actionVdata() {
        $start1 = $start = Yii::app()->request->getParam('start');
        $end1 = $end = Yii::app()->request->getParam('end');
        // $start = '2015-02-01 00:00:00';
        //$end = '2015-02-28 23:59:59';
        if (strlen($start) == 19 && strlen($end) == 19) {
            $start = strtotime($start);
            $end = strtotime($end);
            $msg = '';
            if ($start && $end) {
                $sql = " SELECT
                    redpacket_fees.sn os,
                    branch3.name bn,
                    branch2.name bn2,
                    community.name cn,
                    build.name bn3,
                    redpacket_fees_addr.room rr,
                    customer.name cn1,
                    customer.mobile cm,
                    redpacket_fees.amount oa,
                    sd.sum ss,
                    sd.note sn,
                    FROM_UNIXTIME(redpacket_fees.create_time) oc,
                    CASE 
                    WHEN redpacket_fees.status = 0 THEN '未付款'
                    WHEN redpacket_fees.status = 1 THEN '已付款'
                    WHEN redpacket_fees.status = 99 THEN '交易成功' 
                    ELSE redpacket_fees.status END os1,
                    payment.name pn
                    from redpacket_fees
                    LEFT JOIN redpacket_fees_addr on redpacket_fees_addr.id=redpacket_fees.object_id
                    LEFT JOIN customer on customer.id=redpacket_fees.customer_id
                    LEFT JOIN build on redpacket_fees_addr.build_id=build.id
                    LEFT JOIN community on community.id=redpacket_fees_addr.community_id
                    LEFT JOIN branch branch1 on branch1.id=community.branch_id
                    LEFT JOIN branch branch2 on branch2.id=branch1.parent_id
                    LEFT JOIN branch branch3 on branch3.id=branch2.parent_id
                    LEFT JOIN payment on redpacket_fees.payment_id=payment.id
                    LEFT JOIN (SELECT * from red_packet where sn='redPacket_fees_activity_') sd on sd.remark=redpacket_fees.sn 
                    where redpacket_fees.status in(1,99) and redpacket_fees.create_time>='{$start}' and redpacket_fees.create_time<='{$end}'";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
            } else {
                $result = array();
                $msg = '日期有误/日期为空';
            }
        } else {
            $result = array();
            $msg = '长度不够';
        }
        $this->renderPartial("Vdata", array('data' => $result, 'end' => $end1, 'start' => $start1, 'msg' => $msg));
    }




    public function actionOrder(){

        //echo 'ok';
        $db = Yii::app()->db;
        $model = $db->createCommand("select of.*, from_unixtime(of.create_time), FROM_UNIXTIME(p.pay_time), cu.mobile, cu.balance from (`order` as of, pay as p)
LEFT JOIN customer as cu on (cu.id = of.buyer_id)
where of.pay_id = p.id and of.`status`!=1 and of.status!=99
and of.sn 
in ('2030002150514172105190','2030034150514180605926','2030081150514185405216','2030064150514190205456','2030064150514191005754','8010000150514193105484','8010000150514193405629','8010000150514195505821','8010000150514201305652','2030064150514202005841','2030064150514202705866','2030038150514203205285','2030038150514203405686','2030064150514203705220','2030038150514203705866','2030038150514204005622','2030064150514204405231','2030038150514204505362','2030064150514204705444','2030064150514210005217','2030077150514210305449','2030064150514210405786','2030064150514210605320','2030063150514212305266','2030002150514213105768','2030031150514213705448','2030031150514213805208','8010000150514214705260','8010000150514220105747','8010000150515005105656','2030002150515005905409','8010000150515012205686','8010000150515013505884','2031057150515044405225','8010000150515050505137','8010000150515052505344','8010000150515061505456','8010000150515071705406','8010000150515074205088','2030002150515075305879','2030002150515082705268','8010000150515082705822','2030002150515082705064','2030002150515084805876','8010000150515090805108','8010000150515093205124',
'8010000150515095205884','2030002150515095405106','8010000150515102705285','8010000150515104705016')
")->queryAll();
        $ok = array();
        $no = array();
        foreach($model as $k=>$v){
            // var_dump($v['sn']);

            //  dump(Yii::app()->user);
//                $order_sn = $v['sn'];
//                $order_id = $v['id'];
//                $payment_id = $v['payment_id'];
//                $note = '手工回单调试';
//                $type = PayLib::getHandPayClassName($order_sn);//取得type类型
//                $o = OrderFactory::getInstance($type); //初始化对像
//                $o->init($order_id, $payment_id, $note); //初始化方法
//               if ($o->orderProcessing()) {
//                   $ok[] = $v['sn'];
//                   Yii::log('手工回测试,支付回调修改状态成功流水号：'. $v['sn'], CLogger::LEVEL_INFO, 'colourlife.core.order');
//               } else $no[] = $v['sn'];
            //只判断红包支付
            if (!empty($v['mobile']) && $v['balance'] >= $v['red_packet_pay']){

                $note = '手工更改订单状态（回调失败的单,处理时间' . date('Y-m-d H:i:s') . '）,支付回调修改状态成功流水号：'. $v['sn'];
                if ($v['red_packet_pay']== $v['amount']){
                    $note .= ',全红包支付' . $v['red_packet_pay'];
                    $status = "status = 1, user_red_packet=1";
                } elseif($v['red_packet_pay'] == 0){
                    $note .= ',全部银行支付' . $v['red_packet_pay'];
                    $status = "status = 99";
                } else {
                    $note .= ',戏包支付' . $v['red_packet_pay'] . ',银行支付' . $v['bank_pay'];
                    $status = "status = 99";
                }
                $sql = "UPDATE `order` SET {$status}, note=concat(note, '{$note}'), income_pay_time='" . time() ."' WHERE id={$v['id']}";
                $model = $db->createCommand($sql)->execute();
                if ($model){
                    //更改红包
                    $redPacketModel = new RedPacket();
                    $attr = array();
                    $attr['customer_id'] = $v['buyer_id'];
                    $attr['to_type'] = 4;
                    $attr['sum'] = $v['red_packet_pay'];
                    $attr['sn'] = $v['sn'];
                    if ($redPacketModel->consumeRedPacker($attr)){
                        $ok[$k] = $v['sn'];
                    }
                    Yii::log($note, CLogger::LEVEL_INFO, 'colourlife.core.order');
                    $model_pay = PayLib::get_model_by_sn($v['sn']);
                    OrderLog::createOrderLog($v['id'], $model_pay, $v['status'], $note, $v['buyer_id']);

                    continue;
                }

            }
            $no[$k] = $v['sn'];
            if (empty($v['mobile'])) $no[$k] .= '  没有用户名（'.$v['balance'].$v['buyer_id'].'）';
            elseif ($v['balance'] < $v['red_packet_pay']) $no[$k] .= '  红包不够（'.$v['balance'].'）';



        }
//$model = Yii::app()->db->createCommand("update `order` set `status` = 0, red_packet_pay=0 where sn
//in ('2030002150514172105190','2030034150514180605926','2030081150514185405216','2030064150514190205456','2030064150514191005754','8010000150514193105484','8010000150514193405629','8010000150514195505821','8010000150514201305652','2030064150514202005841','2030064150514202705866','2030038150514203205285','2030038150514203405686','2030064150514203705220','2030038150514203705866','2030038150514204005622','2030064150514204405231','2030038150514204505362','2030064150514204705444','2030064150514210005217','2030077150514210305449','2030064150514210405786','2030064150514210605320','2030063150514212305266','2030002150514213105768','2030031150514213705448','2030031150514213805208','8010000150514214705260','8010000150514220105747','8010000150515005105656','2030002150515005905409','8010000150515012205686','8010000150515013505884','2031057150515044405225','8010000150515050505137','8010000150515052505344','8010000150515061505456','8010000150515071705406','8010000150515074205088','2030002150515075305879','2030002150515082705268','8010000150515082705822','2030002150515082705064','2030002150515084805876','8010000150515090805108','8010000150515093205124',
//'8010000150515095205884','2030002150515095405106','8010000150515102705285','8010000150515104705016')")->execute();
        var_dump($ok);
        var_dump($no);




        //$res = new PayCustomerOrder();
        //$res->init('11120', 8, $note = '手工回单调试');







    }

}
