<?php
/*
 * @version 神奇花园2期
 */
class ZhiShuJieThreeController extends ActivityController{
    public $beginTime='2016-07-24';//活动开始时间
    public $endTime='2017-12-31 23:59:59';//活动结束时间
    public $secret = '@&Tree*Three^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,OtherJiaoShui,Rule,Share,GetGrowValueByClickOther',
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
    /*
     * @version 首页页面
     */
    public function actionIndex(){
        $userId = $this->getUserId();
        EntranceCountLog::model()->writeOperateLog($userId , '' , $operation_time=time(), 31,'');
        ZhiShuJieThree::model()->addShareLog($userId,'',1); //首页
        $isOld= $this->getByCache('isGetZhongZi:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->isGetZhongZi($userId);//新老用户
        });

        if($isOld==1){
            $checkNewOld=1;
        }
        if($isOld==2){
            $checkNewOld=2;
        }
        if($isOld==3){
            $checkNewOld=3;
        }
        if($isOld==4){
            $checkNewOld=4;
        }
        $seed_id=$this->getByCache('getSeedId:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getSeedId($userId,1);
        });
        
        if($seed_id){
            $loginExperienceValue=$this->getByCache('getExperienceValueBylogin:' . $userId, function () use ($userId) {
                return ZhiShuJieThree::model()->getExperienceValueBylogin($userId);
            });;
            $list=ZhiShuJieThree::model()->insertSeed($userId);
        }else{
            $list=ZhiShuJieThree::model()->insertSeed($userId);
            $loginExperienceValue=$this->getByCache('getExperienceValueBylogin:' . $userId, function () use ($userId) {
                return ZhiShuJieThree::model()->getExperienceValueBylogin($userId);
            });;
        }
        $listIdNew=$this->getByCache('getAllSeedIdAndNew:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getAllSeedIdAndNew($userId);
        });

        $listIdZhai=$this->getByCache('getAllSeedIdAndGuoShi:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getAllSeedIdAndGuoShi($userId);
        });

        $listIdValue=$this->getByCache('getAllSeedIdAndValue:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getAllSeedIdAndValue($userId);
        });

        $isCai=$this->getByCache('isCaiFuUser:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->isCaiFuUser($userId);
        });

        $isCaiTan=$this->getByCache('isCaiTan:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->isCaiTan($userId);
        });

        //总的经验值
//        $experienceValue=ZhiShuJieThree::model()->getExperienceValue($userId);
        //土地id
        $list_id=$this->getByCache('getAllSeedId:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getAllSeedId($userId);
        });

        //星辰旅游
//        $xingChenOrderTan=ZhiShuJieThree::model()->getXingChenOrderTan($userId,2);
        //京东下单
        $jingDongOrderTan=$this->getByCache('getJingDongOrderTan:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getJingDongOrderTan($userId,2);
        });

        //环球精选
//        $huanQiuOrderTan=ZhiShuJieThree::model()->getHuanQiuOrderTan($userId,2);
        //彩生活特供
//        $caiTeGongOrderTan=ZhiShuJieThree::model()->getCaiTeGongOrderTan($userId,2);
        //彩富推荐
//        $tuiJianOrderTan=ZhiShuJieThree::model()->getTuiJianNum($userId);
        //邀请注册
        $yaoQingOrderTan=$this->getByCache('getYaoQingNum:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getYaoQingNum($userId);
        });

        //土地等级和经验值
        $tuDiArr=$this->getByCache('getJingYanAndTuDi:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getJingYanAndTuDi($userId);
        });

        $seven=$this->getByCache('getJiFenByShengJi:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getJiFenByShengJi($userId,12);
        });

        $eight=$this->getByCache('getJiFenByShengJi:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getJiFenByShengJi($userId,13);
        });

        if($tuDiArr['num']==7 && $seven){
            ZhiShuJieThree::model()->insertIntegrationValue($userId, 50, 12,$list_id[0]);
        }
        if($tuDiArr['num']==8 && $eight){
            ZhiShuJieThree::model()->insertIntegrationValue($userId, 150, 13,$list_id[0]);
        }
        //进入首页默认的成长值
        $moRenGrowValue=$this->getByCache('getGrowValue:' . $userId, function () use ($userId, $list_id) {
                return ZhiShuJieThree::model()->getGrowValue($userId,$list_id[0]);
        });

        //分享链接
        $seedId=$this->getByCache('getSeedId:' . $userId, function () use ($userId) {
            return ZhiShuJieThree::model()->getSeedId($userId,1);
        });

        $time=time();
		$sign=md5('sd_id='.$seedId.'&ts='.$time);
		$url=F::getHomeUrl('/ZhiShuJieThree/ShareWeb').'?sd_id='.$seedId.'&ts='.$time.'&sign='.$sign;
//		dump($url);//分享链接地址
		$NCUrl=F::getHomeUrl('/advertisement/wealthLife').'?cust_id='.$userId;
        $this->render('/v2016/arborDayphaseIII/index', array(
            'checkNewOld'=>$checkNewOld,//新老用户
            'isCai'=>$isCai,//是否是彩富人生用户
            'list'=>$list,//是否弹框土地升级
            'loginExperienceValue'=>$loginExperienceValue,//登录获得经验值
//            'experienceValue'=>$experienceValue,
            'list_id'=>$list_id,
            'url'=>base64_encode($url),
            'xingChenOrderTan'=>false,
            'jingDongOrderTan'=>$jingDongOrderTan,
            'huanQiuOrderTan'=>false,
            'caiTeGongOrderTan'=>false,
            'tuiJianOrderTan'=>false,
            'yaoQingOrderTan'=>$yaoQingOrderTan,
            'tuDiArr'=>$tuDiArr,
            'moRenGrowValue'=>$moRenGrowValue,
            'listIdZhai'=>$listIdZhai,
            'listIdNew'=>$listIdNew,
            'listIdValue'=>$listIdValue,
            'NCUrl'=>$NCUrl,
            'isCaiTan'=>$isCaiTan,
        ));
    }

    public function getByCache($key, $callback, $timeout = 172800)
    {
        $cacheKey = 'home:cache:ZhiShuJieThree:' . $key;
        $data = Yii::app()->rediscache->get($cacheKey);
        if (!$data) {
            $data = $callback();
            Yii::app()->rediscache->set(
                $cacheKey,
                $data,
                $timeout
            );
        }
        return $data;
    }

    /*
     * @version 新用户ajax
     */
//    public function actionChangeNewToOld(){
//        $transaction = Yii::app()->db->beginTransaction();
//        $changeOld=ZhiShuJieThree::model()->changeIsNewOrCaiFu($this->getUserId(),1);
//        $result=ZhiShuJieThree::model()->insertZhongZi($this->getUserId());
//        if($changeOld && $result ){
//             $transaction->commit();
//            echo json_encode(array('status'=>1));
//        }else{
//            $transaction->rollback();
//            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
//        }
//    }
    /*
     * @version 老用户ajax
     */
    public function actionChangeNewToOldOther(){
        $changeOld=ZhiShuJieThree::model()->changeIsNewOrCaiFu($this->getUserId(),1);
        if($changeOld){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'系统错误,，请重试一下！!'));
        }
    }
    /*
     * @version 彩富用户的提醒ajax
     */
    public function actionChangeCaiFu(){
        $changeCaiFu=ZhiShuJieThree::model()->changeIsNewOrCaiFu($this->getUserId(),2);
        if($changeCaiFu){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'系统错误,，请重试一下！!'));
        }
    }
    /*
     * @version 星辰旅游ajax
     */
    public function actionGetXingChen(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $xingChenRes=ZhiShuJieThree::model()->getGrowValueByXingChenOrder($this->getUserId(),$seedId);
        if(!empty($xingChenRes)){
            $seedGrowValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array('status'=>1,'xingChenRes'=>$xingChenRes,'listIdValue'=>$listIdValue,'seedGrowValue'=>$seedGrowValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败，请重试一下！'));
        }
    }
    /*
     * @version 京东ajax
     */
    public function actionGetJingDong(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $jingDongRes=ZhiShuJieThree::model()->getGrowValueByJingDongOrder($this->getUserId(),$seedId);
        if(!empty($jingDongRes)){
            $seedGrowValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array('status'=>1,'jingDongRes'=>$jingDongRes,'listIdValue'=>$listIdValue,'seedGrowValue'=>$seedGrowValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败，请重试一下！'));
        }
    }
    /*
     * @version 环球精选ajax
     */
    public function actionGetHuanQiu(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $huanQiuRes=ZhiShuJieThree::model()->getGrowValueByHuanQiuOrder($this->getUserId(),$seedId);
        if(!empty($huanQiuRes)){
            $seedGrowValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array('status'=>1,'huanQiuRes'=>$huanQiuRes,'listIdValue'=>$listIdValue,'seedGrowValue'=>$seedGrowValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败，请重试一下！'));
        }
    }
    /*
     * @version 彩生活特供ajax
     */
    public function actionGetCaiTeGong(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $caiTeGongRes=ZhiShuJieThree::model()->getGrowValueByCaiTeGongOrder($this->getUserId(),$seedId);
        if(!empty($caiTeGongRes)){
            $seedGrowValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array('status'=>1,'caiTeGongRes'=>$caiTeGongRes,'listIdValue'=>$listIdValue,'seedGrowValue'=>$seedGrowValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败，请重试一下！'));
        }
    }
    /*
     * @version 邀请彩富ajax
     */
    public function actionGetCaiFuTuiJian(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $caiFuTuiJianRes=ZhiShuJieThree::model()->getGrowValueByTuiJianRen($this->getUserId(),$seedId);
        if(!empty($caiFuTuiJianRes)){
            $seedGrowValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array('status'=>1,'caiFuTuiJianRes'=>$caiFuTuiJianRes,'listIdValue'=>$listIdValue,'seedGrowValue'=>$seedGrowValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败，请重试一下！'));
        }
    }
    /*
     * @version 邀请注册ajax
     */
    public function actionGetYaoQing(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $yaoQingRes=ZhiShuJieThree::model()->getGrowValueByYaoQing($this->getUserId(),$seedId);
        if(!empty($yaoQingRes)){
            $seedGrowValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array('status'=>1,'yaoQingRes'=>$yaoQingRes,'listIdValue'=>$listIdValue,'seedGrowValue'=>$seedGrowValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败，请重试一下！'));
        }
    }
    
    /*
     * @version 摘果实ajax
     */
    public function actionGetGuoShi(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $result=ZhiShuJieThree::model()->getExperienceValueByZhai($this->getUserId(),$seedId);
        if($result){
            //总的成长值
            $growValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $zhaiCount=ZhiShuJieThree::model()->getZhaiNum($this->getUserId(),$seedId);
            if($zhaiCount%3==0 && $zhaiCount>0){
                //摘完三次后退回幼苗，成长值是0
                $res=ZhiShuJieThree::model()->insertGrowValue($this->getUserId(),-$growValue,10,$seedId);
                if($res){
                    $growValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
                }
            }
            if($zhaiCount>3){
                if($zhaiCount%3==0){
                    $zhaiCount=3;
                }else{
                    $zhaiCount=$zhaiCount%3;
                }
            }
            $isOld= ZhiShuJieThree::model()->isGetZhongZi($this->getUserId());//新老用户
            if($isOld==1){
                $checkNewOld=1;
            }
            if($isOld==2){
                $checkNewOld=2;
            }
            if($isOld==3){
                $checkNewOld=3;
            }
            if($isOld==4){
                $checkNewOld=4;
            }
            $isCaiTan=ZhiShuJieThree::model()->isCaiTan($this->getUserId());
            $list=ZhiShuJieThree::model()->insertSeed($this->getUserId());
            $listIdZhai=ZhiShuJieThree::model()->getAllSeedIdAndGuoShi($this->getUserId());
            $tuDiArr=ZhiShuJieThree::model()->getJingYanAndTuDi($this->getUserId());
            $xingChenOrderTan=ZhiShuJieThree::model()->getXingChenOrderTan($this->getUserId(),2);
            //京东下单
            $jingDongOrderTan=ZhiShuJieThree::model()->getJingDongOrderTan($this->getUserId(),2);
            //环球精选
            $huanQiuOrderTan=ZhiShuJieThree::model()->getHuanQiuOrderTan($this->getUserId(),2);
            //彩生活特供
            $caiTeGongOrderTan=ZhiShuJieThree::model()->getCaiTeGongOrderTan($this->getUserId(),2);
            //彩富推荐
            $tuiJianOrderTan=ZhiShuJieThree::model()->getTuiJianNum($this->getUserId());
            //邀请注册
            $yaoQingOrderTan=ZhiShuJieThree::model()->getYaoQingNum($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,//成长值
                'zhaiCount'=>$zhaiCount,//摘的次数
                'list'=>$list,
                'listIdZhai'=>$listIdZhai,
                'tuDiArr'=>$tuDiArr,
                'checkNewOld'=>$checkNewOld,
                'isCaiTan'=>$isCaiTan,
                'xingChenOrderTan'=>$xingChenOrderTan,
                'jingDongOrderTan'=>$jingDongOrderTan,
                'huanQiuOrderTan'=>$huanQiuOrderTan,
                'caiTeGongOrderTan'=>$caiTeGongOrderTan,
                'tuiJianOrderTan'=>$tuiJianOrderTan,
                'yaoQingOrderTan'=>$yaoQingOrderTan,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'摘果实失败，请重新尝试一下'));
        }
    }
    /*
     * @version 自己浇水+对应土地等级 ajax
     */
    public function actionMyJiaoShui(){
        ZhiShuJieThree::model()->addShareLog($this->getUserId(),'',4);//自己浇水
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=7;
        $resultNum= ZhiShuJieThree::model()->getGrowValueByJiaoShui($this->getUserId(),$seedId);
        if($resultNum){
            //总的成长值
            $growValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            //总的经验值
            $experienceValue=ZhiShuJieThree::model()->getExperienceValue($this->getUserId());
            $listIdZhai=ZhiShuJieThree::model()->getAllSeedIdAndGuoShi($this->getUserId());
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'experienceValue'=>$experienceValue,
                'resultNum'=>$resultNum,//获得成长值
                'listIdZhai'=>$listIdZhai,
                'listIdValue'=>$listIdValue,
                )
            );
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 一键浇水ajax
     */
    public function actionOneKey(){
        ZhiShuJieThree::model()->addShareLog($this->getUserId(),'',4);//自己浇水
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
        $resultNum= ZhiShuJieThree::model()->getGrowValueByOneKeyJiaoShui($this->getUserId());
        if($resultNum){
            //总的经验值
            $experienceValue=ZhiShuJieThree::model()->getExperienceValue($this->getUserId());
            $growValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
            $listIdZhai=ZhiShuJieThree::model()->getAllSeedIdAndGuoShi($this->getUserId());
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'experienceValue'=>$experienceValue,
                'resultNum'=>$resultNum,//获得成长值
                'growValue'=>$growValue,
                'listIdZhai'=>$listIdZhai,
                'listIdValue'=>$listIdValue,
                )
            );
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 分享到邻里+10 ajax
     */
    public function actionFenXiang(){
        ZhiShuJieThree::model()->addShareLog($this->getUserId(),'',2);//点击分享
//        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        
//        $seedId=7;
        $result=ZhiShuJieThree::model()->getGrowValueByShare($this->getUserId());
        if(!empty($result)){
            $listIdZhai=ZhiShuJieThree::model()->getAllSeedIdAndGuoShi($this->getUserId());
            $listIdValue=ZhiShuJieThree::model()->getAllSeedIdAndValue($this->getUserId());
            echo json_encode(array('status'=>1,'listIdZhai'=>$listIdZhai,'listIdValue'=>$listIdValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'分享失败，请重新尝试一下！'));
        }
    }
    /*
     * @version 分享到者第三方的+10 ajax
     */
//    public function actionFenXiangOther(){
//        $seedId=  intval(Yii::app()->request->getParam('seedId'));
////        $seedId=7;
//        $result=ZhiShuJieThree::model()->getGrowValueByShareOther($this->getUserId(),$seedId);
//        if(!empty($result)){
//            echo json_encode(array('status'=>1));
//        }else{
//            echo json_encode(array('status'=>0,'msg'=>'分享失败，请重新尝试一下！'));
//        }
//    }
    /*
     * @version 分享出去的页面
     */
    public function actionShareWeb(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }
        ZhiShuJieThree::model()->addShareLog(0,$openId,3);  //分享页面
        $seedId=  intval(Yii::app()->request->getParam('sd_id'));
    	$time=Yii::app()->request->getParam('ts');
    	$sign=Yii::app()->request->getParam('sign');
		
    	$checkSign=md5('sd_id='.$seedId.'&ts='.$time);
    	if ($sign!=$checkSign){
    		exit ('验证失败！');
    	}
        $seedArr=TreeTwoSeed::model()->findByPk($seedId);
        if(empty($seedArr)){
            exit('不要乱搞');
        }
        $userId=$seedArr->customer_id;
        //土地id
        $list_id=ZhiShuJieThree::model()->getAllSeedId($userId);
        //土地等级和经验值
        $tuDiArr=ZhiShuJieThree::model()->getJingYanAndTuDi($userId);
        //进入首页默认的成长值
        $moRenGrowValue=ZhiShuJieThree::model()->getGrowValue($userId,$list_id[0]);
        $num = ZhiShuJieThree::model ()->getWaterNum ( $list_id[0], 3 );
//        $num = ZhiShuJieTwo::model ()->getWaterNum ( $seedId, 3 );
        $this->render('/v2016/arborDayphaseIII/share', array(
            'tuDiArr'=>$tuDiArr,
            'seed_id'=>$seedId,
        	'cust_id'=>$userId,
        	'validate'=>md5('cust_id='.$seedArr->customer_id.'&seed_id='.$seedId),
            'list_id'=>$list_id,
            'moRenGrowValue'=>$moRenGrowValue,
            'num'=>$num,
        ));
    }
    /*
     * @version 朋友帮忙浇水每次+1 ajax
     */
    public function actionOtherJiaoShui(){
    	$seed_id=  intval(Yii::app()->request->getParam('sd_id'));
    	$userId=intval(Yii::app()->request->getParam('cid'));
    	$sign=Yii::app()->request->getParam('sign');
    	$checkSign=md5('cust_id='.$userId.'&seed_id='.$seed_id);
    	if ($sign!=$checkSign){
    		exit ('非法操作！');
    	}
		if (empty ( $seed_id )) {
			echo json_encode ( array (
					'status' => 0,
					'msg' => '参数错误！' 
			) );
			exit ();
		}
		$openID = Yii::app()->session['wx_user']['openid'];
		if (empty ( $openID )) {
			$openID = '';
		}
        $list_id=ZhiShuJieThree::model()->getAllSeedId($userId);
        ZhiShuJieThree::model()->addShareLog(0,$openID,5);  //记录分享点击时间
		$result = ZhiShuJieThree::model ()->getGrowValueByOtherJiao( $list_id[0], $userId, $openID );
		if ($result > 0) {
            $num = ZhiShuJieThree::model ()->getWaterNum ( $list_id[0], 3 );
			//总的积分
			echo json_encode ( array (
					'status' => 1,
					'msg' => '帮好友浇水成功!',
                    'growValue'=>$result,
                    'num' => $num,
			) );
		} else {
			echo json_encode ( array (
					'status' => 0,
					'msg' => $result 
			) );
		}
    }
    /*
     * @version 抽奖页面
     */
    public function actionGetPrize(){
        ZhiShuJieThree::model()->addShareLog($this->getUserId(),'',6);  //记录分享点击时间
        //总的积分
        $integrationValue=ZhiShuJieThree::model()->getIntegrationValue($this->getUserId());
        //最新的十条中奖用户
        $jiangArr=ZhiShuJieThree::model()->getTopTenPrize($this->getUserId());
    	$this->render('/v2016/arborDayphaseIII/lucky_draw', array(
            'integrationValue'=>$integrationValue,
            'jiangArr'=>$jiangArr,
        ));
    }
    /*
     * @version 抽奖按钮ajax
     */
    public function actionChouJiang(){
        $type =  Yii::app()->request->getParam('type');//积分类型1、50；2、150；3、300
        $yan=ZhiShuJieThree::model()->yanZheng($this->getUserId(),$type);
        if($yan){
            $list=ZhiShuJieThree::model()->getValueByChouJiang($this->getUserId(),$type);
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
            exit;
        }
        if(!empty($list)){
            //总的积分
            $integrationValue=ZhiShuJieThree::model()->getIntegrationValue($this->getUserId());
            echo json_encode(array('status'=>1,'integrationValue'=>$integrationValue,'list'=>$list));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 获奖明细页面
     */
    public function actionPrizeDetail(){
        $prizeMobileArr=ZhiShuJieThree::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2016/arborDayphaseIII/Award_winning_single_ming', array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 活动规则页面
     */
    public function actionRule(){
        $this->render('/v2016/arborDayphaseIII/rule');
    }
    /*
     * @version 点击每块土地促发的ajax
     */
    public function actionGetGrowValueByClick(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
//        $seedId=27;
        $list_id=ZhiShuJieThree::model()->getAllSeedId($this->getUserId());
        if(!in_array($seedId,$list_id)){
            $msg="该土地还没有升级";
            echo json_encode(array('status'=>0,'msg'=>$msg));
            exit;
        }
        $sqlUpdate="update tree_two_seed set is_click=2 where id=".$seedId." and is_click=1";
        $result=Yii::app()->db->createCommand($sqlUpdate)->execute();
        $growValue=ZhiShuJieThree::model()->getGrowValue($this->getUserId(),$seedId);
        echo json_encode(array('status'=>1,'growValue'=>$growValue));
    }
    /*
     * @version 点击每块土地促发的ajax
     */
    public function actionGetGrowValueByClickOther(){
        $seedId=  intval(Yii::app()->request->getParam('seedId'));
        $customer_id=  intval(Yii::app()->request->getParam('customer_id'));
        $list_id=ZhiShuJieThree::model()->getAllSeedId($customer_id);
        if(!in_array($seedId,$list_id)){
            $msg="该土地还没有升级";
            echo json_encode(array('status'=>0,'msg'=>$msg));
        }else{
            $growValue=ZhiShuJieThree::model()->getGrowValue($customer_id,$seedId);
            echo json_encode(array('status'=>1,'growValue'=>$growValue));
        }
    }
}
