<?php

/* 
 * @version 邻里发帖、活动、分享数据保存
 */
class FeedNoticeService{
    /*
     * @version 邻里发帖，发活动，发动态数据保存
     * @param int $customerId
     * @param int $feedType(1:发帖；2：发布活动；3：分享)
     * @param int $ts 时间戳
     */
    public function addNotice($customerId=0,$feedType=0,$ts=0){
        if(empty($customerId)){
			throw new CHttpException(1001, '传递邻里数据，用户ID为空');
		}
        //插入Model的逻辑
        $boxModel=new BoxLinli();
        $boxModel->customer_id=$customerId;
        $boxModel->feedType=$feedType;
        $boxModel->is_send=0;
        $boxModel->create_time=$ts;
        $result=$boxModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 邻里点赞，评论数据保存
     * @param int $customerId
     * @param int $type(1:点赞；2：评论;)
     * @param int $ts 时间戳
     */
    public function addNoticeOther($customerId=0,$type=0,$ts=0){
        if(empty($customerId)){
			throw new CHttpException(1001, '传递邻里数据，用户ID为空');
		}
        //插入Model的逻辑
        $popularityModel=new PopularityLinli();
        $popularityModel->customer_id=$customerId;
        $popularityModel->type=$type;
        $popularityModel->is_send=0;
        $popularityModel->create_time=$ts;
        $result=$popularityModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}
