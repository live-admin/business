<?php

/**
 * This is the model class for table "small_loans".
 *
 * The followings are the available columns in table 'small_loans':
 * @property int $id
 * @property varchar $name
 * @property varchar $logo
 * @property varchar $company
 * @property varchar $website
 * @property tinyint $audit
 * @property varchar $version
 * @property tinyint $type
 * @property varchar $url
 * @property text $parameter
 * @property tinyint $state
 * @property int $create_time
 * @property varchar $sl_key
 */
class SetableCls extends CActiveRecord
{
    public $modelName = '首页快捷类';
    
    public $completeURL;            //完整URL地址 = 入口地址 + 参数    

    const AUDIT_NOT = 0;    //未审核
    const AUDIT_YES = 1;    //已审核
    
    const TYPE_WEB = 0;     //类型：网站
    const TYPE_MOBILE = 1;   //类型：手机

    const STATE_YES = 0;      //启用
    const STATE_NO = 1;       //禁用
    
    public $imgFile;
    
    public $community_ids = array();


    public function getTypeName()
    {
        switch ($this->type) {
            case self::TYPE_WEB:
                return "网站";
                break;
            case self::TYPE_MOBILE:
                return "手机";
                break;
        }
    }
    
    public function getAuditName(){
        switch ($this->audit) {
            case self::AUDIT_NOT:
                return '未审核';
                break;
            case self::AUDIT_YES:
                return '已审核';
                break;
        }
    }
    
    public function getStateName(){
        switch ($this->state){
            case self::STATE_YES:
                return "启用";
                break;
            case self::STATE_NO:
                return "禁用";
                break;
        }
    }
    

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'setable_cls';
    }

    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
		return array(
            array('small_loans_id, img, name, url, state, sort,  act, proto_android, proto_ios', 'required', 'on' => 'update,create'),
            array('id, small_loans_id, name, img,state, sort, proto_android, proto_ios, act', 'safe', 'on' => 'search'),
            array('community_ids,img,parameter,imgFile','safe', 'on' => 'create,update'),
        );
		
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'small_loans_id' => '关联资源Id',
            'img' => '广告图片',
            'name'=>'名称',
			'imgFile'=>'广告图',
            'url' => 'Web地址',
            'state' => '状态',
            'sort' => '排序',
			'proto_android' => '安卓类',
			'proto_ios' => '果机类',
			'act' => '应用url/原型proto',
			'community_ids' => '服务范围'
        );
    }
    
    

    /**
     * 根据sl_key判断是否启用
     */
    public function isPorto(){
        if ($this->act == 'url') return '应用URL'; else return '手机原型';
    }
    
    public function search()
    {
        $criteria = new CDbCriteria;

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            //'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
    
    public function getImgUrl()
    {
		$res = new PublicFunV23();
		$url = $res->setAbleUploadImg($this->img);
		return $url;
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
        return parent::beforeSave();
   }
   
   
   //增加参数
   public function addParam($k,$v){
       $arr = array();
       $arr[$k] = $v;
       if($this->parameter){
           $arrParameter = CJSON::decode($this->parameter);
           $resParameter = array_merge($arrParameter,$arr);
           return $resParameter;
       }else{
           return $arr;
       }
   } 
   
   public function getCommunityTreeData()
    {
        $branch_id = 1; //默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
        $branch = Branch::model()->findByPk($branch_id);
        return $branch->getRegionCommunityRelation($this->id, 'SetableCls', false);
    }
   
  

}
