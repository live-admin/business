<?php
/*
 * @version 京东618model
 */
class SixEight extends CActiveRecord{
    private $beginTime='2017-06-14 00:00:00';//活动开始时间
	private $endTime='2017-07-05 23:59:59';//活动结束时间
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /**
	 * @version 获取所有商品
	 * @return array
	 */
	public function getGoodsList($type){
		//先从缓存里获取
//		$redisKey = 'jd_goods_list';
//		$data = Yii::app()->rediscache->get($redisKey);
//		if (empty($data)){
			$data = JdGoods::model()->getProducts($type);
//			Yii::app()->rediscache->set($redisKey, $data, 86400); //缓存1天
//		}
		return $data;
	}
}