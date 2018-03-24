<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/7/16
 * Time: 10:32 上午
 * 3.0邻里时刻 TODO:kakatool
 */
class FeedMoment extends CActiveRecord
{

	const FEED_TYPE_ACTIVITY = 2;
	const FEED_TYPE_MOMENT = 1;

	public $modelName = '邻里内容管理';

	public $id;
	public $customer_id;
	public $content;
	public $photo;
	public $creationtime;

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
		return 'feed_moment';
	}

	public function rules()
	{
		return array(
			array('customer_id', 'required', 'on' => 'create'),
			array('id, customer_id, creationtime', 'numerical', 'integerOnly' => true),
			array('content', 'length', 'max' => 500),
			array('photo', 'length', 'max' => 1000),
			array('id,customer_id,content,photo,creationtime, start_time, stop_time, community_id, customer_name, customer_mobile', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'timeline' => array(self::HAS_ONE, 'FeedTimeline', 'feed_third_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => '用户ID',
			'content' => '内容',
			'photo' => '图片',
			'creationtime' => '创建时间',
			'customer_name' => '用户名',
			'community_name' => '小区',
			'community_id' => '小区',
			'comment' => '评论',
			'feed_type' => '类型',
			'customer_mobile' => '用户手机号',
		);
	}


	/**
	 * 获取图片array，数据库存储json字符串，转换为数组
	 * @return mixed
	 */
	public function getPhotoArray()
	{

		if ($this->photo) {

			$res = new PublicFunV30();
			$photoArray = array();
			$data = CJSON::decode($this->photo);

			if ($data && is_array($data)) {

				foreach ($data as $item) {
					$photoArray[] = $res->setAbleUploadImg($item);
				}
			}

			return $photoArray;
		}

		return array();

	}

	public function getPhotoHtml($limit = 3, $width = 60)
	{
		$photos = $this->getPhotoArray();
		$html = '';
		if ($photos) {
			if ($limit > 0) {
				$i = 0;
				do {
					$photo = array_shift($photos);
					$html .= CHtml::image($photo, 'title', array('width' => $width, 'height' => $width)) . '&nbsp;';
					$i++;
				} while ($photos && $i < 3);
			} else {
				do {
					$photo = array_shift($photos);
					$html .= CHtml::image($photo, 'title', array('width' => $width, 'height' => $width)) . '&nbsp;';
				} while ($photos);
			}

		}

		return $html;
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

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->alias = 'a';
		$criteria->select = 't.id, a.content, a.photo, t.creationtime, t.customer_id, t.feed_type, t.customer_name, c.`name` AS community_name, m.mobile AS customer_mobile';
		$criteria->join = 'LEFT JOIN feed_timeline AS t ON(a.id = t.feed_third_id AND t.feed_type = 1)
            LEFT JOIN community AS c ON(t.community_id = c.id)
            LEFT JOIN customer AS m ON(t.customer_id = m.id)';
		$criteria->compare('a.content', $this->content, true);
		$criteria->compare('t.customer_id', $this->customer_id);
		$criteria->compare('m.mobile', $this->customer_mobile, true);
        if (is_numeric($this->community_id)) {
            $criteria->compare('t.community_id', $this->community_id);
        } else {
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
