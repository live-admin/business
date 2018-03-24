<?php

/**
 * This is the model class for table "platform_shop_applay".
 *
 * The followings are the available columns in table 'platform_shop_applay':
 * @property integer $id
 * @property string $comp_name
 * @property string $sell_goods
 * @property string $contact_name
 * @property string $contact_phone
 * @property string $app_desciption
 * @property string $ip_address
 * @property string $create_date
 * @property integer $region_id
 * @property integer $status
 * @property string $update_date
 * @property integer $update_uid
 */
class PlatformShopApply extends CActiveRecord
{
	public $modelName = '平台商家申请';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'platform_shop_apply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cate_id,comp_name, sell_goods, contact_name, contact_phone', 'required'),
			array('region_id, status, update_uid', 'numerical', 'integerOnly'=>true),
			array('comp_name, sell_goods', 'length', 'max'=>200),
			array('contact_name, ip_address', 'length', 'max'=>50),
			array('contact_phone', 'length', 'max'=>20),
			array('create_date, update_date,update_note,service_area,region_id,app_desciption', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,cate_id,comp_name, sell_goods, contact_name, contact_phone, app_desciption, ip_address, create_date, region_id,service_area, status, update_date, update_uid', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'PlatformShopCategory', 'cate_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cate_id'=>'平台商家分类',
			'comp_name' => '公司名称',
			'sell_goods' => '开店产品/意向服务/所属行业/投放产品',
			'contact_name' => '联系人',
			'contact_phone' => '联系电话',
			'app_desciption' => '意向说明/服务介绍/投放意向',
			'ip_address' => 'ip地址',
			'create_date' => '申请时间',
			'region_id' => '服务区域',
			'service_area'=>'服务地域',
			'status' => '申请状态',
			'update_date' => '更新时间',
			'update_uid' => '更新人',
			'update_note'=>'更改备注',
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
		$criteria->compare('cate_id',$this->cate_id);
		$criteria->compare('comp_name',$this->comp_name,true);
		//$criteria->compare('sell_goods',$this->sell_goods,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('contact_phone',$this->contact_phone,true);
		//$criteria->compare('app_desciption',$this->app_desciption,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('service_area',$this->service_area,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->compare('update_uid',$this->update_uid);
		
		$criteria->order="status asc,create_date DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlatformShopApplay the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 截取字符并hover显示全部字符串
	 * $str string 截取的字符串
	 * $len int 截取的长度
	 */
	public function getFullString($str, $len)
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
	}
	
	public function getStatusName()
	{
		switch ($this->status) {
			case 0:
				return "新建";
				break;
			case 1:
				return "处理中";
				break;
			case 2:
				return "通过";
				break;
			case 3:
				return "拒绝";
				break;
		}
	}
	
	public function getRegionName(){
		if(empty($this->region_id)){
			return "全国";
		}
		$region=Region::getMyRegion($this->region_id);
		if(empty($region)){
			return "全国";
		}
		return implode("-", $region);
	}

    public function getRegionList($pid){
        $regionList = Region::model()->enabled()->orderByGBK()->findAll("parent_id=:pid",array(":pid"=>$pid));
        $list = array();
        if (!empty($regionList)) {
            foreach ($regionList as $region) {
                array_push($list, array('id' => $region->id, 'pId' => $region->parent_id, 'name' => $region->name));
            }
        }
        return $list;
    }
	
	public function getUpdateUserName(){
		//$user=Customer::model()->findByPk($this->update_uid);
		if(empty($this->update_uid)){
			return null;
		}
		$user=Employee::model()->findByPk($this->update_uid);
		if(empty($user)){
			return null;
		}
		return $user->name;
	}
}
