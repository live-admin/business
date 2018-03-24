<?php
/**
 * 表杨分类.
 * User: wede
 * Date: 14-2-25
 * Time: 下午1:37
 */
Yii::import('common.components.models.Category');
class PraiseCategory extends Category{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FreshNews the static model class
     */
    public $type= 1;

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
    /**
    * @return string the associated database table name
    */
    public function tableName()
    {
        return 'fresh_news_category';
    }


    public function getItemRelationName($enabled)
    {
        return 'freshNewss';
    }

    public function getCategoryList()
    {
        $returnArr = $this->findAll(array(
                'condition'=>'state=0 and type = 1',
                'order'=>'weight desc',
            )
        );
        return CHtml::listData($returnArr, 'id', 'name');
    }

    public function getItemModelName()
    {
        return 'FreshNews';
    }
    public function getModelName()
    {
        return '赞美分类';
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('type','1');
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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
} 