<?php
/**
 * 元宵节活动控制器
 * @author gongzhiling
 * Date: 2016/2/16
 * Time:15:03
 */

class LanternFestivalController extends CController{
	
	private $cust_id=0;
	
	public function init(){
		if (isset($_REQUEST['cust_id'])&&!empty($_REQUEST['cust_id'])) {
			$this->cust_id=intval($_REQUEST['cust_id']);
		}else{
			$cid=Yii::app()->request->getParam('cid');
			if (!empty($cid)){
				$this->cust_id=intval($cid);
			}
		}
	}
	/**
	 * 活动首页
	 */
	public function actionIndex(){
		$this->addLog($this->cust_id);
		$this->renderPartial("index",array('cust_id'=>$this->cust_id));
	}
	
	/**
	 * 制作
	 */
	public function actionMake(){
		$this->addLog($this->cust_id,1);
		$this->renderPartial("make",array('cust_id'=>$this->cust_id));
	}
	
	/**
	 * 选祝福语
	 */
	public function actionGreeting(){
		$photo=Yii::app()->request->getParam('photo');
		$this->renderPartial("greeting",array('cust_id'=>$this->cust_id,'photo'=>$photo));
	}
	
	/**
	 * 生成图片
	 */
	public function actionBuild(){
		$time=date("d");
		$validate=md5('colourlife'.$time);
		$photo=Yii::app()->request->getParam('photo');
		$greeting=Yii::app()->request->getParam('greeting');
		$this->renderPartial("build",array('cust_id'=>$this->cust_id,'photo'=>$photo,'greeting'=>$greeting,'validate'=>$validate));
	}
	
	/**
	 * 分享页
	 */
	public function actionShare(){
		$tips=false;
		if (isset($_SERVER['HTTP_REFERER'])&&!empty($_SERVER['HTTP_REFERER'])){
			if (strpos($_SERVER['HTTP_REFERER'], "build")!==false){
				$tips=true;
			}
		}
		$photo=Yii::app()->request->getParam('p');
		$greeting=Yii::app()->request->getParam('g');
		$this->renderPartial("share",array('greeting'=>$greeting,'photo'=>$photo,'tips'=>$tips));
	}
	
	/**
	 * 分享记录
	 */
	public function actionCount(){
		$validate=Yii::app()->request->getParam('validate');
		echo $validate;
		if (!empty($validate)&&$validate==md5('colourlife'.date("d"))){
			$cid=Yii::app()->request->getParam('cid');
			if (!empty($cid)){
				$this->cust_id=intval($cid);
			}
			$photo=Yii::app()->request->getParam('p');
			$greeting=Yii::app()->request->getParam('g');
			$content='';
			if (!empty($greeting)){
				$content.=intval($greeting);
			}
			if (!empty($photo)){
				$content.='_'.$photo;
			}
			$this->addLog($this->cust_id,2,$content);
		}
	}
	
	/**
	 * 操作统计
	 * @param unknown $cust_id
	 * @param unknown $type
	 * @return boolean
	 */
	private function addLog($cust_id,$type=0,$content=''){
		$lantern=new LanternFestivalLog();
		$lantern->cust_id=$cust_id;
		$lantern->type=$type;
		$lantern->content=$content;
		$lantern->create_time=time();
		if ($lantern->save()){
			return true;
		}else {
			return false;
		}
	}
	
}