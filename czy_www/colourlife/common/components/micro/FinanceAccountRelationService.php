<?php

/**
 * ICE接入服务
 */
class FinanceAccountRelationService
{

	protected static $instance;

	protected $runOnConsole = false;

	protected $migratedEmployees = array();
	protected $migratedCustomers = array();


	public function __construct()
	{
		$this->runOnConsole = php_sapi_name() == 'cli';
	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	protected function console($msg = '', $level = 'info')
	{
		if ($this->runOnConsole) {
			echo sprintf(
				'[%s] %s %s %s',
				date('Y-m-d H:i:s'),
				$level,
				$msg,
				PHP_EOL
			);
		} else {
			Yii::log(
				sprintf(
					'%s[%s]',
					$msg, $level
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.components.micro.FinanceAccountRelationService'
			);
		}
		return true;
	}

	public function migrateEmployee($username = '', $withBalance = false)
	{
		if (!$username) {
			return $this->console('请指定需要同步的员工账号');
		}

		if (isset($this->migratedEmployees[$username])) {
			return $this->console(sprintf(
				'员工已经同步至金融平台:',
				$username
			));
		}

		$this->console(sprintf('尝试同步员工 %s 至金融平台', $username));

		try {
			$employee = ICEEmployee::model()->ICEGetAccount(array(
				'username' => $username
			));
		} catch (Exception $e) {
			$this->console(sprintf(
				'从微服务获取员工信息员工失败: %s[%s]',
				$e->getMessage(), $e->getCode()
			));
		}

		if (!isset($employee['employeeAccount']) || !$employee['employeeAccount']) {
			return $this->console(sprintf(
				'无法从微服务获取员工信息员工: %s',
				$username
			));
		}

		$this->console(sprintf(
			'待同步的员工信息： %s',
			json_encode($employee)
		));

		$this->console('插入中间表');
		EmployeeMiddle::model()->InsertOAByICE($username);
		/*$employeeMiddle = EmployeeMiddle::model();
		$employeeMiddle->insertEmployeeMiddle(array($employee), 1);
		$employeeMiddle->insertEmployeeByIce(array($employee), 2);*/

		$pano = FinanceMicroService::getInstance()->getEmployeePano();
		$atid = isset($pano['atid']) && $pano['atid'] ? $pano['atid'] : '';
		$pano = isset($pano['pano']) && $pano['pano'] ? $pano['pano'] : '';
		if (!$pano || !$atid) {
			return $this->console(sprintf('彩之云金融平台账号未配置'));
		}

		$employee = Employee::model()->find(
			'username = :username',
			array(':username' => $username)
		);

		$relation = FinanceEmployeeRelateModel::model()->find(
			'employee_id = :employee_id AND pano = :pano AND atid = :atid',
			array(
				':employee_id' => $employee->id,
				':pano' => $pano,
				':atid' => $atid,
			)
		);
		if ($relation) {
			return $this->console(sprintf(
				'员工已经同步至金融平台:',
				$username
			));
		}


		$employee->gender = isset($employee['sex']) && $employee['sex'] == '男' ? 1 : 2;

		$mobile = $employee->mobile;
		if (!$mobile) {
			$mobile = FinanceMicroService::getInstance()->randMobile();
			$employee->mobile = $mobile;
		}

		try {
			$this->console('创建金融平台账号');
			$account = FinanceMicroService::getInstance()->addClientClient(
				$pano,
				'',
				$employee->name,
				$mobile,
				$employee->gender,
				'',
				'彩之云后台导入',
				0
			);
		} catch (Exception $e) {
			$this->console(sprintf(
				'创建金融平台账号失败: %s[%s]',
				$e->getMessage(),
				$e->getCode()
			));
		}

		if (!$account
			|| !isset($account['account'])
			|| !isset($account['account']['cano'])
			|| !$account['account']['cano']
			|| !isset($account['client'])
			|| !isset($account['client']['cno'])
			|| !$account['client']['cno']
		) {
			return $this->console(sprintf(
				'创建金融平台账号返回数据异常:%s %s',
				$pano,
				json_encode($account)
			));
		}

		if ($withBalance == true) {
			$transaction = FinanceMicroService::getInstance()->fastTransaction(
				$employee->balance,
				'彩之云后台员工查询余额自动同步账号入余额',
				$atid,
				$pano,
				$atid,
				$account['account']['cano'],
				'',
				'http://www.baidu.com'
			);

			if (!$transaction || !isset($transaction['payinfo'])) {
				return $this->console(sprintf(
					'同步账号入余额返回数据异常:%s %s',
					$pano,
					json_encode($transaction)
				));
			}
		}

		$this->console(sprintf('成功创建金融平台账号: %s', json_encode($account)));
		$this->console('更新本地映射关系');

		$relation = FinanceEmployeeRelateModel::model()->find(
			'employee_id = :employee_id AND pano = :pano AND cano = :cano AND cno = :cno',
			array(
				':employee_id' => $employee->id,
				':pano' => $pano,
				':cano' => $account['account']['cano'],
				':cno' => $account['client']['cno']
			)
		);

		if (!$relation) {
			$this->console('本地映射关系不存在，尝试新建');
			$relation = new FinanceEmployeeRelateModel();
			$relation['pano'] = $pano;
			//$relation['atid'] = $atid;
			$relation['cno'] = $account['client']['cno'];
			$relation['cano'] = $account['account']['cano'];
			$relation['employee_id'] = $employee->id;
			$relation['oa_username'] = $username;
			$relation['mobile'] = $mobile;
			$relation->setScenario('create');
		} else {
			$relation->setScenario('update');
		}

		$relation['name'] = $employee->name;
		$relation['pay_password'] = $employee->pay_password;

		if ($relation->save()) {
			$this->console(sprintf(
				'同步员工 %s 完成%s',
				$username, PHP_EOL
			));
			$this->migratedEmployees[$username] = $employee->id;
		} else {
			$this->console(sprintf(
				'无法建立本地映射关系: %s%s',
				json_encode($relation->getErrors()), PHP_EOL
			));
		}

		$employee->save();

		return true;
	}

	public function migrateEmployees($limit = 100, $hasBalance = false)
	{
		$command = Yii::app()->db->createCommand()
			->select('e.username')
			->from('employee e')
			->leftjoin('finance_employee_relation r', 'e.id = r.employee_id');
		$command->where('e.is_deleted = 0 AND e.state = 0 AND ISNULL(r.employee_id) AND e.username <> ""');

		if ($hasBalance) {
			$command->andWhere('e.balance > 0');
		}

		$command->order('e.balance DESC, e.last_time DESC')
			->limit($limit);

		$employees = $command->queryAll();
		if (!$employees) {
			return $this->console('没有可同步的员工');
		}

		foreach ($employees as $employee) {
			$this->migrateEmployee($employee['username']);
		}

		$this->console(sprintf('完成同步员工 %s 个。', count($this->migratedEmployees)));
	}

	public function getMigratedEmployees()
	{
		return $this->migratedEmployees;
	}

	public function migrateCustomer($mobile = '', $withBalance = false, $panoParam = array())
	{
		if (!$mobile) {
			return $this->console('请指定需要同步的用户手机号');
		}

		if (isset($this->migratedCustomers[$mobile])) {
			return $this->console(sprintf(
				'手机号为 %s 的会员已经同步至金融平台',
				$mobile
			));
		}

		$this->console(sprintf('尝试同步手机号为 %s 的会员至金融平台', $mobile));

		$customer = Customer::model()->find(
			'mobile = :mobile',
			array(':mobile' => $mobile)
		);
		if (!$customer) {
			return $this->console(sprintf(
				'不存在的彩之云用户 %s',
				$mobile
			));
		}
		$note = '彩之云后台导入';
		if (!empty($panoParam)) {
			$note = $panoParam['note'];
			unset($panoParam['note']);
			$pano = $panoParam;
		} else {
			$pano = FinanceMicroService::getInstance()->getCustomerPano();
		}
		$atid = isset($pano['atid']) && $pano['atid'] ? $pano['atid'] : '';
		$pano = isset($pano['pano']) && $pano['pano'] ? $pano['pano'] : '';
		if (!$pano || !$atid) {
			return $this->console(sprintf('彩之云金融平台账号未配置'));
		}

		try {
			$this->console('创建金融平台账号');
			$account = FinanceMicroService::getInstance()->addClientClient(
				$pano, '',
				$customer->name,
				$customer->mobile,
				$customer->gender,
				'',
				$note,
				0
			);
		} catch (Exception $e) {
			return $this->console(sprintf(
				'创建金融平台账号失败: %s[%s]',
				$e->getMessage(),
				$e->getCode()
			));
		}

		if (!$account
			|| !isset($account['account'])
			|| !isset($account['account']['cano'])
			|| !$account['account']['cano']
			|| !isset($account['client'])
			|| !isset($account['client']['cno'])
			|| !$account['client']['cno']
		) {
			return $this->console(sprintf(
				'创建金融平台账号返回数据异常: %s %s',
				$pano,
				json_encode($account)
			));
		}

		if ($withBalance == true) {
			$transaction = FinanceMicroService::getInstance()->fastTransaction(
				$customer->balance,
				'彩之云后台用户查询余额自动同步账号入余额',
				$atid,
				$pano,
				$atid,
				$account['account']['cano'],
				'',
				'http://www.baidu.com'
			);

			if (!$transaction || !isset($transaction['payinfo'])) {
				return $this->console(sprintf(
					'同步账号入余额返回数据异常:%s %s',
					$pano,
					json_encode($transaction)
				));
			}
		}

		$this->console(sprintf('成功创建金融平台账号:%s', json_encode($account)));
		$this->console('更新本地映射关系');

		$relation = FinanceCustomerRelateModel::model()->find(
			'customer_id = :customer_id AND pano = :pano AND cano = :cano AND cno = :cno',
			array(
				':customer_id' => $customer->id,
				':pano' => $pano,
				':cano' => $account['account']['cano'],
				':cno' => $account['client']['cno']
			)
		);

		if (!$relation) {
			$this->console('本地映射关系不存在，尝试新建');
			$relation = new FinanceCustomerRelateModel();
			$relation['pano'] = $pano;
			$relation['atid'] = $atid;
			$relation['cno'] = $account['client']['cno'];
			$relation['cano'] = $account['account']['cano'];
			$relation['customer_id'] = $customer->id;
			$relation['mobile'] = $customer->mobile;
			$relation->setScenario('create');
		} else {
			$relation->setScenario('update');
		}

		$relation['name'] = $customer->name;
		$relation['pay_password'] = $customer->pay_password;

		if ($relation->save()) {
			$this->console(sprintf(
				'同步手机号为 %s 的会员完成%s',
				$mobile, PHP_EOL
			));
			$this->migratedCustomers[$mobile] = $customer->id;
		} else {
			$this->console(sprintf(
				'无法建立本地映射关系: %s%s',
				json_encode($relation->getErrors()), PHP_EOL
			));
		}

		return true;
	}

	public function migrateCustomers($limit = 100, $hasBalance = false)
	{
		$command = Yii::app()->db->createCommand()
			->select('c.id, c.name, c.mobile, c.balance, c.pay_password, FROM_UNIXTIME(c.last_time, "%Y-%m-%d %H:%i:%s") AS LastUpdate, r.pano, r.cano, r.cno')
			->from('customer c');
		$command->leftjoin('finance_customer_relation r', 'c.id = r.customer_id');

		$command->where('c.is_deleted = 0 AND c.state = 0 AND NOT (c.community_id = 585 AND c.build_id = 10421 AND c.room = 1 AND c.`name` = "访客") AND ISNULL(r.customer_id)');

		if ($hasBalance) {
			$command->andWhere('c.balance > 0');
		}

		$command->order('c.balance DESC, c.last_time DESC')
			->limit($limit);

		$custoemrs = $command->queryAll();

		if (!$custoemrs) {
			return $this->console('没有可同步的用户');
		}

		foreach ($custoemrs as $customer) {
			$this->migrateCustomer($customer['mobile']);
		}

		$this->console(sprintf('完成同步用户 %s 个。', count($this->migratedCustomers)));
	}

	public function getMigratedCustomers()
	{
		return $this->migratedCustomers;
	}
}