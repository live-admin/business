<?php
/**
 * 彩住宅证书控制器
 * @author gongzhiling
 * Date: 2015/11/2
 * Time:17:36
 */

class CaiZhuZhaiController extends CController{
	private $_userId=0;
	private $_account;
	/**
	 * 彩住宅证书
	 */
	public function actionCart(){
		$this->checkLogin();
		$criteria = new CDbCriteria() ;
		$czz_data=CzzCertificate::model()->findAll('account like :account ' ,array(":account"=>"%$this->_account%"));
		if (count($czz_data)>0){
			$this->renderPartial("cart",array('model'=>$czz_data));
		}else {
			$this->renderPartial("nodata");
		}
	}
	
	private function checkLogin(){
		//csh判断是否来自彩之云客户端
		if (isset($_GET['csh']) || isset($_GET['cust_id'])){
			if(isset($_GET['csh'])){
				$cust_id = floatval(Yii::app()->request->getParam('csh'));
				$cust_id = ($cust_id+1778)/178;
				$cust_id=intval($cust_id);
			}
			if(isset($_GET['cust_id'])){
				$cust_id = floatval(Yii::app()->request->getParam('cust_id'));
				$cust_id=intval($cust_id);
			}
			if (empty($cust_id)) {
				$this->renderPartial("czzdownload");
				exit();
			}
			$customer = Customer::model()->findByPk($cust_id);
			if (empty($customer)) {
				$this->renderPartial("czzdownload");
				exit();
			}
			$this->_account=$customer->mobile;
			$this->_userId = $cust_id;
		}else{
			$this->renderPartial("czzdownload");
			exit();
		}

	}
	
	/**
	 * 身份证号格式化
	 * @param unknown $idNumber
	 * @return string|Ambigous <string, mixed>
	 */
	public function idNumberFormat($idNumber){
		if (empty($idNumber)){
			return '';
		}
		if (strpos($idNumber, '_')!==false){
			$idNumberArr=explode("_", $idNumber);
			foreach ($idNumberArr as &$id){
				$id=substr_replace($id,"**********",3,10);
			}
			$idNumber=implode("_", $idNumberArr);
		}else {
			$idNumber=substr_replace($idNumber,"**********",3,10);
		}
		return $idNumber;
	}
	
	/**
	 * 格式化姓名
	 * @param unknown $name
	 * @return string|Ambigous <string, mixed>
	 */
	public function nameFormat($name){
		if (empty($name)){
			return '';
		}
		if (strpos($name, '_')!==false){
			$nameArr=explode("_", $name);
			foreach ($nameArr as &$v){
				if ($this->strLenCn($v)==4){
					$v=mb_substr($v,0,1,'utf-8').'**'.mb_substr($v,3,1,'utf-8'); //四个中文的中间两个被*号替换
				}elseif ($this->strLenCn($v)==3){
					$v=mb_substr($v,0,1,'utf-8').'*'.mb_substr($v,2,1,'utf-8');  //三个中文的中间一个被*号替换
				}
			}
			$name=implode("_", $nameArr);
		}else {
			if ($this->strLenCn($name)==4){
				$name=mb_substr($v,0,1,'utf-8').'**'.mb_substr($v,3,1,'utf-8'); //四个中文的中间两个被*号替换
			}elseif ($this->strLenCn($name)==3){
				$name=mb_substr($v,0,1,'utf-8').'*'.mb_substr($v,2,1,'utf-8');  //三个中文的中间一个被*号替换
			}
		}
		return $name;
	}
	
	/**
	 * 中文字符个数
	 *
	 * @param string $str
	 * @param string $charset
	 * @return int
	 */
	private function strLenCn($str, $charset = 'utf-8') {
		$re ['utf-8'] = "/[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re ['gb2312'] = "/[\xb0-\xf7][\xa0-\xfe]/";
		$re ['gbk'] = "/[\x81-\xfe][\x40-\xfe]/";
		$re ['big5'] = "/[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		return preg_match_all ( $re [$charset], $str, $match );
	}
	
	/**
	 * 字符串截取
	 * @see 处理中文英文长度不同的问题
	 * @author gesion<gesion@163.com>
	 * @param string $str 原始字符串
	 * @param int    $len 截取长度（中文/全角符号默认为 2 个单位，英文/数字为 1。
	 * 例如：长度 12 表示 6 个中文或全角字符或 12 个英文或数字）
	 * @param bool   $dot 是否加点（若字符串超过 $len 长度，则后面加 "..."）
	 * @return string
	 */
	private function get_substr($str, $len = 12, $dot = true) {
		if (empty ( $str )) {
			return '';
		}
		$i = 0;
		$l = 0;
		$c = 0;
		$a = array ();
		while ( $l < $len ) {
			$t = substr ( $str, $i, 1 );
			if (ord ( $t ) >= 224) {
				$c = 3;
				$t = substr ( $str, $i, $c );
				$l += 2;
			} elseif (ord ( $t ) >= 192) {
				$c = 2;
				$t = substr ( $str, $i, $c );
				$l += 2;
			} else {
				$c = 1;
				$l ++;
			}
			// $t = substr($str, $i, $c);
			$i += $c;
			if ($l > $len)
				break;
			$a [] = $t;
		}
		$re = implode ( '', $a );
		if (substr ( $str, $i, 1 ) !== false) {
			array_pop ( $a );
			($c == 1) and array_pop ( $a );
			$re = implode ( '', $a );
			$dot and $re .= '...';
		}
		return $re;
	}

}
