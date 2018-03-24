<?php

/**
 * 3.0 可配置资源 //TODO:kakatool
 */
class HomeConfigAdCommunityRelation extends CActiveRecord
{


	public $modelName = '资源';


	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_ad_community_relation';
	}

	public function rules()
	{
		return array(
			array('ad_id, community_id', 'required'),
			array('ad_id, community_id, update_time', 'numerical', 'integerOnly' => true),
			array('ad_id, community_id', 'safe', 'on' => 'search'),
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
		//$this->deleteAllByAttributes(array('ad_id' => $id));
		return $this->saveAllCommunityRelation($id, $communityIDs, $isDefault);
	}

	public function saveAllCommunityRelation($adID, $communityIDs = array(), $isDefault = false)
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
			$this->deleteAllByAttributes(array('ad_id' => $adID));

			$cmdNewRelation = $connection->createCommand('INSERT INTO home_config_ad_community_relation(ad_id, community_id, update_time) VALUES(:ad_id, :community_id, :update_time)');

			foreach ($communityIDs as $communityID) {
				$cmdNewRelation->bindParam(':ad_id', $adID, PDO::PARAM_INT);
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