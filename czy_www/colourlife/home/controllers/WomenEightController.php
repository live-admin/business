<?php
/*
 * @version 3.8活动
 */
class WomenEightController extends ActivityController{
    public $beginTime='2017-03-02 00:00:00';//活动开始时间
	public $endTime='2017-03-22 23:59:59';//活动结束时间
    public $secret = '@&Women*^%';
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
        WomenEight::model()->addShareLog($this->getUserId(),1); //首页
        $tanCoupons=WomenEight::model()->getCoupon($this->getUserId());
        $oneUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),2);
        $time=time();
		$customer_id = $this->getUserId() * 778 + 1778;
		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
		$urlShare=F::getHomeUrl('/WomenEight/ShareWeb').'?ts='.$time.'&sign='.$sign;
        $this->render('/v2017/womenEight/index', array(
            'tanCoupons'=>$tanCoupons,
            'oneUrl'=>$oneUrl,
            'surl'=>base64_encode($urlShare),
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
		$data = WomenEight::model()->getGoodsList();
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
		$this->render('/v2017/womenEight/goods_nav',array(
			'goods' => $goods,
			'url' => $url,
		));
	}
    /*
     * @version 抽奖页面
     */
    public function actionGetPrize(){
        $leftChance=WomenEight::model()->getChanceValue($this->getUserId());
    	$this->render('/v2017/womenEight/lucky_draw', array(
            'leftChance'=>$leftChance,
        ));
    }
    /*
     * @version 获奖明细页面
     */
    public function actionPrizeDetail(){
        $prizeMobileArr=WomenEight::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2017/womenEight/prize', array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 抽奖按钮ajax
     */
    public function actionChouJiang(){
        $yan=WomenEight::model()->yanZhengChance($this->getUserId());
        if($yan){
            $list=WomenEight::model()->getPrizeByChouJiang($this->getUserId());
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
            exit;
        }
        if(!empty($list)){
            $leftChance=WomenEight::model()->getChanceValue($this->getUserId());
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 分享页面
     */
    public function actionShareWeb(){
        WomenEight::model()->addShareLog($this->getUserId(),3); //分享页面
    }
    /*
     * @version 规则
     */
    public function actionRule(){
       $this->render('/v2017/womenEight/rules');
    }
    /*
     * @version 点击分享
     */
    public function actionFenXiang(){
        WomenEight::model()->addShareLog($this->getUserId(),2); //点击分享
        echo json_encode(array('status'=>1));
    }
}
