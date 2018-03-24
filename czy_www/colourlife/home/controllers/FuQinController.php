<?php
/*
 * @version 母亲节活动
 */
class FuQinController extends ActivityController{
    public $beginTime='2017-06-13 00:00:00';//活动开始时间
	public $endTime='2017-06-21 23:59:59';//活动结束时间
    public $secret = '@&Fu*Qin^%';
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
//        EntranceCountLog::model()->writeOperateLog($this->getUserId() , '' , $operation_time=time(), 31,'');
        $flag=1;
        $check=  FuqinLog::model()->find('customer_id=:customer_id and type=:type',array(':customer_id'=>$this->getUserId(),':type'=>1));
        if(!empty($check)){
            $flag=0; 
        }
        FuQin::model()->addShareLog($this->getUserId(),1); //首页
        $url=array();
        $url['fanpiaoUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
        $url['caishihuiUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),6);
        $url['caizhuzhaiUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),9);
        $this->render('/v2017/fuQin/index', array(
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
        FuQin::model()->addShareLog($this->getUserId(),$type);
		//获取所有商品
		$data = FuQin::model()->getGoodsList();
		//var_dump($data);
		if (isset($data[$type])){
			$goods[$type] = $data[$type];
		}else {
			$goods[$type] = array();
		}
		$fanPiaoUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
		$this->render('/v2017/fuQin/goods_nav',array(
			'goods' => $goods,
			'fanPiaoUrl' => $fanPiaoUrl,
		));
	}
    /*
     * @version 抢福袋页面
     */
    public function actionGetPrize(){
        FuQin::model()->addShareLog($this->getUserId(),3);
        $leftChance=FuQin::model()->getChanceValue($this->getUserId());
    	$this->render('/v2017/fuQin/lucky_draw', array(
            'leftChance'=>$leftChance,
        ));
    }
    /*
     * @version 获奖明细页面
     */
    public function actionPrizeDetail(){
        $prizeMobileArr=FuQin::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2017/fuQin/prize', array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 抽奖按钮ajax
     */
    public function actionChouJiang(){
        $yan=FuQin::model()->yanZhengChance($this->getUserId());
        if($yan){
            $list=FuQin::model()->getPrizeByChouJiang($this->getUserId());
        }else{
            echo json_encode(array('status'=>2));
            exit;
        }
        if(!empty($list)){
            $leftChance=FuQin::model()->getChanceValue($this->getUserId());
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 点击请求ajax
     */
    public function actionDian(){
        $tid = intval(Yii::app()->request->getParam('tid'));
        FuQin::model()->addShareLog($this->getUserId(),$tid);
        echo json_encode(array('status'=>1));
    }
}
