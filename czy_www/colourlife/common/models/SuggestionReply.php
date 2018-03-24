<?php

/**
 * This is the model class for table "suggestion_reply".
 *
 * The followings are the available columns in table 'suggestion_reply':
 * @property integer $id
 * @property integer $user_id
 * @property string $user_name
 * @property integer $suggestion_id
 * @property string $content
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $is_deleted
 */
class SuggestionReply extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SuggestionReply the static model class
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
        return 'suggestion_reply';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id,user_name,content', 'required','on' => 'create'),
            array('suggestion_id, user_id,create_time, is_deleted', 'numerical', 'integerOnly' => true),
            array('create_ip', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,user_id,user_name, suggestion_id, content, create_time, create_ip, is_deleted', 'safe', 'on' => 'search'),
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
            'suggestion' => array(self::BELONGS_TO, 'Suggestion', 'suggestion_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
					  'user_id' => '回复者ID',
					  'user_name' => '回复者',
            'suggestion_id' => 'Suggestion',
            'content' => '回复内容',
            'create_time' => '回复时间',
            'create_ip' => 'Create Ip',
            'is_deleted' => 'Is Deleted',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('suggestion_id', $this->suggestion_id);
				$criteria->compare('user_id', $this->user_id);
				$criteria->compare('user_name', $this->user_name, true);
				$criteria->compare('content', $this->content, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

}
