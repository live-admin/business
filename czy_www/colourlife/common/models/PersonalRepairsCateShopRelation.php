<?php

/**
 * This is the model class for table "personal_repairs_cate_shop_relation".
 *
 * The followings are the available columns in table 'personal_repairs_cate_shop_relation':
 * @property integer $repairs_cate_id
 * @property integer $shop_id
 * @property integer $state
 * @property integer $update_time
 */
class PersonalRepairsCateShopRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'personal_repairs_cate_shop_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('repairs_cate_id, shop_id, state, update_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('repairs_cate_id, shop_id, state, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
           // 'repairsCate'=>array(self::BELONGS_TO,'PersonalRepairsCategory','repairs_cate_id'),
            'shop'=>array(self::BELONGS_TO,'Shop','shop_id','condition' => "shop.type ='0'"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'repairs_cate_id' => '报修分类',
			'shop_id' => '商家',
			'state' => '状态',
			'update_time' => '更新时间',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('repairs_cate_id',$this->repairs_cate_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('state',$this->state);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function searchByLocal()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('repairs_cate_id',$this->repairs_cate_id);
        $criteria->compare('shop_id',Yii::app()->user->id);
        $criteria->compare('state',$this->state);
        $criteria->compare('update_time',$this->update_time);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function cancel(){
        PersonalRepairsCateShopRelation::model()->deleteAllByAttributes(array('shop_id'=>$this->shop_id,'repairs_cate_id'=>$this->repairs_cate_id));
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'update_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => false,
            ),
        );
    }

    public function assent(){
        $criteria = new CDbCriteria();
        $criteria->compare('shop_id',$this->shop_id);
        $criteria->compare('repairs_cate_id',$this->repairs_cate_id);
        $return = PersonalRepairsCateShopRelation::model()->updateAll(array('state'=>1),$criteria);
        return $return;
    }

    public function noAssent(){
        $criteria = new CDbCriteria();
        $criteria->compare('shop_id',$this->shop_id);
        $criteria->compare('repairs_cate_id',$this->repairs_cate_id);
        $return = PersonalRepairsCateShopRelation::model()->updateAll(array('state'=>0),$criteria);
        return $return;
    }

    public function getCategoryName(){
        $category = PersonalRepairsCategory::model()->findByPk($this->repairs_cate_id);
        if(empty($category)){
            return "";
        }else{
            return $category->name;
        }
    }

    //根据个人报修分类获得商家
    public function getShopByCategory($category_id,$community_id=0){
        $relationList = self::model()->findAllByAttributes(array('repairs_cate_id'=>$category_id));
        if(empty($relationList)){
            $ids = array();
        }else{
            $ids = array_map(function($relat){
                if($relat->state==1){
                    return $relat->shop_id;
                }
            },$relationList);
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);

        if(!empty($community_id)){
            $scRelation = ShopCommunityRelation::model()->findAllByAttributes(array('community_id'=>$community_id));
            $shopList = array_map(function($scModel){
                return $scModel->shop_id;
            },$scRelation);
            $criteria->addInCondition('id',$shopList);
        }

        $shopList = Shop::model()->findAll($criteria);
        return CHtml::listData($shopList,'id','name');
    }

    //判断小区是否在选择的商家的服务范围内
    public function checkShopCommunityRelation($shop_id,$community_id){
        if(empty($shop_id)||empty($community_id)){
            return false;
        }else{
            $scRelation = ShopCommunityRelation::model()->findAllByAttributes(array('shop_id'=>$shop_id));
            $communityList = array_map(function($scModel){
                return $scModel->community_id;
            },$scRelation);
            if(in_array($community_id,$communityList)){
                return true;
            }else{
                return false;
            }
        }
    }
}
