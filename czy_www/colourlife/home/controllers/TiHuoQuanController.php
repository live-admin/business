<?php
/*
 * @version 提货券
 * @copyright(c) josen 2015-09-12
 */
class TiHuoQuanController extends CController {
    private $_userId=0;
    /*
     * @version 我的提货券界面
     */
    public function actionIndex() {
        $userid=$this->checkLogin();
        //提货券列表
        $mobile=DaZhaXie::model()->getMobile($userid);
        $sql="select * from user_ti_huo_quan uthq INNER JOIN `order` o on uthq.order_id=o.id and o.`status`  in (1,3,4,99) and mobile='".$mobile."' LEFT JOIN order_goods_relation ogr on uthq.order_id=ogr.order_id GROUP BY uthq.order_id";
        $orderDetailArr=Yii::app()->db->createCommand($sql)->queryAll();
        $SetableSmallLoansModel = new SetableSmallLoans();
        //大闸蟹url
        $href = $SetableSmallLoansModel->searchByIdAndType(113, '', $userid);
        if ($href) {
            $dzxHref = $href->completeURL;
        }
        else {
            $dzxHref = '';
        }
        $this->renderPartial("my_code",array('uid'=>$userid,'orderDetailArr'=>$orderDetailArr,'dzxHref'=>$dzxHref));
    }
    /*
     * @version 优惠券详情页面
     */
    public function actionCouponDetail(){
        $userid=$this->checkLogin();
        $id=(int)Yii::app()->request->getParam('id');
        $couponDetail=YouHuiQuanWeb::model()->getCouponDetail($id);
//        getLimitProduct
        if(!empty($couponDetail['limit_product'])){
            $productDetail=YouHuiQuanWeb::model()->getLimitProduct($couponDetail['limit_product']);
        }else{
            $productDetail=YouHuiQuanWeb::model()->getManJian($couponDetail['shop_id']);
        }
        
        $SetableSmallLoansModel = new SetableSmallLoans();
        //京东url
        $href = $SetableSmallLoansModel->searchByIdAndType(67, '', $userid);
        if ($href) {
            $jdHref = $href->completeURL;
        }
        else {
            $jdHref = '';
        }
        //环球精选url
        $href = $SetableSmallLoansModel->searchByIdAndType(38, '', $userid);
        if ($href) {
            $hqHref = $href->completeURL;
        }
        else {
            $hqHref = '';
        }
        $this->renderPartial("coupons_detail",array('userid'=>$userid,'couponDetail'=>$couponDetail,'productDetail'=>$productDetail,
            'jdHref'=>$jdHref,
            'hqHref'=>$hqHref,
            ));
    }
   
    private function checkLogin(){
        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }else {
            $userId=0;
            if(isset($_REQUEST['userid'])){
                $userId=intval($_REQUEST['userid']);
                $_SESSION['userid']=$userId;
            }else if(isset($_SESSION['userid'])){
                $userId=$_SESSION['userid'];
            }
            $customer=Customer::model()->findByPk($userId);
            if(empty($userId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            return $this->_userId = $userId;
        }
    }
}

