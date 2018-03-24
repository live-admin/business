<?php

/**
 * This is the model class for table "purchase_goods_region_relation".
 *
 * The followings are the available columns in table 'purchase_goods_region_relation':
 * @property integer $goods_id
 * @property integer $region_id
 * @property integer $update_time
 */
class PurchaseGoodsRegionRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_goods_region_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, region_id, update_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('goods_id, region_id, update_time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'goods_id' => '商品',
			'region_id' => '地区ID',
			'update_time' => '操作时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AppCommunityRelation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 添加城市与商品的关系
     * @param $goods_id
     * @param $RegionList
     * @return bool
     */
    public function saveAll($goods_id, $RegionList)
    {
        if (empty($goods_id)) {
            return false;
        }
        if (empty($RegionList)) { //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;
        } else {
            $sql = "INSERT INTO purchase_goods_region_relation (goods_id,region_id,update_time) VALUES ";
            $sqlArr = array();
            $i = 0;
            $max = count($RegionList);
            $update_time = time();
            $return = true;
            foreach ($RegionList as $key => $value) {
                $i++;
                //拼接sql语句
                array_push($sqlArr, "({$goods_id},{$value},{$update_time})");
                //如果有了100条，或者已全部拼接完
                if ($i % 100 == 0 || $i >= $max) {
                    //执行插入语句
                    if (Yii::app()->getDb()->getPdoInstance()->query($sql . trim(implode(',', $sqlArr), ','))) {
                        //成功则情况数组,继续下一次插入
                        $sqlArr = array();
                    } else {
                        //任何一次失败都结束循环
                        $return = false;
                        break;
                    }
                }
            }
            return $return;
        }
    }

    public function  updateRegionRelation($goods_id, $RegionList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('goods_id' => $goods_id));
        return $this->saveAll($goods_id, $RegionList);
    }

    static public function getRegionIds($id)
    {
        $arr = array();
        $id = intval($id);
        $criteria = new CDbCriteria();
        $criteria->select = 'region_id';
        $criteria->addCondition('goods_id='.$id);
        $ids = self::model()->findAll($criteria);
        if(count($ids)>0)
        {
            foreach($ids as $region)
            {
                $arr[]=$region->region_id;
            }
        }
        return $arr;
    }
}
