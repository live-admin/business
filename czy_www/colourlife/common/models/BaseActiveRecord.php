<?php
class BaseActiveRecord extends CActiveRecord
{
    public function primaryKey()
    {
        return 'id';
        // 对于复合主键，要返回一个类似如下的数组
        //return array('shop_id', 'community_id');
    }
}
