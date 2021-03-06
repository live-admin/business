<?php
/*
 * @version 母亲节活动
 */
class MotherDay extends CActiveRecord{
    //正式环境
    private $begin_time='2016-05-06 09:00:00';
    private $good_Ids = array (
    		'35' => array(
    				39548,
    				39930
    		),
    		'36' => array(
    				39955,
					39956,
					39957,
					39958,
					39959,
					39960,
					39961,
					39963,
					39964,
					39966,
					39968,
					39970,
					39972,
					39974,
					39975,
					39976,
					39977,
					40012,
					40013,
					40015,
					40016,
					40019,
					40022,
					40023
    		),
    		'37' => array(
    				39955,
					39956,
					39957,
					39958,
					39959,
					39960,
					39961,
					39963,
					39964,
					39966,
					39968,
					39970,
					39972,
					39974,
					39975,
					39976,
					39977,
					40012,
					40013,
					40015,
					40016,
					40019,
					40022,
					40023
    		),
    		//测试
/*      		'36' => array (
    				29749,
    				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
     				29749,
     				29750,
    		),
    		'37' => array (
    				29669,
    				29737,
    				29751,
    				28593,
    				29669,
    				29737,
    				29751,
    				28593,
    				29669,
    				29737,
    				29751,
    				28593,
    				29669,
    				29737,
    				29751,
    				28593,
    				29669,
    				29737,
    				29751,
    				28593,
    				29669,
    				29737,
    				29751,
    				28593,
    		),  */
    );
	// 商品的数量
	private $all_num = array (
			'39548' => 0,
			'39930' => 0,
			'39955' => 0,
			'39956' => 0,
			'39957' => 0,
			'39958' => 0,
			'39959' => 0,
			'39960' => 0,
			'39961' => 0,
			'39963' => 0,
			'39964' => 0,
			'39966' => 0,
			'39968' => 0,
			'39970' => 0,
			'39972' => 0,
			'39974' => 0,
			'39975' => 0,
			'39976' => 0,
			'39977' => 0,
			'40012' => 0,
			'40013' => 0,
			'40015' => 0,
			'40016' => 0,
			'40019' => 0,
			'40022' => 0,
			'40023' => 0
	);
    //测试商品
/*    private $all_num = array (
	   		'29749' => 0,
	   		'29751' => 0,
	   		'29750' => 0,
	   		'28593' => 0,
	   		'29669' => 0,
	   		'29737' => 0,
	   		'29749' => 0,
	   		'29751' => 0,
	   		'29750' => 0,
	   		'28593' => 0,
	   		'29669' => 0,
	   		'29737' => 0,
	   		'29749' => 0,
	   		'29751' => 0,
	   		'29750' => 0,
	   		'28593' => 0,
	   		'29669' => 0,
	   		'29737' => 0,
	   		'29749' => 0,
	   		'29751' => 0,
	   		'29750' => 0,
	   		'28593' => 0,
	   		'29669' => 0,
	   		'29737' => 0,
  );   */ 
    
    private $xfzgx=2607;
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 返回当前对应的四款产品
     * return array
     */
    public function getDayProduct($isDelay=false){
    	$currentProductList=array();
    	$nextProductList=array();
    	$num=$this->getWeeks();
    	//下一次更换商品时间
    	$next_time=strtotime($this->begin_time)+($num+1)*7*86400;
    	/* if (!$isDelay){
    		$next_time=strtotime($this->begin_time)+($num+2)*7*86400;
    	} */
    	$data['next_date']=date("n月j日G:i",$next_time);
    	if (!isset($this->good_Ids[$num+1])){
    		$goods_arr=array();
    	}else {
    		$goods_arr=$this->good_Ids[$num+1];  //当前商品
    	}
		if (!empty($goods_arr)){
			foreach ($goods_arr as $val){
				$tmp=array();
				$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status = 0',array(':goods_id'=>$val));
				if (empty($cheapArr)){
					continue;
				}
				$tmp['id']= $cheapArr->id;
				$productArr=$this->getProductDetail($val);
				$tmp['name']=$productArr['name'];
				if($productArr['ku_cun']<0){
					$productArr['ku_cun']=0;
				}
				$tmp['ku_cun']=$productArr['ku_cun'];
				$tmp['img_name']=Yii::app()->ajaxUploadImage->getUrl($productArr['good_image']);
				$tmp['price']=$productArr['customer_price'];
				$tmp['total']=$this->all_num[$val];
				$currentProductList[]=$tmp;
			}
			$data['currentProductList']=$currentProductList;
		}
		/* if (!$isDelay){
			$num=$num+1;
		} */
		if (!isset($this->good_Ids[$num+2])){
			$next_goods=array();
		}else {
			$next_goods=$this->good_Ids[$num+2];  //下周商品
		}
//        dump($next_goods);
		
		if (!empty($next_goods)){
			foreach ($next_goods as $val){
				$tmp=array();
//				$tmp['id']= $cheapArr->id;
				$productArr=$this->getProductDetail($val);
				$tmp['name']=$productArr['name'];
				if($productArr['ku_cun']<0){
					$productArr['ku_cun']=0;
				}
				$tmp['ku_cun']=$productArr['ku_cun'];
				$tmp['img_name']=Yii::app()->ajaxUploadImage->getUrl($productArr['good_image']);
				$tmp['price']=$productArr['customer_price'];
				$nextProductList[]=$tmp;
			}
		}
		$data['currentProductList']=$currentProductList;
		$data['nextProductList']=$nextProductList;
		return $data;
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
       /*  $SetableSmallLoansModel = new SetableSmallLoans();
        //彩生活特供
        $href3 = $SetableSmallLoansModel->searchByIdAndType(39, '', $userId); */
        $homeConfig=new HomeConfigResource();
        $href3=$homeConfig->getResourceByKeyOrId(39,1,$userId);
        if ($href3) {
            $tgHref = $href3->completeURL;
        }
        else {
            $tgHref = '';
        }
        return array(
            'tgHref'=>$tgHref,
        );
    }
    /*
     * @version 通过产品id获取产品信息
     * @param int goods_id
     * return array
     */
    public function getProductDetail($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $productArr=Goods::model()->findByPk($goods_id);
        return $productArr;
    }
/***************************************************彩生活特供********************************************************/
    
    /*
     * @version 根据产品id和用户id来判断每个人只能购买一个产品,限制一人买一个
     * @param int $goods_id
     * @param int $userId
     * return boolean
     */
    public function getOrderBuy($goods_id,$userId){
        if(empty($goods_id) || empty($userId)){
            return false;
        }
        $result=$this->getOne($goods_id);
        if($result){
            $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id where o.seller_id=".$this->xfzgx." and o.`status` in(0,1,3,4,99) and o.buyer_id=".$userId." and ogr.goods_id=".$goods_id;
            $orderArr =Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($orderArr)){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
    /*
     * @version 限定的产品只能购买一个,数量限制一个
     * @param int $goods_id
     * return boolean
     */
    public function getOne($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $num=$this->getWeeks();
        if (!isset($this->good_Ids[$num+1])){
        	return false;
        }
        $goods_arr=$this->good_Ids[$num+1];  //当前商品
         if(in_array($goods_id, $goods_arr)){
         	if ($this->all_num[$goods_id]>0){
         		return true;
         	}else {
         		return false;
         	}
            
         }else{
             return false;
         }
    }
    /*
     * @version 限定的产品只能购买一个,数量限制一个
     * @param int $goods_id
     * return boolean
     */
    public function getCaiFuOne($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $goods_arr=array(36544,36545,36546,36547,38112,38113,38114);
         if(in_array($goods_id, $goods_arr)){
             return true;
         }else{
             return false;
         }
    }
    /*
     * @version 时时获取库存信息
     * @param int $goods_id
     */
    public function getKuCun($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $goodsArr=Goods::model()->findByPk($goods_id);
        return $goodsArr->ku_cun;
    }
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type)
    {
        $shareLog =new MotherIndexLog();
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
    /**
     * 获取周
     * @return number
     */
    public function getWeeks(){
    	$current_time=strtotime(date("Y-m-d").' 09:00:00');
	   	if (isset($_GET['cust_id'])&&($_GET['cust_id']==1745829||$_GET['cust_id']==1734816||$_GET['cust_id']==1254226||$_GET['cust_id']==2208133)){
	   		$current_time=strtotime('2017-01-06 09:00:00');
	   	}
     	//$current_time=strtotime('2017-01-06 09:00:00');
    	$begin_time=strtotime($this->begin_time);
    	$weeks=($current_time-$begin_time)/(7*86400);
    	return floor($weeks);
    }
    
    /**
     * 判断是否有邀请注册
     * @param unknown $userID
     * @return boolean
     */
    public function isRegister($userID){
    	if (empty($userID)){
    		return 0;
    	}
    	$num=$this->getWeeks();
    	$beginTime=strtotime($this->begin_time)+$num*7*86400; //一周的开始时间
    	$endTime=strtotime($this->begin_time)+($num+1)*7*86400; //下一周的结束时间
    	$n=Invite::model()->count('create_time>=:begin_time and create_time<:end_time and customer_id=:customer_id and status=1 and effective=1',array(':begin_time'=>$beginTime,':end_time'=>$endTime,':customer_id'=>$userID));
    	return $n;
    }
    /***********************************端午节活动******************************************************/
    public function getSixProduct(){
        $goods_arr=array(29094,29095,30140,30141,29984,30488);
        $all_num = array (
			'29094' => 0,
			'29095' => 0,
			'30140' => 0,
			'30141' => 0,
			'29984' => 0,
			'30488' => 0,
        );
        
//        $goods_arr=array(29676,29666,29669,28853,29089,28974);
//        $all_num = array (
//			'29676' => 0,
//			'29666' => 0,
//			'29669' => 0,
//			'28853' => 0,
//			'29089' => 0,
//			'28974' => 0,
//        );
        if (!empty($goods_arr)){
			foreach ($goods_arr as $val){
				$tmp=array();
				$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status = 0',array(':goods_id'=>$val));
				if (empty($cheapArr)){
					continue;
				}
				$tmp['id']= $cheapArr->id;
				$productArr=$this->getProductDetail($val);
				$tmp['name']=$productArr['name'];
				if($productArr['ku_cun']<0){
					$productArr['ku_cun']=0;
				}
				$tmp['ku_cun']=$productArr['ku_cun'];
				$tmp['img_name']=Yii::app()->ajaxUploadImage->getUrl($productArr['good_image']);
				$tmp['price']=$productArr['customer_price'];
				$tmp['total']=$all_num[$val];
				$currentProductList[]=$tmp;
			}
			return $currentProductList;
		}
    }
    
}
