<?php
/**
 * E维修控制器
 * @author gongzhiling
 *
 */

class EmaintenanceController extends CController {
	
	
	/**
	 * E维修首页
	 * @throws CHttpException
	 */
	public function actionIndex($page = 1, $pagesize = 10){
		if (empty($_GET['OAName'])){
			echo '参数为空！';
			exit();
		}
		
		$useroa = trim($_GET['OAName']);
		//$useroa = 'maonaiq';
		$oa_id=$this->is_employee($useroa);
		if (empty($oa_id)){
			echo '参数无效！';
			exit();
		}
		// $page = intval($page) - 1;
		// if ($page < 0) {
		//     $page = 0;
		// }
		$keyword = "零星工程,本体金";
		// $keyword = "";
		$data = ExamineMy::model()->getExamineMy($useroa, $keyword, $pagesize, $page);
		// var_dump($data);die;
		$list=array();
		if(empty($data["total"])||empty($data["data"])){
			$this->renderPartial("empty");
		}else {
			foreach ($data["data"] as $k => $_v) {
				$data["data"][$k]["detail_url"] = $this->createUrl('EshiFuDetail',array('ex_id'=>$_v['id'],'cust_id'=>$oa_id));
				$result = ExamineMy::model()->find('ex_id=:ex_id',array(':ex_id'=>$_v['id']));
				if(!$result){
					$sql="INSERT INTO `examine_my` ( `ex_id`, `create_user`, `ex_cid`, `title`, `ptitle`, `state`, `statename`, `ex_msg`, `create_time`) VALUES ('".$_v['id']."', '".$_v['creater']."', '".$_v['cid']."', '".$_v['title']."', '".$_v['ptitle']."', '".$_v['state']."', '".$_v['statename']."', '".$_v['msg']."', '".$_v['createtime']."')";
					Yii::app()->db->createCommand($sql)->execute();
				}else{
					if($result->state>3){
						$data["data"][$k]["id"] = $result->ex_id;
						$data["data"][$k]["creater"] = $result->create_user;
						$data["data"][$k]["cid"] = $result->ex_cid;
						$data["data"][$k]["title"] = $result->title;
						$data["data"][$k]["ptitle"] = $result->ptitle;
						$data["data"][$k]["state"] = $result->state;
						$data["data"][$k]["statename"] = $result->statename;
						$data["data"][$k]["msg"] = $result->ex_msg;
						$data["data"][$k]["createtime"] = $result->create_time;
					}else{
						continue;
					}
		
				}
			}
			$this->renderPartial("index",array('info'=>$data["data"]));
		}
	}
	
	/**
	 * 订单详情
	 */
	public function actionEshiFuDetail(){
		if (empty($_GET['ex_id'])||empty($_GET['cust_id'])){
			echo '参数无效！';
			exit();
		}
		$ex_id=intval($_GET['ex_id']);
		$oa_id=intval($_GET['cust_id']);
		$result = ExamineMy::model()->find('ex_id=:ex_id',array(':ex_id'=>$ex_id));
		$is_EshiFuUrl = false;
		$is_EshiFuOrder = false;
		$is_all = false;
		$eshifu_url='';
		$eorder_url='';
		if (!empty($result)){
			if ($result->state==2||$result->state==4){
				$eshifu_url=$this->EshiFuUrl($ex_id, $oa_id);
				$is_EshiFuUrl=true;
			}elseif ($result->state==6){
				$eshifu_url=$this->EshiFuOrderLook($ex_id, $oa_id);
				$is_EshiFuOrder=true;
			}elseif ($result->state==5){
				$eshifu_url=$this->EshiFuUrl($ex_id, $oa_id);
				$eorder_url=$this->EshiFuOrderLook($ex_id, $oa_id);
				$is_all=true;
			}
		}
		$this->renderPartial ( "detail", array (
				'result' => $result,
				'eshifu_url' => $eshifu_url,
				'eorder_url'=>$eorder_url,
				'is_EshiFuUrl' => $is_EshiFuUrl,
				'is_EshiFuOrder' =>$is_EshiFuOrder,
				'is_all'=>$is_all
		) );
	}
	
	
  //一键找师傅链接    
    private function EshiFuUrl($ex_id,$oa_id){
   			if(empty($ex_id)){
                return false;
            }
            $model = Employee::model()->findByPk($oa_id);
            if($model){
                $oaid = $model->username;
                $username = urlencode($model->name);
                $mobile = $model->mobile;
                $cid = $model->getBranch_id_one();
                $model = Community::model()->find('branch_id=:branch_id',array(':branch_id'=>$cid));
                if($model){
                    $ex_cid = $model->id;
                }else{
                    $ex_cid = 0;
                }
                // $url = 'http://uweb.tunnel.mobi/oacaiguanjia?oaid='.$oaid.'&username='.$username.'&mobile='.$mobile.'&cid='.$ex_cid.'&exid='.$exid;
                $url = 'http://m.eshifu.cn/oacaiguanjia?oaid='.$oaid.'&username='.$username.'&mobile='.$mobile.'&cid='.$ex_cid.'&exid='.$ex_id;
                return $url;
            }else{
                return false;
            }
    }
    
    //订单查看
    private function EshiFuOrderLook($ex_id,$oa_id){
    		if(empty($ex_id)){
    			return false;
    		}
    		$model = Employee::model()->findByPk($oa_id);
    		if($model){
    			$oaid = $model->username;
    			$username = urlencode($model->name);
    			$mobile = $model->mobile;
    			// $url = 'http://uweb.tunnel.mobi/user/oaorderlist.html?oaid='.$oaid.'&username='.$username.'&mobile='.$mobile.'&exid='.$exid;
    			$url = 'http://m.eshifu.cn/user/oaorderlist.html?oaid='.$oaid.'&username='.$username.'&mobile='.$mobile.'&exid='.$ex_id;
    			return $url;
    		}else{
    			return false;
    		}
    }
    
    /**
     * 判断是否为oa账号
     * @param unknown $uname
     * @return boolean
     */
    private function is_employee($uname=''){
    	if (empty($uname)){
    		return false;
    	}
    	$employee=Employee::model()->find("state=0 and is_deleted=0 and username=:username",array(':username'=>$uname));
    	if (!empty($employee)){
    		return $employee->id;
    	}else {
    		return false;
    	}
    }
}