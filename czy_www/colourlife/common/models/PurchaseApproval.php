<?php

/**
 * PayForm class.
 * PayForm is the data structure for keeping
 */
Yii::import('common.api.ColorCloudApi');

class PurchaseApproval extends CFormModel
{
    public $order_sn = array();
    public $body;
    public $title;
    public $cid;
    public $processid;
    public $createUser;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('order_sn,body,title,cid,processid', 'required', 'on' => 'createApproval'),
            // array('amount', 'numerical', 'integerOnly' => true),
            //array('order_sn,body,title,cid,processid', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'cid' => '分类',
            'body' => '正文',
            'title' => '主题',
            'processid' => '审批流程',
        );
    }

    /**创建审批记录
     * @return array|bool|mixed|null
     */
    public function createApproval()
    {
        $return = true;
        $user_id = Yii::app()->user->id;
        // var_dump($user_id);die;
        //更新订单状态,添加记录到数据库
        $transaction = Yii::app()->db->beginTransaction(); //创建事务
        try {
            //创建审批流程
            // var_dump(1111);die;
            $approval_id = $this->approverCreate();
            // var_dump(1111);die;
            if (!$approval_id) //创建失败
            return false;

            if (is_array($this->order_sn) && count($this->order_sn) > 0) {
                foreach ($this->order_sn as $sn) {
                    $order = SN::findContentBySN($sn);
                    if ($order) {
                        //判断订单是否有效订单
                        if ($order->employee_id != $user_id && $order->status != Item::ORDER_AWAITING_PAYMENT)
                            $return = false;

                        //更新订单状态,添加记录到数据库
                        if (!$order->updateStatusApprovalStart() || !$order->updateApprovalID($approval_id))
                            $return = false;
                    }
                }
            }
            if ($return) {
                $transaction->commit();
            } else {
                $transaction->rollback();
            }
        } catch (Exception $e) {
            $transaction->rollback();
        }

        return $return;
    }

    private function getOAuserName()
    {
        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        return empty($employee) ? '' : $employee->oa_username;
    }

    //获得审批分类
    public function getApproverCate()
    {
        $colure = ColorCloudApi::getInstance();
        $return = $colure->callGetExamineClass();
        if ($return['verification'] && $return['total'] > 0) {
            return $return['data'];
        }
        return array();
    }

    //审批创建
    public function approverCreate()
    {
        $colure = ColorCloudApi::getInstance();

        $orderInfo = implode('|', $this->order_sn);
        //var_dump($orderInfo);die;//6010000150203171802453
        $this->body =strip_tags($this->body);
        $this->title =strip_tags($this->title);
        // var_dump($this->title);die;
        $return = $colure->callGetExamineCreate($this->cid, $this->processid, $this->title, $this->body,
            $orderInfo, $this->getOAuserName());
        var_dump($return);die;
        if ($return['verification']) {
            return $return['total'];
        } else
            return false;
    }

    //获得审批人
    public function getApprovalProcess($cid)
    {
        $colure = ColorCloudApi::getInstance();
        $return = $colure->callGetExamineProcess($cid);
        $arr=array();
        $n=0;
        if ($return['verification'] && $return['total'] > 0) {
            foreach($return['data'] as $val){
                $arr[$n]['id']= $val['id'];
                $arr[$n]['name']= $val['name'];
                $arr[$n]['examusers']=$val['examusers'];
                $n++;
            }

        }
        return $arr;
    }

}
