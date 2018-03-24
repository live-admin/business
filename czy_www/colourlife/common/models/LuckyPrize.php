<?php

/**
 * This is the model class for table "lucky_prize".
 *
 * The followings are the available columns in table 'lucky_prize':
 * @property integer $id
 * @property string $prize_name
 * @property string $prize_des
 * @property integer $prize_level
 * @property string $prize_picture
 * @property integer $prize_genner_time
 * @property string $prize_level_name
 * @property integer $prize_count_all
 * @property integer $prize_count_now
 * @property integer $disable
 * @property integer $isdelete
 * @property integer $lucky_act_id
 * @property string $create_username
 * @property integer $create_userid
 * @property string $create_date
 * @property string $update_username
 * @property integer $update_userid
 * @property string $update_date
 */
class LuckyPrize extends CActiveRecord
{
	public $modelName = '抽奖奖项';
	public $prize_pictureFile;
        
        
        const TYPE_COMMON = 0;     //类型：普通奖品
        const TYPE_RED_ENVELOPE = 1;   //类型：红包
        
        const DELAY_NO = 0;       //发奖品无延时
        const DELAY_YES = 1;      //发奖品需要延时
        
        public function getRedEnvelopeTotal(){
            
        }
        
        public function getTypeName()
        {
            switch ($this->type) {
                case self::TYPE_COMMON:
                    return "普通奖品";
                    break;
                case self::TYPE_RED_ENVELOPE:
                    return "红包";
                    break;
            }
        }
        
        public function getDelayName(){
            switch ($this->delay) {
                case self::DELAY_NO:
                    return "无需延时";
                    break;
                case self::DELAY_YES:
                    return "需要延时";
                    break;
                default :
                    return "默认";
                    break;
            }
        }
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_prize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('prize_name, prize_level, prize_genner_time, prize_level_name, prize_count_all, prize_count_now, lucky_act_id, create_username, create_userid, create_date, update_username, update_userid, update_date', 'required'),
			array('prize_name, prize_genner_time, prize_level_name, prize_count_all, lucky_act_id', 'required'),
			array('prize_level, prize_genner_time, prize_count_all, prize_count_now, disable, isdelete, lucky_act_id, create_userid, update_userid, type', 'numerical', 'integerOnly'=>true),
			array('prize_name, create_username, update_username', 'length', 'max'=>100),
			array('prize_picture, prize_level_name', 'length', 'max'=>500),
			array('prize_des,delay', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prize_name, prize_des, prize_level, prize_picture, prize_genner_time, prize_level_name, prize_count_all, prize_count_now, disable, isdelete, lucky_act_id, create_username, create_userid, create_date, update_username, update_userid, update_date', 'safe', 'on'=>'search'),
			array('prize_pictureFile', 'safe', 'on' => 'create, update'),
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
            'luckyAct' => array(self::BELONGS_TO, 'LuckyActivity', 'lucky_act_id'),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'prize_name' => '奖品名称',
			'prize_des' => '奖品描述',
			'prize_level' => '奖品等级',
			'prize_picture' => '奖品图片',
			'prize_pictureFile' => '奖品图片文件',
			'prize_genner_time' => '产生时间',
			'prize_level_name' => '等级名称',
			'prize_count_all' => '总共数量',
			'prize_count_now' => '当前剩余数量',
			'disable' => '禁用',
			'isdelete' => '删除',
			'lucky_act_id' => '抽奖活动',
			'create_username' => 'Create Username',
			'create_userid' => 'Create Userid',
			'create_date' => 'Create Date',
			'update_username' => 'Update Username',
			'update_userid' => 'Update Userid',
			'update_date' => 'Update Date',
            'type' => '奖品类型',
            'delay' => '是否延时',
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
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('prize_des',$this->prize_des,true);
		$criteria->compare('prize_level',$this->prize_level);
		$criteria->compare('prize_picture',$this->prize_picture,true);
		$criteria->compare('prize_genner_time',$this->prize_genner_time);
		$criteria->compare('prize_level_name',$this->prize_level_name,true);
		$criteria->compare('prize_count_all',$this->prize_count_all);
		$criteria->compare('prize_count_now',$this->prize_count_now);
		$criteria->compare('disable',$this->disable);
		//$criteria->compare('isdelete',$this->isdelete);
		$criteria->compare('isdelete',0);
		if(empty($this->lucky_act_id)){
			$criteria->compare('lucky_act_id',Item::LUCKY_ACT_ID);
		}else{
			$criteria->compare('lucky_act_id',$this->lucky_act_id);
		}		
		$criteria->compare('create_username',$this->create_username,true);
		$criteria->compare('create_userid',$this->create_userid);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('update_username',$this->update_username,true);
		$criteria->compare('update_userid',$this->update_userid);
		$criteria->compare('update_date',$this->update_date,true);
                $criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyPrize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeValidate()
	{
		if (empty($this->prize_picture) && !empty($this->prize_pictureFile))
			$this->prize_picture = '';
		return parent::beforeValidate();
	}
	
	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
// 		var_dump($this->prize_pictureFile);
// 		exit();
		if (!empty($this->prize_pictureFile)) {
			$this->prize_picture = Yii::app()->ajaxUploadImage->moveSave($this->prize_pictureFile, $this->prize_picture);
		}
		return parent::beforeSave();
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
	
	public function getPrizePictureUrl()
	{
		return Yii::app()->ajaxUploadImage->getUrl($this->prize_picture, '/common/images/nopic-map.jpg');
	}
	public function getPrizePictureUrlNoDefault()
	{
		return Yii::app()->ajaxUploadImage->getUrl($this->prize_picture, ' ');
	}
	
	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->isdelete=1;
		$model->save();
	}
	
	
	/**
	 * 查的某活动下的奖项列表
	 * @param $actid  活动id
	 * @param $all  true查看所有 false 得到剩余数量大于0的。
	 */
	public function getPrizeList($actid,$all=false, $prizeLevel=''){
		$criteria = new CDbCriteria;
		$criteria->addCondition("disable=0");
		$criteria->addCondition("isdelete=0");
		if(!$all){
			$criteria->addCondition("prize_count_now>0");  //剩余数量大于0的
		}
        if ($prizeLevel) {
            $criteria->addCondition("prize_level=".$prizeLevel);
        }

		$criteria->addCondition("lucky_act_id=:lucky_act_id");
		$criteria->params=array(":lucky_act_id"=>$actid);
		return $this->findAll($criteria);
		
	}
	
	
}
