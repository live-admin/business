<?php

/**
 * This is the model class for table "repair".
 *
 * The followings are the available columns in table 'repair':
 * @property integer $id
 * @property integer $community_id
 * @property integer $customer_id
 * @property integer $accept_employee_id
 * @property integer $complete_employee_id
 * @property string $content
 * @property integer $create_time
 * @property integer $accept_time
 * @property integer $complete_time
 * @property integer $is_deleted
 * @property string $accept_content
 * @property string $complete_content
 * @property integer $category_id
 */
class Routine extends CActiveRecord
{
    public $modelName = '事务';
    public $belongKey = 'community';
    public $userKey = 'customer';
    public $belongModel = 'Community';
    public $userModel = 'Customer';
    public $belongRelationKey = 'community_id';
    public $userRelationKey = 'customer_id';
    public $belongLabel = '小区';
    public $userLabel = '业主';
    public $picture_id;
    public $categorys = 'repairs';
    public $categorysModel = 'RepairCategory';

    private $_status;
    private $_belongName;
    private $_userName;
    private $_acceptName;
    private $_completeName;

    CONST STATUS_PENDING = 0;
    CONST STATUS_ACCEPT = 1;
    CONST STATUS_COMPLETE = 2;
    CONST STATUS_PROCESSED = 3;

    static public $STATIC_NAMES = array(
        self::STATUS_PENDING => '待处理',
        self::STATUS_ACCEPT => '接受处理',
        self::STATUS_COMPLETE => '处理完成',
        self::STATUS_PROCESSED => '已处理',
    );

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('accept_content', 'required', 'on' => 'accept'),
            array('accept_content', 'length', 'min' => 10, 'encoding' => 'utf-8', 'on' => 'accept'),
            array('complete_content', 'required', 'on' => 'complete'),
            array('complete_content', 'length', 'min' => 10, 'encoding' => 'utf-8', 'on' => 'complete'),
            array('content', 'required', 'on' => 'create'),
            array('picture_id', 'checkExistsOrUsed', 'on' => 'create'),
            array('community_id,category_id,customer_id', 'safe', 'on' => 'create'),
            array('content', 'length', 'min' => 10, 'encoding' => 'utf-8', 'on' => 'create'),
            array("{$this->belongKey}Name, {$this->userKey}Name, status, acceptName, completeName", 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        $name = $this->tableName();
        return array(
            $this->belongKey => array(self::BELONGS_TO, $this->belongModel, $this->belongRelationKey),
            $this->userKey => array(self::BELONGS_TO, $this->userModel, $this->userRelationKey),
            'accept_employee' => array(self::BELONGS_TO, 'Employee', 'accept_employee_id'),
            'complete_employee' => array(self::BELONGS_TO, 'Employee', 'complete_employee_id'),
            'pictures' => array(self::HAS_MANY, 'Picture', 'object_id', 'condition' => "`model`='{$name}'"),
            $this->categorys => array(self::BELONGS_TO, $this->categorysModel, 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '标题',
            $this->belongRelationKey => $this->belongLabel,
            $this->userRelationKey => $this->userLabel,
            'accept_employee_id' => '接收处理员工',
            'complete_employee_id' => '处理完成员工',
            'content' => $this->modelName . '内容',
            'create_time' => $this->modelName . '时间',
            'accept_time' => '接收处理时间',
            'complete_time' => '处理完成时间',
            'accept_content' => '接收处理内容',
            'complete_content' => '处理完成内容',
            'category_id' => $this->modelName . '分类',
            'status' => '状态',
            'completeName' => '处理完成员工',
            'acceptName' => '接收处理员工',
            $this->belongKey . 'Name' => $this->belongLabel,
            $this->userKey . 'Name' => $this->userLabel,
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $key = $this->belongKey . 'Name';
        $criteria->compare("`{$this->belongKey}`.`name`", $this->$key, true);
        $criteria->with[] = $this->belongKey;
        $key = $this->userKey . 'Name';
        $criteria->compare("`{$this->userKey}`.`name`", $this->$key, true);
        $criteria->with[] = $this->userKey;
        $criteria->compare("`accept_employee`.`name`", $this->acceptName, true);
        $criteria->with[] = 'accept_employee';
        $criteria->compare("`complete_employee`.`name`", $this->completeName, true);
        $criteria->with[] = 'complete_employee';

        // status
        $this->_compareStatus($criteria, $this->status);

        return new ActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => '`t`.create_time DESC', //设置默认排序是create_time倒序
            ),
            'criteria' => $criteria,
        ));
    }

    public function accept_contactExist($attribute, $params)
    {
        if ($this->strLength($this->accept_content, 'gb2312') <= 0) {
            $this->addError($attribute, '接收备注不能为空！');
            return false;
        }

        return true;
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
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    /**
     * 状态
     * @return int
     */

    public function getStatus()
    {
        if (isset($this->_status))
            return $this->_status;
        if ($this->getScenario() == 'search') {
            $this->_status = '';
        } else {
            $this->_status = self::STATUS_PENDING;
            if (!empty($this->complete_time)) {
                $this->_status = self::STATUS_COMPLETE;
            } else if (!empty($this->accept_time)) {
                $this->_status = self::STATUS_ACCEPT;
            }
        }
        return $this->_status;
    }

    public function setStatus($status)
    {
        if (array_key_exists($status, self::$STATIC_NAMES))
            $this->_status = $status;
        else
            $this->_status = self::STATUS_PENDING;
    }

    public function getStatusName($html = false)
    {
        $return = '';
        $status = $this->getStatus();
        switch ($status) {
            case self::STATUS_PENDING:
                $return .= ($html) ? '<span class="label label-warning">' : '';
                $return .= self::$STATIC_NAMES[$status];
                $return .= ($html) ? '</span>' : '';
                break;
            case self::STATUS_ACCEPT:
                $return .= ($html) ? '<span class="label label-info">' : '';
                $return .= self::$STATIC_NAMES[$status];
                $return .= ($html) ? '</span>' : '';
                break;
            case self::STATUS_COMPLETE:
                $return .= ($html) ? '<span class="label label-success">' : '';
                $return .= self::$STATIC_NAMES[$status];
                $return .= ($html) ? '</span>' : '';
                break;
        }
        return $return;
    }

    public function getStatusNames()
    {
        return self::$STATIC_NAMES;
    }

    private function _compareStatus($criteria, $status)
    {
        switch ($status) {
            case '': // 必须处理空字符串
                break;
            case self::STATUS_PENDING:
                $criteria->compare('accept_time', '0');
                $criteria->compare('complete_time', '0');
                break;
            case self::STATUS_ACCEPT:
                $criteria->compare('accept_time', '>0');
                $criteria->compare('complete_time', '0');
                break;
            case self::STATUS_COMPLETE:
                $criteria->compare('accept_time', '>0');
                $criteria->compare('complete_time', '>0');
                break;
            case self::STATUS_PROCESSED:
                $criteria->compare('accept_time', '>0');
                break;
        }
    }

    public function __get($name)
    {
        if ($name == $this->belongKey . 'Name') {
            return $this->getBelongName();
        } else if ($name == $this->userKey . 'Name') {
            return $this->getUserName();
        } else {
            return parent::__get($name);
        }
    }


    public function __set($name, $value)
    {
        if ($name == $this->belongKey . 'Name') {
            return $this->setBelongName($value);
        } else if ($name == $this->userKey . 'Name') {
            return $this->setUserName($value);
        } else {
            return parent::__set($name, $value);
        }
    }

    /**
     * 业主名称
     * @return string
     */
    public function getUserName()
    {
        if (isset($this->_userName))
            return $this->_userName;
        $key = $this->userKey;
        if (empty($this->$key))
            return '';
        $user = $this->$key;
        return $user->name;
    }

    public function setUserName($name)
    {
        $this->_userName = $name;
    }

    public function getBelongName()
    {
        if (isset($this->_belongName))
            return $this->_belongName;
        $key = $this->belongKey;
        if (empty($this->$key))
            return '';
        $user = $this->$key;
        return $user->name;
    }

    public function setBelongName($name)
    {
        $this->_belongName = $name;
    }

    /**
     * 处理人名称
     * @return string
     */
    public function getAcceptName()
    {
        if (isset($this->_acceptName))
            return $this->_acceptName;
        return empty($this->accept_employee) ? '' : $this->accept_employee->name;
    }

    public function setAcceptName($name)
    {
        $this->_acceptName = $name;
    }

    /**
     * 完成人名称
     * @return string
     */
    public function getCompleteName()
    {
        if (isset($this->_completeName))
            return $this->_completeName;
        return empty($this->complete_employee) ? '' : $this->complete_employee->name;
    }

    public function setCompleteName($name)
    {
        $this->_completeName = $name;
    }

    private $_name;

    public function getName()
    {
        if (isset($this->_name))
            return $this->_name;
        $this->_name = str_replace(
            array('{belong}', '{user}', '{time}', '{model}'),
            array($this->belongName, $this->userName, Yii::app()->format->formatLocaleDatetime($this->create_time), $this->modelName),
            '【{belong}】{user}在{time}的{model}');
        return $this->_name;
    }

    public function accept()
    {
        $this->accept_time = time();
        $this->accept_employee_id = Yii::app()->user->id;
        return $this->save();
    }

    public function complete()
    {
        $this->complete_time = time();
        $this->complete_employee_id = Yii::app()->user->id;
        return $this->save();
    }


    public function afterSave()
    {
        parent::afterSave();
        if ($this->isNewRecord) {
            if (!empty($this->picture_id) && isset($this->picture_id)) {
                $criteria = new CDbCriteria;
                if (is_array($this->picture_id)) {
                    $criteria->addInCondition('id', $this->picture_id);
                } else {
                    $criteria->compare('id', $this->picture_id);
                }

                $pics = Picture::model()->findAll($criteria);

                foreach ($pics as $picture) {
                    if (isset($picture)) {
                        $picture->updateBelongTo($this->tableName(), $this->id);
                    }
                }

            }
        }
    }

    public function getUrl()
    {
        if (isset($this->pictures)) {
            //  $result='';
            //  foreach($this->pictures as $arr)
            // {
            //    $result=$result.';'.$arr->url;
            // }
            //return substr($result,1);
            return $this->pictures;
        } else {
            return '';
        }
    }

    /**
     * 检查picture是否存在或是否被使用
     * @param $attribute
     * @param $params
     * @return bool
     */
    public function checkExistsOrUsed($attribute, $params)
    {
        if (!empty($this->picture_id) && !isset($this->picture_id)) {
            if (is_array($this->picture_id)) {
                foreach ($this->picture_id as $id) {
                    if (!empty($id)) {
                        $picture = Picture::model()->findByPk($id);
                        if (isset($picture)) {
                            $this->addError($attribute, $this->modelName . '图片不存在');
                            break;
                        } else {
                            if (!empty($picture->object_id)) {
                                $this->addError($attribute, $this->modelName . '图片已使用');
                                break;
                            }
                        }
                    }
                }
            } else {
                $picture = Picture::model()->findByPk($this->picture_id);
                if (isset($picture)) {
                    $this->addError($attribute, $this->modelName . '图片不存在');
                } else {
                    if (!empty($picture->object_id)) {
                        $this->addError($attribute, $this->modelName . '图片已使用');
                    }
                }
            }
        }
    }

    public function getCategoryName()
    {
        $name = $this->categorys;
        return isset($this->$name) ? $this->$name->name : '';
    }

    public function getUserMobile()
    {
        $name = $this->userKey;
        return isset($this->$name) ? $this->$name->mobile : '';
    }

    public function getUserAddress()
    {
        $name = $this->userKey;
        return isset($this->$name) ? (isset($this->$name->community) ? $this->$name->community->name . (isset($this->$name->build) ? $this->$name->build->name : '') . (empty($this->$name->room) ? '' : $this->$name->room) : '') : '';

    }

    public function getAcceptHtml()
    {
        if (!empty($this->accept_employee)) {
            $branchName = $this->accept_employee->branchName;
            return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '组织架构:' . $branchName . '电话:' . $this->accept_employee->mobile), $this->acceptName);
        } else
            return '';
    }

    public function getCompleteHtml()
    {
        if (!empty($this->complete_employee)) {
            $branchName = $this->complete_employee->branchName;
            return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '组织架构:' . $branchName . '电话:' . $this->complete_employee->mobile), $this->completeName);
        } else
            return '';
    }

}
