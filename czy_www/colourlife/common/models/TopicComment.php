<?php

/**
 * This is the model class for table "topic_comment".
 *
 * The followings are the available columns in table 'topic_comment':
 * @property string $id
 * @property integer $topic_id
 * @property string $content
 * @property integer $user_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 */
class TopicComment extends CActiveRecord
{
	const STATUS_APPROVED=0;
    public $start_time;
    public $end_time;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'topic_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('topic_id, content, user_id, create_time, update_time', 'required'),
			array('topic_id, user_id, status', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>500),
			array('create_time, update_time', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, topic_id, content,start_time,end_time, user_id, create_time, update_time, status', 'safe', 'on'=>'search'),
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
            'customer'=>array(self::BELONGS_TO,'Customer','user_id'),
            'topic'=>array(self::BELONGS_TO,'Topic','topic_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'topic_id' => '话题ID',
			'content' => '内容',
			'user_id' => '用户ID',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'status' => '状态，默认0，0=>待审核，1=>审核通过，2=>审核不通过',
            'start_time'=>'开始时间',
            'end_time'=>'结束时间'
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

		$criteria->compare('content',$this->content,true);
        if(!empty($this->start_time)){
            $criteria->addCondition("create_time>=" . strtotime($this->start_time));
        }
        if(!empty($this->end_time)){
            $criteria->addCondition("create_time<=" . strtotime($this->end_time));
        }
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 'id desc',
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TopicComment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //获取昵称
    public function getNickname(){
        if(!empty($this->customer)){
            return empty($this->customer->nickname)?"匿名":$this->customer->nickname;
        }else{
            return "匿名";
        }
    }

    //获取评论人账号
    public function getUsername(){
        if(!empty($this->customer)){
            return $this->customer->username;
        }else{
            return "";
        }
    }

    //获取头像
    public function getCustomerImg(){
        if(isset($this->customer)){
            if(!empty($this->customer->portrait)){
                return F::getUploadsUrl("/images/" . $this->customer->portrait);
            };
        }
        return F::getStaticsUrl('/linli/images/normal.png');
    }

    public function getShortContent(){
        return mb_strlen($this->content,'utf-8')>30?F::msubstr($this->content,0,30).'...':$this->content;
    }
    //a标签
    public function getLinkTag()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => CHtml::normalizeUrl(array("/topic/topic/view","id"=>$this->topic_id))), "查看帖子");
    }

    //获取发帖人的头像
    public function getTopicCustomerImg(){
        if(isset($this->topic)){
            if(!empty($this->topic->customer->portrait)){
                return F::getUploadsUrl("/images/" . $this->topic->customer->portrait);
            };
        }
        return F::getStaticsUrl('/linli/images/normal.png');
    }
}
