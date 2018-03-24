<?php

/**
 * This is the model class for table "{{api_auth}}".
 *
 * The followings are the available columns in table '{{api_auth}}':
 * @property integer $id
 * @property string $secret
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $last_time
 * @property string $last_ip
 * @property integer $expire
 * @property string $data
 */
class ApiAuth extends CActiveRecord
{
    const AUTH_OK = 0;
    const AUTH_UNAUTHORIZED = 1;
    const AUTH_INVALID_TIME = 2;

    private $_data = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ApiAuth the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('secret', 'safe'),
            array('create_time, update_time, last_time', 'numerical', 'integerOnly' => true),
            array('secret, create_ip, update_ip, last_ip', 'length', 'max' => 16),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, secret, create_time, create_ip, update_time, update_ip, last_time, last_ip', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'secret' => '密钥',
            'detail' => '详细',
            'create_time' => '创建时间',
            'create_ip' => '创建 IP',
            'update_time' => '更新时间',
            'update_ip' => '更新 IP',
            'last_time' => '最后访问时间',
            'last_ip' => '最后访问 IP',
            'expire' => '登录有效期',
            'data' => '登录数据',
        	'user_agent' =>'UA',
        	'version' =>'版本',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('secret', $this->secret, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('create_ip', $this->create_ip, true);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('update_ip', $this->update_ip, true);
        $criteria->compare('last_time', $this->last_time);
        $criteria->compare('last_ip', $this->last_ip, true);
        $criteria->compare('expire', $this->expire, true);

        return new ActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => 'updateTime DESC',
            ),
            'criteria' => $criteria,
        ));
    }

    /**
     * 自动处理
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'create_ip',
                'updateAttribute' => 'update_ip',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    protected function afterFind()
    {
        $this->_data = unserialize($this->data);
        return parent::afterFind();
    }

    protected function beforeSave()
    {
        if ($this->getIsNewRecord())
           if($this->secret=='')
                $this->secret = F::random(16);

	        $this->last_time = time();
	        $this->last_ip = Yii::app()->getRequest()->getUserHostAddress();
	        $this->data = serialize($this->_data);
	        if(isset($this->user_agent)){
	        	$this->user_agent = Yii::app()->request->userAgent;
	        }
	        if(isset($this->version)){
	            $this->version = empty($_GET["ve"])?"0":$_GET["ve"];
	        }
	        return parent::beforeSave();
    }

    /*
     * 记录最后访问
     */
    public function updateLast()
    {
        $this->updateByPk($this->id, array(
            'last_time' => time(),
            'last_ip' => Yii::app()->getRequest()->getUserHostAddress(),
        	'user_agent' => Yii::app()->request->userAgent,
        	'version' =>empty($_GET["ve"])?"0":$_GET["ve"],
        ));
    }

    public function auth($uri, $callback = false)
    {
        $state = self::AUTH_INVALID_TIME;
        $model = null;
        $uri = parse_url($uri);
        if (isset($uri['path']) && isset($uri['query'])) {
            parse_str($uri['query'], $query);
            if (isset($query['key']) && isset($query['ts']) && isset($query['sign'])) {
                $ts = intval($query['ts']);
                if ((abs($ts - time()) < 3600 * 24)||$uri['path']=='/1.0/posMachine/getOrderInfo'||$uri['path']=='/1.0/posMachine/paysyntony'||(isset($query['flag'])&&$query['flag']=='unionpay')) {
                    if ($state == self::AUTH_INVALID_TIME)
                        $state = self::AUTH_UNAUTHORIZED;
                    $model = $this->findByPk(intval($query['key']));
                    if ($model != null) {
                        $sign = strval($query['sign']);
                        unset($query['sign']);
                        $query['secret'] = $model->secret;
                        if(isset($query['flag'])&&$query['flag']=='unionpay'){
                            unset($query['flag']);
                        }
                        $path = $uri['path'] . '?' . http_build_query($query);
                        $md5 = md5($path);
                        if ($md5 == $sign) {
                            // 令牌验证成功 
                            if ($state == self::AUTH_UNAUTHORIZED)
                                $state = self::AUTH_OK;
                        } else {
                            //fb("path:{$path} md5:{$md5}");;
                            $model = null;
                        }
                    }else{
                        $state = self::AUTH_OK;
                    }
                }
            }
        }
        if (is_callable($callback))
            call_user_func($callback, $model);
        else if ($model != null)
            $model->updateLast();
        unset($_GET['sign']);
        unset($_GET['key']);
        unset($_GET['ts']);
        Yii::app()->user->setAuth($model);
        return $state;
    }

    public function getData($key, $defaultValue = null)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : $defaultValue;
    }

    public function setData($key, $value, $defaultValue = null)
    {
        if ($value === $defaultValue) {
            unset($this->_data[$key]);
        } else {
            $this->_data[$key] = $value;
        }
    }

    public function hasData($key)
    {
        return isset($this->_data[$key]);
    }

    public function getDataKeys()
    {
        return array_keys($this->_data);
    }

}
