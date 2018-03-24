<?php
/*
 * @version 雀神
 */
class QueShenController extends ActivityController{
    public $beginTime='2016-11-22';//活动开始时间
    public $endTime='2016-12-28 23:59:59';//活动结束时间
    public $secret = '@&QueShen*^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Share',
        );
    }
    public function accessRules(){
        return array(
        	array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }
    //首页页面
    public function actionIndex(){
        $this->CheckChampion();
        QueShen::model()->addShareLog($this->getUserId(),1);
        $checkTimesResult=QueShen::model()->checkTimes($this->getUserId(),1);
        if($checkTimesResult){
            QueShen::model()->getPiaoBySystem($this->getUserId(),1);//系统赠送
        }
        $commentDetail=QueShen::model()->showComment();
        $this->render('/v2016/queShen/index', array(
            'commentDetail'=>$commentDetail,//评论
        ));
    }
    //活动规则
    public function actionRule(){
        $this->render('/v2016/queShen/rule');
    }
    //人气排行榜
    public function actionPaiHang(){
        $listRen=QueShen::model()->showRenQi($this->getUserId());
        $this->render('/v2016/queShen/renQi', array(
            'listRen'=>$listRen,//人气排行榜
        ));
    }
    //参赛者信息界面
    public function actionSaiInfo(){
        $this->CheckChampion();
        $user_id=intval(Yii::app()->request->getParam('id'));
//        $user_id=7;
        $listOne=QueShen::model()->getInfoById($user_id,$this->getUserId());
//        dump($listOne);
        $time=time();
		$sign=md5('sd_id='.$user_id.'&ts='.$time);
		$url=F::getHomeUrl('/QueShen/ShareWeb').'?sd_id='.$user_id.'&ts='.$time.'&sign='.$sign;
        $this->render('/v2016/queShen/sai', array(
            'listOne'=>$listOne,
            'url' => base64_encode($url)
        ));
    }
    //分享页面
    public function actionShareWeb(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }
        $user_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
        $time=Yii::app()->request->getParam('ts');
    	$sign=Yii::app()->request->getParam('sign');
        $checkSign=md5('sd_id='.$user_id.'&ts='.$time);
    	if ($sign!=$checkSign){
    		exit ('验证失败！');
    	}
        $listShare=QueShen::model()->getShareInfoById($user_id);
        $this->render('/v2016/queShen/share',array(
            'listShare'=>$listShare,
       ));
    }
    //结果页面
    public function actionResult(){
        $listGuan=array();
        $listRenQiTop=array();
        $ChampionArr=QueShen::model()->getChampion();
        $topArr=QueShen::model()->getRenTop();
        $listGuan['photo']=F::getUploadsUrl("/images/" . $ChampionArr['photo']);
        $listGuan['name']=$ChampionArr['name'];
        $listGuan['brand_age']=$ChampionArr['brand_age'];
        $listGuan['pronouncement']=$ChampionArr['pronouncement'];
        $info=QueUserInfo::model()->findByPk($topArr[0]['user_id']);
        $listRenQiTop['photo']=F::getUploadsUrl("/images/" . $info['photo']);
        $listRenQiTop['name']=$info['name'];
        $listRenQiTop['brand_age']=$info['brand_age'];
        $listRenQiTop['pronouncement']=$info['pronouncement'];
        $listMobile=QueShen::model()->getMobileByGuan();
        $total=QueRenQi::model()->count('user_id=:user_id',array(':user_id'=>$ChampionArr['id']));
        $this->render('/v2016/queShen/result',array(
            'listGuan'=>$listGuan,
            'listRenQiTop'=>$listRenQiTop,
            'listMobile'=>$listMobile,
            'total'=>$total,
        ));
    }
    //提交评论控制器ajax
    public function actionComment(){
        $content = FormatParam::formatGetParams(Yii::app()->request->getParam('content')); //需要过滤评论内容
//        $content='评论一下了';
		if (empty($content)){
			echo json_encode(array(
                'status' => 0,
                'msg' => '评论内容不能为空！',
			));
			exit();
		}
		$length = F::getStringLength($content);
		if ($length >35){
			echo json_encode(array(
                'status' => 0,
                'msg' => '评论内容过长！',
			));
			exit();
		}
        $timeCheck=QueShen::model()->limitTime($this->getUserId());
        if(!$timeCheck){
            echo json_encode(array(
                'status' => 0,
                'msg' => '一分钟只能评论一次',
			));
			exit();
        }
        $res=QueShen::model()->insertComment($this->getUserId(),$content);
        if($res){
            $commentDetail=QueShen::model()->showComment();
            echo json_encode(array(
                'status' => 1,
                'msg' => '评论成功！',
                'commentDetail'=>$commentDetail,
                    
			));
        }else{
            echo json_encode(array(
                'status' => 0,
                'msg' => '评论失败！',
			));
        }
    }
    //点击猜Ta赢控制器ajax
    public function actionGuess(){
        $user_id=intval(Yii::app()->request->getParam('id'));
        $type=intval(Yii::app()->request->getParam('type'));
//        $user_id=7;
        $msg=QueShen::model()->getPiaoNum($this->getUserId());
        if($msg){
            echo json_encode(array(
					'status' => 0,
					'msg' => $msg,
                ));
            exit;
        }
        $check=QueShen::model()->checkTa($this->getUserId(),$user_id);
        $checkOther=QueShen::model()->checkTaByDate($this->getUserId(),$user_id);
        if($checkOther!=2){
            echo json_encode(array(
					'status' => 0,
					'msg' => '不在投票的时间段里！',
                ));
            exit;
        }
        if($check){
            $transaction = Yii::app()->db->beginTransaction();
            $res=QueShen::model()->getPiaoByGuess($this->getUserId(),$user_id);
            $res2=QueShen::model()->getPiaoBySystem($this->getUserId(),3);
            if($res && $res2){
                $transaction->commit();
                if($type==1){
                    $renChance=QueShen::model()->renQiChange($this->getUserId());
                }else{
                    $renChance=QueShen::model()->backPhoto($user_id,$this->getUserId());
                }
                echo json_encode(array(
					'status' => 1,
					'msg' => '为Ta投票成功！',
                    'renChance'=>$renChance,
                ));
            }else{
                $transaction->rollback();
                echo json_encode(array(
					'status' => 0,
					'msg' => '为Ta投票失败！',
                ));
            }
        }else{
            echo json_encode(array(
					'status' => 0,
					'msg' => '你已经为Ta投过票了！',
			));
        }
    }
    //查看全部支持者ajax
    public function actionShowAllSupport(){
        $user_id=intval(Yii::app()->request->getParam('id'));
        $listAll=QueShen::model()->getAllSupport($user_id,$this->getUserId());
        echo json_encode(array(
            'status' => 1,
            'listAll' => $listAll,
		));
    }
    private function checkChampion(){
        $champion=QueShen::model()->getChampion();
        if(!empty($champion)){
            $this->redirect('/QueShen/Result');
        }
    }
    
}
