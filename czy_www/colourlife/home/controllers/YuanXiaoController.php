<?php
/*
 * @version 元宵节活动
 */
class YuanXiaoController extends ActivityController{
    public $beginTime='2017-02-09 00:00:00';//活动开始时间
	public $endTime='2017-02-28 23:59:59';//活动结束时间
    public $secret = '@&Yuan*Xiao^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Share',
        );
    }
    public function accessRules(){
        return array(
        	array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }
    /*
     * @version 首页页面
     */
    public function actionIndex(){
        YuanXiao::model()->addShareLog($this->getUserId(),1); //首页
        $tanCoupons=YuanXiao::model()->getCoupon($this->getUserId());
        $this->render('/v2016/yuanXiao/index', array(
            'tanCoupons'=>$tanCoupons,
        ));
    }
    /**
	 *@version 专区列表页面,根据type值获取不同的商品列表
	 *$goods  某个专区的商品信息，类型为json数组
	 *$url    进入到不同类型商品的url
	 */
	public function actionArea(){
		$type=  intval(Yii::app()->request->getParam('type'));
		if (empty($type)){
			return false;
		}
		//获取所有商品
		$data = YuanXiao::model()->getGoodsList();
		//var_dump($data);
		if (isset($data[$type])){
			$goods[$type] = $data[$type];
		}else {
			$goods[$type] = array();
		}
		$url['jdUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
		$url['tuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),0);
        $url['huaUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),3);
        $url['xiUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),4);
		$this->render('/v2016/yuanXiao/goods_nav',array(
			'goods' => $goods,
			'url' => $url,
		));
	}
    /**
     *@version 今日疯抢页面 
	 */
	public function actionAreasekill(){
		//获取所有时段今日疯抢商品
		$data = YuanXiao::model()->getSekillGoodsList();
		$time=time();
		$url['jdUrl'] = StockingSeckillGoods::model()->getShopUrl($this->getUserId(),1);
		$url['tuanUrl'] = StockingSeckillGoods::model()->getShopUrl($this->getUserId());
		$this->render('/v2016/yuanXiao/sekill_goods_nav',array(
			'goods' => $data,
			'url' => $url,
			'time'=>$time
		));
	}
    /*
     * @version 抽奖页面
     */
    public function actionGetPrize(){
        $leftChance=YuanXiao::model()->getChanceValue($this->getUserId());
        $time=time();
		$customer_id = $this->getUserId() * 778 + 1778;
		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
		$urlShare=F::getHomeUrl('/YuanXiao/ShareWeb').'?ts='.$time.'&sign='.$sign;
    	$this->render('/v2016/yuanXiao/lucky_draw', array(
            'leftChance'=>$leftChance,
            'surl'=>base64_encode($urlShare)
        ));
    }
    /*
     * @version 获奖明细页面
     */
    public function actionPrizeDetail(){
        $prizeMobileArr=YuanXiao::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2016/yuanXiao/prize', array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 抽奖按钮ajax
     */
    public function actionChouJiang(){
        $yan=YuanXiao::model()->yanZhengChance($this->getUserId());
        if($yan){
            $list=YuanXiao::model()->getPrizeByChouJiang($this->getUserId());
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
            exit;
        }
        if(!empty($list)){
            $leftChance=YuanXiao::model()->getChanceValue($this->getUserId());
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 分享页面
     */
    public function actionShareWeb(){
        YuanXiao::model()->addShareLog($this->getUserId(),3); //分享页面
    }
    /*
     * @version 点击分享
     */
    public function actionFenXiang(){
        YuanXiao::model()->addShareLog($this->getUserId(),2); //点击分享
        echo json_encode(array('status'=>1));
    }
}
