<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/7/16
 * Time: 10:28 上午
 * 3.0邻里信息流 TODO:kakatool
 */
class FeedTimeline extends CActiveRecord
{

    public $modelName = '时间轴';

    public $community_id;
    public $customer_id;
    public $customer_name;
    public $feed_type;
    public $feed_third_id;
    public $audit;
    public $is_deleted;
    public $creationtime;
    public $modifiedtime;


    public static function model($classNeme = __CLASS__)
    {
        return parent::model($classNeme);
    }

    public function tableName()
    {
        return 'feed_timeline';
    }

    public function rules()
    {

        return array(
            array('community_id,customer_id', 'required', 'on' => 'create'),
            array('customer_name', 'safe', 'on' => 'create'),
            array('customer_name', 'length', 'max' => 200),
            array('community_id, customer_id,feed_type,feed_third_id,creationtime,modifiedtime,audit,is_deleted', 'numerical', 'integerOnly' => true),
            array('community_id,customer_id,customer_name,feed_type,feed_third_id,audit,is_deleted,creationtime,modifiedtime', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'moment' => array(self::HAS_ONE, 'FeedMoment', array('id' => 'feed_third_id')),//fk => pk
            'activity' => array(self::HAS_ONE, 'FeedActivity', array('id' => 'feed_third_id')),
            'like' => array(self::HAS_MANY, 'FeedLike', 'timeline_id'),
            'comment' => array(self::HAS_MANY, 'FeedComment', 'timeline_id', 'condition' => 'comment.audit=1 and comment.is_deleted=0 '),

        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'community_id' => '时间轴id',
            'customer_id' => '用户id',
            'customer_name' => '用户名',
            'feed_type' => '类型',
            'feed_third_id' => '时刻/活动id',
            'audit' => '是否审核',
            'is_deleted' => '是否删除',
            'creationtime' => '创建时间',
            'modifiedtime' => '更新时间',
        );
    }


    /**
     * 根据用户获取时间轴(支付分页)
     * @param int $customer_id
     * @param int $page
     * @param int $page_size
     * @throws CHttpException
     */
    public function getTimelineListByCustomer($customer_id = 0, $page = 1, $page_size = 10)
    {

        if (!$customer_id) {
            throw new CHttpException(400, '用户id不能为空');
        }
        if ($page < 1) {
            $page = 0;
        } else {
            $page = $page - 1;
        }


        //获取总数量
        $total = FeedTimeline::model()->count('audit=1 AND is_deleted=0 AND customer_id=:customer_id', array(':customer_id' => $customer_id));
        $total = $total == null ? 0 : intval($total);
        $paged = array(
            'total' => $total,
            'page' => $page + 1,
            'size' => $page_size,
            'more' => ($page + 1) * $page_size < $total ? 1 : 0,

        );


        $criteria = new CDbCriteria();
        $criteria->select = 'id,community_id,customer_id,customer_name,feed_type,feed_third_id,creationtime';
        $criteria->condition = ' t.audit=1 AND t.is_deleted=0 AND t.customer_id=:customer_id';
        $criteria->params = array(':customer_id' => $customer_id);
        $criteria->order = ' t.creationtime DESC';
        $criteria->limit = $page_size;
        $criteria->offset = $page * $page_size;


        $model = FeedTimeline::model()->with('moment', 'activity', 'like', 'comment')->findAll($criteria);

        $data = array();

        if ($model && count($model)) {

            foreach ($model as $m) {

                $data[] = array(
                    'id' => (int)$m->id,
                    'type' => (int)$m->feed_type,
                    'normal_feed_content' => $this->_getMomentArray($m),
                    'activity_feed_content' => $this->_getActivityArray($m),
                    'user' => $this->_getCustomerArray($m),
                    'replys' => $this->_getCommentArray($m),
                    'like' => $this->_getLikeArray($m),
                    'like_total' => $this->_getLikeTotal($m),
                    'reply_total' => $this->_getCommentTotal($m),
                    'creationtime' => (int)$m->creationtime,
                    'like_status' => $this->_checkLikeStatus($m),
                );

            }

        }

        $resutl = array(
            'ok' => 1,
            'feeds' => $data,
            'paged' => $paged,
        );

        return $resutl;

    }


    /**
     * 根据小区获取时间轴（支持分页）
     * @param int $community_id
     * @param int $page
     * @param int $page_size
     * @throws CHttpException
     */
    public function getTimelineListByCommunity($community_id = 0, $page = 1, $page_size = 10)
    {

        if (!$community_id) {
            throw new CHttpException(400, '小区id不能为空');
//			$this->addError('community_id','小区id不能为空');
        }
        if ($page < 1) {
            $page = 0;
        } else {
            $page = $page - 1;
        }

        //获取总数量
        $total = FeedTimeline::model()->count('audit=1 AND is_deleted=0 AND community_id=:community_id', array(':community_id' => $community_id));
        $total = $total == null ? 0 : intval($total);
        $paged = array(
            'total' => $total,
            'page' => $page + 1,
            'size' => $page_size,
            'more' => ($page + 1) * $page_size < $total ? 1 : 0,

        );

        $criteria = new CDbCriteria();
        $criteria->select = 'id,community_id,customer_id,customer_name,customer_portrait,feed_type,feed_third_id,creationtime';
        $criteria->condition = ' t.audit=1 AND t.is_deleted=0 AND t.community_id=:community_id';
        $criteria->params = array(':community_id' => $community_id);
        $criteria->order = ' t.creationtime DESC';
        $criteria->limit = $page_size;
        $criteria->offset = $page * $page_size;

        $model = FeedTimeline::model()->with('moment', 'activity', 'like', 'comment')->findAll($criteria);

        $data = array();

        if ($model && count($model)) {

            foreach ($model as $m) {

                $data[] = array(
                    'id' => (int)$m->id,
                    'type' => (int)$m->feed_type,
                    'normal_feed_content' => $this->_getMomentArray($m),
                    'activity_feed_content' => $this->_getActivityArray($m),
                    'user' => $this->_getCustomerArray($m),
                    'replys' => $this->_getCommentArray($m),
                    'like' => $this->_getLikeArray($m),
                    'like_total' => $this->_getLikeTotal($m),
                    'reply_total' => $this->_getCommentTotal($m),
                    'creationtime' => (int)$m->creationtime,
                    'like_status' => $this->_checkLikeStatus($m),
                );

            }

        }

        $resutl = array(
            'ok' => 1,
            'feeds' => $data,
            'paged' => $paged,
        );

        return $resutl;

    }


    private function _getCustomerNickname($customer)
    {
        if (!$customer) {
            return '';
        }

        $name = trim($customer->name);
        if ($name) {
            return $name;
        }

        $name = trim($customer->nickname);
        if ($name) {
            return $name;
        }

        return '彩多多';
    }

    /**
     * 获取用户信息，目前返回id和name，后期需要，返回customer实体内容
     * @param $model
     * @return array
     */
    private function _getCustomerArray($model)
    {

        if ($model) {
            //$customerID = (int)$model->customer_id;
            //$customer = Customer::model()->findByPk($customerID);
            return array(
                'id' => (int)$model->customer_id,
                'nickname' => $model->customer_name,
                'portrait' => Yii::app()->imageFile->getUrl($model->customer_portrait),
                //'portrait' => HomeConfig::model()->getResourceImage($model->customer_portrait),
                //'nickname' => $this->_getCustomerNickname($customer),
                //'portrait' => $customer->getPortraitUrl(),
            );
        }

        return array();

    }


    /**
     * 获取时刻信息
     * @param $model
     * @return array
     */
    private function _getMomentArray($model)
    {

        if ($model && $model->feed_type == 1 && $model->moment) {

            return array(
                'id' => (int)$model->moment->id,
                'content' => $model->moment->content,
                'photos' => $model->moment->getPhotoArray(),
                'creationtime' => (int)$model->moment->creationtime,
            );

        }

        return array();

    }


    /**获取活动信息
     * @param $model
     * @return array
     */
    private function _getActivityArray($model)
    {

        if ($model && $model->feed_type == 2 && $model->activity) {

            //$res = new PublicFunV23();
            $res = new PublicFunV30();
            return array(
                'id' => (int)$model->activity->id,
                'activity_categoty' => array('id' => (int)$model->activity->activity_type_id, 'name' => $model->activity->activity_type_name, 'photo' => $res->setAbleUploadImg($model->activity->activity_type_photo),),
                'content' => $model->activity->content,
                'start_time' => $model->activity->start_time,
                'stop_time' => $model->activity->stop_time,
                'location' => $model->activity->location,
                'creationtime' => (int)$model->activity->creationtime,
            );

        }

        return array();


    }


    /**
     * 获取点赞数据
     * @param $model
     * @return array
     */
    private function _getLikeArray($model)
    {

        if ($model && $model->like && is_array($model->like)) {

            $data = array();
            foreach ($model->like as $m) {
                $customerID = (int)$m->customer_id;
                $data[] = $customerID ? array(
                    'id' => $customerID,
                    'nickname' => $m->customer_name,
                    //'nickname' => $this->_getCustomerNickname($m->customer),
                ) : array(
                    'id' => 0,
                    //'nickname' => $m->customer_name,
                    'nickname' => '',
                );
            }
            return $data;

        }

        return array();

    }

    /**
     * 获取点赞总数
     * @param $model
     * @return int
     */
    private function _getLikeTotal($model)
    {

        if ($model && $model->like && is_array($model->like)) {

            return count(($model->like));

        }

        return 0;

    }


    /**
     * 检查我的点赞状态
     * @param $model
     * @return int
     */
    private function _checkLikeStatus($model)
    {

        $is_like = 0;
        $user_id = (int)Yii::app()->user->getId();
        if ($model && $model->like && is_array($model->like) && $user_id) {

            foreach ($model->like as $m) {

                if ($user_id == (intval($m->customer_id))) {
                    $is_like = 1;
                    break;
                }
            }

        }
        return $is_like;

    }


    /**
     * 获取留言数据
     * @param $model
     * @return array
     */
    private function _getCommentArray($model)
    {

        if ($model && $model->comment && is_array($model->comment)) {

            $data = array();

            foreach ($model->comment as $m) {
                $fromUser = $toUser = array();
                if ($m->to_customer_id) {
                    $toUser = array(
                        'id' => (int)$m->to_customer_id,
                        'nickname' => $m->to_customer_name,
                        //'nickname' => $this->_getCustomerNickname($m->toCustomer)
                    );
                } else {
                    $toUser = array('id' => 0, 'nickname' => '');
                }
                if ($m->from_customer_id) {
                    $fromUser = array(
                        'id' => $m->from_customer_id,
                        'nickname' => $m->from_customer_name,
                        //'nickname' => $this->_getCustomerNickname($m->fromCustomer)
                    );
                } else {
                    $fromUser = array('id' => 0, 'nickname' => '');
                }
                $data[] = array(
                    'id' => (int)$m->id,
                    'to_user' => $toUser,
                    'from_user' => $fromUser,
                    'content' => $m->content,
                    'creationtime' => (int)$m->creationtime,
                );
            }
            return $data;

        }

        return 0;


    }


    /**
     * 获取留言总数
     * @param $model
     * @return int
     */
    private function _getCommentTotal($model)
    {

        if ($model && $model->comment && is_array($model->comment)) {

            return count($model->comment);

        }

        return 0;


    }

} 
