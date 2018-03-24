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
class SetableSmallLoans extends CActiveRecord {

    public $modelName = 'v2.3应用资源';
    public $completeURL;            //完整URL地址 = 入口地址 + 参数    

    const AUDIT_NOT = 0;    //未审核
    const AUDIT_YES = 1;    //已审核
    const TYPE_WEB = 0;     //类型：网站
    const TYPE_MOBILE = 1;   //类型：手机
    const STATE_YES = 0;      //启用
    const STATE_NO = 1;       //禁用

    public $logoFile;
    public $community_ids = array();
    private $_customer = array();

    public function getTypeName() {
        switch ($this->type) {
            case self::TYPE_WEB:
                return "网站";
                break;
            case self::TYPE_MOBILE:
                return "手机";
                break;
        }
    }

    public function getAuditName() {
        switch ($this->audit) {
            case self::AUDIT_NOT:
                return '未审核';
                break;
            case self::AUDIT_YES:
                return '已审核';
                break;
        }
    }

    public function getStateName() {
        switch ($this->state) {
            case self::STATE_YES:
                return "启用";
                break;
            case self::STATE_NO:
                return "禁用";
                break;
        }
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'setable_small_loans';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, company, website, audit, class_id, version, type, url, state, create_time, sl_key, sort, order_by, act, proto_android, proto_ios', 'required', 'on' => 'update,create'),
            array('secret, class_id', 'safe', 'on' => 'update,create'),
            array('id, name, logo, company, website, audit, version, type, url, parameter, state, create_time, sl_key, secret, sort, order_by', 'safe', 'on' => 'search'),
            array('sl_key', 'unique', 'on' => 'update,create'),
            array('community_ids,parameter,logo,logoFile', 'safe', 'on' => 'create,update'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'setableSmallLoansCls' => array(self::BELONGS_TO, 'SetableSmallLoansCls', 'class_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
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
            'url' => '调用URL地址',
            'parameter' => '参数',
            'state' => '状态',
            'create_time' => '创建时间',
            'sl_key' => '唯一键',
            'secret' => '密钥',
            'sort' => '显示排序',
            'order_by' => '优先级',
            'community_ids' => '服务范围',
            'proto_android' => '安卓',
            'proto_ios' => '果机',
            'act' => '应用url/原型proto',
            'is_show' => '是否显示',
            'class_id' => '分组',
        );
    }

    /*
     * 数组转化为字符串
     */

    private function arrayToString($array = null) {
        $str = '';
        if ($array) {
            foreach ($array as $k => $v) {
                if (empty($v))
                    $v = '';
                $str .= "&{$k}={$v}";
            }
            $str = trim($str, '&');
        }
        return $str;
    }

    /*
     * $key 可以为 密钥字符串或id值
     * @param $key 唯一码
     * @param $type 已停用 传1
     * @param $customer_id 默认为0时获取session中的用户登录id
     * @param $checkCommunity 默认为true 是否要进行小区判断
     * @return Aarray()
     */

    public function searchByIdAndType($key, $type = 1, $customer_id = 0, $checkCommunity = true) {
        if (preg_replace("/[0-9 ]/i", '', $key)) {
            $info = self::model()->find('sl_key=:sl_key', array(':sl_key' => $key)); //,' and type=:type:type'=>$type
        } else {
            $info = self::model()->find('id=:id', array(':id' => $key));
            $key = $info->sl_key;
        }

        if (empty($customer_id))
            $customer_id = Yii::app()->user->id;

        if (empty($this->_customer)){
            $customer = Customer::model()->findByPk($customer_id);
            $this->_customer = $customer;
        } else {
            $customer = $this->_customer;
        }

        /* if(!$customer){
          $customer = Customer::model()->findByPk($customer_id);
          } else */
        if (!$info || !$customer) {
            return false;
        }//if ($key == 'GroupPurchase') exit('ok');

        $secret = "DJKC#$%CD%des$";
        //判断该应用是否配置了小区关联
        if ($checkCommunity == true) {
            $relation = SetableSmallLoansCommunityRelation::model()->findAll('small_loans_id=:small_loans_id', array(':small_loans_id' => $info->id));
            if ($relation) {
                //判断该业主所在的小区是否显示该应用
                $c_isOk = false;
                foreach ($relation as $k_comu => $v_comu) {
                    if ($v_comu->community_id == $customer->community_id) {
                        $c_isOk = true;
                        break;
                    }
                }
                if ($c_isOk == false)
                    return false;
            }
        }
        if ($info && $info->audit == self::AUDIT_YES && $info->state == self::STATE_YES) {
            //var_dump(345);die;
            $arrParameter = CJSON::decode($info->parameter);
            if ($customer) {
                $argument = "";
                $str = "";
                if (is_array($arrParameter)) {
                    foreach ($arrParameter as $_k => $_v) {
                        $argument.=$_k . "=" . $_v . "&";
                        $str.=$_k . $_v;
                    }
                }
                $encode_username = urlencode($customer->username);
                $argument .= "userid=" . $customer->id . "&username=" . $encode_username . "&mobile=" . $customer->mobile . "&password=" . md5($customer->id) . "&cid=" . $customer->community_id;
                $str .= "userid" . $customer->id . "username" . $encode_username . "mobile" . $customer->mobile . "password" . md5($customer->id) . "cid" . $customer->community_id;
                if ($key == "LICAIYI" || $key == "XIAODAI") {
                    $inviteModel = Invite::model()->find("mobile=:mobile and status = 1 and model='customer'", array(':mobile' => $customer->mobile));
                    if ($inviteModel) {
                        $argument .= "&tjrid=" . $inviteModel->customer_id;
                        $str .= "tjrid" . $inviteModel->customer_id;
                    } else {
                        $argument .= "&tjrid=";
                        $str .= "tjrid";
                    }
                    $branchName = $customer->community->branch->parent->parent;
                    if (empty($branchName)) {
                        $argument .= "&branchName=";
                        $str .= "branchName";
                    } else {
                        $argument .= "&branchName=" . urlencode(trim($branchName->name));
                        $str .= "branchName" . trim($branchName->name);
                    }
                }
                if ($key == "MIANYONGZUFANG") {
                    $str = "userid" . $customer->id . "username" . $encode_username . "realname" . $customer->name . "mobile" . $customer->mobile . "password" . md5($customer->id) . "cid" . $customer->community_id;
                    $argument = "userid=" . $customer->id . "&username=" . $encode_username . "&realname=" . urlencode($customer->name) . "&mobile=" . $customer->mobile . "&password=" . md5($customer->id) . "&cid=" . $customer->community_id;
                }
                $argument .= "&cname=" . urlencode($customer->CommunityName) . "&caddress=" . urlencode($customer->CommunityAddress);
                $str .= "cname" . $customer->CommunityName . "caddress" . $customer->CommunityAddress;

                if ($key == "EZHUANGXIU") {
                    $str = "userid" . $customer->id . "username" . $encode_username . "mobile" . $customer->mobile . "password" . md5($customer->id) . "cid" . $customer->community_id . "cname" . urlencode($customer->CommunityName) . "caddress" . urlencode($customer->CommunityAddress . "-" . $customer->BuildName . "-" . $customer->room) . "realname" . $customer->name;
                    $argument = "userid=" . $customer->id . "&username=" . $encode_username . "&mobile=" . $customer->mobile . "&password=" . md5($customer->id) . "&cid=" . $customer->community_id . "&cname=" . urlencode($customer->CommunityName) . "&caddress=" . urlencode($customer->CommunityAddress . "-" . $customer->BuildName . "-" . $customer->room) . "&realname=" . urlencode($customer->name);
                }

                if ($key == "MIANYONGZUFANG") {
                    $argument .= "&create_time=" . $customer->create_time;
                    $str .= "create_time" . $customer->create_time;
                }
                if ($key == "EDAIJIA") {
                    $info->completeURL = $info->url;
                } else {
                    $sign = md5($secret . $str . $secret);
                    $info->completeURL = $info->url . "?" . $argument . "&sign=" . strtoupper($sign);
                }

                // $serUrl = 'http://www.5ker.com:6888/';
                $serUrl = F::getHomeUrl();
                // var_dump(1233);die;
                if ($key == "PJJY") {
                    $info->completeURL = $serUrl . 'examine/1?cust_id=' . $customer_id;
                } elseif ($key == "CAIPIAO") {
                    //$info->completeURL .= "&channel=30007#colourlife/index"; 
                    $userId = $customer->id;
                    $merchant_uid = '113912012csh'; //100005
                    $userName = $encode_username;
                    $NickName = !empty($customer->name) ? urlencode($customer->name) : urlencode('访客');
                    $mobile = $customer->mobile;
                    //$key = '1241631csh';
                    // $md5 = $userId.$merchant_uid.$key;
                    $md5 = $userId . $merchant_uid . $info->secret;
                    $sign = md5($md5);
                    $info->completeURL = $info->url . "?userId=" . $userId . "&merchant_uid=" . $merchant_uid . "&userName=" . $userName . "&NickName=" . $NickName . "&mobile=" . $mobile . "&sign=" . $sign;
                } elseif ($key == "PANGLICAIPIAO") {
                    $userId = $customer->id;
                    $merchant_uid = '113912012csh'; //100005
                    $userName = $encode_username;
                    $NickName = !empty($customer->name) ? urlencode($customer->name) : urlencode('访客');
                    $mobile = $customer->mobile;
                    //$key = '1241631csh';
                    // $md5 = $userId.$merchant_uid.$key;
                    $md5 = $userId . $merchant_uid . $info->secret;
                    $sign = md5($md5);
                    $info->completeURL = $info->url . "?userId=" . $userId . "&merchant_uid=" . $merchant_uid . "&userName=" . $userName . "&NickName=" . $NickName . "&mobile=" . $mobile . "&sign=" . $sign;
                } elseif ($key == 'sfheike') {
                    //嘿客 20150305 add
                    $mobile = $customer->mobile;
                    $secret = $info->secret;
                    $sign = "secret={$secret}&mobile={$mobile}"; 
                    $sign = md5($sign);
                    $info->completeURL = $info->url . '?mobile=' . $mobile . '&sign=' . $sign;
                } elseif ($key == 'GoodLuckEveryDay') {
                    $info->completeURL = $serUrl . 'luckyApp?cust_id=' . $customer_id;
                } elseif ($key == 'GoodLuckEveryDay1') {  //广告	
                    $info->completeURL = $serUrl . 'luckyApp/xingrenyouli?cust_id=' . $customer_id;
                } elseif ($key == 'ToFriend') {
                    $info->completeURL = $serUrl . 'ToFriend?userid=' . $customer_id;
                } elseif ($key == 'SSY'){ //双十一活动
                    $info->completeURL = $serUrl . 'luckyApp/SSYView?cust_id=' . $customer_id;
                } elseif ($key == 'QiCai') {
                    $info->completeURL = $serUrl . 'QiCai?userid=' . $customer_id;
                }elseif ($key == 'HuaMei') {
                    $info->completeURL = $serUrl . 'huaMeiDa?userid=' . $customer_id;
                }elseif ($key == 'uncase') {
                    $info->completeURL = $serUrl . 'UnCase?userid=' . $customer_id;
                } elseif(($key == 'weinizhifu')){
                    $info->completeURL = $serUrl . 'UnCase/WeiNiZhiFu?userid=' . $customer_id;
                } elseif ($key == 'regombg') { //广告
                    $info->completeURL = $serUrl . 'luckyApp/?cust_id=' . $customer_id;
                } elseif ($key == 'ThanksgivingActivity') { //630感恩大促
                    $info->completeURL = $serUrl . 'thanksgivingActivity/?cust_id=' . $customer_id;
                }elseif ($key == 'FengActivity') { //一起蜂一夏
                    $info->completeURL = $serUrl . 'activity/feng?cust_id=' . $customer_id;
                }elseif ($key == 'QixiActivity') { //七夕狂欢
                    $info->completeURL = $serUrl . 'activity/qixi?cust_id=' . $customer_id;
                }elseif ($key == 'ZhongqiuActivity') { //中秋月饼
                        $info->completeURL = $serUrl . 'RobMoonCakes/?cust_id=' . $customer_id;
                }elseif ($key == 'inviteActivity') { //云海天城世家注册送
                    $info->completeURL = $serUrl . 'activity/inviteIndex?cust_id=' . $customer_id;
                } elseif ($key == 'huaMeiDa') { //广告huaMeiDa
                    $info->completeURL = $serUrl . 'milk/huaMeiDa?cust_id=' . $customer_id;
                } elseif ($key == 'V23Visit') {
                    $info->completeURL = $serUrl . 'Visit/?userid=' . $customer_id;
                } elseif ($key == 'SpecialTopic') {
                    $info->completeURL = $serUrl . 'luckyApp/SpecialTopic?cust_id=' . $customer_id;
                } elseif ($key == 'newMilk') {
                    $info->completeURL = $serUrl . 'ingMilk?cust_id=' . $customer_id;
                } elseif ($key == 'colourlife0805Act') {
                    $info->completeURL = $serUrl . 'colourlife0805Act?cust_id=' . $customer_id;
                } elseif ($key == 'youMi') {//油米
                    $info->completeURL = $serUrl . 'milk?cust_id=' . $customer_id;
                } elseif ($key == 'wifiApp') {
                    $info->completeURL = $serUrl . 'wifiApp?userid=' . $customer_id;
                }elseif ($key == 'fenxiang') {
                    $info->completeURL = $serUrl . 'fruit/show';
                }elseif ($key == 'luckyAppmainIndex') {
                    $info->completeURL = $serUrl . 'luckyApp/mainIndex?cust_id=' . $customer_id;
                } elseif ($key == 'newInvite') {
                    $info->completeURL = $serUrl . 'ingInvite?cust_id=' . $customer_id;
                } elseif ($key == 'ingInvite0716Act') {
                    $info->completeURL = $serUrl . 'inviteRegister/invite?cust_id=' . $customer_id;
                } elseif ($key == 'operators0713act') {
                    $info->completeURL = $serUrl . 'operators0713act?cust_id=' . $customer_id;
                } elseif ($key == 'newPurchase') {
                    $info->completeURL = $serUrl . 'luckyApp/newPurchase?cust_id=' . $customer_id; 
                } elseif ($key == 'newExamine') {
                    $info->completeURL = $serUrl . 'newExamine?cust_id=' . $customer_id; 
                } elseif ($key == 'djhIndex') {
                    $info->completeURL = $serUrl . 'luckyApp/djhIndex?cust_id=' . $customer_id; 
                } elseif ($key == 'robRiceDumplings') {
                    $info->completeURL = $serUrl . 'robRiceDumplings?cust_id=' . $customer_id; 
                } elseif ($key == 'robLitChi') {
                    $info->completeURL = $serUrl . 'robLitChi/litChiInvite?cust_id=' . $customer_id; 
                } elseif ($key == 'YiNengYuan') {
                    // var_dump(1213);die;
                    $info->completeURL = $serUrl . 'YiNengYuan/Buy?cust_id=' . $customer_id;

                } elseif ($key == 'YuanQuOpen') {
                    $info->completeURL = $serUrl . 'Yuanqu/Open';
                } elseif ($key == 'sudiyi') {
                    //速递易
                    $mobile = $customer->mobile;
                    $secret = $info->secret;
                    $sign = md5($mobile . '&||&' . $secret);
                    $info->completeURL = $info->url . "/?mobile={$mobile}&sign={$sign}";
                } elseif ($key == 'eIntake') {//E入伙
                    $bsecret = $info->secret;
                    $mobile = $customer->mobile;
                    //$mobile = '13823288263';
                    $coding = $customer->community->domain;
                    //$coding = 'ZG-CWCS';
                    $url = $info->url;
                    $sign = $bsecret . '||' . $mobile . '||' . $coding;
                    $sign = strtolower(md5($sign));
                    $info->completeURL = $url . '/?mobile=' . $mobile . '&coding=' . $coding . '&sign=' . $sign.'&community_id='.$customer->community_id;
                    //$info->completeURL = $serUrl . 'wifiApp?userid=' . $customer_id;
                } elseif ($key == 'jiafang'
                    || $key == 'busOnline' || $key == 'efund'
                    || $key == 'xyd' || $key == 'linli'
                    || $key == 'market' || $key == 'anshi'
                    || $key == 'jd' || $key == 'daytuan'
                    || $key == 'market1' || $key == 'oneyuan'
                    || $key == 'PAIXIANSHENGXIAN' || $key == 'czz'
                    || $key == 'chedada' || $key=='rongxin'
                    || $key == 'newMobileRecharge' || $key=='qunina'
                    || $key == 'shengxinsudi' || $key=='jdyb'
                    || $key == 'dazhaxie' || $key=='anshifengmi'
                    || $key == 'mihoutao' || $key=='tousubaoxiu'
                    || $key == 'pzCarManager' || $key=='hualiwang'
                    || $key=='ebaojie' || $key== 'jieyouhs1204'
                    || $key == 'dongxing' || $key=='taipingyang'
                    || $key == 'etousu' || $key == 'xingchenjq'
                    || $key == 'ebaojie' || $key == 'tousubaoxiu'
                    || $key == 'EWEIXIU' || $key == 'cailvyou'
                    || $key == 'Eshifukongtiao' || $key == 'food'
                    || $key == 'huanqiujingxuanH5' || $key == 'qianxizhixing'
                    || $key == 'hehuayidai'

                ) {

                    $bno = 'test';
                    $bsecret = 'abcd';
                    if($key=='chedada'){
                        $bno='1500001';
                        $bsecret='5982c68892cdc8c9d5e3742a745780b4';
                    }
                    if($key=='qunina'){
                        $bno='1500001';
                        $bsecret='5982c68892cdc8c9d5e3742a745780b4';
                    }
                    if($key=='rongxin'){
                        $bno='rongxinhui';
                        $bsecret='Y72LgpmsqF8E4O3i';
                    }
                    if($key == 'hehuayidai'){
                        $bno = 'f3850f57d484471bad802917bb0b7cf420160623';
                        $bsecret = 'cfbhuayijiecsh20160623';
                    }

                    $userid = $customer->id;
                    $mobile = $customer->mobile;
                    $username = empty($customer->name) ? $mobile : $customer->name;
                    $password = $customer->password;
                    $cid = $customer->community_id;
                    if ($cid) {
                        //地址
                        $cname = $customer->build->name; //'彩科彩悦大厦'
                        $community_name = $customer->community->name;
                        if ($customer->regions) {
                            $caddress = '';
                            foreach ($customer->regions as $k => $v) {
                                $caddress .= $v->name . '-';
                            }
                            $caddress = $caddress . '-' . $community_name . '-' . $cname;
                        } else
                            $caddress = $community_name . '-' . $cname;
                        if (!$caddress)
                            $caddress = '测试区';
                        $sec = $info->secret; //'DJKC#$%CD%des$'
                        $sign = $sec . 'bno' . $bno . 'bsecret' . $bsecret . 'userid' . $userid . 'username' . $username . 'mobile' . $mobile . 'password' . $password . 'cid' . $cid . 'cname' . $community_name . 'caddress' . $caddress . $sec;
                        $sign = strtoupper(md5($sign));

                        $url = array(
                            'bno' => $bno,
                            'bsecret' => $bsecret,
                            'userid' => $userid,
                            'username' => urlencode($username),
                            'mobile' => $mobile,
                            'password' => $password,
                            'cid' => $cid,
                            'cname' => urlencode($community_name),//urlencode($cname),
                            'caddress' => urlencode($caddress),
                            'sign' => $sign
                        );
                        $url = $this->arrayToString($url);
                        $port = $_SERVER["SERVER_PORT"];
                        if ($port != 80)
                            $port = ':' . $port;
                        else
                            $port = '';
                        //组装地址
                        $name = explode('.', $_SERVER['SERVER_NAME']);
                        array_shift($name);
                        $key = str_replace('1', '', $key);
                        //派鲜
                        if ($info->website){
                            $http = $info->website . '?' . $url;
                        }elseif($key=='jdyb'){
                            $key="jd";
                            $http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url."&id=210";
                        }elseif($key=='anshifengmi'){
                            $key="anshi";
                            $http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url."&pid=20888";
                        }elseif($key=='mihoutao'){
                            $key="daytuan";
                            $http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url."&pid=1355";
                        }
                        else{
                            $http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url;
                        }
                            
                        $info->completeURL = $http;
                    } else {
                        $info->completeURL = $serUrl . 'pagePromptApp/NotCid';
                    }
                } elseif ($key == 'zhici') {
                    $info->completeURL = $serUrl . 'zhiciApp';
                }elseif ($key == 'hybx'){
                	$info->completeURL = 'http://m.hehenian.com/page/h5/insurance/index.html?channel=0';
                }elseif ($key=='ganenpraise'){
                	 $info->completeURL = $serUrl . 'NewThanksgiving/Announce?cust_id=' . $customer_id;
                }elseif ($key=='fruitCarnival'){
                	$info->completeURL = $serUrl . 'FruitCarnival?cust_id=' . $customer_id;
                }elseif ($key=='warmPurse'){
                	$info->completeURL = $serUrl . 'WarmPurse?cust_id=' . $customer_id;
                }elseif($key=='Etousu'){
                    $info->completeURL = $serUrl . 'Etousu?cust_id=' . $customer_id;
                }elseif ($key=='mealTicket'){
                    $url = F::getMUrl().'mealTicket';
                    $loginKey = 'SD&^)#@LDCsrS';
                    $para = array(
                        'customer_id' => $customer_id,
                        'ts' => time(),
                    );
                    $signPara = F::paraFilter($para);
                    $signPara = F::argSort($signPara);

                    $signStr = F::createLinkString($signPara).'&key='.$loginKey;

                    $para['sign'] = strtoupper(md5($signStr));

                    $info->completeURL = Yii::app()->curl->buildUrl($url, $para);
                }elseif ($key=='nianhuo'){
                	$info->completeURL = $serUrl . 'NianHuo?cust_id=' . $customer_id;
                }elseif ($key=='lanternFestival'){
                	$info->completeURL = $serUrl . 'lanternFestival?cust_id=' . $customer_id;
                }elseif ($key=='baoxiang'){
                	$info->completeURL = $serUrl . 'baoXiang?cust_id=' . $customer_id;
                }elseif ($key=='zhishujie'){
                	$info->completeURL = $serUrl . 'ZhiShu/Ling?cust_id=' . $customer_id;
                }elseif ($key == 'activity') {  //所有活动的配置链接都走这里来，不用硬代码配置
                    $secret = $info->secret;
                    $userID = $customer_id * 778 + 1778;
                    $para = array(
                        'user_id' => $userID,
                        'request_time' => time()
                    );
                    $sign = new Sign($secret);
                    $para['sign'] = $sign->makeSign($para);
                    $info->completeURL = $info->url . '?' . $sign->createLinkString($para);
                }
                return $info;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    //APP旧版请求第三方应用接口
    //APP旧版请求第三方应用接口
    public function searchByPhone($actProto = null, $customer_id = 0, $version = 0) {
        if (empty($customer_id))
            $customer_id = Yii::app()->user->id;
        $customer = Customer::model()->findByPk($customer_id);
        $this->_customer = $customer;
        $moreArr = array();
        if ($customer) {
            $criteria = new CDbCriteria();
            $criteria->condition = 'state = 0 and class_id !=0'; //  type = 1 and
            $criteria->compare("community_type",1);
            $criteria->order = ' class_id asc, sort asc'; //, order_by desc
            $models = self::model()->findAll($criteria);
            $re_url_s = F::getStaticsUrl('/common/');
            $re_url_u = F::getUploadsPath('/');
            $re_url_u = str_replace('\/../..', '', $re_url_u);
            $noPic = $re_url_s . '/images/nopic.png';



            foreach ($models as $_v) {

                // E停车 兼容2015-08-31 之前版本 Joy
                if (0 === $version && $_v->id == 112) continue;

                $code = $_v->class_id - 1;
                //地址
                if (empty($_v->secret)) {
                    $completeURL = $_v->url;

                    //TODO 跳转原生 限制部分小区  joy 2015-08-31
                    $relation = SetableSmallLoansCommunityRelation::model()->findAll('small_loans_id=:small_loans_id', array(':small_loans_id' => $_v->id));
                    if ($relation) {
                        //判断该业主所在的小区是否显示该应用
                        $c_isOk = false;
                        foreach ($relation as $k_comu => $v_comu) {
                            if ($v_comu->community_id == $customer->community_id) {
                                $c_isOk = true;
                                break;
                            }
                        }
                        if ($c_isOk == false)
                            continue;
                    }

                } else {
                    $result = $this->searchByIdAndType($_v->id, 1);//$_v->sl_key
                    if (empty($result->completeURL))
                        continue;
                    $completeURL = $result->completeURL;
                }
                // if($model1){
                $url = $completeURL;
                $name = $_v->name;
                //$info = $_v->info;
                $act = $_v->act;
                $proto = $_v->{$actProto};
                $img = $_v->logo;
                $des = $_v->des;
                $id = $_v->id;
                $end_time = trim($_v->end_time);
                $tipUsed = (int) $_v->tips_used;
                if ($end_time) {
                    //时间转换
                    $time = strtotime($end_time);
                    if (!$time) {
                        $time = $end_time;
                    }
                    if ($time >= time()) {
                        $tipUsed = 1;
                    } else
                        $tipUsed = 0;
                }
                $topUsed = $_v->top_used;
                $topImage = $_v->top_image;
                if (strstr($topImage, 'v23')) {
                    $topImage = $re_url_s . $topImage;
                } elseif ($topImage) {
                    $topImage = $re_url_u . $topImage;
                }
                $tipsImage = $_v->tips_image;
                if (strstr($tipsImage, 'v23')) {
                    $tipsImage = $re_url_s . $tipsImage;
                } else if ($tipsImage) {
                    $tipsImage = $re_url_u . $tipsImage;
                }
                $des = $_v->des;
                //图片
                if (!$img)
                    $img = $noPic;
                else {
                    if (strstr($img, 'v23'))
                        $img = F::getStaticsUrl('/common/' . $img, '/common/images/nopic.png');
                    else
                        $img = Yii::app()->imageFile->getUrl($img);
                }

                if (empty($moreArr[$code])) {
                    $moreArr[$code] = array(
                        'name' => $_v->setableSmallLoansCls->name
                    );
                }

                // if (!$act || !$proto) continue;
                $moreArr[$code]['attr'][] = array(
                    'id' => $id,
                    'act' => $act,
                    'proto' => $proto,
                    'url' => $url,
                    'name' => $name,
                    'img' => $img,
                    'topUsed' => $topUsed,
                    'topImage' => $topImage,
                    'tipUsed' => $tipUsed,
                    'tipsImage' => $tipsImage,
                    'des' => $des
                );
            }
            // }
        }
        return $moreArr;
    }

    //园区版
    public function searchByCompanyPhone($actProto = null,$community_id=0,$label_id=0) {

        $customer_id = Yii::app()->user->id;
        $customer = Customer::model()->findByPk($customer_id);
        $this->_customer = $customer;
        $moreArr = array();
        if ($customer) {
            $criteria = new CDbCriteria();
            $criteria->condition = 'state = 0 and class_id !=0'; //  type = 1 and
            $criteria->compare('label_id',$label_id);
            $criteria->compare('community_type',2);
            $criteria->order = ' class_id asc, sort asc'; //, order_by desc
            $models = self::model()->findAll($criteria);
            $re_url_s = F::getStaticsUrl('/common/');
            $re_url_u = F::getUploadsPath('/');
            $re_url_u = str_replace('\/../..', '', $re_url_u);
            $noPic = $re_url_s . '/images/nopic.png';

            foreach ($models as $_v) {

                $code = $_v->class_id - 1;
                //地址
                if (empty($_v->secret) || $_v == 'proto') {
                    $completeURL = $_v->url;

                    //TODO 跳转原生 限制部分小区  joy 2015-08-31
                    $relation = SetableSmallLoansCommunityRelation::model()->findAll('small_loans_id=:small_loans_id AND community_id=:community_id', array(':small_loans_id' => $_v->id,":community_id"=>$community_id));
                    if ($relation) {
                        //判断该业主所在的小区是否显示该应用
                        $c_isOk = false;
                        foreach ($relation as $k_comu => $v_comu) {
                            if ($v_comu->community_id == $customer->community_id) {
                                $c_isOk = true;
                                break;
                            }
                        }
                        if ($c_isOk == false)
                            continue;
                    }

                } else {
                    $result = $this->searchByIdAndType($_v->id, 1);//$_v->sl_key
                    if (empty($result->completeURL))
                        continue;
                    $completeURL = $result->completeURL;
                }
                // if($model1){
                $url = $completeURL;
                $name = $_v->name;
                //$info = $_v->info;
                $act = $_v->act;
                $proto = $_v->{$actProto};
                $img = $_v->logo;
                $des = $_v->des;
                $id = $_v->id;
                $end_time = trim($_v->end_time);
                $tipUsed = (int) $_v->tips_used;
                if ($end_time) {
                    //时间转换
                    $time = strtotime($end_time);
                    if (!$time) {
                        $time = $end_time;
                    }
                    if ($time >= time()) {
                        $tipUsed = 1;
                    } else
                        $tipUsed = 0;
                }
                $topUsed = $_v->top_used;
                $topImage = $_v->top_image;
                if (strstr($topImage, 'v23')) {
                    $topImage = $re_url_s . $topImage;
                } elseif ($topImage) {
                    $topImage = $re_url_u . $topImage;
                }
                $tipsImage = $_v->tips_image;
                if (strstr($tipsImage, 'v23')) {
                    $tipsImage = $re_url_s . $tipsImage;
                } else if ($tipsImage) {
                    $tipsImage = $re_url_u . $tipsImage;
                }
                $des = $_v->des;
                //图片
                if (!$img)
                    $img = $noPic;
                else {
                    if (strstr($img, 'v23'))
                        $img = F::getStaticsUrl('/common/' . $img, '/common/images/nopic.png');
                    else
                        $img = Yii::app()->imageFile->getUrl($img);
                }

                if (empty($moreArr[$code])) {
                    $moreArr[$code] = array(
                        'name' => $_v->setableSmallLoansCls->name
                    );
                }

                // if (!$act || !$proto) continue;
                $moreArr[$code]['attr'][] = array(
                    'id' => $id,
                    'act' => $act,
                    'proto' => $proto,
                    'url' => $url,
                    'name' => $name,
                    'img' => $img,
                    'topUsed' => $topUsed,
                    'topImage' => $topImage,
                    'tipUsed' => $tipUsed,
                    'tipsImage' => $tipsImage,
                    'des' => $des
                );
            }
            // }
        }
        return $moreArr;
    }

    /*
     * 取得启动图片
     * 20150316
     */
    public function getStartImg($id) {
        if (empty($id))
            return array();
        return self::model()->findByPk($id);
    }

    /**
     * 根据sl_key判断是否启用
     */
    public function isAble($sl_key) {
        $info = self::model()->find('sl_key=:sl_key', array(':sl_key' => $sl_key));
        if ($info && $info->state == self::STATE_YES) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 判断是不事原型/WEB地址
     */

    public function isPorto() {
        if ($this->act == 'url')
            return '应用URL';
        else
            return '手机原型';
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->order = 'class_id desc';
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors() {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    public function getLogoImgUrl() {
        //return Yii::app()->ajaxUploadImage->getUrl($this->logo);
        $res = new PublicFunV23();
        $url = $res->setAbleUploadImg($this->logo);
        return $url;
    }

    public function getClsName() {
        if ($this->setableSmallLoansCls)
            return $this->setableSmallLoansCls->name;
        else
            return '不在首页显示';
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave() {
        if (!empty($this->logoFile)) {
            $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
        }
        return parent::beforeSave();
    }

    //增加参数
    public function addParam($k, $v) {
        $arr = array();
        $arr[$k] = $v;
        if ($this->parameter) {
            $arrParameter = CJSON::decode($this->parameter);
            $resParameter = array_merge($arrParameter, $arr);
            return $resParameter;
        } else {
            return $arr;
        }
    }

    public function getCommunityTreeData() {
        $branch_id = 1; //默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
        $branch = Branch::model()->findByPk($branch_id);
        return $branch->getRegionCommunityRelation($this->id, 'SetableSmallLoans', false);
    }

}
