<?php
/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/7/16
 * Time: 10:27 上午
 * 3.0邻里点赞 TODO:kakatool
 */

class FeedLike extends CActiveRecord{


	public $modelName = '邻里点赞';

	public $timeline_id;
	public $customer_id;
	public $customer_name;
	public $creationtime;

	public $timeline_link;
	public $feed_type;

    public $customer_mobile;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'feed_like';
	}

	public function rules(){

		return array(
			array('timeline_id,customer_id', 'required', 'on' => 'create'),
			array('timeline_id, customer_id,creationtime', 'numerical', 'integerOnly' => true),
			array('customer_name', 'length', 'max' => 200),
			array('timeline_id,customer_id, customer_name,creationtime', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'timeline' => array(self::BELONGS_TO, 'FeedTimeline', 'timeline_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'timeline_id' => '时间轴id',
			'customer_id' => '点赞用户ID',
			'customer_name' => '点赞用户名',
			'creationtime' => '点赞时间',
			'timeline_link' => '连接',
			'customer_mobile' => '点赞用户手机号',
		);
	}

	public function getCreationTime()
	{
		if ($this->creationtime) {
			return date('Y-m-d H:i:s', $this->creationtime);
		} else {
			return '';
		}
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->alias = 'a';
		$criteria->select = 'a.id, a.customer_id, a.customer_name, a.creationtime, a.timeline_id, t.feed_type AS timeline_link, m.mobile as customer_mobile';
        $criteria->join = 'LEFT JOIN feed_timeline AS t ON(a.timeline_id = t.id)
            LEFT JOIN customer AS m ON(a.customer_id = m.id)';
		//$criteria->compare('a.is_deleted', 0);
		$criteria->compare('a.timeline_id', $this->timeline_id);
		$criteria->order = 'a.creationtime desc';
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getFeedTimelineLink()
	{
		return $this->timeline_link == 1
			? CHtml::link('查看帖子', '/feedMoment/' . $this->timeline_id)
			: CHtml::link('查看帖子', '/feedActivity/' . $this->timeline_id);
	}
} 
