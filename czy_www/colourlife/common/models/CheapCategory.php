<?php


class CheapCategory
{
    public $modelName = '优惠商品分类';

    public static $Category = array(
        1 => '天天特价',
        2 => '促销',
        3 => '一元购',
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getCheapCategory()
    {
        return self::$Category;
    }

    public static function getCheapCategoryName($cheap_category_id = 0)
    {
        return self::$Category[$cheap_category_id];
    }

}
