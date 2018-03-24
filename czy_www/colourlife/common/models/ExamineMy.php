<?php

/**
 * This is the model class for table "examine_my".
 *
 * The followings are the available columns in table 'examine_my':
 * @property integer $id
 * @property integer $ex_id
 * @property string $create_user
 * @property integer $ex_cid
 * @property string $title
 * @property integer $state
 * @property string $statename
 * @property string $ex_msg
 * @property integer $create_time
 */
class ExamineMy extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'examine_my';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, ex_id, ex_cid, state, create_time', 'numerical', 'integerOnly'=>true),
			array('create_user, statename', 'length', 'max'=>255),
			array('title, ex_msg', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ex_id, create_user, ex_cid, title, state, statename, ex_msg, create_time', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'ex_id' => '审批编号',
			'create_user' => 'Create User',
			'ex_cid' => '分类编号',
			'title' => '标题',
			'state' => '状态( 0新建，1处理中，2完成，3拒绝)',
			'statename' => 'Statename',
			'ex_msg' => '审批内容简要',
			'create_time' => '创建时间',
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
		$criteria->compare('ex_id',$this->ex_id);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('ex_cid',$this->ex_cid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('statename',$this->statename,true);
		$criteria->compare('ex_msg',$this->ex_msg,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExamineMy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	/**
     * 获取我的审批列表
     * @param $username
     * @param $keyword
     * @param $pagesize
     * @param $pageindex
     * @return mixed
     */
    public function getExamineMy($username, $keyword, $pagesize=NULL,$pageindex=NULL){
        if(empty($username)){
            ExamineMy::model()->addError('id', "获取我的审批列表失败！");
            return false;
        }else{
            //引入彩之云的接口
            Yii::import('common.api.ColorCloudApi');
            //实例化
            $coloure = ColorCloudApi::getInstance();
            $result = $coloure->callGetExamineMy($username, $keyword, $pagesize, $pageindex);            
            if (!isset($result['data'])) {
                //如果未找到预缴费
                ExamineMy::model()->addError('id', "获取我的审批列表失败！");
                $result['data'] = array();
            }
            $data["data"]= $result['data'];
            $data["total"]  = $result["total"];
            return  $data;
        }
    }



     /**
     * oa审批回调修改状态
     * @param $order_id 订单id
     * @param $state 状态
     * @param $statename 状态名称
     * @return bool
     */
    public function UpdateAdvanceSavefee($order_id, $state, $statename)
    {
        if (empty($order_id) || empty($state) || empty($statename)) {
            return false;
        }
        $advanceFee = ExamineMy::model()->findByPk($order_id);
        if (empty($advanceFee)) {
            ExamineMy::model()->addError('id', "未知的审批！");
            return false;
        }        

        //修改我们的订单失败
        if (!ExamineMy::model()->updateByPk($order_id, array('state' => $state,'statename'=>$statename))) {
            Yii::log('失败：oa审批回调修改状态失败！', CLogger::LEVEL_INFO,'colourlife.core.ExamineMy.UpdateAdvanceSavefee');
            ExamineMy::model()->addError('id', "oa审批回调修改状态失败！");
            return false;
        }
        Yii::log('成功：修改oa审批状态成功！', CLogger::LEVEL_INFO,'colourlife.core.ExamineMy.UpdateAdvanceSavefee');
        return true;
    }



}
