<?php

/**
 *
 * 金融平台交易适配类
 * Created by PhpStorm.
 * User: austin
 * Date: 6/24/16
 * Time: 12:23 PM
 * TODO:kakatool
 */
class FinanceSyncService
{

	const FINANCE_TRANSACTION_RECHARGE=1;//充值
	const FINANCE_TRANSACTION_CONSUME=2;//消费
	const FINANCE_TRANSACTION_TRANSFER=3;//转账
	const FINANCE_TRANSACTION_CASH=4;//提现
	const FINANCE_TRANSACTION_EMPLOYEE_CUSTOMER_TRANSFER=5;//员工向用户转账

	const FINANCE_FROM_CUSTOMER=1;//用户
	const FINANCE_FROM_EMPLOYEE=2;//员工

	protected static $instance;


	private $finance_transaction_sync_queue = "finance_transaction_sync";//交易同步队列
	public function __construct()
	{

	}

	public static function getInstance()
	{
		if (!isset(self::$instance))
			self::$instance = new self;
		return self::$instance;
	}


	/**
	 * 员工充值
	 * @param $oa_username
	 * @param $amount
	 * @param $content
	 */
	public function employeeRecharge($oa_username,$amount,$content){

		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_RECHARGE,
			'from_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'to_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'from_user'=>'',
			'to_user'=>$oa_username, //oa_账号
			'amount'=>$amount,
			'content'=>$content
		);

		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));
	}


	/**
	 * 消费
	 * @param $oa_username
	 * @param $amount
	 * @param $content
	 */
	public function employeeConsume($oa_username,$amount,$content){
		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_CONSUME,
			'from_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'to_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'from_user'=>$oa_username,//oa_账号
			'to_user'=>'',
			'amount'=>$amount,
			'content'=>$content
		);

		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));
		
	}

	/**
	 * 员工内部转账
	 * @param $from_employee_id
	 * @param $to_employee_id
	 * @param $amount
	 * @param $content
	 */
	public function employeeTransfer($from_employee_id,$to_employee_id,$amount){
		if(!$from_employee_id||!$to_employee_id){
			return;
		}


		$from_oausername='';
		$from_employee = Employee::model()->findByPk($from_employee_id);
		if($from_employee){
			$from_oausername=$from_employee->oa_username;
		}

		$to_oausername='';
		$to_employee= Employee::model()->findByPk($to_employee_id);
		if($to_employee){
			$to_oausername=$to_employee->oa_username;
		}

		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_TRANSFER,
			'from_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'to_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'from_user'=>$from_oausername,//oa_账号
			'to_user'=>$to_oausername,//oa_账号
			'amount'=>$amount,
			'content'=>'员工'.$from_employee['name'].'转账'.$amount.'给员工'.$to_employee['name']
		);

		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));

	}


	/**
	 * 员工转账给用户
	 * @param $from_employee_id
	 * @param $to_customer_id
	 * @param $amount
	 * @param $content
	 */
	public function employeeTransferToCustomer($from_employee_id,$to_customer_id,$amount){
		if(!$from_employee_id||!$to_customer_id){
			return;
		}

		$from_oausername='';
		$from_employee = Employee::model()->findByPk($from_employee_id);
		if($from_employee){
			$from_oausername=$from_employee->oa_username;
		}

//		$to_customer_mobilie=''; //用customer_id替代mobile
		$to_customer= Customer::model()->findByPk($to_customer_id);
//		if($to_customer){
//			$to_customer_mobilie=$to_customer->mobile;
//		}

		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_EMPLOYEE_CUSTOMER_TRANSFER,
			'from_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'to_type'=>FinanceSyncService::FINANCE_FROM_CUSTOMER,
			'from_user'=>$from_oausername,
			'to_user'=>$to_customer_id,//customer_id
			'amount'=>$amount,
			'content'=>'员工'.$from_employee['name'].'转账'.$amount.'给用户'.$to_customer['name']
		);

		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));

	}

	/**
	 * 员工提现
	 * @param $oa_username
	 * @param $amount
	 * @param $content
	 */
	public function employeeCash($oa_username,$amount,$content){
		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_CASH,
			'from_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'to_type'=>FinanceSyncService::FINANCE_FROM_EMPLOYEE,
			'from_user'=>$oa_username,
			'to_user'=>'',
			'amount'=>$amount,
			'content'=>$content
		);

		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));

	}


	/**
	 * 用户充值
	 * @param $mobile
	 * @param $amount
	 * @param $content
	 */
	public function customerRecharge($customer_id,$amount,$content){

		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_RECHARGE,
			'from_type'=>FinanceSyncService::FINANCE_FROM_CUSTOMER,
			'to_type'=>FinanceSyncService::FINANCE_FROM_CUSTOMER,
			'from_user'=>'',
			'to_user'=>$customer_id,
			'amount'=>$amount,
			'content'=>$content
		);


		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));

	}

	/**
	 * 用户消费
	 * @param $mobile
	 * @param $amount
	 * @param $content
	 */
	public function customerConsume($customer_id,$amount,$content){
		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_CONSUME,
			'from_type'=>FinanceSyncService::FINANCE_FROM_CUSTOMER,
			'to_type'=>FinanceSyncService::FINANCE_FROM_CUSTOMER,
			'from_user'=>$customer_id,
			'to_user'=>'',
			'amount'=>$amount,
			'content'=>$content
		);

		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));

	}


	/**
	 * 用户转账
	 * @param $from_customer_id
	 * @param $to_customer_id
	 * @param $amount
	 * @param $content
	 */
	public function customerTransfer($from_customer_id,$to_customer_id,$amount){
		if(!$from_customer_id||!$to_customer_id){
			return;
		}

		$from_mobile='';
		$from_customer = Customer::model()->findByPk($from_customer_id);
		if($from_customer){
			$from_mobile=$from_customer->mobile;
		}

		$to_mobile='';
		$to_customer= Customer::model()->findByPk($to_customer_id);
		if($to_customer){
			$to_mobile=$to_customer->mobile;
		}

		$data = array(
			'type'=>FinanceSyncService::FINANCE_TRANSACTION_TRANSFER,
			'from_type'=>FinanceSyncService::FINANCE_FROM_CUSTOMER,
			'to_type'=>FinanceSyncService::FINANCE_FROM_CUSTOMER,
			'from_user'=>$from_customer_id,
			'to_user'=>$to_customer_id,
			'amount'=>$amount,
			'content'=>'用户'.$from_customer['name'].'转账'.$amount.'给用户'.$to_customer['name']
		);

		Yii::app()->rediscache->executeCommand('LPUSH',array($this->finance_transaction_sync_queue,json_encode($data)));

	}



}