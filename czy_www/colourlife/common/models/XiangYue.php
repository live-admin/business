<?php
/*
 * @version 五月特惠活动
 */
class XiangYue extends CActiveRecord{
    private $jdProduct=array(1598371,1598371,1219934,1601832,1357259,2213338,2175039,1426784,1138288,1831891,152026,1205598,2279700,1296287,1191051,1729073,1124326,676676,1098440,2328693,765798,1315121);//京东产品sku
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    /*
     * @version 返回url
     * @param $userId
     * return array
     */
    public function getAllUrl($userId){
        if(empty($userId)){
            return false;
        }
        $SetableSmallLoansModel = new SetableSmallLoans();
        //京东url
        $href = $SetableSmallLoansModel->searchByIdAndType(67, '', $userId);
        if ($href) {
            $jdHref = $href->completeURL;
        }
        else {
            $jdHref = '';
        }
        //环球精选url
        $href2 = $SetableSmallLoansModel->searchByIdAndType(38, '', $userId);
        if ($href2) {
            $hqHref = $href2->completeURL;
        }
        else {
            $hqHref = '';
        }
        //彩生活特供
        $href3 = $SetableSmallLoansModel->searchByIdAndType(39, '', $userId);
        if ($href3) {
            $tgHref = $href3->completeURL;
        }
        else {
            $tgHref = '';
        }
        return array(
            'jdHref'=>$jdHref,
        );
    }
    /*
     * @version 获取京东的最新价格
     * return array
     */
    public function getJdPrice(){
        if(!empty($this->jdProduct)){
            $price=array();
            foreach ($this->jdProduct as $product){
                $productPriceJson=JdApi::model()->getProductXyPrice($product);
                $productPriceArr=json_decode($productPriceJson,true);
                $price[$product]=$productPriceArr['result'][0]['price'];
            }
            return $price;
        }else{
            return false;
        }
    }
    
}
