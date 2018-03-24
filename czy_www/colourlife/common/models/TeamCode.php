<?php
/**
 * author liangjianfeng
 * 世界杯球队信息表
 */
class TeamCode extends CActiveRecord{
    
    public function tableName() {
        return "team_code";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => '编码',
            'team' => '球队',
            'group' => '组别',
            'logo' => '国旗',
        );
    }

    public function getHomeTeamLogo(){
        if ($this->code === null) {
            return '';
        }
        return Yii::app()->ajaxUploadImage->getUrl($this->logo);
    }
    
}
