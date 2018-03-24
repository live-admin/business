<?php

/**
 * 3.0 可配置资源 //TODO:kakatool
 */
class HomeConfigResource extends CActiveRecord
{


	public $modelName = '3.0 资源';

	const AUDIT_NOT = 0;    //未审核
	const AUDIT_YES = 1;    //已审核
	const TYPE_WEB = 0;     //类型：网站
	const TYPE_MOBILE = 1;   //类型：手机
	const STATE_YES = 0;      //启用
	const STATE_NO = 1;       //禁用

	public $completeURL;            //完整URL地址 = 入口地址 + 参数

	public $logoFile;
	public $community_ids = array();
	private $_customer = array();

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('name, company, website, version, sl_key, secret, sort, act, url, native', 'required', 'on' => 'update,create'),
			array('sl_key, secret, name, url, act, native', 'required', 'on' => 'update,create'),
			array('id, name, logo, company, website, audit, version, type, url, parameter, state, create_time, sl_key, secret, sort, order_by, act, native', 'safe', 'on' => 'search'),
			array('sl_key', 'unique', 'on' => 'update,create'),
			array('parameter, logo, logoFile, secret, class_id, sl_key, secret, name, url, act, native, company, website, version, sort', 'safe', 'on' => 'create,update'),
		);
	}

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
			'native' => '协议',
		);
	}

	public function tableName()
	{
		return 'home_config_resource';
	}

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

	public function getAuditName()
	{
		switch ($this->audit) {
			case self::AUDIT_NOT:
				return '未审核';
				break;
			case self::AUDIT_YES:
				return '已审核';
				break;
		}
	}


	function getAvailableResourceByPk($resouceID = 0)
	{
		$resouce = self::model()->find('id=:id', array(':id' => $resouceID));
		if ($resouce
			&& $resouce->audit == self::AUDIT_YES
			&& $resouce->state == self::STATE_YES
		) {
			return $resouce;
		} else {
			return false;
		}
	}


	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getLogoImgUrl()
	{
		if (strstr($this->logo, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($this->logo);
		} else {
			return Yii::app()->ajaxUploadImage->getUrl($this->logo);
			//return F::getUploadsUrl('/images/' . trim($this->logo, '/'));
		}
	}


	/**
	 * 根据key或者id获取资源（key有可能重复）
	 * @param $key
	 * @param int $community_type
	 * @param int $customer_id
	 * @return array|bool|CActiveRecord|mixed|null
	 */
	public function getResourceByKeyOrId($key, $community_type = 1, $customer_id = 0)
	{
		//查找资源(id/sl_key)
		if (preg_replace("/[0-9 ]/i", '', $key)) {
			$info = self::model()->find('sl_key=:sl_key AND community_type=:community_type', array(':sl_key' => $key, ':community_type' => $community_type));
		} else {
			$info = self::model()->find('id=:id  AND community_type=:community_type', array(':id' => $key, ':community_type' => $community_type));

			if ($info)
				$key = $info->sl_key;
		}

		if (!$info)
			return false;

		$serUrl = F::getHomeUrl();
		if ($key == 'easyParking') {
			$info->completeURL = $serUrl . 'advertisement/easyParking';
			return $info;
		}
		//else if ($key == 'easyComplain') {
		//	$info->completeURL = $serUrl . 'advertisement/easyComplain';
		//	return $info;
		//}
		else if ($key == 'wealthLife') {
			$info->completeURL = $serUrl . 'advertisement/wealthLife';
			return $info;
		} else if ($key == 'easyPay') {
			$info->completeURL = $serUrl . 'advertisement/easyPay';
			return $info;
		}


//		获取用户信息(需要登录后才能获取)
		if (!$customer_id) {
			$customer_id = Yii::app()->user->id;
		}

		// 获取用户信息(需要登录后才能获取)
		if (!$customer_id)
			return false;

		$customer = Customer::model()->findByPk($customer_id);
		if (!$customer)
			return false;


		$secret = "DJKC#$%CD%des$";

		//$info && $info->audit == self::AUDIT_YES && $info->state == self::STATE_YES 这里是获取最终对接url，不需要判断状态
		if ($info) {

			$arrParameter = CJSON::decode($info->parameter);
			if ($customer) {

				//读取配置参数
				$argument = "";
				$str = "";
				if (is_array($arrParameter)) {
					foreach ($arrParameter as $_k => $_v) {
						$argument .= $_k . "=" . $_v . "&";
						$str .= $_k . $_v;
					}
				}
				$encode_username = urlencode($customer->username);
				$argument .= "userid=" . $customer->id . "&username=" . $encode_username . "&mobile=" . $customer->mobile . "&password=" . md5($customer->id) . "&cid=" . $customer->community_id;
				$str .= "userid" . $customer->id . "username" . $encode_username . "mobile" . $customer->mobile . "password" . md5($customer->id) . "cid" . $customer->community_id;


				//特殊处理（LICAIYI = 钱生花，XIAODAI=合和年理财）
				if ($key == "LICAIYI" || $key == "XIAODAI") {
					$inviteModel = Invite::model()->find("mobile=:mobile and status = 1 and model='customer'", array(':mobile' => $customer->mobile));
					if ($inviteModel) {
						$argument .= "&tjrid=" . $inviteModel->customer_id;
						$str .= "tjrid" . $inviteModel->customer_id;
					} else {
						$argument .= "&tjrid=";
						$str .= "tjrid";
					}
					$branchName = null;
					if ($customer->community && $customer->community->branch && $customer->community->branch->parent && $customer->community->branch->parent->parent) {
						$branchName = $customer->community->branch->parent->parent;

					}

					if (empty($branchName)) {
						$argument .= "&branchName=";
						$str .= "branchName";
					} else {
						$argument .= "&branchName=" . urlencode(trim($branchName->name));
						$str .= "branchName" . trim($branchName->name);
					}
				}

				//MIANYONGZUFANG = 租房
				if ($key == "MIANYONGZUFANG") {
					$str = "userid" . $customer->id . "username" . $encode_username . "realname" . $customer->name . "mobile" . $customer->mobile . "password" . md5($customer->id) . "cid" . $customer->community_id;
					$argument = "userid=" . $customer->id . "&username=" . $encode_username . "&realname=" . urlencode($customer->name) . "&mobile=" . $customer->mobile . "&password=" . md5($customer->id) . "&cid=" . $customer->community_id;
				}
				$argument .= "&cname=" . urlencode($customer->CommunityName) . "&caddress=" . urlencode($customer->CommunityAddress);
				$str .= "cname" . $customer->CommunityName . "caddress" . $customer->CommunityAddress;

				//EZHUANGXIU = E装修
				if ($key == "EZHUANGXIU") {
					$str = "userid" . $customer->id . "username" . $encode_username . "mobile" . $customer->mobile . "password" . md5($customer->id) . "cid" . $customer->community_id . "cname" . urlencode($customer->CommunityName) . "caddress" . urlencode($customer->CommunityAddress . "-" . $customer->BuildName . "-" . $customer->room) . "realname" . $customer->name;
					$argument = "userid=" . $customer->id . "&username=" . $encode_username . "&mobile=" . $customer->mobile . "&password=" . md5($customer->id) . "&cid=" . $customer->community_id . "&cname=" . urlencode($customer->CommunityName) . "&caddress=" . urlencode($customer->CommunityAddress . "-" . $customer->BuildName . "-" . $customer->room) . "&realname=" . urlencode($customer->name);
				}

				if ($key == "MIANYONGZUFANG") {
					$argument .= "&create_time=" . $customer->create_time;
					$str .= "create_time" . $customer->create_time;
				}

				//EDAIJIA=E代驾
				if ($key == "EDAIJIA") {
					$info->completeURL = $info->url;
				} else {
					$sign = md5($secret . $str . $secret);
					$info->completeURL = $info->url . "?" . $argument . "&sign=" . strtoupper($sign);
				}

				// 获取www的网址地址，不同环境网址不同。$serUrl = 'http://www.5ker.com:6888/';
				$serUrl = F::getHomeUrl();


				//PJJY = E评价
				if ($key == "PJJY") {
					$info->completeURL = $serUrl . 'examine/1?cust_id=' . $customer_id;
				} //CAIPIAO = 彩票
				elseif ($key == "CAIPIAO") {
					$userId = $customer->id;
					$merchant_uid = '113912012csh'; //100005
					$userName = $encode_username;
					$NickName = !empty($customer->name) ? urlencode($customer->name) : urlencode('访客');
					$mobile = $customer->mobile;
					$md5 = $userId . $merchant_uid . $info->secret;
					$sign = md5($md5);
					$info->completeURL = $info->url . "?userId=" . $userId . "&merchant_uid=" . $merchant_uid . "&userName=" . $userName . "&NickName=" . $NickName . "&mobile=" . $mobile . "&sign=" . $sign;
				} //PANGLICAIPIAO = 胖狸彩票
				elseif ($key == "PANGLICAIPIAO") {
					$userId = $customer->id;
					$merchant_uid = '113912012csh'; //100005
					$userName = $encode_username;
					$NickName = !empty($customer->name) ? urlencode($customer->name) : urlencode('访客');
					$mobile = $customer->mobile;
					$md5 = $userId . $merchant_uid . $info->secret;
					$sign = md5($md5);
					$info->completeURL = $info->url . "?userId=" . $userId . "&merchant_uid=" . $merchant_uid . "&userName=" . $userName . "&NickName=" . $NickName . "&mobile=" . $mobile . "&sign=" . $sign;
				} //sfheike = 顺丰嘿客
				elseif ($key == 'sfheike') {
					$mobile = $customer->mobile;
					$secret = $info->secret;
					$sign = "secret={$secret}&mobile={$mobile}";
					$sign = md5($sign);
					$info->completeURL = $info->url . '?mobile=' . $mobile . '&sign=' . $sign;
				} //GoodLuckEveryDay=天天好运
				elseif ($key == 'GoodLuckEveryDay') {
					$info->completeURL = $serUrl . 'luckyApp?cust_id=' . $customer_id;
				} //GoodLuckEveryDay1=天天好运
				elseif ($key == 'GoodLuckEveryDay1') {  //广告
					$info->completeURL = $serUrl . 'luckyApp/xingrenyouli?cust_id=' . $customer_id;
				} elseif ($key == 'ToFriend') {
					$info->completeURL = $serUrl . 'ToFriend?userid=' . $customer_id;
				} elseif ($key == 'SSY') { //双十一活动
					$info->completeURL = $serUrl . 'luckyApp/SSYView?cust_id=' . $customer_id;
				} elseif ($key == 'QiCai') {
					$info->completeURL = $serUrl . 'QiCai?userid=' . $customer_id;
				} elseif ($key == 'HuaMei') {
					$info->completeURL = $serUrl . 'huaMeiDa?userid=' . $customer_id;
				} elseif ($key == 'uncase') {
					$info->completeURL = $serUrl . 'UnCase?userid=' . $customer_id;
				} elseif (($key == 'weinizhifu')) {
					$info->completeURL = $serUrl . 'UnCase/WeiNiZhiFu?userid=' . $customer_id;
				} elseif ($key == 'regombg') { //广告
					$info->completeURL = $serUrl . 'luckyApp/?cust_id=' . $customer_id;
				} elseif ($key == 'ThanksgivingActivity') { //630感恩大促
					$info->completeURL = $serUrl . 'thanksgivingActivity/?cust_id=' . $customer_id;
				} elseif ($key == 'FengActivity') { //一起蜂一夏
					$info->completeURL = $serUrl . 'activity/feng?cust_id=' . $customer_id;
				} elseif ($key == 'QixiActivity') { //七夕狂欢
					$info->completeURL = $serUrl . 'activity/qixi?cust_id=' . $customer_id;
				} elseif ($key == 'ZhongqiuActivity') { //中秋月饼
					$info->completeURL = $serUrl . 'RobMoonCakes/?cust_id=' . $customer_id;
				} elseif ($key == 'inviteActivity') { //云海天城世家注册送
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
				} elseif ($key == 'fenxiang') {
					$info->completeURL = $serUrl . 'fruit/show';
				} elseif ($key == 'luckyAppmainIndex') {
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
					$info->completeURL = 'http://eny.czy.colourlife.com/' . 'YiNengYuan/Buy?cust_id=' . $customer_id;
				} elseif ($key == 'YuanQuOpen') {
					$info->completeURL = $serUrl . 'Yuanqu/Open';
				} //sudiyi=速递易
				elseif ($key == 'sudiyi') {
					$mobile = $customer->mobile;
					$secret = $info->secret;
					$sign = md5($mobile . '&||&' . $secret);
					$info->completeURL = $info->url . "/?mobile={$mobile}&sign={$sign}";
				} //eIntake=E入伙
				elseif ($key == 'eIntake') {
					$bsecret = $info->secret;
					$mobile = $customer->mobile;
					$coding = $customer->community == null ? '' : $customer->community->domain;
					$url = $info->url;
					$sign = $bsecret . '||' . $mobile . '||' . $coding;
					$sign = strtolower(md5($sign));
					$info->completeURL = $url . '/?mobile=' . $mobile . '&coding=' . $coding . '&sign=' . $sign . '&community_id=' . $customer->community_id;

				} //其他使用bno,bsecret的对接方式
				elseif ($key == 'jiafang'
					|| $key == 'busOnline' || $key == 'efund'
					|| $key == 'xyd' || $key == 'linli'
					|| $key == 'market' || $key == 'anshi'
					|| $key == 'jd' || $key == 'daytuan'
					|| $key == 'market1' || $key == 'oneyuan'
					|| $key == 'PAIXIANSHENGXIAN' || $key == 'czz' || $key == 'czz-czytest'
					|| $key == 'chedada' || $key == 'rongxin'
					|| $key == 'newMobileRecharge' || $key == 'qunina'
					|| $key == 'shengxinsudi' || $key == 'jdyb'
					|| $key == 'dazhaxie' || $key == 'anshifengmi'
					|| $key == 'mihoutao' || $key == 'tousubaoxiu'
					|| $key == 'pzCarManager' || $key == 'hualiwang'
					|| $key == 'dongxing' || $key == 'ebaojie'
					|| $key == 'EWEIXIU' || $key == 'yidongqianyue'
					|| $key == 'taipingyang' || $key == 'Eshifukongtiao'
					|| $key == 'redwine' || $key == 'judian'
					|| $key == 'cailvyou' || $key == 'food'
					|| $key == 'xingchenjq' || $key == 'cheshenghuo'
					|| $key == 'huanqiujingxuanH5' || $key == 'qianxizhixing'
					|| $key == 'Ejiazhengyuesao' || $key == 'yunyida'
					|| $key == 'caishihui' || $key == 'pintuan'
					|| $key == 'huishenghuo' || $key == 'caiyingshi'
					|| $key == 'yisheng' || $key == 'siqing'
					|| $key == 'xiaolvren' || $key == 'jiaofuyi'
					|| $key == 'shunfeng' || $key == 'hehuayidai'
					|| $key == 'xiaocheshenghuo' || $key == 'ETingCheH5'
					|| $key == 'zhufu' || $key == 'caishihuisc'
					|| $key == 'youyouchongdian' || $key == 'weizhangchaxun'
					|| $key == 'koalaminicang' || $key == 'elvhua1018'
					|| $key == 'education'|| $key == 'Eclear'
					|| $key == 'Exihu' || $key == 'TestColourApi'
					|| $key == 'ticketmall' ||$key == 'ticketmall-czytest'
                    || $key == 'pintuan-czytest' || $key == 'daytuan-czytest'
					|| $key == 'colourviso' || $key == 'chargenet'
					|| $key == 'zhangxinbao' || $key == 'jd-czytest'
					|| $key == 'culture' || $key == 'mjw0510'
					|| $key == 'ghsy'
				) {

					$bno = 'test';
					$bsecret = 'abcd';
					if ($key == 'chedada') {
						$bno = '1500001';
						$bsecret = '5982c68892cdc8c9d5e3742a745780b4';
					}
					if ($key == 'qunina') {
						$bno = '1500001';
						$bsecret = '5982c68892cdc8c9d5e3742a745780b4';
					}
					if ($key == 'rongxin') {
						$bno = 'rongxinhui';
						$bsecret = 'Y72LgpmsqF8E4O3i';
					}
					if ($key == 'judian') {                           //聚电
						$bno = '4e2394ad6bc73e2ejudian';
						$bsecret = '9726bb874e2394ad6bc73e2e99f1asdjudian';
					}
					if ($key == 'caiyingshi') {
						$bno = '1259375337';
						$bsecret = '22774a34100c46d3bff21f4bab73d3b5';
					}
					if ($key == 'hehuayidai') {
						$bno = 'f3850f57d484471bad802917bb0b7cf420160623';
						$bsecret = 'cfbhuayijiecsh20160623';
					}
					if ($key == 'shunfeng') {
						$bno = '99';
						$bsecret = 'abcd';
					}
					if ($key == 'culture') {
						$bno = 'skyhawk';
						$bsecret = 'wErorgeWr49/98er6rfeD/8re2rfedRew2q';
					}

					$userid = $customer->id;
					$mobile = $customer->mobile;
					$username = empty($customer->name) ? $mobile : $customer->name;
					$password = $customer->password;
					$cid = $customer->community_id;
					if ($cid) {
						//地址
						$cname = $customer->build == null ? '' : $customer->build->name;
						//$community_name = $customer->community == null ? '' : $customer->community->name;
						$community_name = $customer->getCommunityName();
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
						$sec = $info->secret;
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
						if ($info->website) {
							$http = $info->website . '?' . $url;
						} elseif ($key == 'jdyb') {
							$key = "jd";
							$http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url . "&id=210";
						} elseif ($key == 'anshifengmi') {
							$key = "anshi";
							$http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url . "&pid=20888";
						} elseif ($key == 'mihoutao') {
							$key = "daytuan";
							$http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url . "&pid=1355";
						} elseif ($key == 'yisheng') {
							$sql = "SELECT id FROM cheap_log WHERE goods_id=31659 AND is_deleted=0 AND `status`=0";
							$code = Yii::app()->db->createCommand($sql)->queryAll();
							$pid = $code[0]['id'];
							$key = "daytuan";
							$http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url . "&pid=" . $pid;
						} elseif ($key == 'yunyida' || $key == 'youyouchongdian' || $key == 'weizhangchaxun') {
							$http = $info->url . '&' . $url;
						} else {

							//兼容https
							if($port == ':443'){
								$http = 'https://' . $key . '.' . implode('.', $name) . '?' . $url;
							}else{
								$http = 'http://' . $key . '.' . implode('.', $name) . $port . '?' . $url;
							}

						}
						$info->completeURL = $http;
					} else {
						$info->completeURL = $serUrl . 'pagePromptApp/NotCid';
					}
				} elseif ($key == 'zhici') {
					$info->completeURL = $serUrl . 'zhiciApp';
				} elseif ($key == 'hybx' || $key == 'cpfh') {
					$secret = $info->secret;
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $info->url . '?' . $sign->createLinkString($para);

					//$info->completeURL = 'http://m.hehenian.com/insurance/hk.do';
				} elseif ($key == 'ganenpraise') {
					$info->completeURL = $serUrl . 'NewThanksgiving/Announce?cust_id=' . $customer_id;
				} elseif ($key == 'fruitCarnival') {
					$info->completeURL = $serUrl . 'FruitCarnival?cust_id=' . $customer_id;
				} elseif ($key == 'warmPurse') {
					$info->completeURL = $serUrl . 'WarmPurse?cust_id=' . $customer_id;
				} elseif ($key == 'mealTicket') {
					$url = F::getMUrl() . 'mealTicket';
					$loginKey = 'SD&^)#@LDCsrS';
					$para = array(
						'customer_id' => $customer_id,
						'ts' => time(),
					);

					$sign = new Sign($loginKey);
					$para['sign'] = $sign->makeSign($para);

					$info->completeURL = Yii::app()->curl->buildUrl($url, $para);
				} elseif ($key == 'nianhuo') {
					$info->completeURL = $serUrl . 'NianHuo?cust_id=' . $customer_id;
				} elseif ($key == 'lanternFestival') {
					$info->completeURL = $serUrl . 'lanternFestival?cust_id=' . $customer_id;
				} elseif ($key == 'xingChenQuestion') {
					$info->completeURL = $serUrl . 'xingChenQuestion?cust_id=' . $customer_id;
				} elseif ($key == 'baoxiang') {
					$info->completeURL = $serUrl . 'baoXiang?cust_id=' . $customer_id;
				} elseif ($key == 'colourTravel') {
					$info->completeURL = $serUrl . 'colourTravel?cust_id=' . $customer_id;
				} elseif ($key == 'zhishujie') {
					$info->completeURL = $serUrl . 'ZhiShu/Ling?cust_id=' . $customer_id;
				} elseif ($key == 'rights') {
					$info->completeURL = $serUrl . 'rights?cust_id=' . $customer_id;
				} elseif ($key == 'easyPay') {
					$info->completeURL = $serUrl . 'advertisement/easyPay?cust_id=' . $customer_id;
				} elseif ($key == 'easyComplain') {
					$info->completeURL = $serUrl . 'advertisement/easyComplain?cust_id=' . $customer_id;
				} elseif ($key == 'wealthLife') {
					$info->completeURL = $serUrl . 'advertisement/wealthLife?cust_id=' . $customer_id;
				} elseif ($key == 'easyParking') {
					$info->completeURL = $serUrl . 'advertisement/easyParking?cust_id=' . $customer_id;
				} elseif ($key == 'easter') {
					$info->completeURL = $serUrl . 'easter?cust_id=' . $customer_id;
				} elseif ($key == 'AprilFool') {
					$mobile = $customer->mobile;
					$time = $customer->create_time;
					$sign = md5('customer_id=' . $customer_id . '||mobile=' . $mobile . '||time=' . $time);
					$customer_id = $customer_id * 778 + 1778;
					$info->completeURL = $serUrl . 'AprilFool?cust_id=' . $customer_id . '&sign=' . $sign;
				} elseif ($key == 'jiniunai') {
					$mobile = $customer->mobile;
					$time = $customer->create_time;
					$sign = md5('customer_id=' . $customer_id . '||mobile=' . $mobile . '||time=' . $time);
					$customer_id = $customer_id * 778 + 1778;
					$info->completeURL = $serUrl . 'NiuNai?cust_id=' . $customer_id . '&sign=' . $sign;
				} elseif ($key == 'wuyuetehui') {
					$mobile = $customer->mobile;
					$time = $customer->create_time;
					$sign = md5('customer_id=' . $customer_id . '||mobile=' . $mobile . '||time=' . $time);
					$customer_id = $customer_id * 778 + 1778;
					$info->completeURL = $serUrl . 'MayPreferential?cust_id=' . $customer_id . '&sign=' . $sign;
				} elseif ($key == 'muqinjie') {
					$info->completeURL = $serUrl . 'MotherDay?cust_id=' . $customer_id;
				} elseif ($key == 'duanwu') {
					$info->completeURL = $serUrl . 'MotherDay/DuanWu?cust_id=' . $customer_id;
				} elseif ($key == 'squareDance') {
					$secret = '@#$WER653';
					$customer_id = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $customer_id,
						'request_time' => time()
					);

					$info->completeURL = $this->makeUrl($serUrl . 'SquareDance', $secret, $para);
				} elseif ($key == 'caifuJuly') {
					$secret = 'sidDIX382';
					$customer_id = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $customer_id,
						'request_time' => time()
					);

					$info->completeURL = $this->makeUrl($serUrl . 'Profit/July', $secret, $para);
				} elseif ($key == 'treeTwo') {
					$secret = '@&Tree*Two^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'ZhiShuJieTwo?' . $sign->createLinkString($para);
				} elseif ($key == 'loveHook') {
					$secret = '^&Hook*Love^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'LoveHook/RankList?' . $sign->createLinkString($para);
				} elseif ($key == 'activity') {  //所有活动的配置链接都走这里来，不用硬代码配置
					$secret = $info->secret;
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $info->url . '?' . $sign->createLinkString($para);
				} elseif ($key == 'siqingchou') {
					$secret = '@&Si*Qing^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'SiQingChou?' . $sign->createLinkString($para);
				} elseif ($key == 'treeThree') {
					$secret = '@&Tree*Three^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'ZhiShuJieThree?' . $sign->createLinkString($para);
				} elseif ($key == 'box') {
					$secret = '@&Box^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'Box?' . $sign->createLinkString($para);
				} elseif ($key == 'popularity') {
					$secret = '@&Popularity*^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'Popularity?' . $sign->createLinkString($para);
				} elseif ($key == 'september') {
					$secret = '@&September*^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'September?' . $sign->createLinkString($para);
				} elseif ($key == 'task') {
					$secret = '@&Task^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'Task?' . $sign->createLinkString($para);
				}elseif ($key == 'liuliang') {
					$secret = '@&LiuLiang^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'Liang/Tip?' . $sign->createLinkString($para);
				}
                elseif ($key == 'OpenDoor') {
					$secret = '@&OpenDoor*^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time(),
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'OpenDoor?' . $sign->createLinkString($para);
				}
				elseif ($key == 'propertyLotty') {
					$secret = 'pr*op%er^ty';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'Property?' . $sign->createLinkString($para);
				}
				elseif ($key == 'thanksgivingBag') {
					$secret = 'th^an#ksgi*ving';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'Thanksgiving?' . $sign->createLinkString($para);
				}elseif ($key == 'queShen') {
					$secret = '@&QueShen*^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'QueShen?' . $sign->createLinkString($para);
				}elseif ($key == 'liuliangbanner') {
					$secret = '@&LiuLiang^%';
					$userID = $customer_id * 778 + 1778;
					$para = array(
						'user_id' => $userID,
						'request_time' => time()
					);
					$sign = new Sign($secret);
					$para['sign'] = $sign->makeSign($para);
					$info->completeURL = $serUrl . 'Liang/banner?' . $sign->createLinkString($para);
				}
				return $info;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	/*
 * 数组转化为字符串
 */

	private function arrayToString($array = null)
	{
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


	public function isPorto()
	{
		if ($this->act == 'url')
			return '应用URL';
		else
			return '手机原型';
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('name', $this->name, true);
		$criteria->compare('act', $this->act, true);
		$criteria->compare('state', $this->state);
		$criteria->order = 'id desc';
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

	public function getClsName()
	{
		if ($this->setableSmallLoansCls)
			return $this->setableSmallLoansCls->name;
		else
			return '不在首页显示';
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
	public function addParam($k, $v)
	{
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

	public function getCommunityTreeData()
	{
		$branch_id = 1; //默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
		$branch = Branch::model()->findByPk($branch_id);
		return $branch->getRegionCommunityRelation($this->id, 'SetableSmallLoans', false);
	}

	/**
	 * 生成活动链接
	 * @param unknown $url
	 * @param unknown $secret
	 * @param unknown $para
	 * @return string
	 */
	private function makeUrl($url, $secret, $para)
	{
		$sign = new Sign($secret);

		$para['sign'] = $sign->makeSign($para);

		return $url . '/?' . $sign->createLinkString($para);
	}

}
