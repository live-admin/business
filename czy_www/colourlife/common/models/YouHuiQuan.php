<?php

/**
 * This is the model class for table "you_hui_quan".
 *
 * The followings are the available columns in table 'you_hui_quan':
 * @property integer $id
 * @property string $name
 * @property string $dec
 * @property integer $type
 * @property integer $get_way
 * @property integer $shop_id
 * @property integer $category_id
 * @property integer $man_jian
 * @property string $limit_product
 * @property integer $use_start_time
 * @property integer $use_end_time
 * @property integer $get_start_time
 * @property integer $get_end_time
 * @property integer $total
 * @property integer $limit_num
 * @property string $amout
 * @property string $community_id
 */
class YouHuiQuan extends CActiveRecord
{
    public $modelName = '优惠券';
    public $type_arr=array(1=>'抵扣券',2=>'红包券');
    public $get_way_arr=array(1=>'买家自行领取',2=>'自动发放至卡包');
    public $shop_id_arr=array(1=>'平台通用',2=>'京东特供',3=>'海外直购',4=>'大闸蟹',5=>'彩生活特供');
//    public $category_id_arr=array(1=>'粮油',2=>'母婴');
    //使用时间
    public $start_time_1;
    public $end_time_1;
    //领取时间
    public $start_time_2;
    public $end_time_2;
    
    public $you_hui_quan_id;
    public $sn;
    
     public $typeFile; //平台图片
    
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'you_hui_quan';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('dec', 'required'),
            array('type, get_way, shop_id, category_id, man_jian,total, limit_num', 'numerical', 'integerOnly'=>true),
            array('name, limit_product, community_id', 'length', 'max'=>20000),
            array('amout', 'length', 'max'=>10),
            array('typeFile', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, dec, type, get_way, shop_id, man_jian, limit_product, use_start_time, use_end_time, get_start_time, get_end_time, total, limit_num, amout, community_id,you_hui_quan_id,sn,typeFile,type_image', 'safe', 'on'=>'search'),
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
            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '优惠券编码',
            'name' => '优惠券名称',
            'dec' => '简短描述',
            'type' => '优惠券类型',
            'get_way' => '领取方式',
            'shop_id' => '适用商家',
//            'category_id' => '适用类目',
            'man_jian' => '达到使用金额',
            'limit_product' => '限制产品',
            'use_start_time_ymd' => '有效期开始时间',
            'use_end_time_ymd' => '有效期结束时间',
            'get_start_time_ymd' => '领取开始时间',
            'get_end_time_ymd' => '领取结束时间',
            'total' => '总数量',
            'limit_num' => '使用次数限制',
            'amout' => '面额',
            'community_id' => '发放小区',
            'use_start_time'=>'有效期开始时间',
            'use_end_time'=>'有效期结束时间',
            'get_start_time'=>'领取开始时间',
            'get_end_time'=>'领取结束时间',
            'type_image'=>'平台类型图标',
            'typeFile'=>'平台类型图标',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('dec',$this->dec,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('get_way',$this->get_way);
        $criteria->compare('shop_id',$this->shop_id);
        $criteria->compare('type_image', $this->type_image, true);
//        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('man_jian',$this->man_jian);
        $criteria->compare('limit_product',$this->limit_product,true);
        $criteria->compare('use_start_time',$this->use_start_time);
        $criteria->compare('use_end_time',$this->use_end_time);
        $criteria->compare('get_start_time',$this->get_start_time);
        $criteria->compare('get_end_time',$this->get_end_time);
        $criteria->compare('total',$this->total);
        $criteria->compare('limit_num',$this->limit_num);
        $criteria->compare('amout',$this->amout,true);
        $criteria->compare('community_id',$this->community_id,true);
        $criteria->order = 'id desc';
        
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    //平台图片
    public function getTypeImageUrl()
    {
        return Yii::app()->imageFile->getUrl($this->type_image, '/common/images/nopic-map.jpg');
    }
    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->typeFile)) {
            $this->type_image = Yii::app()->ajaxUploadImage->moveSave($this->typeFile, $this->type_image);
        }
        return parent::beforeSave();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return YouHuiQuan the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    //优惠券类型
    public function getTypeName()
    {
        return $this->type_arr[$this->type];
    }
    //领取方式
    public function getGetWay()
    {
        return $this->get_way_arr[$this->get_way];
    }
    //适用商家
    public function getShopId()
    {
        return $this->shop_id_arr[$this->shop_id];
    }
    //适用类目
//    public function getCategoryId()
//    {
//        return $this->category_id_arr[$this->category_id];
//    }
    //有效期开始时间
    public function getUse_start_time_ymd(){
        if($this->use_start_time){
            return date("Y-m-d",strtotime($this->use_start_time));
        }else{
            return date("Y-m-d",time());
        }
    }
    //有效期结束时间
    public function getUse_end_time_ymd(){
        if($this->use_end_time){
            return date("Y-m-d",strtotime($this->use_end_time));
        }else{
            return date("Y-m-d",time());
        }
    }
    //领取开始时间
    public function getGet_start_time_ymd(){
        if($this->get_start_time){
            return date("Y-m-d",strtotime($this->get_start_time));
        }else{
            return date("Y-m-d",time());
        }
    }
    //领取结束时间
    public function getGet_end_time_ymd(){
        if($this->get_end_time){
            return date("Y-m-d",strtotime($this->get_end_time));
        }else{
            return date("Y-m-d",time());
        }
    }
    //限制产品的字段
    public function getLimitProduct(){
        return mb_strlen($this->limit_product,'utf-8')>30?F::msubstr($this->limit_product,0,30).'...':$this->limit_product;
    }
}