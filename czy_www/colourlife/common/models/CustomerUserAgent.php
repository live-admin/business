<?php
/**
 * Created by PhpStorm.
 * User: ゛嗨⑩啉°
 * Date: 13-12-13
 * Time: 上午11:17
 */

class CustomerUserAgent extends CActiveRecord
{
    public $id;
    /**
     * 创建时间
     * @var
     */
    public $create_time;
    /**
     * 创建 IP
     * @var
     */
    public $create_ip;
    /**
     * 创建 UA
     * @var
     */
    public $create_user_agent;
    /**
     * update_time
     * @var
     */
    public $update_time;
    /**
     * 更新 IP
     * @var
     */
    public $update_ip;
    /**
     * update_user_agent
     * @var
     */
    public $update_user_agent;
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'customer_user_agent';
    }

    public function rules()
    {
        return array(
            array('create_user_agent','required','on' => 'create'),//创建需要的参数
            array('update_user_agent','required','on'=>'update'),//更新时需要的参数
        );
    }

    public function relations()
    {
        return array();
    }

    public function behaviors()
    {
        return array(
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'create_ip',
                'updateAttribute' => 'update_ip',
                'setUpdateOnCreate' => true,
            ),
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'create_time' => '创建时间',
            'create_ip' => '创建 IP',
            'create_user_agent' => '创建 UA',
            'update_time' => '更新时间',
            'update_ip' => '更新 IP',
            'update_user_agent' => '更新 UA'
        );
    }

    /**
     * 创建Customer_user_agent    cookie
     * @param string $username   Customer用户名
     * @param null $user_agent  浏览器唯一标识 $_SERVER['HTTP_USER_AGENT']
     */
    public static function createUserAgent($username,$user_agent = null)
    {
        $id = self::updateUserAgent();
        $model = Customer::model()->findByAttributes(array('username' => $username));
        $model->reg_identity = $id;//记录用户唯一标识符
        $model->save();

        F::setCookie(Item::CUSTOMER_USER_AGENT_COOKIE_NAME,$id,time()+31536000);
    }

    /**
     * 更新Customer_user_agent Cookie
     * @param null $user_agent
     */
    public static function updateUserAgent($user_agent = null)
    {
        $cookie = F::getCookie(Item::CUSTOMER_USER_AGENT_COOKIE_NAME);
        $user_agent = (null === $user_agent) ? Yii::app()->request->getUserAgent() : $user_agent;//如有外部传进来的参数  则使用外部的
        //Cookie存在且在CustomerUserAgent存在该ID时进行更新 否则新建唯一标识
        if(!empty($cookie)&&$ua = self::model()->findByPk($cookie)){
            $ua->update_user_agent = $user_agent;
            $ua->save();
        }
        else{
            $ua = new self;
            $ua->create_user_agent = $user_agent;
            $ua->update_user_agent = $user_agent;
            $ua->save();
        }
        //设置唯一标识符到Cookie
        F::setCookie(Item::CUSTOMER_USER_AGENT_COOKIE_NAME,$ua->id,time()+31536000);
        return $ua->id;
    }

} 