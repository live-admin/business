<?php

/**
 * This is the model class for table "ef_fund_project".
 *
 * The followings are the available columns in table 'ef_fund_project':
 * @property string $id
 * @property string $serial_number
 * @property string $title
 * @property string $executive_party
 * @property string $create_time
 * @property string $begin_time
 * @property string $end_time
 * @property string $target_amount
 * @property string $raise_amount
 * @property string $contribute_people_count
 * @property string $pic_url
 * @property string $thumb_url
 * @property integer $state
 * @property string $project_description
 * @property number $used_amount 
 * @property integer $hidden_in_index
 */
class FundProject extends CActiveRecord
{
	public $temp_pic_url;
	
	public $formattedBeginTime;
	public $formattedEndTime;
	
	public $min_createTime;
	public $max_createTime;
	public $min_beginTime;
	public $max_beginTime;
	public $min_endTime;
	public $max_endTime;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ef_fund_project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('serial_number, title, create_time, begin_time, end_time, target_amount, executive_party', 'required'),
				array('state', 'numerical', 'integerOnly'=>true),
				array('serial_number', 'length', 'max'=>128),
				array('title, executive_party', 'length', 'max'=>200),
				array('create_time, begin_time, end_time, contribute_people_count', 'length', 'max'=>10),
				array('target_amount, raise_amount, used_amount', 'length', 'max'=>14),
				array('pic_url, thumb_url', 'length', 'max'=>2000),
				array('project_description', 'safe'),
				array('id, serial_number, title, executive_party, create_time, begin_time, end_time, target_amount, raise_amount, contribute_people_count, pic_url, thumb_url, state, project_description, used_amount', 'safe', 'on'=>'search'),
				
				array('serial_number', 'unique'),
				
				array('formattedBeginTime, formattedEndTime', 'safe'),
				array('min_createTime, max_createtime, min_beginTime, max_beginTime, min_endTime, max_endTime, temp_pic_url', 'safe'),
				
		        array('hidden_in_index', 'safe'),
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
				"project_evolution" => array(self::HAS_MANY, 'FundProjectEvolution', 'fund_project_id'),
				'project_whipround' => array(self::HAS_MANY, 'FundProjectWhipround', 'fund_project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'serial_number' => '项目编号',
				'title' => '标题',
				'executive_party' => '执行方',
				'create_time' => '创建时间',
				'begin_time' => '开始捐款时间',
				'end_time' => '结束捐款时间',
				'target_amount' => '捐款目标金额',
				'raise_amount' => '已经捐款金额',
				'contribute_people_count' => '捐款人数',
				'pic_url' => '图片',
				'thumb_url' => '缩略图',
				'state' => '项目状态',  // 0--默认(系统根据时间和投资情况决定项目的状态), 1--禁用，2--完成',
				'project_description' => '捐款说明',
					
		        'formattedBeginTime' => '开始捐款时间',
		        'formattedEndTime' => '结束捐款时间',
				
				'min_createTime' => '创建时间_起始',
				'max_createTime' => '创建时间_截止',
				
				'min_beginTime' => '开始捐款时间_起始',
				'max_beginTime' => '开始捐款时间_截止',
				
				'min_endTime' => '结束捐款时间_起始',
				'max_endTime' => '结束捐款时间_截止',
		        'used_amount' => '已捐金额',  //基金会往外捐
		        'hidden_in_index' => '在首页隐藏', //1在首页隐藏，0不隐藏
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
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('executive_party',$this->executive_party,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('target_amount',$this->target_amount,true);
		$criteria->compare('raise_amount',$this->raise_amount,true);
		$criteria->compare('contribute_people_count',$this->contribute_people_count,true);
		$criteria->compare('pic_url',$this->pic_url,true);
		$criteria->compare('thumb_url',$this->thumb_url,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('project_description',$this->project_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FundProject the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getPicUrl()
	{
		return Yii::app()->imageFile->getUrl($this->pic_url, '/common/images/nopic-map.jpg');
	}
	
	public function getThumbPicUrl()
	{
		return Yii::app()->imageFile->getUrl($this->thumb_url, '/common/images/nopic-map.jpg');
	}

	/**
	 * @return bool
	 */
	protected function beforeSave()
	{
		if (!empty($this->temp_pic_url)) {
			$this->pic_url = Yii::app()->ajaxUploadImage->moveSave($this->temp_pic_url, $this->pic_url);
			$picPath = Yii::app()->imageFile->getFilename($this->pic_url);
			$thumbPicUrl = Yii::app()->imageFile->getNewName($this->pic_url);
			$thumbPicPath = Yii::app()->imageFile->getFilename($thumbPicUrl);

			$this->thumb_url = $thumbPicUrl;
			
			//剪切到缩略图
			$conversion = new ImageConversion($picPath);
			$conversion->conversion($thumbPicPath, array(
			        'w' => 326,   // 结果图的宽
			        'h' => 246,   // 结果图的高
			        't' => 'resize ,clip', // 转换类型
			));
			
			//剪切大图
			$conversion = new ImageConversion($picPath);
			$conversion->conversion($picPath, array(
			        'w' => 489,   // 结果图的宽
			        'h' => 367,   // 结果图的高
			        't' => 'resize ,clip', // 转换类型
			));
		}
				
		return parent::beforeSave();
	}
	
	public function getStateText()
	{
		switch($this->state)
		{
			case 0 :
				if($this->isNotBegin())
				{
					return "未开始";
				}
				if($this->isRaising())
				{
					return "正在捐款";
				}
				
				if($this->isRaiseComplete())
				{
					return "完成";
				}
				break;
			case 1:
				return "禁用";
				break;
			case 2:
				return "完成";
				break;
			default :
				return "未知";
				break;
		}
	}
	
	/**
	 * 未开始
	 */
	public function isNotBegin()
	{
		return time() < $this->begin_time && ($this->state = 0);
	}
	
	public function isRaising()
	{
	    if($this->isDefaultProject())
	    {
	        return true;
	    }
		$time = time();
		return ($time >= $this->begin_time && $time <= $this->end_time) 
				&& ($this->raise_amount < $this->target_amount)
				&& $this->state == 0
		;
	}
	
	public function isDefaultProject()
	{
	    return $this->id == 1;
	}
	
	public function isRaiseComplete()
	{
	    if($this->isDefaultProject())
	    {
	        return false;
	    }
	    if($this->state == 2)
	    {
	        return true;
	    }
	    if($this->state == 1)
	    {
	        return false;
	    }
		$time = time();
		return ($time > $this->end_time) || ($this->raise_amount >= $this->target_amount) ;
	}
}

