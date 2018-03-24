<?php

/**
 * This is the model class for table "fresh_news".
 *
 * The followings are the available columns in table 'fresh_news':
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property integer $creator
 * @property string $content
 * @property integer $praise
 * @property integer $audit
 * @property integer $category_id
 * @property integer $is_deleted
 * @property integer $click
 * @property integer $community_id
 * @property string $customer_name
 * @property string $customer_mobile
 * @property integer $create_time
 * @property Customer $customer
 * @property integer $dideba
 * @property FreshNewsComments $comments
 */
class FreshNews extends CActiveRecord
{
    public $modelName = '新鲜事';
    public $start_time;
    public $end_time;
    public $communities;
    public $region;
    public  $url;
    public  $images = array();
    public  $urlFile;
    public static $cdb;

    const AUDIT_PASS = 1; //审核状态-通过
    const AUDIT_WAIT = 0; //审核状态 - 待审
    const AUDIT_OUT = 2; //审核状态-不通过

    const FRESH_NEW_TYPE = 0;//小区新鲜事
    const PRAISE_TYPE = 1;//表扬事件

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fresh_news';
	}

    public function getModelName(){
        return "";
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, category_id', 'required'),
			array('creator, praise, audit, category_id, is_deleted, click, type', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('customer_name', 'length', 'max'=>45),
			array('customer_mobile', 'length', 'max'=>15),
			array('content,images,upFile,reward', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title,images,upFile,reward, creator, content, praise, audit, category_id, is_deleted, click, community_id, customer_name, customer_mobile, create_time, start_time, end_time', 'safe', 'on'=>'search'),
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
            'category' => array(self::BELONGS_TO,'FreshNewsCategory','category_id'),
            'comments' => array(self::HAS_MANY,'FreshNewsComments','fresh_news_id', 'condition' => 'reply_id = 0 AND audit = '.self::AUDIT_PASS, 'order' => 'create_time DESC'),
            'community' => array(self::BELONGS_TO,'Community','community_id'),
            'customer' => array(self::BELONGS_TO,'Customer','creator'),
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
			'creator' => '发表人',
			'content' => '内容',
			'praise' => '赞',
			'audit' => '审核',
			'category_id' => '分类',
			'is_deleted' => '是否删除',
			'click' => '点击数',
			'community_id' => '小区',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'customer_name' => '发布人名称',
			'customer_mobile' => '联系电话',
			'create_time' => '发布时间',
            'communities' => '选择的小区',
            'region'=>'地域',
			'urlFile' => '图片',
            'reward' =>'公司嘉奖',
		);
	}

    public function customerCreate(){
        $customer = Customer::model()->findByPk(Yii::app()->user->id);
        $this->community_id = $customer->community_id;
        $this->creator = $customer->id;
        $this->customer_name = $customer->name;
        $this->customer_mobile = $customer->mobile;
        $this->create_time = time();
        $this->praise = $this->click = 0;
       return  $this->createFreshPrais($this->type);
    }

    public function getCommunity()
    {
        $data = array();
        $model = Community::model()->findAllByAttributes(array('state'=>0));
        foreach($model as $val){
            $data[$val->id] = $val->name;
        }
        return $data;
    }

    /**
     * 根据传过来的type内容区别是新鲜事或表扬数据列表
     * @param bool $type 默认为false
     * @return CActiveDataProvider
     */
    public function search($type = false)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
        if($type){
            $criteria->compare('type',1);
        }
        else{
            $criteria->compare('type',0);
        }
        if(!empty($this->creator)){
            if('员工' == $this->creator){
                $criteria->compare('creator', 0);
            }
            else{
                $customer = Customer::model()->findByAttributes(array('username' => $this->creator));
                if(!empty($customer)){
                    $criteria->compare('creator',$customer->id);
                }
            }
        }
        $criteria->addCondition("(title LIKE '%".$this->content."%' OR content LIKE '%".$this->content."%' )");
		//$criteria->compare('content',$this->content,true);
		$criteria->compare('praise',$this->praise);
		$criteria->compare('audit',$this->audit);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('click',$this->click);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_mobile',$this->customer_mobile,true);
        if ($this->start_time != "") {
            $criteria->addCondition('create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

        self::$cdb = $criteria;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 't.audit, t.create_time desc',
            )
		));
	}

    /**
     * 新鲜事查询
     * @return CActiveDataProvider
     */
    public function freshSearch()
    {
        return $this->search(false);
    }

    /**
     * 表杨查询
     */
    public function praiseSearch(){
        return $this->search(true);
        /*$criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
      //  $criteria->compare('title',$this->title,true);
        $criteria->compare('creator',$this->creator);
        $criteria->addCondition("(title LIKE '%".$this->content."%' OR content LIKE '%".$this->content."%' )");
        $criteria->compare('praise',$this->praise);
        $criteria->compare('audit',$this->audit);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('is_deleted',$this->is_deleted);
        $criteria->compare('click',$this->click);
        $criteria->compare('community_id',$this->community_id);
        $criteria->compare('customer_name',$this->customer_name,true);
        $criteria->compare('customer_mobile',$this->customer_mobile,true);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('type','1');
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));*/
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FreshNews the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getCategoryName(){
       return  empty($this->category)?"":$this->category->name;
    }

    public function getCommunityName(){
       return empty($this->community)?"":$this->community->name;
    }

    public function getAuditName($audit = '')
    {
        $return = '';
        switch ($audit) {
            case '':
                $return = "";
                break;
            case 0:
                $return = '<span class="label label-error">待审核</span>';
                break;
            case 1:
                $return = '<span class="label label-success">审核通过</span>';
                break;
            case 2:
                $return = '<span class="label label-error">审核未通过</span>';
                break;
        }
        return $return;
    }

    public function getCreatorName(){
        return empty($this->customer)?"员工":$this->customer->username;
    }

    public function getIsAudit()
    {
        return $this->getAttribute('audit') == self::AUDIT_WAIT;
    }

    public function passAudit()
    {
        $this->audit = self::AUDIT_PASS;
    }

    public function outAudit()
    {
        $this->audit = self::AUDIT_OUT;
    }

    //获得投诉小区所属的部门
    public function getCommunityInBranch()
    {
        $community_id = $this->community_id;
        if (empty($community_id)) {
            return "获取小区信息失败！";
        }
        $community = Community::model()->findByPk($community_id);
        if (empty($community)) {
            return "获取小区所属部门失败！";
        }
        return Branch::getMyParentBranchName($community->branch_id).'-'.$community->branchName;
    }

    //获得投诉小区所在的地区
    public function getCommunityDetail()
    {
        $community_id = $this->community_id;
        if (empty($community_id)) {
            return "获取小区信息失败！";
        }
        $community = Community::model()->findByPk($community_id);
        if (empty($community)) {
            return "获取小区所属部门失败！";
        }
        $addressList = $community->getMyParentRegionNames();
        $address = implode("-", $addressList) . "-" . $community->name;
        return $address;
    }

    //根据不同的类型得到图片
    public function getTypePics()
    {
        $pic = array();
        $CDbCriteria = new CDbCriteria();
        $CDbCriteria->compare("model", 'freshNews');
        $CDbCriteria->compare("object_id", $this->id);
        $picture = Picture::model()->findAll($CDbCriteria);
        if (empty($picture)) {
            return $pic;
        } else {

            foreach ($picture as $val) {
                $pic[] = Yii::app()->imageFile->getUrl($val->url);
            }
            return $pic;
        }
    }

    public function getIsDeleteName()
    {
        if($this->is_deleted == 0){
            $result = '<span class="label label-error">否</span>';
        }
        else{
            $result = '<span class="label label-success">是</span>';
        }
        return $result;
    }
    public function behaviors(){
        $array = array( 'CTimestampBehavior' => array(
            'class' => 'zii.behaviors.CTimestampBehavior',
            'createAttribute' => 'create_time',
            'updateAttribute' => null,
            'setUpdateOnCreate' => true,
        ));
        return CMap::mergeArray(parent::behaviors(),$array);
    }
    
    protected function beforeValidate() {
    	if(empty($this->url) && !empty($this->urlFile))
    		$this->url = '';
    	return parent::beforeValidate();
    }
    
    public function createFreshPrais($type=0){
    	$typeModel = get_class($this);
    	if($this->save()){
            if (!empty($this->images)) {
                //保存上传的图片路径
                $crieria = new CDbCriteria();
                $crieria->addInCondition('id', $this->images);
                $attr = array(
                    'model' => $typeModel,
                    'object_id' => $this->id,
                );
                return Picture::model()->updateAll($attr, $crieria);
            }else{
                return true;
            }
    	}
        return false;
    }
    
    public function getFreshNewsDideba(){
    	$count = Dideba::model()->count('model=:model and object_id=:object_id',array(':model'=>"freshNews",':object_id'=>$this->id));
    	return $count + $this->praise;
    }

    public function getPraiseDideba(){
    	$count = Dideba::model()->count('model=:model and object_id=:object_id',array(':model'=>"praise",':object_id'=>$this->id));
    	return $count;
    }

    public function getDideba()
    {
        return Dideba::model()->count('object_id = :object_id', array(':object_id' => $this->id)) + $this->praise;
   }

    public function getCustomerPortrait()
    {
        if($this->customer && !empty($this->customer->portrait)){
            return Yii::app()->ajaxUploadImage->getUrl($this->customer->portrait, '/common/images/nopic.png');
        }
        return Yii::app()->ajaxUploadImage->getUrl('/common/images/nopic.png');
    }

    //判断该用户是否赞过
    public function getIsLike(){
        return Dideba::model()->count('object_id = :object_id AND customer_id=:customer_id AND model=:model', array(':object_id' => $this->id,':customer_id'=>Yii::app()->user->id,':model'=>'FreshNews'));
    }
    public function getFromName(){
        if($this->creator==0){
            return "彩生活总部";
        }else{
            return "业主";
        }
    }

    //算时间差
    public function timediff($begin_time,$end_time)
    {
        if($begin_time < $end_time){
            $starttime = $begin_time;
            $endtime = $end_time;
        }
        else{
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        $timediff = $endtime-$starttime;
        $days = intval($timediff/86400);
        $remain = $timediff%86400;
        $hours = intval($remain/3600);
        $remain = $remain%3600;
        $mins = intval($remain/60);
        $secs = $remain%60;
        $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
        return $res;
    }

    //返回小区新鲜事发布距离现在的时间
    public function getTimeDifference(){
        $begin_time=$this->create_time;
        $end_time=time();
        $result=$this->timediff($begin_time,$end_time);
        $string="";
        if($result['day']>0){
            return date("Y-m-d",$this->create_time);
        }else{
            if($result['hour']>0){
                $string.=$result['hour'] . "小时" ;
            }
            if($result['min']>0){
                $string.=$result['min'] . "分钟" ;
            }
            if($string==""){
                return "刚刚";
            }else{
                $string.="前";
                return $string;
            }
        }

    }

    //获取小区新鲜事的评论条数
    public function getCommentsNum(){
        $count = FreshNewsComments::model()->count('fresh_news_id=:fresh_news_id AND audit=:audit',array(':fresh_news_id'=>$this->id,':audit'=>1));
        return $count;
    }

}
