<?php

/**
 * 金融平台用户饭票关联表
 * TODO:kakatool
 * Created by PhpStorm.
 * User: austin
 * Date: 6/23/16
 * Time: 11:22 AM
 */
class FinanceCustomerRelateModel extends CActiveRecord
{
	public $modelName = '金融平台用户饭票关联表';

	public $id;
	public $pano;
	public $cno;
	public $cano;
	public $customer_id;
	public $mobile;
	public $name;
	public $pay_password;


	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return "finance_customer_relation";
	}

	public function rules()
	{

		return array(
			array('pano,cno,cano,customer_id,mobile', 'required', 'on' => 'create,update'),
			array('name,pay_password', 'length', 'max' => 100)
		);

	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pano' => '平台账号',
			'cno' => '用户账号',
			'cano' => '用户账号',
			'atid' => '支付账号',
			'customer_id' => '用户ID',
			'mobile' => '手机',
			'pay_password' => '加密码',
			'name' => '姓名',
		);
	}


	/**
	 * 检查是否存在
	 * @param int $customer_id
	 * @return bool
	 */
	public function checkIfExsit($mobile = '',$pano = '')
	{
		if (!$mobile) {
			return FALSE;
		}

		if (empty($pano)){
			$pano = FinanceMicroService::getInstance()->getCustomerPano()['pano'];
		}
		$item = FinanceCustomerRelateModel::model()->find('mobile=:mobile and pano=:pano', array(':mobile' => $mobile,':pano'=>$pano));
		return $item == NULL ? FALSE : TRUE;

	}


	/**
	 * 根据用户id获取用户金融账号
	 * @param int $customer_id
	 * @return array|bool|CActiveRecord|mixed|null
	 */
	public function getByCustomerid($customer_id = 0,$pano = '')
	{
		if (!$customer_id) {
			return FALSE;
		}

		if (empty($pano)){
			$pano = FinanceMicroService::getInstance()->getCustomerPano()['pano'];
		}
		return FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and pano=:pano', array(':customer_id' => $customer_id,':pano' => $pano));
	}

	/**
	 * 根据手机号码查询
	 * @param string $mobile
	 * @return array|bool|CActiveRecord|mixed|null
	 */
	public function getByMobile($mobile = '',$pano = '')
	{
		if (!$mobile) {
			return FALSE;
		}
		
		if (empty($pano)){
			$pano = FinanceMicroService::getInstance()->getCustomerPano()['pano'];
		}
		
		return FinanceCustomerRelateModel::model()->find('mobile=:mobile and pano=:pano', array(':mobile' => $mobile,':pano' => $pano));
	}


	/**
	 * 增加记录
	 * @param $data
	 * @return bool
	 */
	public function addFinanceCustomerRelation($data = array())
	{

		if (!$data || empty($data)) {
			return FALSE;
		}

		$strKey = '';
		$strValue = '';
		if (isset($data['atid']) && isset($data['fanpiaoid'])){
			$strKey = ',atid,fanpiaoid';
			$strValue = ',:atid,:fanpiaoid';
		}
		$sql = ' INSERT INTO finance_customer_relation (pano,cno,cano,customer_id,mobile,`name`,pay_password'.$strKey.') VALUES(:pano,:cno,:cano,:customer_id,:mobile,:name,:pay_password'.$strValue.') ';

		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$command->bindParam(":pano", $data['pano'], PDO::PARAM_STR);
		$command->bindParam(":cno", $data['cno'], PDO::PARAM_STR);
		$command->bindParam(":cano", $data['cano'], PDO::PARAM_STR);
		$command->bindParam(":customer_id", $data['customer_id'], PDO::PARAM_INT);
		$command->bindParam(":mobile", $data['mobile'], PDO::PARAM_STR);
		$command->bindParam(":name", $data['name'], PDO::PARAM_STR);
		$command->bindParam(":pay_password", $data['pay_password'], PDO::PARAM_STR);
		if (!empty($strKey) && !empty($strValue)){
			$command->bindParam(":atid", $data['atid'], PDO::PARAM_INT);
			$command->bindParam(":fanpiaoid", $data['fanpiaoid'], PDO::PARAM_INT);
		}

		$command->execute();

		return TRUE;

	}

	/**
	 * 获取用户的支付方式
	 * @param unknown $pano
	 * @param unknown $customer_id
	 * @return boolean|Ambigous <multitype:, mixed>
	 */
	public function getPayListByPanoAndCustomerID($pano, $customer_id)
	{
		if (empty($pano) || empty($customer_id)) {
			return false;
		}
		$sql = "select fcr.pano,fcr.atid,fcr.cno,fcr.customer_id,fcr.mobile,fcr.name as username,fcr.cano,fpt.atid,fpt.typeid,fpt.name,fpt.memo from finance_customer_relation fcr left join finance_pay_type fpt on fcr.pano = fpt.pano where fpt.status = 1 and fcr.pano = '{$pano}' and fcr.customer_id = {$customer_id}";
		$query = Yii::app()->db->createCommand($sql);
		$result = $query->queryAll();
		if (!empty($result)) {
			return $result[0];
		}
		return false;
	}

}