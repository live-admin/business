<?php

/**
 * This is the model class for table "suggestion".
 *
 * The followings are the available columns in table 'suggestion':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $tel
 * @property integer $category_id
 * @property string $content
 * @property integer $audit
 * @property string $comment
 * @property integer $level
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_deleted
 * @property integer $is_replied
 */
class Suggestion extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '建议';

    public $replyType = array('1' => '是','0' => '否');
    public $logoFile;


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Suggestion the static model class
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
        return 'suggestion';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id,category_id,content,name,tel', 'required', 'on' => 'create,update'),
					  array('level,audit,comment', 'required', 'on' => 'auditOK, auditNO'),
					  array('audit', 'checkAudit', 'on' => 'auditOK, auditNO'), // 审核通过
            array('user_id,audit,level,category_id,create_time, update_time, is_deleted,is_replied', 'numerical', 'integerOnly' => true),
            array('name','length', 'max' => 255),
            array('content,logoFile', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,user_id,name,tel,audit,level,comment, category_id, content, create_time, update_time, is_deleted,is_replied', 'safe', 'on' => 'search'),
        );
    }

		/**
     * 检查指审核时是否有审核过的状态
     * @param $attribute
     * @param $params
     */
    public function checkAudit($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->isAudited) {
                $this->addError($attribute, $this->modelName . '已审核过，无法操作。');
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category' => array(self::BELONGS_TO, 'SuggestionCategory', 'category_id'),
					  'lv' => array(self::BELONGS_TO, 'SuggestionLevel', 'level'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
						'user_id' => '用户ID',
					  'name' => '姓名',
				  	'tel' => '电话',
            'category_id' => '分类',
            'content' => '建议内容',
					  'audit' => '审核',
					  'comment' => '审核意见',
					  'level' => '级别',
            'create_time' => '创建时间',
            'update_time' => '最后更新时间',
            'is_deleted' => '是否删除',
            'is_replied' => '是否回复',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
				$criteria->compare('user_id', $this->user_id);
				$criteria->compare('name', $this->name, true);
				$criteria->compare('tel', $this->tel, true);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('content', $this->content, true);
				$criteria->compare('audit', $this->audit);
				$criteria->compare('comment', $this->comment, true);
				$criteria->compare('level', $this->level);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
				$criteria->compare('is_replied', $this->is_replied);
        $criteria->order = 'update_time desc,create_time desc';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(

            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
						'AuditBehavior' => array(
                'class' => 'common.components.behaviors.AuditBehavior',
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

		public function auditOK()
    {
        $this->updateByPk($this->id, array('audit' => 1));
    }

    public function auditNO()
    {
        $this->updateByPk($this->id, array('audit' => 2));
    }

    public function getReplyType()
    {
        return $this->replyType;
    }

    public function getTypeName()
    {
        return $this->replyType[$this->is_replied];
    }

		public function getLevelNames()
    {
        $levels = array();
				$criteria = new CDbCriteria;
				$criteria->compare("state","0");
				$criteria->compare("is_deleted","0");
				$levels = SuggestionLevel::model()->findAll($criteria);
				if (isset($levels)) {
            $level_list = array();
            foreach ($levels as $level) {
                $level_list[''] = "请选择级别";
                $level_list[$level->id] = $level->name.'　红包:'.$level->redpacket;
            }
            return $level_list;
        } else {
            return "";
        }

    }


}
