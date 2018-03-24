<?php

/**
 * This is the model class for table "examine".
 *
 * The followings are the available columns in table 'examine':
 * @property integer $id
 * @property string $answers
 * @property string $note
 * @property integer $create_time
 * @property integer $customer_id
 */
class Examine extends CActiveRecord
{


    public $modelName = '用户答卷';
	public $startTime;
    public $endTime;
	public $regionName;
	public $communityName;
	public $Number;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Examine the static model class
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
        return 'examine';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id,category_id,answers', 'required', 'on' => 'create'),
            array('create_time, customer_id', 'numerical', 'integerOnly' => true),
            array('answers', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            //array('note', 'safe'),
            array('id,category_id, answers, note, create_time, regionName, customer_id, startTime, endTime', 'safe', 'on' => 'search'),
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
            'category' => array(self::BELONGS_TO, 'ExamineCategory', 'category_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id', 'select'=>'name'),
        );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'answers' => '答案',
            'note' => '反馈意见',
            'create_time' => '创建时间',
            'customer_id' => '用户id',
            'category_id' => '问卷期数',
            'is_deleted' => '是否被删',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'regionName'=>'地区',
			'Number'=>'总数',
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
		if (empty($this->startTime) && empty($this->endTime)) {
			$criteria->compare('id', $this->id);
			$criteria->compare('answers', $this->answers, true);
			$criteria->compare('note', $this->note, true);
			$criteria->compare('create_time', $this->create_time);
			$criteria->compare('customer_id', $this->customer_id);
			$criteria->compare('category_id', $this->category_id);
		} else {
		    //时间搜
			$criteria->select = 'r.name as regionName,
								com.name as communityName,
								count(*) as Number';
			$criteria->group ='com.name,r.name';
			$criteria->join = "JOIN customer c on t.customer_id=c.id JOIN community com on com.id=c.community_id JOIN region r on r.id=com.region_id"; 
			if ($this->startTime != '') {
				$criteria->compare("t.create_time", ">=" . strtotime($this->startTime . ' 00:00:00'));
			}
			
			if ($this->endTime != '') {
				$criteria->compare("t.create_time", "<=" . strtotime($this->endTime . ' 23:59:59'));
			}	
		}
		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
		));
    }
	
	
	public function beforeSave(){
        /*一个月内禁止评论*/
	    $date = date('Y-m') . '-01';
		$hour = date('H:i:s');
		$month = 1;
		$end_date =  date('Y-m-d', strtotime($date."+{$month} month -1 day"));
	    $start_date = strtotime($date . ' 00:00:00');
	    $end_date = strtotime($end_date . ' 23:59:59');
	    $re = $this::model()->find("customer_id=:uid AND create_time>=:start_date AND create_time<=:end_date",array(':uid'=>$this->customer_id, ':start_date'=>$start_date, ':end_date'=>$end_date));
		if (!empty($re)) {
               
			//echo json_encode(array("success"=>0,"data"=>array('msg'=>'一个月只能评论一次哦',"errors"=>array('一个月只能评论一次哦'))));
                    echo "一个月只能评论一次哦";
			exit;
		}
		return parent::beforeSave();
	}

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

}
