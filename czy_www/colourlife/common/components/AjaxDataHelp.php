<?php

class AjaxDataHelp
{

//获取地区
    public static function  Regions($parent_id = 0)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('parent_id', $parent_id);

        return Region::model()->enabled()->orderByGBK()->findAll($criteria);
    }

    //根据地区id获取小区
    public static function  Communities($region_id)
    {
        $info = Yii::app()->cache->get('area'.$region_id);
        if(!empty($info)){
            return $info;
        }
        $criteria = new CDbCriteria();
        if (!empty($region_id)) {
            $crit = new CDbCriteria();
            $crit->compare('parent_id', $region_id);
            $data = Region::model()->enabled()->findAll($crit);
            if (empty($data)) {
                $criteria->compare('region_id', $region_id);
            } else {
                $criteria->addInCondition('region_id', CHtml::listData($data, 'id', 'id'));
            }

        }
        $criteria->order = 'alpha';
        $communities = Community::model()->enabled()->findAll($criteria);
        Yii::app()->cache->set('area'.$region_id,$communities,3600*24);
        return $communities;
    }
    
    //根据省Id获取小区
    public static function CommunitiesByProvince($region_id){
        $info = Yii::app()->cache->get('provinve'.$region_id);
        if(!empty($info))
            return $info;
        $criteria = new CDbCriteria();
        if (!empty($region_id)) {
            $crit = new CDbCriteria();
            $crit->compare('parent_id', $region_id);
            $data = Region::model()->enabled()->findAll($crit);
            $crit2 = new CDbCriteria();
            $crit2->addInCondition('parent_id', CHtml::listData($data, 'id', 'id'));
            $data = Region::model()->enabled()->findAll($crit2);
            if (empty($data)) {
                $criteria->compare('region_id', $region_id);
            } else {
                $criteria->addInCondition('region_id', CHtml::listData($data, 'id', 'id'));
            }
        }
        $criteria->order = 'alpha';
        $communities = Community::model()->enabled()->findAll($criteria);
        Yii::app()->cache->set('provinve'.$region_id,$communities,3600*24);
        return $communities;
    }

    ///根据小区id获取楼栋
    public static function Builds($community_id)
    {
        $criteria = new CDbCriteria();
        if (!empty($community_id)) {
            $criteria->compare('community_id', $community_id);
        }
        return Build::model()->enabled()->findAll($criteria);
    }
    
    //根据小区id获取彩之云楼栋
    public static function ColourdBuilds($community_id){
//        Yii::import('common.api.ColorCloudApi');
//        Yii::import('common.api.IceApi');
        if (!empty($community_id)) {
            $community = Community::model()->enabled()->findByPk($community_id);
            $builds = $community->getColorCloudBuildings();
        }else{
            $builds = array();
        }
        return $builds;     

		     //房间号
		     //
        
    }
    
    public static function ColourdRooms($build_id){
//        Yii::import('common.api.ColorCloudApi');
        Yii::import('common.api.IceApi');
        if (!empty($build_id)) {
            $rooms = IceApi::getInstance()->getUnitsWithBuilding($build_id);
            $rooms = $rooms['data'];
            foreach($rooms as $key=>$val){
                if(!empty($val['unitname'])){
                    $room[] = array('id'=>$val['id'], 'unitname'=>$val['unitname'], 'name'=>$val['unitname'].$val['name'],'floor'=>$val['floor']);
                }else{
                    $room[] = $val;
                }
            }
//            $rooms = ColorCloudApi::getInstance()->getUnitsWithBuilding($build_id);
        }else{
            $room = array();
        }
        return $room;
    }
    
    //根据小区获得商品
    public static function Goods($community_id,$shop_id){
        if(empty($community_id)){
            return 0;
        }else{
            $criteria = new CDbCriteria;
            $criteria->compare('shop_id',$shop_id);
            $criteria->compare('community_id',$community_id);
            $criteria->compare('is_on_sale',Goods::SALE_YES);
            return ShopCommunityGoodsSell::model()->count($criteria);
        }
    }
}