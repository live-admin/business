<?php
/*
 * @version 雀神model
 */
class QueShen extends CActiveRecord{
    public $beginTime='2016-11-22';//活动开始时间
    public $endTime='2016-12-28 23:59:59';//活动结束时间
    private $jiangJin=5000;//总的奖金
    private $limitTime=60;//限制评论间隔时间
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /***************************************** 首页**********************************************************/
    /*
     * @version 插入评论数据
     * @param int $customer_id
     * @param int $content
     * return boolean
     */
    public function insertComment($customer_id,$content){
        if(empty($customer_id) || empty($content)){
            return false;
        }
        $commentModel=new QueComment();
        $commentModel->customer_id=$customer_id;
        $commentModel->content=$content;
        $commentModel->create_time=time();
        $result=$commentModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 倒序显示评论列表
     */
    public function showComment(){
        $list=array();
        $commentArr=QueComment::model()->findAll(array('order'=>'create_time desc'));
//        dump($commentArr);
        if(!empty($commentArr)){
            foreach ($commentArr as $key=>$comment){
                $cusInfo=$this->getCustomerInfo($comment['customer_id']);
                $list[$key]['name']=$cusInfo['name'];
                $list[$key]['image']=$cusInfo['image'];
                $list[$key]['content']=$comment['content'];
                $list[$key]['create_time']=date('m-d H:i',$comment['create_time']);
            }
            return $list;
        }else{
            $list=array();
        }
    }
    /**
	 * @version 获取头像和名称
	 * @param int $customer_id
	 * @return array
	 */
	public function getCustomerInfo($customer_id){
		if (empty($customer_id)){
			return array('image'=>F::getStaticsUrl('/activity/v2016/queShen/images/default_icon.png'),'name'=>'访客');
		}
		$data=array();
		$customer=Customer::model()->findByPk($customer_id);
        if(!empty($customer['portrait'])){
            $arr=explode(':', $customer['portrait']);
            if(count($arr)>1){
                $data['image']=$customer['portrait'];
            }else{
                $data['image']=F::getUploadsUrl("/images/" . $customer['portrait']);
            }
        }else {
            $data['image']=F::getStaticsUrl('/activity/v2016/queShen/images/default_icon.png');
        }
		if (!empty($customer['nickname'])){
			$data['name']=$customer['nickname'];
		}else {
			$data['name']='访客';
		}
		return $data;
	}
    /*
     * @version 评论限制
     * @param int $customer_id
     * return boolean
     */
    public function limitTime($customer_id){
        if (empty($customer_id)){
			return false;
		}
        $now=time();
        $sqlSelect='select create_time from que_comment where customer_id='.$customer_id.' order by create_time desc';
        $commentArr =Yii::app()->db->createCommand($sqlSelect)->queryAll();
        if(!empty($commentArr)){
            $createTime=$commentArr[0]['create_time'];
            $leftTime=$now-$createTime;
            if($leftTime<$this->limitTime){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
    /*
     * @version 获取人气排行榜明细
     * @param int $customer_id
     * return array
     */
    public function showRenQi($customer_id){
        if (empty($customer_id)){
			return false;
		}
        $listRen=array();
        $userInfo=QueUserInfo::model()->findAll('status=:status',array(':status'=>0));
        if(!empty($userInfo)){
            $sqlAll="select sum(value) as total from que_ren_qi";
            $renQiTotal =Yii::app()->db->createCommand($sqlAll)->queryAll();
            $total=$renQiTotal[0]['total'];
            foreach ($userInfo as $key=>$v){
                $sql="select sum(value) as summary from que_ren_qi where user_id=".$v['id'];
                $renQiArr =Yii::app()->db->createCommand($sql)->queryAll();
                $listRen[$key]['photo']=F::getUploadsUrl("/images/" . $v['photo']);
                $listRen[$key]['name']=$v['name'];
                $listRen[$key]['brand_age']=$v['brand_age'];
                $listRen[$key]['record']=$v['record'];
                $listRen[$key]['pronouncement']=$v['pronouncement'];
                $listRen[$key]['value']=$renQiArr[0]['summary'];
                if($total==0){
                    $listRen[$key]['persent']='0%';
                }else{
                    $listRen[$key]['persent']=round(($renQiArr[0]['summary']/$total)*100,1).'%';
                }
                $check=$this->checkTa($customer_id,$v['id']);
                $checkOther=$this->checkTaByDate($customer_id,$v['id']);
                $listRen[$key]['button']=$check;
                $listRen[$key]['id']=$v['id'];
                $listRen[$key]['buttonOther']=$checkOther;
            }
            $piao = array();
            foreach ($listRen as $val){
                $piao[]=$val['value'];
            }
            array_multisort($piao,SORT_DESC,$listRen);
            return $listRen;
        }else{
            return $listRen=array();
        }
    }
    /*
     * @version 检测用户是否已经对参赛者投票
     * @param int $customer_id
     * @param int $user_id
     * return boolean
     */
    public function checkTa($customer_id,$user_id){
        if (empty($customer_id) || empty($user_id)){
			return false;
		}
        $checkQi=QueRenQi::model()->find('user_id=:user_id and customer_id=:customer_id',array(':user_id'=>$user_id,':customer_id'=>$customer_id));
        if(!empty($checkQi)){
            return false;
        }else{
            return true;
        }
    }
    /*
     * @version 27号当天按钮的显示
     * @param int $customer_id
     * @param int $user_id
     * return boolean
     */
    public function checkTaByDate($customer_id,$user_id){
        if (empty($customer_id) || empty($user_id)){
			return false;
		}
        $now=time();
        $limitDate=  strtotime('2016-11-27');
        if($now>=$limitDate){
            $checkQi=QueRenQi::model()->find('user_id=:user_id and customer_id=:customer_id',array(':user_id'=>$user_id,':customer_id'=>$customer_id));
            if(!empty($checkQi)){
                return 0;
            }else{
                return 1;
            }
        }else{
            return 2;
        }
    }
    /*
     * @version 猜他赢获取人气
     * @param int $customer_id
     * @param int $user_id
     * return boolean
     */
    public function getPiaoByGuess($customer_id,$user_id){
        if (empty($customer_id) || empty($user_id)){
			return false;
		}
        $renQiModel=new QueRenQi();
        $renQiModel->user_id=$user_id;
        $renQiModel->customer_id=$customer_id;
        $renQiModel->value=1;
        $renQiModel->create_time=time();
        $result=$renQiModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 猜Ta赢后返回的数据更新
     * @param int $customer_id
     * return array
     */
    public function renQiChange($customer_id){
        if (empty($customer_id)){
			return false;
		}
        $listRen=array();
        $userInfo=QueUserInfo::model()->findAll('status=:status',array(':status'=>0));
        if(!empty($userInfo)){
            $sqlAll="select sum(value) as total from que_ren_qi";
            $renQiTotal =Yii::app()->db->createCommand($sqlAll)->queryAll();
            $total=$renQiTotal[0]['total'];
            foreach ($userInfo as $key=>$v){
                $sql="select sum(value) as summary from que_ren_qi where user_id=".$v['id'];
                $renQiArr =Yii::app()->db->createCommand($sql)->queryAll();
                $listRen[$key]['value']=$renQiArr[0]['summary'];
                if($total==0){
                    $listRen[$key]['persent']='0%';
                }else{
                    $listRen[$key]['persent']=round(($renQiArr[0]['summary']/$total)*100,1).'%';
                }
                
                $check=$this->checkTa($customer_id,$v['id']);
                $checkOther=$this->checkTaByDate($customer_id,$v['id']);
                $listRen[$key]['button']=$check;
                $listRen[$key]['id']=$v['id'];
                $listRen[$key]['buttonOther']=$checkOther;
            }
            $piao = array();
            foreach ($listRen as $val){
                $piao[]=$val['value'];
            }
            array_multisort($piao,SORT_DESC,$listRen);
            return $listRen;
        }else{
            return $listRen=array();
        }
    }
    /*
     * @version 系统默认获取一次投票机会
     * @param int $customer_id
     * @param int $way
     * return boolean
     */
    public function getPiaoBySystem($customer_id,$way){
        if (empty($customer_id) || empty($way)){
			return false;
		}
        if($way==3){
            $value=-1;
        }else{
            $value=1;
        }
        $piaoModel=new QuePiao();
        $piaoModel->customer_id=$customer_id;
        $piaoModel->value=$value;
        $piaoModel->way=$way;
        $piaoModel->create_time=time();
        $result=$piaoModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 系统是否已经赠送过或者已经达到次数
     * @param int $customer_id
     * @param int $way
     */
    public function checkTimes($customer_id,$way){
        if (empty($customer_id) || empty($way)){
			return false;
		}
        if($way==1){
            $sqlOne='select sum(value) as total from que_piao where customer_id='.$customer_id.' and way='.$way;
            $renQiTotal =Yii::app()->db->createCommand($sqlOne)->queryAll();
            $total=$renQiTotal[0]['total'];
            if($total>=1){
                return false;
            }else{
                return true;
            }
        }elseif($way==2){
            $sqlOne='select sum(value) as total from que_piao where customer_id='.$customer_id.' and way='.$way;
            $renQiTotal =Yii::app()->db->createCommand($sqlOne)->queryAll();
            $total=$renQiTotal[0]['total'];
            if($total>=4){
                return false;
            }else{
                return true;
            }
        }
    }
    /*
     * @version 检测用户的票数
     * @param int $customer_id
     * return boolean
     */
    public function getPiaoNum($customer_id){
        if (empty($customer_id)){
			return false;
		}
        $yaoNum=QuePiao::model()->count('customer_id=:customer_id and way=2',array(':customer_id'=>$customer_id));
        $sqlAll='select sum(value) as total from que_piao where customer_id='.$customer_id;
        $renQiTotal =Yii::app()->db->createCommand($sqlAll)->queryAll();
        $total=$renQiTotal[0]['total'];
        if($total<=0 && $yaoNum<4){
            return $msg=1;
        }elseif($total<=0 && $yaoNum>=4){
            return $msg=2;
        }else{
            return false;
        }
    }
    /*
     * @version 通过参赛者ID获取参赛者信息以及支持者
     * @param int $user_id
     * @param int $customer_id
     * return array
     */
    public function getInfoById($user_id,$customer_id){
        if (empty($user_id) || empty($customer_id)){
			return false;
		}
        $listOne=array();
        $userInfo=QueUserInfo::model()->findByPk($user_id);
        $listOne['photo']=F::getUploadsUrl("/images/" . $userInfo['photo']);
        $listOne['name']=$userInfo['name'];
        $listOne['age']=$userInfo['age'];
        $listOne['brand_age']=$userInfo['brand_age'];
        $listOne['record']=$userInfo['record'];
        $listOne['pronouncement']=$userInfo['pronouncement'];
        $count=QueRenQi::model()->count('user_id=:user_id',array(':user_id'=>$user_id));
        $listOne['num']=$count;
        $check=$this->checkTa($customer_id,$user_id);
        $checkOther=$this->checkTaByDate($customer_id,$user_id);
        $listOne['button']=$check;
        $listOne['buttonOther']=$checkOther;
        $exist=QueRenQi::model()->find('user_id=:user_id and customer_id=:customer_id',array(':user_id'=>$user_id,':customer_id'=>$customer_id));
        $data=$this->getCustomerInfo($customer_id);
        $myPhoto=$data['image'];
        if(!empty($exist)){
            $listOne['support'][]=$myPhoto;
        }
        $sql='select customer_id from que_ren_qi where user_id='.$user_id.' group by customer_id order by create_time desc,id desc limit 11';
        $renArr =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($renArr)){
            foreach ($renArr as $ren){
                if($ren['customer_id']==$customer_id){
                    continue;
                }
                $data=$this->getCustomerInfo($ren['customer_id']);
                $otherPhoto=$data['image'];
                $listOne['support'][]=$otherPhoto;
            }
        }else{
            $listOne['support']=array(); 
        }
        return $listOne;
    }
    /*
     * @version 显示全部支持者头像
     * @param int $user_id
     * @param int $customer_id
     * return array
     */
    public function getAllSupport($user_id,$customer_id){
        if (empty($user_id) || empty($customer_id)){
			return false;
		}
        $listAll=array();
        $count=QueRenQi::model()->count('user_id=:user_id',array(':user_id'=>$user_id));
        $listAll['num']=$count;
        $exist=QueRenQi::model()->find('user_id=:user_id and customer_id=:customer_id',array(':user_id'=>$user_id,':customer_id'=>$customer_id));
        $data=$this->getCustomerInfo($customer_id);
        $myPhoto=$data['image'];
        if(!empty($exist)){
            $listAll['photo'][]=$myPhoto;
        }
        $sql='select customer_id from que_ren_qi where user_id='.$user_id.' group by customer_id order by create_time desc,id desc';
        $renArr =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($renArr)){
            foreach ($renArr as $ren){
                if($ren['customer_id']==$customer_id){
                    continue;
                }
                $data=$this->getCustomerInfo($ren['customer_id']);
                $otherPhoto=$data['image'];
                $listAll['photo'][]=$otherPhoto;
            }
        }else{
            $listAll['photo']=array(); 
        }
        return $listAll;
    }
    /*
     * @version 点击猜ta赢后返回的数据
     * @param int $user_id
     * @param int $customer_id
     * return array
     */
    public function backPhoto($user_id,$customer_id){
        if (empty($user_id) || empty($customer_id)){
			return false;
		}
        $listBack=array();
        $count=QueRenQi::model()->count('user_id=:user_id',array(':user_id'=>$user_id));
        $listBack['num']=$count;
        $check=$this->checkTa($customer_id,$user_id);
        $listBack['button']=$check;
        $checkOther=$this->checkTaByDate($customer_id,$user_id);
        $listBack['buttonOther']=$checkOther;
        $exist=QueRenQi::model()->find('user_id=:user_id and customer_id=:customer_id',array(':user_id'=>$user_id,':customer_id'=>$customer_id));
        $data=$this->getCustomerInfo($customer_id);
        $myPhoto=$data['image'];
        if(!empty($exist)){
            $listBack['photo'][]=$myPhoto;
        }
        $sql='select customer_id from que_ren_qi where user_id='.$user_id.' group by customer_id order by create_time desc,id desc limit 11';
        $renArr =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($renArr)){
            foreach ($renArr as $ren){
                if($ren['customer_id']==$customer_id){
                    continue;
                }
                $data=$this->getCustomerInfo($ren['customer_id']);
                $otherPhoto=$data['image'];
                $listBack['photo'][]=$otherPhoto;
            }
        }else{
            $listBack['photo']=array(); 
        }
        return $listBack;
    }
    /*
     * @version 通过参赛者ID获取参赛者信息以及支持者
     * @param int $user_id
     * return array
     */
    public function getShareInfoById($user_id){
        if (empty($user_id)){
			return false;
		}
        $listShare=array();
        $userInfo=QueUserInfo::model()->findByPk($user_id);
        $listShare['photo']=F::getUploadsUrl("/images/" . $userInfo['photo']);
        $listShare['name']=$userInfo['name'];
        $listShare['age']=$userInfo['age'];
        $listShare['brand_age']=$userInfo['brand_age'];
        $listShare['record']=$userInfo['record'];
        $listShare['pronouncement']=$userInfo['pronouncement'];
        $count=QueRenQi::model()->count('user_id=:user_id',array(':user_id'=>$user_id));
        $listShare['num']=$count;
        $sqlAll="select sum(value) as total from que_ren_qi";
        $renQiTotal =Yii::app()->db->createCommand($sqlAll)->queryAll();
        $total=$renQiTotal[0]['total'];
        if($total==0){
            $listShare['persent']='0%';
        }else{
            $listShare['persent']=round(($count/$total)*100,1).'%';
        }
        return $listShare;
    }
    /*
     * @version 获取冠军信息
     * return array
     */
    public function getChampion(){
        $isChampion=QueUserInfo::model()->find('is_champion=:is_champion',array(':is_champion'=>1));
        if(!empty($isChampion)){
            return $isChampion;
        }else{
            return false;
        }
    }
    /*
     * @version 获取人气最高
     * return array
     */
    public function getRenTop(){
        $sql='select user_id,sum(value) as summary,max(create_time) as maxtime from que_ren_qi group by user_id order by summary desc,maxtime';
        $renQiTop =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($renQiTop)){
            return $renQiTop;
        }else{
            return false;
        }
    }
    /*
     * @version 获取全部猜中冠军的用户
     */
    public function getMobileByGuan(){
        $champion=$this->getChampion();
        $caiResult=QueRenQi::model()->findAll('user_id=:user_id',array(':user_id'=>$champion['id']));
        $total=QueRenQi::model()->count('user_id=:user_id',array(':user_id'=>$champion['id']));
        if($total==0){
            $fanPiao=0;
        }else{
            $fanPiao=round($this->jiangJin/$total);
        }
       
        $listMobile=array();
        if(!empty($caiResult)){
            foreach ($caiResult as $key=>$v){
                $cusArr=Customer::model()->findByPk($v['customer_id']);
                $listMobile[$key][]=substr_replace($cusArr['mobile'],'****',3,4);
                $listMobile[$key][]=$fanPiao;
            }
        }else{
            $listMobile=array();
        }
        return $listMobile;
    }
    /*
     * @version 记录数据
     * @param int $customer_id
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type){
        $qLog =new QueLog();
        $qLog->customer_id=$customer_id;
        $qLog->type=$type;
        $qLog->create_time=time();
        $result = $qLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}