<?php
/*
 * @version 优惠券
 * @copyright(c) josen 2015-08-20
 */
class YouHuiQuanController extends CController {
    private $_userId=0;
    /*
     * @version 我的优惠券界面
     */
    public function actionIndex() {
        $userid=$this->checkLogin();
        //优惠券列表
        $detailArr=YouHuiQuanWeb::model()->getDetail($userid);

        $this->renderPartial("my_coupons_new",array('userid'=>$userid,'detailArr'=>$detailArr));
    }
    /*
     * @version 即将过期的优惠券
     */
    public function actionExpire(){
        $userid=(int)Yii::app()->request->getParam('userid');
        //即将过期优惠券列表
        $expireDetailArr=YouHuiQuanWeb::model()->getDalayNum($userid,2);
        $this->renderPartial("overdue_new",array('userid'=>$userid,'expireDetailArr'=>$expireDetailArr));
    }
    /*
     * @version 领取优惠券列表页
     */
    public function actionGetCoupons(){
        $userid=$this->checkLogin();
        $quanDetailArr=YouHuiQuanWeb::model()->getTodayTui($userid);
        $this->renderPartial("get_coupons_new",array('userid'=>$userid,'quanDetailArr'=>$quanDetailArr));
    }
    /*
     * @version 领取优惠券动作
     * 
     */
    public function actionGetYouHuiQuan(){
        $userid=$this->checkLogin();
        $id = intval(isset($_POST['id']) ? $_POST['id'] : 0);
        $exe=YouHuiQuanWeb::model()->getYouHuiQuan($userid,$id);
        if($exe){
            $ret=array("success"=>1);
            echo json_encode($ret);
        }else{
            $ret=array("success"=>0);
            echo json_encode($ret);
        }
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
        //大闸蟹
        $href = $SetableSmallLoansModel->searchByIdAndType(113, '', $userid);
        if ($href) {
            $dzxHref = $href->completeURL;
        }
        else {
            $dzxHref = '';
        }
        //彩生活特供
        $href = $SetableSmallLoansModel->searchByIdAndType(39, '', $userid);
        if ($href) {
            $tgHref = $href->completeURL;
        }
        else {
            $tgHref = '';
        }
        $this->renderPartial("coupons_detail_new",array('userid'=>$userid,'couponDetail'=>$couponDetail,'productDetail'=>$productDetail,
            'jdHref'=>$jdHref,
            'hqHref'=>$hqHref,
            'dzxHref'=>$dzxHref,
            'tgHref'=>$tgHref,
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
                $userId=intval($_SESSION['userid']);
            }
            $customer=Customer::model()->findByPk($userId);
            if(empty($userId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            return $this->_userId = $userId;
        }
    }
}

