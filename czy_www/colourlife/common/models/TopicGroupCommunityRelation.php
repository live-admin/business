<?php

/**
 * This is the model class for table "topic_group_community_relation".
 *
 * The followings are the available columns in table 'topic_group_community_relation':
 * @property string $id
 * @property integer $group_id
 * @property integer $community_id
 * @property string $create_time
 */
class TopicGroupCommunityRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'topic_group_community_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_id, community_id', 'required'),
			array('group_id, community_id', 'numerical', 'integerOnly'=>true),
			array('create_time', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, group_id, community_id, create_time', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'group_id' => 'Group',
			'community_id' => 'Community',
			'create_time' => 'Create Time',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TopicGroupCommunityRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function saveAll($group_id, $communityList)
	{
		if (empty($group_id)) {
			return false;
		}
		if (empty($communityList)) { //如果传入的小区为空。则不需要添加关联记录。直接返回成功
			return true;
		} else {
			$sql = "INSERT INTO topic_group_community_relation (group_id,community_id,create_time) VALUES ";
			$sqlArr = array();
			$i = 0;
			$max = count($communityList);
			$create_time = time();
			$return = true;
			foreach ($communityList as $key => $value) {
				$i++;
				//拼接sql语句
				array_push($sqlArr, "({$group_id},{$value},{$create_time})");
				//如果有了100条，或者已全部拼接完
				if ($i % 100 == 0 || $i >= $max) {
					//执行插入语句
					if (Yii::app()->getDb()->getPdoInstance()->query($sql . trim(implode(',', $sqlArr), ','))) {
						//成功则情况数组,继续下一次插入
						$sqlArr = array();
					} else {
						//任何一次失败都结束循环
						$return = false;
						break;
					}
				}
			}
			return $return;
		}
	}

	public function  updateCommunityRelation($group_id, $communityList)
	{
		//删除所有的相关记录
		$this->deleteAllByAttributes(array('group_id' => $group_id));
		return $this->saveAll($group_id, $communityList);

	}
}
