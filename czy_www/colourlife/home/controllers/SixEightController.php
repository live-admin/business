<?php
/*
 * @version 京东618活动
 */
class SixEightController extends ActivityController{
    public $beginTime='2017-06-14 00:00:00';//活动开始时间
	public $endTime='2017-07-05 23:59:59';//活动结束时间
    public $secret = '@&Six*Eight^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - Share',
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
        $fanUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
        $typeAll=  JdGoods::model()->_type;
		$goods = SixEight::model()->getGoodsList(array(1));
        $goodsOther = SixEight::model()->getGoodsList(array(2,3));
        $this->render('/v2017/sixEight/index', array(
            'fanUrl'=>$fanUrl,
            'typeAll'=>$typeAll,
            'goods'=>$goods,
            'goodsOther'=>$goodsOther,
        ));
    }
    /**
	 *@version 分类列表页面,根据type值获取不同的商品列表
	 */
	public function actionArea(){
		$type=  intval(Yii::app()->request->getParam('type'));
        $typeAll=  JdGoods::model()->_type;
		//获取所有商品
		$goods = SixEight::model()->getGoodsList(array(0));
		$fanPiaoUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),8);
		$this->render('/v2017/sixEight/goods_nav',array(
			'goods' => $goods,
			'fanPiaoUrl' => $fanPiaoUrl,
            'typeAll'=>$typeAll,
		));
	}
}
