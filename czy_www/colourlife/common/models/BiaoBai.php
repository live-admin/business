<?php
/*
 * @version 表白活动model
 */
class BiaoBai extends CActiveRecord{
    private $beginTime='2017-05-16 00:00:00';//活动开始时间
	private $endTime='2017-05-26 23:59:59';//活动结束时间
    //正式
    private $lifeArr=array(
        1=>array(38233,40968,44083),
        2=>array(44378,44379,44380),
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*********************************************************首页***************************************************************************/
    
    /**
	 * @version 获取boy页面所有商品
     * @param int $type
	 * @return array
	 */
	public function getGoodsList($type){
        if(empty($type)){
            return false;
        }
		$data = $this->getProductDetail($this->lifeArr[$type]);
		return $data;
	}
    /*
     * @version 获取商品详情信息
     * @param array 商品ID数组
     * return array
     */
    public function getProductDetail($lifeArr){
        if(empty($lifeArr)){
            return false;
        }
        $tmp=array();
        foreach ($lifeArr as $key=>$val){
            $productArr=  Goods::model()->findByPk($val);
            //$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status = 0',array(':goods_id'=>$val));
            if (empty($productArr)){
                continue;
            }
            $image_arr=explode(':', $productArr['good_image']);
            if(count($image_arr)>1){
                $tmp[$key]['imgName'] = $productArr['good_image'];
            }else{
                $tmp[$key]['imgName'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
            }
            $tmp[$key]['pid']= $productArr->id;
            $tmp[$key]['price']=$productArr->customer_price;
            $tmp[$key]['name']=$productArr->name;
        }
        return $tmp;
    }
    /*
     * @version 通过用户ID获取手机号码
     * @param int $customerId 用户ID
     * return string 
     */
    public function getMobileByCustomerId($customerId){
        if(empty($customerId)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customerId);
        return $cusArr['mobile'];
    }
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param int $tid
     * return boolean
     */
    public function addShareLog($customer_id,$tid)
    {
        $shareLog =new BiaobaiLog();
        $shareLog->customer_id=$customer_id;
        $shareLog->type=$tid;
        $shareLog->create_time=time();
        $result = $shareLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
}