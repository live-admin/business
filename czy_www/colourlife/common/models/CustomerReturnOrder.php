<?php

class CustomerReturnOrder extends RetreatOrder
{
    public $branch_id;
    public $region;
    public $community;
    public $communityIds = array(); //小区

    public $province_id;
    public $city_id;
    public $district_id;
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('t.type',1);
        $criteria->compare('t.sn',$this->sn,true);
        $criteria->compare('order_sn',$this->order_sn,true);
        $criteria->compare('t.seller_id',$this->seller_id);
        $criteria->compare('t.buyer_id',$this->buyer_id);
        $criteria->compare('amount',$this->amount,true);
        $criteria->compare('bank_pay',$this->bank_pay,true);
        $criteria->compare('red_packet_pay',$this->red_packet_pay,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('t.create_time',$this->create_time);
        $criteria->compare('t.create_ip',$this->create_ip,true);
        $criteria->compare('t.update_time',$this->update_time);
        $criteria->compare('return_express_name',$this->return_express_name,true);
        $criteria->compare('return_express_sn',$this->return_express_sn,true);
        $criteria->compare('note',$this->note,true);
         if ($this->start_time != "") {
            $criteria->addCondition('t.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('t.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

        if(!empty($this->buyer_name)){
            $criteria->with[] = 'buyerInfo';
            $criteria->compare('buyerInfo.name',$this->buyer_name,true);
        }

        if(!empty($this->seller_name)){
            $criteria->with[] = "sellerInfo";
            $criteria->compare("sellerInfo.name",$this->seller_name,true);
        }

        return new CActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => '`t`.update_time DESC', //设置默认排序是create_time倒序
            ),
            'criteria'=>$criteria,
        ));
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            array(
                'seller_name' => '商家',
                'seller_id' => '商家',
                'buyer_id' => '业主',
                'buyer_name' => '业主'
            )
        );
    }

    public function ICEGetLinkageRegionDefaultValue()
    {
        $updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
        return $updateDefaults
            ? $updateDefaults
            : $this->ICEGetLinkageRegionDefaultValueForSearch();
    }

    protected function ICEGetLinkageRegionDefaultValueForUpdate()
    {
        return array();
    }

    public function ICEGetLinkageRegionDefaultValueForSearch()
    {
        $searchRegion = $this->ICEGetSearchRegionData(isset($_GET[__CLASS__])
            ? $_GET[__CLASS__] : array());

        $defaultValue = array();

        if ($searchRegion['province_id']) {
            $defaultValue[] = $searchRegion['province_id'];
        } else {
            return $defaultValue;
        }

        if ($searchRegion['city_id']) {
            $defaultValue[] = $searchRegion['city_id'];
        } else {
            return $defaultValue;
        }

        if ($searchRegion['district_id']) {
            $defaultValue[] = $searchRegion['district_id'];
        } else {
            return $defaultValue;
        }

        return $defaultValue;
    }

    protected function ICEGetSearchRegionData($search = array())
    {
        return array(
            'province_id' => isset($search['province_id']) && $search['province_id']
                ? $search['province_id'] : '',
            'city_id' => isset($search['city_id']) && $search['city_id']
                ? $search['city_id'] : '',
            'district_id' => isset($search['district_id']) && $search['district_id']
                ? $search['district_id'] : '',
        );
    }
}
