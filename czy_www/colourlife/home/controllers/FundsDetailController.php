<?php
/**
 * 财富人生资金明细控制器
 * @author gongzhiling
 * @date 2016-04-18 15:35
 */
class FundsDetailController extends CController{
	/**
	 * 资金明细
	 */
	public function actionDetail() {
		$sn = Yii::app ()->request->getParam ( 'sn' );
		$ts = Yii::app ()->request->getParam ( 'ts' );
		$model = Yii::app ()->request->getParam ( 'm' );
		$sign = Yii::app ()->request->getParam ( 'sign' );
		$str = 'sn=' . $sn . '&model=' . $model . '&ts=' . $ts;
		$fundsign = md5 ( $str );
		if ($sign != $fundsign || empty ( $sn )) {
			throw new CHttpException ( 400, '参数错误！' );
		}
		$detail = array ();
		if ($model == 'plan') { // 增值计划
			Yii::import ( 'common.services.AppreciationPlanService' );
			$service = new AppreciationPlanService ();
		} elseif ($model == 'profit') { // 物业宝和停车宝
			Yii::import ( 'common.services.ProfitService' );
			$service = new ProfitService ();
		} else {
			throw new CHttpException ( 400, '参数错误！' );
		}
		$fundsDetail = $service->getFundsDetail ( $sn );
		if ($fundsDetail ['RESP_CODE'] == '000' && ! empty ( $fundsDetail ['RESP_MSG'] )) {
			$detail = json_decode ( $fundsDetail ['RESP_MSG'], true );
			$cacheKey = md5 ( 'fundsDetail' );
			$result = Yii::app ()->cache->set ( $cacheKey, $detail, 3600 );
			$this->renderPartial ( "detail", array (
					'fundsDetail' => $detail 
			) );
		} else {
			$this->renderPartial ( "null" );
		}
	}
	
	/**
	 * 四方协议
	 */
	public function actionAgreement() {
		$num = intval ( Yii::app ()->request->getParam ( 'k' ) );
		$ts = Yii::app ()->request->getParam ( 'ts' );
		$checkSign = md5 ( 'ts=' . $ts . '&k=' . ($num - 1) . 'cfrs_agreement' );
		$sign = Yii::app ()->request->getParam ( 'sign' );
		if ($sign != $checkSign || empty ( $num )) {
			throw new CHttpException ( 400, '参数错误！' );
		}
		$cacheKey = md5 ( 'fundsDetail' );
		$detail = Yii::app ()->cache->get ( $cacheKey );
		if (! empty ( $detail )) {
			$data = $detail [$num - 1];
			$this->renderPartial ( "agreement", array (
					'data' => $data 
			) );
		} else {
			throw new CHttpException ( 404, '抱歉，找不到你请求的页面！' );
		}
	}
	/**
	 * 格式化姓名
	 * 
	 * @param unknown $name        	
	 * @return string Ambigous mixed>
	 */
	public function getName($name) {
		$len = $this->strLenCn ( $name );
		$headLen = 1;
		$endLen = 1;
		if ($len >= 5) {  //五个以上，第五个之后的内容用*代替
			$headLen = 5;
			$endLen = 0;
		} elseif ($len == 2) { //两个字符串的只显示第一个
			$endLen = 0;
		}
		$name = $this->strStar ( $name, $len, $headLen, $endLen );
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
	 * 字符串部分内容使用星星代替
	 * 
	 * @param unknown $str        	
	 * @param number $len        	
	 * @param number $headLen        	
	 * @param number $tailLen        	
	 * @return string
	 */
	private function strStar($str, $len = 0, $headLen = 0, $tailLen = 0) {
		$star = '';
		$startStr = '';
		$endStr = '';
		$num = $len - ($headLen + $tailLen);
		if ($headLen > 0) {  //头部
			$startStr = mb_substr ( $str, 0, $headLen, 'utf-8' );
		}
		if ($tailLen > 0) { //尾部
			$start = $len - 1;
			$endStr = mb_substr ( $str, $start, $tailLen, 'utf-8' );
		}
		
		for($i = 0; $i < $num; $i ++) {
			$star .= '*';
		}
		return $startStr . $star . $endStr;
	}
}