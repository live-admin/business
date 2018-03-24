<?php
class Item {
    //定义常量
    const DELETE_YES = 1; //已删除
    const DELETE_ON = 0; //未删除
    const STATE_YES = 1; //状态禁用
    const STATE_ON = 0; //状态启用
    //支付状态
    const PAY_STATUS_OK = 1; //已支付
    const PAY_STATUS_NO = 0; //未支付
    //商家对应小区的关系状态
    const SHOP_COMMUNITY_STATUS_OK = 1;
    const SHOP_COMMUNITY_STATUS_NO = 0;
    //支付方式状态
    const PAYMENT_STATE_OK = 0; //正常支付方式
    const PAYMENT_STATE_NO = 1; //关闭支付方式 
    //支付方式状态
    const PAY_STATUE_NO = 0; //末付款
    const PAY_STATUE_OK = 1; //已付款
    //默认账号前缀
    const User_Prefix = 'user_';
    //订单相关状态
    const CUSTOMER_ORDER_LOCK = 1; //订单已锁定
    const CUSTOMER_ORDER_UNLOCK = 0; //订单未锁定
    const ORDER_USE_RED_PACKER_YES = 1; //订单使用饭票抵扣的金额已从用户账号扣除
    const ORDER_USE_RED_PACKER_NO = 0; //订单使用饭票抵扣的金额尚未从用户账号扣除
    //订单关联的商品的相关状态
    const GOODS_LOCK = 1; //商品已锁定
    const GOODS_UNLOCK = 0; //商品未锁定
    const GOODS_USE_INTEGRAL_YES = 1; //商品使用积分抵扣金额的对应积分已从用户账号扣除
    const GOODS_USE_INTEGRAL_NO = 0; //商品使用积分抵扣金额的对应积分尚未从用户账号扣除
    //业主订单对应的取消/退货订单状态
    const RETREAT_ORDER_BUYER_APPLY = 0; //(买家申请退款/退货)[商家]退款/退货审核(通过类型判断是退款还是退货)
    const RETREAT_ORDER_AWAITING_GOODS = 1; //(商家/仲裁同意)[平台]待退款
    const RETREAT_ORDER_APPLY_ARBITRATE = 2; //退货仲裁(同意=>待发货,不同意=>退货失败,仲裁都是针对是否同意买家)
    const RETREAT_ORDER_AWAITING_BACK_GOODS = 3; //(商家同意退货)[买家]待发货
    const RETREAT_ORDER_AWAITING_BACK_CONFIRM = 4; //(买家已发货)[商家]待收货
    const RETREAT_ORDER_REFUND_REJECT = 5; //(买家申请退款/退货)[商家]拒绝退款/退货
    const RETREAT_ORDER_AWAITING_ARBITRATE = 6; //退款仲裁(同意=>待退款,不同意=>退货/退款失败,仲裁都是针对是否同意买家)
    const RETREAT_ORDER_REFUND_FAILED = 97; //退款/退货失败(通过类型判断是退款还是退货)
    const RETREAT_ORDER_REFUND_SUCCESS = 99; //(平台结算)退款成功
    //业主订单和加盟订单主状态
    const ORDER_AWAITING_PAYMENT = 0; //已下单，未付款
    const ORDER_AWAITING_GOODS = 1; //已付款，待发货
    const ORDER_AWAITING_CONFIRM = 3; //已发货，待收货
    const ORDER_TRANSACTION_SUCCESS = 4; //买家已收货
    const ORDER_TRANSACTION_SUCCESS_CLOSE = 99; //待结算
    const ORDER_BALANCE_DEDUCT_FAILED = 96; //交易失败
    const ORDER_CANCEL_CLOSE = 97; //取消订单
    const ORDER_CANCEL_REFUND = 102; //已退款
    const ORDER_CANCEL_REFUND_APART = 103; //部分退款

    //加盟退货订单的状态
    const SELLER_ORDER_APPLY_BACK = 0; //退货审核
    const SELLER_ORDER_AWAITING_REFUND = 1; //待退款
    const SELLER_ORDER_AWAITING_GOODS = 2; //待发货
    const SELLER_ORDER_AWAITING_CONFIRM = 3; //已发货
    const SELLER_ORDER_APPLY_BACK_REJECT = 4; //拒绝退货
    const SELLER_ORDER_AWAITING_ARBITRATE = 5; //退货仲裁，商家拒绝退货后的仲裁
    const SELLER_ORDER_APPLY_ARBITRATE = 6; //申请仲裁 买家申请退货，商家拒绝后的状态
    const SELLER_ORDER_ARBITRATE_SUCCESS_CLOSE = 98; //退货失败
    const SELLER_ORDER_REFUND_SUCCESS_CLOSE = 99; //退款成功

    //彩富人生订单状态
    const PROFIT_ORDER_INIT = 0; //待付款
    const PROFIT_ORDER_AUTHORIZE = 1; //已授权
    const PROFIT_ORDER_SUCCESS = 99; //交易成功
    const PROFIT_ORDER_CANCEL = 98; //订单已取消
    const PROFIT_ORDER_REFUND = 90; //已退款
    const PROFIT_ORDER_CONTINUOUS = 96; //已续投
    const PROFIT_ORDER_EXTRACT_ING = 87; //提现中
    const PROFIT_ORDER_EXTRACT_SUCCESS = 88; //提现成功
    const PROFIT_ORDER_EXTRACT_FAIL = 97; //提现失败
    const PROFIT_ORDER_REDEEM_ING = 100; //赎回中
    const PROFIT_ORDER_REDEEM_SUCCESS = 101; //已赎回，待消单
    const PROFIT_ORDER_REDEEM_FAIL = 102; //赎回失败
    const PROFIT_ORDER_REDEEM_DONE = 103; //赎回成功

    // 保险订单状态
    const INSURE_ORDER_INIT = 0; // 待付款
    const INSURE_ORDER_PAYED = 1; // 已付款
    const INSURE_ORDER_SUCCESS = 2; // 已出单

    const SPECIALTY_ORDER_INIT = 0; //待付款
    const SPECIALTY_ORDER_PAYED = 1; //已付款
    const SPECIALTY_ORDER_SUCCESS = 2; //已付款

    //其它订单费用状态
    const FEES_AWAITING_PAYMENT = 0; //待付款
    //const FEES_AWAITING_GOODS = 1; //已付款
    const FEES_TRANSACTION_REFUND = 90; //退款
    const FEES_TRANSACTION_LACK = 96; //交易失败(饭票余额不足)
    const FEES_TRANSACTION_FAIL = 97; //交易失败
    const FEES_TRANSACTION_SUCCESS = 99; //交易成功
    const FEES_TRANSACTION_ERROR = 1; //已付款
    const FEES_CANCEL = 98; //取消订单
    const FEES_RECHARGEING = 101; //订单充值中
    const FEES_PART_REFUND = 117; //部分退款
    //内部采购订单状态
    const ORDER_PURCHASE_PAYMENT = 0; //已下单，待付款
    const ORDER_PURCHASE_GOODS = 1; //已付款，待发货
    const ORDER_PURCHASE_APPROVAL_START = 2; //已提交审批
    const ORDER_PURCHASE_APPROVAL_SUCCESS = 5; //审批成功
    const ORDER_PURCHASE_CONFIRM = 3; //已发货，待确认
    const ORDER_PURCHASE_TRANSACTION_SUCCESS = 4; //买家已收货
    const ORDER_PURCHASE_SUCCESS_CLOSE = 99; //交易成功
    const ORDER_PURCHASE_DEDUCT_FAILED = 96; //积分扣除失败
    const ORDER_PURCHASE_CANCEL_CLOSE = 97; //取消订单
    //支付表的状态
    const PAY_WAIT_STATEMENT = 0; //等待结算
    const PAY_STATEMENT = 1; //订单完成可以结算
    const PAY_STATEMENT_OK = 2; //订单完成可以结算
    //虚拟充值类型
    const VIRTUAL_MOBILE_TYPE = 0; //手机号
    const VIRTUAL_QQ_TYPE = 1; //qq
    const VIRTUAL_GAME_TYPE = 2; //game
    //用户激活状态
    const USER_ACTIVATED = 0;
    const USER_WAIT_ACTIVATE = 1;
    //优惠卷和小区的关联状态
    const DISCOUNT_COMMUNITY_YES = 1; //已服务
    const DISCOUNT_COMMUNITY_NO = 0; //未服务
    //小区启禁用状态
    const COMMUNITY_STATE_YES = 1; //禁用
    const COMMUNITY_STATE_NO = 0; //启用
    //业主投诉的状态
    const COMPLAIN_REPAIRS_AWAITING_HANDLE = 0; //(业主发起投诉或质检中心审核不同意关闭),待处理
    const COMPLAIN_REPAIRS_AWAITING_RECEIVE = 1; //(400已指派或员工流转后),待(执行人)接收          ｛已删除｝
    const COMPLAIN_REPAIRS_RECEIVE_HANDLING = 2; //(执行人已接收),处理中
    const COMPLAIN_REPAIRS_HANDLE_END = 3; //(执行人)处理完成
    const COMPLAIN_REPAIRS_CONFIRM_END = 4; //(确认人)确认完成
    const COMPLAIN_REPAIRS_POOR_VISIT = 5; //(业主差评)待400回访                                ｛已删除｝
    const COMPLAIN_REPAIRS_PUBLIC_NONE = 10; //公共报修时，指定小区主任，可转接给400服务中心
    const COMPLAIN_REPAIRS_APPLY_COLOSE = 97; //400申请关闭                                     ｛已删除｝
    const COMPLAIN_REPAIRS_ABNORMAL_COLOSE = 98; //(质检中心同意400的关闭申请),投诉结束            ｛已删除｝
    const COMPLAIN_REPAIRS_SUCCESS_COLOSE = 99; //(业主评分后),投诉结束
    //投诉报修的type字段 
    const COMPLAIN_REPAIRS_TYPE_CUSTOMER = 0; //业主投诉
    const COMPLAIN_REPAIRS_TYPE_EMPLOYEE = 1; //员工投诉
    const COMPLAIN_REPAIRS_TYPE_PERSON = 2; //个人报修
    const COMPLAIN_REPAIRS_TYPE_PUBLIC = 3; //公共报修
    //投诉报修日志表中type字段，记录类型
    const COMPLAIN_REPAIRS_LOG_TYPE_NORMAL = 0; //常规记录
    const COMPLAIN_REPAIRS_LOG_TYPE_HANDLE = 1; //处理记录
    const COMPLAIN_REPAIRS_LOG_TYPE_SUPERVISION = 2; //监督指导记录
    //个人报修的状态
    const PERSONAL_REPAIRS_AWAITING_HANDLE = 0; //个人报修业主下单后的状态
    const PERSONAL_REPAIRS_RECEIVE_HANDLING = 2; //(执行人已接收),处理中
    const PERSONAL_REPAIRS_HANDLE_END = 3; //(执行人)处理完成
    const PERSONAL_REPAIRS_CONFIRM_END = 4; //(确认人)确认完成
    const PERSONAL_REPAIRS_ASSIST_HANDLE = 8; //（商家4小时未处理）,没有找到小区主任/400协助处理   [已删除]
    const PERSONAL_REPAIRS_SUCCESS_COLOSE = 99; //(业主评分后),结束
    //-----------个人报修商家状态----------
    const PERSONAL_REPAIRS_SHOP_ACCEPTED = 5; //(商家)已接收
    const PERSONAL_REPAIRS_SHOP_HAS_SERVED = 6; //(商家）已服务
    const PERSONAL_REPAIRS_SHOP_REFUSAL = 7; //(商家)已拒绝
    const PERSONAL_REPAIRS_ABNORMAL_COLOSE = 98; //(商家/小区主任/400)拒绝,结束
    //投诉报修前台状态
    const COMPLAIN_REPAIRS_LOG_AWAITING_HANDL = 0; //待处理
    const COMPLAIN_REPAIRS_LOG_HANDLING = 1; //处理中
    const COMPLAIN_REPAIRS_LOG_BAD_HANDLING = 2; //差评处理中
    const COMPLAIN_REPAIRS_LOG_CONFIRM_END = 3; //处理完成（已确认），待评论
    const COMPLAIN_REPAIRS_LOG_COMMENT = 4; //评论完成
    const COMPLAIN_REPAIRS_LOG_COLOSE = 5; //结束
    //前台投拆报修统一状态
    const COMPLAIN_REPARS_START = 1; //提交
    const COMPLAIN_REPARS_ING = 2; //处理中
    const COMPLAIN_REPARS_EVALUATION = 3; //处理完成
    const CONPLAIN_REPARS_COMPLETE = 4; //评价完成
    const COMPLAIN_REPAIRS_REFUSE = 5; //个人报修拒绝状态
    //投诉报修详情当前负责人ID
    const COMPLAIN_REPAIRS_DETAIL_SERVICE = -1; //集团信息中心
    const COMPLAIN_REPAIRS_DETAIL_QUALITY = -2; //质检中心
    const COMPLAIN_REPAIRS_DETAIL_CUSTOMER = -3; //待业主评价
    const COMPLAIN_REPAIRS_DETAIL_UNKNOWN = -4; //未知的
    const PERSONAL_REPAIRS_SHOP_STATE = -5; //商家
    const PERSONAL_REPAIRS_EXRCUTE_STATE = -6; //物业
    //注册用户唯一标识符COOKIE
    const CUSTOMER_USER_AGENT_COOKIE_NAME = 'customer_user_agent'; //Cookie保存名称
    ##########  饭票常量  ############
    const RED_PACKET_TYPE_CONSUME = 0; //消费饭票
    const RED_PACKET_TYPE_ACQUIRE = 1; //获得饭票
    const RED_PACKET_FROM_TYPE_ADVANCE_FEES = 1; //预缴费获得饭票
    const RED_PACKET_FROM_TYPE_ARREARS_REFUND = 2; //欠费退款获得饭票
    const RED_PACKET_FROM_TYPE_LOTTERY = 3; //抽奖获得饭票
    const RED_PACKET_FROM_TYPE_PARKING_FEES_REFUND = 4; //停车费退款获得饭票
    const RED_PACKET_FROM_TYPE_GOODS = 5; //商品退款饭票
    const RED_PACKET_FROM_TYPE_BUGS = 6; //有奖捉虫饭票
    const RED_PACKET_FROM_TYPE_WORLD_CUP_VS = 7; //世界杯竞猜胜负饭票
    const RED_PACKET_FROM_TYPE_WORLD_CUP_PROMOTION = 8; //世界杯竞猜晋级饭票
    const RED_PACKET_FROM_TYPE_POWER_FEES = 9; //商铺买电获取饭票
    const RED_PACKET_FROM_TYPE_INVITE = 10; //邀请注册送饭票
    const RED_PACKET_FROM_TYPE_MOON_CAKES = 11; //购月饼满100送饭票
    const RED_PACKET_FROM_TYPE_JULY_TRAFFIC_SUBSIBY = 12; //七月流量补助获得饭票
    const RED_PACKET_FROM_TYPE_AUGUST_TRAFFIC_SUBSIBY = 13; //八月流量补助获得饭票
    const RED_PACKET_FROM_TYPE_VIRTUALRECHARGE_REFUND = 14; //充值退款获得饭票
    const RED_PACKET_FROM_TYPE_E_MONEY_AWARD = 15; //E理财千分之五奖励获得饭票
    const RED_PACKET_FROM_TYPE_CHANGE_REDPACKET = 16; //APP抽中二等奖水果改发饭票
    const RED_PACKET_FROM_TYPE_OCT_MILK = 17; //购指定牛奶获赠饭票
    const RED_PACKET_FROM_TYPE_ORDER_SEND = 18; //邀请业主首次购买牛奶获得饭票
    const RED_PACKET_FROM_TYPE_JULY_MENG_NIU = 19; //7月份蒙牛订单客户赠送饭票
    const RED_PACKET_FROM_TYPE_AUGUST_RECOMMEND_AWARD = 20; //7月份蒙牛订单客户赠送饭票
    const RED_PACKET_FROM_TYPE_SEPTEMBER_TRAFFIC_SUBSIBY = 21; //九月流量补助获得饭票
    const RED_PACKET_FROM_TYPE_OCTOBER_TRAFFIC_SUBSIBY = 22; //十月流量补助获得饭票
    const RED_PACKET_FROM_TYPE_SEPTEMBER_RECOMMEND_ELICAI = 23; //九月E理财奖励提成获得饭票
    const RED_PACKET_FROM_TYPE_OCTOBER_RECOMMEND_ELICAI = 24; //十月E理财奖励提成获得饭票
    const RED_PACKET_FROM_TYPE_WEIXIN_AWARD = 25; //微信活动中奖获得饭票
    const RED_PACKET_FROM_TYPE_BAIWANGDAJIANG = 26; //百万大奖中奖获得饭票
    const RED_PACKET_FROM_TYPE_NOVEMBER_RECOMMEND_ELICAI = 27; //十一月E理财奖励提成获得饭票
    const RED_PACKET_FROM_TYPE_DECEMBER_RECOMMEND_ELICAI = 28; //十二月E理财奖励提成获得饭票
    const RED_PACKET_FROM_TYPE_OA_CARRY = 29; //OA转账获得饭票
    const RED_PACKET_FROM_TYPE_E_LICAI_TUIJIAN_AWARD = 30; //2月E理财和定期理财推荐奖励明细（彩之云）
    const RED_PACKET_FROM_TYPE_NEW_CUSTOMER_REGISTER = 31;  //活动期间新用户注册奖励饭票
    const RED_PACKET_FROM_TYPE_SPRING_REGISTER = 35; //春节期间通过新注册获得饭票
    const RED_PACKET_FROM_TYPE_ERUHUO_LOTTERY = 36; //E入伙抽奖获得饭票
    const RED_PACKET_FROM_TYPE_MANAGER_AWARD = 37; //获得总经理奖励饭票
    const RED_PACKET_FROM_TYPE_RICE_OIL = 38; //购指定粮油获赠饭票
    const RED_PACKET_FROM_TYPE_ORDER_SEND_RICEOIL = 39; //邀请好友购买指定粮油获得饭票(首次)
    const RED_PACKET_FROM_TYPE_CUSTOMER_RECHARGE = 40; //饭票充值增加饭票
    const RED_PACKET_FROM_TYPE_EJIAFANG_COMMENT = 41; //E家访评论获得饭票
    const RED_PACKET_FROM_TYPE_ELICAI_DINGQI_TICHEN_JIANGLI = 42; //E理财定期理财推荐奖励提成饭票
    const RED_PACKET_FROM_TYPE_PURCHASE = 43; //购指定海外直购商品获赠饭票
    const RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI = 44; //彩富人生订单推荐提成奖励饭票
    const RED_PACKET_FROM_TYPE_REDPACKETFEES = 45; //饭票充值累加饭票总额
    const RED_PACKET_FROM_TYPE_BUY_LITCHI_AWARD = 46; //购指定荔枝商品获赠饭票
    const RED_PACKET_FROM_TYPE_INVITE_BUY_LITCHI = 47; //邀请业主购买荔枝商品获得饭票
    const RED_PACKET_FROM_TYPE_NEWS_REPORT = 48; //彩生活发布会记者饭票
    const RED_PACKET_FROM_TYPE_REDPACKET_FEES_ACTIVITY = 49; //饭票充值 充100送 5% 活动
    const RED_PACKET_FROM_TYPE_CARRY = 50; //彩之云转账获得饭票
    const RED_PACKET_FROM_TYPE_BACK=55;
    const RED_PACKET_FROM_TYPE_NITOUSU_WOSONG_QIAN=51; //你投诉,我送钱赠送饭票
    const RED_PACKET_FROM_TYPE_RXH_LICAI_TICHEN_JIANGLI = 52; //荣信汇理财推荐提成奖励饭票
    const RED_PACKET_FROM_TYPE_ACCOUNT_MANAGER_REWARD = 56; //客户经理试点方案奖励饭票
    const RED_PACKET_FROM_TYPE_HAPPY_SUMMER_ACTIVITY = 57; //欢乐一夏·畅享自游饭票
    const RED_PACKET_FROM_TYPE_JITI_JIANGJIN_AWARD = 58; //集体奖金包
    const RED_PACKET_FROM_TYPE_TRANSFER_RED_PACKET = 59; //OA红包转到彩之云红包
    const RED_PACKET_FROM_TYPE_MID_AUTUMN_FESTIVAL  = 60; //中秋团圆福利
    const RED_PACKET_FROM_TYPE_PROFIT_CONTINUOUS_CFRS = 61; //彩富人生续投赠送50饭票
    const RED_PACKET_FROM_TYPE_XIAN_ACTIVITY = 62; //西安事业部预收16年管理费及停车费活动
    const RED_PACKET_FROM_TYPE_REWARDS_JIANGLI = 63; //订单推荐提成奖励红包	
    const RED_PACKET_FROM_TYPE_WARM_PURSE = 64; //冬日饭票活动完成任务送3元饭票
    const RED_PACKET_FROM_TYPE_HZ_ACTIVITY = 65; //惠州事业部预收16年管理费活动
    const RED_PACKET_FROM_TYPE_NINA_MIANDAN = 66; //年货免单/送送送活动获得饭票
    const RED_PACKET_FROM_TYPE_MEAL_TICKET = 67; //饭票券
    const RED_PACKET_FROM_TYPE_CAI_HOUSE = 68; //彩住宅
    const RED_PACKET_FROM_TYPE_YUN_YIDA = 69; //云易达获得饭票
    const RED_PACKET_FROM_TYPE_POWER_FEES_REFUND = 70; //商铺买电退款获得饭票
    const RED_PACKET_FROM_TYPE_ARREARS_CONSUMPTION_PAYMENT_REFUND = 71; //物业费退款获得饭票
    const RED_PACKET_FROM_TYPE_PROFIT_ACTIVITY_JULY = 72; //彩富7月新人礼活动
    const RED_PACKET_FROM_TYPE_THIRD_PAYMENT = 73;//第三方支付获得饭票
    const RED_PACKET_FROM_TYPE_REFUND_FP = 74; //追回饭票
    const RED_PACKET_FROM_TYPE_LOCAL_REDPACKETFEES = 75; //饭票充值累加饭票总额

    const RED_PACKET_TO_TYPE_ADVANCE_FEES_PAYENT = 1;//预缴费消费饭票
    const RED_PACKET_TO_TYPE_ARREARS_CONSUMPTION_PAYMENT = 2;//欠费消费饭票
    const RED_PACKET_TO_TYPE_PARKING_FEES_PAYMENT = 3;//缴停车费饭票
    const RED_PACKET_TO_TYPE_GOODS_PAYMENT = 4;//商品消费饭票
    const RED_PACKET_TO_TYPE_VIRTUALRECHARGE_PAYMENT = 5;//虚拟充值消费饭票
    const RED_PACKET_TO_TYPE_POWER_FEES = 6;//商铺买电消费饭票
    const RED_PACKET_TO_TYPE_ROB_MOON_CAKES = 7;//抢月饼消费饭票
    const RED_PACKET_TO_TYPE_ROB_PERFECT_CRAB = 8;//完美蟹逅活动消费饭票
    const RED_PACKET_TO_TYPE_TIXIANXIAOFEI = 9;//提现消费饭票
    const RED_PACKET_TO_TYPE_ROB_MILK = 10;//抢纯甄牛奶消费饭票
    const RED_PACKET_TO_TYPE_THIRD_PAYMENT = 11;//第三方支付
    const RED_PACKET_TO_TYPE_ROB_CAR_MAY = 12;//2015年5月幸福中国行幸运抽汽车消费饭票
    const RED_PACKET_TO_TYPE_ROB_GOOD_GIFT = 13;//2015年5月幸福中国行抽奖获得精美礼品消费饭票
    const RED_PACKET_TO_TYPE_ROB_HEI_MEI_JIU = 14;//2015年5月幸福中国行抽奖获得黑莓酒礼盒消费饭票
    const RED_PACKET_TO_TYPE_ROB_TONIC_MAY = 15;//2015年5月幸福中国行抽奖获得甜蜜红枣消费饭票
    const RED_PACKET_TO_TYPE_LOTTERY_MAY_CAR = 16;//2015年5月幸福中国行抽奖扣除饭票0.1
    const RED_PACKET_TO_TYPE_REDPACKET_PAYMENT = 17;//饭票充值 2015-06-03
    const RED_PACKET_TO_TYPE_WEISHANGQUAN_PAY = 18;//微商圈支付
    const RED_PACKET_TO_TYPE_CARRY = 19; //彩之云转账消费饭票
    const RED_PACKET_TO_TYPE_RETURN = 20; //扣回赠送饭票
    const RED_PACKET_TO_TYPE_MEAL_TICKET = 21; //购买饭票券消费饭票
    const RED_PACKET_TO_TYPE_YUN_YIDA = 23; //云易达发放饭票
    const RED_PACKET_TO_TYPE_ERUHUO_LOTTERY = 24; //云易达发放饭票
    const RED_PACKET_TO_TYPE_GOODS = 25; //商品退款扣除饭票
    const RED_PACKET_TO_TYPE_INSURE = 26; //购买彩富保险消费饭票
    const RED_PACKET_TO_TYPE_SPECIALTY = 27; //购买复合公司服务消费饭票
    const RED_PACKET_TO_TYPE_ORDER_PAY = 28; //购买复合公司服务消费饭票
    const RED_PACKET_TO_TYPE_RETURN_FP = 29; //扣回饭票

    const RED_PACKET_USED = 1; //已使用饭票
    const RED_PACKET_UNUSED = 0; //未使用饭票


    /** **************************************************************************** **/
    const CAIREDPACKET_LOCK = 1; //饭票活动已锁定
    const CAIREDPACKET_UNLOCK = 0; //饭票活动未锁定
    const CAI_RED_PACKET_FROM_TYPE_GOODJIXAO_AWARD = 1; //绩效奖励获得彩管家饭票
    const CAI_RED_PACKET_FROM_TYPE_TIXIAN_FAIL_REFUND = 2; //彩管家饭票提现失败退还饭票
    const CAI_RED_PACKET_FROM_TYPE_LOOK_EMONEY_AWARD = 3; //找E理财BUG奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_CARRY_EMPLOYEE = 4; //获得同事饭票(同事转账获得饭票)
    const CAI_RED_PACKET_FROM_TYPE_MANAGER_AWARD = 5; //总经理奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_PROPERTY_ACTIVITY_AWARD = 6; //彩富人生推荐奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_SPRING_AWARD = 7; //春节加班奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_CAI_ZHI_JIA_AWARD = 8; //彩之家年终提成奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_38_AWARD = 9; //3.8节日饭票
    const CAI_RED_PACKET_FROM_TYPE_FEBRUARY_FUWU_AWARD = 10; //客户经理服务之星奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_DEC_XINFU_AWARD = 11; //2014年12月份幸福中国行游园奖励
    const CAI_RED_PACKET_FROM_TYPE_NIAN_ZHONG_AWARD = 12; //年终奖励提成饭票
    const CAI_RED_PACKET_FROM_TYPE_E_LICAI_TUIJIAN_AWARD = 13; //E理财和定期理财推荐奖励
    const CAI_RED_PACKET_FROM_TYPE_JITI_JIANGJIN_AWARD = 14; //集体奖金包
    const CAI_RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI = 15; //彩富人生提成自动奖励
    const CAI_RED_PACKET_FROM_TYPE_ELICAI_DINGQI_TICHEN_JIANGLI=16;//E理财定期理财奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_MOST_BEAUTIFUL_SMILE=17;//最美微笑奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_TOUZI_DAOQI_AWARD=18;//彩管家饭票理财到期返还饭票
    const CAI_RED_PACKET_FROM_TYPE_HUAMEI_CAIGUO_JIEKUANG=19;//华美达酒店采购借款奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_RXH_LICAI_TICHEN_JIANGLI=20;//荣信汇理财奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_ACCOUNT_MANAGER_REWARD = 21; //客户经理试点方案奖励饭票
    const CAI_RED_PACKET_FROM_TYPE_CYBGZ_ACTIVITY = 22; //“我的空间我做主”创意办公桌奖
    const CAI_RED_PACKET_FROM_TYPE_EXPERT_IN_CIVIL = 23; //高手在民间，干货征集令
    
    const CAI_RED_PACKET_FROM_TYPE_REWARDS_JIANGLI = 25; //订单提成自动奖励
    const CAI_RED_PACKET_FROM_TYPE_ARREARS_RETURN = 26; //大额欠费回收奖励彩管家

    const CAI_RED_PACKET_TO_TYPE_TIXIAN = 1; //提现消费饭票
    const CAI_RED_PACKET_TO_TYPE_CARRY = 2; //给同事发饭票(转同事)
    const CAI_RED_PACKET_TO_TYPE_COLOURLIFE = 3; //OA转账消费饭票(转彩之云)
    const CAI_RED_PACKET_TO_TYPE_ELICAI_TOUZI = 4; //E理财投资消费饭票
    const CAI_RED_PACKET_TO_TYPE_SYSTEM_DEDUCT = 5; //系统扣除饭票


    /*     * **************************************************************************** */

    ##########  END 饭票常量  ############
    //梦想大看台相关常量
    const DREAM_ACT_ID = 1;   //配置当前活动Id
    //抽奖相关常量
    //最新
	const LUCKY_ACT_ID_CAR = 12;//汽车抽奖ID
    const LUCKY_ACT_ID = 13;   //配置当前活动Id
    const LUCKY_REGIST_NUM = 10;  //注册送抽奖次数
    const LUCKY_LOGIN_NUM = 1;    //登录送抽奖次数
    const LUCKY_ORDER_NUM = 5;    //支付送抽奖次数
    const LUCKY_COMPLAIN = 1;     //投诉报修送抽奖次数
    const LUCKY_INVITE_NUM = 5;   //邀请好友送抽奖次数
    const LUCKY_DAY_MAX = 5;      //每天最多抽奖次数
    const LUCKY_FINISH_INFO = 10; //完善资料送抽奖次数
    const LUCKY_AGAIN_ID = 9;     //“再来一次”奖项的id  
    const LUCKY_THANKS_ID = 124;    //“谢谢参与”奖项的id
    const LUCKY_THANKS_ID_CAR = 137;    //汽车抽奖“谢谢参与”奖项的id
    const LUCKY_SMALL_PRIZE = 118;      //新用户必中奖项
    const LUCKY_ONE_SECOND_ID = 13;      //按出1秒送饭票活动ID

    static $lucky_thanks_angle = array("min" => 61, "max" => 119);  //谢谢参与的圆盘弧度(有些逻辑需要直接返回谢谢参与，连中奖逻辑都不用跑)
    static $lucky_tiyan_branch_ids = array(599);      //体验区组织架构id

    const LUCKY_ELICAI_NUM = 5; //成功投资E理财送抽奖次数
    const LUCKY_EWEIXIU_NUM = 5; //成功使用E维修送抽奖次数
    //old
    // const LUCKY_ACT_ID = 5;   //配置当前活动Id
    // const LUCKY_REGIST_NUM=10;	//注册送抽奖次数
    // const LUCKY_LOGIN_NUM=1;  	//登录送抽奖次数
    // const LUCKY_ORDER_NUM=5;	//支付送抽奖次数
    // const LUCKY_COMPLAIN=1;		//投诉报修送抽奖次数
    // const LUCKY_INVITE_NUM=5;	//邀请好友送抽奖次数
    // const LUCKY_DAY_MAX=5;		//每天最多抽奖次数
    // const LUCKY_FINISH_INFO=10;	//完善资料送抽奖次数
    // const LUCKY_AGAIN_ID=9;		//“再来一次”奖项的id  
    // const LUCKY_THANKS_ID=53;	  //“谢谢参与”奖项的id
    // const LUCKY_SMALL_PRIZE=42;	  //新用户必中奖项
    // static $lucky_thanks_angle=array("min"=>211,"max"=>239);  //谢谢参与的圆盘弧度(有些逻辑需要直接返回谢谢参与，连中奖逻辑都不用跑)
    // static $lucky_tiyan_branch_ids=array(599);		//体验区组织架构id
    //投诉报修执行过，正在监督人定义
    const COMPLAIN_REPAIRS_HANDLING_EXECUTE = 0; //执行过
    const COMPLAIN_REPAIRS_HANDLING_SUPERVISOR = 1; //监督人
    const COMPLAIN_REPAIRS_HANDLING_SUPERVISIONOVER = 2; // 监督过的
    const COMPLAIN_REPAIRS_SATISFACTION = 1; //满意
    const COMPLAIN_REPAIRS_NO_COMMENTS = 0; //不评价
    const COMPLAIN_REPAIRS_DISSATISFIED = -1; //不满意
    //对帐任务单状态
    const TASK_STATUS_NOT_START = 0; //未开始
    const TASK_STATUS_FAILED_DOWNLOAD = 1; //下载失败
    const TASK_STATUS_RECONCILIATION = 2; //对账中
    const TASK_STATUS_RECONCILIATION_PARTIAL_SUCCESS = 3; //对账部分成功
    const TASK_STATUS_RECONCILIATION_ALL_SUCCESS = 4; //对账全部成功
    //对帐任务明细状态
    const TASK_DETAILED_STATUS_NOT_START = 0; //未开始
    const TASK_DETAILED_SUCCESS = 1; //成功
    const TASK_DETAILED_FAIL = 2; //失败，异常
    //饭票 2015.2.15
    const RED_PACKET_FROM_TYPE_V23_REDPACKET = 35;
    //限制短信验证  2015.3.5
    const SMS_LIMIT_VALIDATE = 300;
    //第三方重复下单时间
    const THIRD_ORDER_OVERTIME = 7200;
    //第三方下单回调次数限制 20150512
    const THIRD_REMOTE_SERVER_CALLBACK_NUM = 8;
    //京东商家ID
    const JD_SELL_ID        = 4996;
    const HUANQIU_JINGXUAN  = 4995;
    const DA_ZHA_XIE        = 5009;
    const CAI_TE_GONG       = 4990;
    const CAISHIHUI         = 5034;
    const PINTUAN           = 5033;
    const SIQING            = 5045;
    const SELLER_JD         = 5058; // 京东
    const XFZGX=2607;//幸福中国行 xfzgx
    const GOUWUKA=5072;
    
    //post回调88 2015-06-04
    const POS_PAYMENT_STATUS = 88;
    
    //年年卡充值状态
    const NIAN_RECHARGEING=1;//充值中
    const NIAN_SUCCESS=2;//充值成功
    const NIAN_REFUND=0;//待退款
    const NIAN_BACK=55;//已退款
    
    //年年卡充值类型
    const NIAN_HUAFEI=1;//话费
    const NIAN_LIULIANG=2;//流量
    
    //接入停车场类型
    //停车类别
    const PARKING_TYPE_COLOURLIFE = 100;  // 彩生活
    const PARKING_TYPE_GEMEITE = 101; // 格美特
    const PARKING_TYPE_AIKE = 102; // 艾科
    const PARKING_TYPE_HANWANG = 103; // 汉王
    const PARKING_TYPE_YITINGCHE = 104; // 易停车

    //提货券订单订单状态(1:待发货;2:已发货;3:已收货)
    const THQ_DAI_FAHUO=1;
    const THQ_YI_FAHUO=2;
    const THQ_YI_SHOUHUO=3;
    
    //邀请注册送饭票
    const INVITE_REGISTER_START_TIME="2014-12-22 00:00:00";
    const INVITE_REGISTER_END_TIME="2016-12-28 21:59:59";

}
