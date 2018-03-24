<?php
/**
 * Created by PhpStorm.
 * User: wede
 * Date: 14-2-24
 * Time: 下午4:53
 */
Yii::import('common.components.models.Category');
class FreshNewsCategory  extends Category{


    public $type=0;
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FreshNews the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    public function rules()
    {
        $array = array(
            array('type', 'safe'),
        );
        return CMap::mergeArray(parent::rules(), $array);
    }

   public function behaviors(){
       $reutnr = array( 'CTimestampBehavior' => array(
           'class' => 'zii.behaviors.CTimestampBehavior',
           'createAttribute' => 'create_time',
           'updateAttribute' => null,
           'setUpdateOnCreate' => true,
       ));
       return CMap::mergeArray(parent::behaviors(),$reutnr);
   }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'fresh_news_category';
    }
    public function getItemModelName()
    {
        return 'FreshNews';
    }

    public function getModelName()
    {
        return '新鲜事分类';
    }

    public function getItemRelationName($enabled)
    {
        return 'freshNewss';
    }

    public static function getFreshCategory($id=null)
    {
        $data = array();
        if(!empty($id)){
            if($model = self::model()->findByPk($id)){
                return $model->name;
            }
        }
        else{
            $cdb = new CDbCriteria();
            $cdb->addCondition('type = 0')->addCondition('state = 0')->addCondition('is_deleted = 0');
            $cdb->order = 'weight';
            if($model = self::model()->findAll($cdb)){
                foreach($model as $val)
                {
                    $data[$val->id] = $val->name;
                }
            }
        }
        return $data;
    }

    public function getCategoryList()
    {
        $returnArr = $this->findAll(array(
                'condition'=>'state = 0 and type = 0 ',
                'order'=>'weight desc',
            )
        );
        return CHtml::listData($returnArr, 'id', 'name');
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('type','0');
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

} 