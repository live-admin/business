<?php

/**
 * This is the model class for table "prize_list".
 *
 * The followings are the available columns in table 'prize_list':
 * @property integer $id
 * @property string $grade_name
 * @property string $prize_name
 * @property string $prize_pic_url
 * @property integer $num
 * @property integer $round
 * @property integer $type
 * @property integer $state
 * @property integer $create_time
 * @property integer $update_time
 */
class PrizeList extends CActiveRecord
{
    public $modelName = '奖项';
    public $typeFile; //奖品图片
    public $type_arr=array(0=>'否',1=>'是');//是否补发
    public $state_arr=array(0=>'禁用',1=>'启用');//是否启用(0禁用，1启用)
//    public $dengjiang_arr=array(0=>'特等奖',1=>'一等奖',2=>'二等奖',3=>'三等奖',4=>'四等奖',5=>'五等奖',6=>'幸运奖',88=>'特殊奖');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prize_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grade_name', 'required'),
			array('num,dengjiang,round, type, state,sort,create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('grade_name, prize_name', 'length', 'max'=>64),
			array('prize_pic_url', 'length', 'max'=>500),
            array('typeFile', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, grade_name, prize_name, prize_pic_url, num,dengjiang,round, type, state,sort,create_time, update_time,typeFile', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '表ID',
			'grade_name' => '奖项等级',
			'prize_name' => '奖品名称',
			'prize_pic_url' => '奖品图片',
			'num' => '人数',
            'dengjiang'=>'几等奖',
			'round' => '第几轮',
			'type' => '是否补发',
			'state' => '是否启用',
            'sort' => '排序',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
            'typeFile'=>'奖品图片',
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
		$criteria->compare('grade_name',$this->grade_name,true);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('prize_pic_url',$this->prize_pic_url,true);
		$criteria->compare('num',$this->num);
        $criteria->compare('dengjiang',$this->dengjiang);
		$criteria->compare('round',$this->round);
		$criteria->compare('type',$this->type);
		$criteria->compare('state',$this->state);
        $criteria->compare('sort',$this->sort);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>30,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PrizeList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
     * @version 显示已有的图片或者默认的图片
     */
    public function getTypeImageUrl(){
        return Yii::app()->imageFile->getUrl($this->prize_pic_url, '/common/images/nopic-map.jpg');
    }
    /**
     * @version 处理添加或者修改时候的图片
     */
    protected function beforeSave(){
        if (!empty($this->typeFile)) {
            $this->prize_pic_url = Yii::app()->ajaxUploadImage->moveSave($this->typeFile, $this->prize_pic_url);
        }
        return parent::beforeSave();
    }
    /*
     * @version 是否补发
     */
    public function getType()
    {
        return $this->type_arr[$this->type];
    }
    /*
     * @version 几等奖
     */
//    public function getDengJiang()
//    {
//        return $this->dengjiang_arr[$this->dengjiang];
//    }
    /*
     * @version 是否启用
     */
    public function getStateName($state = '')
    {
        $return = '';
        switch ($state) {
            case '':
                $return = "";
                break;
            case 0:
                $return = '<span class="label label-error">'.$this->state_arr[0].'</span>';
                break;
            case 1:
                $return = '<span class="label label-success">'.$this->state_arr[1].'</span>';
                break;
        }
        return $return;
    }
//    public function getState()
//    {
//        return $this->state_arr[$this->state];
//    }
    /*
     * @version 启用功能
     */
    public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
        $model->state=1;
        $model->save();
	}
    /*
     * @version 禁用功能
     */
    public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
        $model->state=0;
        $model->save();
	}
	
	/**
	 * 通过奖项ID获取中奖名单
	 * @param unknown $id
	 * @return multitype:|unknown
	 */
	public function getWinningMem($id){
		$name=array();
		if (empty($id)){
			return $name;
		}
		$winning=WinningLog::model()->findAll("prize_list_id=:id",array(':id'=>$id));
		if (empty($winning)){
			return $name;
		}
		foreach ($winning as $v){
			$tmp=array();
			$lottery=LotteryMember::model()->findByPk($v->lottery_member_id);
			if (empty($lottery)){
				continue;
			}
			$tmp['name']=$lottery->uname;
			$tmp['username']=$lottery->username;
			$tmp['type']=$lottery->type;
			$tmp['mobile']=$lottery->mobile;
			$name[]=$tmp;
		}
		return $name;
	}
}
