<?php
/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/14/16
 * Time: 6:07 下午
 */

class HomeConfigMainFuncTagRelation extends CActiveRecord{

	public $modelName = '主功能标签关联表';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_mainfunc_tag_relation';
	}

	public function rules()
	{
		return array(
			array('more_function_id, tag_id', 'required'),
			array('more_function_id, tag_id, update_time', 'numerical', 'integerOnly' => true),
			array('more_function_id, tag_id', 'safe', 'on' => 'search'),
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

	public function updateCommunityRelation($id, $tagIDs)
	{
		//删除所有的相关记录
		//$this->deleteAllByAttributes(array('activity_category_id' => $id));
		return $this->saveAllCommunityRelation($id, $tagIDs);
	}

	public function saveAllCommunityRelation($targetID, $tagIDs = array())
	{
		$tagIDs = array_unique($tagIDs);
		if(!$tagIDs){
			return false;
		}

		$connection = Yii::app()->db;
		$dateline = time();
		$transaction = $connection->beginTransaction();
		try {
			//删除所有的相关记录
			$this->deleteAllByAttributes(array('more_function_id' => $targetID));

			$cmdNewRelation = $connection->createCommand('INSERT INTO ' . $this->tableName() . '(more_function_id, tag_id, update_time) VALUES(:more_function_id, :tag_id, :update_time)');

			foreach ($tagIDs as $tagID) {
				$cmdNewRelation->bindParam(':more_function_id', $targetID, PDO::PARAM_INT);
				$cmdNewRelation->bindParam(':tag_id', $tagID, PDO::PARAM_INT);
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