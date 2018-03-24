<?php
/*
 * @version 母亲节活动
 */
class MuQinController extends ActivityController{
    public $beginTime='2017-05-11 00:00:00';//活动开始时间
	public $endTime='2017-05-21 23:59:59';//活动结束时间
    public $secret = '@&Mu*Qin^%';
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
        EntranceCountLog::model()->writeOperateLog($this->getUserId() , '' , $operation_time=time(), 31,'');
        $flag=1;
        $check=MuqinLog::model()->find('customer_id=:customer_id and type=:type',array(':customer_id'=>$this->getUserId(),':type'=>1));
        if(!empty($check)){
            $flag=0; 
        }
        MuQin::model()->addShareLog($this->getUserId(),1); //首页
        $url=array();
        $url['hualiUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),3);
		$url['qianUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),4);
        $url['huanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),7);
        $this->render('/v2017/muQin/index', array(
            'flag'=>$flag,
            'url'=>$url,
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
        $tanCoupons=false;
        if($type==48){
            $tanCoupons=MuQin::model()->getCoupon($this->getUserId());
        }
        MuQin::model()->addShareLog($this->getUserId(),$type);
		//获取所有商品
		$data = MuQin::model()->getGoodsList();
		//var_dump($data);
		if (isset($data[$type])){
			$goods[$type] = $data[$type];
		}else {
			$goods[$type] = array();
		}
		$url['jdUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
		$url['tuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),0);
		$this->render('/v2017/muQin/goods_nav',array(
			'goods' => $goods,
			'url' => $url,
            'tanCoupons'=>$tanCoupons,
		));
	}
    /*
     * @version 抢福袋页面
     */
    public function actionGetPrize(){
        MuQin::model()->addShareLog($this->getUserId(),3);
        $leftChance=MuQin::model()->getChanceValue($this->getUserId());
        $time=time();
		$customer_id = $this->getUserId() * 778 + 1778;
		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
		$urlShare=F::getHomeUrl('/MuQin/ShareWeb').'?ts='.$time.'&sign='.$sign;
    	$this->render('/v2017/muQin/lucky_draw', array(
            'leftChance'=>$leftChance,
            'surl'=>base64_encode($urlShare)
        ));
    }
    /*
     * @version 获奖明细页面
     */
    public function actionPrizeDetail(){
        $prizeMobileArr=MuQin::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2017/muQin/prize', array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 抽奖按钮ajax
     */
    public function actionChouJiang(){
        $yan=MuQin::model()->yanZhengChance($this->getUserId());
        if($yan){
            $list=MuQin::model()->getPrizeByChouJiang($this->getUserId());
        }else{
            echo json_encode(array('status'=>2));
            exit;
        }
        if(!empty($list)){
            $leftChance=MuQin::model()->getChanceValue($this->getUserId());
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 话题页面
     */
    public function actionTie(){
        MuQin::model()->addShareLog($this->getUserId(),2);
    	$this->render('/v2017/muQin/tie');
    }
    /*
     * @version 点击请求ajax
     */
    public function actionDian(){
        $tid = intval(Yii::app()->request->getParam('tid'));
        MuQin::model()->addShareLog($this->getUserId(),$tid);
        echo json_encode(array('status'=>1));
    }
}
