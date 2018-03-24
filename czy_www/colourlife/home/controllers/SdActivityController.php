<?php
/**
 * Created by PhpStorm.
 * User: Roy
 * Date: 2016/12/7
 * Time: 9:55
 */
class SdActivityController extends ActivityController
{
    public $secret = 'sIU#rs8742*$s';
    public $beginTime = '2016-12-20';
    public $endTime = '2017-01-09 23:59:59';

    public function filters()
    {
        return array(
            'accessControl',
            //'Validity',
            'signAuth - Share, Receive, SendCode',
        );
    }

    // 宝箱  宝箱ID => 开启宝箱所需的卡片
    protected $chest = array(
        1 => 2,  // 铜宝箱
        2 => 4,  // 银宝箱
        3 => 6   // 金宝箱
    );

    /*
     * 奖项配置
     * 是一个二维数组，记录了所有本次抽奖的奖项信息，
     * 其中id表示中奖等级，prize表示奖品，v表示中奖概率。
     * 注意其中的v必须为整数，你可以将对应的 奖项的v设置成0，即意味着该奖项抽中的几率是0，
     * 数组中v的总和（基数），基数越大越能体现概率的准确性。
     * 本例中v的总和为100，那么平板电脑对应的 中奖概率就是1%，
     * 如果v的总和是10000，那中奖概率就是万分之一了。
     */
    protected $prize_config = array(
        '1' => array('id'=>1, 'v'=>1, 'maxNumber'=>1, 'award'=>'彩之云充电宝'),
        '2' => array('id'=>2, 'v'=>2, 'maxNumber'=>2, 'award'=>'彩之云U盘'),
        '3' => array('id'=>3, 'v'=>97, 'maxNumber'=>10000, 'award'=>'彩特供满100减5元优惠券'),
    );

    protected $prize = array(
        '1' => array('id'=>1, 'name'=>'彩之云充电宝', 'image'=>'xiaomi.png'),
        '2' => array('id'=>2, 'name'=>'彩之云U盘', 'image'=>'u.png'),
        '3' => array('id'=>3, 'name'=>'彩特供满100减5元优惠券', 'image'=>'youhuijuan.png')
    );

    public function actionIndex()
    {

        $userId = $this->getUserId();

        $allCards = $this->getCard();
        $userCards = $this->countUserCard($userId);

        $cards = array();
        // 处理首页拼图数据
        foreach ($allCards as $card) {
            $cards[] = array(
                "id" => $card['id'],
                "image" => $card['image'],
                "number" =>  array_key_exists($card['id'], $userCards) ? $userCards[$card['id']] : 0
            );
        }

        // 用户拥有卡片种类数
        $countCardTypes = count($userCards);

        $chestLog = $this->countUserChest($userId);
        $chestLogIds = array_keys($chestLog);

        // 处理首页宝箱数据
        $chest = array();
        foreach ($this->chest as $key => $val) {
            $row = array(
                'id' => $key,
                'status' => 0  // 0-不可开启 1-可以开启 2-已开启
            );
            if ($countCardTypes >= $val)
                $row['status'] = 1;
            if (in_array($key, $chestLogIds))
                $row['status'] = 2;

            $chest[] = $row;
        }

        $outData['cards'] = $cards;
        $outData['chest'] = $chest;

        // 弹窗
        $tips = $this->requestTips($userId);
        if (0 == $countCardTypes && false == $tips)
            $tips = array(
                'id' => 0,
                'image' => F::getStaticsUrl('/activity/v2016/sd/images/pop/chest.png'),
                'text' => '在彩之云内寻找遗失的拼图，集齐6张不同的拼图即可瓜分10万彩饭票',
                'button_text' => '去看看',
                'url' => '/SdActivity/Guide'
            );

        $outData['tips'] = $tips;
        $outData['countdown_time'] = strtotime($this->endTime) - time();

        //$countSql = 'SELECT COUNT(`id`) FROM `activity_shuangdan_chest_log` WHERE `chest_type`=3';
        //$fullNumber = Yii::app()->db->createCommand($countSql)->queryScalar();
        $fullNumber = (date('H') * 83) + date('i') + ceil((time() - strtotime('2017-01-04')) / 86400 ) * 2000;

        if ($fullNumber > 12047)
            $fullNumber = 12047;

        $outData['full_number'] = $fullNumber;
        $outData['fp'] = 8.30;

        //echo json_encode($outData);exit;

        $this->renderPartial('/v2016/sd/index', array('outData'=>json_encode($outData)));
    }

    /**
     * 开启宝箱
     */
    public function actionChest()
    {
        $chestType = Yii::app()->request->getParam('chest_type');
        $userId = $this->getUserId();

        if ( !in_array($chestType, array_keys($this->chest)))
            $this->output('', 0, '宝箱类型错误');

        $userCards = $this->countUserCard($userId);
        // 用户拥有卡片种类数
        $countCardTypes = count($userCards);
        if ($countCardTypes < $this->chest[$chestType])
            $this->output('', 0, '聚齐'.$this->chest[$chestType].'张拼图才可以开启宝箱');

        // 每个宝箱只能被开启一次
        $sql = 'SELECT * FROM `activity_shuangdan_chest_log` WHERE `user_id`=:user_id AND `chest_type`=:chest_type';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $command->bindParam(':chest_type', $chestType, PDO::PARAM_INT);

        // 已开过宝箱
        $chest = $command->queryRow();
        if ($chest) {
            $tips = array(
                'image' => '',
                'text' => '',
                'button_text' => '确定',
            );
            switch ($chestType) {
                case 1:
                    $card = $this->getCard($chest['prize_id']);
                    $tips['image'] = $card['pop_image'];
                    $tips['text'] = '已获得一张拼图';
                    break;
                case 2:
                    $prize = $this->prize[$chest['prize_id']];
                    $tips['image'] = F::getStaticsUrl('/activity/v2016/sd/images/pop/').$prize['image'];
                    $tips['text'] = '已获得'.$prize['name'];
                    break;
                case 3:
                    $tips['image'] = F::getStaticsUrl('/activity/v2016/sd/images/pop/fp.png');
                    $tips['text'] = '已获得1彩饭票';
                    break;
            }

            $this->output($tips);
        }


        // 开宝箱
        $result = $this->openChest($userId, $chestType);
        if (false == $result)
            $this->output('', 0, '系统繁忙...');

        $this->output($result);
    }

    /**
     * 求赠
     */
    public function actionRequest()
    {
        $cardId = Yii::app()->request->getParam('card_id');
        $mobile = Yii::app()->request->getParam('mobile');

        if ( !preg_match('/^1[3|4|5|7|8][0-9]\d{8}$/', $mobile))
            $this->output('', 0, '手机号码输入错误');

        $cards = $this->getCard();
        if ( !in_array($cardId, array_keys($cards)))
            $this->output('', 0, '无效的拼图ID');

        $responseUserInfo = Customer::model()->find('mobile=:mobile', array(':mobile'=>$mobile));
        if ( !$responseUserInfo)
            $this->output('', 2, '对方未注册彩之云账号');

        $userInfo = $this->getUserInfo();

        $saveData = array(
            'card_id' => $cardId,
            'request_user_id' => $userInfo->id,
            'request_user_mobile' => $userInfo->mobile,
            'response_user_id' => $responseUserInfo->id,
            'response_user_mobile' => $responseUserInfo->mobile,
            'status' => 0,
            'tip_value' => 0,
            'create_time' => time(),
            'update_time' => time()
        );

        $result = Yii::app()->db->createCommand()->insert('activity_shuangdan_user_request', $saveData);
        if ( !$result)
            $this->output('', 0, '系统繁忙...');

        $this->output(array('request_id'=>$result));
    }

    /**
     * 回复求赠请求
     * @throws CException
     */
    public function actionReply()
    {
        $requestId = (int)Yii::app()->request->getParam('request_id');

        $sql = 'SELECT * FROM `activity_shuangdan_user_request` WHERE `id`=:id AND `status`=0';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':id', $requestId, PDO::PARAM_INT);
        $requestInfo = $command->queryRow();

        if (!$requestInfo)
            exit('参数错误');

        $cardId = $requestInfo['card_id'];
        $userId = $this->getUserId();

        if ($userId != $requestInfo['response_user_id'])
            exit('参数错误，不是自己的');

        $userCardSql = 'SELECT COUNT(`id`) FROM `activity_shuangdan_user_card` WHERE `user_id`=:user_id AND `status`=0 AND `card_id`=:card_id';
        $command = Yii::app()->db->createCommand($userCardSql);
        $command->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $command->bindParam(':card_id', $cardId, PDO::PARAM_INT);
        $userCardNumber = $command->queryScalar();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $replyCode = Yii::app()->request->getParam('reply_code');
            if ('true' == $replyCode) {
                if ($userCardNumber < 1)
                    $this->output('', 0, '没有多余的该张拼图');

                $connection = Yii::app()->db;
                $transaction = $connection->beginTransaction();

                try{
                    $updateRequestData = array(
                        'status' => 2,
                        'tip_value' => 0,
                        'update_time' => time()
                    );

                    $updateUserCardData = array(
                        'status' => 1,
                        'update_time' => time()
                    );

                    $saveData = array(
                        'user_id'       => $requestInfo['request_user_id'],
                        'card_id'       => $cardId,
                        'status'        => 0,
                        'source'        => 2,
                        'source_log'    => $requestId,
                        'create_time'   => time(),
                        'update_time'   => time(),
                    );

                    // 更新用户卡片为已赠送
                    Yii::app()->db->createCommand()->update('activity_shuangdan_user_card', $updateUserCardData, 'user_id=:user_id AND card_id=:card_id AND status=0 LIMIT 1', array(':user_id'=>$userId, ':card_id'=>$cardId));
                    // 更新请求记录
                    Yii::app()->db->createCommand()->update('activity_shuangdan_user_request', $updateRequestData, 'id=:id', array(':id'=>$requestId));
                    // 新增卡片
                    Yii::app()->db->createCommand()->insert('activity_shuangdan_user_card', $saveData);
                }
                catch (Exception $e) {
                    $transaction->rollback();

                    $this->output('', 0, '赠送失败');
                }

                $transaction->commit();

                $this->output(array('msg'=>'赠送成功'));

            }
            elseif ('false' == $replyCode) {
                $updateData = array(
                    'status' => 1,
                    'tip_value' => 0,
                    'update_time' => time()
                );

                try {
                    Yii::app()->db->createCommand()->update('activity_shuangdan_user_request', $updateData, 'id=:id', array(':id'=>$requestId));
                }
                catch (Exception $e) {
                    $this->output('', 0, '拒绝失败');
                }

                $this->output(array('msg'=>'成功拒绝'));
            }
            else {
                $this->output('', 0, '回复类型参数错误');
            }

        }
        else {

            $cardInfo = $this->getCard($cardId);

            $result = array(
                'card' => array(
                    'image' => $cardInfo['image'],
                    'number' => $userCardNumber
                ),
                'request' => array(
                    'id' => $requestInfo['id'],
                    'request_user_id' => $requestInfo['request_user_id'],
                    'request_user_mobile' => $requestInfo['request_user_mobile']
                )
            );

            //echo json_encode($result);exit;

            $this->renderPartial('/v2016/sd/shareQiu', array('outData'=>json_encode($result)));
        }
    }

    /**
     * 赠送
     */
    public function actionResponse()
    {
        $cardId = Yii::app()->request->getParam('card_id');
        $number = (int)Yii::app()->request->getParam('number');
        $userId = $this->getUserId();

        $cards = $this->getCard();
        if ( !in_array($cardId, array_keys($cards)))
            $this->output('', 0, '无效的拼图ID');

        if ($number < 1)
            $this->output('', 0, '赠送拼图数量错误');

        $sql = 'SELECT COUNT(`id`) FROM `activity_shuangdan_user_card` WHERE `user_id`=:user_id AND `card_id`=:card_id AND `status`=0';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $command->bindParam(':card_id', $cardId, PDO::PARAM_INT);
        $total = $command->queryScalar();

        if ( $total < $number)
            $this->output('', 0, '该拼图数量不足');

        $userInfo = $this->getUserInfo();
        $saveData = array(
            'user_id' => $userInfo->id,
            'user_mobile' => $userInfo->mobile,
            'card_id' => $cardId,
            'share_number' => $number,
            'surplus_number' => $number,
            'create_time' => time(),
            'update_time' => time()
        );

        $updateData = array(
            'status' => 1,
            'update_time' => time()
        );

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try {
            // 更新用户卡片为已赠送
            Yii::app()->db->createCommand()->update('activity_shuangdan_user_card', $updateData, 'user_id=:user_id AND card_id=:card_id AND status=0 LIMIT '.$number, array(':user_id'=>$userId, ':card_id'=>$cardId));
            // 分享记录
            Yii::app()->db->createCommand()->insert('activity_shuangdan_user_share', $saveData);
            $shardId = Yii::app()->db->getLastInsertID();
        }
        catch (Exception $e) {
            $transaction->rollback();

            $this->output('', 0, '分享失败');
        }

        $transaction->commit();

        $result = array(
            'url' => base64_encode(F::getHomeUrl('/SdActivity/Receive').'?shard_id='.$shardId)
        );

        $this->output($result);
    }

    /**
     * 寻宝指引
     * @throws CException
     */
    public function actionGuide()
    {
        $userId = $this->getUserId();
        $czzResource = HomeConfigResource::model()->getResourceByKeyOrId(87, 1, $userId);
        $ctgResource = HomeConfigResource::model()->getResourceByKeyOrId(39, 1, $userId);
        $result = array(
            'czz_url' => $czzResource->completeURL,
            'ctg_url' => $ctgResource->completeURL
        );

        $this->renderPartial('/v2016/sd/treasureToGuide', array('outData'=>json_encode($result)));
    }

    /**
     * 分享领取页面
     * @throws CException
     */
    public function actionReceive()
    {
        $shardId = Yii::app()->request->getParam('shard_id');

        $sql = 'SELECT * FROM `activity_shuangdan_user_share` WHERE `id`=:id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':id', $shardId, PDO::PARAM_INT);
        $shardInfo = $command->queryRow();
        if ( !$shardInfo)
            $this->output('', 0, '分享不存在');

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $mobile = Yii::app()->request->getParam('mobile');
            $receiveUserInfo = Customer::model()->find('mobile=:mobile', array(':mobile'=>$mobile));

            if ($shardInfo['surplus_number'] < 1)
                $this->output('', 0, '已领取完');

            // 未注册的先自动注册
            if ( !$receiveUserInfo) {
                $code = Yii::app()->request->getParam('code');
                if (empty($code))
                    $this->output('', 2, '该手机号未注册彩之云');

                $checkMsg = $this->checkCode($mobile, $code);
                if (true !== $checkMsg)
                    $this->output('', 0, $checkMsg);

                $receiveUserInfo = $this->register($mobile);
                if (false === $receiveUserInfo)
                    $this->output('', 0, '系统繁忙');
            }

            $saveData = array(
                'user_id'       => $receiveUserInfo->id,
                'card_id'       => $shardInfo['card_id'],
                'status'        => 0,
                'source'        => 1,
                'source_log'    => $shardId,
                'create_time'   => time(),
                'update_time'   => time(),
            );

            $updateData = array(
                'surplus_number' => new CDbExpression('surplus_number-1'),
                'update_time' => time()
            );

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            $command = $connection->createCommand();

            try {
                $command->insert('activity_shuangdan_user_card', $saveData);
                $command->update('activity_shuangdan_user_share', $updateData, 'id=:id', array(':id'=>$shardId));
            }
            catch (Exception $e) {
                $transaction->rollback();

                $this->output('', 0, '系统繁忙');
            }

            $transaction->commit();

            $this->output(array('msg'=>'领取成功'));
        }
        else {
            $cardInfo = $this->getCard($shardInfo['card_id']);

            $result = array(
                'shard_id' => $shardInfo['id'],
                'user_mobile' => $shardInfo['user_mobile'],
                'card_image' => $cardInfo['image'],
                'share_number' => $shardInfo['share_number'],
                'surplus_number' => $shardInfo['surplus_number'],
            );

            //echo json_encode($result); exit;

            $this->renderPartial('/v2016/sd/share', array('outData'=>json_encode($result)));
        }

    }

    /**
     * 获取拼图
     * @throws CException
     */
    public function actionTip()
    {
        $sectionId = (int)Yii::app()->request->getParam('section_id');

        $sql = 'SELECT `cards` FROM `activity_shuangdan_tip_config` WHERE `section_id`=:section_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':section_id', $sectionId, PDO::PARAM_INT);
        $cards = $command->queryScalar();

        //$cards = '3,4,5';

        $randCards = array(1,2,3,4,5,6);
        if ($cards)
            $randCards = explode(',', $cards);

        $sql = 'SELECT COUNT(`id`) FROM `activity_shuangdan_user_card` WHERE `card_id`=6 AND `status`<>1';
        $total = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($total > 200) {
            if ($unsetKey = array_search(6, $randCards))
                unset($randCards[$unsetKey]);
        }

        $cardIdKey = array_rand($randCards, 1);
        $cardId = (int)$randCards[$cardIdKey];
        $userId = $this->getUserId();

        $saveData = array(
            'user_id'       => $userId,
            'card_id'       => $cardId,
            'status'        => 0,
            'source'        => 0,
            'source_log'    => $sectionId,
            'create_time'   => time(),
            'update_time'   => time(),
        );

        Yii::app()->db->createCommand()->insert('activity_shuangdan_user_card', $saveData);

        $cardInfo = $this->getCard($cardId);

        $result = array(
            'tip' => array(
                'image' => $cardInfo['image'],
                'text' => '恭喜您获得一张拼图',
                'button_text' => '确定',
            )
        );

        //echo json_encode($result);exit;

        $this->renderPartial('/v2016/sd/popCard', array('outData'=>json_encode($result)));
    }

    /**
     * 用户卡片记录
     * @param $userId
     * @return array
     */
    private function countUserCard($userId)
    {
        $userSql = 'SELECT `card_id`, COUNT(`id`) AS `total` FROM `activity_shuangdan_user_card` WHERE `user_id`=:user_id AND (`status`=0 OR `status`=2) GROUP BY `card_id`';
        $command = Yii::app()->db->createCommand($userSql);
        $command->bindParam(':user_id', $userId, PDO::PARAM_INT);

        $userCards = $command->queryAll();

        $result = array();
        foreach ($userCards as $row)
            $result[$row['card_id']] = $row['total'];

        return $result;
    }

    /**
     * 获取所有拼图
     * @param string $cardId
     * @return array
     */
    private function getCard($cardId='')
    {
        $cardsCacheKey = 'cache:home:activity:cards:all';

        $cards = Yii::app()->rediscache->get($cardsCacheKey);
        if (empty($cards)) {
            $cardSql = 'SELECT * FROM `activity_shuangdan_card` ORDER BY `sort` ASC';
            $cardList = Yii::app()->db->createCommand($cardSql)->queryAll();
            $cards = array();
            foreach ($cardList as $card) {
                $card['pop_image'] = F::getStaticsUrl('/activity/v2016/sd/images/pop/').$card['image'];
                $card['image'] = F::getStaticsUrl('/activity/v2016/sd/images/card/').$card['image'];
                $cards[$card['id']] = $card;
            }

//            Yii::app()->rediscache->set(
//                $cardsCacheKey,
//                $cards,
//                ICEService::GetCacheExpire(86400)
//            );
        }

        if ($cardId)
            return $cards[$cardId];

        return $cards;
    }

    /**
     * 用户开取宝箱记录
     * @param $userId
     * @return array
     */
    private function countUserChest($userId)
    {
        $sql = 'SELECT `chest_type`,`prize_id`,`prize_status` FROM `activity_shuangdan_chest_log` WHERE `user_id`=:user_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $chestLog = $command->queryAll();

        $result = array();
        foreach ($chestLog as $chest)
            $result[$chest['chest_type']] = $chest;

        return $result;
    }

    /**
     * 活动首页弹窗提醒
     * @param $userId
     * @return array|bool
     */
    private function requestTips($userId)
    {
        $sql = 'SELECT * FROM `activity_shuangdan_user_request` WHERE (`request_user_id`=:request_user_id OR `response_user_id`=:response_user_id) and `tip_value`=0';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':request_user_id', $userId, PDO::PARAM_INT);
        $command->bindParam(':response_user_id', $userId, PDO::PARAM_INT);
        $lists = $command->queryAll();


        if ($lists) {
            // 是否更新提醒状态
            $isUpdate = false;
            $chestLogId = 0;
            foreach ($lists as $chestLog) {

                $chestLogId = $chestLog['id'];

                $card = $this->getCard($chestLog['card_id']);

                $tip = array(
                    'id' => $chestLog['id'],
                    'image' => $card['pop_image'],
                    'text' => '',
                    'button_text' => '确定',
                    'url' => ''
                );

                if ($userId == $chestLog['response_user_id'] && 0 == $chestLog['status']) {
                    $tip['text'] = substr_replace($chestLog['request_user_mobile'], '****', 3, 4).'向您求赠一张拼图';
                    $tip['button_text'] = '去看看';
                    $tip['url'] = '/SdActivity/Reply?request_id='.$chestLog['id'];
                    $isUpdate = true;
                    break;
                }
                else if ($userId == $chestLog['request_user_id']) {
                    // 被拒绝
                    if (1 == $chestLog['status']) {
                        $tip['text'] = substr_replace($chestLog['response_user_mobile'], '****', 3, 4).'拒绝了您的求赠请求';
                        $tip['image'] = F::getStaticsUrl('/activity/v2016/sd/images/pop/refused.png');
                        $isUpdate = true;
                        break;
                    }
                    // 已赠送
                    else if (2 == $chestLog['status']) {
                        $tip['text'] = $chestLog['response_user_mobile'].'用户赠送了您一张拼图';
                        $isUpdate = true;
                        break;
                    }
                }
            }

            if (true === $isUpdate) {
                $updateSql = 'UPDATE `activity_shuangdan_user_request` SET `tip_value`=1 WHERE `id`=:id';
                $result = Yii::app()->db
                    ->createCommand($updateSql)
                    ->bindValues(array(':id' => $chestLogId))
                    ->execute();

                return $tip;
            }

        }

        return false;
    }

    /**
     * 开启宝箱
     * @param $userId
     * @param $chestId
     * @return array|bool
     */
    private function openChest($userId, $chestId)
    {
        $result = false;
        switch ($chestId) {
            case 1:
                $result = $this->chestOne($userId);
                break;
            case 2:
                $result = $this->chestTwo($userId);
                break;
            case 3:
                $result = $this->chestThree($userId);
                break;
        }

        return $result;
    }

    /**
     * 开启1号宝箱
     * @param $userId
     * @return array|bool
     */
    private function chestOne($userId)
    {
        $cards = $this->getCard();
        unset($cards[6]);
        $lotteryKey = array_rand($cards, 1);
        $lotteryCard = $cards[$lotteryKey];

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        $chestLogData = array(
            'user_id' => $userId,
            'chest_type' => 1,
            'prize_id' => $lotteryCard['id'],
            'prize_status' => 1,
            'create_time'   => time(),
            'update_time'   => time(),
        );

        $userCardData = array(
            'user_id'       => $userId,
            'card_id'       => $lotteryCard['id'],
            'status'        => 0,
            'source'        => 3,
            'source_log'    => '',
            'create_time'   => time(),
            'update_time'   => time(),
        );

        $command = $connection->createCommand();
        try {
            // 第一步 锁定开取宝箱所需要的拼图状态
            $this->lockUserCard($userId, 2);

            // 第二步 记录用户开启宝箱日志
            $command->insert('activity_shuangdan_chest_log', $chestLogData);
            $chestLogId = Yii::app()->db->getLastInsertID();

            // 第三步 开启宝箱奖品处理
            $userCardData['source_log'] = $chestLogId;
            $command->insert('activity_shuangdan_user_card', $userCardData);
        }
        catch (Exception $e) {
            $transaction->rollback();

            return false;
        }

        $transaction->commit();

        return array(
            'image' => $lotteryCard['pop_image'],
            'text' => '恭喜您获得一张拼图',
            'button_text' => '确定',
        );
    }

    /**
     * 开启2号宝箱
     * @param $userId
     * @return array|bool
     */
    private function chestTwo($userId)
    {
        // 剔除已发完的奖品
        $createTime = mktime(0,0,0);
        $sql = 'SELECT `prize_id`, COUNT(`id`) AS `total` FROM `activity_shuangdan_chest_log` WHERE `chest_type`=2 AND `create_time`>:create_time GROUP BY `prize_id`;';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':create_time', $createTime, PDO::PARAM_INT);
        $result = $command->queryAll();

        if ($result) {
            foreach ($result as $row) {
                if ($row['total'] >= $this->prize_config[$row['prize_id']]['maxNumber'])
                    unset($this->prize_config[$row['prize_id']]);
            }
        }

        $rid = $this->getRand(); //根据概率获取奖项id
        $prize = $this->prize[$rid];
        $prizeStatus = 0;

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        if (3 == $rid) {
            $userInfo = Customer::model()->findByPk($userId);
            $coupon = array(
                'mobile' => $userInfo->mobile,
                'you_hui_quan_id' => 100000154,
                'is_use' => 0,
                'num' => 0,
                'create_time' => time()
            );

            if ($command->insert('user_coupons', $coupon))
                $prizeStatus = 1;
        }

        $chestLogData = array(
            'user_id' => $userId,
            'chest_type' => 2,
            'prize_id' => $prize['id'],
            'prize_status' => $prizeStatus,
            'create_time'   => time(),
            'update_time'   => time(),
        );

        $command = $connection->createCommand();
        try {
            // 第一步 锁定开取宝箱所需要的拼图状态
            $this->lockUserCard($userId, 4);

            // 第二步 记录用户开启宝箱日志
            $command->insert('activity_shuangdan_chest_log', $chestLogData);
        }
        catch (Exception $e) {
            $transaction->rollback();
            return false;
        }

        $transaction->commit();

        return array(
            'image' => F::getStaticsUrl('/activity/v2016/sd/images/pop/').$prize['image'],
            'text' => '恭喜您获得'.$prize['name'],
            'button_text' => '确定',
        );

    }

    private function getRand()
    {
        $arr = array();
        foreach ($this->prize_config as $key => $val)
            $arr[$val['id']] = $val['v'];

        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($arr);
        //概率数组循环
        foreach ($arr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($arr);

        return $result;
    }

    /**
     * 开启3号宝箱
     * @param $userId
     * @return array|bool
     */
    private function chestThree($userId)
    {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        $chestLogData = array(
            'user_id' => $userId,
            'chest_type' => 3,
            'prize_id' => 1,
            'prize_status' => 0,
            'create_time'   => time(),
            'update_time'   => time(),
        );

        $command = $connection->createCommand();

        try {
            // 第一步 锁定开取宝箱所需要的拼图状态
            $this->lockUserCard($userId, 6);

            // 第二步 记录用户开启宝箱日志
            $chestLogId = $command->insert('activity_shuangdan_chest_log', $chestLogData);
        }
        catch (Exception $e) {
            $transaction->rollback();
            return false;
        }

        $transaction->commit();

        // 发饭票
        $cmobile_id = 2224375;
        $cmobile = '20000000005';
        $customer_id = $userId;
        $amount = 1;
        $note = '双旦活动开启金宝箱';

        $rebateResult = RedPacketCarry::model()->customerTransferAccounts($cmobile_id, $customer_id, $amount, 1, $cmobile, $note);

        if (1 ==$rebateResult['status'])
            $command->update('activity_shuangdan_chest_log', array('prize_status'=>1), "id=:id", array(':id'=>$chestLogId));

        return array(
            'image' => F::getStaticsUrl('/activity/v2016/sd/images/pop/fp.png'),
            'text' => '恭喜您获得1彩饭票',
            'button_text' => '确定',
        );
    }

    /**
     * 锁定用户拼图
     * @param $userId
     * @param $cardNumber
     * @return bool
     */
    private function lockUserCard($userId, $cardNumber)
    {
        $sql = 'SELECT `id`, `status` FROM `activity_shuangdan_user_card` WHERE `user_id`=:user_id AND `status`<>1 GROUP BY `card_id`';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result = $command->queryAll();

        $cardIds = array();
        foreach ($result as $row) {
            if (2 == $row['status']) {
                $cardNumber -= 1;
                continue;
            }

            $cardIds[] = $row['id'];
        }

        if ($cardNumber > 0) {
            sort($cardIds);
            $updateIds = array_slice($cardIds, 0, $cardNumber);
            //dump(implode(',', $updateIds));

            $updateData = array(
                'status' => 2, //已锁定
                'update_time' => time()
            );

            // 锁定开取宝箱所需要的拼图状态
            $total = $command->update('activity_shuangdan_user_card', $updateData, "id IN (".implode(',', $updateIds).")");
        }

        return true;
    }

    /**
     * 注册
     * @param $mobile
     * @return bool
     */
    private function register($mobile)
    {
        $ip=0;
        if (isset($_SERVER['REMOTE_ADDR'])&&!empty($_SERVER['REMOTE_ADDR'])){
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        $salt = F::random(8);
        $cust_model = new Customer();
        $cust_model->username=Item::User_Prefix.$mobile;
        $cust_model->password='Czy123456';
        $cust_model->salt=$salt;
        $cust_model->name='访客';
        $cust_model->mobile=$mobile;
        $cust_model->community_id=585;
        $cust_model->build_id=10421;
        $cust_model->room=1;
        $cust_model->create_time=time();
        $cust_model->last_time=time()+rand(100, 86400);
        $cust_model->last_ip=$ip;
        $cust_model->reg_type=rand(0, 2);
        $cust_model->customer_code = $this->getInviteCode();
        $cust_result = $cust_model->save();
        if ($cust_result){

            $msg='恭喜你获得拼图一张，赶紧登陆彩之云去看看吧！账号：'.$mobile.' ，密码：Czy123456 下载链接：http://dwz.cn/2Xb78l';
            //发短信通知
            $sms = Yii::app()->sms;
            $sms->setType('easter', array('mobile' => $mobile));

            if (false == $sms->sendMsg($msg)) {
                Yii::log("短信发送失败[{$mobile}:{$sms->error}]",CLogger::LEVEL_ERROR,'colourlife.core.easter.register');
            }else {
                Yii::log("短信发送成功[{$mobile}]",CLogger::LEVEL_INFO,'colourlife.core.easter.register');
            }

            return $cust_model;
        }

        return false;
    }

    /**
     * 生成随机不重复邀请码
     * @return string
     */
    private function getInviteCode(){
        $invitecode = '';
        $flag = true;
        $i=1;
        while ($flag && $i<=100) {
            $code = F::random(5);
            $code = strtoupper($code);
            $count = InvitationCode::model()->find('code=:code', array(':code' => $code));
            if($count && $i!=100){
                $i++;
                continue;
            }

            if($count && $i==100){
                $sms = Yii::app()->sms;
                $sms->name = '亲爱的管理员';
                $sms->setType('invitecodeNotice', array('mobile' => '13066839936')); //18998945813
                $sms->sendUserMessage('invitecodeNoticeTemplate');
                Yii::log("邀请码生成错误", CLogger::LEVEL_INFO, 'colourlife.api.customer.invitecode');
                $invitecode='#####';
                break;

            }

            $invitecode = $code;
            $flag = false;

        }
        $invitationcode = new InvitationCode();
        $invitationcode->code = $invitecode;
        $invitationcode->save();
        return $invitecode;
    }
}