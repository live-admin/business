<?php

/**
 * This is the model class for table "lucky_layout".
 *
 * The followings are the available columns in table 'lucky_layout':
 * @property integer $id
 * @property integer $lucky_act_id
 * @property integer $layout_index
 * @property integer $prize_id
 */
class LuckyLayout extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_layout';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lucky_act_id, layout_index, prize_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lucky_act_id, layout_index, prize_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'luckyAct' => array(self::BELONGS_TO, 'LuckyActivity', 'lucky_act_id'),
			'prize' => array(self::BELONGS_TO, 'LuckyPrize', 'prize_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lucky_act_id' => '活动id',
			'layout_index' => '布局位置值',
			'prize_id' => '奖项id',
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
		$criteria->compare('lucky_act_id',$this->lucky_act_id);
		$criteria->compare('layout_index',$this->layout_index);
		$criteria->compare('prize_id',$this->prize_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyLayout the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 根据数组，添加布局layout.
	 * @param 活动id  $actId 
	 * @param 布局数组 $arr  {1=>1,2=>1,3=>1,4=>2,5=>5} 下标=>奖项id
	 */
	public function saveLayoutArray($actId,$arr){
		if($actId==1){return;}   //第一个活动，是用硬代码的。为避免误操作，过滤掉。
		if(is_array($arr)){
			//删除掉已有的
			$this->deleteAll("lucky_act_id=$actId");
			
			$prizeids=array();
			foreach ($arr as $key=>$value){  //保存
				if($value){
					$m=new LuckyLayout();
					$m->lucky_act_id=$actId;
					$m->layout_index=$key;
					$m->prize_id=$value;
					$m->save();
					$prizeids[]=$value;
				}
			}
			//讲没有进行布局的奖项，都禁用
			$prizeIdsStr=implode(',', $prizeids);
			LuckyPrize::model()->updateAll(array("disable"=>1,),"lucky_act_id=$actId and id not in ($prizeIdsStr)");
		}
	}

	/**
	 * 获得奖项布局 布局值和奖项值的对应
	 * @param 活动idd $actId
	 */
	public static function getLayoutIndexPrize($actId){
		$connection=Yii::app()->db;
		$sql="SELECT layout_index,prize_id FROM lucky_layout WHERE lucky_act_id=".$actId;
		$command=$connection->createCommand($sql);
		$rows=$command->queryAll();
		return $rows;
	}

	
	/**
	 * 根据数组，添加布局角度分配layout.
	 * @param 活动id  $actId
	 * @param 布局数组 $arr array(array("id"=>1,"anglestart"=>20,"angleend"=>60),array("id"=>2...)...)
	 */
	public function saveLayoutCircleArray($actId,$arr){
		if($actId==1){return;}   //第一个活动，是用硬代码的。为避免误操作，过滤掉。
		if(is_array($arr)){
			foreach ($arr as $key=>$value){
				$prize=LuckyPrize::model()->findByPk($value['id']);
				if($prize){
					$angle_start_str=$value['anglestart']; //将形如 "20,150,270"格式的拆开，当个数字化后，再组合
					$angle_end_str=$value['angleend'];
					$angle_start_arr=explode(",", $angle_start_str);
					$angle_end_arr=explode(",", $angle_end_str);
					if(count($angle_start_arr)==count($angle_end_arr)){
						
						$angle_start_arr_int=array();
						foreach ($angle_start_arr as $v){
							$angle_start_arr_int[]=intval($v);
						}
						$angle_start=implode(",", $angle_start_arr_int);
						$prize->angle_start=$angle_start;
						//
						//将形如 "20,150,270"格式的拆开，当个数字化后，再组合
							
						$angle_end_arr_int=array();
						foreach ($angle_end_arr as $v){
							$angle_end_arr_int[]=intval($v);
						}
						$angle_end=implode(",", $angle_end_arr_int);
						$prize->angle_end=$angle_end;
						//$prize->save();
						//$erros=$prize->getErrors();
						$prize->update();
					}
					
				}
				
			}
		}
	}
	
	/**
	 * 获得奖项布局 布局值和奖项值的对应
	 * @param 活动idd $actId
	 */
	public static function getLayoutCirclePrize($actId){
		$connection=Yii::app()->db;
		$sql="SELECT id,prize_name,angle_start,angle_end FROM lucky_prize WHERE isdelete=0 and disable=0 and lucky_act_id=".$actId;
		$command=$connection->createCommand($sql);
		$rows=$command->queryAll();
		return $rows;
	}
}
