<?php
/**
 * This is the model class for table "Commerce".
 *
 * The followings are the available columns in table 'Commerce':
 * logo,title,introduction,details,type,state
 * @property integer $id
 * @property string $logo
 * @property string $title
 * @property string $introduction
 * @property string $details
 * @property integer $type
 * @property integer $state
 */

class Commerce extends CActiveRecord
{  
    const STATE_YES = 0;      //启用
    const STATE_NO = 1;       //禁用
    
    const TYPE_SUPPLIER = 0;    //招供应商
    const TYPE_LEAGUE = 1;      //招加盟商
    
    public $logoFile;
    /**
     * @var string 模型名
     */
    public $modelName = '社区招商';

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
        return 'commerce';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title,introduction,details,content,type,state,priority', 'required', 'on' => 'create,update'),
            array('logoFile','safe', 'on' => 'create,update'),
            array('title,type','safe' , 'on' => 'search'),
        );
    }
    
 
    public function getStateName(){
        switch ($this->state){
            case self::STATE_YES:
                return "启用";
                break;
            case self::STATE_NO:
                return "禁用";
                break;
            default :
                return "未知";
        }
    }
    
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
    
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'logo_image' => '图片',
            'logoFile' => '图片',
            'title' => '标题',
            'introduction' => '简介',
            'details' => '详情',
            'content' => '下页内容',
            'type' => '类型',
            'state' => '状态',
            'create_time' => '创建时间',
            'supplier_shop_id' => '供应商',
            'priority' => '优先级',
        );
    }
    
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        $criteria->compare('type', $this->type, true);
        $criteria->order = 'priority DESC' ;//排序条件 
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
    
    public function getLogoImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo_image);
    }
    
    /**
    * 处理图片
    * @return bool
    */
   protected function beforeSave()
   {
        if (!empty($this->logoFile)) {
                $this->logo_image = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo_image);
        }
        return parent::beforeSave();
   }

}
        