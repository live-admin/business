<?php
/**
 * This is the model class for table "rice_dumplings_result".
 *
 * The followings are the available columns in table 'rice_dumplings_result':
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
class RiceDumplingsResult extends CActiveRecord{
    public $modelName = "中粽子名单";
    
    public $customer_name;
    public $customer_mobile;
    
    const STATUS_WAIT = 0;      //等待发货
    const STATUS_SEND = 1;      //已发货
    const STATUS_RECEIVED = 2;   //已收货
    
    public function tableName() {
        return "rice_dumplings_result";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function rules() {
        return array(
            array('set_dumplings_id,linkman,address,tel,customer_id,create_time','required','on' => 'create'),
            array('express_company,tracking_number,status,linkman,address,tel','safe','on' => 'update'),
            array('linkman,tel,customer_id,customer_name,customer_mobile,status', 'safe', 'on' => 'search'),
        );
    }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'set_dumplings_id' => '粽子活动Id',
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
            'ActivityName' => '抢粽子活动',
            'express_company' => '快递公司',
            'tracking_number' => '快递单号',
            'CustomerAddress' => '业主地址',
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
            'set_rice_dumplings' => array(self::BELONGS_TO, 'SetRiceDumplings', 'set_dumplings_id'),
        );
    }
    
    public function getActivityName(){
        return $this->set_rice_dumplings?$this->set_rice_dumplings->activity_name:"";
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
    
    //抢粽子
    public function rob($set_dumplings_id,$customer_id){
        if($this->isAbleRob($set_dumplings_id)){
            $model = self::model()->find('customer_id='.$customer_id.' and FROM_UNIXTIME(create_time,"%Y-%m-%d")="'.date('Y-m-d',time()).'"');
            if(!$model){
                //指定组织架构(如:体验小区)，返回false(已经抢光了)
		$customer=Customer::model()->findByPk($customer_id);
		$branchId=$customer->community->branch->id;
                if(in_array($branchId,Item::$lucky_tiyan_branch_ids)){
                    return 0;    //没抢到
                }
                $customerModel = Customer::model()->findByPk($customer_id);
                if(!$customerModel){
                    return 0;    //没抢到
                }
                $colourlife_add = $customerModel->community->name.$customerModel->build->name.$customerModel->room;
                $colourlife_name = $customerModel->name;
                $colourlife_mobile = $customerModel->mobile;
                $setRiceDumplingModel = SetRiceDumplings::model()->findByPk($set_dumplings_id);
                Yii::app()->db->createCommand("lock tables rice_dumplings_result WRITE, set_rice_dumplings READ")->execute();
                $select_sql = "select remaining_number from set_rice_dumplings where id = ".$set_dumplings_id;
                $setRiceDumplingInfo = Yii::app()->db->createCommand($select_sql)->queryAll();
                if($setRiceDumplingInfo[0]['remaining_number'] < 1){
                    return 0;    //没抢到
                }
                $insert_sql = "insert into rice_dumplings_result (set_dumplings_id,tel,linkman,address,customer_id,create_time) values ('".
                        $set_dumplings_id."','".$colourlife_mobile."','".$colourlife_name."','".$colourlife_add."','".$customer_id."','".time()."');";
                $res = Yii::app()->db->createCommand($insert_sql)->execute();                    
                Yii::app()->db->createCommand("unlock tables")->execute();
                if($res){
                    $setRiceDumplingModel->remaining_number = $setRiceDumplingModel->remaining_number - 1;
                    $setRiceDumplingModel->save();
                    return 1;     //抢到了
                }else{
                    return 0;     //没抢到
                }                                                        
            }else{
                return 2;      //今天已经抢到过，不能再抢
            }
        }else{
            return 0;        //没抢到
        }
    }
    
    //判断是否可以抢，返回值true代表能抢  false代表不能抢
    public function isAbleRob($set_dumplings_id){
       $setRiceDumpling = SetRiceDumplings::model()->findByPk($set_dumplings_id);
       if(strtotime($setRiceDumpling->end_time) >= time()){
            if(!$setRiceDumpling){
                return false;
            }
            if($setRiceDumpling->remaining_number >= 1){                
                return true;
            }else{
                return false;
            }
       }else{
           return false;
       }
    }
    
    
    //获取最近中奖信息
    public function getNewInfo(){  
        $listJia=array(
//            '恭喜金陵天成小区业主陈*抢到了一份五芳斋粽子！',
//            '恭喜碧水龙庭业主王**抢到了一份五芳斋粽子！',
//            '恭喜碧水龙庭业主梁**抢到了一份五芳斋粽子！',
//            '恭喜景尚雅苑业主吴**抢到了一份五芳斋粽子！'
        );
        //倒叙查询最近20条记录
        $criteria =new CDbCriteria;
        $criteria->limit =20; 
        $criteria->order = "id desc";
        $data = RiceDumplingsResult::model()->findAll($criteria);        
        if(count($data) > 3){
            $listJia = array();            
        }
        foreach($data as $_v){
            $name=empty($_v->customer)?(""):(F::msubstr($_v->customer->name,0,1)."**");
            $listJia[] = "恭喜".$_v->customer->community->name."业主".$name."抢到了一份五芳斋粽子！";
        }
        return $listJia;
    }
    
    
    
}