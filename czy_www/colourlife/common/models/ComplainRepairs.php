<?php

/**
 * This is the model class for table "complain_repairs".
 *
 * The followings are the available columns in table 'complain_repairs':
 * @property integer $id
 * @property integer $type
 * @property integer $category_id
 * @property integer $user_id
 * @property integer $community_id
 * @property string $content
 * @property integer $create_time
 * @property integer $accept_employee_id
 * @property integer $accept_time
 * @property string $accept_content
 * @property integer $final_evaluate_state
 * @property integer $final_evaluate_note
 * @property integer $final_evaluate_time
 * @property integer $state
 * @property integer $model
 * @property integer $user_is_visible
 * @property integer $is_deleted
 * @property integer $apply_close
 * @property integer $suggest_colse
 */
class ComplainRepairs extends CActiveRecord
{
    static $_state_list = array(
        Item::COMPLAIN_REPAIRS_AWAITING_HANDLE => "待处理",
        Item::COMPLAIN_REPAIRS_AWAITING_RECEIVE => "待接收",
        Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING => "处理中",
        Item::COMPLAIN_REPAIRS_HANDLE_END => "已处理",
        Item::COMPLAIN_REPAIRS_CONFIRM_END => "已确认",
        Item::COMPLAIN_REPAIRS_POOR_VISIT => "回访",
        Item::COMPLAIN_REPAIRS_APPLY_COLOSE => "申请关闭",
        Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE => "400申请关闭结束",
        Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE => "已经关闭",
        Item::COMPLAIN_REPAIRS_PUBLIC_NONE => "指定小区主任",
    );

	public $noComments = array(
        '0' => '重新处理',
        '1' => '重新指派',
		'2' => '申请关闭',
	);

	public $userComment = array(
		'0' => '未评论',
		//'1' => '满意',
		//'-1' => '不满意',
	);

    public static $_apply_close = array(
        '0' => '否',
        '1' => '是',
    );

    public static $_suggest_colse = array(
        '0' => '否',
        '1' => '是',
    );


    public $oldState;

	/**
     * @var string 模型名
     */
    public $modelName = '投诉报修';
    public $suggestion; //处理意见
    public $agree; //处理结果，同意为1，不同意为0
    public $is_connect; //是否接通
    public $telphone; //回访电话
    public $visit_time; //回访时间
    public $connect_time; //接通时间
    public $score; //评分
    public $note; //备注
    public $customer_tel;
    public $staff_name;
    public $staff_tel;

    public $customer_name;
    public $username;
    public $startTime;
    public $endTime;
    //以下字段仅供搜索用
    public $communityIds = array(); //小区
    public $branch_id;
    public $region;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ComplainRepairs the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'complain_repairs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, category_id,customer_name,customer_tel, community_id,content', 'required', "on" => "create"),
            array('low', 'safe', 'on' => 'create'),
            array('accept_content', "required", "on" => "update"),
        	array('apply_content','required','on'=>'applyClose'),
            array('connect_time', 'numerical', 'integerOnly' => true),
            array('type, category_id, user_id, community_id, create_time, accept_employee_id, accept_time, final_evaluate_state, final_evaluate_time, state, model, user_is_visible, is_deleted', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('branch_id,execute,confirm,apply_content,apply_close,region,branch_id,username,startTime,endTime,communityIds,id, type, category_id, user_id,customer_name,customer_tel, community_id, content,
            create_time, accept_employee_id, accept_time, accept_content, final_evaluate_state, final_evaluate_note,
            final_evaluate_time, state, model, user_is_visible, is_deleted, apply_close, suggest_colse', 'safe', 'on' => 'search,report_search,report_searchByQuality'),
            //			ICE 搜索数据
            array('province_id,city_id,district_id', 'safe'),
        );
    }

    //评价信息，0=>不满意，1=>满意
    public function getEvaluate()
    {
        return $this->userComment;
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'category' => array(self::BELONGS_TO, 'ComplainCategory', 'category_id'),
            'repair_category' => array(self::BELONGS_TO, 'RepairCategory', 'category_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
            'execute_employee' => array(self::BELONGS_TO, 'Employee', 'execute'),
            'accept_employee' => array(self::BELONGS_TO, 'Employee', 'accept_employee_id'),
            'complain_repairs_visit' => array(self::BELONGS_TO, 'ComplainRepairsVisit', 'accept_employee_id'),
            'execAttred' => array(self::HAS_MANY, 'ComplainRepairsHandling', 'complain_repairs_id', 'condition' => "execAttred.type ='0'"),
            'superAttred' => array(self::HAS_MANY, 'ComplainRepairsHandling', 'complain_repairs_id', 'condition' => "superAttred.type ='1'"),
            'confirmed' => array(self::HAS_MANY, 'ComplainRepairsHandling', 'complain_repairs_id', 'condition' => "confirmed.type ='2'"),
            'picture' => array(self::HAS_MANY, 'Picture', 'object_id', 'condition' => "picture.model ='OwnerComplain'"),
            'logs' => array(self::HAS_MANY, 'ComplainRepairsLog', 'complain_repairs_id'),
            'customer' => array(self::BELONGS_TO, 'Customer', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '投诉工单',
            'type' => '类型',
            'category_id' => '类别',
            'user_id' => '投诉人ID',
            'customer_name' => '姓名',
            'customer_tel' => '电话',
            'community_id' => '小区',
            'content' => '内容',
            'create_time' => '创建时间',
            'accept_employee_id' => '最后处理人',
            'accept_time' => '最后处理时间',
            'accept_content' => '原因',
            'final_evaluate_state' => '用户最终评价',
            'final_evaluate_note' => '用户最终评价内容',
            'final_evaluate_time' => '用户最终评价时间',
            'state' => '状态',
            'model' => '模型',
            'user_is_visible' => '用户是否可见',
            'is_deleted' => '是否删除',
            'source' => '评分',
            'suggestion' => '处理意见',
            'agree' => '处理结果',
            'is_connect' => '是否接通',
            'visit_time' => '回访时间',
            'connect_time' => '接通时间',
            'score' => '评分',

            'username' => '用户名',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'communityIds' => '小区',
            'region' => '地区',
            'branch_id' => '部门',
        	'customerName'=>'投诉人',
        	'customerTel'=>'联系电话',
        	'create_time' => '投诉时间',
        	'communityInBranch' => '投诉小区所属部门',
        	'communityDetail' => '业主投诉小区',
			'noComments' => '处理方案',
			'note' => '评价内容',
            'apply_close' => '申请关闭',
            'suggest_colse' => '建议关闭',
            'low' => '是否低分投诉',
            'work_no' => '400工牌号',
            'visit_work_no' => '400回访工号',
        );
    }

    //get姓名
    public function getCustomerName()
    {
        if($this->customer_name!='')
        {
            $username = empty($this->customer)?'':'('.$this->customer->username.')';
            return $this->customer_name.$username;
        }else{
            return '';
        }

    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('customer_name', $this->customer_name, true);
        $criteria->compare('customer_tel', $this->customer_tel, true);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('accept_employee_id', $this->accept_employee_id);
        $criteria->compare('accept_time', $this->accept_time);
        $criteria->compare('accept_content', $this->accept_content, true);
        $criteria->compare('final_evaluate_state', $this->final_evaluate_state);
        $criteria->compare('final_evaluate_note', $this->final_evaluate_note);
        $criteria->compare('final_evaluate_time', $this->final_evaluate_time);
        $criteria->compare('state', $this->state);
        $criteria->compare('model', $this->model);
        $criteria->compare('user_is_visible', $this->user_is_visible);
        $criteria->compare("execute", $this->execute);
        $criteria->compare('confirm', $this->confirm);
        $criteria->compare('apply_close', $this->apply_close);
        $criteria->compare('suggest_colse', $this->suggest_colse);
        $criteria->compare('work_no', $this->work_no);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.id desc',
            )
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
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    protected function afterSave()
    {
        if($this->getIsNewRecord()){//新建投诉报修时赠送积分
            $key = '';
            switch($this->type)
            {
                case Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER://业主投诉
                    $key = 'owner_complain';
                    break;
                case Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE://员工投诉
                    //$key = 'staff_complain';
                    break;
                case Item::COMPLAIN_REPAIRS_TYPE_PERSON://个人报修
                    $key = 'personal_repairs';
                    break;
                case Item::COMPLAIN_REPAIRS_TYPE_PUBLIC://公共报修
                    $key = 'public_repairs';
                    break;
            }
            if(!empty($key)){
                Customer::model()->changeCredit($key);
            }
        }
    }

    public function getStateNames()
    {
		$state = array();
		$list = array(
            Item::COMPLAIN_REPAIRS_AWAITING_HANDLE,
            Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING,
            Item::COMPLAIN_REPAIRS_HANDLE_END,
            Item::COMPLAIN_REPAIRS_CONFIRM_END,
			Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE,
        );
		foreach(self::$_state_list as $key => $val)
		{
			if(in_array($key,$list)){
				$state[$key] = $val;
			}
		}
        return CMap::mergeArray(array('' => '全部'), $state);
    }

    public function getStatusName($html = true)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= empty(self::$_state_list[$this->state])?"":self::$_state_list[$this->state];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    public function getStatusNameByReport(){
        return empty(self::$_state_list[$this->state])?"":self::$_state_list[$this->state];
    }

    public function getCategoryName()
    {
        return empty($this->category) ? '' : $this->category->name;
    }

    public function getRepairCategoryName()
    {
        return empty($this->repair_category) ? '' : $this->repair_category->name;
    }
//  ICE接入
    public function getCommunityName()
    {
//        return empty($this->community) ? '' : $this->community->name;
//  ICE接入
        if(!empty($this->community_id)){
            $community = ICECommunity::model()->findByPk($this->community_id);
            if(!empty($community)){
               return  $community['name'];
            }
        }

        return '';
    }

    public function getSource()
    {
        return empty($this->complain_repairs_visit) ? 0 : $this->complain_repairs_visit->score;
    }

    public function getAcceptEmployeeName()
    {
//        return empty($this->accept_employee) ? '' : $this->accept_employee->name;
//	    ICE接入
        if(!empty($this->accept_employee_id)){
            $employee = ICEEmployee::model()->findbypk($this->accept_employee_id);
            if(!empty($employee['name'])){
                return $employee['name'];
            }

        }

        return '';
    }

    public function getAcceptEmployeeMobile()
    {
//        return empty($this->accept_employee) ? '' : $this->accept_employee->mobile ;
//	    ICE接入
        if(!empty($this->accept_employee_id)){
            $employee = ICEEmployee::model()->findbypk($this->accept_employee_id);
            if(!empty($employee->mobile)){
                return $employee->mobile;
            }

        }

        return '';
    }

    public function getNotExecAttrEmployee()
    {
        $criteria = new CDbCriteria;
        if (!empty($this->execAttred))
            $criteria->addNotInCondition('id', array_map(function ($hand) {
                return $hand->employee_id;
            }, $this->execAttred));

        return CHtml::listData(Employee::model()->findAll($criteria), 'id', 'name');
    }

    //获取投诉报修详情当前执行人的名字跟电话
    public function getNameMobile($employee_id){
        $model=Employee::model()->findByPk($employee_id);
        $info=array();
        if(!empty($model)){
            $info['name']=$model->name;
            $mobile=$model->mobile;
            $info['mobile']=empty($mobile)?"":  $mobile ;

        }
        return $info;
    }
    public function getAllNotExecAttrEmployee()
    {
        $criteria = new CDbCriteria;
        if (!empty($this->execAttred))
            $criteria->addNotInCondition('id', array_map(function ($hand) {
                return $hand->employee_id;
            }, $this->execAttred));
        return Employee::model()->enabled()->findAll();
    }

    public function getNotSuperAttrEmployee()
    {
        $criteria = new CDbCriteria;
        if (!empty($this->superAttred))
            $criteria->addNotInCondition('id', array_map(function ($hand) {
                return $hand->employee_id;
            }, $this->superAttred));

        return CHtml::listData(Employee::model()->findAll($criteria), 'id', 'name');
    }


    public function getExecAttrEmployee()
    {
        $str = '';
        $criteria = new CDbCriteria;
        if (!empty($this->execAttred)) {
            $criteria->addInCondition('id', array_map(function ($hand) {
                return $hand->employee_id;
            }, $this->execAttred));

            $data = Employee::model()->findAll($criteria);
            if (count($data) > 0) {
                foreach ($data as $employee) {
                    $str .= $employee->name . ' , ';
                }
            }
        }

        return $str;
    }

    public function getSuperAttrEmployee()
{
    $str = '';
    $criteria = new CDbCriteria;
    if (!empty($this->superAttred)) {
        $criteria->addInCondition('id', array_map(function ($hand) {
            return $hand->employee_id;
        }, $this->superAttred));

        $data = Employee::model()->findAll($criteria);
        if (count($data) > 0) {
            foreach ($data as $employee) {
                $str .= $employee->name . ' , ';
            }
        }
    }

    return $str;
}

    public function getConfirm()
    {
        /*$data = ComplainRepairsHandling::model()->findByAttributes(array('complain_repairs_id' => $this->id, 'type' => 2));
        */
        return empty($this->confirmed) ? '' : $this->confirmed[0]->employee_id;
    }

    public function getOpenStatus()
    {
        if ($this->state == Item::COMPLAIN_REPAIRS_CONFIRM_END ||
            $this->state == Item::COMPLAIN_REPAIRS_APPLY_COLOSE ||
            $this->state == Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE ||
            $this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE
        )
            return false;
        else
            return true;

    }


    public function getCloseState()
    {
        if ($this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE || $this->state == Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE) {
            return false;
        } else {
            return true;
        }
    }

    public function checkIsNormal()
    {
        if ($this->state == Item::COMPLAIN_REPAIRS_CONFIRM_END ||
            $this->state == Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE ||
            $this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE
        )
            return false;
        else
            return true;
    }

    //获取查看页面的片，返回数组
    public function getPic()
    {
        $pics = array();
        if (!empty($this->picture))
            $pics = array_map(function ($pic) {
                return $pic->portraitUrl;
            }, $this->picture);
        return $pics;
    }

    public function getUserAddress()
    {
        $customer = Customer::model()->findByPk($this->user_id);
//      返回的UserAddress小区名字接入ice
        if(!empty($customer)){
            $community = ICECommunity::model()->ICEfindByPk($customer->community_id);
            if(!empty($community)){
                return isset($customer) ? (isset($community['name']) ? $community['name'] . (isset($customer->build) ? $customer->build->name : '') . (empty($customer->room) ? '' : $customer->room) : '') : '';
            }
        }
//        return isset($customer) ? (isset($customer->community) ? $customer->community->name . (isset($customer->build) ? $customer->build->name : '') . (empty($customer->room) ? '' : $customer->room) : '') : '';

    }


    /**
     * @param $isNow :是否是当前执行人,default 0
     * @return bool
     * @throws CHttpException
     * 检测当前用户是否是执行人,如果isNow=true,判断用户是否是当前执行人
     */
    public function checkIsReceive($isNow = false)
    {
        if($this->execute==Yii::app()->user->id)
            return true;

        return false;
    }

    /**
     * @return int/null
     * 或得投诉的当前执行人。没有则返回null
     */
    public function getNowExecutorsID()
    {
       return  $this->execute;
    }

    //检测我是否是确认人
    public function checkIsConfirm()
    {
       if($this->confirm == Yii::app()->user->id){
           return true;
       }else{
           return false;
       }
    }

    /**
     * @return bool
     * @throws CHttpException
     * 检测当前用户是否是监督人
     */
    public function checkIsSuper()
    {
        $attr = array(
            'complain_repairs_id' => $this->id,
            'type' => 1, //监督人
        );
        //查询该投诉的所有监督人
        $execList = ComplainRepairsHandling::model()->findAllByAttributes($attr);
        if (empty($execList)) {
            //throw new CHttpException(403, '没有权限！');
            return false;
        }
        $idList = array();
        $user_id = Yii::app()->user->id;
        foreach ($execList as $exec) {
            array_push($idList, $exec->employee_id);
        }
        if (in_array($user_id, $idList)) {
            return true;
        } else {
            return false;
        }
    }

    //检测我是否能处理
    public function checkICanRedo()
    {
        //是执行人。且无当前执行人或当前执行人等于自己
        if ($this->checkIsReceive()) {
            $id = $this->getNowExecutorsID();
            if (empty($id) || Yii::app()->user->id == $id) {
                if ($this->state == Item::COMPLAIN_REPAIRS_AWAITING_RECEIVE ||
                    $this->state == Item::COMPLAIN_REPAIRS_PUBLIC_NONE ||
                    $this->state == Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING
                ) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //检测我是否能接收
    public function checkICanReceive()
    {
        if ($this->checkIsReceive()) {
            if ($this->state == Item::COMPLAIN_REPAIRS_PUBLIC_NONE ||
                $this->state == Item::COMPLAIN_REPAIRS_AWAITING_RECEIVE
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //检测我是否能处理
    public function checkICanHandle()
    {
        if ($this->checkIsReceive(true)) {
            if ($this->state == Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    //获得投诉人所在的地址，如果是员工投诉则获得员工所属部门
    public function getReportUserAddress()
    {
        $user_id = $this->user_id;
        if (empty($user_id)) {
            return "获取用户信息失败！400创单？";
        }
        if ($this->type == Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE) { //如果是员工投诉
            $employee = Employee::model()->findByPk($user_id);
            return $employee->getBranchName();
        } else {
            $customer = Customer::model()->findByPk($user_id);
            if (empty($customer)) {
                return "获取业主信息失败！";
            }
            $community_id = $customer->community_id;
            $community = Community::model()->findByPk($community_id);
            if (empty($community_id) || empty($community)) {
                return "获取业主所在小区信息失败！";
            }
            $addressList = $community->getMyParentRegionNames();
            $address = implode("-", $addressList) . "-" . $community->name;
            $address .= "-" . (empty($customer->build) ? "" : $customer->build->name);
            $address .= "-" . $customer->room;
            return $address;
        }
    }

    //获得投诉小区所属的部门
    public function getCommunityInBranch()
    {
        $community_id = $this->community_id;
        if (empty($community_id)) {
            return "获取小区信息失败！";
        }
        $community = Community::model()->findByPk($community_id);
        if (empty($community)) {
            return "获取小区所属部门失败！";
        }
	    return $community->ICEGetCommunityBranchesNames();
        return Branch::getMyParentBranchName($community->branch_id).'-'.$community->branchName;
    }

    //获得投诉小区所在的地区
    public function getCommunityDetail()
    {
        $community_id = $this->community_id;
        if (empty($community_id)) {
            return "获取小区信息失败！";
        }
        $community = Community::model()->findByPk($community_id);
        if (empty($community)) {
            return "获取小区所属部门失败！";
        }
        $addressList = $community->getMyParentRegionNames();
        $address = implode("-", $addressList) . "-" . $community->name;
        return $address;
    }

    public function getFrontStart()
    {

        if ($this->state == Item::COMPLAIN_REPAIRS_AWAITING_HANDLE || $this->state == Item::COMPLAIN_REPAIRS_PUBLIC_NONE) {
            return Item::COMPLAIN_REPARS_START; //
        } else if ($this->state == Item::COMPLAIN_REPAIRS_CONFIRM_END) {
            return Item::COMPLAIN_REPARS_EVALUATION;
        } else if ($this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE || $this->state == Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE) {
            return Item::CONPLAIN_REPARS_COMPLETE;
        } else {
            return Item::COMPLAIN_REPARS_ING;
        }
    }

    public function getLogConfig($string)
    {
        $configList = Yii::app()->config->$string;
        if (empty($configList)) {
            return array("create" => "", "execution" => "","two_execution"=>"", "finish" => "","comment_end"=>"","stoped"=>"");
        } else {
            return $configList;
        }
    }

    public function getCommunityTreeData()
    {
        $branch_id = 1; //默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
        $branch = Branch::model()->findByPk($branch_id);
        return $branch->getRegionCommunityRelation($this->id, '', true);
    }

    public function getPics($type = "", $id = "")
    {
        $pic = array();
        if ($type == "" || $id == "") {
            return $pic;
        } else {
            $CDbCriteria = new CDbCriteria();
            $CDbCriteria->compare("model", $type);
            $CDbCriteria->compare("object_id", $id);
            $picture = Picture::model()->findAll($CDbCriteria);
            if (empty($picture)) {
                return $pic;
            } else {

                foreach ($picture as $val) {
                    $pic[] = F::getUploadsUrl("/images/" . $val->url);
                }
                return $pic;
            }
        }
    }

    //根据不同的类型得到图片
    public function getTypePics($modelName)
    {
        $pic = array();
        if ($modelName == "")
            return $pic;

        $CDbCriteria = new CDbCriteria();
        $CDbCriteria->compare("model", $modelName);
        $CDbCriteria->compare("object_id", $this->id);
        $picture = Picture::model()->findAll($CDbCriteria);
        if (empty($picture)) {
            return $pic;
        } else {

            foreach ($picture as $val) {
                $pic[] = Yii::app()->imageFile->getUrl($val->url);
            }
            return $pic;
        }
    }

    /**
     * @param $status 传入的状态
     * @return array
     * 根据投诉报修当前状态获得当前负责人
     */
    public function getResponsibleByStatus(){
        $returnArr = array();
        switch($this->state){
            case Item::COMPLAIN_REPAIRS_AWAITING_HANDLE://待处理，只能400处理
                $returnArr[0] = Item::COMPLAIN_REPAIRS_DETAIL_SERVICE;
                break;
            case Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING://已接受，读取当前执行人
                $returnArr[0] = ($this->execute==0)?Item::COMPLAIN_REPAIRS_DETAIL_SERVICE:$this->execute;
                break;
            case Item::COMPLAIN_REPAIRS_HANDLE_END://待确认，读取所有确认人
                $returnArr[0] = $this->confirm;
                break;
            case Item::COMPLAIN_REPAIRS_CONFIRM_END://确认完成，显示待业主评价
                $returnArr[0] = Item::COMPLAIN_REPAIRS_DETAIL_CUSTOMER;
                break;

            case Item::COMPLAIN_REPAIRS_PUBLIC_NONE://公共报修指定小区主任，也是读取所有执行人
                $returnArr[0] = $this->execute;
                break;

            default:
                $returnArr[0] = Item::COMPLAIN_REPAIRS_DETAIL_UNKNOWN;
        }
        return $returnArr;
    }

    public function getEmployees($arr)
    {
        $employeeStr = '';
        if (!empty($arr)) {
            foreach ($arr as $key => $val) {
                $employee = Employee::model()->findbypk($val);
                if (!empty($employee))
                    $employeeStr .= $employee->name.',';
            }
        }
        return $employeeStr;
    }

    public function getSuperEmployee()
    {
        $criteria = new CDbCriteria;
        if (!empty($this->superAttred))
            $criteria->addNotInCondition('id', array_map(function ($hand) {
                return $hand->employee_id;
            }, $this->superAttred));

        return CHtml::listData(Employee::model()->findAll($criteria), 'id', 'name');
    }

    //获取执行人
    public function getExecuteEmployee()
    {
        $employee = Employee::model()->findByPk($this->execute);
        if(empty($employee))
            return null;

        $arr[0]['id'] = $this->execute;
        $arr[0]['name'] = empty($employee)?'':$employee->name;
        return $arr;
    }

    //获取确认人
    public function getConfirmEmployee()
    {
        $employee = Employee::model()->findByPk($this->confirm);
        if(empty($employee))
            return null;

        $arr[0]['id'] = $this->confirm;
        $arr[0]['name'] = empty($employee)?'':$employee->name;
        return $arr;
    }

    public function getFinalEvaluateStateName(){
        if(!empty($this->userComment[$this->final_evaluate_state])){
            return $this->userComment[$this->final_evaluate_state];
        }
        return '';
    }


    //获取执行人
    public function getExecName()
    {
        $employee = Employee::model()->findByPk($this->execute);
        return empty($employee)?'':$employee->name;
    }

    //获取确认人
    public function getConfirmName()
    {
        $employee = Employee::model()->findByPk($this->confirm);
        return empty($employee)?'':$employee->name;
    }

    //根据状态计算投诉报修除个人报修的条数，只包括执行过，监督，监督过
    /*
     * $state 状态
     * $type 类型，0=业主投诉，1=员工投诉，3=公共报修
     * $handlingType
     * */
    public  function getComplainRepairsNum($handlingType=NULL,$type=NULL,$state=array()){
        $criteria=new CDbCriteria();
        $criteria->join="LEFT JOIN complain_repairs_handling crh ON `t`.`id` = crh.`complain_repairs_id`";
        if(in_array($type,array(0,1,3))){
            $criteria->compare("`t`.type",$type);
        }
        $criteria->compare("`crh`.type",$handlingType);
        $criteria->compare("`crh`.employee_id",Yii::app()->user->id);
        $criteria->addCondition("IF (`t`.type=1,`t`.branch_id<>0,1=1)");
        $criteria->addInCondition("`t`.state",$state);
        $criteria->group="`t`.id";
        $model=ComplainRepairs::model()->findAll($criteria);
        $count=count($model);
        return $count;
    }

    //根据状态计算投诉报修除个人报修我执行的条数
    /*
     * $state 状态
     * $type 类型，0=业主投诉，1=员工投诉，3=公共报修
     * $handlingType
     * */
    public  function getComplainRepairsNumber($type=NULL,$state=array()){
        $criteria=new CDbCriteria();
        if(in_array($type,array(0,1,3))){
            $criteria->compare("`t`.type",$type);
        }
        $criteria->addCondition("IF (`t`.type=1,`t`.branch_id<>0,1=1) AND IF(`t`.state=2 ,`t`.`execute` =".Yii::app()->user->id.",1=1) AND  IF(`t`.state=3,`t`.`confirm` =".Yii::app()->user->id.",1=1)");
        $criteria->addInCondition("`t`.state",$state);
        $criteria->group="`t`.id";
        $model=ComplainRepairs::model()->findAll($criteria);
        $count=count($model);
        return $count;
    }


    public function getApplyClose(){
    	return empty($this->apply_close)?"未申请关闭":"已申请关闭";
    }

    public function getSuggestColse(){
    	return empty($this->suggest_colse)?"未建议闭关":"已建议关闭";
    }


    //根据状态计算投诉报修除个人报修的列表，只包括执行过，监督，监督过
    /*
     * $state 状态
     * $type 类型，0=业主投诉，1=员工投诉，3=公共报修
     * $handlingType 0=执行过的，1=监督，2=监督过的
     * */
    public  function getComplainRepairsList($handlingType=NULL,$type=NULL,$state=array(),$page=1,$pagesize=10){
        $criteria=new CDbCriteria();
        $criteria->join="LEFT JOIN complain_repairs_handling crh ON `t`.`id` = crh.`complain_repairs_id`";
        if(in_array($type,array(0,1,3))){
            $criteria->compare("`t`.type",$type);
        }
        $criteria->compare("`crh`.type",$handlingType);
        $criteria->compare("`crh`.employee_id",Yii::app()->user->id);
        $criteria->addCondition("IF (`t`.type=1,`t`.branch_id<>0,1=1)");
        $criteria->addInCondition("`t`.state",$state);
        $criteria->group="`t`.id";
        $page_count=(intval($page)-1)*$pagesize;
        $criteria->limit=$pagesize;
        $criteria->offset=$page_count;
        $model=ComplainRepairs::model()->findAll($criteria);
        return $model;
    }


    /**
     * @return array
     * 获得用户权限范围内的事业部、片区、小区数目
     */
    public function getDataCountByUser(){
        //获得用户的所有管辖部门ID
        $branch_Ids = $this->getBranchIdsByAuth();
        $community_num = 0;//小区总数
        $areaList = array();//片区id集合
        $divisionList = array();//事业部id集合

        //获得每个管辖部门下的小区
        foreach($branch_Ids as $branch_id){
            $branch = Branch::model()->findByPk($branch_id);
            $communityList = $branch->getBranchAllData('Community');
            $community_num += count($communityList);
            //获取片区
            foreach($communityList as $community){
                $id = $community ->branch_id;
                //通过小区获得小区的管辖部门
                $branchObj = Branch::model()->findByPk($id);
                //获得该小区管辖部门的上一级部门(片区)
                $parent = empty($branchObj->parent)?null:$branchObj->parent;
                //上级部门存在，那么片区存在，保存片区id
                if($parent!=null){
                    $areaList[] = $parent->id;
                    $areaList = array_unique($areaList);
                    $division = empty($parent->parent)?null:$parent->parent;
                    //片区的上级管辖部门(事业部)存在，那么保存事业部id
                    if($division!=null)$divisionList[] = $division->id;
                    $divisionList = array_unique($divisionList);
                }
            }
        }
        $attr = array(
            'division' => count($divisionList),
            'area'  => count($areaList),
            'community' => $community_num
        );
        return $attr;
    }

    /**
     * @return array
     * 按时间筛选各分类的投诉报修数据
     */
    public function getDataByTime($startTime="2000-01-01 00:00:00",$endTime=""){
        $startTime = strtotime(empty($startTime)?"2000-01-01 00:00:00":$startTime);
        $endTime = empty($endTime)?time():strtotime($endTime);
        //获得用户的所有管辖部门ID
        $customerAttr = array("name"=>"业主投诉","handlingNum"=>0,"endNum"=>0,"total"=>0,
            "category_id"=>Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);
        $personalAttr = array("name"=>"个人报修","handlingNum"=>0,"endNum"=>0,"total"=>0,
            "category_id"=>Item::COMPLAIN_REPAIRS_TYPE_PERSON);
        $publicAttr = array("name"=>"公共报修","handlingNum"=>0,"endNum"=>0,"total"=>0,
            "category_id"=>Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);

//        $branch_Ids = $this->getBranchIdsByAuth();
//        //获得用户管辖部门下的所有部门ID
//        $branchIds = array();
//        foreach($branch_Ids as $branch_id){
//            $branch = Branch::model()->findByPk($branch_id);
//            //合并数组
//            $branchIds = array_merge($branchIds,$branch->getBranchIds());
//        }
//        //根据部门ID获取所有小区
//        $cri = new CDbCriteria();
//        $cri->addInCondition('branch_id', $branchIds);
//        $communityList = Community::model()->findAll($cri);
//        //获得小区ID集合
//        $communityIds = array_map(function($community){
//            return $community->id;
//        },$communityList);

//	    ICE接入 通过当前登录人的权限获取到他下面的小区id集合
	    $employye = ICEEmployee::model()->findbypk(Yii::app()->user->id);
	    $communityIds = $employye->ICEGetOrgCommunity();


        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('create_time',$startTime,$endTime);
        $criteria->addInCondition('community_id',$communityIds);
        //筛选投诉报修
        $objList = ComplainRepairs::model()->findAll($criteria);
        //处理中状态的所有定义//0，2，3，10
        $stateAttr = array(Item::COMPLAIN_REPAIRS_AWAITING_HANDLE,Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING,
            Item::COMPLAIN_REPAIRS_HANDLE_END,Item::COMPLAIN_REPAIRS_PUBLIC_NONE);
        foreach($objList as $obj){
            //按条件统计
            switch($obj->type){
                case Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER://业主投诉
                    $customerAttr['total'] += 1;
                    $customerAttr['handlingNum'] += in_array($obj->state,$stateAttr)?1:0;
                    $customerAttr['endNum'] += ($obj->state==Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE?1:0);
                    break;
                case Item::COMPLAIN_REPAIRS_TYPE_PUBLIC://公共报修
                    $publicAttr['total'] += 1;
                    $publicAttr['handlingNum'] += in_array($obj->state,$stateAttr)?1:0;
                    $publicAttr['endNum'] += ($obj->state==Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE?1:0);
                    break;
            }
        }

        $totalCriteria = new CDbCriteria();
        $totalCriteria->addBetweenCondition('create_time',$startTime,$endTime);
        $totalCriteria->addInCondition('community_id',$communityIds);
        $personalAttr['total'] += PersonalRepairsInfo::model()->count($totalCriteria);

        $handleCriteria = new CDbCriteria();
        $handleCriteria->addBetweenCondition('create_time',$startTime,$endTime);
        $handleCriteria->addInCondition('community_id',$communityIds);
        // 处理中包括的状态
        $handleCriteria->addCondition("IF(state=0,shop_state IN (0,5,7),state IN (2,3))");
        $personalAttr['handlingNum'] += PersonalRepairsInfo::model()->count($handleCriteria);

        $endCriteria = new CDbCriteria();
        $endCriteria->addBetweenCondition('create_time',$startTime,$endTime);
        $endCriteria->addInCondition('community_id',$communityIds);
        $endCriteria->addInCondition('state',array(Item::PERSONAL_REPAIRS_SUCCESS_COLOSE,Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE));
        $personalAttr['endNum'] += PersonalRepairsInfo::model()->count($endCriteria);

        $attr = array($customerAttr,$publicAttr,$personalAttr);
        return $attr;
    }

    /**
     * @param $type 分类ID(业主投诉/公共报修/个人报修)
     * @param string $startTime 开始时间(不传或空自动转换为2000-01-01)
     * @param string $endTime 开始时间(不传或空自动转换为当前日期)
     * @param int $page 请求页数，注意从0开始
     * @param int $pageSize 页面条数。默认10
     * @return array
     * 根据分类获取小区的投诉报修统计数据
     */
    public function getCommunityDataByType($type,$startTime="2000-01-01 00:00:00",$endTime="",$page=0,$pageSize=10){
        $startTime = strtotime(empty($startTime)?"2000-01-01 00:00:00":$startTime);
        $endTime = empty($endTime)?time():strtotime($endTime);
        //获得小区ID集合
        $communityIds = $this->getCommunityIdsByType($type,$startTime,$endTime);
        //分页
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $communityIds);
        $criteria->order = 'id ASC';
        $count = count($communityIds);
        $pager = new CPagination($count);
        $pager->setPageSize($pageSize);
        $pager->setCurrentPage($page);
        $criteria->offset = $page*$pageSize;
        $criteria->limit = $pager->getPageSize();
        $communityList = Community::model()->findAll($criteria);
        //获取每个小区的投诉报修统计信息
        $communityAttr = array();
        foreach($communityList as $community){
            $attr['id']=$community->id;
            $attr['name'] = $community->name;
            $attr['branch'] = trim($community->getCommunityBelongBranch($community->id),"/");

            $totalCriteria = new CDbCriteria();
            $totalCriteria->addBetweenCondition('create_time',$startTime,$endTime);
            $totalCriteria->compare('community_id',$community->id);
            //获得小区对应的投诉报修集合
            if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                $model = new PersonalRepairsInfo();
            }else{
                $model = new ComplainRepairs();
                $totalCriteria->compare('type',$type);
            }
            //获得该小区在选择的类型下的总数
            $attr['total'] = $model->count($totalCriteria);

            $handleCriteria = new CDbCriteria();
            $handleCriteria->addBetweenCondition('create_time',$startTime,$endTime);
            $handleCriteria->compare('community_id',$community->id);
            if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                // 处理中包括的状态
                $handleCriteria->addCondition("IF(state=0,shop_state IN (0,5,7),state IN (2,3))");
            }else{
                $handleCriteria->compare('type',$type);
                //获得该小区在选择的类型下的总数
                $handleAttr = array(Item::COMPLAIN_REPAIRS_AWAITING_HANDLE,Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING,
                    Item::COMPLAIN_REPAIRS_HANDLE_END,Item::COMPLAIN_REPAIRS_PUBLIC_NONE);
                $handleCriteria->addInCondition('state',$handleAttr);
            }
            $handlingNum = $model->count($handleCriteria);
            //获得处理中状态的数目
            $attr['handlingNum'] = $handlingNum;

            $endCriteria = new CDbCriteria();
            $endCriteria->addBetweenCondition('create_time',$startTime,$endTime);
            $endCriteria->addCondition('community_id='.$community->id);
            if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                $endAttr = array(Item::PERSONAL_REPAIRS_SUCCESS_COLOSE,Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE);
                $endCriteria->addInCondition('state',$endAttr);
            }else{
                $endCriteria->compare('type',$type);
                $endCriteria->addCondition('`state`='.Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
            }
            //获得已结束的数目
            $attr['endNum'] = $model->count($endCriteria);

            //保存每个小区的相关记录
            $communityAttr[] = $attr;
        }
        return $communityAttr;
    }

    /**
     * @param $type 分类ID(业主投诉/公共报修/个人报修)
     * @param string $startTime 开始时间(不传或空自动转换为2000-01-01)
     * @param string $endTime 开始时间(不传或空自动转换为当前日期)
     * @param int $page 请求页数，注意从0开始
     * @param int $pageSize 页面条数。默认10
     * @return array
     * 根据分类获取片区的投诉报修统计数据
     */
    public function getAreaDataByType($type,$startTime="2000-01-01",$endTime="",$page=0,$pageSize=10){
        $startTime = strtotime(empty($startTime)?"2000-01-01 00:00:00":$startTime);
        $endTime = empty($endTime)?time():strtotime($endTime);
        //获得小区ID集合
        $communityIds = $this->getCommunityIdsByType($type,$startTime,$endTime);
        //循环所有小区。获得该小区对应的片区ID
        $AreaIds = array();
        //片区和小区的关联数组
        $idToAreaList = array();
        foreach($communityIds as $community_id){
            $community = Community::model()->findByPk($community_id);
            if(!empty($community->branch)){
                //获得小区的所属片区
                $area_id = $community->branch->parent_id;
                $AreaIds[] = $area_id;
                //保存片区和小区的关联，减少通过片区找小区的查询时间和内存
                $idToAreaList[$area_id][] = $community_id;
            }
        }
        $AreaIds = array_unique($AreaIds);

        //分页
        $areaCriteria = new CDbCriteria();
        $areaCriteria->addInCondition('id', $AreaIds);
        $areaCriteria->order = 'id ASC';
        $count = count($AreaIds);
        $pager = new CPagination($count);
        $pager->setPageSize($pageSize);
        $pager->setCurrentPage($page);
        $areaCriteria->offset = $page*$pageSize;
        $areaCriteria->limit = $pager->getPageSize();
        //获得一页分区数据
        $areaList = Branch::model()->findAll($areaCriteria);
        //循环片区
        $areaAttr = array();
        foreach($areaList as $area){
            $attr['id']=$area->id;
            $attr['name'] = $area->name ;
            $attr['branch'] = str_ireplace("-","/",Branch::getMyParentBranchName($area->id));
            $attr['total'] = $attr['handlingNum'] = $attr['endNum'] = 0;
            //通过片区ID获得关联的有效小区
            foreach($idToAreaList[$area->id] as $communityId){
                $criteria = new CDbCriteria();
                $criteria->addBetweenCondition('create_time',$startTime,$endTime);
                $criteria->compare('community_id',$communityId);
                if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                    $model = new PersonalRepairsInfo();
                }else{
                    $model = new ComplainRepairs();
                    $criteria->compare('type',$type);
                }
                $attr['total'] += $model->count($criteria);

                //获得小区对应的投诉报修集合
                $handleCriteria = new CDbCriteria();
                $handleCriteria->addBetweenCondition('create_time',$startTime,$endTime);
                $handleCriteria->compare('community_id',$communityId);
                if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                    // 处理中包括的状态
                    $handleCriteria->addCondition("IF(state=0,shop_state IN (0,5,7),state IN (2,3))");
                }else{
                    $handleCriteria->compare('type',$type);
                    $handleAttr = array(Item::COMPLAIN_REPAIRS_AWAITING_HANDLE,Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING,
                        Item::COMPLAIN_REPAIRS_HANDLE_END,Item::COMPLAIN_REPAIRS_PUBLIC_NONE);
                    $handleCriteria->addInCondition('state',$handleAttr);
                }
                $handlingNum = $model->count($handleCriteria);
                //获得处理中状态的数目
                $attr['handlingNum'] += $handlingNum;

                $endCriteria = new CDbCriteria();
                $endCriteria->addBetweenCondition('create_time',$startTime,$endTime);
                $endCriteria->compare('community_id',$communityId);
                if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                    $endAttr = array(Item::PERSONAL_REPAIRS_SUCCESS_COLOSE,Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE);
                    $endCriteria->addInCondition('state',$endAttr);
                }else{
                    $endCriteria->compare('type',$type);
                    $endCriteria->addCondition('`state`='.Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
                }
                $endNum = $model->count($endCriteria);
                //获得已结束的数目
                $attr['endNum'] += $endNum;
            }
            $areaAttr[] = $attr;
        }
        return $areaAttr;
    }

    /**
     * @param $type 分类ID(业主投诉/公共报修/个人报修)
     * @param string $startTime 开始时间(不传或空自动转换为2000-01-01)
     * @param string $endTime 开始时间(不传或空自动转换为当前日期)
     * @param int $page 请求页数，注意从0开始
     * @param int $pageSize 页面条数。默认10
     * @return array
     * 根据分类获取事业部的投诉报修统计数据
     */
    public function getDivisionDataByType($type,$startTime="2000-01-01 00:00:00",$endTime="",$page=0,$pageSize=10){
        $startTime = strtotime(empty($startTime)?"2000-01-01 00:00:00":$startTime);
        $endTime = empty($endTime)?time():strtotime($endTime);
        $idToDivisionList = array();
        //获得用户权限范围内的所有事业部id
        $DivisionIdList = $this->getDivisionIds($idToDivisionList,$type,$startTime,$endTime);
        //按分页每次取一页数据
        $cri = new CDbCriteria();
        $cri->addInCondition('id', $DivisionIdList);
        $cri->order = 'id ASC';
        $count = count($DivisionIdList);
        $pager = new CPagination($count);
        $pager->setPageSize($pageSize);
        $pager->setCurrentPage($page);
        $cri->offset = $page*$pageSize;
        $cri->limit = $pager->getPageSize();
        //获得一页事业部数据
        $divisionList = Branch::model()->findAll($cri);
        //循环事业部，获得事业部的投诉报修统计信息
        $divisionAttr = array();
        foreach($divisionList as $division){
            $attr['id'] = $division->id;
            $attr['name'] = $division->name;
            $attr['branch'] = str_ireplace("-","/",Branch::getMyParentBranchName($division->id));
            $attr['total'] = $attr['handlingNum'] = $attr['endNum'] = 0;
            foreach($idToDivisionList[$division->id] as $communityId){
                $criteria = new CDbCriteria();
                $criteria->addBetweenCondition('create_time',$startTime,$endTime);
                $criteria->compare('community_id',$communityId);
                if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                    $model = new PersonalRepairsInfo();
                }else{
                    $criteria->compare('type',$type);
                    $model = new ComplainRepairs();
                }
                $attr['total'] += $model->count($criteria);

                $handleCriteria = new CDbCriteria();
                $handleCriteria->addBetweenCondition('create_time',$startTime,$endTime);
                $handleCriteria->compare('community_id',$communityId);
                if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                    // 处理中包括的状态
                    $handleCriteria->addCondition("IF(state=0,shop_state IN (0,5,7),state IN (2,3))");
                }else{
                    $handleCriteria->compare('type',$type);
                    $handleAttr = array(Item::COMPLAIN_REPAIRS_AWAITING_HANDLE,Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING,
                        Item::COMPLAIN_REPAIRS_HANDLE_END,Item::COMPLAIN_REPAIRS_PUBLIC_NONE);
                    $handleCriteria->addInCondition('state',$handleAttr);
                }
                $handlingNum = $model->count($handleCriteria);
                //获得处理中状态的数目
                $attr['handlingNum'] += $handlingNum;

                $endCriteria = new CDbCriteria();
                $endCriteria->addBetweenCondition('create_time',$startTime,$endTime);
                $endCriteria->compare('community_id',$communityId);
                if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
                    //已结束包括的状态
                    $endAttr = array(Item::PERSONAL_REPAIRS_SUCCESS_COLOSE,Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE);
                }else{
                    $endCriteria->compare('type',$type);
                    $endAttr = array(Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
                }
                $endCriteria->addInCondition('state',$endAttr);
                $endNum = $model->count($endCriteria);
                //获得已结束的数目
                $attr['endNum'] += $endNum;
            }
            $divisionAttr[] = $attr;
        }
        return $divisionAttr;
    }

    /**
     * @param $type 分类ID(业主投诉/公共报修/个人报修)
     * @param $community_id 小区ID
     * @param string $startTime
     * @param string $endTime
     * @param int $page
     * @param int $pageSize
     * @return array|CActiveRecord|mixed|null
     * 根据分类和小区ID获取投诉报修信息
     */
    public function getComplainRepairsByCommunity($type,$community_id,$startTime="2000-01-01 00:00:00",$endTime="",$page=0,$pageSize=10){
        $startTime = strtotime(empty($startTime)?"2000-01-01 00:00:00":$startTime);
        $endTime = empty($endTime)?time():strtotime($endTime);
        return $this->getComplainRepairs($type,array($community_id),$startTime,$endTime,$page,$pageSize);
    }

    /**
     * @param $type
     * @param $branch_id  片区ID或事业部ID
     * @param string $startTime
     * @param string $endTime
     * @param int $page
     * @param int $pageSize
     * @return array|CActiveRecord|mixed|null
     * 根据片区或事业部查询投诉报修信息
     */
    public function getComplainRepairsByBranch($type,$branch_id,$startTime="2000-01-01 00:00:00",$endTime="",$page=0,$pageSize=10){
        $startTime = strtotime(empty($startTime)?"2000-01-01 00:00:00":$startTime);
        $endTime = empty($endTime)?time():strtotime($endTime);
        $branch = Branch::model()->findByPk($branch_id);
        $communityList = $branch->getBranchAllData('Community');
        $communityIds = array();
        foreach($communityList as $community){
            $communityIds[] = $community->id;
        }
        //获得在用户权限范围内且有投诉报修记录的小区ID
        $idList = $this->getCommunityIdsByType($type,$startTime,$endTime);
        //取交集过滤掉片区或事业部下不在用户权限的小区ID
        $idAarray = array_intersect($communityIds,$idList);
        return $this->getComplainRepairs($type,$idAarray,$startTime,$endTime,$page,$pageSize);
    }

    /**
     * @param $type 分类ID(业主投诉/公共报修/个人报修)
     * @param array communityIds 小区ID集合
     * @param string $startTime 开始时间(不传或空自动转换为2000-01-01)
     * @param string $endTime 开始时间(不传或空自动转换为当前日期)
     * @param int $page 请求页数，注意从0开始
     * @param int $pageSize 页面条数。默认10
     */
    private function getComplainRepairs($type,$communityIds=array(),$startTime,$endTime,$page=0,$pageSize=10){
        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('create_time',$startTime,$endTime);
        if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){
            $model = new PersonalRepairsInfo();
            $criteria->addCondition("IF(state=0,shop_state IN (0,5,7),state IN (2,3))");
        }else if($type==Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER || $type==Item::COMPLAIN_REPAIRS_TYPE_PUBLIC){
            $model = new ComplainRepairs();
            $criteria->compare('type',$type);
            $criteria->addCondition("`state` IN (0,10,2,3)");
        }
        $criteria->addInCondition('community_id',$communityIds);
        $criteria->order = 'create_time DESC';
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->setPageSize($pageSize);
        $pager->setCurrentPage($page);
        $criteria->offset = $page*$pageSize;
        $criteria->limit = $pager->getPageSize();

        $modelList = $model->findAll($criteria);
        return $modelList;
    }



    private function getBranchIdsByAuth(){
        if(Yii::app()->user->checkAccess("op_backend_apiProperty_reportsIndex")){
            return array(1);
        }
        $user_id = Yii::app()->user->id;
        //$employee = Employee::model()->findByPk($user_id);
        $ebrList = EmployeeBranchRelation::model()->findAllByAttributes(array('employee_id'=>$user_id));
        //获得用户的所有管辖部门ID
        $branch_Ids = array_map(function ($record) {
            return $record->branch_id;
        }, $ebrList);
        return $branch_Ids;
    }

    //获得用户权限范围内的事业部ID
    private function getDivisionIds(&$idToDivisionList,$type,$startTime,$endTime){
        $communityIds = $this->getCommunityIdsByType($type,$startTime,$endTime);
        $divisionIds = array();
        //片区和小区的关联数组
        foreach($communityIds as $community_id){
            $community = Community::model()->findByPk($community_id);
            if(!empty($community->branch)){
                //获得小区的所属事业部
                if(!empty($community->branch->parent)){
                    $division_id = $community->branch->parent->parent_id;
                    $divisionIds[] = $division_id;
                    $idToDivisionList[$division_id][] = $community_id;
                }
            }
        }
        $divisionIds = array_unique($divisionIds);
        return $divisionIds;
    }

    /**
     * @param $type 投诉报修类型(个人报修/业主报修、公共报修)
     * @param int $startTime 开始时间 时间戳
     * @param int $endTime 结束时间 时间戳
     * @return array
     * 根据投诉报修类型(个人/业主、员工、公共)获得有效的小区ID集合
     */
    private function getCommunityIdsByType($type,$startTime,$endTime){
        if($startTime>time() or $startTime>$endTime)return array();
        $typeCriteria = new CDbCriteria();
        $typeCriteria->addBetweenCondition('create_time',$startTime,$endTime);
        //获得用户要查询的对象集合
        if($type==Item::COMPLAIN_REPAIRS_TYPE_PERSON){//个人报修
            $crList = PersonalRepairsInfo::model()->findAll($typeCriteria);
        }else{
            $typeCriteria->compare('type',$type);
            $crList = ComplainRepairs::model()->findAll($typeCriteria);
        }
        //获得对象所涉及到的小区ID
        $crCommunityIds = array_map(function($obj){
            return $obj->community_id;
        },$crList);
        //过滤重复ID
        $crCommunityIds = array_unique($crCommunityIds);
        //获得用户所在的管辖部门ID
        $branch_Ids = $this->getBranchIdsByAuth();
        //获得用户管辖部门下的所有部门ID
        $branchIds = array();
        foreach($branch_Ids as $branch_id){
            $branch = Branch::model()->findByPk($branch_id);
            //合并数组
            $branchIds = array_merge($branchIds,$branch->getBranchIds());
        }
        //根据部门ID获取所有小区
        $cri = new CDbCriteria();
        $cri->addInCondition('branch_id', $branchIds);
        $communityList = Community::model()->findAll($cri);
        //获得小区ID集合
        $communityIds = array_map(function($community){
            return $community->id;
        },$communityList);
        //取交集获得所有有投诉报修记录的ID集合
        return array_intersect($crCommunityIds,$communityIds);
    }

    //前台状态
    public function getUserState(){
    	if($this->state == Item::COMPLAIN_REPAIRS_AWAITING_HANDLE){
    		return  Item::COMPLAIN_REPAIRS_LOG_AWAITING_HANDL;//0
    	}else if($this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE){
    		return Item::COMPLAIN_REPAIRS_LOG_COLOSE;//5
    	}else if($this->state == Item::COMPLAIN_REPAIRS_CONFIRM_END){
    		return Item::COMPLAIN_REPAIRS_LOG_CONFIRM_END;//3
    	}else if($this->state == Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING && $this->oldState == Item::COMPLAIN_REPAIRS_CONFIRM_END){
    		return Item::COMPLAIN_REPAIRS_LOG_BAD_HANDLING;//2
    	}else{
    		return Item::COMPLAIN_REPAIRS_LOG_HANDLING;//1
    	}
    }

    public function afterFind(){
    	$this->oldState = $this->state;
    	return parent::afterFind();
    }

    public function getBranchNames()
    {
        $branchName = '';
        if (!empty($this->community) && !empty($this->community->branch)) {
            $branchName = $this->community->branch->getMyParentBranchName($this->community->branch_id);
        }
        return $branchName;
    }

    //获得小区所在的地区，isself=true，包括小区
    public function getCommunityBelongRegion($isself = false)
    {
        $community = Community::model()->findByPk($this->community_id);
        if (!empty($community)) {
            //$_regionName = Region::getMyParentRegionNames($community->region_id, true);
            $_regionName = $community->ICEGetCommunityRegionsNames();
        } else {
            $_regionName = "";
        }
        if ($isself) {
            $_regionName .= '-' . $community->name;
        }
        return $_regionName;
    }

    public function getReportCreateTime(){
        return date("Y-m-d H:i:s",$this->create_time);
    }

    public function getLowStates(){
        return array(
            '0' => '否',
            '1' => '是',
        );
    }

    public function getLowState(){
        switch($this->low){
            case 0:
                return "否";
                break;
            case 1:
                return "是";
                break;
            default :
                return "未知";
                break;
        }
    }

}