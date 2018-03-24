<?php
class OtherFeesLogic
{
    public static function getFreeList($feeType, $community_id, $page, $pagesize, $permission, $employee_id)
    {
        if(!Yii::app()->getAuthManager()->checkAccess($permission, $employee_id))
        {
            return array(
                'type' => 0,
                'detail' => array(),
            );
        }
        $otherFeeList = array();
        
        $otherFeeList['type']=1;
        $otherFeeList['detail']=array();
        
        $criteria=new  CDbCriteria();
        
        if($feeType == "PropertyFees")
        {
            $criteria->with[] = "PropertyFees";
            $criteria->compare("`PropertyFees`.community_id", $community_id);
            $criteria->compare("`t`.`model`", "PropertyFees");
        }
        if($feeType == "AdvanceFees")
        {
            $criteria->with[]="AdvanceFees";
            $criteria->compare("`AdvanceFees`.community_id", $community_id);
            $criteria->compare("`t`.`model`", "AdvanceFees");
        }        
        if($feeType == "ParkingFees")
        {
            $criteria->with[]="ParkingFees";
            $criteria->compare("`ParkingFees`.community_id", $community_id);
            $criteria->compare("`t`.`model`", "ParkingFees");
        }
        
        $criteria->order = "`t`.`create_time` DESC";
        $criteria->limit = $pagesize;
        $page_count = (intval($page) - 1) * $pagesize;
        $criteria->offset = $page_count;
        
        $data=OthersFees::model()->findAll($criteria);
        foreach($data as $val){
            $otherFeeList['detail'][] = self::OtherFeeObjToArray($val, $feeType);
        }
        
        return $otherFeeList;
    }
    
    public static function findByPk($id)
    {
        $fee = OthersFees::model()->findByPk($id);
        if($fee == null){
            return null;
        }
        $fee = self::OtherFeeObjToArray($fee, $fee->model);
        $fee['community_name'] = '';
        if(isset($fee['community_id']))
        {           
            $comm = Community::model()->findByPk($fee['community_id']);            
            if($comm != null)
            {
                $fee['community_name'] = $comm->name;
            }
        }
        return $fee;
    }
    
    private static function OtherFeeObjToArray($obj, $feeType)
    {
        $row = array();
        $row['id']=$obj->id;
        $row['sn']=$obj->sn;
        $row['model']=$obj->model;
        $row['object_id']=$obj->object_id;
        $row['customer_id']=$obj->customer_id;
        $row['customer_name']=$obj->getCustomerName();
        $row['payment_id']=$obj->payment_id;
        $row['payment_name']=$obj->getPaymentName();
        $row['mobile']=$obj->getCustomerMobile();
        $row['amount']=$obj->amount;
        $row['note']=$obj->note;
        $row['create_ip']=$obj->create_ip;
        $row['create_time']= $obj->create_time;
        $row['status']=$obj->status;
        $row['pay_time']= $obj->pay_time;
        $row['update_time']= $obj->update_time;
        
        if($feeType == "PropertyFees")
        {
            $row['community_id']=$obj->PropertyFees->community_id;
            $row['room']=$obj->PropertyFees->room;
            $row['build']=$obj->PropertyFees->build;
            $row['colorcloud_unit']=$obj->PropertyFees->colorcloud_unit;
            $row['colorcloud_building']=$obj->PropertyFees->colorcloud_building;
            $row['colorcloud_order']=$obj->PropertyFees->colorcloud_order;
        }
        if($feeType == "AdvanceFees")
        {
            $row['community_id']=$obj->AdvanceFees->community_id;
            $row['room']=$obj->AdvanceFees->room;
            $row['build']=$obj->AdvanceFees->build;
            $row['colorcloud_unit']=$obj->AdvanceFees->colorcloud_unit;
            $row['colorcloud_building']=$obj->AdvanceFees->colorcloud_building;
            $row['colorcloud_order']=$obj->AdvanceFees->colorcloud_order;
        }
        if($feeType == "ParkingFees")
        {
            if(isset($obj->ParkingFees))
            {
                $row['community_id']=$obj->ParkingFees->community_id;
                $row['parking_id']=$obj->ParkingFees->id;
                $row['car_number']=$obj->ParkingFees->car_number;                        
                $row['type_id']=$obj->ParkingFees->type_id;
                $row['period']=$obj->ParkingFees->period;
                $row['room']=$obj->ParkingFees->room;
                $row['build_id']=$obj->ParkingFees->build_id;
                $row['build']=Build::model()->getBuildName($obj->ParkingFees->build_id);
            }
        }
        
        return $row;
    }
}