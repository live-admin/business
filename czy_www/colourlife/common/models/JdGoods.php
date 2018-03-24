<?php

/**
 * This is the model class for table "activity_goods".
 *
 * The followings are the available columns in table 'activity_goods':
 * @property integer $id
 * @property integer $goods_id
 * @property integer $type
 * @property integer $shop_type
 * @property integer $is_up
 * @property integer $sort
 * @property string $activity_name
 * @property integer $create_time
 * @property integer $update_time
 * @property decimal $rebate_prize
 */
class JdGoods extends CActiveRecord
{
	public $modelName = '京东618商品';
	//分类
	public $_type = array (
        '0'	=>'全部',
        '1' => '最热抢购',
        '2' => '专享特惠',
        '3' => '品牌专区',
        '4' => '潮流数码',
        '5' => '生活电器',
        '6' => '美妆个护',
        '7' => '休闲零食',
        '8' => '家居必备',
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'jd_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, type, sort, create_time, update_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goods_id, type, sort, create_time, update_time', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'goods_id' => '商品ID',
			'type' => '分类',
			'sort' => '排序',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
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
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivityGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取商品
	 * @param $isType
	 * @return multitype:
	 */
	public function getProducts($type=array(0)){
		$data=array();
        $num=count($type);
		$criteria = new CDbCriteria() ;
        if($type[0]!=0){
            $typeStr=implode(',', $type);
            $criteria -> condition = 'type in ('.$typeStr.')';
        }
		$criteria -> order = 'sort asc';
		$goodIDs=$this->findAll($criteria);
		if (!empty($goodIDs)){
			foreach ($goodIDs as $val){
				$tmp=array();
				$productArr=$this->getProductDetail($val->goods_id);
				if (empty($productArr)){
					continue;
				}
				$image_arr=array();
                $price=$productArr->customer_price;
                $image_arr=explode(':', $productArr['good_image']);
                if(count($image_arr)>1){
                    $tmp['img_name'] = $productArr['good_image'];
                }else{
                    $tmp['img_name'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
                }
				$tmp['gid']= $val->goods_id;
				$tmp['name']=$productArr->name;
				$tmp['customer_price']=$price;
				$tmp['market_price']=$productArr->market_price;
                $tmp['brief']=$productArr->brief;
				//是否按照专区分类
				if ($type[0]==0 || $num!=1){
					$data[$val->type][]=$tmp;
				}else {
					$data[]=$tmp;
				}
			}
			unset($goodIDs);
		}
		return $data;
	}
	
	/**
	 * 获取商品详情
	 * @param unknown $goods_id
	 * @return array
	 */
	public function getProductDetail($goods_id){
		if(empty($goods_id)){
			return false;
		}
		$productArr=Goods::model()->findByPk($goods_id);
		return $productArr;
	}
	
	/**
	 * 获取分类名称
	 * @param string $state
	 * @return string
	 */
	public function getTypeName($type)
	{
		return $this->_type[$type];
	}
}
