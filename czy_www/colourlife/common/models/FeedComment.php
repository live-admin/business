<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/7/16
 * Time: 10:27 上午
 * 3.0邻里留言 TODO:kakatool
 */
class FeedComment extends CActiveRecord
{

	public $modelName = '邻里留言';

	public $id;
	public $timeline_id;
	public $content;
	public $from_customer_id;
	public $from_customer_name;
	public $to_customer_id;
	public $to_customer_name;
	public $audit;
	public $is_deleted;
	public $creationtime;
	public $modifiedtime;

	public $timeline_link;
	public $feed_type;

    public $customer_mobile;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'feed_comment';
	}


	public function rules()
	{
		return array(
			array('timeline_id', 'required', 'on' => 'create'),
			array('from_customer_id,from_customer_name,creationtime,modifiedtime', 'required', 'on' => 'create'),
			array('id, from_customer_id, to_customer_id, audit,is_deleted,creationtime,modifiedtime', 'numerical', 'integerOnly' => true),
			array('content', 'length', 'max' => 500),
			array('from_customer_name,to_customer_name', 'length', 'max' => 200),
			array('id,timeline_id, from_customer_id, from_customer_name, to_customer_id, to_customer_name, content,audit,is_deleted,creationtime, modifeidtime, customer_mobile', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'timeline' => array(self::BELONGS_TO, 'FeedTimeline', 'timeline_id'),
			'fromCustomer' => array(self::BELONGS_TO, 'Customer', 'from_customer_id'),
			'toCustomer' => array(self::BELONGS_TO, 'Customer', 'to_customer_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'timeline_id' => '时间轴id',
			'content' => '内容',
			'from_customer_id' => '留言用户ID',
			'from_customer_name' => '留言用户名',
			'to_customer_id' => '被回复用户ID',
			'to_customer_name' => '被回复用户名',
			'audit' => '是否审核',
			'is_deleted' => '是否删除',
			'creationtime' => '创建时间',
			'modifiedtime' => '修改时间',
			'customer_name' => '用户名',
			'timeline_link' => '连接',
			'customer_mobile' => '用户手机号',
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
		$criteria->select = 'a.id, a.content, a.from_customer_id, a.from_customer_name, a.to_customer_id, a.to_customer_name, a.creationtime, a.timeline_id, t.feed_type AS timeline_link, m.mobile as customer_mobile';
        $criteria->join = 'LEFT JOIN feed_timeline AS t ON(a.timeline_id = t.id)
            LEFT JOIN customer AS m ON(a.from_customer_id = m.id)';
		$criteria->compare('a.is_deleted', 0);
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
