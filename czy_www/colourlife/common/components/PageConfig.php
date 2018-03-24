<?php 
class PageConfig{
    //洗衣绑定的商家ID
    const LIFE_SHOP_BY_LAUNDRY = 4;
    //家电维修绑定的商家ID
    const LIFE_SHOP_BY_REPAIR = 180;
    //房屋绑定的商家ID
    const LIFE_SHOP_BY_HOUSE = 12;
    
    //本地生活页面右侧美食商家的类型
    const LIFE_SHOP_TYPE_BY_GOURMET = 1;
    //本地生活页面右侧旅游商家的类型
    const LIFE_SHOP_TYPE_BY_TOURISM = 5;
    //本地生活页面右侧休闲娱乐商家的类型
    const LIFE_SHOP_TYPE_BY_CAR = 4;

    //加盟站不能商家和供应商无加盟关系时的提示语
    const SHOP_JM_NO_RELATION_MESSAGE = "您还没有加盟该商家,暂时不能购买此商品！加盟热线:0755-8888888";
    
    //预订类型
    private static $life_reserve_type = array(
        0 => '洗衣预订',
        1 => '家电维修预订',
        2 => '房屋',
    );
    
    //服务时间
    private static $life_reserve_time = array(
        0 =>array(//洗衣预订的时间
            'AM09:00-PM13:00' => 'AM09:00-PM13:00',
            'PM13:00-PM18:30' => 'PM13:00-PM18:30',
            'PM18:30-PM22:30' => 'PM18:30-PM22:30',
        ),
        1 =>array(//家电维修的预订时间
            'AM07:00-PM12:00' => 'AM07:00-PM12:00',
            'PM12:00-PM17:30' => 'PM12:00-PM17:30',
            'PM17:30-PM24:00' => 'PM17:30-PM24:00',
            'AM00:00-AM07:00' => 'AM00:00-AM07:00',
        ),
        2 => array(
            '出售' => '我有房出售',
            '出租' => '我有房出租',
            '求租' => '我要租房',
            '买房' => '我要买房',
        ),
    );
    
    /**
     * 得到预订的类型
     * Parameter:type=>定义的服务类型值
     * */
    static public function getServeType($type){
        if (empty(self::$life_reserve_type[$type])) {
            throw new CHttpException('500', '无效的预订类型');
        }else{
            return self::$life_reserve_type[$type];
        }
    } 
    /**
     * 得到预订的服务时间集合
     * Parameter:type=>定义的服务类型值
     * */
    static public function getServerTimeList($type){
        if (empty(self::$life_reserve_type[$type])) {
            throw new CHttpException('500', '无效的预订类型');
        }else{
            return self::$life_reserve_time[$type];
        }
    }
    
    static public function getServerTime($type,$value){
        if (empty(self::$life_reserve_type[$type])) {
            throw new CHttpException('500', '无效的预订类型');
        }else{
            if (empty(self::$life_reserve_type[$type][$value])) {
                throw new CHttpException('500', '无效的预订类型');
            }else{
                return self::$life_reserve_time[$type][$value];
            }
        }
    }
    
}

