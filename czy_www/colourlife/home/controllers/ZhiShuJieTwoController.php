<?php
/*
 * @version 植树节活动2期
 */
class ZhiShuJieTwoController extends ActivityController{
    public $beginTime='2016-05-10';//活动开始时间
    public $endTime='2016-07-29 23:59:59';//活动结束时间
    public $secret = '@&Tree*Two^%';
    public $layout = false;
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,OtherJiaoShui,Rule,Share',
        );
    }

    public function accessRules()
    {
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
        ZhiShuJieTwo::model()->addShareLog($this->getUserId(),'',1);  //记录分享点击时间
        $result=  ZhiShuJieTwo::model()->isGetZhongZi($this->getUserId());
        if($result){
            $res=ZhiShuJieTwo::model()->getZhongZi($this->getUserId());
            if(!$res){
                exit('获取种子失败，请刷新页面重试');
            }
        }
        $resCount=0;
        $res2Count=0;
        $res3Count=0;
        $NCUrl='';
        //是否是彩富人生用户(是否弹框牛奶值)
        $isCaiFuUser=ZhiShuJieTwo::model()->isCaiFuUser($this->getUserId());
			// 是否是彩富人生用户(是否弹框增长值)
		$isNoCaiFuUser = ZhiShuJieTwo::model ()->isNoCaiFuUser ( $this->getUserId () );
		// 星辰下单数量
		$res = ZhiShuJieTwo::model ()->getXingChenOrderTan ( $this->getUserId () );
		if (! empty ( $res )) {
			$resCount = count ( $res );
		}
		// 太平洋保险下单数量
		$res2 = ZhiShuJieTwo::model ()->getTaiPingYangOrderTan ( $this->getUserId () );
		if (! empty ( $res2 )) {
			$res2Count = count ( $res2 );
		}
		// E家政下单数量
		$res3 = ZhiShuJieTwo::model ()->getEJiaZhengOrderTan ( $this->getUserId () );
		if (! empty ( $res3 )) {
			$res3Count = count ( $res3 );
		}
        //邀请注册人数
        $yaoQingNum=ZhiShuJieTwo::model()->getYaoQingNum($this->getUserId());
        //总的成长值
        $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
        //总的积分
        $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
        //登录获得经验值
        $loginExperienceValue=ZhiShuJieTwo::model()->getExperienceValueBylogin($this->getUserId());
        //总的经验值
        $experienceValue=ZhiShuJieTwo::model()->getExperienceValue($this->getUserId());
        //分享链接
        $seed_id=ZhiShuJieTwo::model()->getSeedId($this->getUserId(),1);
        $time=time();
		$sign=md5('sd_id='.$seed_id.'&ts='.$time);
		$url=F::getHomeUrl('/ZhiShuJieTwo/ShareWeb').'?sd_id='.$seed_id.'&ts='.$time.'&sign='.$sign;
		if ($isNoCaiFuUser){
			$NCUrl=F::getHomeUrl('/advertisement/wealthLife').'?cust_id='.$this->getUserId();
		}
        $this->render('/v2016/arborDayphaseII/index', array(
            'isCaiFuUser'=>$isCaiFuUser,
            'isNoCaiFuUser'=>$isNoCaiFuUser,
            'resCount'=>$resCount,
            'res2Count'=>$res2Count,
            'res3Count'=>$res3Count,
            'yaoQingNum'=>$yaoQingNum,
            'growValue'=>$growValue,
            'integrationValue'=>$integrationValue,
            'loginExperienceValue'=>$loginExperienceValue,
            'experienceValue'=>$experienceValue,
            'url'=>base64_encode($url),
        	'NCUrl'=>$NCUrl
        ));
    }
    /*
     * @version 领取彩富人生成长值+10 ajax
     */
    public function actionGetCai(){
        $isCaiFuUser=ZhiShuJieTwo::model()->isCaiFuUser($this->getUserId());
        if(!$isCaiFuUser){
            return false;
        }
        $result= ZhiShuJieTwo::model()->getValueByCaiFu($this->getUserId());
        if($result){
            //总的成长值
            $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            //星辰下单数量
            $res=ZhiShuJieTwo::model()->getXingChenOrderTan($this->getUserId());
            if(!empty($res)){
                $resCount=count($res);
            }else{
                $resCount=0;
            }
            //太平洋保险下单数量
            $res2=ZhiShuJieTwo::model()->getTaiPingYangOrderTan($this->getUserId());
            if(!empty($res2)){
                $res2Count=count($res2);
            }else{
                $res2Count=0;
            }
            //E家政下单数量
            $res3=ZhiShuJieTwo::model()->getEJiaZhengOrderTan($this->getUserId());
            if(!empty($res3)){
                $res3Count=count($res3);
            }else{
                $res3Count=0;
            }
             //邀请注册人数
            $yaoQingNum=ZhiShuJieTwo::model()->getYaoQingNum($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'integrationValue'=>$integrationValue,
                'resCount'=>$resCount,
                'res2Count'=>$res2Count,
                'res3Count'=>$res3Count,
                'yaoQingNum'=>$yaoQingNum,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
        
    }
    /*
     * @version 非彩富人生用户ajax
     */
    public function actionGetNoCai(){
        $result= TreeTwoSeed::model()->updateAll(array('times' =>new CDbExpression('times+1')),"customer_id=".$this->getUserId());
        if($result){
            
            //星辰下单数量
            $res=ZhiShuJieTwo::model()->getXingChenOrderTan($this->getUserId());
            if(!empty($res)){
                $resCount=count($res);
            }else{
                $resCount=0;
            }
            //太平洋保险下单数量
            $res2=ZhiShuJieTwo::model()->getTaiPingYangOrderTan($this->getUserId());
            if(!empty($res2)){
                $res2Count=count($res2);
            }else{
                $res2Count=0;
            }
            //E家政下单数量
            $res3=ZhiShuJieTwo::model()->getEJiaZhengOrderTan($this->getUserId());
            if(!empty($res3)){
                $res3Count=count($res3);
            }else{
                $res3Count=0;
            }
             //邀请注册人数
            $yaoQingNum=ZhiShuJieTwo::model()->getYaoQingNum($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'resCount'=>$resCount,
                'res2Count'=>$res2Count,
                'res3Count'=>$res3Count,
                'yaoQingNum'=>$yaoQingNum,
                )
            );
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 星辰下单+2 ajax
     */
    public function actionXingChenOrder(){
        $result= ZhiShuJieTwo::model()->getValueByXingChenOrder($this->getUserId());
        if($result){
            //总的成长值
            $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            //太平洋保险下单数量
            $res2=ZhiShuJieTwo::model()->getTaiPingYangOrderTan($this->getUserId());
            if(!empty($res2)){
                $res2Count=count($res2);
            }else{
                $res2Count=0;
            }
            //E家政下单数量
            $res3=ZhiShuJieTwo::model()->getEJiaZhengOrderTan($this->getUserId());
            if(!empty($res3)){
                $res3Count=count($res3);
            }else{
                $res3Count=0;
            }
             //邀请注册人数
            $yaoQingNum=ZhiShuJieTwo::model()->getYaoQingNum($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'integrationValue'=>$integrationValue,
                'res2Count'=>$res2Count,
                'res3Count'=>$res3Count,
                'yaoQingNum'=>$yaoQingNum,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    /*
     * @version 太平洋保险下单+2 ajax
     */
    public function actionTaiPingYangOrder(){
        $result= ZhiShuJieTwo::model()->getValueByTaiPingYangOrder($this->getUserId());
        if($result){
            //总的成长值
            $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            //E家政下单数量
            $res3=ZhiShuJieTwo::model()->getEJiaZhengOrderTan($this->getUserId());
            if(!empty($res3)){
                $res3Count=count($res3);
            }else{
                $res3Count=0;
            }
             //邀请注册人数
            $yaoQingNum=ZhiShuJieTwo::model()->getYaoQingNum($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'integrationValue'=>$integrationValue,
                'res3Count'=>$res3Count,
                'yaoQingNum'=>$yaoQingNum,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    /*
     * @version E家政下单+2 ajax
     */
    public function actionEJiaZhengOrder(){
        $result= ZhiShuJieTwo::model()->getValueByEJiaZhengOrder($this->getUserId());
        if($result){
            //总的成长值
            $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
             //邀请注册人数
            $yaoQingNum=ZhiShuJieTwo::model()->getYaoQingNum($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'integrationValue'=>$integrationValue,
                'yaoQingNum'=>$yaoQingNum,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    
    /*
     * @verson 邀请注册+1 ajax
     */
    public function actionYaoQingResiter(){
        $result= ZhiShuJieTwo::model()->getValueByYaoQing($this->getUserId());
        if($result){
            //总的成长值
            $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'integrationValue'=>$integrationValue,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    /*
     * @version 自己浇水+1 ajax
     */
    public function actionMyJiaoShui(){
        ZhiShuJieTwo::model()->addShareLog($this->getUserId(),'',4);  //记录浇水时间
        $seed_id=ZhiShuJieTwo::model()->getSeedId($this->getUserId(),1);
        $result= ZhiShuJieTwo::model()->getValueByJiaoShui($this->getUserId(),$seed_id);
        if($result){
            //总的成长值
            $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'integrationValue'=>$integrationValue,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    /*
     * @version 点击分享按钮的+5 ajax
     */
    public function actionFenXiang(){
        ZhiShuJieTwo::model()->addShareLog($this->getUserId(),'',2);  //记录分享点击时间
        $seed_id=ZhiShuJieTwo::model()->getSeedId($this->getUserId(),1);
        $result=ZhiShuJieTwo::model()->getValueByShare($this->getUserId(),$seed_id);
        if(!empty($result)){
        	//总的成长值
        	$growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
        	//总的积分
        	$integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            echo json_encode(array('status'=>1,'integrationValue'=>$integrationValue,'growValue'=>$growValue));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'分享失败'));
        }
    }
    /*
     * @version 分享出去的页面
     */
    public function actionShareWeb(){
        $openId=0;
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }
        ZhiShuJieTwo::model()->addShareLog(0,$openId,3);  //记录分享点击时间
        $seed_id=  intval(Yii::app()->request->getParam('sd_id'));
    	$time=Yii::app()->request->getParam('ts');
    	$sign=Yii::app()->request->getParam('sign');
    	$checkSign=md5('sd_id='.$seed_id.'&ts='.$time);
    	if ($sign!=$checkSign){
    		exit ('验证失败！');
    	}
        $seedArr=TreeTwoSeed::model()->findByPk($seed_id);
        if(empty($seedArr)){
            exit('不要乱搞');
        }
        $userId=$seedArr->customer_id;
        //总的积分
        $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($userId);
        //总的成长值
        $growValue=ZhiShuJieTwo::model()->getGrowValue($userId);
        //总的经验值
        $experienceValue=ZhiShuJieTwo::model()->getExperienceValue($userId);
        $num = ZhiShuJieTwo::model ()->getWaterNum ( $seed_id, 3 );
        $this->render('/v2016/arborDayphaseII/share', array(
            'integrationValue'=>$integrationValue,
            'experienceValue'=>$experienceValue,
            'seed_id'=>$seed_id,
        	'cust_id'=>$seedArr->customer_id,
        	'validate'=>md5('cust_id='.$seedArr->customer_id.'&seed_id='.$seed_id),
        	'num'=>$num,
        	'growValue' => $growValue,
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
			$openID = 0;
		}
        ZhiShuJieTwo::model()->addShareLog(0,$openID,5);  //记录分享点击时间
		$result = ZhiShuJieTwo::model ()->getGrowValueByOtherJiao ( $seed_id, $userId, $openID );
		if ($result > 0) {
			//总的成长值
			$growValue=ZhiShuJieTwo::model()->getGrowValue($userId);
			//浇水次数
			$num = ZhiShuJieTwo::model ()->getWaterNum ( $seed_id, 3 );
			//总的积分
			$integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($userId);
			echo json_encode ( array (
					'status' => 1,
					'msg' => '帮好友浇水成功!',
					'growValue' => $growValue,
					'num' => $num,
					'integrationValue'=>$integrationValue 
			) );
		} else {
			echo json_encode ( array (
					'status' => 0,
					'msg' => $result 
			) );
		}
    }
    
    /*
     * @version 摘果实ajax
     */
    public function actionGetGuoShi(){
        $result=ZhiShuJieTwo::model()->getExperienceValueByZhai($this->getUserId());
        if($result){
            //总的成长值
            $growValue=ZhiShuJieTwo::model()->getGrowValue($this->getUserId());
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            //总的经验值
            $experienceValue=ZhiShuJieTwo::model()->getExperienceValue($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'growValue'=>$growValue,
                'integrationValue'=>$integrationValue,
                'experienceValue'=>$experienceValue,
                )
            );
        }else{
            echo json_encode(array('status'=>0,'msg'=>'摘果实失败，请重新尝试一下'));
        }
    }
    /*
     * @version 抽奖页面
     */
    public function actionGetPrize(){
        ZhiShuJieTwo::model()->addShareLog($this->getUserId(),'',6);  //记录分享点击时间
        //总的积分
        $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
        //最新的十条中奖用户
        $jiangArr=ZhiShuJieTwo::model()->getTopTenPrize($this->getUserId());
    	$this->render('/v2016/arborDayphaseII/lucky_draw', array(
            'integrationValue'=>$integrationValue,
            'jiangArr'=>$jiangArr,
        ));
    }
    /*
     * @version 抽奖按钮ajax
     */
    public function actionChouJiang(){
        $type =  Yii::app()->request->getParam('type');//积分类型1、20；2、50；3、100
        $yan=ZhiShuJieTwo::model()->yanZheng($this->getUserId(),$type);
        if($yan){
            $list=ZhiShuJieTwo::model()->getValueByChouJiang($this->getUserId(),$type);
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
        
        if(!empty($list)){
            //总的积分
            $integrationValue=ZhiShuJieTwo::model()->getIntegrationValue($this->getUserId());
            echo json_encode(array('status'=>1,'integrationValue'=>$integrationValue,'list'=>$list));//局部积分
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 获奖明细页面
     */
    public function actionPrizeDetail(){
        $prizeMobileArr=ZhiShuJieTwo::model()->getPrizeDetail($this->getUserId());
    	$this->render('/v2016/arborDayphaseII/Award_winning_single_ming', array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 活动规则页面
     */
    public function actionRule(){
        $this->render('/v2016/arborDayphaseII/rule');
    }

}
