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
class SmallLoans extends CActiveRecord
{
    public $modelName = '第三方应用';
    
    public $completeURL;            //完整URL地址 = 入口地址 + 参数    

    const AUDIT_NOT = 0;    //未审核
    const AUDIT_YES = 1;    //已审核
    
    const TYPE_WEB = 0;     //类型：网站
    const TYPE_MOBILE = 1;   //类型：手机

    const STATE_YES = 0;      //启用
    const STATE_NO = 1;       //禁用
    
    public $logoFile;
    
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
        return 'small_loans';
    }

    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, company, website, audit, version, type, url, state, create_time, sl_key, sort, order_by', 'required', 'on' => 'update,create'),
            array('secret', 'safe', 'on' => 'update,create'),
            array('id, name, logo, company, website, audit, version, type, url, parameter, state, create_time, sl_key, secret, sort, order_by', 'safe', 'on' => 'search'),
            array('sl_key','unique','on'=>'update,create'),
            array('community_ids,parameter,logo,logoFile','safe', 'on' => 'create,update'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '名称',
            'logo' => 'LOGO',
            'logoFile' => 'LOGO',
            'company' => '公司名称',
            'website' => '官网',
            'audit' => '审核',
            'version' => '版本',
            'type' => '类型',
            'url' => '入口地址',
            'parameter' => '参数',
            'state' => '状态',
            'create_time' => '创建时间',
            'sl_key' => '唯一键',
            'secret' => '密钥',
            'sort' => '页面排序',
            'order_by' => '优先级',
            'community_ids' => '服务范围',
            'is_show'=>'是否显示',
        );
    }
    
    public function searchByIdAndType($key , $type , $customer_id = 0){
        $info = self::model()->find('sl_key=:sl_key and type=:type',array(':sl_key'=>$key,':type'=>$type));
        $customer = Customer::model()->findByPk(Yii::app()->user->id);
        if(!$customer){
            $customer = Customer::model()->findByPk($customer_id);
        }
        if(!$info || !$customer){
            return false;
        }
        $secret = "DJKC#$%CD%des$";
        //判断该应用是否配置了小区关联
        $relation = SmallLoansCommunityRelation::model()->findAll('small_loans_id=:small_loans_id',array(':small_loans_id'=>$info->id));
        if($relation){
            //判断该业主所在的小区是否显示该应用
            $customer_relation = SmallLoansCommunityRelation::model()->find('small_loans_id=:small_loans_id and community_id=:community_id',
                    array(':small_loans_id'=>$info->id, ':community_id'=>$customer->community_id));
            if(!$customer_relation){
                return false;
            }            
        }
        if($info && $info->audit == self::AUDIT_YES && $info->state == self::STATE_YES){            
            $arrParameter = CJSON::decode($info->parameter);
            if($customer){
                $argument = "";
                $str = "";
                if(is_array($arrParameter)){
                    foreach($arrParameter as $_k=>$_v){
                        $argument.=$_k."=".$_v."&";
                        $str.=$_k.$_v;
                    }
                }
                $argument .= "userid=".$customer->id."&username=".$customer->username."&mobile=".$customer->mobile."&password=".md5($customer->id)."&cid=".$customer->community_id;
                $str .= "userid".$customer->id."username".$customer->username."mobile".$customer->mobile."password".md5($customer->id)."cid".$customer->community_id;
                if($key == "LICAIYI" || $key == "XIAODAI"){
                    $inviteModel = Invite::model()->find("mobile=:mobile and status = 1 and model='customer'",array(':mobile'=>$customer->mobile));
                    if($inviteModel){
                        $argument .= "&tjrid=".$inviteModel->customer_id;
                        $str .= "tjrid".$inviteModel->customer_id;
                    }else{
                        $argument .= "&tjrid=";
                        $str .= "tjrid";
                    }
                    $branchName=$customer->community->branch->parent->parent;
                    if(empty($branchName)){
                        $argument .= "&branchName=";
                        $str .= "branchName";
                    }else{
                        $argument .= "&branchName=".urlencode(trim($branchName->name));
                        $str .= "branchName".trim($branchName->name);
                    }

                }
                if($key == "MIANYONGZUFANG"){
                    $str = "userid".$customer->id."username".$customer->username."realname".$customer->name."mobile".$customer->mobile."password".md5($customer->id)."cid".$customer->community_id;
                    $argument = "userid=".$customer->id."&username=".$customer->username."&realname=".urlencode($customer->name)."&mobile=".$customer->mobile."&password=".md5($customer->id)."&cid=".$customer->community_id;
                }
                $argument .= "&cname=".urlencode($customer->CommunityName)."&caddress=".urlencode($customer->CommunityAddress);
                $str .= "cname".$customer->CommunityName."caddress".$customer->CommunityAddress;

                if($key == "EZHUANGXIU"){
                    $str = "userid".$customer->id."username".$customer->username."mobile".$customer->mobile."password".md5($customer->id)."cid".$customer->community_id."cname".urlencode($customer->CommunityName)."caddress".urlencode($customer->CommunityAddress."-".$customer->BuildName."-".$customer->room)."realname".$customer->name;
                    $argument = "userid=".$customer->id."&username=".$customer->username."&mobile=".$customer->mobile."&password=".md5($customer->id)."&cid=".$customer->community_id."&cname=".urlencode($customer->CommunityName)."&caddress=".urlencode($customer->CommunityAddress."-".$customer->BuildName."-".$customer->room)."&realname=".urlencode($customer->name);
                }



                if($key == "MIANYONGZUFANG"){
                    $argument .= "&create_time=".$customer->create_time;
                    $str .= "create_time".$customer->create_time;
                }

                if($key == "EDAIJIA"){
                    $info->completeURL = $info->url;
                }else{
                    $sign = md5($secret . $str . $secret);
                    // var_dump($sign);die;
                    $info->completeURL = $info->url."?".$argument."&sign=".strtoupper($sign);
                }

                if($key == "CAIPIAO"){
                    //$info->completeURL .= "&channel=30007#colourlife/index"; 
					$userId = $customer->id;
                    $merchant_uid = '113912012csh';//100005
                    $userName = $customer->username;
                    $NickName = !empty($customer->name)?urlencode($customer->name):urlencode('访客');
                    $mobile = $customer->mobile;
                    //$key = '1241631csh';
                    // $md5 = $userId.$merchant_uid.$key;
                    $md5 = $userId.$merchant_uid.$info->secret;
                    $sign=md5($md5);
                    $info->completeURL = $info->url."?userId=".$userId."&merchant_uid=".$merchant_uid."&userName=".$userName."&NickName=".$NickName."&mobile=".$mobile."&sign=".$sign;
					
                }


                if($key == "PANGLICAIPIAO"){
                    $userId = $customer->id;
                    $merchant_uid = '113912012csh';//100005
                    $userName = $customer->username;
                    $NickName = !empty($customer->name)?urlencode($customer->name):urlencode('访客');
                    $mobile = $customer->mobile;
                    //$key = '1241631csh';
                    // $md5 = $userId.$merchant_uid.$key;
                    $md5 = $userId.$merchant_uid.$info->secret;
                    $sign=md5($md5);
                    $info->completeURL = $info->url."?userId=".$userId."&merchant_uid=".$merchant_uid."&userName=".$userName."&NickName=".$NickName."&mobile=".$mobile."&sign=".$sign;
                }

                return $info;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    //APP旧版请求第三方应用接口
    //APP旧版请求第三方应用接口
    public function searchByPhone(){
        $customer = Customer::model()->findByPk(Yii::app()->user->id);
        if($customer){
            $criteria = new CDbCriteria() ;          
            $criteria -> condition = 'type = 1 and state = 0';  
            $criteria -> order = 'sort asc, order_by desc';
            $models = self::model()->findAll($criteria);
            $res = array();
            $res = array(
                "first"=>array(),
                "second"=>array(),
                "third"=>array(),
                "fourth"=>array(),

            );
            $pageName="";
            $n=0;
            $m=0;
            $nn=0;
            $mm=0;
            foreach($models as $_v){
                $model=self::model()->searchByIdAndTypeNew($_v->sl_key,1);
                if($model){
                    if($_v->sort==1){
                        $pageName="first";
                        $res[$pageName][$n]['cn_name'] =$model->name;
                        $res[$pageName][$n]['en_name'] =$_v->sl_key;
                        $res[$pageName][$n]['completeURL'] = $model->completeURL;
                        $res[$pageName][$n]['logo'] = $model->logo;
                        $res[$pageName][$n]['state'] = $model->state;
                        $res[$pageName][$n]['orderBy'] = $model->order_by;
                        $res[$pageName][$n]['is_show'] = $model->is_show;
                        $n++;
                    }else if($_v->sort==2){
                        $pageName="second";
                        $res[$pageName][$m]['cn_name'] =$model->name;
                        $res[$pageName][$m]['en_name'] =$_v->sl_key;
                        $res[$pageName][$m]['completeURL'] = $model->completeURL;
                        $res[$pageName][$m]['logo'] = $model->logo;
                        $res[$pageName][$m]['state'] = $model->state;
                        $res[$pageName][$m]['orderBy'] = $model->order_by;
                        $res[$pageName][$m]['is_show'] = $model->is_show;
                        $m++;
                    }else if($_v->sort==3){
                        $pageName="third";
                        $res[$pageName][$nn]['cn_name'] =$model->name;
                        $res[$pageName][$nn]['en_name'] =$_v->sl_key;
                        $res[$pageName][$nn]['completeURL'] = $model->completeURL;
                        $res[$pageName][$nn]['logo'] = $model->logo;
                        $res[$pageName][$nn]['state'] = $model->state;
                        $res[$pageName][$nn]['orderBy'] = $model->order_by;
                        $res[$pageName][$nn]['is_show'] = $model->is_show;
                        $nn++;
                    }else if($_v->sort==4){
                        $pageName="fourth";
                        $res[$pageName][$mm]['cn_name'] =$model->name;
                        $res[$pageName][$mm]['en_name'] =$_v->sl_key;
                        $res[$pageName][$mm]['completeURL'] = $model->completeURL;
                        $res[$pageName][$mm]['logo'] = $model->logo;
                        $res[$pageName][$mm]['state'] = $model->state;
                        $res[$pageName][$mm]['orderBy'] = $model->order_by;
                        $res[$pageName][$mm]['is_show'] = $model->is_show;
                        $mm++;
                    }
                }
            }
            return $res;
        }else{
            return false;
        }
    }
    
    
    
    public function searchByIdAndTypeNew($key , $type){
        $info = self::model()->find('sl_key=:sl_key and type=:type',array(':sl_key'=>$key,':type'=>$type));
        if(!$info){
            return false;
        }
        if($info && $info->audit == self::AUDIT_YES && $info->state == self::STATE_YES){            
            $customer = Customer::model()->findByPk(Yii::app()->user->id);
            if($customer){
                $resultApp = $this->searchByIdAndType($key , $type);
                if($resultApp){
                    return $resultApp;
                }else{
                    $info->completeURL = F::getHomeUrl('/application')."?key=".$key."&customer_id=".$customer->id;
                }                    
            }else{
                $info->completeURL = F::getHomeUrl('/application')."?key=".$key;
            }
            return $info;
        }else{
            return false;
        }
    }

    //APP新版请求第三方应用接口
    public function searchByPhoneNew(){
        $criteria = new CDbCriteria() ;
        $criteria -> condition = 'type = 1 and state = 0';
        $criteria -> order = 'sort asc, order_by desc';
        $models = self::model()->findAll($criteria);
        $res = array();
        $res = array(
            "first"=>array(),
            "second"=>array(),
            "third"=>array(),
            "fourth"=>array(),

        );
        $pageName="";
        $n=0;
        $m=0;
        $nn=0;
        $mm=0;
        foreach($models as $_v){
            $model=self::model()->searchByIdAndTypeNew($_v->sl_key,1);
            if($model){
                if($_v->sort==1){
                    $pageName="first";                
                    $res[$pageName][$n]['cn_name'] =$model->name;
                    $res[$pageName][$n]['en_name'] =$_v->sl_key;
                    $res[$pageName][$n]['completeURL'] = $model->completeURL;
                    $res[$pageName][$n]['logo'] = $model->logo;
                    $res[$pageName][$n]['state'] = $model->state;
                    $res[$pageName][$n]['orderBy'] = $model->order_by;
                    $res[$pageName][$n]['is_show'] = $model->is_show;                        
                    $n++;
                }else if($_v->sort==2){
                    $pageName="second";
                    $res[$pageName][$m]['cn_name'] =$model->name;
                    $res[$pageName][$m]['en_name'] =$_v->sl_key;
                    $res[$pageName][$m]['completeURL'] = $model->completeURL;
                    $res[$pageName][$m]['logo'] = $model->logo;
                    $res[$pageName][$m]['state'] = $model->state;
                    $res[$pageName][$m]['orderBy'] = $model->order_by;
                    $res[$pageName][$m]['is_show'] = $model->is_show;
                    $m++;
                }else if($_v->sort==3){
                    $pageName="third";
                    $res[$pageName][$nn]['cn_name'] =$model->name;
                    $res[$pageName][$nn]['en_name'] =$_v->sl_key;
                    $res[$pageName][$nn]['completeURL'] = $model->completeURL;
                    $res[$pageName][$nn]['logo'] = $model->logo;
                    $res[$pageName][$nn]['state'] = $model->state;
                    $res[$pageName][$nn]['orderBy'] = $model->order_by;
                    $res[$pageName][$nn]['is_show'] = $model->is_show;
                    $nn++;
                }else if($_v->sort==4){
                    $pageName="fourth";
                    $res[$pageName][$mm]['cn_name'] =$model->name;
                    $res[$pageName][$mm]['en_name'] =$_v->sl_key;
                    $res[$pageName][$mm]['completeURL'] = $model->completeURL;
                    $res[$pageName][$mm]['logo'] = $model->logo;
                    $res[$pageName][$mm]['state'] = $model->state;
                    $res[$pageName][$mm]['orderBy'] = $model->order_by;
                    $res[$pageName][$mm]['is_show'] = $model->is_show;
                    $mm++;
                }
            }
        }
        return $res;

    }
    /**
     * 根据sl_key判断是否启用
     */
    public function isAble($sl_key){
        $info = self::model()->find('sl_key=:sl_key',array(':sl_key'=>$sl_key));
        if($info && $info->state == self::STATE_YES){
            return true;
        }else{
            return false;
        }
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
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
    
    public function getLogoImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo);
    }
    
    /**
    * 处理图片
    * @return bool
    */
   protected function beforeSave()
   {
        if (!empty($this->logoFile)) {
                $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
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
        return $branch->getRegionCommunityRelation($this->id, 'SmallLoans', false);
    }
   
   
   //获取6月份活动  第三方应用（彩票）链接
   public function getGoucaiUrl(){
       if(empty($_SESSION['cust_id'])){
           return "";
       }
       $key = "CAIPIAO";
       $type = self::TYPE_MOBILE;
       $info = self::model()->find('sl_key=:sl_key and type=:type',array(':sl_key'=>$key,':type'=> $type));
        $secret = "DJKC#$%CD%des$";
        if($info && $info->audit == self::AUDIT_YES && $info->state == self::STATE_YES){
            $customer = Customer::model()->findByPk($_SESSION['cust_id']);
            $arrParameter = CJSON::decode($info->parameter);
            if($customer){
                $argument = "";
                $str = "";
                if(is_array($arrParameter)){
                    foreach($arrParameter as $_k=>$_v){
                        $argument.=$_k."=".$_v."&";
                        $str.=$_k.$_v;
                    }
                }
                $argument .= "userid=".$customer->id."&username=".$customer->username."&mobile=".$customer->mobile."&password=".md5($customer->id);
                $str .= "userid".$customer->id."username".$customer->username."mobile".$customer->mobile."password".md5($customer->id);
                if($key != "LICAIYI"){
                    $argument .= "&cid=".$customer->community_id."&cname=".$customer->CommunityName."&caddress=".$customer->CommunityAddress;
                    $str .= "cid".$customer->community_id."cname".$customer->CommunityName."caddress".$customer->CommunityAddress;
                }                
                if($key == "EDAIJIA"){
                    $info->completeURL = $info->url;
                }else{
                    $sign = md5($secret . $str . $secret);
                    $info->completeURL = $info->url."?".$argument."&sign=".strtoupper($sign);
                }
                if($key == "CAIPIAO"){
                   // $info->completeURL .= "&channel=30007#colourlife/index"; 
				   
				   
				   $userId = $customer->id;
                    $merchant_uid = '113912012csh';
                    $userName = $customer->username;
                    $NickName = !empty($customer->name)?urlencode($customer->name):urlencode('访客');
                    $mobile = $customer->mobile;
                    $key = '1241631csh';//111111augu
                    $md5 = $userId.$merchant_uid.$key;
                    $sign=md5($md5);
                    $info->completeURL = $info->url."?userId=".$userId."&merchant_uid=".$merchant_uid."&userName=".$userName."&NickName=".$NickName."&mobile=".$mobile."&sign=".$sign;
                }

                if($key == "PANGLICAIPIAO"){
                    $userId = $customer->id;
                    $merchant_uid = '113912012csh';//10005
                    $userName = $customer->username;
                    $NickName = !empty($customer->name)?urlencode($customer->name):urlencode('访客');
                    $mobile = $customer->mobile;
                    $key = '1241631csh';//111111augu
                    $md5 = $userId.$merchant_uid.$key;
                    $sign=md5($md5);
                    $info->completeURL = $info->url."?userId=".$userId."&merchant_uid=".$merchant_uid."&userName=".$userName."&NickName=".$NickName."&mobile=".$mobile."&sign=".$sign;
                }


                return $info->completeURL;
            }else{
                return false;
            }
        }else{
            return false;
        }
   }

}
