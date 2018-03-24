<?php

/**
 * 3.0 可配置资源 //TODO:kakatool
 */
class CustomerChatMessage extends CActiveRecord
{

	const MESSAGE_TYPE_TEXT = 'text';
	const MESSAGE_TYPE_IMG = 'img';
	const MESSAGE_SEND_NO = 0;
	const MESSAGE_SEND_OK = 1;
	const MESSAGE_SEND_SENDING = 2;

	public $modelName = '消息群发';

	public $from_date;
	public $to_date;
	public $imgFile;


	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'customer_chat_message';
	}

	public function rules()
	{
		return array(
			array('type', 'required', 'on' => 'create'),
			array('type ', 'required', 'on' => 'update'),
			array('type, message, img, imgFile, audit, target_type, target, create_by_id, create_by_username', 'safe', 'on' => 'update,create'),
			array('id, type, message, img, imgFile, audit, target_type, target, create_by_id, create_by_username, send', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => '类型',
			'message' => '内容',
			'img' => '图片',
			'imgFile' => '图片',
			'created' => '创建时间',
			'updated' => '发送时间',
			'create_by_id' => '操作人',
			'create_by_username' => '操作人',
			'audit' => '审核',
			'send' => '发送状态',
			'times' => '发送次数',
		);
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

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->alias = 'm';
		$criteria->compare('m.message', $this->message, true);
		$criteria->compare('m.create_by_username', $this->create_by_username, true);
		$criteria->compare('m.type', $this->type);
		$criteria->compare('m.send', $this->send);
		$fromDatime = $this->getFromDatetime();
		$toDatetime = $this->getToDatetime();
		if ($fromDatime && $toDatetime) {
			$criteria->addBetweenCondition('m.created', $fromDatime, $toDatetime);
		} else {
			if ($fromDatime) {
				$criteria->addCondition('m.created >= ' . $fromDatime);
			}

			if ($toDatetime) {
				$criteria->addCondition('m.created <= ' . $toDatetime);
			}
		}

		$criteria->order = 'm.id desc';
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getImgUrl()
	{
        if ($this->type == 'img') {
            $res = new PublicFunV30();
            return $res->setAbleUploadImg($this->img);
        } else {
            return '';
        }
	}

	public function getUpdatedTime()
	{
		if ($this->updated) {
			return date('Y-m-d H:i:s', $this->updated);
		} else {
			return '';
		}
	}

	public function getCreationTime()
	{
		if ($this->created) {
			return date('Y-m-d H:i:s', $this->created);
		} else {
			return '';
		}
	}

	public function getMessageType()
	{
		return $this->type == self::MESSAGE_TYPE_TEXT ? '文字' : '图片';
	}

	public function getMessageSend()
	{
		switch ($this->send) {
			case self::MESSAGE_SEND_NO:
			default:
				return '未发送';
				break;
			case self::MESSAGE_SEND_OK:
				return '已发送';
				break;
			case self::MESSAGE_SEND_SENDING:
				return '发送中';
				break;
		}
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
		if (!empty($this->imgFile)) {
			$this->img = Yii::app()->ajaxUploadImage->moveSave($this->imgFile, $this->img);
		}

        if ($this->type == 'text' && !trim($this->message)) {
            return $this->addError('message', '文字消息不能没有文字');
        }

        if ($this->type == 'img' && !$this->img) {
            return $this->addError('imgFile', '图片消息不能没有图片');
        }

		if ($this->isNewRecord) {
			$this->created = time();
			$this->from = '0';
			$this->send = '0';
			$this->audit = '1';
			$this->target_type = 'users';
			$this->target = json_encode(array());

			$this->create_by_id = Yii::app()->user->getId();
			$this->create_by_username = Yii::app()->user->getName();
		}

		$this->updated = time();

		return parent::beforeSave();
	}

	public function getCommunityTreeData()
	{
		//默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
		$branch_id = 1;
		$branch = Branch::model()->findByPk($branch_id);
		return $branch->getRegionCommunityRelation($this->id, 'CustomerChatMessage', false);
	}
}
