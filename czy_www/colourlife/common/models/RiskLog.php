<?php 
class RiskLog extends CActiveRecord{
	
	/**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RiskLog the static model class
     */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 返回表名
	 * @return string
	 */
	public function tableName()
	{
		return 'risk_log';
	}
	
	/**
	 * 
	 * @return multitype:
	 */
	public function relations()
	{
		return array();
	}
	
	public function rules()
	{
		return array(
				array('username, device_info,event_id,risk_result', 'required'),
				array('username', 'length', 'max' => 15),
				array('event_id', 'length', 'max' => 20),
				array('id, username, device_info,event_id,risk_result', 'safe', 'on' => 'search'),
		);
	}
	
	/**
	 * 表属性
	 * @return multitype:string
	 */
	public function attributeLabels()
	{
		return array(
				'id' => '表ID',
				'username' => '用户名',
				'device_info' => '设备信息',
				'event_id' => '事件类型',
				'risk_result' => '风险结果',
				'addtime' => '添加时间'
		);
	}
}