<?php

/**
 * This is the model class for table "Goods_category_community_relation".
 *
 * The followings are the available columns in table 'Goods_category_community_relation':
 * @property integer $id
 * @property integer $pid
 * @property integer $goods_category_id
 * @property integer $community_id
 * @property integer $percentage
 * @property integer $create_time
 * @property GoodsCategory|null  $goodsCategory
 * @property Community|null $community
 */
class GoodsCategoryCommunityRelation extends CActiveRecord
{
    public $modelName = '分类提成';
    public $start_time;
    public $end_time;
    public $branch_id;
    public $name;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'goods_category_community_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, goods_category_id', 'required'),
			array('pid, goods_category_id, community_id', 'numerical', 'integerOnly'=>true),
            array('pid, goods_category_id, community_id, percentage, create_time, branch_id', 'safe', 'on' => 'create, update'),
            array('goods_category_id, community_id', 'checkCreate', 'on' => 'create, update'),
            array('percentage', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, goods_category_id, community_id, percentage, create_time, start_time, end_time, branch_id', 'safe', 'on'=>'search'),
		);
	}

    public function checkCreate()
    {
        if(!$this->hasErrors()){
            if($this->isNewRecord){
                if($model = self::model()->findByAttributes(array('goods_category_id' => $this->goods_category_id, 'community_id' => $this->community_id, 'pid' => $this->pid))){
                    $this->addError('community_id', '当前小区已绑定分成比例！');
                }
            }
            else{
                $cdb = new CDbCriteria();
                $cdb->compare('goods_category_id', $this->goods_category_id)
                    ->compare('community_id', $this->community_id)
                    ->compare('pid', $this->pid);
                $cdb->addCondition('id != '. $this->id);
                if($model = self::model()->find($cdb)){
                    $this->addError('community_id', '当前小区已绑定分成比例！');
                }
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'business' => array(self::BELONGS_TO, 'BusinessCategory', 'pid'),
            'goodsCategory' => array(self::BELONGS_TO, 'GoodsCategory', 'goods_category_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
		);
	}

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pid' => '公司业务分类',
			'goods_category_id' => '商品分类',
			'community_id' => '小区',
			'percentage' => '比例',
            'branch_id' => '管辖部门',
			'create_time' => '创建时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('goods_category_id',$this->goods_category_id);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('percentage',$this->percentage);
        if ($this->start_time != "") {
            $criteria->addCondition('create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getGoodsCategoryName()
    {
        if($model = GoodsCategory::model()->findByPk($this->goods_category_id)){
            return $model->name;
        }
        return '';
    }

    public function getCommunityName()
    {
        if($model = Community::model()->findByPk($this->community_id)){
            return $model->name;
        }
        return '';
    }

    public function getCommunity()
    {
        $data = array();
        $model = Community::model()->findAllByAttributes(array('state'=>0));
        foreach($model as $val){
            $data[$val->id] = $val->name;
        }
        return $data;
    }

    protected function afterFind()
    {
        $this->name = !empty($this->goodsCategory) ? $this->goodsCategory->name.'-':'';
        $this->name .= !empty($this->community) ? $this->community->name : $this->name;
        parent::afterFind();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GoodsCategoryCommunityRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 根据区域ID增加当前区域ID下的小区以前子区域的小区
     * @param $branch_id
     * @return bool
     */
    public function createBranchToCommunity($branch_id)
    {
        if($community = Community::model()->findAllByAttributes(array('branch_id' => $branch_id))){
            foreach($community as $val){
                if(!$model = self::model()->findByAttributes(array('pid' => $this->pid, 'community_id' => $val->id, 'goods_category_id' => $this->goods_category_id))){
                    $model = new self();
                }
                if($model->isNewRecord){
                    $model->setScenario('create');
                }
                else{
                    $model->setScenario('update');
                }
                $model->pid = $this->pid;
                $model->goods_category_id = $this->goods_category_id;
                $model->community_id = $val->id;
                $model->percentage = $this->percentage;
                if(!$model->save()){
                    return false;
                }
            }
        }
        if($branch = Branch::model()->findAllByAttributes(array('branch_id' => $branch_id, 'state' => 0))){
            foreach($branch as $val)
            {
                $this->createBranchToCommunity($val->id);
            }
        }
        return true;
    }

    /**
     * 获取客户经理的分成比例
     * 这个问题取最底层的数据即茶饮料的分成比例10%,如果底层没有配置 默认往上一层取，如果分成比例都没配置 按照分层比例为0%
     * ps 这条是由王昊确认的
     * @param integer $community_id 订单的小区ID
     * @param integer $category_id  商品的分类ID
     * @return float|int|boolean    分成比例
     */
    public static function getAmRate($community_id, $category_id)
    {
        /**
         * @var $goodsCategory  GoodsCategory
         */
        /**
         * @var $am GoodsCategoryCommunityRelation
         */
        $rate = false;
        $goodsCategory = GoodsCategory::model()->findByPk($category_id);
        if(!empty($goodsCategory)){
            $am = GoodsCategoryCommunityRelation::model()->findByAttributes(array('community_id' => $community_id, 'goods_category_id' => $goodsCategory->id));
            if(!empty($am)){
                $rate = $am->percentage / 100;
            }
            elseif(!empty($goodsCategory->parent_id)){//查询不到客户经理的分成比例且父分类不为空时 查询上级分类的分成比例
                $rate = self::getAmRate($community_id, $goodsCategory->parent_id);
            }
        }
        return $rate;
    }
}
