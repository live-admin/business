<?php
/*
 * @version 四月团购活动
 */
class SiYueController extends ActivityController{
    public $beginTime='2017-04-15 00:00:00';//活动开始时间
	public $endTime='2017-04-28 23:59:59';//活动结束时间
    public $secret = '@&Si*Yue^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Share,ChouJiang,AjaxRedpacketMin',
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
        SiYue::model()->addShareLog($this->getUserId(),1); //首页
        $customerID = $this->getUserId();
		$session = Yii::app()->session;
		$siYueSessionKey=$customerID.'siyue_flag';
		$flag=0;//弹框
//        unset($session[$siYueSessionKey]);
		if(isset($session[$siYueSessionKey])){
			$flag=1;//悬浮窗
		}
        
		Yii::app()->session->add($siYueSessionKey,1);
        //中奖纪录信息
        $tip=SiYue::model()->getTip($customerID);
        $pinUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),5);
//        dump($pinUrl);
        $this->render('/v2017/siYue/index', array(
            'flag'=>$flag,
            'tip'=>$tip,
            'pinUrl'=>$pinUrl,
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
		$data = SiYue::model()->getGoodsList();
		//var_dump($data);
		if (isset($data[$type])){
			$goods[$type] = $data[$type];
		}else {
			$goods[$type] = array();
		}
		$url['jdUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
		$url['tuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),0);
        
		$this->render('/v2017/siYue/goods_nav',array(
			'goods' => $goods,
			'url' => $url,
		));
	}
    /**
     *@version 1元秒杀
	 */
	public function actionAreasekill(){
		//获取1元秒杀专区
		$data = SiYue::model()->getSekillGoodsList();
		$time=time();
		$url['jdUrl'] = StockingSeckillGoods::model()->getShopUrl($this->getUserId(),1);
		$url['tuanUrl'] = StockingSeckillGoods::model()->getShopUrl($this->getUserId());
        $kuCunArr=SiYue::model()->getStock();
		$this->render('/v2017/siYue/sekill_goods_nav',array(
			'goods' => $data,
			'url' => $url,
			'time'=>$time,
            'kuCunArr'=>$kuCunArr,
		));
	}
    //活动规则页面
	public function actionRule(){
		$this->render('/v2017/siYue/rules');
	}
    //红包雨页面
    public function actionRedpacket(){
		$leftChance=SiYue::model()->getChanceValue($this->getUserId());
		$this->render('/v2017/siYue/redpacket',array(
     		'leftChance' => $leftChance,
		));
	}
	//用户参加红包雨活动点击红包少于10个
	public function actionAjaxRedpacketMin(){
		//谢谢参与
		//将中奖信息插入到中奖记录表
		$customerID = $this->getUserId();
        $res=SiYue::model()->insertPrize($customerID,1,'速度有点慢哦');
        if($res){
            $leftChance=  SiYue::model()->getChanceValue($customerID);
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'数据错误，请重新试一次'));
        }
	}
    
    //用户参加红包雨活动成功抽奖按钮ajax
    public function actionChouJiang(){
        $yan=  SiYue::model()->yanZhengChance($this->getUserId());
        if($yan){
            $list=SiYue::model()->getPrizeByChouJiang($this->getUserId());
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
            exit;
        }
        if(!empty($list)){
            $leftChance=SiYue::model()->getChanceValue($this->getUserId());
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    //邀请好友页面
    public function actionYao(){
        SiYue::model()->addShareLog($this->getUserId(),2); 
        $yaoProduct=SiYue::model()->getProductInfo();
        $seedId=$this->getUserId() * 778 + 1778;
		$sign=md5($seedId.'colourlife');
        //$url="http://m.newcolourlife.pw/Home/register?source=siyue&sd_id=".$seedId."&sign=".$sign;
//        $url="http://m-czytest.colourlife.com/Home/register?source=siyue&sd_id=".$seedId."&sign=".$sign;
        $url="http://m.colourlife.com/Home/register?source=siyue&sd_id=".$seedId."&sign=".$sign;
        $tuanUrl= ActivityGoods::model()->getShopUrl($this->getUserId(),0);
		$this->render('/v2017/siYue/yao',array(
     		'yaoProduct' => $yaoProduct,
            'url'=>base64_encode($url),
            'tuanUrl'=>$tuanUrl,
		));
    }
    //邀请记录
    public function actionRecord(){
        $inviteInfo=SiYue::model()->getRecord($this->getUserId());
        $this->render('/v2017/siYue/record',array(
     		'inviteInfo' => $inviteInfo,
		));
    }
}
