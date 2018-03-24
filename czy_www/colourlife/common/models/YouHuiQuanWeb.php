<?php

/* 
 * @version 优惠券h5端的接口
 * @copyright josen 2015.08.21
 */
class YouHuiQuanWeb extends CActiveRecord{
    //优惠券状态
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version
     * @param int $uid
     */
    public function getMobile($uid){
        if(empty($uid)){
            return false;
        }
        $resultArr=Customer::model()->findByPk($uid);
        return $resultArr['mobile'];
    }
    /*
     * @version 用户优惠券的信息
     * @pram int $uid 用户id
     * $return array
     */
    public function getDetail($uid){
        if(empty($uid)){
            return false;
        }
        $mobile=$this->getMobile($uid);
        $sql="select * from user_coupons uc LEFT JOIN you_hui_quan yhq on uc.you_hui_quan_id=yhq.id where uc.mobile='".$mobile."' order by uc.is_use asc,yhq.use_end_time desc";
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $resultArr;
    }
    /*
     * @version 优惠券即将过期的数目(3天内)
     * @param int $uid
     * @param int $type 1：获取数量;2:获取详情
     * @return int
     */
    public function getDalayNum($uid,$type){
        if(empty($uid) || empty($type)){
            return false;
        }
        $mobile=$this->getMobile($uid);
        $now=  time();
        $delayTime=$now+2*24*60*60;
        $sql="select count(*) from user_coupons uc LEFT JOIN you_hui_quan yhq on uc.you_hui_quan_id=yhq.id where uc.mobile=".$mobile." and UNIX_TIMESTAMP(yhq.use_end_time)<=".$delayTime." and is_use=0 and (UNIX_TIMESTAMP(yhq.use_end_time)+24*60*60)>".$now;
        $sql2="select * from user_coupons uc LEFT JOIN you_hui_quan yhq on uc.you_hui_quan_id=yhq.id where uc.mobile=".$mobile." and UNIX_TIMESTAMP(yhq.use_end_time)<=".$delayTime." and is_use=0 and (UNIX_TIMESTAMP(yhq.use_end_time)+24*60*60)>".$now;
        if($type==1){
            $count = Yii::app()->db->createCommand($sql)->queryScalar();
            return $count;
        }
        if($type==2){
            $resultArr=Yii::app()->db->createCommand($sql2)->queryAll();
            return $resultArr;
        }
    }
    /*
     * @version 即将过期优惠券剩余天数
     * @param string $use_end_time
     * @return int 
     */
    public function getDayNum($use_end_time){
        if(empty($use_end_time)){
            return false;
        }
        $use_end_time_int=strtotime($use_end_time);
        $use_end_time_int=$use_end_time_int+24*60*60;
        $now=time();
        $shengxia=($use_end_time_int-$now)/60;
        
        if($shengxia<60){
            return "0天 0小时 ".floor($shengxia)."分钟";
        }elseif($shengxia>=60 && $shengxia<(24*60)){
            return "0天 ".floor($shengxia/60)."小时 ".($shengxia%60)."分钟";
        }elseif($shengxia>=(24*60)){
            $day=floor($shengxia/(60*24));
            $hours=floor(($shengxia-($day*24*60))/60);
            $min=($shengxia-($day*24*60))%60;
            return $day."天 ".$hours."小时 ".$min."分钟";
        }
        //return $shengxia;
    }

    /*
     * @version 获取优惠券的背景图片
     * @param int $shop_id
     * @param int $is_use
     * @param int $use_end_time
     * @return string
     */
    public function getBackgroup($shop_id,$is_use,$use_end_time){
        if(empty($shop_id)){
            return false;
        }
        $end_time_int=strtotime($use_end_time)+24*60*60;
        $now= time();
        if ($is_use==1) {
            return F::getStaticsUrl('/youhuiquan/images/used_bg.png');
        }elseif($now>$end_time_int && $is_use==0){
            return F::getStaticsUrl('/youhuiquan/images/expired.png');
        }elseif($shop_id==1 && $is_use==0){
            return F::getStaticsUrl('/youhuiquan/images/colorlife_bg.png');
        }elseif ($shop_id==2 && $is_use==0) {
            return F::getStaticsUrl('/youhuiquan/images/img_05.png');
        }elseif ($shop_id==4 && $is_use==0) {
            return F::getStaticsUrl('/youhuiquan/images/dazhaxie_bg.png');
        }
    }
    /*
     * @version 获取商家的图标
     * @param int int $shop_id
     * @return string
     */
//    public function getTip($shop_id){
//        if(empty($shop_id)){
//            return false;
//        }
//        if($shop_id==1){
//            return F::getStaticsUrl('/youhuiquan/images/img_04.png');
//        }elseif ($shop_id==2) {
//            return F::getStaticsUrl('/youhuiquan/images/img_02.png');
//        }
//    }
    /*
     * @version 获取今日推荐的优惠券
     * @param int $uid
     */
    public function getTodayTui($uid){
        if(empty($uid)){
            return false;
        }
        $customerArr=Customer::model()->findByPk($uid);
        $now=time();
        $youArr=array();
        $sql="select * from you_hui_quan where get_way=1 and UNIX_TIMESTAMP(get_start_time)<=".$now." and (UNIX_TIMESTAMP(get_end_time)+24*60*60)>=".$now." and (UNIX_TIMESTAMP(use_end_time)+24*60*60)>".$now." order by shop_id asc,id desc";
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($resultArr)){
            
            foreach ($resultArr as $result){
                if(empty($result['community_id'])){
                    $youArr[]=$result;
                }
                else{
                    $community_id_arr=explode(',',$result['community_id']);
                    if(in_array($customerArr['community_id'], $community_id_arr)){
                        $youArr[]=$result;
                    }
                }
            }
        }else{
            return $youArr=array();
        }
        return $youArr;
    }
    /*
     * @version 领取优惠券
     * @param int $uid 用户id
     * @param int $id 优惠券编码
     * @return boolean
     */
    public function getYouHuiQuan($uid,$id){
        if(empty($id)){
            return false;
        }
        $mobile=$this->getMobile($uid);
        $resultArr=  UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile ',array(':you_hui_quan_id'=>$id,':mobile'=>$mobile));
        $resultArr2= YouHuiQuan::model()->find('id=:id',array(':id'=>$id));
        if($resultArr2['total']>0){
            if(empty($resultArr)){
                $create_time=time();
                $sql="insert into user_coupons(mobile,you_hui_quan_id,is_use,num,create_time) values('".$mobile."',".$id.",0,0,".$create_time.")";
                $result=Yii::app()->db->createCommand($sql)->execute();
                $sql2="update you_hui_quan set total=total-1 where id=".$id;
                $result2=Yii::app()->db->createCommand($sql2)->execute();
                if($result && $result2){
                        return true;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 优惠券的领取状态(立即领取\已领取\已使用)
     * @param int $uid 用户id
     * @param int $id 优惠券编码
     * @param int type 1:返回图片；2:返回文字
     * @return string
     */
    public function getLingStatus($uid,$id,$type){
        if(empty($uid) || empty($id)){
            return false;
        }
        
        
        $mobile=$this->getMobile($uid);
        $resultArr=  UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile ',array(':you_hui_quan_id'=>$id,':mobile'=>$mobile));
        $resultArr2=  YouHuiQuan::model()->findByPk($id);
        $end_time_int=strtotime($resultArr2['use_end_time'])+24*60*60;
        $now= time();
        if($resultArr2['total']==0){
            if($type==1){
                return F::getStaticsUrl('/youhuiquan/images/getted.png');
            }else{
                return "已领完";
            }
        }
        elseif(empty($resultArr)){
            if($type==1){
                return F::getStaticsUrl('/youhuiquan/images/img_01.png');
            }else{
                return "领取";
            }
            
        }elseif(!empty ($resultArr) && $resultArr['is_use']==0 && ($now<=$end_time_int)){
            if($type==1){
                return F::getStaticsUrl('/youhuiquan/images/holded.png');
            }else{
                return "已领取";
            }
        }elseif(!empty ($resultArr) && $resultArr['is_use']==1){
            if($type==1){
                return F::getStaticsUrl('/youhuiquan/images/used.png');
            }else{
                return "已使用";
            }
        }elseif(!empty ($resultArr) && ($now>=$end_time_int)){
            if($type==2){
                return "已过期";
            }
        }
    }
    /*
     * @version 获取优惠券详情
     * @param int $uid
     * @param int $id
     * @return array
     */
    public function getCouponDetail($id){
        if(empty($id)){
            return false;
        }
        $resultArr= YouHuiQuan::model()->findByPk($id);
        return $resultArr;
    }
    /*
     * @version 获取限制产品信息
     * @param string $limit_product
     * @return array
     */
    public function getLimitProduct($limit_product){
        if(empty($limit_product)){
            return false;
        }
        $sql="select * from goods where id in(".$limit_product.")";
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $resultArr;
    }
    /*
     * @version 获取后台编辑(满减)产品信息
     * @param int $shop_id 适用商家
     */
    public function getManJian($shop_id){
        if(empty($shop_id)){
            return false;
        }
        $sql="select * from goods where is_on_sale=1 and audit=1 and  ping_tai=".$shop_id;
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $resultArr;
    }
    
    
    
    
    /*
     * @version 根据不同的平台链接不同产品的链接
     * @param int $shop_id 商家id
     * @param int $product_id 产品id
     * @return string 
     */
//    public function getProductHref($shop_id,$product_id){
//        if(empty($shop_id) || empty($product_id)){
//            return false;
//        }
//        $SetableSmallLoansModel = new SetableSmallLoans();
//        //京东url
//        if($shop_id==Item::JD_SELL_ID){
//            
//            $href = $SetableSmallLoansModel->searchByIdAndType(67, '', $this->_userId);
//            if ($href) {
//                $goodHref = $href->completeURL;
//            }
//            else {
//                $goodHref = '';
//            }
//        }
//        //环球精选url
//        if($shop_id==Item::HUANQIU_JINGXUAN){
//            
//            $href = $SetableSmallLoansModel->searchByIdAndType(38, '', $this->_userId);
//            if ($href) {
//                $goodHref = $href->completeURL;
//            }
//            else {
//                $goodHref = '';
//            }
//        }
//        return $goodHref."&pid=.".$product_id;
//    }
    /*
     * @version 商品详情页显示可领券
     * @param int $product_id
     */
    public function checkLingQuan($product_id){
        if(empty($product_id)){
            return false;
        }
        $now=time();
        $sql="select limit_product from you_hui_quan where UNIX_TIMESTAMP(use_start_time)<=".$now." and (UNIX_TIMESTAMP(use_end_time)+24*60*60)>=".$now." and UNIX_TIMESTAMP(get_start_time)<=".$now." and (UNIX_TIMESTAMP(get_end_time)+24*60*60)>=".$now." and limit_product!=''";
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($resultArr)){
            $productString='';
            foreach ($resultArr as $result){
                $productString.=$result['limit_product'].",";
            }
            $productString=trim($productString,',');
            $productArr=explode(',',$productString);
            $productArr=array_unique($productArr);
            if(in_array($product_id, $productArr)){
                    return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 根据产品信息获取可使用的优惠券
     * @param int $uid 用户id
     * @param int $shop_id 商家id
     * @param array $productArr 产品数组
     * @param decimal $total 总金额
     * @return array
     */
    public function getUserCoupons($uid,$shop_id,$productArr,$total){
        if(empty($shop_id) || empty($productArr) || empty($productArr) || empty($total)){
            return false;
        }
        $youArr=array();
        $now=time();
        $mobile=$this->getMobile($uid);
        $sql="select * from user_coupons uc LEFT JOIN you_hui_quan yhq on uc.you_hui_quan_id=yhq.id where uc.mobile='".$mobile."' and yhq.shop_id in(".$shop_id.",1) and yhq.man_jian<=".$total." and yhq.man_jian>0 and yhq.limit_product!='' and uc.is_use=0 and (UNIX_TIMESTAMP(yhq.use_end_time)+24*60*60)>=".$now." and UNIX_TIMESTAMP(yhq.use_start_time)<=".$now;
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($resultArr)){
            foreach ($resultArr as $result){
                $limit_product_arr=explode(',',$result['limit_product']);
                foreach ($productArr as $product){
                    if(in_array($product, $limit_product_arr)){
                        $youArr[]=$result;
                        continue;
                    }
                }
            }
        }
        $sql2="select * from user_coupons uc LEFT JOIN you_hui_quan yhq on uc.you_hui_quan_id=yhq.id where uc.mobile='".$mobile."' and yhq.shop_id in(".$shop_id.",1) and yhq.man_jian<=".$total." and yhq.man_jian>0 and yhq.limit_product='' and uc.is_use=0 and (UNIX_TIMESTAMP(yhq.use_end_time)+24*60*60)>=".$now." and UNIX_TIMESTAMP(yhq.use_start_time)<=".$now;
        $resultArr2=Yii::app()->db->createCommand($sql2)->queryAll();
        if(!empty($resultArr2)){
            foreach ($resultArr2 as $result2){
                $youArr[]=$result2;
            }
        }

        
        $sql3="select * from user_coupons uc LEFT JOIN you_hui_quan yhq on uc.you_hui_quan_id=yhq.id where uc.mobile='".$mobile."' and yhq.shop_id in(".$shop_id.",1) and yhq.limit_product!='' and  yhq.man_jian=0 and uc.is_use=0 and (UNIX_TIMESTAMP(yhq.use_end_time)+24*60*60)>=".$now." and UNIX_TIMESTAMP(yhq.use_start_time)<=".$now;
        $resultArr3=Yii::app()->db->createCommand($sql3)->queryAll();
        if(!empty($resultArr3)){
            foreach ($resultArr3 as $result3){
                $limit_product_arr=explode(',',$result3['limit_product']);
                foreach ($productArr as $product){
                    if(in_array($product, $limit_product_arr)){
                        $youArr[]=$result3;
                        continue;
                    }
                }
            }
        }
        
//        if(!empty($youArr)){
//            foreach ($youArr as $you){
//                array_unique($you);
//            }
//        }
        return $youArr;
        
        
    }
    /*
     * @version 付款成功后，扣除优惠券的使用次数，如果次数扣完则标志为已使用
     * @param int $order_id 订单号id
     * $return boolean   
     */
    public function kouCoupons($order_id){
        if(empty($order_id)){
            return false;
        }
        $orderArr=Order::model()->findByPk($order_id);
        $mobile=$this->getMobile($orderArr['buyer_id']);
        $updateSql="update user_coupons set num=num+1 where you_hui_quan_id=".$orderArr['you_hui_quan_id']." and is_use=0 and mobile='".$mobile."' LIMIT 1";
        $result=Yii::app()->db->createCommand($updateSql)->execute();
        $userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile and is_use=0',array(':you_hui_quan_id'=>$orderArr['you_hui_quan_id'],':mobile'=>$mobile));
        $youArr=YouHuiQuan::model()->findByPk($orderArr['you_hui_quan_id']);
        if($userCouponsArr['num']>=$youArr['limit_num']){
            $updateSql2="update user_coupons set is_use=1 where you_hui_quan_id=".$orderArr['you_hui_quan_id']." and is_use=0 and mobile='".$mobile."' LIMIT 1";
            $result2=Yii::app()->db->createCommand($updateSql2)->execute();
        }
        if(isset($result2) && $result){
            return true;
        }elseif(!isset($result2) && $result){
            return true;
        }else{
            return false;
        }
    }
    //add
    /*
     * @version 获取平台名称
     * @param int $shop_id 平台id
     * @return string
     */
//    public function getShipName($shop_id){
//        if(empty($shop_id)){
//            return false;
//        }
//        if($shop_id==1){
//            return '平台通用';
//        }
//        if($shop_id==2){
//            return '京东特供';
//        }
//        if($shop_id==3){
//            return '海外直购';
//        }
//        if($shop_id==4){
//            return '大闸蟹';
//        }
//    }
}

