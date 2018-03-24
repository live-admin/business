<?php

/**
 * This is the model class for table "reserve".
 *
 * The followings are the available columns in table 'reserve':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $customer_id
 * @property string $content
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $is_deleted
 * @property string $name
 * @property string $tel
 * @property string $address
 * @property string $time
 */
class Reserve extends CActiveRecord
{
    public $modelName = '服务预订';
    public $customerName;
    public $shopName;

    public $reply;
    public $username;
    public $c_username;
    public $branch_id;
    public $region;
    public $community;
    public $start_time;
    public $end_time;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Reserve the static model class
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
        return 'reserve';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('content', 'required','on'=>'search'),
            array('reply', 'required', 'on' => 'update'),
            array('shop_id, customer_id, create_time, is_deleted, time', 'numerical', 'integerOnly' => true),
            array('create_ip', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('customerName,customer_id,shopName,create_time,username,c_username,branch_id,region,community,start_time,end_time', 'safe', 'on' => 'search'),
            array('name,tel,content, address', 'safe'),
            array('shop_id,content', 'required', 'on' => 'apiCreate'),
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
            'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'reserveReply' => array(self::HAS_MANY, 'ReserveReply', 'reserve_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'shop_id' => '商家',
            'customer_id' => '业主',
            'content' => '预订内容',
            'create_time' => '创建时间',
            'create_ip' => '预订IP',
            'time' => '预订时间',
            'is_deleted' => 'Is Deleted',
            'customerName' => '业主',
            'shopName' => '商家名称',
            'reply' => '回复',
            'name' => '姓名',
            'tel' => '联系电话',
            'cus_Name' => '业主',
            'username' => '商家帐号',
            'c_username' => '业主帐号',
            'region' => '地区',
            'community' => '小区',
            'branch_id' => '管辖部门',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'address' => '地址',
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
        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        $criteria->with[] = "shop";
        if (!empty($this->branch))
            $criteria->addInCondition('shop.branch_id', $this->branch->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
            $criteria->addInCondition('shop.branch_id', $employee->getBranchIds());
        /*if (!empty($this->community)) {
            $criteria->distinct = true;
            $criteria->join = 'inner join shop_community_relation scr on scr.community_id=shop.id';
            $criteria->addInCondition('scr.community_id', $this->community);
        } else if ($this->region != '') {
            $criteria->distinct = true;
            $criteria->join = 'inner join shop_community_relation scr on scr.community_id=shop.id';
            $criteria->addInCondition('src.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
        }*/
        if ($this->start_time != "") {
            $criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }
        $criteria->join .= " left join `customer` on `t`.`customer_id`=`customer`.`id`";
        $criteria->compare("`customer`.`name`", $this->customerName, true);
        $criteria->compare("`customer`.username", $this->c_username, true);
        // $criteria->join .= " left join `shop` on `t`.`shop_id`=`shop`.`id`";
        $criteria->compare("`shop`.`name`", $this->shopName, true);
        $criteria->compare("shop.username", $this->username, true);
        //$criteria->addInCondition('shop.branch_id', $employee->getBranchIds());

        $criteria->compare('`t`.create_time', $this->create_time);
        $criteria->compare('`t`.name', $this->name, true);
        $criteria->compare('`t`.tel', $this->tel, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.create_time desc',
            )
        ));
    }

    public function search_by_shop()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->join .= " left join `customer` on `t`.`customer_id`=`customer`.`id`";
        $criteria->compare("`customer`.`name`", $this->customerName, true);
        $criteria->join .= " left join `shop` on `t`.`shop_id`=`shop`.`id`";
        $criteria->compare("`shop`.`name`", $this->shopName, true);
        $criteria->compare("`t`.`shop_id`", Yii::app()->user->id, true);
        $criteria->compare('`t`.create_time', $this->create_time);
        $criteria->compare('`t`.name', $this->name, true);
        $criteria->compare('`t`.tel', $this->tel, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.create_time desc',
            )
        ));
    }

    public function behaviors()
    {
        return array(

            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'create_ip',
                'updateAttribute' => 'create_ip'
            ),
        );
    }

    public function getChat()
    {
        $_return = ReserveReply::model()->findBySql("select * from `reserve_reply` where `reserve_id`=:reserve_id order by `create_time` desc", array(':reserve_id' => $this->id));
        return $_return;
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


    public function getShop_Name()
    {
        return isset($this->shop) ? $this->shop->name : '';
    }

    public function getCus_Name()
    {
        return isset($this->customer) ? $this->customer->name : '';
    }

    public function getShop_Desc()
    {
        return isset($this->shop) ? $this->shop->desc : '';
    }

    public function getShop_Logo_image()
    {
        return isset($this->shop) ? Yii::app()->imageFile->getUrl($this->shop->logo_image) : '';
    }

}
