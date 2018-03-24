<?php
/*
 * @version 荔枝节活动
 */
class LiZhiJieController extends ActivityController{
    public $beginTime='2017-06-08 00:00:00';//活动开始时间
	public $endTime='2017-06-25 23:59:59';//活动结束时间
    public $secret = '@&Li*Zhi^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - Share,ChouJiang',
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
        LiZhiJie::model()->addShareLog($this->getUserId(),1);
        $type=0;
        $goods = LiZhiJie::model()->getGoodsList($type);
        $fanUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
        $this->render('/v2017/liZhiJie/index', array(
            'fanUrl'=>$fanUrl,
            'goods' => $goods,
        ));
    }
    /**
     *@version 9.9限时秒杀
	 */
	public function actionAreasekill(){
        LiZhiJie::model()->addShareLog($this->getUserId(),2);
		//获取9.9限时秒杀专区
		$data = LiZhiJie::model()->getSekillGoodsList();
		$time=time();
		$fanUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
        $kuCunArr=LiZhiJie::model()->getStock();
		$this->render('/v2017/liZhiJie/sekill_goods_nav',array(
			'goods' => $data,
			'fanUrl' => $fanUrl,
			'time'=>$time,
            'kuCunArr'=>$kuCunArr,
		));
	}
    /*
     * @version 荔枝的前世今生
     */
    public function actionProductOne(){
        LiZhiJie::model()->addShareLog($this->getUserId(),3);
        $type=1;
        $goods = LiZhiJie::model()->getGoodsList($type);
		$fanUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
        $this->render('/v2017/liZhiJie/goods_nav',array(
			'goods' => $goods,
			'fanUrl' => $fanUrl,
		));
    }
    /*
     * @version 荔枝才是夏日的老大
     */
    public function actionProductTwo(){
        LiZhiJie::model()->addShareLog($this->getUserId(),4);
        $type=2;
        $goods = LiZhiJie::model()->getGoodsList($type);
		$fanUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
        $this->render('/v2017/liZhiJie/goods_nav_other',array(
			'goods' => $goods,
			'fanUrl' => $fanUrl,
		));
    }
    //邀请好友页面
    public function actionYao(){
        $seedId=$this->getUserId() * 778 + 1778;
		$sign=md5($seedId.'colourlife');
//        $url="http://m.newcolourlife.pw/Home/register?source=lizhijie&sd_id=".$seedId."&sign=".$sign;
//        $url="http://m-czytest.colourlife.com/Home/register?source=lizhijie&sd_id=".$seedId."&sign=".$sign;
        $url="http://m.colourlife.com/Home/register?source=lizhijie&sd_id=".$seedId."&sign=".$sign;
		$this->render('/v2017/liZhiJie/yao',array(
            'url'=>base64_encode($url),
		));
    }
    //邀请记录
    public function actionRecord(){
        $inviteInfo=LiZhiJie::model()->getRecord($this->getUserId());
        $this->render('/v2017/liZhiJie/record',array(
     		'inviteInfo' => $inviteInfo,
		));
    }
    //彩富送好礼界面
    public function actionCaiFu(){
		$this->render('/v2017/liZhiJie/cai');
	}
    //领取优惠券按钮ajax
    public function actionLingQuYou(){
        $buType=  intval(Yii::app()->request->getParam('buType'));
        $qid=LiZhiJie::model()->getCaiQuan($this->getUserId(),$buType);
        if(!empty($qid)){
            echo json_encode(array('status'=>1,'qid'=>$qid));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    //抽奖页面
    public function actionGetPrize(){
        $leftChance=LiZhiJie::model()->getChanceValue($this->getUserId());
        $tip=LiZhiJie::model()->getTip($this->getUserId());
    	$this->render('/v2017/liZhiJie/lucky_draw', array(
            'leftChance'=>$leftChance,
            'tip'=>$tip,
        ));
    }
    //抽奖按钮ajax
    public function actionChouJiang(){
        $yan=LiZhiJie::model()->yanZhengChance($this->getUserId());
        if($yan){
            $list=LiZhiJie::model()->getPrizeByChouJiang($this->getUserId());
        }else{
            echo json_encode(array('status'=>2));
            exit;
        }
        if(!empty($list)){
            $leftChance=LiZhiJie::model()->getChanceValue($this->getUserId());
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    //@version 获奖明细页面
    public function actionPrizeDetail(){
        $prizeMobileArr=LiZhiJie::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2017/liZhiJie/prize', array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    
}
