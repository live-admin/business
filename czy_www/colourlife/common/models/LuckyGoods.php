<?php

/**
 * This is the model class for table "lucky_goods".
 *
 * The followings are the available columns in table 'lucky_goods':
 * @property integer $id
 * @property string $goods_name
 * @property integer $goods_id
 * @property integer $disable
 * @property integer $isdelete
 * @property integer $lucky_act_id
 */
class LuckyGoods extends CActiveRecord
{
	public $modelName = '可抽奖商品';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('goods_name, goods_id, lucky_act_id', 'required'),  //商品名称不填，自动填写
			array('goods_id, lucky_act_id', 'required'),
			array('goods_id, disable, isdelete, lucky_act_id', 'numerical', 'integerOnly'=>true),
			array('goods_name', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goods_name, goods_id, disable, isdelete, lucky_act_id', 'safe', 'on'=>'search'),
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
			'luckyAct' => array(self::BELONGS_TO, 'LuckyActivity', 'lucky_act_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'goods_name' => '商品名称',
			'goods_id' => '商品id',
			'disable' => '禁用',
			'isdelete' => '删除',
			'lucky_act_id' => '活动',
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
		$criteria->compare('goods_name',$this->goods_name,true);
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('disable',$this->disable);
		//$criteria->compare('isdelete',$this->isdelete);
		$criteria->compare('isdelete',0);
		$criteria->compare('lucky_act_id',$this->lucky_act_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function afterValidate()
	{
		if(empty($this->goods_id)){  //id为空，放行，在validate处会被验证不通过
		}else{
			$goodsid=$this->goods_id;
			$goods=Goods::model()->findByPk($goodsid);
			if(empty($goods) || $goos->is_deleted=0){
				$this->addError("goods_id","商品不存在或者无效");
			}else{
				if(empty($this->goods_name)){  //没有设置商品名称，自动填充
					$this->goods_name=$goods->name;
				}
			}
		}
		return parent::beforeValidate();
	}
	
	/**
	 * 截取字符并hover显示全部字符串
	 * $str string 截取的字符串
	 * $len int 截取的长度
	 */
	public function getFullString($str, $len)
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
	}
	
	/**
	 * 假删除
	 * @see CActiveRecord::delete()
	 */
	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->isdelete=1;
		$model->save();
	}
}
