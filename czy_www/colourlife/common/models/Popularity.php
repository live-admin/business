<?php
/*
 * @version 人气征集model
 */
class Popularity extends CActiveRecord{
    public $beginTime='2016-10-17';//活动开始时间
    public $endTime='2016-11-17 23:59:59';//活动结束时间
    //获得人气值途径
    private $getArr=array(
        1=>'签到',
        2=>'完善资料',
        3=>'邻里发帖',
        4=>'邻里点赞',
        5=>'邀请注册',
        6=>'下单购买',
        7=>'好友助力',
        8=>'缴纳物业费',
        9=>'缴纳停车费',
        10=>'投诉报修',
        11=>'建议反馈',
    ); 
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 通过用户id获取人气值
     * @param int $customer_id
     * return int 人气值
     */
    public function getRenQiValueByCustomerId($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select sum(value) as total from popularity_get where customer_id=".$customer_id;
        $renQiArr =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($renQiArr[0]['total'])){
            return $renQiArr[0]['total'];
        }else{
            return 0;
        }
    }
    /*
     * @version 每天签到获取人气值
     * @param int $customer_id
     * @return boolean
     */
    public function getValueByQianDao($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $qiArr= PopularityGet::model()->findAll('customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>1));
        $num=count($qiArr);
        if($num<1){
            $sql="select create_time from popularity_get where way=1 and customer_id=".$customer_id." order by  create_time desc";
            $createArr =Yii::app()->db->createCommand($sql)->queryAll();
            $day_list=array();
            if(!empty($createArr)){
                foreach ($createArr as $v){
                    $day_list[]=date('Y-m-d',$v['create_time']);
                }
                $dayNum=$this->getDays($day_list);
            }else{
                $dayNum=1;
            }
            if($dayNum%5==0){
                $value=20;
            }else{
                $value=5;
            }
            $isQianDao=$this->insertValue($customer_id,$value,1,'');
            if($isQianDao){
                return $qianDao=array('value'=>$value);//签到后返回的数组
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 每天签到可以获得人气值
     * @param int $customer_id
     * @return boolean
     */
    public function showValueByQianDao($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $qiArr= PopularityGet::model()->findAll('customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>1));
        $num=count($qiArr);
        if($num<1){
            $sql="select create_time from popularity_get where way=1 and customer_id=".$customer_id." order by  create_time desc";
            $createArr =Yii::app()->db->createCommand($sql)->queryAll();
            $day_list=array();
            if(!empty($createArr)){
                foreach ($createArr as $v){
                    $day_list[]=date('Y-m-d',$v['create_time']);
                }
                $dayNum=$this->getDays($day_list);
            }else{
                $dayNum=1;
            }
            if($dayNum%5==0){
                $value=20;
            }else{
                $value=5;
            }
            return $qianDao=array('value'=>$value);//签到后返回的数组
        }else{
            return false;
        }
    }
    /*
     * @version 获取连续的天数
     * @param array $day_list
     * return int $continue_day 连续天数
     */
    private function getDays($day_list){
        $continue_day = 1 ;//连续天数
        if(count($day_list) >= 1){
            for ($i=1; $i<=count($day_list); $i++){
                if( ( abs(( strtotime(date('Y-m-d')) - strtotime($day_list[$i-1]) ) / 86400)) == $i ){   
                   $continue_day = $i+1;  
                 }else{
                      break;       
                  }    
            }
        }
        return $continue_day;//输出连续几天
    }
    /*
     * @version 插入人气值
     * @param int $customer_id
     * @param int $value
     * @param int $way
     * return array/boolean
     */
    public function insertValue($customer_id,$value,$way,$open_id){
        if(empty($customer_id) || empty($value) || empty($way)){
            return false;
        }
        $popularityModel=new PopularityGet();
        $popularityModel->customer_id=$customer_id;
        $popularityModel->open_id=$open_id;
        $popularityModel->value=$value;
        $popularityModel->way=$way;
        $popularityModel->create_time= time();
        $isInsert=$popularityModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 获取当前用户这个月的签到详情
     * @param int $customer_id
     * return array
     */
    public function getAllQianDaoByCustomer($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $yueTime=strtotime('2016-10');
//        $yueTime=strtotime(date('Y-m', time()));
        $selectSql='select create_time from popularity_get where customer_id='.$customer_id.' and way=1 and create_time>='.$yueTime;
        $qiArr =Yii::app()->db->createCommand($selectSql)->queryAll();
        $listDay=array();
        $today = mktime(0,0,0);
//        $beginTime='2016-08-10';
        $beginDay=  strtotime($this->beginTime);
        $dayNum=($today-$beginDay)/86400+1;
        for($i=1;$i<=$dayNum;$i++){
            $listDay[]=0;
        }
        if(!empty($qiArr)){
            foreach ($qiArr as $key=>$v){
                $key=($today-strtotime(date('Y-m-d', $v['create_time'])))/86400;
                $listDay[$dayNum-$key-1]=1;
            }
        }
        return $listDay;
    }
    /*
     * @version 完善资料
     * @param int $customer_id
     * return boolean
     */
    public function completeInfo($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $ziliao=PopularityGet::model()->find('customer_id=:customer_id and way=2',array(':customer_id'=>$customer_id));
        if(!empty($ziliao)){
            return true;
        }else{
            $sql="select * from customer where id=".$customer_id;
            $cusArr =Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($cusArr) && !empty($cusArr[0]['nickname']) && !empty($cusArr[0]['name']) && !empty($cusArr[0]['gender']) && !empty($cusArr[0]['community_id']) && !empty($cusArr[0]['portrait'])){
                $isQianDao=$this->insertValue($customer_id,5,2,'');
                if($isQianDao){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }
    /*
     * @version 邻里发帖和邻里点赞或者评论是否完成
     * @param int $customer_id
     * @param int $way
     * return boolean
     */
    public function isLinLiComplete($customer_id,$way){

        $day_star = strtotime("today");//今日零点的时间戳
        $day_end = $day_star+"86400";//明日零点(今日24点）的时间戳

        if(empty($customer_id) || empty($way)){
            return false;
        }
        $check=PopularityGet::model()->find('customer_id=:customer_id and create_time>=:day_star and create_time<:day_end and way=:way',array(':way'=>$way,':customer_id'=>$customer_id,':day_star'=>$day_star,':day_end'=>$day_end));
        if(!empty($check)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 我的人气值详情
     * @param int $customer_id
     * return array
     */
    public function renQiDetail($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select create_time,way,value from popularity_get where customer_id=".$customer_id." order by create_time desc";
        $qiArr =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($qiArr)){
            return $qiArr;
        }else{
            return false;
        }
    }
    /*
     * @version 根据way获取获得途径
     * @param int $way
     * return string
     */
    public function getWayName($way){
        if(empty($way)){
            return false;
        }
        return $this->getArr[$way];
    }
    /*
     * @version 获取排名
     * @param int $customer_id
     * return array
     */
    public function getMingByCustomerId($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customer_id);
        $mobile=$cusArr['mobile'];
        $list=array();
        $sql="select customer_id,sum(value) as summary,max(create_time) as maxtime from popularity_get where customer_id not in(2118868)  GROUP BY customer_id ORDER BY summary desc,maxtime limit 0,200";
        $renQiArr =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($renQiArr)){
            foreach ($renQiArr as $key=>$renQi){
               if($renQi['customer_id']==$customer_id){
                    $list['paiming']=$key+1;
                    $list['summary']=$renQi['summary'];
                    $list['mobile']=$mobile;
               }
               $cus=Customer::model()->findByPk($renQi['customer_id']);
               $renQiArr[$key]['mobile']=$cus['mobile'];
            }
        }
        
        $list['grow']= $renQiArr;
        return $list;
    }
    /*
     * @version 好友助力
     * @param int $customer_id
     * @param int $openId
     * return boolean
     */
    public function getValueByZhuLi($customer_id,$open_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        //判断是否微信用户助力
    	if (!empty($open_id)){
    		$sql="select * from popularity_get where open_id='".$open_id."' and way=7 and create_time>=".$beginTime." and create_time<=".$endTime;
            $model=Yii::app()->db->createCommand($sql)->queryAll();
    		if (!empty($model)){
    			return -3;
    		}
    	}
        $sql="select * from popularity_get where customer_id=".$customer_id." and way=7 and create_time>=".$beginTime." and create_time<=".$endTime;
        $popularityArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($popularityArr) && count($popularityArr)>=15){
            return -2;
        }
        $res=$this->insertValue($customer_id,1,7,$open_id);
        if($res){
            return $res;
        }else{
            false;
        }
    }
    /**
     * 获取助力次数
     * @param int $customer_id  用户id
     * @param int $way  类型
     * @return int 
     */
    public function getZhuLiNum($customer_id,$way){
        if(empty($customer_id) || empty($way)){
            return false;
        }
    	$num=0;
    	$beginTime = mktime(0,0,0);
    	$endTime = time();
    	$zhuArr = PopularityGet::model ()->findAll ('customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime', array (
				':beginTime' => $beginTime,
				':endTime' => $endTime,
				':way' => $way,
                ':customer_id'=>$customer_id,
		));
    	if (!empty($zhuArr)){
    		$num=count($zhuArr);
            if($num>15){
                $num=15;
            }
    	}
    	return $num;
    }
    /**
	 * 获取商家链接
	 */
	public function getShopUrl($userID,$type=0){
		$id=0;
		if ($type==1){ //京东
			$id=67;
		}elseif ($type==0) { //彩特供
			$id=39;
		}elseif ($type==2) { //E投诉
			$id=30;
		}elseif ($type==3) { //E评价
			$id=23;
		}
		if (empty($id)||empty($userID)){
			return '';
		}
		$homeConfig=new HomeConfigResource();
		$href=$homeConfig->getResourceByKeyOrId($id,1,$userID);
		if ($href){
			$url=$href->completeURL;
		}else {
			$url='';
		}
		return $url;
	}
    /*
     * @version 记录数据
     * @param int $customer_id
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$open_id,$type){
        $pLog =new PopularityLog();
        $pLog->customer_id=$customer_id;
        $pLog->open_id=$open_id;
        $pLog->type=$type;
        $pLog->create_time=time();
        $result = $pLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 判断当月是否已经参加（投诉报修、建议反馈）
     * @param int $customer_id
     * @param int $way
     * return boolean
     */
    public function checkMonth($customer_id,$way){
        if(empty($customer_id) || empty($way)){
            return false;
        }
        $today = date("Y-m-d");
        $firstday = date('Y-m-01', strtotime($today));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        $beginTime=  strtotime($firstday);
        $endTime=strtotime($lastday);
        $sql="select * from popularity_get where customer_id=".$customer_id." and way=".$way." and create_time>=".$beginTime." and create_time<".$endTime;
        $popularityArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($popularityArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 检测是否超过十五次
     * @param int $customer_id
     * return boolean
     */
    public function checkFiften($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
    	$endTime = time();
        $resultNum=PopularityGet::model()->count('customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(
            ':beginTime' => $beginTime,
            ':endTime' => $endTime,
            ':way' => 7,
            ':customer_id'=>$customer_id,
        ));
        if($resultNum>=15){
            return false;
        }else{
            return true;
        }
    }
}


