<?php

/**
 * This is the model class for table "tie_zi".
 *
 * The followings are the available columns in table 'tie_zi':
 * @property integer $id
 * @property integer $user_id
 * @property string $image_url
 * @property integer $type
 * @property string $content
 * @property integer $create_time
 */
class TieZi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tie_zi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('user_id, type, create_time', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>255),
			array('image_url', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, image_url, type, content, create_time', 'safe', 'on'=>'search'),
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
			'id' => '主键',
			'user_id' => '发帖用户id',
			'image_url' => '图片',
			'type' => '类型(1:水果脱光光；2:水果厨神;3:水果群party;4:许愿墙)',
			'content' => '帖子内容',
			'create_time' => '创建时间',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('image_url',$this->image_url,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TieZi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取头像和名称
	 * @param unknown $id
	 * @param string $isPhoto
	 * @return multitype:string NULL Ambigous <unknown, Ambigous <unknown, NULL>, unknown>
	 */
	public function getCustomerInfo($id,$isPhoto=true){
		if (empty($id)){
			return array('image'=>F::getStaticsUrl('/home/fruitCarnival/images/normal.png'),'name'=>'匿名');
		}
		$data=array();
		$customer=Customer::model()->findByPk($id);
		if (!empty($isPhoto)){
			if(!empty($customer['portrait'])){
				$data['image']=F::getUploadsUrl("/images/" . $customer['portrait']);
			}else {
				$data['image']=F::getStaticsUrl('/home/fruitCarnival/images/normal.png');
			}
		}
		if (!empty($customer['nickname'])){
			$data['name']=$customer['nickname'];
		}else {
			$data['name']='匿名';
		}
		return $data;
	}
	
	/**
	 * 点赞列表
	 * @param unknown $tie_zi_id
	 */
	public function getDianZanList($tie_zi_id){
		if (empty($tie_zi_id)){
			return array();
		}
		$dianzan_list=DianZan::model()->findAll("tie_zi_id=:tie_zi_id and is_praised=2",array(':tie_zi_id'=>$tie_zi_id));
		return $dianzan_list;
	}
	
	public function getUserDianZan($tie_zi_id,$userID){
		if (empty($tie_zi_id)||empty($userID)){
			return array();
		}
		$dianzan=DianZan::model()->find("tie_zi_id=:tie_zi_id and user_id=:user_id",array(':tie_zi_id'=>$tie_zi_id,':user_id'=>$userID));
		return $dianzan;
	}
	
	/**
	 * 评论列表
	 * @param unknown $tie_zi_id
	 */
	public function getPingLunList($tie_zi_id){
		if (empty($tie_zi_id)){
			return array();
		}
		$pinglun_list=PingLun::model()->findAll("tie_zi_id=:tie_zi_id",array(':tie_zi_id'=>$tie_zi_id));
		return $pinglun_list;
	}
	
	/**
	 * 回复列表
	 * @param unknown $ping_lun_id
	 * @return multitype:
	 */
	public function getReplyList($ping_lun_id){
		if (empty($ping_lun_id)){
			return array();
		}
		$reply_list=Reply::model()->findAll("ping_lun_id=:ping_lun_id",array(':ping_lun_id'=>$ping_lun_id));
		return $reply_list;
	}
}
