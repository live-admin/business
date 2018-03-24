<?php
/**
 * 2016年会抽奖展示端
 * @author gongzhiling
 * Date: 2015/12/17
 * Time:15:35
 */

class LotteryDrawController extends CController{
	
	/**
	 * 抽奖页
	 */
	public function actionIndex(){
		$name=array();
		$lottery=LotteryMember::model()->findAll(array(
				'select'=>array('uname','username','mobile','type'),
				'condition' => 'lottery_type=0',
		));
		if (!empty($lottery)){
			shuffle($lottery);
			foreach ($lottery as $v){
				if ($v->type==2){
					$name[]=$v->uname.'_'.$v->mobile;
				}else{
					$name[]=$v->username.'_'.$v->uname;
				}
			}
		}
		$prize=PrizeList::model()->find("state=1");  //取当前正在进行中的奖项
		$this->renderPartial ( "index", array (
				'name' => $name,
				'prize'=>$prize,
				'validate'=>md5(date("Ymd").'colourlife') 
		) );
	}
	
	/**
	 * 抽奖过程
	 */
	public function actionDraw(){
		if (!isset($_POST['validate'])||$_POST['validate']!=md5(date("Ymd").'colourlife')){
			$data['status']=0;
			$data['msg']='非法操作！';
			echo json_encode($data);
			exit();
		}
		$data=array();
		$prize=PrizeList::model()->find("state=1");
		if (empty($prize)){
			$data['status']=0;
			$data['msg']='抽奖还没开始！';
			echo json_encode($data);
			exit();
		}
		$num=$prize->num;
		$lottery=LotteryMember::model()->findAll("lottery_type=0");//取未中奖名单
		if (empty($lottery)){
			$data['status']=0;
			$data['msg']='请先录入名单！';
			echo json_encode($data);
			exit();
		}
		if (count($lottery)>$prize->num){
			$total=$prize->num;
		}else {
			$total=count($lottery);
		}
		$name_arr=array();
		do {
			shuffle($lottery);  //随机打乱数组
			$key=array_rand($lottery);  //随机取出名单
			$object=$lottery[$key];
			$r=$this->operate($object, $prize->id);
			if ($r){
				if ($object->type==2){ //嘉宾显示格式
					$object->mobile=substr_replace($object->mobile,"****",3,4);
					$name_arr[]=$object->uname.'_'.$object->mobile;
				}else{  //员工显示格式
					$name_arr[]=$object->username.'_'.$object->uname;
				}
				unset($lottery[$key]);
			}
		}while(count($name_arr)<$total);
		if (!empty($name_arr)){
			$data['status']=1;
			$data['data']=$name_arr;
		}else {
			$data['status']=0;
			$data['msg']='操作出错！';
		}
		echo json_encode($data);
	}
	
	/**
	 * 抽奖流程
	 * @param unknown $object
	 * @param unknown $prizeID
	 * @return boolean
	 */
	private function operate($object,$prizeID){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$wl_id=false;
			$lm_update=LotteryMember::model()->updateByPk($object->id, array('lottery_type'=>1));
			//添加中奖记录
			if (!empty($lm_update)){
				$model=new WinningLog();
				$model->lottery_member_id=$object->id;
				$model->prize_list_id=$prizeID;
				$model->create_time=time();
				$wl_id=$model->save();
			}
			
			if (!empty($wl_id)){
				PrizeList::model()->updateByPk($prizeID, array('state'=>0));
				$transaction->commit ();
				return true;
			}else {
				$transaction->rollback ();
				return false;
			}
			
		}catch (Exception $e){
			$transaction->rollback ();
			return false;
		}
	}
	
}