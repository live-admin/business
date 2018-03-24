<?php

/**
 * This is the model class for table "czz_building".
 *
 * The followings are the available columns in table 'czz_building':
 * @property string $id
 * @property string $name
 * @property string $location
 * @property string $location_coordinate
 * @property integer $open_time
 * @property integer $property_right
 * @property string $around_education
 * @property string $around_food
 * @property string $around_shopping
 * @property string $around_transport
 * @property string $property_type
 * @property integer $checkin_time
 * @property double $property_fee_preice
 * @property string $developer
 * @property string $sales_license_number
 * @property integer $building_area
 * @property integer $floor_area
 * @property integer $total_home_count
 * @property string $build_type
 * @property string $decoration_state
 * @property double $plot_ratio
 * @property double $green_ratio_per
 * @property string $property_company
 * @property string $pic_url
 * @property string $thumb_url
 * @property string $adv_slogan
 * @property string $state
 * @property string $display_order
 */
class CzzBuilding extends CActiveRecord
{
    public $temp_pic_url;
    public $temp_pic_path;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'czz_building';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, location, open_time, checkin_time', 'required'),
			array('open_time, property_right, display_order,checkin_time, building_area, floor_area, total_home_count', 'numerical', 'integerOnly'=>true),
			array('property_fee_preice, plot_ratio, green_ratio_per, state', 'numerical'),
			array('name, location_coordinate, developer, property_company', 'length', 'max'=>128),
			array('location', 'length', 'max'=>512),
			array('around_education, around_food, around_shopping, around_transport', 'length', 'max'=>1000),
			array('property_type, decoration_state', 'length', 'max'=>32),
			array('sales_license_number, build_type', 'length', 'max'=>64),
			array('pic_url, thumb_url', 'length', 'max'=>400),
			array('adv_slogan', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, location, display_order, location_coordinate, open_time, property_right, around_education, around_food, around_shopping, around_transport, property_type, checkin_time, property_fee_preice, developer, sales_license_number, building_area, floor_area, total_home_count, build_type, decoration_state, plot_ratio, green_ratio_per, property_company, pic_url, thumb_url, adv_slogan', 'safe', 'on'=>'search'),
		    
		    array('pic_url', 'picCheck'),

		    array('open_time_format, checkin_time_format, temp_pic_url, temp_pic_path  ', 'safe'),
		);
	}
	
	public function picCheck()
	{
	    if(empty($this->pic_url) && empty($this->temp_pic_path))
	    {
	        $this->addError("pic_url", "房源图片不能为空");
	    }
	}


	public function getopen_time_format()
	{
	    return date('Y-m-d', $this->open_time);
	}
	
	public function setopen_time_format($val)
	{
	    $this->open_time = Util::getStartTimeOfDay($val);
	}
	
    public function getcheckin_time_format()
	{
	    return date('Y-m-d', $this->checkin_time);
	}
	
	public function setcheckin_time_format($val)
	{
	    return $this->checkin_time = Util::getStartTimeOfDay($val);
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
			'id' => 'ID',
			'name' => '楼盘名称',
			'location' => '地理位置',
			'location_coordinate' => '地理位置坐标',
			'open_time' => '开盘时间',
			'property_right' => '产权年限',
			'around_education' => '周边-医疗教育',
			'around_food' => '周边-餐饮娱乐',
			'around_shopping' => '周边-超时购物',
			'around_transport' => '周边-交通配套',
			'property_type' => '物业类型',
			'checkin_time' => ' 入住时间',
			'property_fee_preice' => '物业费(元/平米)',
			'developer' => '开发商',
			'sales_license_number' => '销售许可证号',
			'building_area' => '建筑面积',
			'floor_area' => '占地面积',
			'total_home_count' => '总户数',
			'build_type' => '建筑类别',
			'decoration_state' => '装修状况',
			'plot_ratio' => '容积率',
			'green_ratio_per' => '绿化率(百分比)',
			'property_company' => '物业公司',
			'pic_url' => '房源图',
			'thumb_url' => '房源缩略图',
			'adv_slogan' => '广告语',
		    'state' => '状态', //0-正常, 1-售尽, 2-停售
		        
		    'open_time_format' => '开盘时间',
		    'checkin_time_format' => '入住时间',
			'display_order' => '显示顺序',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('location_coordinate',$this->location_coordinate,true);
		$criteria->compare('open_time',$this->open_time);
		$criteria->compare('property_right',$this->property_right);
		$criteria->compare('around_education',$this->around_education,true);
		$criteria->compare('around_food',$this->around_food,true);
		$criteria->compare('around_shopping',$this->around_shopping,true);
		$criteria->compare('around_transport',$this->around_transport,true);
		$criteria->compare('property_type',$this->property_type,true);
		$criteria->compare('checkin_time',$this->checkin_time);
		$criteria->compare('property_fee_preice',$this->property_fee_preice);
		$criteria->compare('developer',$this->developer,true);
		$criteria->compare('sales_license_number',$this->sales_license_number,true);
		$criteria->compare('building_area',$this->building_area);
		$criteria->compare('floor_area',$this->floor_area);
		$criteria->compare('total_home_count',$this->total_home_count);
		$criteria->compare('build_type',$this->build_type,true);
		$criteria->compare('decoration_state',$this->decoration_state,true);
		$criteria->compare('plot_ratio',$this->plot_ratio);
		$criteria->compare('green_ratio_per',$this->green_ratio_per);
		$criteria->compare('property_company',$this->property_company,true);
		$criteria->compare('pic_url',$this->pic_url,true);
		$criteria->compare('thumb_url',$this->thumb_url,true);
		$criteria->compare('adv_slogan',$this->adv_slogan,true);
		$criteria->compare('display_order',$this->display_order,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getPicUrl()
	{
	    return Yii::app()->imageFile->getUrl($this->pic_url, '/common/images/nopic-map.jpg');
	}
	
	public function getThumbUrl()
	{
	    return Yii::app()->imageFile->getUrl($this->thumb_url, '/common/images/nopic-map.jpg');
	}



	/**
	 * @return bool
	 */
	protected function beforeSave()
	{
	    if (!empty($this->temp_pic_path)) {
	        $this->pic_url = Yii::app()->ajaxUploadImage->moveSave($this->temp_pic_path, $this->pic_url);
	        $picPath = Yii::app()->imageFile->getFilename($this->pic_url);
	        $thumbPicUrl = Yii::app()->imageFile->getNewName($this->pic_url);
	        $thumbPicPath = Yii::app()->imageFile->getFilename($thumbPicUrl);

	        $this->thumb_url = $thumbPicUrl;
	        	
	        //剪切到缩略图
	        $conversion = new ImageConversion($picPath);
	        $conversion->conversion($thumbPicPath, array(
	            'w' => 234,   // 结果图的宽
	            'h' => 100,   // 结果图的高
	            't' => 'resize ,clip', // 转换类型
	        ));
	        	
	        //剪切大图
	        $conversion = new ImageConversion($picPath);
	        $conversion->conversion($picPath, array(
	            'w' => 640,   // 结果图的宽
	            'h' => 300,   // 结果图的高
	            't' => 'resize ,clip', // 转换类型
	        ));
	    }

	    return parent::beforeSave();
	}

	public function getStateText()
	{
	    $stateTab = array(
	        0 => '正常',
	        1 => '售尽',
	        2 => '停售',
	    );
	    return isset($stateTab[$this->state]) ? $stateTab[$this->state] : "未知";
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CzzBuilding the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
