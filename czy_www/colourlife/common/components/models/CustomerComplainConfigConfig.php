<?php

/**
 * Created by PhpStorm.
 * User: jayjiang
 * Date: 13-11-27
 * Time: 下午2:24
 */
class CustomerComplainConfigConfig extends Config
{

    public $valueAttributes = array(
        'notAssessmentToVisitTime',
        'fourthSenderLeaderID',
        'fourthMonitoringTime',
        'automaticSupervisoryPositions',
        'secondSenderPosition',
        'threeSenderLeaderID',
        'secondSendTime',
        'threeSendTime'
    );
    public $secondSenderPosition; //第二次发送人职位
    public $threeSenderLeaderID; //第三次发送人领导人id
    public $threeSenderLeaderIDName; //第三次发送人领导人名称
    public $secondSendTime; //第二次发送时间
    public $threeSendTime; //第三次发送时间
    public $automaticSupervisoryPositions; //第一次自动监督职位设置
    public $fourthSenderLeaderID; //第四次发送领导人
    public $fourthSenderLeaderIDName; //第四次发送领导人名称
    public $fourthMonitoringTime; //第四次监控时间
    public $notAssessmentToVisitTime; //不评价转回访时间

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                array(
                    'fourthSenderLeaderIDName, threeSenderLeaderIDName, secondSendTime, threeSendTime,fourthMonitoringTime',
                    'required',
                    'on' => 'update'
                ),
                array(
                    'automaticSupervisoryPositions,notAssessmentToVisitTime,fourthSenderLeaderID,threeSenderLeaderID,secondSenderPosition',
                    'safe',
                    'on' => 'update'
                ),
                array('secondSendTime, threeSendTime,secondSendTime', 'numerical', 'integerOnly' => true, 'on' => 'update')
            )
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            array(
                'automaticSupervisoryPositions' => '第一次自动监督职位',
                'secondSenderPosition' => '第二次发送人职位',
                'threeSenderLeaderID' => '第三次发送人领导人',
                'threeSenderLeaderIDName' => '第三次发送人领导人',
                'fourthSenderLeaderID' => '第四次发送领导人',
                'secondSendTime' => '第二次监控时间',
                'threeSendTime' => '第三次监控时间',
                'fourthSenderLeaderIDName' => '第四次发送领导人名称',
                'fourthMonitoringTime' => '第四次监控时间',
                'notAssessmentToVisitTime' => '不评价转回访时间',
            )
        );
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            array(
                array('name' => 'automaticSupervisoryPositions', 'value' => $this->getAutomaticSupervisoryPositionsName(), 'type' => 'raw'),
                array('name' => 'secondSenderPosition', 'value' => $this->getPositionsName(), 'type' => 'raw'),
                array('name' => 'threeSenderLeaderID', 'value' => $this->getThreeSenderLeaderName(), 'type' => 'raw'),
                array('name' => 'fourthSenderLeaderID', 'value' => $this->getFourthSenderLeaderName(), 'type' => 'raw'),
                array('name' => 'secondSendTime', 'type' => 'timeValue'),
                array('name' => 'threeSendTime', 'type' => 'timeValue'),
                array('name' => 'fourthMonitoringTime', 'type' => 'timeValue'),
                array('name' => 'notAssessmentToVisitTime', 'type' => 'timeValue'),
            )
        );
    }

    protected function afterFind()
    {
        if (!is_array($this->val)) {
            $this->val = array();
        }
        foreach ($this->val as $name => $value) {
            if (!empty($name) && in_array($name, $this->valueAttributes)) {
                $this->$name = $value;
            }
        }
        $this->threeSenderLeaderIDName = $this->getThreeSenderLeaderName();
        $this->fourthSenderLeaderIDName = $this->getfourthSenderLeaderName();
        return parent::afterFind();
    }

    protected function beforeSave()
    {
        $this->val = $this->getAttributes($this->valueAttributes);
        return parent::beforeSave();
    }

    //取得员工
    public function getEmployees()
    {
        return CHtml::listData(Employee::model()->findAll(), 'id', 'name');
    }

    //取得员工职位
    public function getPositions()
    {
        return CHtml::listData(Position::model()->findAll(), 'id', 'name');
    }

    public function getThreeSenderLeaderName()
    {
        $str = '';
        if (is_array($this->threeSenderLeaderID)) {
            foreach ($this->threeSenderLeaderID as $threeSenderLeaderID) {
                $employee = Employee::model()->findByPk(intval($threeSenderLeaderID));
                if (!empty($employee)) {
                    $str .= $employee->name . ' , ';
                }
            }
        }
        return $str;
    }

    public function getThreeSenderLeaderData()
    {
        $arr = array();
        if (is_array($this->threeSenderLeaderID)) {
            foreach ($this->threeSenderLeaderID as $key => $threeSenderLeaderID) {
                $employee = Employee::model()->findByPk(intval($threeSenderLeaderID));
                if (!empty($employee)) {
                    $arr[$key]['id'] = $threeSenderLeaderID;
                    $arr[$key]['name'] = $employee->name;
                }
            }
        }
        return $arr;
    }
    public function getFourthSenderLeaderName()
    {
        $str = '';
        if (is_array($this->fourthSenderLeaderID)) {
            foreach ($this->fourthSenderLeaderID as $fourthSenderLeaderID) {
                $employee = Employee::model()->findByPk(intval($fourthSenderLeaderID));
                if (!empty($employee)) {
                    $str .= $employee->name . ' , ';
                }
            }
        }
        return $str;
    }

    public function getFourthSenderLeaderData()
    {
        $arr = array();
        if (is_array($this->fourthSenderLeaderID)) {
            foreach ($this->fourthSenderLeaderID as $key => $fourthSenderLeaderID) {
                $employee = Employee::model()->findByPk(intval($fourthSenderLeaderID));
                if (!empty($employee)) {
                    $arr[$key]['id'] = $fourthSenderLeaderID;
                    $arr[$key]['name'] = $employee->name;
                }
            }
        }
        return $arr;
    }

    public function getPositionsName()
    {
        $str = '';
        if (is_array($this->secondSenderPosition)) {
            foreach ($this->secondSenderPosition as $secondSenderPosition) {
                $position = Position::model()->findByPk(intval($secondSenderPosition));
                if (!empty($position)) {
                    $str .= $position->name . ' , ';
                }
            }
        }
        return $str;
    }


    public function getAutomaticSupervisoryPositionsName()
    {
        $position = Position::model()->findByPk(intval($this->automaticSupervisoryPositions));
        if (empty($position)) {
            return null;
        } else {
            return $position->name;
        }
    }
}