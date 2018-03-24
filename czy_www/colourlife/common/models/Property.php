<?php
/**
 * Created by PhpStorm.
 * User: taodanfeng
 * Date: 2016/10/20
 * Time: 10:37
 * 预缴费活动
 */
class Property extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    //获取抽奖机会次数
    public function getChanceNumber($userID){
        $connection = Yii::app()->db;
        $Sql = 'SELECT `number`  FROM `property_prize_chance` WHERE `customer_id`=:customer_id and `status`=0';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':customer_id', $userID, PDO::PARAM_STR);
        $list = $command->queryColumn();
        if ($list) {
            return $list[0];
        }else{
            return 0;
        }
    }
    //获取中奖纪录列表
    public function getPrizeRecord($userID){
        $param=array();
        $connection = Yii::app()->db;
        $Sql = 'SELECT a.*,b.name,b.img_url FROM property_prize_record a left join property_prize b on a.prize_id=b.id WHERE a.customer_id=:customer_id';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':customer_id', $userID, PDO::PARAM_STR);
        $list = $command->queryAll();
        if ($list) {
            foreach($list as $vo){
                if($vo['status']==1){
                    //已领取(饭票和券不做时间处理，实物无下订单，故不跳到订单详情页面)
                    $datetime=date('Y-m-d',$vo['time']);
                    $url = F::getUploadsUrl('/images/' .$vo['img_url']);
                    //$url = F::getStaticsUrl('/activity/v2016/property/images/' . $vo['img_url']);
                    $param[]=array('id'=>$vo['prize_id'],'time'=>$vo['time'],'name'=>$vo['name'],'level_id'=>$vo['level_id'],'img_url'=> $url,'status'=>$vo['status'],'datetime'=>$datetime);
                }else{
                    //未领取（跳转到奖品选择页面）
                    $datetime=date('Y-m-d',$vo['time']);
                    $param[]=array('time'=>$vo['time'],'level_id'=>$vo['level_id'],'status'=>$vo['status'],'datetime'=>$datetime,'record_id'=>$vo['id']);
                }
            }
            return json_encode($param);
        }else{
            return 0;
        }
    }
    //获取中奖后的奖品选择列表
    public function getPrizeList($level_id){
        $param=array();
        $connection = Yii::app()->db;
        $Sql = 'select * from property_prize where level_id=:level_id and status=0 order by id ASC ';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':level_id', $level_id, PDO::PARAM_STR);
        $list = $command->queryAll();
        if ($list) {
            return $list;
        }else{
            return 0;
        }
    }
    //组装进入中奖后的奖品选择列表的参数
    public function getPrizeListParam($prizeList,$customer_id,$mobile,$record_id){
        $param=array();
        foreach ($prizeList as $val){
            //$url = F::getStaticsUrl('/activity/v2016/property/images/' . $val['img_url']);
            $url = F::getUploadsUrl('/images/' .$val['img_url']);
            if($val['category_id']==1||$val['category_id']==3||$val['category_id']==5){
                //京东，彩实惠，彩特供，奖品领取时跳到地址页面
                $param[]=array('id'=>$val['id'],'name'=>$val['name'],'goods_id'=>$val['goods_id'],'img_url'=>$url,'customer_id'=>$customer_id,'category_id'=>$val['category_id'],'record_id'=>$record_id);
            }else if($val['category_id']==2){
                //饭票直接调用接口
                $param[]=array('id'=>$val['id'],'name'=>$val['name'],'img_url'=>$url,'customer_id'=>$customer_id,'category_id'=>$val['category_id']);
            }else if($val['category_id']==4||$val['category_id']==6){
                //券直接调用接口
                $param[]=array('id'=>$val['id'],'name'=>$val['name'],'img_url'=>$url,'mobile'=>$mobile,'category_id'=>$val['category_id']);
            }

        }
        return json_encode($param);
    }
    //获取中奖等级的饭票金额和计划总数
    public function getTiketMoney($level_id){
        $connection = Yii::app()->db;
        $Sql = 'select price,amount from property_prize_level  where id=:level_id ';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':level_id', $level_id, PDO::PARAM_STR);
        $list = $command->queryRow();
        if ($list) {
            return $list;
        }else{
            return '';
        }
    }
    //获取发饭票账户金额
    public function getMoney($customer_id){
        $connection = Yii::app()->db;
        $Sql = 'select balance from customer  where id=:customer_id ';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':customer_id', $customer_id, PDO::PARAM_STR);
        $list = $command->queryRow();
        if ($list) {
            return $list;
        }else{
            return '';
        }
    }

    //获取用户手机号
    public function getMobile($customer_id){
        $param=array();
        $connection = Yii::app()->db;
        $Sql = 'select mobile from customer where id=:customer_id ';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':customer_id', $customer_id, PDO::PARAM_STR);
        $list = $command->queryColumn();
        if ($list) {
            return $list[0];
        }else{
            return 0;
        }
    }

    //更新奖品数量
    public function updatePrizeNumber($prize_id,$level_id){
        $connection = Yii::app()->db;
        $Sql = 'select number from property_prize  where id=:prize_id ';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':prize_id', $prize_id, PDO::PARAM_STR);
        $list = $command->queryColumn();
        $number=$list[0]+1;
        $flag=true;
        switch($prize_id){
            case 14:
                $maxnumber=2500;
                break;
            case 15:
                $maxnumber=2500;
                break;
            case 22:
                $maxnumber=100;
                break;
            case 23:
                $maxnumber=100;
                break;
            case 24:
                $maxnumber=200;
                break;
            case 25:
                $maxnumber=200;
                break;
            case 26:
                $maxnumber=400;
                break;
            case 27:
                $maxnumber=400;
                break;
            case 33:
                $maxnumber=3;
                break;
            case 34:
                $maxnumber=4;
                break;
            case 35:
                $maxnumber=3;
                break;
            default;
                //获取每等奖的计划总数（除了券和一等奖之外）
                $flag=false;
              $maxnumber=$this->getMaxnumber($level_id);
                break;
        }
        if($flag===true){
            if($number<$maxnumber){
                $data=array('number'=>$number);
                Yii::app()->db->createCommand()->update('property_prize', $data, "id=:prize_id ", array(':prize_id'=>$prize_id));
            }else if($number==$maxnumber){
                   $data=array('number'=>$number,'status'=>1);
                    Yii::app()->db->createCommand()->update('property_prize', $data, "id=:prize_id ", array(':prize_id'=>$prize_id));
            }
        }else{
            if($maxnumber>1){
                //还有剩余的计划数量
                $data=array('number'=>$number);
                Yii::app()->db->createCommand()->update('property_prize', $data, "id=:prize_id ", array(':prize_id'=>$prize_id));
            }else if($maxnumber==1){
                $data=array('number'=>$number);
                Yii::app()->db->createCommand()->update('property_prize', $data, "id=:prize_id ", array(':prize_id'=>$prize_id));
                $data1=array('status'=>1);
                Yii::app()->db->createCommand()->update('property_prize', $data1, "level_id=:level_id ", array(':level_id'=>$level_id));
            }
        }


    }
    //获取没一等奖的计划总数（除了券和一等奖之外）
    private function getMaxnumber($level_id){
        switch ($level_id){
            case 2:
            case 5:
            case 6:
                $row=$this->getTiketMoney($level_id);
                 $useNumber=$this->getUseNumber($level_id);
                $maxNumber=$row['amount']-$useNumber['total'];
                break;
            case 3:
                $row=$this->getTiketMoney($level_id);
                $useNumber=$this->getUseNumber($level_id);
                $maxNumber=$row['amount']-1400-$useNumber['total'];
                break;
            case 4:
                $row=$this->getTiketMoney($level_id);
                $useNumber=$this->getUseNumber($level_id);
                $maxNumber=$row['amount']-5000-$useNumber['total'];
                break;
        }
        return $maxNumber;
    }
    //获取剩余的计划数量
    private  function getUseNumber($level_id){
        $connection = Yii::app()->db;
        $Sql = 'select sum(number) as total from property_prize where level_id=:level_id';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':level_id', $level_id, PDO::PARAM_STR);
        $list = $command->queryRow();
        if ($list) {
            return $list;
        }else{
            return 0;
        }
    }

    //获取$openCouponId
    public function getOpenCouponId($prize_id){
        $connection = Yii::app()->db;
        $Sql = 'select goods_id,`name` from property_prize where id=:prize_id';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':prize_id', $prize_id, PDO::PARAM_STR);
        $list = $command->queryRow();
        if ($list) {
            return $list;
        }else{
            return 0;
        }
    }
    //获取用户在该中奖时间内是否有纪录
    public function getRecordRow($time){
        $connection = Yii::app()->db;
        $Sql = 'select * from property_prize_record where time=:time';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':time', $time, PDO::PARAM_STR);
        $list = $command->queryRow();
       return $list;
    }

}