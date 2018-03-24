<?php

/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2015/9/22
 * Time: 13:48
 */


/**
 * This is the model class for table "shop".
 *
 * The followings are the available columns in table 'shop':
 * @property integer $id
 * @property string $mobile
 * @property integer $shift_red_packet
 * @property string $name
 * @property string $new_mobile
 * @property string $identity_card
 * @property string $note
 * @property string $customer_id
 * @property integer $create_time
 * @property string $new_moblie
 */







class ShiftInfo extends CActiveRecord
{

    public $modelName = '用户资料修改';
    public $startTime;
    public $endTime;
    public $mobile;
    public $username;
    public $sfhkOrder;
    public $name;
    public $user_name;


    public function tableName()
    {
        return 'shift_info';
    }

    public function rules()
    {
        return array(
            array(
                'id, mobile,shift_red_packet, create_time, user_name, identity_card ,note ,customer_id',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'mobile' => '手机号码',
            'shift_red_packet' => '转移的红包金额',
            'create_time' => '创建时间',
            'user_name' => '用户姓名',
            'username' => 'username',
            'password' => '密码',
            'name' => '姓名',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('username', $this->user_name, true);
        $criteria->compare('mobile', $this->mobile, true);
       //  $criteria->compare('identity_card', $this->identity_card, true);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


}

