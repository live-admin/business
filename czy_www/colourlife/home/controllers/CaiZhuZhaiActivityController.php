<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/9/13
 * Time: 10:39
 */
class CaiZhuZhaiActivityController extends WeChatController
{
    public $appId  = 'wxbf8c699c1fb1539b';
    public $secret = '11e91f26e78e57a102be7994dad976b9';
    //public $appId  = 'wx688cde30d9c13b39';
    //public $secret = 'd4624c36b6795d1d99dcf0547af5443d';

    public $loginKey = 'SD&^)#@LDCsrS';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'weChatAuth - tip, appReceive, CheckSign',
            'isLogin - CheckSign, Index, Introduce, AjaxLottery, Receive, SendCode',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }

        /*
         * 奖项数组
         * 是一个二维数组，记录了所有本次抽奖的奖项信息，
         * 其中id表示中奖等级，prize表示奖品，v表示中奖概率。
         * 注意其中的v必须为整数，你可以将对应的 奖项的v设置成0，即意味着该奖项抽中的几率是0，
         * 数组中v的总和（基数），基数越大越能体现概率的准确性。
         * 本例中v的总和为100，那么平板电脑对应的 中奖概率就是1%，
         * 如果v的总和是10000，那中奖概率就是万分之一了。
         */
    public $prize = array(
        // 万成100售房部
//        10 => array(
//            '1' => array('id'=>27, 'prize'=>'100', 'v'=>0, 'maxNumber'=>1, 'award'=>'一等奖'),
//            '2' => array('id'=>26, 'prize'=>'50', 'v'=>0, 'maxNumber'=>3, 'award'=>'二等奖'),
//            '3' => array('id'=>25, 'prize'=>'10', 'v'=>20, 'maxNumber'=>40, 'award'=>'三等奖'),
//            '4' => array('id'=>24, 'prize'=>'5', 'v'=>30, 'maxNumber'=>50, 'award'=>'四等奖'),
//            '5' => array('id'=>23, 'prize'=>'1', 'v'=>50, 'maxNumber'=>100, 'award'=>'纪念奖'),
//        ),
//        // 懿合苑售房部
//        11 => array(
//            '1' => array('id'=>32, 'prize'=>'100', 'v'=>5, 'maxNumber'=>10, 'award'=>'一等奖'),
//            '2' => array('id'=>31, 'prize'=>'60', 'v'=>15, 'maxNumber'=>50, 'award'=>'二等奖'),
//            '3' => array('id'=>30, 'prize'=>'30', 'v'=>20, 'maxNumber'=>133, 'award'=>'三等奖'),
//            '4' => array('id'=>29, 'prize'=>'20', 'v'=>25, 'maxNumber'=>250, 'award'=>'四等奖'),
//            '5' => array('id'=>28, 'prize'=>'10', 'v'=>35, 'maxNumber'=>700, 'award'=>'纪念奖'),
//        ),
//        // 春节大转盘
//        12 => array(
//            '1' => array('id'=>33, 'prize'=>'20', 'v'=>10, 'maxNumber'=>10, 'award'=>'一等奖'),
//            '2' => array('id'=>34, 'prize'=>'10', 'v'=>30, 'maxNumber'=>25, 'award'=>'二等奖'),
//            '3' => array('id'=>35, 'prize'=>'5', 'v'=>560, 'maxNumber'=>560, 'award'=>'三等奖'),
//            '4' => array('id'=>36, 'prize'=>'3', 'v'=>1400, 'maxNumber'=>2250, 'award'=>'纪念奖'),
//        ),
        // 3.8
        13 => array(
            '1' => array('id'=>37, 'prize'=>'200', 'v'=>3, 'maxNumber'=>3, 'award'=>'一等奖'),
            '2' => array('id'=>38, 'prize'=>'100', 'v'=>5, 'maxNumber'=>5, 'award'=>'二等奖'),
            '3' => array('id'=>39, 'prize'=>'70', 'v'=>10, 'maxNumber'=>10, 'award'=>'三等奖'),
            '4' => array('id'=>40, 'prize'=>'50', 'v'=>32, 'maxNumber'=>32, 'award'=>'四等奖'),
            '5' => array('id'=>41, 'prize'=>'20', 'v'=>80, 'maxNumber'=>80, 'award'=>'纪念奖'),
        ),
        //
//        14 => array(
//            '1' => array('id'=>42, 'prize'=>'3000', 'v'=>4, 'maxNumber'=>4, 'award'=>'一等奖'),
//            '2' => array('id'=>43, 'prize'=>'500', 'v'=>5, 'maxNumber'=>5, 'award'=>'二等奖'),
//            '3' => array('id'=>44, 'prize'=>'100', 'v'=>30, 'maxNumber'=>30, 'award'=>'三等奖'),
//            '4' => array('id'=>45, 'prize'=>'50', 'v'=>60, 'maxNumber'=>60, 'award'=>'四等奖'),
//            '5' => array('id'=>46, 'prize'=>'20', 'v'=>102, 'maxNumber'=>102, 'award'=>'纪念奖'),
//        )
//        15 => array(
//            '1' => array('id'=>47, 'prize'=>'200', 'v'=>5, 'maxNumber'=>5, 'award'=>'一等奖'),
//            '2' => array('id'=>48, 'prize'=>'150', 'v'=>10, 'maxNumber'=>10, 'award'=>'二等奖'),
//            '3' => array('id'=>49, 'prize'=>'100', 'v'=>15, 'maxNumber'=>15, 'award'=>'三等奖'),
//            '4' => array('id'=>50, 'prize'=>'50', 'v'=>55, 'maxNumber'=>55, 'award'=>'四等奖'),
//            '5' => array('id'=>51, 'prize'=>'0', 'v'=>120, 'maxNumber'=>120, 'award'=>'谢谢参与'),
//        ),
        16 => array(
            '1' => array('id'=>52, 'prize'=>'5', 'v'=>200, 'maxNumber'=>200, 'award'=>'一等奖'),
            '2' => array('id'=>53, 'prize'=>'4', 'v'=>400, 'maxNumber'=>400, 'award'=>'二等奖'),
            '3' => array('id'=>54, 'prize'=>'3', 'v'=>1000, 'maxNumber'=>1000, 'award'=>'三等奖'),
            '4' => array('id'=>55, 'prize'=>'2', 'v'=>2000, 'maxNumber'=>2000, 'award'=>'四等奖'),
            '5' => array('id'=>56, 'prize'=>'1', 'v'=>3000, 'maxNumber'=>3000, 'award'=>'五等奖'),
        ),
    );

    /**
     * 验证消息的确来自微信服务器
     */
    public function actionCheckSign()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        //测试
        //$token = 'ColourLifeTest1778';
        //正式
        $token = 'CaiZhuZhai';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature  ) {
            echo $_GET["echostr"];
            exit;
        }
    }

    public function actionIndex($id)
    {
        $activityId = intval($id);
        if ( !in_array($activityId, array_keys($this->prize)))
            exit('非法请求');

//        switch ($activityId) {
//            case 10:
//                $view = '/v2016/caiZhuZhai/wc';
//                break;
//            case 11:
//                $view = '/v2016/caiZhuZhai/yh';
//                break;
//            case 12:
//                $view = '/v2016/caiZhuZhai/cj';
//                break;
//        }


        $view = '/v2016/caiZhuZhai/cj';

        $this->renderPartial($view, array('activity_id' => $activityId, 'prize' => $this->prize[$id]));
    }

    public function actionIntroduce()
    {
        $this->renderPartial('/v2016/caiZhuZhai/rule');
    }

    /**
     * 抽奖
     * @param $id
     */
    public function actionAjaxLottery($id)
    {
        $activityId = intval($id);
    	if ( !in_array($activityId, array_keys($this->prize)))
            exit('非法请求');

        // 判断是否已经抽过奖
        $openId = $_SESSION['wechat_user']['openid'];
        $sql = 'SELECT `id`, `status` FROM `czz_activity_luck_list` WHERE `open_id`=:open_id AND `activity_id`=:activity_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':open_id', $openId, PDO::PARAM_STR);
        $command->bindParam(':activity_id', $activityId, PDO::PARAM_INT);

        $result = $command->queryRow();

        if ($result) {
            if (1 == $result['status']) {
                $this->output('', 0, 1);
            }
            else {
                $this->output('', 0, 4);
            }
        }

        // 抽奖奖品
        $prize_arr = $this->prize[$activityId];

        $sql = 'SELECT `type_id`, COUNT(`id`) AS `total` FROM `czz_activity_luck_list` WHERE `activity_id`=:activity_id GROUP BY `type_id`';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':activity_id', $activityId, PDO::PARAM_INT);
        $result = $command->queryAll();

        if ($result) {
            foreach ($result as $row) {
                foreach ($prize_arr as $key => $val) {
                    if ($row['type_id']==$val['id'] && $row['total'] > $val['maxNumber'])
                        unset($prize_arr[$key]);
                }
            }
        }
        if ( empty($prize_arr))
            $this->output('', 0, 2);


        $prizeId = $this->lottery($prize_arr);
        $awards = $prize_arr[$prizeId];
        //dump($awards);

        $data = array(
            'open_id'       => $openId,
            'type_id'       => $awards['id'],
            'value'         => $awards['prize'],
            'activity_id'   => $activityId,
            'create_time'   => time()
        );

        $res = Yii::app()->db->createCommand()->insert('czz_activity_luck_list', $data);
        if ( !$res)
            $this->output('', 0, 3);

        $outData = array(
            'prize_id'      => $prizeId,
            'type_id'       => $awards['id'],
            'value'         => $awards['prize'],
            'award'         => $awards['award'],
            'open_id'       => $_SESSION['wechat_user']['openid'],
            'valid_time'    => date('Y年m月d日').'~'.date('Y年m月d日', strtotime('+15'.' days'))
        );

        $this->output($outData);
    }

    public function actionReceive($id)
    {
        $activityId = intval($id);
        if ( !in_array($activityId, array_keys($this->prize)))
            exit('非法请求');

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $mobile = Yii::app()->request->getParam('mobile');
            if ( !preg_match('/^1[3|4|5|7|8][0-9]\d{8}$/', $mobile))
                $this->output('', 0, '手机号码错误');

            $model = new SmsForm;
            $model->setScenario('check');
            $model->attributes = Yii::app()->request->restParams;

            $num = Item::SMS_LIMIT_VALIDATE;
            //检查次数
            $count = $model->GetBlackValidateNum();
            if ($count >= $num)
                $this->output('', 0, '您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');

            if (!$model->validate())
                $this->output('', 0, $this->errorSummary($model));

            $model->useCode();

            // 同一手机号码只能领取一次
            $sql = 'SELECT `id` FROM `ticket` WHERE `category_id`=:activity_id AND `receive_mobile`=:mobile';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $command->bindParam(':activity_id', $activityId, PDO::PARAM_INT);
            $result = $command->queryRow();
            if ($result)
                $this->output('', 0, '手机号码已领取过');

            if ( false === $this->makeTicket($mobile, $activityId) )
                $this->output('', 0, '领取失败，请稍后重试...');

            $this->output(array('msg' => '领取成功'));
        }
        else {
            //$sql = 'SELECT `id`, `name` FROM `czz_building`';
            //$result = Yii::app()->db->createCommand($sql)->queryAll();
            //dump($result);

            $this->renderPartial('/v2016/caiZhuZhai/receive', array('activity_id' => $activityId));
        }
    }

    public function actionTip()
    {
        $id =  Yii::app()->request->getParam('ticket_id');
        $model = MealTicket::model()->findByPk($id);

        if (!$model)
            $this->output('', 0, '饭票不存在');

        $result = array(
            'id' => $model->id,
            'award' => $model->value,
            'valid_time' =>  date('Y年m月d日', $model->create_time).'~'.date('Y年m月d日', ($model->create_time + (86400 * 15)))
        );

        $this->renderPartial('/v2016/caiZhuZhai/tip', array(
            'result' => $result,
        ));
    }

    public function actionAppReceive($id)
    {
        $model = MealTicket::model()->findByPk($id);
        if ( !$model)
            $this->output('', 0, '券不存在');

        if ($model->status == MealTicket::MEAL_TICKET_RECEIVE)
            $this->output('领取成功');

        if ($model->status != MealTicket::MEAL_TICKET_SEND)
            $this->output('', 0, '券不能领取');

        $receiveMobile = Yii::app()->session['logged_user']->mobile;
        $receiveId = Yii::app()->session['logged_user']->id;
        if ($model->receive_mobile != $receiveMobile)
            $this->output('', 0, '不是自己的券');

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $cmobile_id = 2362481;
            $cmobile = '20000000007';
            $customer_id = $receiveId;
            $amount = $model->value;
            $note = '彩住宅幸运大转盘';

            $rebateResult = RedPacketCarry::model()->customerTransferAccounts($cmobile_id, $customer_id, $amount, 1, $cmobile, $note);

            if (1 ==$rebateResult['status']) {
                MealTicket::model()->updateByPk($model->id, array('status'=>MealTicket::MEAL_TICKET_RECEIVE, 'receive_id'=>$receiveId, 'receive_time'=>time(), 'tip'=>1));
            }
            else {
                $transaction->rollback();
                $this->output('', 0, '饭票券领取失败-1');
            }
        }
        catch (Exception $e) {
            $transaction->rollback();
            $this->output('', 0, '饭票券领取失败-2');
        }

        $transaction->commit();

        $this->output(array('msg'=>'领取成功'));
    }

    /**
     * 发送验证码
     */
    public function actionSendCode()
    {
        $model = new SmsForm();
        $model->setScenario('verifyMobile');

        $model->attributes = Yii::app()->request->restParams;

        if (!$model->validate())
            $this->output('', 0, $this->errorSummary($model));

        //检查次数
        $num = Item::SMS_LIMIT_VALIDATE;
        $count = $model->GetBlackValidateNum();
        if ($count >= $num)
            $this->output('', 0, '您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');

        if ( !$model->send())
            $this->output('', 0, $this->errorSummary($model));

        $this->output(array('msg'=>'发送成功'));
    }

    private function makeTicket($mobile, $activityId)
    {
        //$buildingArr = explode('|', $building);

        // 判断是否已经抽过奖
        $openId = $_SESSION['wechat_user']['openid'];
        $sql = 'SELECT `c`.`id`,`t`.`id` AS `type_id`, `t`.`category_id`, `t`.`value` FROM `czz_activity_luck_list` AS `c` INNER JOIN `ticket_type` AS `t` ON (`c`.`type_id`=`t`.`id`) WHERE `c`.`open_id`=:open_id AND `c`.`status`=0 AND `c`.`activity_id`=:activity_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':open_id', $openId, PDO::PARAM_STR);
        $command->bindParam(':activity_id', $activityId, PDO::PARAM_INT);
        $result = $command->queryRow();

        if ( !$result )
            return false;

        $updateData = array(
            'status'        => 1,
            //'buliding_id'   => $buildingArr[0],
            //'buliding_name' => $buildingArr[1],
        );

        $saveData = array(
            'sn' => 0,
            'category_id' => $result['category_id'],
            'type_id' => $result['type_id'],
            'value' => $result['value'],
            'status' => 1,
            'owner_id' => 0,
            'receive_mobile' => $mobile,
            'note' => '彩住宅饭票券',
            'create_time' => time(),
            'send_time' => time(),
            'tip' => 0
        );

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        $command = $connection->createCommand();
        
        try {
            $command->insert('ticket', $saveData);
            $ticketId = $connection->getLastInsertID();

            $updateData['relation_id'] = $ticketId;
            $command->update('czz_activity_luck_list', $updateData, "id=:id", array(':id'=>$result['id']));

            $transaction->commit();

            return true;
        }
        catch (Exception $e) {
            $transaction->rollback();
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'colourlife.core.CaiZhuZhaiActivity');
            return false;
        }
    }

    private function lottery($prize_arr)
    {
        /*
         * 每次前端页面的请求，PHP循环奖项设置数组
         * 通过概率计算函数get_rand获取抽中的奖项id。
         * 将中奖奖品保存在数组$res['yes']中
         * 而剩下的未中奖的信息保存在$res['no']中
         * 最后输出json个数数据给前端页面。
         */
        $tempArr = array();
        foreach ($prize_arr as $key => $val) {
            $tempArr[$key] = $val['v'];
        }

        return $rid = $this->getRand($tempArr); //根据概率获取奖项id

        //return $prize_arr[$rid];
    }

    private function getRand($proArr)
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}
