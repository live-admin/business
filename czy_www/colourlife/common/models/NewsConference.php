<?php
/*
 * 住宅品牌发布发码
 * @update 20150627
 * @by wenda
 */
class NewsConference extends CActiveRecord{
    
    public $modelName = "品牌发布码";

    public function tableName() {
        return "news_conference";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function rules() {
         return array(
             array('code,mobile,update_time,type','safe','on'=>'search'),
         );
     }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => '码',
            'mobile' => '手机号',
            'update_time' => '状态',
            'type' => '类型',
        );
    }
    
}
