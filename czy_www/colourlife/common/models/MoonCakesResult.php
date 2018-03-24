<?php
/**
 * This is the model class for table "moon_cakes_result".
 *
 * The followings are the available columns in table 'moon_cakes_result':
 * @property int $id
 * @property int $set_dumplings_id
 * @property string $linkman
 * @property string $address
 * @property string $tel
 * @property int $customer_id
 * @property int $status
 * @property int $create_time
 * @property string $express_company
 * @property string $tracking_number
 */
class MoonCakesResult extends CActiveRecord{
    public $modelName = "中月饼名单";
    
    public $customer_name;
    public $customer_mobile;
    
    const STATUS_WAIT = 0;      //等待发货
    const STATUS_SEND = 1;      //已发货
    const STATUS_RECEIVED = 2;   //已收货
    
    public function tableName() {
        return "moon_cakes_result";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('set_riceDumplings_id,linkman,address,tel,customer_id,create_time,code_relation_id','required','on' => 'create'),
            array('express_company,tracking_number,status,linkman,address,tel','safe','on' => 'update'),
            array('linkman,tel,customer_id,customer_name,customer_mobile,status', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'set_riceDumplings_id' => '抢粽子活动Id',
            'linkman' => '收件人',
            'address' => '收货地址',
            'tel' => '联系电话',
            'customer_id' => '业主Id',
            'status' => '状态',
            'create_time' => '创建时间',
            'is_deleted' => '是否删除',
            'CustomerName' => '业主姓名',
            'CustomerMobile' => '业主手机号',
            'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机号',
            'ActivityName' => '抢月饼活动',
            'express_company' => '快递公司',
            'tracking_number' => '快递单号',
            'CustomerAddress' => '业主地址',
            'code_relation_id' => '一元购码id',
        );
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('linkman', $this->linkman, true);
        $criteria->compare('tel', $this->tel, true);
        $criteria->compare('t.status', $this->status);
        if($this->customer_name || $this->customer_mobile){
            $criteria->with=array(
                'customer',
            );
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }
        $criteria->order = 't.id DESC';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }


    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'set_moon_cakes' => array(self::BELONGS_TO, 'SetMoonCakes', 'set_mooncakes_id'),
            'oneCode' => array(self::BELONGS_TO, 'OneYuanBuy', 'code_relation_id'),
        );
    }
    
    public function getActivityName(){
        return $this->set_moon_cakes?$this->set_moon_cakes->activity_name:"";
    }
    
    public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }
    
    
    public function getStatusNames(){
        return array(self::STATUS_WAIT => '等待发货',
                    self::STATUS_SEND => '已发货',
                    self::STATUS_RECEIVED => '已收货',
                );
    }
    
    public function getStatusName($html = false){
        $res = '';
        $res .= ($html) ? '<span class="label label-success">' : '';
        $res .= $this->StatusNames[$this->status];
        $res .= ($html) ? '</span>' : '';
        return $res;        
    }
    
    public function getCustomerAddress(){
        if($this->customer){
            return $this->customer->CommunityAddress."-".$this->customer->BuildName."-".$this->customer->room;
        }else{
            return "";
        }
    }

    //抢月饼 0抢光了 1抢到了 2今天已经抢到过,不能再抢 4活动未开始 5/6/7未抢到
    public function rob($act_id,$customer_id){
        if($this->isAbleRob($act_id)){
            $model = self::model()->find('customer_id='.$customer_id.' and FROM_UNIXTIME(create_time,"%Y-%m-%d")="'.date('Y-m-d',time()).'"');
            if(!$model){
                //指定组织架构(如:体验小区)，返回false(已经抢光了)
                $customerModel = Customer::model()->findByPk($customer_id);
                //if(!$customerModel){
                //    return 0;    //抢光了
                //}
                //if(!$customerModel->community > 0){
                //    return 0;//抢光了
                //}
                //$branchId=$customerModel->community->branch->id;
                //if(in_array($branchId,Item::$lucky_tiyan_branch_ids)){
                //    return 0;    //抢光了
                //}

                //今日未过,以前中过,返回未抢到
                // $ifRoded = self::model()->find("customer_id=:cid",array(":cid"=>$customer_id));
                // if($ifRoded){
                //     return 5;//未抢到
                // }

                $rand = mt_rand(1,30);
                if ($rand!=1) {
                    return 6;//未抢到
                }



                $colourlife_add = $customerModel->community->name.$customerModel->build->name.$customerModel->room;
                $colourlife_name = $customerModel->name;
                $colourlife_mobile = $customerModel->mobile;

                $select_sql = "select remaining_number from set_moon_cakes where id = ".$act_id;
                $setMoonCakesInfo = Yii::app()->db->createCommand($select_sql)->queryAll();
                if($setMoonCakesInfo[0]['remaining_number'] < 1){
                    return 0;    //抢光了
                }

                //$redPackage = new LuckyEntityEnvelope();
                //$getRed = $redPackage->gennerEntity($customer_id, $act_id);
                //$getRed = floatval($getRed);
                //if( $getRed == 0){ //产生奖失败
                //    return 7;//未抢到
                //}
                Yii::app()->db->createCommand("unlock tables")->execute();
                Yii::app()->db->createCommand("lock tables moon_cakes_result WRITE, set_moon_cakes WRITE, one_yuan_code WRITE")->execute();
                $update_sql ="update set_moon_cakes set remaining_number=remaining_number-1 where remaining_number=".$setMoonCakesInfo[0]['remaining_number']." and id =".$act_id;
                $res = Yii::app()->db->createCommand($update_sql)->execute();
                if($res){
                    $insert_sql = "insert into moon_cakes_result (set_mooncakes_id,tel,linkman,address,customer_id,create_time) values ('".
                        $act_id."','".$colourlife_mobile."','".$colourlife_name."','".$colourlife_add."','".$customer_id."','".time()."');";
                    $res2 = Yii::app()->db->createCommand($insert_sql)->execute();
                    if($res2){
                        $result_id = Yii::app()->db->getLastInsertID();

                        $sqls = "select * from one_yuan_code where is_send=0 and update_time=0 and is_use=0 limit 1";
                        $ss = Yii::app()->db->createCommand ($sqls)->queryAll();
                        if ($ss) {
                            $sqlu2 = "update one_yuan_code set is_send=1,customer_id={$customer_id},send_time='".time()."',valid_time='".strtotime("+1 month")."',goods_id=20755,model='MoonCakesResult',relation_id={$result_id} where id=".$ss[0]["id"];
                            Yii::app()->db->createCommand($sqlu2)->execute();

                            $sqlu = "update moon_cakes_result set code_relation_id={$ss[0]["id"]} where id=".$result_id;
                            Yii::app()->db->createCommand($sqlu)->execute();
                            Yii::app()->db->createCommand("unlock tables")->execute();
                            return 1; //抢到了
                        }
                        else {
                            Yii::app()->db->createCommand("unlock tables")->execute();
                            return 7; //没抢到
                        }

                    }else{
                        Yii::app()->db->createCommand("unlock tables")->execute();
                        return 8; //没抢到
                    }
                }else{
                    Yii::app()->db->createCommand("unlock tables")->execute();
                    return 5;    //没抢到
                }
                //Yii::app()->db->createCommand("unlock tables")->execute();

            }else{
                return 2;      //今天已经抢到过,不能再抢
            }
        }else{
            return 0;        //抢光了
        }
    }



    //判断是否可以抢，返回值true代表能抢  false代表不能抢
    public function isAbleRob($set_mooncakes_id){
       $setMoonCakes = SetMoonCakes::model()->findByPk($set_mooncakes_id);
       if(strtotime($setMoonCakes->end_time) >= time()){
            if(!$setMoonCakes){
                return false;
            }
            if($setMoonCakes->remaining_number >= 1){                
                return true;
            }else{
                return false;
            }
       }else{
           return false;
       }
    }
    


    //判断是否可以抢，返回值true代表能抢  false代表不能抢
    public function NewisAbleRob($set_mooncakes_id){
       $setMoonCakes = SetMoonCakes::model()->findByPk($set_mooncakes_id);
       if(strtotime($setMoonCakes->end_time) >= time()&&strtotime($setMoonCakes->start_time) <= time()){
            if(!$setMoonCakes){
                return 0;
            }
            if($setMoonCakes->remaining_number >= 1){
                return 2;
            }else{
                return 1;
            }
       }else{
           return 0;
       }
    }




    //获取最近中奖信息
    public function getNewInfo(){  
        $listJia=array(
            '恭喜金陵天成小区业主陈*抢到了一份彩生活月饼！',
            '恭喜碧水龙庭业主王**抢到了一份彩生活月饼！',
            '恭喜碧水龙庭业主梁**抢到了一份彩生活月饼！',
            '恭喜景尚雅苑业主吴**抢到了一份彩生活月饼！'
        );
        //倒叙查询最近20条记录
        $criteria =new CDbCriteria;
        $criteria->limit =20; 
        $criteria->order = "id desc";
        $data = MoonCakesResult::model()->findAll($criteria);
        if(count($data) > 3) {
            $listJia = array();            
        }
        foreach($data as $_v){
            $name=empty($_v->customer)?(""):(F::msubstr($_v->customer->name,0,1)."**");
            $listJia[] = "恭喜".$_v->customer->community->name."业主".$name."抢到了一份彩生活月饼！";
        }
        return $listJia;
    }
    
    
    
}