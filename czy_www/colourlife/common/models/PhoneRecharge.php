<?php

/**
 * This is the model class for table "build".
 *
 * The followings are the available columns in table 'build':
 * @property integer $id
 * @property string $name
 * @property integer $community_id
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $operators
 */
class PhoneRecharge extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '手机充值';

    const OPERATORS_MOBILE = 1;
    const OPERATORS_UNICOM = 2;
    const OPERATORS_TELECOM = 3;

    private $_operators = array(
        self::OPERATORS_MOBILE => '中国移动',
        self::OPERATORS_UNICOM => '中国联通',
        self::OPERATORS_TELECOM => '中国电信',
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Build the static model class
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
        return 'phone_recharge';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('facevalue,price,operators', 'required', 'on' => 'create, update'),
            array('facevalue,price,operators', 'safe', 'on' => 'search'),
            array('facevalue,operators', 'isExits', 'on' => 'create'),
        );
    }

    public function isExits($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->find('facevalue=:facevalue and operators=:operators',
                array(':facevalue' => $this->facevalue, ':operators' => $this->operators))
            ) {
                $this->addError($attribute, '添加记录已存在');
            }
        }
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
            'facevalue' => '面值',
            'price' => '售价',
            'operators' => '运营商',
            'state' => '状态',
        );
    }

    public function getOperatorsList()
    {
        return $this->_operators;
    }

    public function getOperatorsValue()
    {
        if (!empty($this->_operators[$this->operators]))
            return $this->_operators[$this->operators];
        else
            return '未设置';
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

        $criteria->compare('facevalue', $this->facevalue);
        $criteria->compare('price', $this->price);
        $criteria->compare('operators', $this->operators);
        $criteria->compare('state', $this->state);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

}
