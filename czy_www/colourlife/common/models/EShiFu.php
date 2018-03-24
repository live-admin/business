<?php
/*
 * @version 母亲节model
 */
class EShiFu extends CActiveRecord{
    private $beginTime='2017-05-09 00:00:00';//活动开始时间
	private $endTime='2017-06-11 23:59:59';//活动结束时间
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*********************************************************首页***************************************************************************
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type)
    {
        $shareLog =new EshifuLog();
        $shareLog->customer_id=$customer_id;
        $shareLog->type=$type;
        $shareLog->create_time=time();
        $result = $shareLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
}