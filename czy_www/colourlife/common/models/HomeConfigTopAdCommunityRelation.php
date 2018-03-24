<?php

/**
 * This is the model class for table "home_config_top_ad_community_relation".
 *
 * The followings are the available columns in table 'home_config_top_ad_community_relation':
 * @property integer $top_ad_id
 * @property integer $community_id
 * @property integer $last_time
 */
class HomeConfigTopAdCommunityRelation extends CActiveRecord
{
	public $modelName = '资源';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'home_config_top_ad_community_relation';
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => NULL,
				'updateAttribute' => 'last_time',
				'setUpdateOnCreate' => true,
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('top_ad_id', 'required'),
			array('top_ad_id, community_id, last_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('top_ad_id, community_id, last_time', 'safe', 'on'=>'search'),
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
			'top_ad_id' => 'Top Ad',
			'community_id' => 'Community',
			'last_time' => 'Last Time',
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

		$criteria->compare('top_ad_id',$this->top_ad_id);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('last_time',$this->last_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HomeConfigTopAdCommunityRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
			$this->deleteAllByAttributes(array('top_ad_id' => $adID));

			$cmdNewRelation = $connection->createCommand('INSERT INTO home_config_top_ad_community_relation(top_ad_id, community_id, last_time) VALUES(:top_ad_id, :community_id, :last_time)');

			foreach ($communityIDs as $communityID) {
				$cmdNewRelation->bindParam(':top_ad_id', $adID, PDO::PARAM_INT);
				$cmdNewRelation->bindParam(':community_id', $communityID, PDO::PARAM_INT);
				$cmdNewRelation->bindParam(':last_time', $dateline, PDO::PARAM_INT);
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
