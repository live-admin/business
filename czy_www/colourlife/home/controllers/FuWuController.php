<?php
/*
 * @version 服务专题活动
 */
class FuWuController extends ActivityController{
    public $beginTime='2017-04-23 00:00:00';//活动开始时间
	public $endTime='2017-05-07 23:59:59';//活动结束时间
    public $secret = '@&Fu*Wu^%';
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
        FuWu::model()->addShareLog($this->getUserId(),1);
        $customerID = $this->getUserId();
		$session = Yii::app()->session;
		$siYueSessionKey=$customerID.'fuwu_flag';
		$flag=0;//弹框
//        unset($session[$siYueSessionKey]);
		if(isset($session[$siYueSessionKey])){
			$flag=1;//悬浮窗
		}
		Yii::app()->session->add($siYueSessionKey,1);
        $eUrl="http://m.eshifu.cn/family/groupbuy/index?name=%E7%AC%AC%E4%BA%8C%E5%AD%A3%E5%BA%A6%E5%9B%A2%E8%B4%AD";
        $cUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),6);
        $this->render('/v2017/fuWu/index', array(
            'flag'=>$flag,
            'eUrl'=>$eUrl,
            'cUrl'=>$cUrl,
        ));
    }
    /*
     * @version 生活攻略1
     */
    public function actionLifeOne(){
        FuWu::model()->addShareLog($this->getUserId(),5);
        $type=1;
        $goods = FuWu::model()->getGoodsList($type);
		$tuanUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
        $this->render('/v2017/fuWu/goods_nav_other',array(
			'goods' => $goods,
			'tuanUrl' => $tuanUrl,
		));
    }
    /*
     * @version 生活攻略2
     */
    public function actionLifeTwo(){
        FuWu::model()->addShareLog($this->getUserId(),6);
        $type=2;
		if (empty($type)){
			return false;
		}
        $goods = FuWu::model()->getGoodsList($type);
		$tuanUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
        $this->render('/v2017/fuWu/goods_nav_another',array(
			'goods' => $goods,
			'tuanUrl' => $tuanUrl,
		));
    }
    /*
     * @version 抽奖页面
     */
    public function actionGetPrize(){
        
        $leftChance=FuWu::model()->getChanceValue($this->getUserId());
    	$this->render('/v2017/fuWu/lucky_draw', array(
            'leftChance'=>$leftChance,
        ));
    }
    /*
     * @version 获奖明细页面
     */
    public function actionPrizeDetail(){
        $prizeDetailArr=FuWu::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2017/fuWu/prize', array(
            'prizeDetailArr'=>$prizeDetailArr,
        ));
    }
    /*
     * @version 抽奖按钮ajax
     */
    public function actionChouJiang(){
        $yan=FuWu::model()->yanZhengChance($this->getUserId());
        if($yan){
            $list=FuWu::model()->getPrizeByChouJiang($this->getUserId());
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
            exit;
        }
        if(!empty($list)){
            $leftChance=FuWu::model()->getChanceValue($this->getUserId());
            echo json_encode(array('status'=>1,'leftChance'=>$leftChance,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 领取地址界面
     */
	public function actionAddress(){
        $id = intval(Yii::app()->request->getParam('id'));
		$this->render('/v2017/fuWu/address',array(
            'id'=>$id,
        ));
	}
    /*
     * @version 填写地址后点击保存
     */
	public function actionAjaxSaveAddress(){
		$customerID = $this->getUserId();
        $id = intval(Yii::app()->request->getParam('id'));
        $provinceName = Yii::app()->request->getParam('provinceName');
        $cityName = Yii::app()->request->getParam('cityName');
        $countyName = Yii::app()->request->getParam('countyName');
        $townName = Yii::app()->request->getParam('townName');

        $detailaddress = Yii::app()->request->getParam('address');
        $tel = Yii::app()->request->getParam('buyer_tel');
        $username = Yii::app()->request->getParam('buyer_name');

        //地址拼接
        $address = $provinceName . ' ' . $cityName . ' ' . $countyName . ' ' . $townName . ' ' . $detailaddress;
        $time = time();
        $data = array(
            'customer_id' => $customerID,
            'address' => $address,
            'mobile' => $tel,
            'username' => $username,
            'prize_id'=>$id,
            'create_time' => $time,
        );
        $mobile=  FuWu::model()->getMobileByCustomerId($customerID);
        $check=FuwuPrize::model()->find('mobile=:mobile and id=:id',array(':mobile'=>$mobile,':id'=>$id));
        if(!empty($check)){
            $execute=Yii::app()->db->createCommand()->insert('fuwu_address', $data);
            if($execute){
                echo json_encode(array('status'=>1,'msg'=>'地址保存成功，请耐心等待发货'));
            }else{
                echo json_encode(array('status'=>0,'msg'=>'地址保存失败'));
            }
        }else{
            echo json_encode(array('status'=>0,'msg'=>'无法保存地址'));
        }
	}
    /*
    * @versino 获取所有省
    * @coptyright(c) 2015.04.30 josen
    * @return json
    */
	public function actionGetProvince(){
		$array=PinTuan::model()->getProvince();
		echo urldecode(json_encode($array));
	}
	/*
     * @versino 获取市
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
	public function actionGetCity(){
		$id = intval(Yii::app()->request->getParam('provice_id'));
		$array=PinTuan::model()->getCity($id);
		echo urldecode(json_encode($array));
	}
	/*
     * @versino 获取县/区
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
	public function actionGetCounty(){
		$id = intval(Yii::app()->request->getParam('city_id'));
		$array=PinTuan::model()->getCounty($id);
		echo urldecode(json_encode($array));
	}
	/*
     * @versino 获取镇
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
	public function actionGetTown(){
		$id = intval(Yii::app()->request->getParam('county_id'));
		$array=PinTuan::model()->GetTown($id);
		echo urldecode(json_encode($array));
	}
    /*
     * @version 
     */
    public function actionDian(){
        $tid = intval(Yii::app()->request->getParam('tid'));
        FuWu::model()->addShareLog($this->getUserId(),$tid);
        echo json_encode(array('status'=>1));
    }
}
