<?php

/**
 * This is the model class for table "expire_remind_config".
 *
 * The followings are the available columns in table 'expire_remind_config':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $time
 * @property integer $days
 * @property integer $type
 * @property integer $state
 * @property integer $create_time
 * @property integer $update_time
 */
class ExpireRemindConfig extends CActiveRecord
{
	public $modelName = '彩富订单到期配置';
	public $state_arr=array(0=>'已禁用',1=>'已启用');//是否启用(0禁用，1启用)
	public $type_arr = array (   //1到期天数，2执行时间，3业主短信，4客户经理短信,5业主推送，6客户经理推送
			1 => '到期天数',
			2 => '执行时间',
			3 => '业主短信',
			4 => '客户经理短信',
			5 => '业主推送',
			6 => '客户经理推送',
			7 => '启动脚本'
	);
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expire_remind_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('days, type, state, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>64),
			array('time', 'length', 'max'=>64),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, content, time, days, type, state, create_time, update_time', 'safe', 'on'=>'search'),
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
			'title' => '标题',
			'content' => '内容',
			'time' => '执行时间',
			'days' => '离到期天数',
			'type' => '配置类型',
			'state' => '是否启用',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('days',$this->days);
		$criteria->compare('type',$this->type);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExpireRemindConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取启用名称
	 * @param string $state
	 * @return string
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
	
	/**
	 * 获取类型
	 */
	public function getType()
	{
		return $this->type_arr[$this->type];
	}
	
	/*
	 * @version 启用功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=1;
		$model->update_time=time();
		if ($model->save()){
			$all=$this->findAll('state=1 and type=:type order by update_time desc',array(':type'=>$model->type));
			if (!empty($all)&&count($all)>1){
				unset($all[0]);
				foreach ($all as $val){
					$this->updateByPk($val->id, array('state'=>0));
				}
			}
		}
	}
	/*
	 * @version 禁用功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=0;
		$model->update_time=time();
		$model->save();
	}
	
	/**
	 * 获取到期提醒的配置内容
	 *
	 * @return array
	 */
	public function getExpireRemindConfig() {
		// 定义默认配置
		$data = array (
				'1' => 8,
				'2' => strtotime ( date ( "Y-m-d" ) . '08:50:00' ),
				'3' => '【彩之云】尊敬的彩富人生业主：您的{order}订单将在{date}到期，提前续投尊享至尊服务，登录彩之云APP，续投一键搞定。退订回T。', // 业主的短信内容
				'4' => '【彩之云】尊敬的彩富人生推荐人：您所推荐的业主{userName}参加的{order}订单将于{date}到期，建议您及时上门拜访，鼓励业主进行续投操作。退订回T。', // 客户经理的短信内容
				'5' => array (
						'title' => '尊敬的彩富人生业主：',
						'content' => '您的{order}订单将在{date}到期，提前续投尊享至尊服务，登录彩之云APP，续投一键搞定。' 
				), // 业主的短信内容
				'6' => array (
						'title' => '尊敬的彩富人生推荐人：',
						'content' => '您所推荐的业主{userName}参加的{order}订单将于{date}到期，建议您及时上门拜访，鼓励业主进行续投操作。' 
				), // 客户经理的短信内容
				'7' => 'off' 
		);
		$config = ExpireRemindConfig::model ()->findAll ( "state=1" );
		if (! empty ( $config )) {
			foreach ( $config as $val ) {
				if ($val->type == 1) {
					$data [1] = $val->days + 1;
				} elseif ($val->type == 2) {
					$data [2] = strtotime($val->time);
				} elseif ($val->type == 3 || $val->type == 4) {
					$data [$val->type] = $val->content;
				} elseif ($val->type == 5 || $val->type == 6) {
					$data [$val->type] = array (
							'title' => $val->title,
							'content' => $val->content 
					);
				} elseif ($val->type == 7 && $val->state == 1) {
					$data [7] = 'on';
				}
			}
		}
		return $data;
	}
}
