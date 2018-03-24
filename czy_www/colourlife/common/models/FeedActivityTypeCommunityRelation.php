<?php

/**
 * 3.0 可配置资源 //TODO:kakatool
 */
class FeedActivityTypeCommunityRelation extends CActiveRecord
{


	public $modelName = '邻里活动类型小区关联';


	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'feed_activity_type_community_relation';
	}

	public function rules()
	{
		return array(
			array('activity_type_id, community_id', 'required'),
			array('activity_type_id, community_id, creationtime', 'numerical', 'integerOnly' => true),
			array('activity_type_id, community_id', 'safe', 'on' => 'search'),
		);
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => NULL,
				'updateAttribute' => 'creationtime',
				'setUpdateOnCreate' => true,
			),
		);
	}

	public function updateCommunityRelation($id, $communityIDs, $isDefault = false)
	{
		//删除所有的相关记录
		//$this->deleteAllByAttributes(array('activity_type_id' => $id));
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
			$this->deleteAllByAttributes(array('activity_type_id' => $targetID));

			$cmdNewRelation = $connection->createCommand('INSERT INTO feed_activity_type_community_relation(activity_type_id, community_id, creationtime) VALUES(:activity_type_id, :community_id, :creationtime)');

			foreach ($communityIDs as $communityID) {
				$cmdNewRelation->bindParam(':activity_type_id', $targetID, PDO::PARAM_INT);
				$cmdNewRelation->bindParam(':community_id', $communityID, PDO::PARAM_INT);
				$cmdNewRelation->bindParam(':creationtime', $dateline, PDO::PARAM_INT);
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