<?php
/**
 * Class UserBehavior
 */
class UserBehavior extends CActiveRecordBehavior
{
    /**
     * 是否将密码修改同步到 OA 系统
     * @var bool
     */
    public $isOaUpdatePassword = false;

    /**
     * 记录用户原有的密码字段
     * @var
     */
    private $_password;

    /**
     * 通过用户名/手机号码查找用户
     * @param $username
     * @param bool $akaMobile 是否通过手机查找
     * @return CActiveRecord
     */
    public function getByUsername($username, $akaMobile = false)
    {
        if ($akaMobile) { // Fixme: 检测 $username 是否是手机号码
            $user = $this->owner->enabled()->find('mobile=:mobile', array(':mobile' => strtolower($username)));
            if ($user != null)
                return $user;
        }
        return $this->owner->enabled()->find('username=:username', array(':username' => $username));
    }

    /**
     * 验证输入密码是否和当前用户密码相等
     * @param unknown_type $password
     */
    public function validatePassword($password, $username='')
    {
        // 输入密码是否和当前用户密码相等
        if ($this->_hashPassword($password, $this->owner->salt) === $this->owner->password)
            return true;

        // 验证OA链接的密码是否和当前用户密码相等
        if ($this->validatePasswordByOa($password))
            return true;

        // 手势密码登录
        if ($this->validatePasswordByGesture($password))
            return true;

//        if ($this->validatePasswordByAD($password, $username))
//            return true;

        // 第三方auth验证
        if ($this->validatePasswordByThirdAuth($password))
            return true;

        return false;
    }
    
    /**
     * 验证OA链接的密码是否和当前用户密码相等
     * @param unknown_type $password
     */
    public function validatePasswordByOa($password){
        return $this->owner->password === md5($password . $this->owner->salt);
    }

    /**
     * 手势密码登录
     * @param $password
     * @return bool
     */
    public function validatePasswordByGesture($password){
        return isset($this->owner->gesture) ? $this->owner->gesture->gesture_code === md5($password) : false;
    }

    /**
     * 第三方auth验证
     * @param $password
     * @return bool
     */
    public function validatePasswordByThirdAuth($password) {

        $thirdAuth = CustomerThirdAuth::model()->find('open_code=:open_code AND customer_id=:customer_id', array(':open_code'=>$password, ':customer_id'=>$this->owner->id));

        return $thirdAuth ? true : false;
    }

    /**
     * AD验证登录employee
     */
    public function validatePasswordByAD($password, $username){
        Yii::import('common.api.IceApi');
        //实例化
        $ice = IceApi::getInstance();
        //调用ICE接口login
        $result = $ice->login($username,$password);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 返回MD5加密后的密码
     */
    private function _hashPassword($password, $salt)
    {
        return md5(md5($password) . $salt);
    }

    public function afterFind($event)
    {
        $this->_password = $this->owner->password;
    }

    public function beforeSave($event)
    {
        if (empty($this->owner->salt)) {
            //产生唯一的随机数
            $this->owner->salt = F::random(8);
        }
        if ($this->owner->password != $this->_password) {
			if ($this->isOaUpdatePassword && Yii::app()->params['oaUpdatePassword']) {
                if (!$this->updatePwdByOa($this->owner->username, $this->owner->password))
                    $event->isValid = false;  // 终止保存操作
            }
            // 如果用户修改了密码，重新加密密码
            $this->owner->password = $this->_hashPassword($this->owner->password, $this->owner->salt);
		}
    }

    public function afterSave($event)
    {
        $owner = $this->getOwner();
        if ($owner->getIsNewRecord()) {
            // 同步用户信息到 OpenFire 聊天服务数据库
            Yii::app()->chat->saveChatUser($owner);
        }
    }
    
    private function updatePwdByOa($username, $pwd)
    {
        $owner = $this->getOwner();
        Yii::import('common.api.ColorCloudApi');
        $colure = ColorCloudApi::getInstance();
        $res = $colure->callGetUserCheck($username,"");
        if($res && $res['total'] == 0){
            return true;       //OA账号无该用户（只是在彩之云单向修改）
        }
        if (!$res || !empty($res['error'])) {
            $owner->addError('password', 'OA对接失败'); 
            return false;    //OA的API调用失败
        }
        if($res['total'] == 1){
            $result = $colure->callSetUserUpPwd($username,$pwd);
            if (!$result) {
                $owner->addError('password', 'OA对接失败');   //调用OA的API出现error 
                return false;
            }
            if ($result['total'] == 1) {
                return true;                                 //修改密码成功
            } else {
                $owner->addError('password', 'OA修改密码失败');
                return false;  //修改密码失败
            }
        }else{
            return true;   //OA账号无该用户（只是在彩之云单向修改）
        }
    }

}
