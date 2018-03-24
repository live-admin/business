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
class RechargeCategory extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '充值分类';

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
        return 'recharge_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cardid,cardname', 'required', 'on' => 'search'),
            array('state', 'required', 'on' => 'disable, enable'),
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
            'cardid' => '编码',
            'classid' => '大类编码',
            'cardname' => '小类名称',
            'cardnameinfo' => '名称详细',
            'detail' => '商品介绍',
            'compty' => '资费说明',
            'usecity' => '开通城市',
            'usemethod' => '使用方法',
            'fullcostsite' => '充值网址',
            'proare' => '产品产地',
            'serviceNum' => '客服服务中心',
            'update_time' => '最后更新时间',
            'state' => '状态',
            'type' => '分类',
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
        $criteria->compare('cardname', $this->cardname, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function Synchroe()
    {
        try {
            $query = OfCardApi::getInstanceWithConfig(Yii::app()->config->rechargeService)->queryList(22);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $len = count($query);
        Yii::app()->cache->set('Ofdata_len', $len);
        for ($i = 0; $i < $len; $i++) {
            $list = array();

            $list['cardid'] = $query[$i]['cardid'];
            $list['classid'] = $query[$i]['classid'];
            $list['cardname'] = $query[$i]['cardname'];
            $list['detail'] = $query[$i]['detail'];
            $list['compty'] = $query[$i]['compty'];
            $list['usecity'] = $query[$i]['usecity'];
            $list['usemethod'] = $query[$i]['usemethod'];
            $list['fullcostsite'] = $query[$i]['fullcostsite'];
            $list['proare'] = $query[$i]['proare'];
            $list['serviceNum'] = $query[$i]['serviceNum'];
            Yii::app()->cache->set('Ofdata_' . $i, $list);
        }
        return 1;
    }

    public function getTitleAndDetail($str)
    {
        $str = str_replace(array('(', ')'), array('（', '）'), $str);
        if (preg_match('/([^（]*)（([^）]*)）(.*)/', $str, $mts)) {
            return array($mts[1] . $mts[3], $mts[2]);
        } else {
            return array($str, '');
        }
    }

    public function Handle()
    {
        $len = Yii::app()->cache->get('Ofdata_len');
        if ($len === false)
            $len = 0;
        for ($i = 0; $i < $len; $i++) {
            $list = Yii::app()->cache->get('Ofdata_' . $i);
            list($list['cardname'], $list['cardnameinfo']) = $this->getTitleAndDetail($list['cardname']);
            Yii::app()->cache->set('Ofdata_' . $i, $list);
        }
        return 1;
    }

    public function WriteData($i)
    {
        $query = Yii::app()->cache->get('Ofdata_' . $i);

        $modelnew = RechargeCategory::model()->findByAttributes(array('cardid' => $query['cardid']));
        if (empty($modelnew)) {
            $modelnew = new RechargeCategory();
            $modelnew->cardid = $query['cardid'];
            $modelnew->classid = $query['classid'];
            $modelnew->cardname = $query['cardname'];
            $modelnew->cardnameinfo = $query['cardnameinfo'];
            $modelnew->detail = $query['detail'];
            $modelnew->compty = $query['compty'];
            $modelnew->usecity = $query['usecity'];
            $modelnew->usemethod = $query['usemethod'];
            $modelnew->fullcostsite = $query['fullcostsite'];
            $modelnew->proare = $query['proare'];
            $modelnew->serviceNum = $query['serviceNum'];
            $modelnew->update_time = time();
            $modelnew->save();
        } else {
            $modelnew->cardid = $query['cardid'];
            $modelnew->classid = $query['classid'];
            $modelnew->cardname = $query['cardname'];
            $modelnew->cardnameinfo = $query['cardnameinfo'];
            $modelnew->detail = $query['detail'];
            $modelnew->compty = $query['compty'];
            $modelnew->usecity = $query['usecity'];
            $modelnew->usemethod = $query['usemethod'];
            $modelnew->fullcostsite = $query['fullcostsite'];
            $modelnew->proare = $query['proare'];
            $modelnew->serviceNum = $query['serviceNum'];
            $modelnew->update_time = time();
            $modelnew->save();
        }
        Yii::app()->cache->set('Ofdata' . $i, null);
        return $modelnew->cardid;
    }

    public function SetCategoryMoble(array $ids)
    {
        foreach ($ids as $value) {
            $temp = RechargeCategory::model()->findByPk($value);
            $temp->type = 1;
            $temp->save();
        }
    }

    public function SetCategoryTencent(array $ids)
    {
        foreach ($ids as $value) {
            $temp = RechargeCategory::model()->findByPk($value);
            $temp->type = 2;
            $temp->save();
        }
    }

    public function SetCategoryGames(array $ids)
    {
        foreach ($ids as $value) {
            $temp = RechargeCategory::model()->findByPk($value);
            $temp->type = 3;
            $temp->save();
        }
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
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

}
