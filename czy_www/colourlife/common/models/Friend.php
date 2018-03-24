<?php

/**
 * This is the model class for table "friend".
 *
 * The followings are the available columns in table 'friend':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $friend_id
 * @property string $note
 * @property integer $status
 */
class Friend extends CActiveRecord
{
    public $modelName = '家人';
    public $friendusername;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Friend the static model class
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
        return 'friend';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, friend_id ', 'required'),
            array('customer_id, friend_id, status', 'numerical', 'integerOnly' => true),
            array('note', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, customer_id, friend_id, note, status, friendusername', 'safe', 'on' => 'search'),
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
            'customerfriend' => array(self::BELONGS_TO, 'Customer', 'friend_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'customer_id' => '业主用户名',
            'friend_id' => '家人ID',
            'note' => '备注',
            'status' => '状态',
            'friendusername' => '家人用户名'
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
        //var_dump($this->friendusername);exit;
        $criteria->join .= " left join `customer` on `t`.`friend_id`=`customer`.`id`";

        $criteria->compare('`t`.`id`', $this->id);
        $criteria->compare('`t`.`customer_id`', $this->customer_id);
        $criteria->compare('friend_id', $this->friend_id);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('status', $this->status);
        if ($this->friendusername != null) {
            $criteria->compare("`customer`.`username`", $this->friendusername, true);
        }

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));

        //return Friend::model()->findByAttributes($attributes)
    }

    public function behaviors()
    {
        return array();
    }


    /**
     * 获取家人姓名
     */
    public function getFriendName()
    {
        if (isset($this->customerfriend)) {
            return $this->customerfriend->name;
        } else {
            return '';
        }
    }

    /**
     * 获取家人头像
     */
    public function getFriendPortrait()
    {
        if (isset($this->customerfriend)) {
            return $this->customerfriend->getPortraitUrl();
        } else {
            return '';
        }
    }

    /**
     * 获取家人手机
     */
    public function getFriendMobile()
    {
        if (isset($this->customerfriend)) {
            return $this->customerfriend->mobile;
        } else {
            return '';
        }
    }

    /**
     * 获取家人的小区
     * Enter description here ...
     */
    public function getFriendCommunity()
    {
        if (isset($this->customerfriend)) {
            if (isset($this->customerfriend->community)) {
                return $this->customerfriend->community->name;
            } else {
                return '';
            }

        } else {
            return '';
        }

    }

    /**
     *  获取家人房间号
     */
    public function getFriendRoom()
    {
        if (isset($this->customerfriend)) {
            return $this->customerfriend->room;
        } else {
            return '';
        }

    }

    /**
     * 获取好友楼栋
     * Enter description here ...
     */
    public function getFriendBuild()
    {
        if (isset($this->customerfriend)) {
            if (isset($this->customerfriend->build)) {
                return $this->customerfriend->build->name;
            } else {
                return '';
            }

        } else {
            return '';
        }
    }

    public function getStatusText()
    {
        $msg = '';
        switch ($this->status) {
            case "0":
                $msg = "未处理";
                break;
            case "1":
                $msg = "已同意";
                break;
            case "2":
                $msg = "拒绝";
                break;

        }
        return $msg;
    }

}
