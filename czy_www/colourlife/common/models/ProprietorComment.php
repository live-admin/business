<?php

/**
 * This is the model class for table "jf_proprietor_comment".
 *
 * The followings are the available columns in table 'jf_proprietor_comment':
 * @property string $id
 * @property string $create_time
 * @property string $level
 * @property string $content
 * @property string $manager_id
 * @property string $proprietor_id
 * @property string $manager_tag
 * @property string $manager_tag_content
 * @property string $community_id
 * @property string $address
 */
class ProprietorComment extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'jf_proprietor_comment';
	}
	
	static $status_sc = array(
        0 => "未审核",
        1 => "审核成功",
        2 => "审核失败",
    );

    public static function getStatusNameList()
    {
        return CMap::mergeArray(array('' => '全部'), self::$status_sc);
    }
	
    static $send_status = array(
        0 => "未发放",
        1 => "红包发放成功",
        2 => "红包发放失败",
    );
    
	public static function getSendStatusNameList()
    {
        return CMap::mergeArray(array('' => '全部'), self::$send_status);
    }
    
	//发放状态
    public function getSendStatusName($html = false)
    {
        if($this->is_send==1){
            return ($html) ? self::$send_status[$this->is_send] : "<span class='label label-success'>".self::$send_status[$this->is_send]."</span>";
        }else{
            return ($html) ? self::$send_status[$this->is_send] : "<span class='label label-important'>".self::$send_status[$this->is_send]."</span>";
        }
    }

    //审核状态
    public function getStatusName($html = false)
    {
        if($this->status==1){
            return ($html) ? self::$status_sc[$this->status] : "<span class='label label-success'>".self::$status_sc[$this->status]."</span>";
        }else{
            return ($html) ? self::$status_sc[$this->status] : "<span class='label label-important'>".self::$status_sc[$this->status]."</span>";
        }
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time, manager_id, proprietor_id', 'required'),
			array('create_time, level, manager_id, proprietor_id, manager_tag, community_id,evaluate_type', 'length', 'max'=>10),
			array('content, address', 'length', 'max'=>1000),
			array('manager_tag_content', 'length', 'max'=>2000),
			array('red', 'numerical', 'integerOnly'=>false),
			array('status, is_send', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, create_time, level, content, manager_id, proprietor_id, manager_tag, manager_tag_content, community_id, address, red, status, remark, is_send ', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
				'attachment' => array(self::HAS_MANY, 'CommentAttachment', "comment_id"),
				'proprietor' => array(self::BELONGS_TO, "Customer", "proprietor_id"), 
				'manager' => array(self::BELONGS_TO, "Customer", "manager_id"),
				'community' => array(self::BELONGS_TO, "Community", "community_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'create_time' => '评价时间',
			'level' => '评价等级',
			'content' => '评价内容',
			'manager_id' => '业务经理',
			'proprietor_id' => '业主',
			'manager_tag' => '标记',
			'manager_tag_content' => '标记内容',
			'community_id' => '小区',
			'address' => '地址',
			'red' => '红包',
			'status'  => '状态',
			'is_send'  => '发送状态',
			'remark'  => '备注',
			'evaluate_type'  => '类型',
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
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('manager_id',$this->manager_id,true);
		$criteria->compare('proprietor_id',$this->proprietor_id,true);
		$criteria->compare('manager_tag',$this->manager_tag,true);
		$criteria->compare('manager_tag_content',$this->manager_tag_content,true);
		$criteria->compare('community_id',$this->community_id,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('status', $this->status);

		$criteria->compare('is_send', $this->is_send, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 将评价等级转化为文字
	 */
	public static function levelToText($level)
	{
		$level = $level < 0 ? 0 : $level % 4;
		$arr = array(
				0 => "不满意",
				1 => "一般",
				2 => "满意",
				3 => "非常满意"
		);
		return $arr[$level];
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getLevelText()
	{
		return self::levelToText($this->level);
	}
	
	public function auditOK()
    {
        $this->updateByPk($this->id, array('status' => 1));
    }

    public function auditNO()
    {
        $this->updateByPk($this->id, array('status' => 2));
    }
}
