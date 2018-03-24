<?php

/**
 * This is the model class for table "review".
 *
 * The followings are the available columns in table 'review':
 * @property integer $id
 * @property string $model
 * @property integer $object_id
 * @property integer $customer_id
 * @property string $content
 * @property string $reply
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $audit
 * @property integer $is_deleted
 * @property string $score
 */
class Review extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '商家评价';
    public $objectLabel = '商家名称';
    public $objectModel = 'shop';
    public $_objectName;
    public $customerName;
    public $goodsName;
    public $username;
    public $mobile;
    public $branch_id;
    public $region;
    public $community;
    public $start_time;
    public $end_time;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Review the static model class
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
        return 'review';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content', 'required'),
            array('audit', 'checkAudit', 'on' => 'auditOK, auditNO'), // 审核通过
            array('object_id, customer_id, create_time, update_time, audit, is_deleted', 'numerical', 'integerOnly' => true),
            array('model', 'length', 'max' => 50),
            array('create_ip, update_ip', 'length', 'max' => 20),
            array('score', 'length', 'max' => 10),
            array('reply', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('customerName,objectName, audit, score,goodsName,username,mobile,branch_id,community,region,start_time,end_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            $this->objectModel => array(self::BELONGS_TO, ucfirst($this->objectModel), 'object_id'),

            'shops' => array(self::BELONGS_TO, 'Shop', 'object_id'),
            'goods_s' => array(self::BELONGS_TO, 'Goods', 'object_id')
        );
    }

    public function beforeSave()
    {
        if (Yii::app()->config->autoAudit == 1) {
            //echo '111';
            $this->audit = 1;
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        if (Yii::app()->config->autoAudit == 1) {
            //echo '2222';
            $this->updateScore();
        }
        return parent::afterSave();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'object_id' => $this->objectLabel,
            'customer_id' => '业主名称',
            'content' => '评价内容',
            'reply' => '回复内容',
            'mobile' => '手机',
            'create_time' => '评价时间',
            'create_ip' => '评价IP',
            'update_time' => '回复时间',
            'update_ip' => '回复IP',
            'audit' => '审核',
            'score' => '评分',
            'objectName' => $this->objectLabel,
            'customerName' => '业主名称',
            'goodsName' => '商品名称',
            'username' => '业主帐号',
            'mobile' => '业主手机',
            'branch_id' => '管辖部门',
            'region' => '地区',
            'community' => '小区',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
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

        $criteria->compare('model', $this->objectModel, true); //设置条件为商家
        if ($this->_objectName != '') {
            $criteria->with[] = $this->objectModel;
            $criteria->compare($this->objectModel . '.name', $this->objectName, true);
        }
        if ($this->customerName != '') {
            $criteria->with[] = 'customer';
            $criteria->compare('customer.name', $this->customerName, true);
        }
        // $employee = Employee::model()->findByPk(Yii::app()->user->id);
        // $criteria->addInCondition('shop.branch_id', $employee->getBranchIds());
        $criteria->compare('`t`.content', $this->content, true);
        $criteria->compare('`t`.reply', $this->reply, true);
        $criteria->compare('`t`.create_time', $this->create_time);
        $criteria->compare('`t`.create_ip', $this->create_ip, true);
        $criteria->compare('`t`.update_time', $this->update_time);
        $criteria->compare('`t`.update_ip', $this->update_ip, true);
        $criteria->compare('`t`.audit', $this->audit, true);
        $criteria->compare('`t`.score', $this->score, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function auditOK()
    {
        $this->updateByPk($this->id, array('audit' => 1));
        $this->updateScore();
    }

    public function auditNO()
    {
        $this->updateByPk($this->id, array('audit' => 2));
    }

    /**
     * 检查指审核时是否有审核过的状态
     * @param $attribute
     * @param $params
     */
    public function checkAudit($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->isAudited) {
                $this->addError($attribute, $this->modelName . '已审核过，无法操作。');
            }
        }
    }

    /**
     * 截取字符并hover显示全部字符串
     * $str string  截取的字符串
     * $len int 截取的长度
     */
    public function getFullString($str, $len)
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
    }

    //更新评分
    public function updateScore()
    {
        //$model = CActiveRecord::model($this->model);
        $model = ucfirst(trim($this->model));
        $score = $this->getScore();
        return $model::model()->updateByPk($this->object_id, array('score' => $score));
    }

    private function getScore()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'sum(score) score,count(score) as total';
        $criteria->compare("model", $this->model);
        $criteria->compare("object_id", $this->object_id);
        $aResult = Review::model()->passed()->find($criteria);

        if ($aResult == null) {
            $totalScore = 0;
        } else {
            $totalScore = $aResult->score;
        }

        $totalCount = Review::model()->passed()->count($criteria);

        if ($totalCount != 0)
            return $totalScore / $totalCount;

        return 0;
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'AuditBehavior' => array(
                'class' => 'common.components.behaviors.AuditBehavior',
            ),
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'create_ip',
                'updateAttribute' => 'update_ip',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getObjectName()
    {
        if (isset($this->_objectName))
            return $this->_objectName;
        $name = $this->objectModel;
        return empty($this->$name) ? '' : $this->$name->name;
    }

    public function setObjectName($name)
    {
        $this->_objectName = $name;
    }

    /**获取姓名
     * @return string
     */
    public function getShoporGoods_Name()
    {
        return $this->model == 'shop' ? (isset($this->shops) ? $this->shops->name : '') : (isset($this->goods_s) ? $this->goods_s->name : '');
    }

    //取得评价商品人的NickName
    public function getName()
    {
        return (!empty($this->customer) ? empty($this->customer->name) ? "匿名用户" : $this->customer->name : '匿名用户');
    }

    public function getShop_Name()
    {
        return $this->model == 'goods' ? (isset($this->goods_s) ? $this->goods_s->shop->name : '') : '';
    }

    /**获取商品价格
     * @return string
     */
    public function getPrice()
    {
        return $this->model == 'goods' ? (isset($this->goods_s) ? (($this->goods_s->audit_cheap == Goods::AUDIT_CHEAP_YES) ? $this->goods_s->cheap_price : $this->goods_s->customer_price) : '') : '';
    }

    /**获取描述
     * @return string
     */
    public function getDescription()
    {
        return $this->model == 'shop' ? (isset($this->shops) ? $this->shops->desc : '') : (isset($this->goods_s) ? $this->goods_s->description : '');;
    }


    public function getBuyerHtml()
    {
        return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' . $this->customer->mobile . ',业主帐号:' . $this->customer->username), $this->customer->name);
    }

}
