<?php

/**
 * This is the model class for table "topic".
 *
 * The followings are the available columns in table 'topic':
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $user_id
 * @property integer $community_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $cate_id
 * @property integer $favour_num
 * @property integer $comment_num
 * @property integer $status
 */
class Topic extends CActiveRecord
{
    public $modelName = '话题';
    const WAITAUDIT=0;
    const AGREEAUDIT=1;
    const DISAGREEAUDIT=2;
    public $name;
    public $username;
    public $start_time;
    public $end_time;

    private static $_status = array(
        self::WAITAUDIT => "待审核",//已下单，待付款
        self::AGREEAUDIT => "审核通过",//买家已付款待发货
        self::DISAGREEAUDIT => "审核不通过",//已发货，待收货
    );

    public function getStatusNames()
    {
        return CMap::mergeArray(array('' => '全部'),self::$_status);
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'topic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, user_id, community_id, group_id', 'required'),
			array('user_id, community_id,group_id,favour_num, comment_num, status,parent_status,order_sort', 'numerical', 'integerOnly'=>true),
			array('create_time, update_time', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, content, user_id, start_time,end_time,parent_id,parent_status,community_id, create_time, update_time, group_id, favour_num, comment_num, status', 'safe', 'on'=>'search'),
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
            "customer"=>array(self::BELONGS_TO,"Customer","user_id"),
            "group"=>array(self::BELONGS_TO,"TopicGroup","group_id"),
            "community"=>array(self::BELONGS_TO,"Community","community_id"),
            "cate"=>array(self::BELONGS_TO,"TopicCategory","cate_id"),
            'comment'=>array(self::HAS_MANY,"TopicComment","topic_id",'order'=>"id DESC"),
            'favour'=>array(self::HAS_MANY,"TopicFavour","topic_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => '标题',
			'content' => '内容',
			'user_id' => '发帖人',
			'community_id' => '小区',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'cate_id' => '分类ID',
            'group_id'=>'所属小组',
			'favour_num' => '赞的次数',
			'comment_num' => '评论的次数',
			'status' => '状态',
            'parent_id' => '父级ID',
            'order_sort'=>'排序',
            'start_time'=>'开始时间',
            'end_time'=>'结束时间',
            'username'=>'用户名',
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

		$criteria->compare('content',$this->content,true);
        if(!empty($this->start_time)){
           $criteria->addCondition("create_time>=" . strtotime($this->start_time));
        }
        if(!empty($this->end_time)){
            $criteria->addCondition("create_time<=" . strtotime($this->end_time));
        }
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('status',$this->status);
        $criteria->addCondition("parent_id=0");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 'id desc',
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Topic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //获取分类
    public function getCate(){
        $cate=array();
        $model=TopicCategory::model()->findAll();
        if(!empty($model)){
            foreach($model as $val){
                $id=$val->id;
                $name=$val->name;
                $cate[$id]=$name;
            }
        }
        return $cate;
    }


    //获取当前话题分类
    public function getMyCate(){
        return isset($this->cate)?$this->cate->name:"";
    }

    //获取当前话题图片路劲
    public function getMyCatePic(){
        return isset($this->cate)?F::getStaticsUrl($this->cate->pic):"";
    }
    //发帖人名字
    public function getCustomerName(){
        if(isset($this->customer)){
            return empty($this->customer->nickname)?"匿名":$this->customer->nickname;
        }
        return "";
    }
    public function getCustomerUserName(){
        if(isset($this->customer)){
            return empty($this->customer->username)?"匿名":$this->customer->username;
        }
        return "";
    }
    //获取头像
    public function getCustomerImg(){
        if(isset($this->customer)){
           if(!empty($this->customer->portrait)){
               return F::getUploadsUrl("/images/" . $this->customer->portrait);
           };
        }
        return F::getStaticsUrl('/linli/images/normal.png');
    }


    //小区名字
    public function getCommunityName(){
        // if(isset($this->community)){
        //     return $this->community->name;
        // }
        // return "";

        //  ICE接入
        if(!empty($this->community_id)){
            $community = ICECommunity::model()->findByPk($this->community_id);
            if(!empty($community)){
               return  $community['name'];
            }
        }

        return '';
    }
    //获取状态
    public function getStatusName(){
       if($this->status==self::WAITAUDIT){
           return "待审核";
       }else if($this->status==self::AGREEAUDIT){
            return "审核通过";
        }else if($this->status==self::DISAGREEAUDIT){
            return "审核不通过";
        }
    }

    //判断是否有关注
    public function getIsFocus($id){
       $model=TopicFocus::model()->find("user_id=:user_id AND topic_id=:topic_id",array(":user_id"=>Yii::app()->user->id,":topic_id"=>$id));
        if(!empty($model)){
            return true;
        }
        return false;
    }
    //判断是否有点赞
    public function getIsFavour($id){
        $model=TopicFavour::model()->find("user_id=:user_id AND topic_id=:topic_id",array(":user_id"=>Yii::app()->user->id,":topic_id"=>$id));
        if(!empty($model)){
            return true;
        }
        return false;
    }

    //获取话题图片
    public function getTopicPic($id){
        $topic=Topic::model()->findByPk($id);
        $topic_id=$id;
        if($topic->parent_id!=0){
            $topic_id=$topic->parent_id;
        }
        $images=array();
        $model=TopicPic::model()->findAll("topic_id=:topic_id",array(":topic_id"=>$topic_id));
        if(!empty($model)){
            foreach($model as $val){
                $images[]=F::getUploadsUrl($val->path);
            }
        }
        return $images;
    }


    public function addImages()
    {
        if (Yii::app()->user->hasState('images')) {
            $userImages = Yii::app()->user->getState('images');

            //Now lets create the corresponding models and move the files
            foreach ($userImages as $k => $image) {
                if (is_file($image["path"])) {
                    $img = new TopicPic();
                    $img->path = $image["savePath"];
                    $img->topic_id = $this->id;
                    $img->size = $image["size"];
                    $img->create_time = time();
                    if (!$img->save()) {
                        Yii::log("Could not save Image:\n" . CVarDumper::dumpAsString(
                                $img->getErrors()), CLogger::LEVEL_ERROR);
                        throw new Exception('Could not save Image');
                    }
                } else {
                    Yii::log($image["path"] . " is not a file", CLogger::LEVEL_WARNING);
                }
            }
            Yii::app()->user->setState('images', null);
        }
    }

    public function afterSave()
    {
        $this->addImages();
        return parent::afterSave();
    }

    public function beforeDelete()
    {
        //删除所有的小区的对应的帖子
        Topic::model()->deleteAllByAttributes(array('parent_id' => $this->id));
        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        //删除图片和关联关系
        $data = TopicPic::model()->findAllByAttributes(array('topic_id' => $this->id));
        if (count($data) > 0) {
            foreach ($data as $val) {
                $file = dirname(__FILE__) . '/../../uploads' . $val->path;
                if (file_exists($file)) {
                    @unlink($file);
                }
            }
        }
        TopicPic::model()->deleteAllByAttributes(array('topic_id' => $this->id));
        TopicComment::model()->deleteAllByAttributes(array('topic_id' => $this->id));
        TopicFavour::model()->deleteAllByAttributes(array('topic_id' => $this->id));

        return parent::afterDelete();
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'AuditBehavior' => array('class' => 'common.components.behaviors.AuditBehavior'),
        );
    }


    public function auditOk()
    {
        $this->status = self::AGREEAUDIT;
    }

    public function auditNo()
    {
        $this->status = self::DISAGREEAUDIT;
    }


    public function getShortTitle(){
        return mb_strlen($this->title,'utf-8')>10?F::msubstr($this->title,0,10).'...':$this->title;
    }


    public function getShortContent(){
        return mb_strlen($this->content,'utf-8')>30?F::msubstr($this->content,0,30).'...':$this->content;
    }

    //发送全小区
    public function updateSendCommunity()
    {
        $this->sendCommunity();
        $this->parent_status = 1;
    }
    //发送全小区
    public function sendCommunity()
    {
        $sql = '';
        //取最大组织架构的小区IDS
        $community_ids =  $data = Branch::model()->findByPk(1)->getBranchAllIds('Community');
        if(!empty($community_ids)){
            $sql ="INSERT INTO `topic` (`title`,`content`,`user_id`,`community_id`,`create_time`,`update_time`,`cate_id`,`favour_num`,`comment_num`,`status`,`parent_id`,`group_id`) VALUES";

            foreach($community_ids as $cid)
            {
                $topic=TopicGroupCommunityRelation::model()->find('community_id=:cid AND group_id=:group_id',array(':cid'=>$cid,':group_id'=>$this->group_id));
                if($cid!=$this->community_id && !empty($topic))
                {
                    $sql .=" ('".$this->title."','".$this->content."','".$this->user_id."','".$cid."','".$this->create_time."','".$this->update_time."','0','0','0','".$this->status."','".$this->id."','".$this->group_id."'),";
                    ;
                }else{
                    continue;
                }
            }
            $sql = substr($sql,0,-1);
        }
        if($sql!=''){
            $db = Yii::app()->db;
            $transaction = $db->beginTransaction();
            try {
                $db->createCommand($sql)->execute();
                //sleep(10);
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
            }
        }
    }


    //获取发帖人所在小区
    public function getCustomerCommunity(){
       if(isset($this->customer)){
            if(isset($this->customer->community)){
                return $this->customer->community->name;
            }else{
                return "";
            }
       }else{
           return "";
       }
    }
    //hover效果
    public function getBranchTag()
    {
        if(!empty($this->community_id)){
            $branch_id=$this->community->branch->id;
            return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '所属部门:' .
                Branch::getMyParentBranchName($branch_id)), $this->community->branch->name);
        }
    }

    //获取所有小组ID
    public function getAllGroup(){
        $data=array(""=>"全部");
        $model=BackendTopicGroup::model()->findAll();
        if(!empty($model)){
            foreach($model as $val){
                $id=$val->id;
                $data[$id]=$val->title;
            }
        }
        return $data;
    }

    //a标签
    public function getLinkTag()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => '#', ), $this->getShortContent());
    }

    //获取评论人的昵称
    public function getCommentNickname(){
        $nickname=array();
        if(!empty($this->favour)){
            foreach($this->favour as $user){
                if(!empty($user->customer)){
                    if(!empty($user->customer->nickname)){
                        $nickname[]=$user->customer->nickname;
                    }else{
                        $nickname[]="匿名";
                    }

                }else{
                    $nickname[]="匿名";
                }
            }
        }
        return $nickname;
    }

    //获取评论内容跟昵称
    public function getComments(){
        $nickname=array();
        $nick = '';
        if(!empty($this->comment)){
            $n=0;
            foreach($this->comment as $user){
                $nick="匿名";
                if(!empty($user->customer)){
                    if(!empty($user->customer->nickname))
                        $nick=$user->customer->nickname;
                }
                $nickname[$n]["nickname"]=$nick;
                $nickname[$n]["content"]=mb_strlen($user->content,'utf-8')>30?F::msubstr($user->content,0,30).'...':$user->content;
                $n++;
            }
        }
        return $nickname;
    }

    //获取评论内容跟昵称
    public function getThreeComments(){
        $nickname=array();
        $nick = '';
        if(!empty($this->comment)){
            $n=0;
            foreach($this->comment as $user){
                $nick="匿名";
                if(!empty($user->customer)){
                    if(!empty($user->customer->nickname))
                        $nick=$user->customer->nickname;
                }
                $nickname[$n]["nickname"]=$nick;
                $nickname[$n]["content"]=mb_strlen($user->content,'utf-8')>30?F::msubstr($user->content,0,30).'...':$user->content;
                if($n==2){
                    break;
                }
                $n++;

            }
        }
        return $nickname;
    }

    //获取分组名字
    public function getGroupName(){
        if(isset($this->group)){
            return mb_strlen($this->group->title,'utf-8')>8?F::msubstr($this->group->title,0,8).'...':$this->group->title;
        }

    }

    //获取后台分组名字
    public function getBackendGroupName(){
        if(isset($this->group)){
            return $this->group->title;
        }else{
            return "";
        }
    }

    //判断是否有新圈子
    static public function getIsHaveNewGroup(){
        $ids=array();
        //查询用户已经关注的圈子ID
        $model=TopicGroupCustomerRelation::model()->findAll("customer_id=:customer_id AND community_id=:community_id",array(":customer_id"=>Yii::app()->user->id,":community_id"=> Yii::app()->user->cid));

        foreach($model as $val){
            $id=$val->group_id;
            if($id!=1){
                $ids[]=$id;
            }

        }
        $criteria=new CDbCriteria();
        $criteria->join="LEFT JOIN `topic_group_community_relation` tg ON tg.group_id=`t`.id";
        $criteria->compare("tg.community_id",Yii::app()->user->cid);
        $criteria->compare("`t`.is_show",1);
        $criteria->addNotInCondition("`t`.id",$ids);
        $criteria->distinct="tg.id";
        $group=TopicGroup::model()->findAll($criteria);
        if(!empty($group)){
            return true;
        }else{
            return false;
        }
    }
}
