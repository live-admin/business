<?php

/**
 * This is the model class for table "event_category".
 *
 * The followings are the available columns in table 'event_category':
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $desc
 * @property integer $state
 * @property integer $is_deleted
 */
class Recharge extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '充值';
    public $_cardid;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Community the static model class
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
        return 'recharge';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('_cardid', 'required', 'on' => 'synchroe'),
            array('cardid,cardname,subclassid', 'required', 'on' => 'search'),
            array('myprice', 'required', 'on' => 'update'),

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
            '_cardid' => '编码',
            'cardid' => '编码',
            'pervalue' => '商品面值',
            'inprice' => '对应SP等级的结算价',
            'sysddprice' => 'CP系统1级直销价',
            'sysdd1price' => 'CP系统2级直销价',
            'sysdd2price' => 'CP系统3级直销价',
            'memberprice' => '普通会员价',
            'innum' => '商品库存情况',
            'cardname' => '商品名称',
            'othername' => '别名',
            'howeasy' => 'howeasy',
            'amounts' => '可选数量', // 在线充产品可选数量，连续的用“-”表示，“,”用作分隔符
            'subclassid' => '所属子类编码', // 此商品所属子类编码
            'classtype' => '所属商品类型', // 此商品所属商品类型（1-实物商品，2-直充商品，3-卡密商品，
            // 4-手机快冲，5-手机慢冲，6-支付商品）
            'fullcostsite' => '充值网址',
            'caption' => '标题',
            'lastreftime' => '最后操作时间', // 最后操作时间
            'accountdesc' => '账号描述', // 账号描述
            'update_time' => '最后修改时间',
            'category_id' => '分类ID',
            'myprice' => '销售价格',
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

        $criteria->compare('cardid', $this->cardid, true);
        $criteria->compare('subclassid', $this->subclassid, true);
        $criteria->compare('cardname', $this->cardname, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function Synchroe($cardidNum)
    {
        try {
            $query = OfCardApi::getInstanceWithConfig(Yii::app()->config->rechargeService)->queryCardInfo($cardidNum);
        } catch (Exception $e) {
            //var_dump($e);
            return 0;
        }

        foreach ($query as $value2) {
            $modelnew = Recharge::model()->findByAttributes(array('cardid' => $value2['cardid']));
            if (empty($modelnew)) {
                $modelnew = new Recharge();
                $modelnew->cardid = $value2['cardid'];
                $modelnew->pervalue = $value2['pervalue'];
                $modelnew->inprice = $value2['inprice'];
                $modelnew->sysddprice = $value2['sysddprice'];
                $modelnew->sysdd1price = $value2['sysdd1price'];
                $modelnew->sysdd2price = $value2['sysdd2price'];
                $modelnew->memberprice = $value2['memberprice'];
                $modelnew->innum = $value2['innum'];
                $modelnew->cardname = $value2['cardname'];
                $modelnew->othername = $value2['othername'];
                $modelnew->howeasy = $value2['howeasy'];
                $modelnew->amounts = $value2['amounts'];
                $modelnew->subclassid = $value2['subclassid'];
                $modelnew->classtype = $value2['classtype'];
                $modelnew->fullcostsite = $value2['fullcostsite'];
                $modelnew->caption = $value2['caption'];
                $modelnew->lastreftime = $value2['lastreftime'];
                $modelnew->accountdesc = $value2['accountdesc'];
                $modelnew->category_id = 0;
                $modelnew->update_time = time();
                $modelnew->save();
            } else {
                $modelnew->cardid = $value2['cardid'];
                $modelnew->pervalue = $value2['pervalue'];
                $modelnew->inprice = $value2['inprice'];
                $modelnew->sysddprice = $value2['sysddprice'];
                $modelnew->sysdd1price = $value2['sysdd1price'];
                $modelnew->sysdd2price = $value2['sysdd2price'];
                $modelnew->memberprice = $value2['memberprice'];
                $modelnew->innum = $value2['innum'];
                $modelnew->cardname = $value2['cardname'];
                $modelnew->othername = $value2['othername'];
                $modelnew->howeasy = $value2['howeasy'];
                $modelnew->amounts = $value2['amounts'];
                $modelnew->subclassid = $value2['subclassid'];
                $modelnew->classtype = $value2['classtype'];
                $modelnew->fullcostsite = $value2['fullcostsite'];
                $modelnew->caption = $value2['caption'];
                $modelnew->lastreftime = $value2['lastreftime'];
                $modelnew->accountdesc = $value2['accountdesc'];
                $modelnew->update_time = time();
                $modelnew->save();
            }
        }
        return 1;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => null,
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => false,
            ),
        );
    }

}
