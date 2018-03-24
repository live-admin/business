<?php
/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/11/16
 * Time: 9:56 下午
 */

class HomeConfigActivityCommunityRelation extends CActiveRecord{

	public $modelName = '活动小区关联表';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_activity_community_relation';
	}

	public function rules()
	{
		return array(
			array('activity_category_id, community_id', 'required'),
			array('activity_category_id, community_id, update_time', 'numerical', 'integerOnly' => true),
			array('activity_category_id, community_id', 'safe', 'on' => 'search'),
		);
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => NULL,
				'updateAttribute' => 'update_time',
				'setUpdateOnCreate' => true,
			),
		);
	}

	public function updateCommunityRelation($id, $communityIDs, $isDefault = false)
	{
		//删除所有的相关记录
		//$this->deleteAllByAttributes(array('activity_category_id' => $id));
		return $this->saveAllCommunityRelation($id, $communityIDs, $isDefault);
	}

	public function saveAllCommunityRelation($targetID, $communityIDs = array(), $isDefault = false)
	{
		if ($isDefault == true) {
			array_unshift($communityIDs, '0');
		}
		$communityIDs = array_unique($communityIDs);

		$connection = Yii::app()->db;
		$dateline = time();
		$transaction = $connection->beginTransaction();
		try {
			//删除所有的相关记录
			$this->deleteAllByAttributes(array('activity_category_id' => $targetID));

			$cmdNewRelation = $connection->createCommand('INSERT INTO home_config_activity_community_relation(activity_category_id, community_id, update_time) VALUES(:activity_category_id, :community_id, :update_time)');

			foreach ($communityIDs as $communityID) {
				$cmdNewRelation->bindParam(':activity_category_id', $targetID, PDO::PARAM_INT);
				$cmdNewRelation->bindParam(':community_id', $communityID, PDO::PARAM_INT);
				$cmdNewRelation->bindParam(':update_time', $dateline, PDO::PARAM_INT);
				$cmdNewRelation->execute();
			}

			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();
			return false;
		}


		return true;
	}
} 