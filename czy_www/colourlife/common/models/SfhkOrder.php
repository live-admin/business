<?php
/**
 * This is the model class for table "topic".
 *
 * The followings are the available columns in table 'topic':
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $user_id
 * @property integer $community_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $cate_id
 * @property integer $favour_num
 * @property integer $comment_num
 * @property integer $status
 */
class SfhkOrder extends CActiveRecord {

    public $modelName = '顺风订单';
    const WAITAUDIT = 0;
    const AGREEAUDIT = 1;
    const DISAGREEAUDIT = 2;
    public $name;
    public $username;
    private static $_status = array(
        self::WAITAUDIT => "待审核", //已下单，待付款
        self::AGREEAUDIT => "审核通过", //买家已付款待发货
        self::DISAGREEAUDIT => "审核不通过", //已发货，待收货
    );

    public function getStatusNames() {
        return CMap::mergeArray(array('' => '全部'), self::$_status);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sfhk_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id,order_id,order_start_time,order_total_price,fullname,telephone,mobile,address', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            "sfhkOrderProducts" => array(self::MANY_MANY, "SfhkOrderProducts", array('order_id' => 'order_id')),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'order_id' => '订单号',
            'order_start_time' => '下单时间',
            'order_total_price' => '订单金额',
            'fullname' => '收货人名称',
            'telephone' => '收货人电话',
            'mobile' => '收货人手机号',
            'address' => '收货人地址',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('order_id', $this->order_id, true);
        $criteria->compare('order_start_time', $this->order_start_time, true);
        $criteria->compare('order_total_price', $this->order_total_price);
        $criteria->compare('fullname', $this->fullname);
        $criteria->compare('telephone', $this->telephone, true);
        $criteria->compare('mobile', $this->mobile);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id desc',
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Topic the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //获取状态
    public function getStatusName() {
        if ($this->status == self::WAITAUDIT) {
            return "待审核";
        } else if ($this->status == self::AGREEAUDIT) {
            return "审核通过";
        } else if ($this->status == self::DISAGREEAUDIT) {
            return "审核不通过";
        }
    }

    public function behaviors() {
        return array();
    }
}
