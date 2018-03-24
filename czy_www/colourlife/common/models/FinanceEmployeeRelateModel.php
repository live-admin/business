<?php

/**
 * 金融平台员工饭票关联表
 * TODO:kakatool
 * Created by PhpStorm.
 * User: austin
 * Date: 6/23/16
 * Time: 3:43 PM
 */
class FinanceEmployeeRelateModel extends CActiveRecord
{

	public $modelName = '金融平台员工饭票关联表';

	public $id;
	public $pano;
	public $cno;
	public $cano;
	public $employee_id;
	public $oa_username;
	public $mobile;
	public $name;
	public $pay_password;


	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return "finance_employee_relation";
	}

	public function rules(){

		return array(
			array('pano,cno,cano,employee_id,oa_username', 'required', 'on' => 'create,update'),
			array('name,pay_password,oa_username','length','max'=>100),
			array('mobile','length','max'=>11)

		);

	}


	/**
	 * 检查是否存在
	 * @param int $oa_username OA账号
	 * @return bool
	 */
	public function checkIfExsit($oa_username=''){
		if(!$oa_username){
			return FALSE;
		}

		$item=FinanceEmployeeRelateModel::model()->find('oa_username=:oa_username',array(':oa_username'=>$oa_username));

		return $item==NULL?FALSE:TRUE;

	}

	/**
	 * 根据员工id获取员工
	 * @param int $employee_id
	 * @return array|bool|CActiveRecord|mixed|null
	 */
	public function getByEmployeeid($employee_id=0){
		if(!$employee_id){
			return FALSE;
		}

		return FinanceEmployeeRelateModel::model()->find('employee_id=:employee_id',array(':employee_id'=>$employee_id));
	}

	/**
	 * 根据oa账号获取员工
	 * @param string $oa_username
	 * @return array|bool|CActiveRecord|mixed|null
	 */
	public function getByOa($oa_username=''){
		if(!$oa_username){
			return FALSE;
		}

		return FinanceEmployeeRelateModel::model()->find('oa_username=:oa_username',array(':oa_username'=>$oa_username));
		
	}


	/**
	 * 增加记录
	 * @param $data
	 * @return bool
	 */
	public function addFinanceEmployeeRelation($data=array()){

		if(!$data||empty($data)){
			return FALSE;
		}

		$sql = ' INSERT INTO finance_employee_relation (pano,cno,cano,employee_id,mobile,`name`,oa_username,pay_password) VALUES(:pano,:cno,:cano,:employee_id,:mobile,:name,:oa_username,:pay_password) ';

		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$command->bindParam(":pano",$data['pano'],PDO::PARAM_STR);
		$command->bindParam(":cno",$data['cno'],PDO::PARAM_STR);
		$command->bindParam(":cano",$data['cano'],PDO::PARAM_STR);
		$command->bindParam(":employee_id",$data['employee_id'],PDO::PARAM_INT);
		$command->bindParam(":mobile",$data['mobile'],PDO::PARAM_STR);
		$command->bindParam(":name",$data['name'],PDO::PARAM_STR);
		$command->bindParam(":oa_username",$data['oa_username'],PDO::PARAM_STR);
		$command->bindParam(":pay_password",$data['pay_password'],PDO::PARAM_STR);

		$command->execute();

		return TRUE;

	}

}