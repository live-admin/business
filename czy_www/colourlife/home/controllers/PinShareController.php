<?php
class PinShareController extends CController{
    private $beginTime='2017-04-15 00:00:00';
    private $endTime='2017-04-28 23:59:59';
    public $layout = false;
    public function init(){
        $nowTime = time();
        if ( $nowTime < strtotime($this->beginTime)){
           exit('活动未开始，敬请期待！'); 
        }
        if ( $nowTime > strtotime($this->endTime)){
            exit('活动已结束！');
        }
    }
    /*
     * @version 分享页面
     */
    public function actionShareWeb(){
        PinTuan::model()->addShareLog(0,3);
        $productList=PinTuan::model()->getWeekProduct();//产品信息
        $this->render("/v2016/pinShare/share",array(
            'productList'=>$productList,
        ));
    }
}


