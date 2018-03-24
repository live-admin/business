<?php

/**
 * 3.0 可配置资源 //TODO:kakatool
 */
class FeedActivity extends CActiveRecord
{

	const FEED_TYPE_ACTIVITY = 2;
	const FEED_TYPE_MOMENT = 1;

	public $modelName = '邻里活动';
	public $activity_type_id;
	public $activity_type_name;
	public $activity_type_photo;
	public $start_time;
	public $stop_time;

	public $community_id;
	public $from_date;
	public $to_date;
	public $customer_name;
	public $community_name;
	public $comment;
	public $feed_type;

    public $customer_mobile;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'feed_activity';
	}

	public function rules()
	{
		return array(
			array('customer_id', 'required', 'on' => 'create'),
			array('activity_type_id', 'required', 'on' => 'create'),
			array('activity_type_id', 'checkActivityType', 'on' => 'create'),
			array('start_time', 'checkStartTime', 'on' => 'create'),
			array('stop_time', 'checkStopTime', 'on' => 'create'),
			array('customer_id, customer_id, activity_type_id, creationtime', 'numerical', 'integerOnly' => true),
			array('activity_type_name, activity_type_photo, content, location', 'length', 'max' => 500),
			array('id,customer_id, activity_type_id, activity_type_name, activity_type_photo, content, location, creationtime, start_time, stop_time, community_id, customer_name, customer_mobile', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => '用户ID',
			'activity_type_id' => '类型ID',
			'activity_type_name' => '活动类型',
			'activity_type_photo' => '类型图片',
			'content' => '简介',
			'start_time' => '活动时间',
			'stop_time' => '结束时间',
			'location' => '地点',
			'creationtime' => '创建时间',
			'customer_name' => '用户名',
			'community_name' => '小区',
			'community_id' => '小区',
			'comment' => '评论',
			'feed_type' => '类型',
			'customer_mobile' => '用户手机号',
		);
	}

	public function checkActivityType($attribute, $param)
	{
		$type = FeedActivityType::model()->find('id = :id', array(':id' => $this->activity_type_id));
		if (!$type) {
			$this->addError($attribute, '活动类型不存在。');
			return false;
		}

		//$this->activity_type_name = $type->name;
		//$this->activity_type_photo = $type->photo;
		$this->setAttributes(array(
			'activity_type_name' => $type->name,
			'activity_type_photo' => $type->photo,
		));
	}

	public function checkStartTime($attribute, $param)
	{
		if ($this->start_time && $this->start_time < strtotime('today')) {
			$this->addError($attribute, '活动开始时间不能早于今天。');
		}
	}

	public function checkStopTime($attribute, $param)
	{
		if ($this->stop_time && $this->stop_time < strtotime('today')) {
			$this->addError($attribute, '活动结束时间不能早于今天。');
		}
		if ($this->start_time && $this->stop_time
			&& $this->stop_time < $this->start_time
		) {
			$this->addError($attribute, '活动结束时间不能早于开始时间。');
		}
	}

	public function beforeSave()
	{
		if ($this->start_time) {
			//$this->start_time = date('Y-m-d', $this->start_time);
			$this->setAttributes(array(
				'start_time' => date('Y-m-d H:i:s', $this->start_time),
			));
		}
		if ($this->stop_time) {
			//$this->stop_time = date('Y-m-d', $this->stop_time);
			$this->setAttributes(array(
				'stop_time' => date('Y-m-d H:i:s', $this->stop_time),
			));
		}

		return true;
	}

	protected function getFromDatetime()
	{
		if ($this->from_date) {
			$datetime = strtotime($this->from_date);
			if ($datetime > 0) {
				return strtotime($this->from_date . ' 00:00:00');
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	protected function getToDatetime()
	{
		if ($this->to_date) {
			$datetime = strtotime($this->to_date);
			if ($datetime > 0) {
				return strtotime($this->to_date . ' 23:59:59');
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	public function getCreationTime()
	{
		if ($this->creationtime) {
			return date('Y-m-d H:i:s', $this->creationtime);
		} else {
			return '';
		}
	}

	public function getFeedType()
	{
		return $this->feed_type == self::FEED_TYPE_ACTIVITY ? '活动' : '动态';
	}

    public function getLimitedContent() 
    {
        if ($this->content && strlen($this->content) > 30) {
            return substr($this->content, 0, 30) . chr(0) . '...';
        }

        return $this->content;
    }

    public function getLimitedLocation() 
    {
        if ($this->content && strlen($this->location) > 30) {
            return substr($this->location, 0, 30) . chr(0) . '...';
        }

        return $this->content;
    }

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->alias = 'a';
		$criteria->select = 't.id, a.start_time, a.stop_time, a.location, a.content, t.feed_type, t.creationtime, t.customer_id, t.customer_name, c.`name` AS community_name, a.activity_type_id, a.activity_type_name, m.mobile AS customer_mobile';
		$criteria->join = 'LEFT JOIN feed_timeline AS t ON(a.id = t.feed_third_id AND t.feed_type = 2)
			LEFT JOIN community AS c ON(t.community_id = c.id)
            LEFT JOIN customer AS m ON(t.customer_id = m.id)';
		$criteria->compare('a.content', $this->content, true);
		$criteria->compare('a.activity_type_name', $this->activity_type_name, true);
		$criteria->compare('t.customer_id', $this->customer_id);
		$criteria->compare('m.mobile', $this->customer_mobile, true);
        if (is_numeric($this->community_id)) {
            $criteria->compare('t.community_id', $this->community_id);
        } else {
            if ($this->community_id == '全部小区') {
                $this->community_id = '';
            }
            $criteria->compare('c.`name`', $this->community_id, true);
        }
		$criteria->compare('t.is_deleted', '0');
		$fromDatime = $this->getFromDatetime();
		$toDatetime = $this->getToDatetime();
		if ($fromDatime && $toDatetime) {
			$criteria->addBetweenCondition('t.creationtime', $fromDatime, $toDatetime);
		} else {
			if ($fromDatime) {
				$criteria->addCondition('t.creationtime >= ' . $fromDatime);
			}

			if ($toDatetime) {
				$criteria->addCondition('t.creationtime <= ' . $toDatetime);
			}
		}

		$criteria->order = 't.creationtime desc';
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}
