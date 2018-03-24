<?php

/**
 * This is the model class for table "reward_jobs".
 *
 * The followings are the available columns in table 'reward_jobs':
 * @property integer $id
 * @property integer $product_type
 * @property string $job_name
 * @property integer $job_is_special
 * @property integer $create_time
 * @property integer $update_employee_time 
 * @property string $update_employee_id
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $reward_param
 * @property integer $allot_param
 * @property string $remark

 */
class RewardJobs extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '职位提成参数';
    ///public $job_name;
    public $job_is_special;
    ///public $remark;
    ///public $product_type = '1';

	private static $product_types = array(
        '1' => '金融类产品',
        '2' => '非金融类产品',
    );
    
    private static $ex_jobs = array(
        '员工(非客户经理)' => '员工(非客户经理)',
        '总部客户部' => '总部客户部',
    	'事业部客户部经理' => '事业部客户部经理',
    	'小组团队长' => '小组团队长',
    );
    
    private static $com_jobs = array(
        '小区主任' => '小区主任',
    	'客户经理' => '客户经理',
    );
  
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Employee the static model class
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
        return 'reward_jobs';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        
        return array(
			array('job_name', 'required'),
			array('job_is_special, product_type, is_deleted, jobkey', 'numerical', 'integerOnly'=>true),
			array('reward_param', 'numerical', 'integerOnly'=>false),
			array('allot_param', 'numerical', 'integerOnly'=>false),
			array('job_name', 'length', 'max'=>250),
			array('type', 'length', 'max'=>100),
			array('remark', 'length', 'max'=>255),
			array('state', 'numerical', 'integerOnly'=>true),
			array('product_type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, job_name, type, product_type, is_deleted, reward_param, allot_param, remark, state，update_employee_time，update_employee_id，create_time，jobkey', 'safe', 'on'=>'search'),
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
        ///"id" => array(self::HAS_MANY, 'FundProjectEvolution', 'fund_project_id'),
    }
    
	/**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'updateAttribute' => null,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
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
            'product_type' => '产品类型',
        	'type' => '理财产品',
            'job_name' => '职位',
        	'jobkey'  => '职位key',
           
            'create_time' => '创建时间',
            'update_employee_time' => '最后修改时间',
            'update_employee_id' => '最后修改人ID',
            'state' => '状态',
            'reward_param' => '奖励系数',
            'allot_param' => '分配系数',           
            'remark' => '说明',
			'job_is_special' => '职位是否特殊',
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
        
        $criteria->addCondition(" is_deleted=0");

        $criteria->compare('product_type', $this->product_type);
        $criteria->compare('type', $this->type);

        $criteria->compare('job_name', $this->job_name, true);

        $criteria->compare('state', $this->state);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function searchNew()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->addCondition(" is_deleted=0");

        $criteria->compare('product_type', $this->product_type);
        $criteria->compare('type', $this->type);

        $criteria->compare('job_name', $this->job_name, true);

        $criteria->compare('state', $this->state);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));

    }
    /*
     * 记录最后访问
     */
    public function updateLast()
    {
        $this->updateByPk($this->id, array(
            'update_employee_time' => time(),
            'update_employee_id' => Yii::app()->user->id,
        ));
    }

    public static function getProductType($type = null)
    {
        if (null === $type) {
            return self::$product_types;
        } else {
            if (isset(self::$product_types[$type])) {
                return self::$product_types[$type];
            }
            return '类型未定义';
        }
    }
    
    public function getProductTypeName()
    {
		return $this->getProductType($this->attributes['product_type']);
    }
    
    public static function getRewardJobsList()
    {
        
        $jobs = array();
                
        $connection = Yii::app()->db;
        $sql = "SELECT  DISTINCT job_name FROM reward_jobs WHERE state=0 AND is_deleted=0 ORDER BY job_name DESC";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        if(!empty($result)){
        	foreach ($result as $k1 => $val1) {
				$jobs[$result[$k1]['job_name']] = $result[$k1]['job_name'];
			}
        }
        return $jobs;
    }  
    
    /**
     * @param boolean $extra 是否要包括这边自动义的一些普通职位
     * @return array 返回所有的职位信息，一定包括了这边特殊定义的职位
     * 
     */
    public function getOAJobsList($extra=false){
        $jobsList = array();
        
        //调OA接口获得jobs数据//
        /*
    	foreach($jobsList as $_v){
        if (isset($_v->id)) {
        		$jobsList[$_v->id] = $_v->name;
        	}else{
        		$jobsList[$_v->name] = $_v->name;
        	}  
        }*/
        
        Yii::import('common.components.MultiTblComm');
        $datas = MultiTblComm::getInstance()->getOAJobs(); 
		$iNum = count($datas);
		if ($iNum>0){
			foreach ($datas as $k1 => $val1) {
				$jobsList[$datas[$k1]['name']] = $datas[$k1]['name'];
	             
			}
		}
		
        $jobsList = array_merge(self::$ex_jobs, $jobsList);
        
        if(!$extra){
        	return $jobsList;
        }else{
        	return array_merge(self::$com_jobs, $jobsList);
        }
    }
    
    public function isExJob($job = null)
    {
    	
    	if(isset($job)){
	    	foreach(self::$ex_jobs as $key => $value ){

	    		if(isset($value)){
		        	if(strcasecmp($job, $value)==0){
		        		return true;
		        	}
	    		}
	    	}
    	}
    	return false;
    }

    
    /**
     * 根据可获得奖励的职位列表信息
     * @param int $product_type 产品种类
     * @param string $job_name  职位名称
     * @return array
     */
    public function getRewardJobs($type, $job_name = '')
    {
        
        $jobs = array();
        
        if (empty($type)) {
        	return $jobs;
        }
        
        $criteria = new CDbCriteria;
        
        $criteria->addCondition(" is_deleted=0");
        $criteria->addCondition(" state=0");
        $criteria->compare("type", $type);
        if (!empty($job_name))
        {
        	$criteria->compare("job_name", $job_name);
        }

        $jobs = RewardJobs::model()->findAll($criteria);
        
        return $jobs;
    } 
    
    /**
     * 根据职位获得OA方面职位列表信息
     * @param string $job_name  职位名称
     * @return array
     */
    public function getOAJobs($job_name = '')
    {
        
        $jobs = array();
        
        if (empty($job_name)){
        	return $jobs;
        }
                
        $connection = Yii::app()->db;
        $sql = "select job_name, oa_job FROM jobs4oa where state=0 and is_deleted=0 and job_name ='".$job_name."'";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        if(!empty($result)){
        	return $result;
        }
        return $jobs;
    }  
    
    public function getKey4Create($job_name = '')
    {
    	if (empty($job_name)){
        	return 0;
        }
        
        if (strnatcmp(strtoupper($job_name),strtoupper('总部客户部'))==0){
        	return 1;
        }
        else if (strnatcmp(strtoupper($job_name),strtoupper('事业部客户部经理'))==0){
        	return 2;
        }
    	else if (strnatcmp(strtoupper($job_name),strtoupper('客户经理'))==0){
        	return 4;
        }
    	else if (strnatcmp(strtoupper($job_name),strtoupper('员工(非客户经理)'))==0){
        	return 8;
        }
    	else if (strnatcmp(strtoupper($job_name),strtoupper('小组团队长'))==0){
        	return 16;
        }
    	else if (strnatcmp(strtoupper($job_name),strtoupper('小区主任'))==0){
        	return 32;
        }
    	else {
        	
    		$connection = Yii::app()->db;
    		
    		$sql = "select jobkey from reward_jobs where job_name= '".$job_name."' ";
    		
    		$command = $connection->createCommand($sql);
    		$result = $command->queryAll();
        
	    	if (count($result) > 0) {
	    		
	    		return $result[0]['jobkey']; 
	        }else{
	        	$sql = "SELECT (MAX(jobkey>>12) +1)<<12 AS maxkey FROM reward_jobs";
	        	
	        	$command1 = $connection->createCommand($sql);
    			$result1 = $command1->queryAll();
		        if (count($result1) > 0) {
		    		
		    		return $result1[0]['maxkey']; 
		        }else{
		        	return 0;
		        }
	        }
        }
    }
    
    public function beCanInsert()
    {
    	$irtn = 0;

    	$criteria = new CDbCriteria;
        
        $criteria->addCondition(" is_deleted=0");
        $criteria->addCondition(" state=0");
        $criteria->compare('type', $this->type);
        $criteria->compare('job_name', $this->job_name);

        $jobs = RewardJobs::model()->findAll($criteria);
        if (!empty($jobs)){
        	if(count($jobs) > 0){
        		$irtn = 1; //已经存在同类数据。
        		return $irtn;
        	}
        }
        
    	$connection = Yii::app()->db;
    	
    	if (strnatcmp(strtoupper($this->job_name),strtoupper('员工(非客户经理)'))==0){
    		$sql = "select sum(reward_param) as reward_sum, sum(allot_param) as allot_sum FROM reward_jobs where state=0 and is_deleted=0 and job_is_special=1 and type='".$this->type."'";
    	}else{
    		$sql = "select sum(reward_param) as reward_sum, sum(allot_param) as allot_sum FROM reward_jobs where state=0 and is_deleted=0 and job_name<>'员工(非客户经理)' and type='".$this->type."'";
    	}
        $command = $connection->createCommand($sql);
        
        $result = $command->queryAll();
        
    	if (count($result) > 0) {
    		$reward_sum = $result[0]['reward_sum']; 
		    $allot_sum = $result[0]['allot_sum']; 
		    if ($allot_sum + $this->allot_param > 100.00){
		    	$irtn = 2; //分配参数的总和过大。
        		return $irtn;
		    }
        }
        if ($this->isExJob($this->job_name)){
        	if (strnatcmp(strtoupper($this->job_name),strtoupper('员工(非客户经理)'))!=0){
        		$sql = "select sum(reward_param) as reward_sum, sum(allot_param) as allot_sum FROM reward_jobs where state=0 and is_deleted=0 and job_is_special=1 and type='".$this->type."'";
        		
        		$command1 = $connection->createCommand($sql);
        
        		$result = $command1->queryAll();
        
		    	if (count($result) > 0) {
		    		$reward_sum = $result[0]['reward_sum']; 
				    $allot_sum = $result[0]['allot_sum']; 
				    if ($allot_sum + $this->allot_param > 100.00){
				    	$irtn = 2; //分配参数的总和过大。
		        		return $irtn;
				    }
		        }
        	}
        }
    	
    	return $irtn;
    }
    
	public function beCanUpdate()
    {
    	$irtn = 0;

    	$criteria = new CDbCriteria;
        
        $criteria->addCondition(" is_deleted=0");
        $criteria->addCondition(" state=0");
        $criteria->addCondition(' id!='.$this->id);
        $criteria->compare('type', $this->type);
        $criteria->compare('job_name', $this->job_name);

        $jobs = RewardJobs::model()->findAll($criteria);

        if (!empty($jobs)){
        	if(count($jobs) > 0){
        		$irtn = 1; //已经存在同样数据。
        		return $irtn;
        	}
        }
    	
    	$connection = Yii::app()->db;
    	
    	if (strnatcmp(strtoupper($this->job_name),strtoupper('员工(非客户经理)'))==0){
    		$sql = "select sum(reward_param) as reward_sum, sum(allot_param) as allot_sum FROM reward_jobs 
    			where state=0 and is_deleted=0 and job_is_special=1 and id!=".$this->id 
    			." and type='".$this->type."'";
    	}else{
    		$sql = "select sum(reward_param) as reward_sum, sum(allot_param) as allot_sum FROM reward_jobs 
    			where state=0 and is_deleted=0 and job_name<>'员工(非客户经理)' and id!=".$this->id 
    			." and type='".$this->type."'";
    	}
    	
        $command = $connection->createCommand($sql);
        
        $result = $command->queryAll();
        
    	if (count($result) > 0) {
    		$reward_sum = $result[0]['reward_sum']; 
		    $allot_sum = $result[0]['allot_sum']; 
		    if ($allot_sum + $this->allot_param > 100.00){
		    	$irtn = 2; //分配参数的总和过大。
        		return $irtn;
		    }
        }
        
    	if ($this->isExJob($this->job_name)){
        	if (strnatcmp(strtoupper($this->job_name),strtoupper('员工(非客户经理)'))!=0){
        		$sql = "select sum(reward_param) as reward_sum, sum(allot_param) as allot_sum FROM reward_jobs 
    			where state=0 and is_deleted=0 and job_is_special=1 and id!=".$this->id 
    			." and type='".$this->type."'";
        		
        		$command1 = $connection->createCommand($sql);
        
        		$result = $command1->queryAll();
        
		    	if (count($result) > 0) {
		    		$reward_sum = $result[0]['reward_sum']; 
				    $allot_sum = $result[0]['allot_sum']; 
				    if ($allot_sum + $this->allot_param > 100.00){
				    	$irtn = 2; //分配参数的总和过大。
		        		return $irtn;
				    }
		        }
        	}
        }
    	
    	return $irtn;
    }
}

