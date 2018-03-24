<?php

/**
 * This is the model class for table "order_log".
 *
 * The followings are the available columns in table 'order_log':
 * @property integer $id
 * @property integer $order_id
 * @property string $user_model
 * @property integer $user_id
 * @property integer $create_time
 * @property integer $status
 * @property string $note
 */
class OrderLog extends CActiveRecord
{

    public $modelName = 'OrderLog';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'order_log';
    }

    public function rules()
    {
        return array(
            array('order_id, note', 'required'),
            array('order_id, user_id, create_time, status', 'numerical', 'integerOnly' => true),
            array('user_model', 'length', 'max' => 255),
            array('status', 'checkStatus', 'on' => 'disposal'),
            array('id, order_id, user_model, user_id, create_time, status, note', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'order_id' => '订单',
            'user_model' => '用户模型',
            'user_id' => '用户',
            'create_time' => '创建时间',
            'status' => '状态',
            'note' => '备注',
        );
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('user_model', $this->user_model, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('note', $this->note, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function checkStatus($attribute, $params)
    {
        //现在只判断是否为空，以后可能判断在某一阶段不能修改为某个状态。
        if ($this->status == 0 || $this->status == '') {
            $this->addError($attribute, '状态错误');
        }
    }

    public function getUserName($userModel = null, $user_id = '')
    {
        $userModel = ucfirst(trim($userModel));

        if ($userModel == null || $user_id == '') {
            return "";
        } elseif ($user_id == 0) {
            return '系统';
        } else {
            $model = CActiveRecord::model($userModel)->findByPk($user_id);
            return !(empty($model))?(empty($model->name) ? $model->username : $model->name):$user_id;
            
        }
    }

    static public function createOrderLog($order_id, $user_model, $status, $note = '',$user_id=0)
    {   
        $model = new self;
        $model->order_id = $order_id;
        $model->user_model = $user_model;        
        if(intval($user_id)!=0){
            $model->user_id = $user_id;
        }else{
            $model->user_id = Yii::app()->user->id;
        }        
        $model->status = $status;
        $model->note = $note;
        return $model->save();
    }

}
