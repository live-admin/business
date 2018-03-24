<?php
/*
 * @version 国庆7天乐model
 */
class TaskDate extends CActiveRecord{
    private $startDay='2016-10-01';//活动开始时间
    private $endDay='2016-10-07 23:59:59';//活动结束时间
    private $zhuFuCount=5;
    private $day=7;
    private $dateArr=array(
        1=>'2016-10-01',
        2=>'2016-10-02',
        3=>'2016-10-03',
        4=>'2016-10-04',
        5=>'2016-10-05',
        6=>'2016-10-06',
        7=>'2016-10-07',
        8=>'2016-10-08',
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 获取首页返回的数据格式
     * @param int $customer_id  用户id
     * return array
     */
    public function getIndexData($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $resultData=array();
        for($i=1;$i<=$this->day;$i++){
            $taskActive=$this->getTaskActive($i);
            $isComplete=$this->getIsComplete($customer_id, $i);
            $resultData['tasks'][$i-1]['task_active']=$taskActive;
            $resultData['tasks'][$i-1]['task_id']=$i;
            $resultData['tasks'][$i-1]['is_complete']=$isComplete['status'];
            if($i==2){
                $shenQiUrl=$this->getShopUrl($customer_id,648);
                $resultData['tasks'][$i-1]['url'][]=$shenQiUrl;
            }
            elseif($i==3){
                $eUrl=$this->getShopUrl($customer_id,23);
                $resultData['tasks'][$i-1]['url'][]=$eUrl;
            }
            elseif($i==5){
                $CaiUrl=$this->getShopUrl($customer_id,39);
                $JingUrl=$this->getShopUrl($customer_id,67);
                $resultData['tasks'][$i-1]['url'][]=$CaiUrl;
                $resultData['tasks'][$i-1]['url'][]=$JingUrl;
            }else{
                $resultData['tasks'][$i-1]['url'][]='';
            }
//            $resultData['tasks'][$i-1]['url'][]='';
        }
        return $resultData;
        
    }
    /*
     * @version 根据日期来判断活动状态
     * @param int $i
     * return int
     */
    public function getTaskActive($i){
        $now=time();
        $perTime=  strtotime($this->dateArr[$i]);
        $nextTime=strtotime($this->dateArr[$i+1]);
        if($now<$perTime){
           return -1; 
        }
        if($now>$nextTime){
            return 1;
        }
        if($now>=$perTime && $now<$nextTime){
            return 0;
        }
    }
    /*
     * @version 根据日期来判断任务状态
     * @param int $customer_id
     * @param int $i
     * return int
     */
    public function getIsComplete($customer_id,$i){
        if(empty($customer_id)){
            return false;
        }
        $task=array();
        if($i==1){
            $task1=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=1',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>  strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            $task2=TaskComplete::model()->count('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=2',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            if(!empty($task1) && $task2>=5){
                return $task=array('status'=>1,'task'=>$task1,'taskOther'=>$task2);
            }elseif(!empty($task1) && $task2<5){
                return $task=array('status'=>2,'task'=>$task1,'taskOther'=>$task2);
            }elseif(empty($task1) && $task2>=5){
                return $task=array('status'=>3,'task'=>$task1,'taskOther'=>$task2);
            }else{
                return $task=array('status'=>0);
            }
        }
        if($i==2){
            $task3=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=3',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            if(!empty($task3)){
                return $task=array('status'=>1,'task'=>$task3,'taskOther'=>'');
            }else{
                return $task=array('status'=>0);
            }
        }
        if($i==3){
            $task4=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=4',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            if(!empty($task4)){
                return $task=array('status'=>1,'task'=>$task4,'taskOther'=>'');
            }else{
                return $task=array('status'=>0);
            }
        }
        if($i==4){
            $task5=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=5',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            if(!empty($task5)){
                return $task=array('status'=>1,'task'=>$task5,'taskOther'=>'');
            }else{
                return $task=array('status'=>0);
            }
        }
        if($i==5){
            $task6=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=6',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            if(!empty($task6)){
                return $task=array('status'=>1,'task'=>$task6,'taskOther'=>'');
            }else{
                return $task=array('status'=>0);
            }
        }
        if($i==6){
            $task7=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=7',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            $task8=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=8',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            if(!empty($task7) || !empty($task8)){
                return $task=array('status'=>1,'task'=>$task7,'taskOther'=>$task8);
            }else{
                return $task=array('status'=>0);
            }
        }
        if($i==7){
            $task9=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=9',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            $task10=TaskComplete::model()->find('customer_id=:customer_id and create_time>=:beginTime and create_time<:endTime and task_id=10',array(
                ':customer_id'=>$customer_id,
                ':beginTime'=>strtotime($this->dateArr[$i]),
                ':endTime'=>strtotime($this->dateArr[$i+1]),
            ));
            if(!empty($task9) || !empty($task10)){
                return $task=array('status'=>1,'task'=>$task9,'taskOther'=>$task10);
            }else{
                return $task=array('status'=>0);
            }
        }
    }
    /**
	 * 获取商家链接
     * @param int $customer_id
     * @param int $resource_id
     * return string
	 */
	public function getShopUrl($customer_id,$resource_id){
        if (empty($customer_id) || empty($resource_id)){
			return '';
		}
		$homeConfig=new HomeConfigResource();
		$href=$homeConfig->getResourceByKeyOrId($resource_id,1,$customer_id);
		if ($href){
			$url=$href->completeURL;
		}else {
			$url='';
		}
		return $url;
	}
    /**
     * @version 获取好友祝福次数，返回true或者false-----2
     * @param int $customer_id  用户id
     * @param int $task_id  任务
     * @return boolean
     */
//    public function getZhuFu($customer_id,$task_id){
//        if(empty($customer_id) || empty($task_id)){
//            return false;
//        }
//        $beginTime = strtotime('2016-10-01 00:00:00');
//        $endTime = strtotime('2016-10-01 23:59:59');
//    	$num=TaskComplete::model()->count('customer_id=:customer_id and task_id=:task_id and create_time>=:beginTime and create_time<= :endTime',array(
//            ':customer_id'=>$customer_id,
//            ':task_id'=>$task_id,
//            ':beginTime'=>$beginTime,
//            ':endTime'=>$endTime,
//        ));
//        if($num>=$this->zhuFuCount){
//            return true;
//        }else{
//            return false;
//        }
//    }
    /*
     * @versopm 是否是彩富用户
     * @param int $customer_id
     * return boolean
     */
    public function isCaiFu($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$customer_id));
        $appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and status=99',array(':customer_id'=>$customer_id));
        if(!empty($propertyArr) || !empty($appreciationArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 插入数据表
     * @param int $customer_id
     * @param int $task_id
     * return boolean
     */
    public function getTask($customer_id,$open_id,$task_id){
        if(empty($customer_id) || empty($task_id)){
            return false;
        }
        $task =new TaskComplete();
        $task->customer_id=$customer_id;
        $task->open_id=$open_id;
        $task->task_id=$task_id;
        $task->create_time=time();
        $task->time_order=time();
        $result=$task->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 任务记录详情
     * @param int $customer_id
     * return array
     */
    public function getHistory($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sqlSelect='select * from task_complete where customer_id='.$customer_id.' and  create_time>='.  strtotime($this->dateArr[1]).' and create_time<'.strtotime($this->dateArr[8]).' group by FROM_UNIXTIME(time_order, "%Y-%m-%d")  order by create_time';
        $historyArr=Yii::app()->db->createCommand($sqlSelect)->queryAll();
//        dump($historyArr);
//        $historyDataAll=array();
        $historyData=array();
        if(!empty($historyArr)){
            foreach ($historyArr as $key=>$v){
                if($v['task_id']==1 || $v['task_id']==2){
                    $taskArr=$this->getIsComplete($customer_id,1);
                    if($taskArr['status']==1){
                        $historyData['tasks'][$key]['task_id']=1;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        $historyData['tasks'][$key]['task_detail'][]='邻里发#国庆祝福#帖';
                        $historyData['tasks'][$key]['task_detail'][]='国庆祝福集赞5个';
                    }
                    if($taskArr['status']==2){
                        $historyData['tasks'][$key]['task_id']=1;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        $historyData['tasks'][$key]['task_detail'][]='邻里发#国庆祝福#帖';
                    }
                    if($taskArr['status']==3){
                        $historyData['tasks'][$key]['task_id']=1;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        $historyData['tasks'][$key]['task_detail'][]='国庆祝福集赞5个';
                    }
                }
                if($v['task_id']==3){
                    $taskArr=$this->getIsComplete($customer_id,2);
                    if($taskArr['status']==1){
                        $historyData['tasks'][$key]['task_id']=2;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        $historyData['tasks'][$key]['task_detail'][]='神奇花园完成一次浇水';
                    }
                }
                if($v['task_id']==4){
                    $taskArr=$this->getIsComplete($customer_id,3);
                    if($taskArr['status']==1){
                        $historyData['tasks'][$key]['task_id']=3;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        $historyData['tasks'][$key]['task_detail'][]='完成一次E评价';
                    }
                }
                if($v['task_id']==5){
                    $taskArr=$this->getIsComplete($customer_id,4);
                    if($taskArr['status']==1){
                        $historyData['tasks'][$key]['task_id']=4;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        $historyData['tasks'][$key]['task_detail'][]='完成一次饭票充值';
                    }
                }
                if($v['task_id']==6){
                    $taskArr=$this->getIsComplete($customer_id,5);
                    if($taskArr['status']==1){
                        $historyData['tasks'][$key]['task_id']=5;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        $orderArr=$this->getOrder($customer_id,5);
                        if(!empty($orderArr['caiArr'])){
                            $historyData['tasks'][$key]['task_detail'][]='彩特供下单（单笔金额>49元）';
                        }
                        if(!empty($orderArr['jingArr'])){
                            $historyData['tasks'][$key]['task_detail'][]='京东下单（单笔金额>49元）';
                        }
                        
                    }
                }
                if($v['task_id']==7 || $v['task_id']==8){
                    $taskArr=$this->getIsComplete($customer_id,6);
                    if($taskArr['status']==1){
                        $historyData['tasks'][$key]['task_id']=6;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        if(!empty($taskArr['task'])){
                            $historyData['tasks'][$key]['task_detail'][]='彩富用户直接通关';
                        }
                        $inviteArr=$this->getInvite($customer_id,6);
                        if(!empty($inviteArr)){
                            foreach ($inviteArr as $k1=>$v1){
                                $mobile=substr_replace($v1['mobile'],'****',3,4);
                                $historyData['tasks'][$key]['task_detail'][]='邀请好友'.$mobile.'注册成功';
                            }
                        }
                        
                    }
                }
                if($v['task_id']==9 || $v['task_id']==10){
                    $taskArr=$this->getIsComplete($customer_id,7);
                    if($taskArr['status']==1){
                        $historyData['tasks'][$key]['task_id']=7;
                        $historyData['tasks'][$key]['complete_date']=date('Y.m.d',$v['create_time']);
                        if(!empty($taskArr['task'])){
                            $historyData['tasks'][$key]['task_detail'][]='完成预缴物业费或停车费';
                        }
                        $inviteArr=$this->getInvite($customer_id,7);
                        if(!empty($inviteArr)){
                            foreach ($inviteArr as $k2=>$v2){
                                $mobile=substr_replace($v2['mobile'],'****',3,4);
                                $historyData['tasks'][$key]['task_detail'][]='邀请好友'.$mobile.'注册成功';
                            }
                        }
                        
                    }
                }
            }
            
            @$historyData['tasks']=array_values($historyData['tasks']);
        }else{
            $historyData['tasks']=array();
        }
        return $historyData;
    }
    /*
     * @version 获取邀请注册的所有用户信息
     * @param int $customer_id
     * @param int $dayId
     * return array
     */
    public function getInvite($customer_id,$dayId){
        if(empty($customer_id)){
            return false;
        }
        $beginTime=strtotime($this->dateArr[$dayId]);
        $endTime=strtotime($this->dateArr[$dayId+1]);
        $sqlSelect='select * from invite where `status`=1 and effective=1 and customer_id='.$customer_id.' and create_time>='.$beginTime.' and create_time<'.$endTime;
        $inviteArr=Yii::app()->db->createCommand($sqlSelect)->queryAll();
        return $inviteArr;
    }
    /*
     * @version 订单信息
     * @param int $customer_id
     * @param int $dayId
     * return array
     */
    public function getOrder($customer_id,$dayId){
        if(empty($customer_id)){
            return false;
        }
        $shopArr=array();
        $beginTime=strtotime($this->dateArr[$dayId]);
        $endTime=strtotime($this->dateArr[$dayId+1]);
        $sqlSelect="select * from `order` where buyer_id=".$customer_id." and seller_id in(2607,5043,5018,5016,5019,5021,5022,5039,4999,5031,5030,5029,5036,5040,5044,5047,4990)  and status in (1,3,4,99) and amount>49 and income_pay_time>=".$beginTime." and income_pay_time<".$endTime;
        $sqlSelect2="select * from `order` where buyer_id=".$customer_id." and seller_id=4996 and status in (1,3,4,99) and amount>49 and income_pay_time>=".$beginTime." and income_pay_time<".$endTime;
        $caiArr=Yii::app()->db->createCommand($sqlSelect)->queryAll();
        $jingArr=Yii::app()->db->createCommand($sqlSelect2)->queryAll();
        return $shopArr=array('caiArr'=>$caiArr,'jingArr'=>$jingArr);
    }
    /**
     * 获取祝福次数
     * @param int $customer_id  用户id
     * @param int $task_id 任务
     * @return int 
     */
    public function getZhuFuNum($customer_id,$task_id){
        if(empty($customer_id) || empty($task_id)){
            return false;
        }
    	$num=0;
    	$beginTime = strtotime($this->dateArr[1]);
        $endTime = strtotime($this->dateArr[2]);
    	$zhuArr = TaskComplete::model ()->findAll ('customer_id=:customer_id and task_id=:task_id and create_time>=:beginTime and create_time< :endTime', array (
				':beginTime' => $beginTime,
				':endTime' => $endTime,
				':task_id' => $task_id,
                ':customer_id'=>$customer_id,
		));
    	if (!empty($zhuArr)){
    		$num=count($zhuArr);
    	}
    	return $num;
    }
    /*
     * @version 好友祝福
     * @param int $customer_id
     * @param int $openId
     * return boolean
     */
    public function getValueByZhuFu($customer_id,$open_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = strtotime($this->dateArr[1]);
        $endTime = strtotime($this->dateArr[2]);
        $now=time();
        if($now<$beginTime || $now>=$endTime){
            return -4;
        }
        //判断是否微信用户助力
    	if (!empty($open_id)){
    		$sql="select * from task_complete where open_id='".$open_id."' and task_id=2 and create_time>=".$beginTime." and create_time<".$endTime;
            $model=Yii::app()->db->createCommand($sql)->queryAll();
    		if (!empty($model)){
    			return -3;
    		}
    	}
        $sql="select * from task_complete where customer_id=".$customer_id." and task_id=2 and create_time>=".$beginTime." and create_time<=".$endTime;
        $taskArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($taskArr) && count($taskArr)>=5){
            return -2;
        }
        $res=$this->getTask($customer_id,$open_id,2);
        if($res){
            return $res;
        }else{
            false;
        }
    }
    /*
     * @version 记录数据
     * @param int $customer_id
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$open_id,$type){
        $tLog =new TaskLog();
        $tLog->customer_id=$customer_id;
        $tLog->open_id=$open_id;
        $tLog->type=$type;
        $tLog->create_time=time();
        $result = $tLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    
    
    
    
    
}


