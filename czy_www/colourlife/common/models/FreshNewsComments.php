<?php

/**
 * This is the model class for table "fresh_news_comments".
 *
 * The followings are the available columns in table 'fresh_news_comments':
 * @property integer $id
 * @property string $content
 * @property integer $creator
 * @property integer $type
 * @property integer $audit
 * @property integer $fresh_news_id
 * @property integer $reply_id
 * @property integer $create_time
 * @property Customer $customer
 */
class FreshNewsComments extends CActiveRecord
{
    public $modelName = '新鲜事评论';
    public $start_time;
    public $end_time;
    public $reply;//评论循环

    const AUDIT_PASS = 1; //审核状态-通过
    const AUDIT_WAIT = 0; //审核状态 - 待审
    const AUDIT_OUT = 2; //审核状态-不通过

    const COMMENTS_TYPE_FRESH = 0;//新鲜事
    const COMMENTS_TYPE_PRAISE = 1;//表扬

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fresh_news_comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('creator, fresh_news_id', 'required'),
			array('creator, fresh_news_id, reply_id', 'numerical', 'integerOnly'=>true),
			array('content', 'safe'),
            array('fresh_news_id, content', 'checkCreate', 'on' => 'createComments'),//用户评论
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, content, creator, type, fresh_news_id, create_time, start_time, end_time, audit', 'safe', 'on'=>'search'),
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
            'freshNewss' => array(self::BELONGS_TO,'FreshNews','fresh_news_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'creator'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => '评论内容',
			'creator' => '评论人',
            'type' => '类型',
			'fresh_news_id' => '信息名称',
            'reply_id' => '回复ID',
            'audit' => '审核状态',
			'create_time' => '评论时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
		);
	}

    protected function afterFind()
    {
        $this->reply = self::model()->findAllByAttributes(array('fresh_news_id' => $this->fresh_news_id, 'reply_id' => $this->id, 'audit' => FreshNews::AUDIT_PASS), array('order' => 'create_time DESC'));
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
        $criteria->compare('type',$this->type);
		$criteria->compare('id',$this->id);
        $criteria->compare('audit',$this->audit);
		$criteria->compare('content',$this->content,true);
        if(!empty($this->creator)){
            $customer = Customer::model()->findByAttributes(array('username' => $this->creator));
            if(!empty($customer)){
                $criteria->compare('creator',$customer->id);
            }
        }
        $criteria->compare('reply_id',$this->reply_id);
		$criteria->compare('fresh_news_id',$this->fresh_news_id);
        if ($this->start_time != "") {
            $criteria->addCondition('create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 't.audit, t.create_time DESC'
            ),
		));
	}

    public function getFreshNews()
    {
        if(!empty($this->freshNewss)){
            return $this->freshNewss->title;
        }
        return $this->fresh_news_id;
    }

    public function getCreatorName()
    {
        if($model = Customer::model()->findByPk($this->creator)){
            return $model->username;
        }
        return '';
    }

    public function getReplyName()
    {
        if($model = Customer::model()->findByPk($this->reply_id)){
            return $model->name;
        }
        return '';
    }

    public function getAuditName($audit = '')
    {
        $return = '';
        switch ($audit) {
            case '':
                $return = "";
                break;
            case 0:
                $return = '<span class="label label-error">待审核</span>';
                break;
            case 1:
                $return = '<span class="label label-success">审核通过</span>';
                break;
            case 2:
                $return = '<span class="label label-error">审核未通过</span>';
                break;
        }
        return $return;
    }

    public function getIsAudit()
    {
        return $this->getAttribute('audit') == self::AUDIT_WAIT;
    }

    public function passAudit()
    {
        $this->audit = self::AUDIT_PASS;

    }

    public function outAudit()
    {
        $this->audit = self::AUDIT_OUT;
    }

    public function checkCreate($attribute, $param)
    {
        if (!$this->hasErrors()) {
            if(empty($this->fresh_news_id)){
                $this->addError('fresh_news_id', '评论文章ID不能为空');
            }
            if(empty($this->content)){
                $this->addError('content', '评论内容不能为空！');
            }
        }
    }

    /**
     * 发表评论
     * @param array $data array('content' => data, 'fresh_news_id => '', 'reply_id' => '')
     * @param int $type     新鲜事|表扬
     * @param int $creator  发表人
     * @return bool
     */
    public static function createComments(array $data, $type = FreshNews::FRESH_NEW_TYPE, $creator = 0)
    {
        if(empty($creator) && !empty(Yii::app()->user->id)){
            $creator = Yii::app()->user->id;
        }
        $audit = FreshNews::AUDIT_WAIT;//评论默念审核状态
        if(isset(Yii::app()->config->{'communityInteraction'}) && Yii::app()->config->{'communityInteraction'}['commentsAutoAudit']){//是否开启自动审核
            $audit = FreshNews::AUDIT_PASS;
        }
        $attribute = array(
            'content' => isset($data['content']) ? $data['content'] : null,
            'fresh_news_id' => isset($data['fresh_news_id']) ? $data['fresh_news_id'] : null,
            'reply_id' => isset($data['reply_id']) ? $data['reply_id'] : null,
        );
        $model = new self();
        $model->setScenario('createComments');
        $model->attributes = $attribute;
        $model->creator = $creator;
        $model->type = $type;
        $model->audit = $audit;
        if($model->validate() && $model->save()){
            return true;
        }
        return $model->getErrors();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FreshNewsComments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function behaviors(){
		$array = array( 'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
		));
		return CMap::mergeArray(parent::behaviors(),$array);
	}

    public function getCustomerPortrait()
    {
        if($this->customer && !empty($this->customer->portrait)){
            return Yii::app()->ajaxUploadImage->getUrl($this->customer->portrait, '/common/images/nopic.png');
        }
        return Yii::app()->ajaxUploadImage->getUrl('/common/images/nopic.png');
    }

    /**
     * 循环回复评论
     * @param array $comments
     * @param FreshNews $freshNews
     * @return string
     */
    public static function forComments(array $comments, FreshNews $freshNews)
    {
        $html = '';
        $temp = 'forComments';
        $type = FreshNews::FRESH_NEW_TYPE == $freshNews->type ? 'freshNews' : 'praise';
        if($comments){
            /**
             * @var FreshNewsComments $val
             */
            foreach($comments as $val)
            {
                $html .= <<<EOF
<dl>
    <dt class="cp_list_img"><img src="{$val->customerPortrait}"></dt>
    <dd class="cp_list_content">
        <p class="list_base_info">
            <span>{$val->creatorName}</span>回复<span>{$freshNews->creatorName}</span>
        </p>
        <div class="topic_details">
            <p>{$val->content}</p>
        </div>
        <div class="comment_conent" style="display:none">
            <textarea class="comment_text" data-val="{$freshNews->id}" data-type="{$type}" data-ext="{$val->id}" placeholder="回复：{$val->creatorName}"></textarea>
            <div class="func_area clearfix">
                <button class="oneBtn newbtn dark_82x28">发表</button>
            </div>
        </div>
        <div class="comment_this">
            <a href="javascript:void(0);" class="comment_a">回复</a>
        </div>
    </dd>
</dl>
EOF;
                $html .= self::forComments($val->reply, $freshNews);
            }
        }
        return $html;
    }
}
