<?php

/**
 * This is the model class for table "virtual_recharge".
 *
 * The followings are the available columns in table 'virtual_recharge':
 * @property integer $id
 * @property string $cardid
 * @property integer $cardnum
 * @property string $game_userid
 * @property string $game_area
 * @property string $game_srv
 * @property float $income_price
 * @property float $expend_price
 * @property integer $type
 */
class VirtualRecharge extends OthersFees
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'virtual_recharge';
    }

    public function rules()
    {
        return array(
            array('cardnum,cardid,game_userid,income_price,expend_price', 'required'),
            array('cardnum,type', 'numerical', 'integerOnly' => true),
            array('cardid, game_userid, game_area, game_srv', 'length', 'max' => 255),
            array('id, cardid, cardnum, game_userid, game_area, game_srv,income_price,expend_price', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'cardid' => '卡号',
            'cardnum' => '数量',
            'game_userid' => '帐号',
            'game_area' => 'Game Area',
            'game_srv' => 'Game Srv',
            'income_price' => '收入价格',
            'expend_price' => '支出价格',
            'type' => '类型',
        );
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('cardid', $this->cardid, true);
        $criteria->compare('cardnum', $this->cardnum);
        $criteria->compare('game_userid', $this->game_userid, true);
        $criteria->compare('game_area', $this->game_area, true);
        $criteria->compare('game_srv', $this->game_srv, true);
        $criteria->compare('income_price', $this->income_price, true);
        $criteria->compare('expend_price', $this->expend_price, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function behaviors()
    {
        return array();
    }
}
