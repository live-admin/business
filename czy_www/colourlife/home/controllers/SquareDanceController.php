<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/12
 * Time: 9:34
 */
class SquareDanceController extends ActivityController
{
    public $secret = '@#$WER653';
    public $beginTime = '2016-04-12';
    public $endTime = '2016-05-01 23:59:59';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth',
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

    public function actionIndex()
    {
        $userId = Yii::app()->request->getParam('user_id');

        $userId = $this->getUserId($userId);

        if ( !is_int($userId))
            $this->redirect('http://mapp.colourlife.com/m.html');

        $user = Customer::model()->findByPk($userId);
        if (!$user)
            $this->redirect('http://mapp.colourlife.com/m.html');

        $province = '';
        $city = '';
        $area = '';

        $community = Community::model()->findByPk($user->community_id);
        if ($community) {
            $region = $community->getMyParentRegionNames();
            $province = isset($region[0]) ? $region[0] : '';
            $city = isset($region[1]) ? $region[1] : '';
            $area = isset($region[2]) ? $region[2] : '';
        }

        $result = array(
            'name' => $user->name,
            'tel' => $user->mobile,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'sex' => $user->gender == 1 ? '男' : '女'
        );

        $this->renderPartial('/v2016/dance/index', $result);
    }

    public function actionSave()
    {
        $name = htmlspecialchars(Yii::app()->request->getParam('name'));
        if (empty($name) || strlen($name) > 12)
            $this->output('', 0, '请填写正确的姓名');

        $sex = Yii::app()->request->getParam('sex', '女');
        if ( !in_array($sex, array('男', '女')))
            $this->output('', 0, '性别参数错误');

        $tel = Yii::app()->request->getParam('tel');
        if (!preg_match('/^1\d{10}$/', $tel))
            $this->output('', 0, '请填写正确的手机号码');

        $cardNo = Yii::app()->request->getParam('card_no');
        if ( false == CheckIdentity::checkCode($cardNo)) {
            $this->output('', 0, '请填写正确的身份证号码');
        }

        $province = htmlspecialchars(Yii::app()->request->getParam('province'));
        $city = htmlspecialchars(Yii::app()->request->getParam('city'));
        $area = htmlspecialchars(Yii::app()->request->getParam('area'));
        if ( empty($province) || empty($city) || empty($area))
            $this->output('', 0, '请选择完地址');

        $type = (int)Yii::app()->request->getParam('type', 1);
        if ( !in_array($type, array(0, 1))) // 0 团队 1 个人
            $this->output('', 0, '报名类型参数错误');

        $number = 1;
        $teamLeader = 0;
        if (0 === $type) {
            $number = (int)Yii::app()->request->getParam('number', 1);
            if ($number < 2 || $number > 24)
                $this->output('', 0, '团队人数参数错误');
        }
        else {
            $teamLeader = (int)Yii::app()->request->getParam('team_leader', 1);
            if ( !in_array($teamLeader, array(0, 1))) // 0 否 1 是
                $this->output('', 0, '是否愿意参数错误');
        }

        // 同一个身份证 同一个电话 只能报一次
        $selectSql = 'SELECT `id` FROM `square_dance` WHERE `card_no`=:card_no OR `tel`=:tel';
        $result = Yii::app()->db->createCommand($selectSql)->execute(array(':card_no'=>$cardNo, ':tel'=>$tel));
        if ($result)
            $this->output('', 0, '重复报名！');

        $userId = $this->getUserId();
        $createTime = time();
        $insertSql = 'INSERT INTO `square_dance` (`name`, `sex`, `tel`, `card_no`, `province`, `city`, `area`, `type`, `number`, `team_leader`, `user_id`, `create_time`)
                                       VALUES (:name, :sex, :tel, :card_no, :province, :city, :area, :type, :number, :team_leader, :user_id, :create_time)';

        $command = Yii::app()->db->createCommand($insertSql);

        $params = array(
            ':name' => $name,
            ':sex' => $sex,
            ':tel' => $tel,
            ':card_no' => $cardNo,
            ':province' => $province,
            ':city' => $city,
            ':area' => $area,
            ':type' => $type,
            ':number' => $number,
            ':team_leader' => $teamLeader,
            ':user_id' => $userId,
            ':create_time' => $createTime
        );

        if ($command->execute($params))
            $this->output('报名成功');

        $this->output('', 0, '系统异常，请稍后再试！');
    }

    public function actionRule()
    {
        $this->renderPartial('/v2016/dance/rule');
    }
}