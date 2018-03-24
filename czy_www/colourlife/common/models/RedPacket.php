<?php

/**
 * This is the model class for table "red_packet".
 *
 * The followings are the available columns in table 'red_packet':
 * @property string $id
 * @property integer $type
 * @property integer $customer_id
 * @property integer $from_type
 * @property integer $to_type
 * @property string $sum
 * @property string $create_time
 * @property string $note
 */
class RedPacket extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $modelName = '饭票';
    public $username;
    public $name;
    public $community_id;
    public $mobile;
    public $region;
    public $branch;
    public $build_id;
    public $branch_id;

	public $communityIds;
	public $province_id;
	public $city_id;
	public $district_id;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

	public function tableName()
	{
		return 'red_packet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, note', 'required'),
			array('type, customer_id, from_type, create_time, to_type', 'numerical', 'integerOnly'=>true),
			array('sum', 'length', 'max'=>10),
			array('remark', 'length', 'max'=>100),
            array('sn,lukcy_result_id','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mobile,username,name,community_id,branch_id,region, type, customer_id, from_type, to_type, sum, create_time, note', 'safe', 'on' => 'search'),
			array('mobile,username,name,community_id,branch_id,region, type, customer_id, from_type, to_type, sum, create_time, note', 'safe', 'on' => 'new_search'),
			//			ICE 搜索数据
			array('province_id,city_id,district_id', 'safe'),
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
             'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => '类型',
			'customer_id' => 'Customer',
			'from_type' => 'From Type',
			'to_type' => 'To Type',
			'sum' => '金额',
			'create_time' => '时间',
			'note' => '备注',
            'username'=>'用户名',
            'name'=>'名字',
            'mobile'=>'电话',
            'community_id'=>'小区',
            'region'=>'地区',
            'branch_id'=>"部门",
			'lukcy_result_id'=>"中奖记录ID",
            'remark' => '捎一句话',
			'communityIds' => '小区'
		);
	}

	public function getUsername()
	{
		return empty($this->customer) ? "" : $this->customer->username;
	}

	public function getName()
	{
		return empty($this->customer) ? "" : $this->customer->name;
	}

	public function getMobile()
	{
		return empty($this->customer) ? "" : $this->customer->mobile;
	}
//  ICE接入
	public function getCommunity()
	{
//		return empty($this->customer->community) ? "" : $this->customer->community->name;
		if (!empty($this->customer->community_id)) {
			//return Region::getMyParentRegionNames($this->customer->community->region_id,$isOneSelf=true);
//            return $this->customer->community->ICEGetCommunityRegionsNames();
			$community = ICECommunity::model()->findByPk($this->customer->community_id);
			if (!empty($community)) {
				return $community->name;
			}
		}
		return "";
	}
//  ICE接入
	public function getRegionTag()
	{
//		if (isset($this->customer->community)) {
//			return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '所在地区:' .
//				Region::getMyParentRegionNames($this->customer->community->region_id)), $this->customer->community->region->name);
//		} else {
//			return "";
//		}

		if (isset($this->customer->community_id)) {
			$community = ICECommunity::model()->findByPk($this->customer->community_id);
			$regionName = $regionString = '';
			if(!empty($community)){
				$regionString = $community->regionString;
				$regionName = $community->region;
			}
			return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
				'data-original-title' => '所在地区:' .$regionString),
				$regionName
			);
		} else {
			return "";
		}

	}
//  ICE 接入
	public function getRegion()
	{
		if (isset($this->customer->community_id)) {
			//return Region::getMyParentRegionNames($this->customer->community->region_id,$isOneSelf=true);
//            return $this->customer->community->ICEGetCommunityRegionsNames();
			$community = ICECommunity::model()->findByPk($this->customer->community_id);
			if (!empty($community)) {
				return $community->ICEGetCommunityRegionsNames();
			}
		}
		return "";

	}

	public function getType()
	{
		if ($this->type == Item::RED_PACKET_TYPE_CONSUME) {
			return "消费";
		} else if ($this->type == Item::RED_PACKET_TYPE_ACQUIRE) {
			return "获取";
		}
	}

	public function getRedPacketType()
	{
		return array(
			Item::RED_PACKET_TYPE_CONSUME => '消费',
			Item::RED_PACKET_TYPE_ACQUIRE => '获取'
		);
	}

	/**
	 * @param array $attr 数组元素包括customer_id(用户ID)、from_type(获取方式1or2or3or4)、sum(饭票金额)、
	 * sn(抽奖活动ID：from_type=3,或订单sn:from_type=1or2or4)
	 * @return bool
	 * 添加饭票,
	 */
	public function addRedPacker($attr = array(), &$redInfo = false)
	{
		Yii::log(
		sprintf(
		'addRedPacker 调用: %s %s',
		json_encode($attr),
		json_encode($redInfo)
		),
		CLogger::LEVEL_ERROR,
		'colourlife.core.redpacket.add'
				);
		//关键参数不能缺失
		if (!isset($attr['customer_id']) or !isset($attr['from_type']) or !isset($attr['sum']) or !isset($attr['sn'])) {
			return false;
		}
		if (empty($attr['customer_id']) or empty($attr['from_type']) or empty($attr['sum']) or empty($attr['sn'])) {
			return false;
		}
		//金额必须为数字
		if (!is_numeric($attr['sum'])) {
			Yii::log(
			sprintf(
			'addRedPacker 调用,金额不是数字: %s',
			$attr['sum']
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.redpacket.add'
					);
			return false;
		}
		// 充值金额
		$consumeBalance = 0;
		switch ($attr['from_type']) {
			case Item::RED_PACKET_FROM_TYPE_ADVANCE_FEES://预缴费获得饭票
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(预缴费)退款返还饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_ARREARS_REFUND://欠费退款获得饭票
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(欠费缴费)退款返还饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_LOTTERY://抽奖获得饭票
				$luckInfo = LuckyActivity::model()->findByPk($attr['sn']);
				$hasObj = empty($luckInfo) ? false : true;
				$note = "通过【{$luckInfo->name}】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_PARKING_FEES_REFUND://停车费退款获得饭票
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(缴停车费)退款返还饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_GOODS://商品退饭票
                if(strpos($attr['sn'],'-') === false){
                    $backSn=$attr['sn'];
                }else{
                    $backSn=str_replace('-','',$attr['sn']);
                }
				$model = SN::findContentBySN($backSn);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$backSn}】(业主商品)退款返还饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_BUGS://有奖捉虫饭票
				$hasObj = true;
				$note = "通过【有奖捉虫】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_WORLD_CUP_VS://世界杯竞猜胜负饭票
				$hasObj = true;
				$note = "通过【世界杯竞猜胜负】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_WORLD_CUP_PROMOTION://世界杯竞猜晋级饭票
				$hasObj = true;
				$note = "通过【世界杯竞猜晋级】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_INVITE:    //邀请好友送饭票
				$hasObj = true;
				$note = "通过【邀请好友注册】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_MOON_CAKES:    //购月饼满100送饭票
				$hasObj = true;
				$note = "通过【中秋月饼满送活动】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_CHANGE_REDPACKET:
				$hasObj = true;
				$note = "通过【APP抽中二等奖水果改发饭票】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_OCT_MILK:
				$hasObj = true;
				$note = "通过【订购指定牛奶】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_RICE_OIL:
				$hasObj = true;
				$note = "通过【订购指定粮油】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_PURCHASE:
				$hasObj = true;
				$note = "通过【订购指定海外直购商品】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_BUY_LITCHI_AWARD:
				$hasObj = true;
				$note = "通过【购指定荔枝商品】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_ORDER_SEND:
				$hasObj = true;
				$note = "通过【邀请业主首次购买牛奶】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_ORDER_SEND_RICEOIL:
				$hasObj = true;
				$note = "通过【邀请业主首次购买指定粮油】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_INVITE_BUY_LITCHI:
				$hasObj = true;
				$note = "通过【邀请业主购买荔枝商品】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_NEW_CUSTOMER_REGISTER:
				$hasObj = true;
				$note = "通过【活动期间新用户注册】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_JULY_MENG_NIU:
				$hasObj = true;
				$note = "通过【7月份蒙牛订单】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_AUGUST_RECOMMEND_AWARD:
				$hasObj = true;
				$note = "通过【8月份推荐奖励饭票】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_OA_CARRY:
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "通过【{$attr['sn']}】(OA转账获得饭票)获得饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_CARRY:
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				if (isset($attr['is_local']) && $attr['is_local'] == 1){
					$note = "通过【{$attr['sn']}】(彩之云地方饭票转全国饭票获得饭票)获得饭票【{$attr['sum']}】元";
				}else {
					$note = "通过【{$attr['sn']}】(彩之云转账获得饭票)获得饭票【{$attr['sum']}】元";
				}
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_CUSTOMER_RECHARGE:
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "通过【{$attr['sn']}】(饭票充值)获得饭票【{$attr['sum']}】元";
				$consumeBalance = $attr['sum'];
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
			case Item::RED_PACKET_FROM_TYPE_V23_REDPACKET:
                $hasObj = true;
                $note = "春节期间通过新注册获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_ERUHUO_LOTTERY://抽奖获得饭票
                $hasObj = true;
                $note = "通过【E入伙抽奖】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_MANAGER_AWARD://获得总经理奖励饭票
                $hasObj = true;
                $note = "通过【总经理奖励】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_NEWS_REPORT://彩生活发布会记者饭票
                $hasObj = true;
                $note = "通过【彩生活发布会记者饭票】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_REDPACKET_FEES_ACTIVITY://感恩回馈，充饭票满100送2元，赠送红包XX元 活动
                $hasObj = true;
                $note = "通过【感恩回馈，充饭票满100送2元】活动，获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_EJIAFANG_COMMENT://E家访评论获得饭票
                $hasObj = true;
                $note = "通过【E家访评论获得饭票】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_REDPACKETFEES://饭票充值获得饭票
                $hasObj = true;
                $note = "通过【饭票充值获得饭票】获取饭票【{$attr['sum']}】元";
                $consumeBalance = $attr['sum'];
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_ELICAI_DINGQI_TICHEN_JIANGLI://E理财定期提成奖励
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(E理财定期提成奖励自动发放)获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_RXH_LICAI_TICHEN_JIANGLI://荣信汇理财推荐提成奖励饭票
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(荣信汇理财提成奖励自动发放)获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI://彩富人生订单推荐提成奖励饭票自动发放
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(彩富人生订单推荐提成奖励自动发放)获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_BACK://年年卡手机充值退款
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(年年卡手机充值退款)获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_PROFIT_CONTINUOUS_CFRS://彩富人生续投赠送饭票
                $hasObj = true;
                $note = "通过【彩富人生续投赠送饭票】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
	        case Item::RED_PACKET_FROM_TYPE_REWARDS_JIANGLI://订单推荐提成奖励饭票自动发放
                $hasObj = true;
                $note = "订单【{$attr['sn']}】(订单推荐提成奖励系统发放)获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_XIAN_ACTIVITY://西安事业部预收16年管理费及停车费活动
                $hasObj = true;
                $note = "订单【{$attr['sn']}】(西安事业部预收16年管理费及停车费活动)获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_WARM_PURSE:    //冬日饭票活动完成任务送饭票
                $hasObj = true;
                $note = "通过【冬日饭票活动完成任务】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_HZ_ACTIVITY://惠州事业部预收16年管理费活动
                $hasObj = true;
                $note = "订单【{$attr['sn']}】(惠州事业部预收16年管理费活动)获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_MEAL_TICKET://饭票券
                if(strpos($attr['sn'],'-') === false){
                    $backSn=$attr['sn'];
                }else{
                    $backSn=str_replace('-','',$attr['sn']);
                }            
                $hasObj = true;
                $note = "收到饭票券ID【{$backSn}】获得饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_NINA_MIANDAN:    //年货免单/送送送活动获得饭票
                $hasObj = true;
                $note = "通过【免单/送送送】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_CAI_HOUSE:    //年货免单/送送送活动获得饭票
                $hasObj = true;
                $note = "通过【彩住宅】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_YUN_YIDA:    //云易达获得饭票
                $hasObj = true;
                $note = "通过【云易达{$attr['sn']}】获取饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_POWER_FEES_REFUND:    //商铺买电退款获得饭票
                $hasObj = true;
                $note = "订单【{$attr['sn']}】(商铺买电)退款返还饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_ARREARS_CONSUMPTION_PAYMENT_REFUND:    //物业费退款获得饭票
                $hasObj = true;
                $note = "订单【{$attr['sn']}】(物业费)退款返还饭票【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_PROFIT_ACTIVITY_JULY:    //彩富7月新人礼活动
                $hasObj = true;
                $note = "订单【{$attr['sn']}】彩富7月新人礼活动【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_THIRD_PAYMENT:    //第三方支付对应账户获得饭票
                $hasObj = true;
                $note = "订单【{$attr['sn']}】支付获得【{$attr['sum']}】元";
                $remark = empty($attr['remark']) ? "":$attr['remark'];
                break;
            case Item::RED_PACKET_FROM_TYPE_REFUND_FP://追回饭票
            	$hasObj = true;
                $note = isset($attr['note']) ? $attr['note'] : "追回饭票";
                $remark = !isset($attr['remark']) ? "":$attr['remark'];
                break;
			case Item::RED_PACKET_FROM_TYPE_LOCAL_REDPACKETFEES://地方饭票充值获得饭票
				$hasObj = true;
				$note = "通过【{$attr['sn']}地方饭票充值获得饭票】获取饭票【{$attr['sum']}】元";
				$remark = empty($attr['remark']) ? "":$attr['remark'];
				break;
            default;
                $hasObj = false;$note = "未知方式充值";$remark = "未知方式充值";
        }
        Yii::log(
        sprintf(
        'addRedPacker 调用,参数调整完成: %s',
        json_encode($attr)
        ),
        CLogger::LEVEL_ERROR,
        'colourlife.core.redpacket.add'
        		);
        // 内部充值处理
        if(strpos($attr['sn'], '113') === 0){
        	Yii::log(
        	sprintf(
        	'addRedPacker 调用,订单号是113开头的: %s',
        	strpos($attr['sn'], '113')
        	),
        	CLogger::LEVEL_ERROR,
        	'colourlife.core.redpacket.add'
        			);
            $model = SN::findContentBySN($attr['sn']);
            $hasObj = empty($model)?false:true;
            $remark = isset($attr['remark']) ? $attr['remark'] : '';
        }
        //如果传入的sn找不到对象
        if(!$hasObj){
        	Yii::log(
        	sprintf(
        	'addRedPacker 调用,找不到该订单: %s',
        	json_encode($attr)
        	),
        	CLogger::LEVEL_ERROR,
        	'colourlife.core.redpacket.add'
        			);
        	return false;
        }
        $attr['type'] = Item::RED_PACKET_TYPE_ACQUIRE;//设置属性为获取
        $attr['note'] = $note;//备注
        $attr['remark'] = $remark;//捎一句话
        if(!$attr['note'] || $attr['note'] == ''){
            $attr['note'] = $remark;
        }
        Yii::log(
        sprintf(
        'addRedPacker 调用: %s',
        json_encode($attr)
        ),
        CLogger::LEVEL_ERROR,
        'colourlife.core.redpacket.add'
        		);
        $redPacket = new self();
        $customer = Customer::model()->findByPk($attr['customer_id']);
        if(!$customer){
        	Yii::log(
        	sprintf(
        	'addRedPacker 调用，用户不存在: %s',
        	json_encode($attr)
        	),
        	CLogger::LEVEL_ERROR,
        	'colourlife.core.redpacket.add'
        			);
        			throw new Exception('用户不存在！');
        }

        $isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
        $transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
        try {
            $balance = ($customer->getBalance()+$attr['sum']);
            $consumeBalance = $customer->consume_balance + $consumeBalance;
            $redPacket->setAttributes($attr);

            $redPacketSave = $redPacket->save();
            if (!$redPacketSave){
            	(!$isTransaction)?$transaction->rollback():'';
            	Yii::log("饭票明细保存失败".json_encode($redPacket->getErrors()),CLogger::LEVEL_ERROR,CLogger::LEVEL_ERROR,'colourlife.core.redpacket.add');
            	return false;
            }
            $customerUpdate = Customer::model()->updateByPk($customer->id,array('balance'=>$balance, 'consume_balance'=>$consumeBalance,'last_time' => time()));
            if (!$customerUpdate){
            	(!$isTransaction)?$transaction->rollback():'';
            	Yii::log("更新用户信息表失败".json_encode($customer->getErrors()),CLogger::LEVEL_ERROR,CLogger::LEVEL_ERROR,'colourlife.core.redpacket.add');
            	return false;
            }
            Yii::log(
            sprintf(
            'addRedPacker balance_consumeBalance: %s',
            $customer->id.'_'.$balance.'_'.$consumeBalance
            ),
            CLogger::LEVEL_ERROR,
            'colourlife.core.redpacket.add'
            		);

            // 转账交易不需要请求金融平台，只产生本地交易记录
            if($attr['from_type']!=Item::RED_PACKET_FROM_TYPE_OA_CARRY
                &&$attr['from_type']!=Item::RED_PACKET_FROM_TYPE_CARRY
				&&$attr['from_type']!=Item::RED_PACKET_FROM_TYPE_REFUND_FP
				&&$attr['from_type']!=Item::RED_PACKET_FROM_TYPE_LOCAL_REDPACKETFEES
			){
            	if ($attr['from_type'] == Item::RED_PACKET_FROM_TYPE_THIRD_PAYMENT && $attr['customer_id'] == 2222308){ //售货机为了避开金融平台交易接口同个订单不能重复提交的限制条件，在订单号后面加‘+’
            		$attr['sn'] = $attr['sn'].'+';
            	}
				if ($attr['from_type'] == Item::RED_PACKET_FROM_TYPE_GOODS && $attr['customer_id'] !== 2222308){ //退款，售货机为了避开金融平台交易接口同个订单不能重复提交的限制条件，在订单号后面加‘+’
					$attr['sn'] = $attr['sn'].'++';
				}
                // 普通交易，请求金融平台
				if($attr['from_type'] == Item::RED_PACKET_FROM_TYPE_BACK){//年年卡退款订单
					$attr['sn'] = $attr['sn'].'-';
				}
                $ftransaction = $this->finance_transaction($attr);
                if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
                    //throw new Exception('消费饭票失败！');
                	(!$isTransaction)?$transaction->rollback():'';
                	Yii::log(
                	sprintf(
                	'addRedPacker 消费饭票失败: %s',
                	json_encode($ftransaction)
                	),
                	CLogger::LEVEL_ERROR,
                	'colourlife.core.redpacket.add'
                			);
                	return false;
                }
            }else {
            	// 转账交易，模拟transaction结果
                $ftransaction['payinfo'] = array('tno'=>'simulate');
            }
            /*
            if(!$redPacket->save() or !Customer::model()->updateByPk($customer->id,array('balance'=>$balance, 'consume_balance'=>$consumeBalance))){
                $errors1 = $redPacket->getErrors();
            	$errors2 = $customer->getErrors();
            	Yii::log("添加饭票失败".json_encode($errors1)."==".json_encode($errors2),CLogger::LEVEL_ERROR,CLogger::LEVEL_ERROR,'colourlife.core.redpacket.add');
                //save方法返回bool值，所以需要手动抛出异常
                throw new Exception( '添加饭票失败！' );
            }
            */
            $redInfo['modle']=$redPacket;
             (!$isTransaction)?$transaction->commit():'';

            /*
            //TODO:kakatool 插入金融平台交易同步方法
            //只要不是转账,都认为是充值
//            if($attr['from_type']!=Item::RED_PACKET_FROM_TYPE_OA_CARRY
//                &&$attr['from_type']!=Item::RED_PACKET_FROM_TYPE_CARRY){
//                FinanceSyncService::getInstance()->customerRecharge($attr['customer_id'],$attr['sum'],$note);
//            }
            */
             Yii::log(
             sprintf(
			        'addRedPacker finance transaction response: %s',
			        json_encode($ftransaction)
		        ),
             CLogger::LEVEL_ERROR,
             'colourlife.core.redpacket.add'
             		);
            return true;
        } catch ( Exception $e ) {
            (!$isTransaction)?$transaction->rollback():'';
            Yii::log("addRedPacker的try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.add');
        }
        return false;
    }

    /**
     * @param array $attr 数组元素包括customer_id(用户ID)、to_type(消费方式1or2)、sum(饭票金额)、
     * sn(订单sn:to_type=1or2)
     * @return bool
     * 消费饭票,
     */
    public function consumeRedPacker($attr=array()){
    	
    	Yii::log("consumeRedPacker消费饭票开始：".json_encode($attr),CLogger::LEVEL_INFO,'colourlife.core.redpacket.consumeRedPacker');
    	
        //关键参数不能缺失
        if(!isset($attr['customer_id']) or !isset($attr['to_type']) or !isset($attr['sum']) or !isset($attr['sn'])){
        	Yii::log("consumeRedPacker消费饭票有参数不存在：".json_encode($attr),CLogger::LEVEL_INFO,'colourlife.core.redpacket.consumeRedPacker');
            return false;
        }
        if(empty($attr['customer_id']) or empty($attr['to_type']) or empty($attr['sum']) or empty($attr['sn'])){
        	Yii::log("consumeRedPacker消费饭票有参数为空：".json_encode($attr),CLogger::LEVEL_INFO,'colourlife.core.redpacket.consumeRedPacker');
            return false;
        }

        // 忽略，交由金融平台判断
        /*
        //金额必须为数字
        if(!$this->checkMoney($attr['customer_id'],$attr['sum'])){
            return false;
        }
        */

        $model = SN::findContentBySN($attr['sn']);
        $hasObj = empty($model)?false:true;
        // 扣除消费金额
        $consumeBalance = 0;
        switch($attr['to_type']){
            case Item::RED_PACKET_TO_TYPE_ADVANCE_FEES_PAYENT://预缴费消费饭票
                $note = "订单【{$attr['sn']}】(预缴费)消费饭票【{$attr['sum']}】元";
                $consumeBalance = $attr['sum'];
                break;
            case Item::RED_PACKET_TO_TYPE_ARREARS_CONSUMPTION_PAYMENT://欠费消费饭票
                $note = "订单【{$attr['sn']}】(欠费缴费)消费饭票【{$attr['sum']}】元";
                $consumeBalance = $attr['sum'];
                break;
            case Item::RED_PACKET_TO_TYPE_PARKING_FEES_PAYMENT://缴停车费饭票
                $note = "订单【{$attr['sn']}】(缴停车费)消费饭票【{$attr['sum']}】元";
                $consumeBalance = $attr['sum'];
                break;
            case Item::RED_PACKET_TO_TYPE_GOODS_PAYMENT://商品消费饭票
                $note = "订单【{$attr['sn']}】商品消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_VIRTUALRECHARGE_PAYMENT://充值消费饭票
                $note = "订单【{$attr['sn']}】充值消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_POWER_FEES://商铺买电消费饭票
                $note = "订单【{$attr['sn']}】商铺买电消费饭票【{$attr['sum']}】元";
                $consumeBalance = $attr['sum'];
                break;
            case Item::RED_PACKET_TO_TYPE_THIRD_PAYMENT://第三方支付消费饭票
                $note = "订单【{$attr['sn']}】第三方支付消费饭票【{$attr['sum']}】元";
				if(substr($attr['sn'] , 0 ,4) == 9013 ){
					$consumeBalance = $attr['sum'];
				}else{
					$consumeBalance = 0;
				}
                break;
            case Item::RED_PACKET_TO_TYPE_REDPACKET_PAYMENT://饭票充值消费饭票 2015-06-03
                $note = "订单【{$attr['sn']}】第三方支付消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_WEISHANGQUAN_PAY://微商圈消费饭票 2015-07-09
                $note = "订单【{$attr['sn']}】微商圈消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_CARRY://彩之云转账消费饭票
            	if (isset($attr['is_local']) && $attr['is_local'] == 1){
            		$note = "订单【{$attr['sn']}】(彩之云地方饭票转全国饭票)消费饭票【{$attr['sum']}】元";
            	}else {
            		$note = "订单【{$attr['sn']}】(彩之云转账)消费饭票【{$attr['sum']}】元";
            	}
                break;
            case Item::RED_PACKET_TO_TYPE_MEAL_TICKET://购买饭票券消费饭票
                $note = "订单【{$attr['sn']}】(购买饭票券)消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_YUN_YIDA://云易达发放消费饭票
                $note = "订单【{$attr['sn']}】(易达发放)消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_ERUHUO_LOTTERY://云易达发放消费饭票
                $note = "订单【{$attr['sn']}】(E入伙)消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_GOODS://第三方退款扣除消费饭票
                $note = "订单【{$attr['sn']}】(第三方退款)消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_INSURE://第三方退款扣除消费饭票
                $note = "订单【{$attr['sn']}】(彩富保险)消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_SPECIALTY://第三方退款扣除消费饭票
                $note = "订单【{$attr['sn']}】(专业公司服务)消费饭票【{$attr['sum']}】元";
                break;
            case Item::RED_PACKET_TO_TYPE_RETURN_FP://扣回饭票
				$note = isset($attr['note']) ? $attr['note'] : "扣回饭票";
                break;
            default;
                $note = "订单【{$attr['sn']}】消费饭票【{$attr['sum']}】元";;
        }
        Yii::log('consumeRedPacker调用：参数调整完成：'.json_encode($attr).',订单是否存在'.$hasObj,CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        //如果传入的sn找不到对象
        if(!$hasObj)return false;
        if($model->tableName() != 'redpacket_internal_transaction' && $attr['to_type']!=Item::RED_PACKET_TO_TYPE_CARRY && $attr['to_type']!=Item::RED_PACKET_TO_TYPE_RETURN_FP){
            $model->user_red_packet=Item::RED_PACKET_USED;//已使用饭票
        }
        // 内部充值处理
        if(strpos($attr['sn'], '113') === 0){
            Yii::log(
                sprintf(
                    'consumeRedPacker 调用,订单号是113开头的: %s',
                    json_encode($attr)
                ),
                CLogger::LEVEL_ERROR,
                'colourlife.core.redpacket.consumeRedPacker'
            );
        }
        $attr['type'] = Item::RED_PACKET_TYPE_CONSUME;//设置属性为消费
        $attr['note'] = $note;//备注
        if (isset($attr['is_local']) && $attr['is_local'] == 1){ //地方饭票
        	$redPacket = new LocalRedPacket();
        }else {
        	$redPacket = new self();
        }
        $customer = Customer::model()->findByPk($attr['customer_id']);
        if(!$customer){
        	Yii::log('consumeRedPacker调用：用户不存在：'.$attr['customer_id'].',订单是否存在'.$hasObj,CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        	throw new Exception('用户不存在！');
        }
        $isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
        $transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
        try {
        	Yii::log('consumeRedPacker调用：准备入库操作：'.json_encode($attr),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        	if (isset($attr['is_local']) && $attr['is_local'] == 1){ //地方饭票
        		Yii::log('地方饭票consumeRedPacker调用：获取用户地方饭票余额：'.json_encode($attr),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        		$balance = ($customer->getBalance(true,$attr['org_pano'],$attr['org_atid'])-$attr['sum']);
        		Yii::log('地方饭票consumeRedPacker调用：计算用户地方饭票余额：'.$balance,CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        	}else {
        		$balance = ($customer->getBalance()-$attr['sum']);
        	}
        	$consumeBalance = $customer->consume_balance - $consumeBalance;
        	$redPacket->setAttributes($attr);
        	$redPacket->validate();
        	if(!$redPacket->validate()){
        		Yii::log("验证信息:".json_encode($redPacket->getErrors()).">>>".json_encode($redPacket->getErrors()),CLogger::LEVEL_INFO,'colourlife.core.redpacket.consumeRedPacker');
        	}
        	//Yii::log("验证信息:".json_encode($redPacket->getErrors()).">>>".json_encode($model->getErrors()),CLogger::LEVEL_INFO,'colourlife.core.redpacket');
        
        	// 次啊只有全国饭票交易记录保存到旧的交易表中
        	if (!$redPacket->save()){
        		//save方法返回bool值，所以需要手动抛出异常
        		//throw new Exception('消费饭票失败！');
        		// TODO 保存记录失败，需要进行额外的处理
        		(!$isTransaction)?$transaction->rollback():'';
        		Yii::log("保存{$attr['sn']},记录redPacket失败，需要进行额外的处理",CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        		return false;
        	} else if(!Customer::model()->updateByPk($customer->id, array('balance'=>$balance, 'consume_balance'=>$consumeBalance,'last_time' => time()))){
        		(!$isTransaction)?$transaction->rollback():'';
        		Yii::log("更新customer表的balance和consume_balance失败，订单号：{$attr['sn']}，需要进行额外的处理",CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        		return false;
        	}
        	/*
        	 if(Customer::model()->updateByPk($customer->id, array('balance'=>$balance, 'consume_balance'=>$consumeBalance))){
        	if(!$redPacket->save()  or !$model->save()){
        	//save方法返回bool值，所以需要手动抛出异常
        	throw new Exception('消费饭票失败！');
        	}
        	}else{
        	throw new Exception('消费饭票失败！');return false;
        	}
        	*/
        	// 转账交易不需要请求金融平台，只产生本地交易记录//聚合支付地方饭票走别的接口
        	if($attr['to_type']!=Item::RED_PACKET_TO_TYPE_CARRY && $attr['to_type']!=Item::RED_PACKET_TO_TYPE_RETURN_FP && empty($attr['pay_info'])) {
        		// 普通交易，请求金融平台
				if($attr['customer_id'] == 2222308 && $attr['to_type'] == Item::RED_PACKET_TO_TYPE_GOODS){
					$attr['sn'] = $attr['sn'].'-';
				}
        		$ftransaction = $this->finance_transaction($attr);
        	} else {
				//聚合支付
				if(isset($attr['pay_info']) && !empty($attr['pay_info'])){
					Yii::log('支持地方饭票支付开始：结果：'.($attr['pay_info']),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket');
					//写地方饭票交易流水
					Yii::import('common.services.PayService');
					$payService = new PayService();
					$pay_info = json_decode($attr['pay_info'],true);
					foreach($pay_info as $pay_v){
						if($pay_v['atid'] !=1){
							$payService->makeRedPacket($attr['customer_id'], $attr['sn'], $pay_v['money'],$isLocal=true ,$pay_v['atid'] , $note='');
						}
					}
					Yii::log('地方饭票写入流水结束：结果：'.json_encode($attr),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket');
					$ftransaction = $this->aggregatePay($attr);
					Yii::log('地方饭票支付结束：支付结果：'.json_encode($ftransaction),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket');
				}else{
					// 转账交易，模拟transaction结果
					$ftransaction['payinfo'] = array('tno'=>'simulate');
				}
        	}
        	if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
        		//throw new Exception('消费饭票失败！');
        		(!$isTransaction)?$transaction->rollback():'';
        		Yii::log('饭票交易失败：结果：'.json_encode($ftransaction),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        		return false;
        	}
        
        	(!$isTransaction)?$transaction->commit():'';
        
        	// 不再需要同步
        	/*
        	 //TODO:kakatool 插入金融平台交易同步方法
        	//只要不是转账,都认为是消费
        	//            if($attr['to_type']!=Item::RED_PACKET_TO_TYPE_CARRY){
        	//                FinanceSyncService::getInstance()->customerConsume($attr['customer_id'],$attr['sum'],$note);
        	//            }
        	*/
        	Yii::log(
        	sprintf(
        	'consumeRedPacker finance transaction response: %s',
        	json_encode($ftransaction)
        	),
        	CLogger::LEVEL_ERROR,
        	'colourlife.core.redpacket.consumeRedPacker'
        			);
        	return true;
        }catch(Exception $e) {
            (!$isTransaction)?$transaction->rollback():'';
            Yii::log('consumeRedPacker的try异常，'.json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.consumeRedPacker');
        }
        return false;
    }

    /**
     * 请求金融平台交易接口
     * @param array $attr
     * @return  array
     */
    private function finance_transaction($attr=array()){
    	Yii::log(
    	sprintf(
    	'finance_transaction调用: %s',
    	json_encode($attr)
    	),
    	CLogger::LEVEL_ERROR,
    	'colourlife.core.redpacket.finance_transaction'
    			);
        $orgAccount = null;
        $destAccount = null;
        if(isset($attr['ftype']) && isset($attr['ftype']) != 1){
            // 彩之云地方饭票
            // TODO
        } else {
            $r = FinanceMicroService::getInstance()->getCustomerPano();
            Yii::log(
            sprintf(
            'finance_transaction调用,获取pano: %s',
            json_encode($r)
            ),
            CLogger::LEVEL_ERROR,
            'colourlife.core.redpacket.finance_transaction'
            		);
            $pano = $r['pano'];
            $atid = $r['atid'];
            // 彩之云全国饭票
            if($attr['type']!=Item::RED_PACKET_TYPE_ACQUIRE){
                // 如果不是充值交易，获取支付用户金融平台账号
                $orgAccount = FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and pano=:pano and atid=:atid',array(':customer_id'=>$attr['customer_id'],':pano' => $pano,':atid' => $atid));
                if (empty($orgAccount)){
                	Yii::log(
                	"finance_transaction调用,获取饭票关联表的cano不存在",
                	CLogger::LEVEL_ERROR,
                	'colourlife.core.redpacket.finance_transaction'
                			);
                	return false;
                }
                Yii::log(
                sprintf(
                'finance_transaction调用,获取饭票关联表的cano: %s',
                $orgAccount->cano
                ),
                CLogger::LEVEL_ERROR,
                'colourlife.core.redpacket.finance_transaction'
                		);
                $orgcano = $orgAccount->cano;
                $orgatid = $atid;
                $destcano = $pano;
                $destatid = $atid;
            } else if($attr['type']!=Item::RED_PACKET_TYPE_CONSUME){
                // 如果不是充值交易，获取收款用户金融平台账号
                $destAccount = FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and pano=:pano and atid=:atid',array(':customer_id'=>$attr['customer_id'],':pano' => $pano,':atid' => $atid));
                if (empty($destAccount)){
                	Yii::log(
                	"finance_transaction调用,获取饭票关联表的destAccount的cano不存在",
                	CLogger::LEVEL_ERROR,
                	'colourlife.core.redpacket.finance_transaction'
                			);
                			return false;
                }
                Yii::log(
                sprintf(
                'finance_transaction调用,获取饭票关联表的destAccount的cano: %s',
                $destAccount->cano
                ),
                CLogger::LEVEL_ERROR,
                'colourlife.core.redpacket.finance_transaction'
                		);
                $orgcano = $pano;
                $orgatid = $atid;
                $destcano = $destAccount->cano;
                $destatid = $atid;
            }
        }
       		Yii::log(
                sprintf(
                'finance_transaction调用,获取饭票关联表的destAccount的cano: %s',
                $orgatid.'_'.$orgcano.'_'.$destatid.'_'.$destcano
                ),
                CLogger::LEVEL_ERROR,
                'colourlife.core.redpacket.finance_transaction'
                		);
       		if (isset($attr['remark']) && !empty($attr['remark'])){
       			$remark = $attr['remark'];
       		}else {
       			$remark = $attr['note'];
       		}
        //执行交易操作
        return FinanceMicroService::getInstance()->fastTransaction(
            $attr['sum'],           //交易金额
            $remark,          //备注
            $orgatid,                  //支付类型
            $orgcano,               //支付账号
            $destatid,                  //收款类型
            $destcano,              //收款账号
            $attr['note'],          //备注
            '',                     //回调方法，快速交易不需要设置
            $attr['sn']             //本地交易编号
        );

    }


	/**
	 * 请求金融平台交易接口（支持地方饭票聚合支付接口）2017-04-14 //2017-09-29不支持聚合支付，史上被坑的第二惨的一次
	 * $orderno  	业务系统交易编号
	 * $content  	交易说明（显示给用户的内容）
	 * $orgaccounts  	支付帐号，json数组，如[{"atid":1,"ano":"xxxxx","money":1.00}]
	 * $desttype 	收款账号类型，atid
	 * $atid		账号类型	可选
	 * $ano			账号编号	可选
	 * $starttime	查询起始时间，时间戳	可选
	 * $stoptime	查询结束时间，时间戳	可选
	 *$transtype	交易类型	可选
	 * $ispay		是否只匹配支付参数，0全部，1支付，2收款	可选
	 * $skip		忽略记录数	默认0
	 * $limit		最大记录数	默认20
	 */
	/**
	 * @param array $attr
	 * @return mixed
	 * @throws CHttpException
	 */
	private function aggregatePay($attr=array()){
		Yii::log(
			sprintf(
				'aggregatePay: %s',
				json_encode($attr)
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.redpacket.aggregatePay'
		);
		if(!isset($attr['pay_info']) || empty($attr['pay_info'])){
			Yii::log(
				"aggregatePay,地方饭票支付信息不存在",
				CLogger::LEVEL_ERROR,
				'colourlife.core.redpacket.aggregatePay'
			);
			return false;
		}
		$pay_info = json_decode($attr['pay_info'] , true);
		Yii::log('地方饭票支付开始：请求参数：'.json_encode($pay_info),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket.aggregatePay');
		foreach($pay_info as $v){
			$orgAccount = FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and cano=:cano and atid=:atid',array(':customer_id'=>$attr['customer_id'],':cano' => $v['ano'],':atid' => $v['atid']));
			if(empty($orgAccount)){
				Yii::log(
					"aggregatePay,获取饭票关联表的pano不存在",
					CLogger::LEVEL_ERROR,
					'colourlife.core.redpacket.aggregatePay'
				);
				return false;
			}
		}
		$r = FinanceMicroService::getInstance()->getCustomerPano();
		$atid = $r['atid'];
		$destaccountno = $r['pano'];
		//2017-10-01获取用户全国饭票的金融账号，作为地方饭票转全国饭票的收款账号、订单支付的支付账号
		$orgAccount = FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and pano=:pano and atid=:atid',array(':customer_id'=>$attr['customer_id'],':pano' => $destaccountno,':atid' => $atid));
		if(empty($orgAccount))
		{
			Yii::log(
				"finance_transaction调用,获取饭票关联表的cano不存在",
				CLogger::LEVEL_ERROR,
				'colourlife.core.redpacket.finance_transaction'
			);
			return false;
		}
		if (isset($attr['remark']) && !empty($attr['remark'])){
			$remark = $attr['remark'];
		}else {
			$remark = $attr['note'];
		}

		//执行饭票转换交易操作
		$export_result =  FinanceMicroService::getInstance()->fastTransaction(
			$pay_info[0]['money'],
			$remark,
			$pay_info[0]['atid'],
			$pay_info[0]['ano'],
			$atid,
			$orgAccount->cano,
			$remark,
			$callback = '',
			$attr['sn']
		);

		//执行全国饭票转账至彩网总账
		if($export_result || isset($export_result['payinfo']) || isset($export_result['payinfo']['tno']))
		{
			return FinanceMicroService::getInstance()->fastTransaction(
				$pay_info[0]['money'],
				$remark,
				$atid,
				$orgAccount->cano,
				$atid,
				$destaccountno,
				$remark,
				$callback = '',
				$attr['sn'].'+'
			);
		}else{
			Yii::log(
				$attr['customer_id']."饭票转换交易操作失败".json_encode($export_result),
				CLogger::LEVEL_ERROR,
				'colourlife.core.redpacket.finance_transaction.export'
			);
			return false;
		}

	}

    /**
     * @param $customer_id 用户ID
     * @param $amount   需要支出的金额
     * @return bool
     * 判断需要支出的金额是否超出用户余额
     */
    public function checkMoney($customer_id,$amount){
        if(empty($customer_id) or empty($amount)){
            return false;
        }
        if(!is_numeric($amount)){
            return false;
        }
        $customer = Customer::model()->findByPk($customer_id);

        if(empty($customer)){
            return false;
        }
        if($amount <= $customer->getBalance()){
            return true;
        }
        return false;
    }

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
        $criteria->with[]="customer";
        $criteria->compare("customer.username",$this->username,true);
        $criteria->compare("customer.name",$this->name,true);
        $criteria->compare("customer.mobile",$this->mobile,true);

        $community_ids = array();
        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        $branchIds = $employee->mergeBranch;
        //判断小区权限
        if (!empty($employee->branch)) {
            foreach ($branchIds as $branchId) {
                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
                $community_ids = array_unique(array_merge($community_ids, $data));
            }
            $criteria->addInCondition('customer.community_id', $community_ids);
        }
        if ($this->branch_id != '') {
            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');

            $criteria->addInCondition('customer.community_id', $community_ids);
        } else if ($this->region != '') //如果有地区
        {
            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
            $criteria->addInCondition('customer.community_id', $community_ids);
        }
        $criteria->compare("customer.community_id",$this->community_id);
		$criteria->compare('`t`.type',$this->type);
		$criteria->compare('`t`.customer_id',$this->customer_id);
		$criteria->compare('`t`.from_type',$this->from_type);
		$criteria->compare('`t`.to_type',$this->to_type);
		$criteria->compare('`t`.sum',$this->sum);
		$criteria->compare('`t`.create_time',$this->create_time);
		$criteria->compare('`t`.note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => '`t`.create_time DESC'
            ),
		));
	}

    public function new_search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->with[]="customer";
        $criteria->compare("customer.username",$this->username,true);
        $criteria->compare("customer.name",$this->name,true);
        $criteria->compare("customer.mobile",$this->mobile,true);

        $community_ids = array();
        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        $branchIds = $employee->mergeBranch;
        $this_type = array(1,2);
        //判断小区权限
        if (!empty($employee->branch)) {
            foreach ($branchIds as $branchId) {
                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
                $community_ids = array_unique(array_merge($community_ids, $data));
            }
            $criteria->addInCondition('customer.community_id', $community_ids);
        }
        if ($this->branch_id != '') {
            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');

            $criteria->addInCondition('customer.community_id', $community_ids);
        } else if ($this->region != '') //如果有地区
        {
            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
            $criteria->addInCondition('customer.community_id', $community_ids);
        }
        $criteria->compare("customer.community_id",$this->community_id);
        if($this->type==1){
            $criteria->compare('`t`.type',$this_type);
        }
        if($this->type==0){
            $criteria->compare('`t`.type',$this->type);
        }
        $criteria->compare('`t`.customer_id',$this->customer_id);
        $criteria->compare('`t`.from_type',$this->from_type);
        $criteria->compare('`t`.to_type',$this->to_type);
        $criteria->compare('`t`.sum',$this->sum);
        $criteria->compare('`t`.create_time',$this->create_time);
        $criteria->compare('`t`.note',$this->note,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => '`t`.create_time DESC'
            ),
        ));
    }



    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getDetail(){
       if($this->type==Item::RED_PACKET_TYPE_CONSUME){//消费饭票
           if($this->to_type==Item::RED_PACKET_TO_TYPE_ADVANCE_FEES_PAYENT){//预缴费消费饭票
               return "缴预缴费消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ARREARS_CONSUMPTION_PAYMENT){//欠费消费饭票
               return "缴物业费消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_PARKING_FEES_PAYMENT){//缴停车费饭票
               return "缴停车费消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_GOODS_PAYMENT){
               return "商品消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_VIRTUALRECHARGE_PAYMENT){//手机充值消费饭票金额
               return "手机充值消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ROB_MOON_CAKES){
               return "抢月饼消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ROB_PERFECT_CRAB){
               return "完美蟹逅消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_POWER_FEES){
               return "商铺买电消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_TIXIANXIAOFEI){
               return "提现支出饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ROB_MILK){
               return "抢牛奶消费饭票金额";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ROB_CAR_MAY){
               return "幸运抽汽车消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ROB_GOOD_GIFT){
               return "抽奖获得精美礼品消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ROB_HEI_MEI_JIU){
               return "抽奖获得黑莓酒礼盒消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ROB_TONIC_MAY){
               return "抽奖获得甜蜜红枣消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_LOTTERY_MAY_CAR){
               return "饭票抽奖消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_WEISHANGQUAN_PAY){
               return "微商圈消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_THIRD_PAYMENT){
               return "第三方支付消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_CARRY){
               return "彩之云转账消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_RETURN){
               return "扣回赠送饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_YUN_YIDA){
               return "云易达消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ERUHUO_LOTTERY){
               return "E入伙消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_GOODS){
               return "第三方退款消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_INSURE){
               return "购买彩富保险消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_SPECIALTY){
               return "购买复合公司服务消费饭票";
           }else if($this->to_type==Item::RED_PACKET_TO_TYPE_ORDER_PAY){
			   return "订单支付消费饭票";
		   }else if($this->to_type==Item::RED_PACKET_TO_TYPE_MEAL_TICKET){
			   return "购买饭票券消费饭票";
		   }else if($this->to_type==Item::RED_PACKET_TO_TYPE_RETURN_FP){
			   return "活动追回饭票";
		   }else{
		       return $this->note;
           }

       }else if($this->type==Item::RED_PACKET_TYPE_ACQUIRE){//获得饭票
           if($this->from_type==Item::RED_PACKET_FROM_TYPE_ADVANCE_FEES){//预缴费获得饭票
               return "预缴费获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_ARREARS_REFUND){
               return "欠费退款获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_LOTTERY){
               return "抽奖获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_PARKING_FEES_REFUND){
               return "停车费退款获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_GOODS){
               return "商品退款获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_INVITE){
               return "邀请好友注册获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_WARM_PURSE){
               return "冬日饭票活动完成任务获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_NINA_MIANDAN){
               return "年货免单/送送送活动获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_MOON_CAKES){
               return "购月饼套餐获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_JULY_TRAFFIC_SUBSIBY){
               return "七月份流量补助获得饭票";
           }else if ($this->from_type==Item::RED_PACKET_FROM_TYPE_AUGUST_TRAFFIC_SUBSIBY){
               return "八月份流量补助获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_VIRTUALRECHARGE_REFUND){
               return "充值退款获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_E_MONEY_AWARD){
               return "E理财千分之五奖励获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_CHANGE_REDPACKET){
               return "APP抽中二等奖水果改发饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_OCT_MILK){
               return "订购指定牛奶获赠饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_RICE_OIL){
               return "订购指定粮油获赠饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_PURCHASE){
               return "订购指定海外直商品获赠饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_ORDER_SEND){
               return "邀请业主首次购买牛奶获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_ORDER_SEND_RICEOIL){
               return "邀请业主首次购买粮油获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_JULY_MENG_NIU){
               return "7月份蒙牛订单客户赠送饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_AUGUST_RECOMMEND_AWARD){
               return "8月份推荐奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_SEPTEMBER_TRAFFIC_SUBSIBY){
               return "九月份流量补助获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_OCTOBER_TRAFFIC_SUBSIBY){
               return "十月份流量补助获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_SEPTEMBER_RECOMMEND_ELICAI){
               return "九月E理财奖励提成获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_OCTOBER_RECOMMEND_ELICAI){
               return "十月E理财奖励提成获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_WEIXIN_AWARD){
               return "微信活动中奖获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_BAIWANGDAJIANG){
               return "百万大奖获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_NOVEMBER_RECOMMEND_ELICAI){
               return "十一月E理财奖励提成获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_DECEMBER_RECOMMEND_ELICAI){
               return "十二月E理财奖励提成获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_OA_CARRY){
               return "OA转账获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_V23_REDPACKET){
               return "注册奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_E_LICAI_TUIJIAN_AWARD){
               return "E理财推荐奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_SPRING_REGISTER){
               return "2015春节注册奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_NEW_CUSTOMER_REGISTER){
               return "用户活动注册奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_ERUHUO_LOTTERY){
               return "E入伙抽奖获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_MANAGER_AWARD){
               return "总经理奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_CUSTOMER_RECHARGE){
               return "饭票充值获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_ELICAI_DINGQI_TICHEN_JIANGLI){
               return "E理财定期提成奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_RXH_LICAI_TICHEN_JIANGLI){
               return "荣信汇理财提成奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI){
               return "彩富人生推荐提成奖励";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_EJIAFANG_COMMENT){
               return "E家访评论获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_REDPACKETFEES){
               return "饭票充值获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_BUY_LITCHI_AWARD){
               return "购买指定荔枝商品获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_INVITE_BUY_LITCHI){
               return "邀请业主购买荔枝获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_NEWS_REPORT){
               return "彩生活发布会获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_REDPACKET_FEES_ACTIVITY){
               return "感恩回馈，充饭票满100送2元，活动";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_CARRY){
               return "彩之云转账获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_NITOUSU_WOSONG_QIAN){
               return "你投诉我送钱赠送饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_ACCOUNT_MANAGER_REWARD){
               return "客户经理试点方案奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_HAPPY_SUMMER_ACTIVITY){
               return "欢乐一夏·畅享自游饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_JITI_JIANGJIN_AWARD){
               return "集体奖金包";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_TRANSFER_RED_PACKET) {//OA红包转到彩之云红包
               return "OA红包转到彩之云红包";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_MID_AUTUMN_FESTIVAL) {//中秋团圆福利
               return "中秋团圆福利";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_PROFIT_CONTINUOUS_CFRS) {
               return "彩富人生续投赠送饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_XIAN_ACTIVITY) {
               return "预缴费活动送饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_HZ_ACTIVITY) {
               return "预缴费活动送饭票";
           } else if($this->from_type==Item::RED_PACKET_FROM_TYPE_REWARDS_JIANGLI){
               return "提成发放系统奖励饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_CAI_HOUSE){
               return "彩住宅返还饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_MEAL_TICKET){
               return "饭票券获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_YUN_YIDA){
               return "云易达获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_POWER_FEES_REFUND){
               return "商铺买电退款获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_ARREARS_CONSUMPTION_PAYMENT_REFUND){
               return "物业费退款退款获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_PROFIT_ACTIVITY_JULY){
               return "彩富7月新人礼获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_THIRD_PAYMENT){
               return "第三方支付对应账户获得饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_REFUND_FP){
               return "活动追回饭票";
           }else if($this->from_type==Item::RED_PACKET_FROM_TYPE_LOCAL_REDPACKETFEES){
			   return "地方饭票兑换获得全国饭票";
		   }else{
               return $this->note;
           }

		} else {
			return " ";
		}
	}

	protected function ICEGetSearchRegionData($search = array())
	{
		return array(
			'province_id' => isset($search['province_id']) && $search['province_id']
				? $search['province_id'] : '',
			'city_id' => isset($search['city_id']) && $search['city_id']
				? $search['city_id'] : '',
			'district_id' => isset($search['district_id']) && $search['district_id']
				? $search['district_id'] : '',
		);
	}

	protected function ICEGetLinkageRegionDefaultValueForUpdate()
	{
		return array();
	}

	public function ICEGetLinkageRegionDefaultValueForSearch()
	{
		$searchRegion = $this->ICEGetSearchRegionData(isset($_GET[__CLASS__])
			? $_GET[__CLASS__] : array());

		$defaultValue = array();

		if ($searchRegion['province_id']) {
			$defaultValue[] = $searchRegion['province_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['city_id']) {
			$defaultValue[] = $searchRegion['city_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['district_id']) {
			$defaultValue[] = $searchRegion['district_id'];
		} else {
			return $defaultValue;
		}

		return $defaultValue;
	}

	public function ICEGetLinkageRegionDefaultValue()
	{
		$updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
		return $updateDefaults
			? $updateDefaults
			: $this->ICEGetLinkageRegionDefaultValueForSearch();
	}


    /**
     * 订单消费明细
     * @return array|string
     */
	public function getTypeName(){
        $sn = $this->sn;
        //获取模型类型
        $model_string = SN::findModelBySN($sn);

        if($model_string == 'OthersPropertyFees' || $model_string == 'OthersParkingFees' || $model_string == 'OthersPowerFees' || $model_string == 'OthersFees' || $model_string == 'OthersVirtualRecharge'){
            $model = new $model_string();
            $model->model = $model->objectModel;
            $model_name = $model->getModelNames();
            if($model_name){
                return $model_name;
            }else{
                return $this->getDetail();
            }
        }
        elseif ($model_string == 'PropertyActivity'){
            $model = SN::findContentBySN($sn);
            if(!empty($model)){
                if($model->model == 'ParkingFees'){
                    return '冲抵停车';
                }elseif ($model->model == 'ParkingFeesMonth'){
                    return '月卡停车费冲抵';
                }elseif ($model->model == 'PropertyActivity'){
                    return '冲抵物业费';
                }elseif ($model->model == 'PropertyFees'){
                    return '欠费冲抵物业费';
                }else{
                    return $this->getDetail();
                }
            }else{
                return $this->getDetail();
            }

        }
        elseif ($model_string == 'RedpacketFees'){
            return '红包充值';
        }
        elseif ($model_string == 'CaiRedpacketTixian'){
            return '彩管家红包提现';
        }
        elseif ($model_string == 'CaiRedpacketCarry'){
            $model = SN::findContentBySN($sn);
            if(empty($model)){
                return $this->getDetail();
            }
            if($this->type==Item::RED_PACKET_TYPE_CONSUME) {//消费饭票
                if($model->receiver)
                    return '转账-转给'.$model->receiver->name;
                else
                    return $this->getDetail();
            }elseif ($this->type==Item::RED_PACKET_TYPE_ACQUIRE){
                if($model->employee)
                    return '转账-来自'.$model->employee->name;
                else
                    return $this->getDetail();
            }
        }
        elseif ($model_string == 'ConductData'){
            return '投资E理财';
        }
        elseif ($model_string == 'ElicaiRedpacketTicheng'){
            return 'E理财定期投资提成奖励';
        }
        elseif ($model_string == 'RedPacketCarry'){
            $model = SN::findContentBySN($sn);
            if($this->type==Item::RED_PACKET_TYPE_CONSUME) {//消费饭票
                if(!empty($model) && $model->receiver)
                    return '转账-转给'.$model->receiver->name;
                else
                    return $this->getDetail();
            }elseif ($this->type==Item::RED_PACKET_TYPE_ACQUIRE){
                if(!empty($model) && $model->sender)
                    return '转账-来自'.$model->sender->name;
                else
                    return $this->getDetail();
            }
        }
        elseif ($model_string == 'RxhOrder'){
            return '荣信汇平台';
        }
        elseif ($model_string == 'ConductRedeem'){
            return '彩管家投资E理财';
        }
        elseif ($model_string == 'RedpacketInternalTransaction'){
            return $this->getDetail();
        }
        elseif ($model_string == 'Order' || $model_string == 'CustomerOrder' || $model_string == 'SellerOrder'){
            $model = SN::findContentBySN($sn);
            return !empty($model) && $model->seller ? $model->seller->name : $this->getDetail();
        }
        elseif ($model_string == 'RetreatOrder'){
            $model = SN::findContentBySN($sn);
            return !empty($model) && $model->sellerInfo ? $model->sellerInfo->name.'退款' : $this->getDetail();
        }
        elseif ($model_string == 'ThirdFees'){
            $model = SN::findContentBySN($sn);
            if($model && $model->ThirdFeesAddr){
                $re = ThirdFeesSeller::model()->find('cId=:cid', array(':cid' => $model->ThirdFeesAddr->cId));
                if ($re){
                    $str = $re->name;
                } else $str = $this->getDetail();
            }else{
                $str = $this->getDetail();
            }
            return $str;
        }
        elseif ($model_string == 'Rewards'){
            return '奖励';
        }else{
            return $this->getDetail();
        }

    }

}
