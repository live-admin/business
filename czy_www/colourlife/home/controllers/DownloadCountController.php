<?php
/**
 * 下载统计
 * @author gongzhiling
 * @copyright 2016-03-22 17:13
 */
class DownloadCountController extends CController{
	public function actionIndex(){
		$type=intval(Yii::app()->request->getParam('type'));
		if (!empty($type)){
			$downLoad= new DownloadCount();
			$downLoad->type=$type;
			$downLoad->create_time=time();
			$downLoad->save();
		}
		$this->redirect('http://mapp.colourlife.com/m.html');
	}
}