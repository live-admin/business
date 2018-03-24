<?php

/**
 * This is the model class for table "payment".
 * 停车费、物业缴费的支付方式所有的折扣都取消掉
 *
 * The followings are the available columns in table 'payment':
 * @property integer $id
 * @property integer $code
 * @property string $name
 * @property string $description
 * @property string $config
 * @property integer $property_discount
 * @property integer $parking_discount
 * @property integer $state
 * @property integer $type
 * @property integer $display_order
 * @property integer $update_time
 * @property integer $update_employee_id
 * @property integer $rate
 * @update 20150424 增加alipay支付宝
 */
class Payment extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '支付方式';
    public $logoFile;
    public $configAttributes = array();
    public $pay_list;
    public $rate;


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Payment the static model class
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
        return 'payment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, description', 'required', 'on' => 'update'),
            array('name', 'length', 'max' => 255, 'on' => 'update'),
            //array('is_online, is_mobile', 'boolean', 'on' => 'update'), // 是否电脑、手机支付不能设置，由程序配置
            array('property_discount, parking_discount', 'numerical', 'min' => '0.01', 'max' => '1.00', 'on' => 'update'),
            array('display_order', 'numerical', 'integerOnly' => true, 'on' => 'update'),
            array('logoFile, rate', 'safe', 'on' => 'create, update'), // 增加和编辑图片文件
            //array('state', 'checkDisable', 'on' => 'disable'), // 禁用时检查关联项目
            //array('state', 'checkEnabled', 'on' => 'enable'), // 启用时检查关联项目
            array('id, code, name, description, state, type, rate', 'safe', 'on' => 'search'),
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
            'code' => '代码',
            'name' => '名称',
            'description' => '描述',
            'logo' => 'Logo',
            'logoFile' => 'Logo 文件',
            'config' => '配置',
            'state' => '状态',
            //'is_online' => '是否电脑支付',
            'type' => '支付方式',
            'display_order' => '排序值',
            'update_time' => '更新时间',
            'update_employee_id' => '更新人',
            'property_discount' => '物业费折扣',
            'parking_discount' => '停车费折扣',
            'rate' => '费率',
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
        $criteria->compare('code', $this->code);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('state', $this->state);
        //$criteria->compare('is_online', $this->is_online);
        $criteria->compare('type', $this->type);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function online()
    {
        $criteria = $this->getDbCriteria();
        $criteria->compare('type', 1);
        $criteria->order = 'display_order DESC';
        return $this->enabled();
    }

   public function isAccount()
    {
        $criteria = $this->getDbCriteria();
        $criteria->compare('is_account', 1);
        $criteria->order = 'display_order DESC';
        return $this->enabled();
    }

    public function mobile()
    {
        $criteria = $this->getDbCriteria();
        $criteria->compare('type', 0);
        $criteria->order = 'display_order DESC';
        return $this->enabled();
    }

    protected function getClassNameByCode($code)
    {
        switch ($code) {
            case 'upop':
                Yii::import('common.components.models.UPOPPayment');
                return 'UPOPPayment';
            case 'unionpay':
                Yii::import('common.components.models.UnionPayment');
                return 'UnionPayment';
            case 'netpay':
                Yii::import('common.components.models.NetPayment');
                return 'NetPayment';
            case 'tenpay':
                Yii::import('common.components.models.TenPayment');
                return 'TenPayment';
            case 'tenpayWap':
            case 'tenpayBank':
                Yii::import('common.components.models.TenWapPayment');
                return 'TenWapPayment';
            case 'tenpayMobile':
                Yii::import('common.components.models.TenMobilePayment');
                return 'TenMobilePayment';
            case 'kuaiqian':
                Yii::import('common.components.models.KuaiQianPayment');
                return 'KuaiQianPayment';
            case 'weixin':
                Yii::import('common.components.models.WeixinPayment');
                return 'WeixinPayment';
            case 'weixinMobile':
                Yii::import('common.components.models.WeixinMobilePayment');
                return 'WeixinMobilePayment';
            case 'alipay':
                Yii::import('common.components.models.AlipayPayment');
                return 'AlipayPayment';
            case 'shuangqian':
                Yii::import('common.components.models.ShuangQianPayment');
                return 'ShuangQianPayment';
        }
        throw new CHttpException(404, "不支持的支付方式ss");
    }

    protected function getApiByCode($code){
        $billApi = null;
        switch ($code) {
            case 'upop':
                break;
            case 'unionpay':
                break;
            case 'netpay':
                break;
            case 'tenpayMobile':
            case 'tenpay':
                Yii::import('common.components.account.Tenpay');
                $billApi = Tenpay::getInstance();
                break;
            case 'tenpayWap':
            case 'tenpayBank':
                break;
            case 'kuaiqian':
                Yii::import('common.components.account.Kuaiqian');
                $billApi = Kuaiqian::getInstance();
                break;
        }
        return $billApi;
    }

    public function getClassName()
    {
        return $this->getClassNameByCode($this->code);
    }

    public function getApiObject(){
        return $this->getApiByCode($this->code);
    }

    public function findByCode($code)
    {
        return self::model($this->getClassNameByCode($code))->enabled()->find('code=:code', array(':code' => $code));
    }

    public function getSpecificModel()
    {
        return self::model($this->getClassName())->findByPk($this->id);
    }

    public function setConfigValue(array $config)
    {
        $this->config = serialize($config);
    }

    public function getConfigValue()
    {
        $config = @unserialize($this->config);
        if (!is_array($config))
            $config = array();
        return $config;
    }

    /**
     * 禁用时检查是否有启用的下级项目
     * @param $attribute
     * @param $params
     */
    public function checkDisable($attribute, $params)
    {
        if (!$this->hasErrors() && $this->isDisabled) {
            $this->addError($attribute, $this->modelName . '存在已禁用的，无法操作。');
        }
    }

    public function checkEnabled($attribute, $params)
    {
        if (!$this->hasErrors() && $this->isEnabled) {
            $this->addError($attribute, $this->modelName . '存在已启用的，无法操作。');
        }
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => NULL,
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

    protected function afterFind()
    {
        foreach ($this->getConfigValue() as $name => $value) {
            if (!empty($name) && in_array($name, $this->configAttributes)) {
                $this->$name = $value;
            }
        }
        return parent::afterFind();
    }

    protected function beforeSave()
    {
        $this->setConfigValue($this->getAttributes($this->configAttributes));
        if (!empty($this->logoFile)) {
            $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
        }
        return parent::beforeSave();
    }

    protected function beforeValidate()
    {
        if (empty($this->logo) && !empty($this->logoFile))
            $this->logo = '';
        return parent::beforeValidate();
    }

    public function getLogoUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo, '/common/images/nopic.png');
    }

    public function  getNewLogoUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->new_logo, '/common/images/nopic.png');
    }

    public function getPaymentName($patment_id=""){
        if($patment_id==""){
            return "";
        }else if($patment_id==Item::POS_PAYMENT_STATUS){
            return "POS机支付";
        }else{
            $model=Payment::model()->findByPk($patment_id);
            if(!empty($model)){
                return $model->name;
            }else{
                return "";
            }
        }
    }

    public function getRateName()
    {
        return $this->rate.'%';
    }
}
