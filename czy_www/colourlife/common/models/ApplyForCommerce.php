<?php
/**
 * This is the model class for table "apply_for_commerce".
 *
 * The followings are the available columns in table 'apply_for_commerce':
 * logo,title,introduction,details,type,state
 * @property string $username
 * @property integer $sex
 * @property string $tel
 * @property string $note
 * @property string $commerce_id
 * @property integer $create_time
 * @property integer $state
 */

class ApplyForCommerce extends CActiveRecord
{  
    
    public $modelName = '社区招商(用户申请)';
    
    const SEX_SECRET = 0; //性别（保密）
    const SEX_MAN = 1;   //性别（男）
    const SEX_WOMEN = 2;   //性别（女）

    const TYPE_SUPPLIER = 0;    //招供应商
    const TYPE_LEAGUE = 1;      //招加盟商
    
    const STATE_UNTREATED = 0;  //未处理
    const STATE_PROCESSING = 1;   //处理中
    const STATE_PROCESSED = 2;   //已处理
    const STATE_REFUSED = 3;   //拒绝
    
    
    public function getTypeName(){
        switch($this->type){
            case self::TYPE_SUPPLIER:
                return "招供应商";
                break;
            case self::TYPE_LEAGUE:
                return "招加盟商";
                break;
            default :
                return "未知";
                
        }
    }
    
    public function getTypeNames(){
        return array(self::TYPE_SUPPLIER => '招供应商',
                    self::TYPE_LEAGUE => '招加盟商',
                );
    }
    
    public function getStateName(){
        switch ($this->state){
            case self::STATE_UNTREATED:
                return "未处理";
                break;
            case self::STATE_PROCESSING:
                return "处理中";
                break;
            case self::STATE_PROCESSED:
                return "已处理";
                break;
            case self::STATE_REFUSED:
                return "拒绝";
                break;
            default :
                return "未知";
        }
    }
    
    public function getSexName(){
        switch($this->sex){
            case self::SEX_SECRET:
                return "保密";
                break;
            case self::SEX_MAN:
                return "男";
                break;
            case self::SEX_WOMEN:
                return "女";
                break;
        }
    }
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Employee the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'apply_for_commerce';
    }
       
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => '姓名',
            'sex' => '性别',
            'tel' => '手机号码',
            'note' => '留言',
            'type' => '类型',
            'state' => '状态',
            'create_time' => '创建时间',
        );
    }
    
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('username', $this->username, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('tel', $this->tel, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function rules()
    {
        return array(
            array('username,tel','required','on' => 'create'),
            array('note,sex','safe','on' => 'create'),
            array('username,type,tel','safe' , 'on' => 'search'),
        );
    }
    
}
        