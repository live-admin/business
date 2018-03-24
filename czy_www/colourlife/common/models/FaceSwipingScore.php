<?php

/**
 * This is the model class for table "face_swiping_score".
 *
 * The followings are the available columns in table 'face_swiping_score':
 * @property integer $id
 * @property integer $customer_id
 * @property string $name
 * @property string $comment
 * @property integer $score
 * @property integer $good
 * @property integer $way
 * @property integer $create_time
 */
class FaceSwipingScore extends CActiveRecord
{
	public $you_hui_quan = 100000111;
	public $you_hui_quan_total = 500;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'face_swiping_score';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, score, good, way, is_anonymous, create_time', 'numerical', 'integerOnly'=>true),
			array('open_id', 'length', 'max'=>100),
			array('name', 'length', 'max'=>100),
			array('pic', 'length', 'max'=>255),
			array('comment', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, open_id, name, pic, comment, score, good, way, is_anonymous, create_time', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'customer_id' => '用户ID',
			'open_id' => '微信唯一ID',
			'name' => '好友名称',
			'pic' => '用户头像',
			'comment' => '好友评论',
			'score' => '分数',
			'good' => '是否赞（1赞，2差评）',
			'way' => '途径（1上传图片，2好友评论，3邀请好友）',
			'is_anonymous' => '是否匿名（0公开，1匿名）',
			'create_time' => '添加时间',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('open_id',$this->open_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pic',$this->pic,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('good',$this->good);
		$criteria->compare('way',$this->way);
		$criteria->compare('is_anonymous',$this->is_anonymous);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FaceSwipingScore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取用户的评论
	 * @param unknown $customer_id
	 * @return multitype:|number
	 */
	public function getCommentList($customer_id,$currentPage = 1,$perPage = 10,$isLimit = false){
		$data = array(
			'list' => array(),
			'goodNum' => 0,
			'badNum' => 0
		);
		if (empty($customer_id)){
			return $data;
		}
		$criteria = new CDbCriteria ();
		$criteria->addCondition("way = 2");
		$criteria->addCondition("customer_id = :customer_id");
		$criteria->params[':customer_id'] = $customer_id;
		$criteria->order = 'create_time desc'; // 排序条件
		if ($isLimit){
			//$perPage = 8;
			$nextPage = $currentPage + 1; // 下一页
			$offset = ($currentPage-1) * $perPage;
			$criteria->limit = $perPage;
			$criteria->offset = $offset;
		}
		$list = $this->findAll ( $criteria );
		if (empty($list)){
			return $data;
		}
		/* if (count ( $list ) <$perPage) {
			$nextPage = 0;
		} */
		$goodNum = 0;
		$badNum = 0;
		$commentList = array();
		foreach ($list as $val){
			$tmp = array();
			if ($val->is_anonymous == 1){
				$tmp['nickName'] = '匿名';
				$tmp['photo'] = F::getStaticsUrl('/activity/v2016/faceSwiping/images/img_pro.png');
			}else {
				$tmp['nickName'] = urldecode($val->name);
				$tmp['photo'] = $val->pic;
			}
			$tmp['comment'] = urldecode($val->comment);
			$tmp['good'] = $val->good;
			if ($val->good == 1){
				$goodNum+=1;
			}elseif ($val->good == 2){
				$badNum+=1;
			}
			$commentList[] = $tmp;
		}
		if ($isLimit){
			$data['goodNum'] = $this->count("way = 2 and good = 1 and customer_id = :customer_id",array(':customer_id' => $customer_id));
			$data['badNum'] = $this->count("way = 2 and good = 2 and customer_id = :customer_id",array(':customer_id' => $customer_id));
		}else {
			$data['goodNum'] = $goodNum;
			$data['badNum'] = $badNum;
		}
		$data['list'] = $commentList;
		//$data['nextPage'] = $nextPage;
		return $data;
	}
	
	/**
	 * 获取用户的排名
	 * @param unknown $customer_id
	 * @return multitype:
	 */
	public function getRanking($customer_id, $isReset = false){
		if (empty($customer_id)){
			return array();
		}
		$data = array();
		//$cacheKey = md5('home:faceswiping:ranking');
		//从缓存里取
		//$data = Yii::app ()->cache->get ( $cacheKey );
		//没缓存读库
		//if (empty($data) || $isReset){
			$criteria = new CDbCriteria();
			$criteria->order = 'score DESC,create_time ASC' ;//排序条件
			$model = FaceSwipingPhoto::model()->findAll($criteria);
			if (!empty($model)){
				foreach ($model as $key => $val){
					$customer = Customer::model()->findByPk($val->customer_id);
					if (empty($customer) || $customer->state ==1){
						continue;
					}
					$tmp = array();
					$tmp['rank'] = $key+1;
					if (!empty($val->img)){
						if ($val->is_default == 1){
							$tmp['picUrl'] = Yii::app()->imageFile->getUrl($val->img);
						}else {
							$tmp['picUrl'] = Yii::app()->imageFile->getUrl('faceswiping/'.$val->img);
						}
					}else {
						$tmp['picUrl'] = '';
					}
					$tmp['nickName'] = empty($customer->nickname) ? '' : $customer->nickname;
					$tmp['totalScore'] = $val->score;
					$tmp['isSelf'] = 0;
					if ($customer_id == $val->customer_id){
						$tmp['isSelf'] = 1;
					}
					$data[$val->customer_id] = $tmp;
				}
			}
			//Yii::app ()->cache->set ( $cacheKey, $data, 86400 );
		//}
		
		return $data;
	}
	
	/**
	 * 记录日志
	 * @param unknown $customer_id
	 * @param unknown $open_id
	 * @param unknown $type
	 * @return boolean
	 */
	public function addLog($customer_id,$open_id,$type){
		if (empty($type)){
			return false;
		}
		$log =new FaceSwipingLog();
		$log->customer_id=$customer_id;
		$log->open_id=$open_id;
		$log->type=$type;
		$log->create_time=time();
		$result = $log->save();
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 添加颜值
	 * @param unknown $customer_id
	 * @param unknown $way
	 * @param unknown $name
	 * @param number $score
	 * @param number $open_id
	 * @param string $comment
	 * @param number $good
	 * @param number $is_anonymous
	 * @return boolean
	 */
	public function addScore($customer_id,$way,$name = '',$open_id = 0,$comment = '',$good = 0,$is_anonymous = 0,$picUrl = ''){
		if (empty($customer_id) || empty($way)){
			return -1;
		}
		$photoModel = FaceSwipingPhoto::model()->find("customer_id=:customer_id",array(
				':customer_id' => $customer_id
		));
		if (empty($photoModel) || empty($photoModel->img)){
			return -2;
		}
		$score = 0;
		if ($way == 2){
			if ($good == 1){
				$score = 1;
			}elseif ($good == 2){
				$score = -1;
			}
		}elseif ($way == 1||$way == 3){
			$score = 5;
		}
		$scoreModel = new FaceSwipingScore();
		$scoreModel->customer_id = $customer_id;
		$scoreModel->way = $way;
		$scoreModel->score = $score;
		$scoreModel->name = $name;
		$scoreModel->open_id = $open_id;
		$scoreModel->comment = $comment;
		$scoreModel->good = $good;
		$scoreModel->is_anonymous = $is_anonymous;
		$scoreModel->pic = $picUrl;
		$scoreModel->create_time = time();
		$result = $scoreModel->save();
		//dump($scoreModel->errors);
		if (!empty($result)){
			if ($good == 1){
				$presult=FaceSwipingPhoto::model ()->updateAll ( array (
						"score" => new CDbExpression ( "score+1" )
				), "id=$photoModel->id");
			}elseif ($good == 2){
				$presult=FaceSwipingPhoto::model ()->updateAll ( array (
						"score" => new CDbExpression ( "score-1" )
				), "id=$photoModel->id");
			}elseif ($way == 1 || $way == 3){
				$presult=FaceSwipingPhoto::model ()->updateAll ( array (
						"score" => new CDbExpression ( "score+5" )
				), "id=$photoModel->id");
			}
			//$this->getRanking($customer_id,true);
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 领取优惠券
	 * @param unknown $mobile
	 * @return boolean
	 */
	public function receiveQuan($customer_id,$mobile){
		if (empty($mobile)){
			return false;
		}
		//判断总数是否已达到
		$total = UserCoupons::model()->count("you_hui_quan_id=:you_hui_quan_id",array(
				':you_hui_quan_id' => $this->you_hui_quan
		));
		if ($total >= $this->you_hui_quan_total){
			return -1;
		}
		//添加优惠券
		$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(":you_hui_quan_id" => $this->you_hui_quan,":mobile" => $mobile));
		if(empty($userCouponsArr)){
			$uc_model=new UserCoupons();
			$uc_model->mobile=$mobile;
			$uc_model->you_hui_quan_id=$this->you_hui_quan;
			$uc_model->create_time=time();
			$result1=$uc_model->save();
			if ($result1){
				//入库兑换表
				$faceChange = new FaceSwipingChange();
				$faceChange->customer_id = $customer_id;
				$faceChange->you_hui_quan_id = $this->you_hui_quan;
				$faceChange->create_time = time();
				$faceChange->save();
				return true;
			}else {
				return false;
			}
		}else {
			return -2;
		}
	}
	
	/**
	 * 获取历史分数列表
	 */
	public function getHistoryScoreList($customer_id){
		if (empty($customer_id)){
			return array();
		}
		$data = array();
		$criteria = new CDbCriteria ();
		$criteria->addCondition("customer_id = :customer_id");
		$criteria->params[':customer_id'] = $customer_id;
		$criteria->order = 'create_time desc'; // 排序条件
		$list = $this->findAll ( $criteria );
		if (!empty($list)){
			foreach ($list as $val){
				$tmp = array();
				$tmp['way'] = $val->way;
				$tmp['score'] = $val->score;
				$tmp['addDate'] = date("Y-m-d",$val->create_time);
				$tmp['addTime'] = date("H:i:s",$val->create_time);
				if ($val->way == 2){ //好友评论
					if ($val->is_anonymous == 1){
						$tmp['name'] = '匿名';
					}else {
						$tmp['name'] = urldecode($val->name);
					}
					$tmp['content'] = '';
					if ($val->good == 1){
						$tmp['content'] = '颜值爆表';
					}elseif ($val->good == 2){
						$tmp['content'] = '颜值欠佳';
					}
				}elseif ($val->way == 3){ //邀请好友
					$tmp['name'] = substr_replace($val->name,"****",3,4);
				}
				$data[] = $tmp;
			}
		}
		return $data;
	}
}
