<?php
class IndividualRepairs extends ComplainRepairs
{
    public $modelName = '个人投诉';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}

?>

?>