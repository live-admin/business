<?php
/**
 * 刷脸活动
 * @author gongzhiling
 * @date 2016-9-20 15:39
 */

class FaceSwipingController extends ActivityController{
	public $beginTime='2016-10-10 09:00:00';//活动开始时间
	public $endTime='2016-10-30 23:59:59';//活动结束时间
	public $secret = 'fa^c%e!sw`ipi*&ng';
	public $layout = false;
	
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'Validity',
				'signAuth -Share,Rule,ShareWeb,Comment,Ranking',
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
	
	/**
	 * 首页
	 */
	public function actionIndex(){
		$customer = $this->getUserInfo();
		if (empty($customer)){
			throw new CHttpException(400, "用户不存在");
		}
		FaceSwipingScore::model()->addLog($customer->id,'',1);
		//获取用户头像
		$userPic = $this->getUserPic($customer);
		$data['picUrl'] = $userPic['picUrl'];
		$data['isFirst'] = $userPic['isFirst'];
		//评论
		$data['commentList'] = FaceSwipingScore::model()->getCommentList($customer->id);
		//颜值+排名
		$rankList = FaceSwipingScore::model()->getRanking($customer->id);
		$ranking = 0;
		$score = 0;
		if (isset($rankList[$customer->id])){
			$rank = $rankList[$customer->id];
			$ranking = $rank['rank'];
			$score = $rank['totalScore'];
		}
		$data['score'] = $score;
		$data['ranking'] = $ranking;
		//分享url
		$time=time();
		$customer_id = $customer->id * 778 + 1778;
		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
		$url=F::getHomeUrl('/FaceSwiping/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
		$this->render('/v2016/faceSwiping/index',array(
			'data' => json_encode($data),
			'surl' => base64_encode($url)
		));
	}
	
	/**
	 * 历史分数
	 */
	public function actionHistoryScore(){
		//按时间倒序出数据
		$photoModel = $this->isExistPic($this->getUserId());
		$data['totalScore'] = $photoModel->score;
		$data['historyList'] = FaceSwipingScore::model()->getHistoryScoreList($this->getUserId());
		$this->render('/v2016/faceSwiping/history',array(
			'hlist' => json_encode($data)
		));
	}
	
	/**
	 * 排行榜
	 */
	public function actionRanking(){
		$data = FaceSwipingScore::model()->getRanking($this->getUserId());
		if (!empty($data)){
			$data = array_values($data);
		}
		$this->render('/v2016/faceSwiping/ranking',array(
			'rankingList' => json_encode($data)
		));
	}
	
	/**
	 * 规则
	 */
	public function actionRule(){
		$this->render('/v2016/faceSwiping/rule');
	}
	
	/**
	 * 上传图片
	 */
	public function actionUploadPic(){
		$userID = $this->getUserId();
		if (empty($userID)){
			echo json_encode(array('status'=>0,'msg'=>'请先登录'));
			exit();
		}
		if (isset($_POST['file']) && !empty($_POST['file'])) {
			$picModel = FaceSwipingPhoto::model()->find("customer_id=:customer_id",array(':customer_id'=>$userID));
			if (empty($picModel)){
				echo json_encode(array(
						'status' => 0,
						'msg' => '非法操作！'
				));
				exit();
			}
			//保存图片
			//$file = FormatParam::formatGetParams($_POST['file']);
			$file = $_POST['file'];
			//dump($file);
			if (empty($file)){
				echo json_encode(array(
						'status' => 0,
						'msg' => '参数错误！'
				));
				exit();
			}
			$pathArr = $this->getUploadDir();
			// 获取图片
			$fileArr = explode(',', $file);
			// 判断类型
			$ext = '.jpg';
			if(strstr($fileArr[0],'image/jpeg')!==''){
				$ext = '.jpg';
			}elseif(strstr($fileArr[0],'image/gif')!==''){
				$ext = '.gif';
			}elseif(strstr($fileArr[0],'image/png')!==''){
				$ext = '.png';
			}
			$fileName = $pathArr['day'].time().rand(0,10000).$ext;
			if (!file_put_contents($pathArr['baseDir'].$fileName,base64_decode($fileArr[1]),true)){
				echo json_encode(array(
						'status' => 0,
						'msg' => '图片保存失败！'
				));
				exit();
			}
			$picModel->img = $fileName;
			$add_score = false;
			if ($picModel->is_default == 1){
				$picModel->is_default = 0;
				$add_score = true;
			}
			$picModel->update_time = time();
			if ($picModel->save()){
				if ($add_score){
					//加5分
					FaceSwipingScore::model()->addScore($userID,1);
				}
				echo json_encode(array(
						'status' => 1,
						'picUrl' => $picModel->ImgUrl
				));
			}else {
				echo json_encode(array(
						'status' => 0,
						'msg' => '上传失败'
				));
			}
        }else {
        	echo json_encode(array(
        			'status' => 0,
        			'msg' => '参数错误'
        	));
        }
		
	}
	
	/**
	 * 分享页
	 */
	public function actionShareWeb(){
		if(!isset(Yii::app()->session['wx_user']['openid']) || empty(Yii::app()->session['wx_user']['openid'])){
			throw new CHttpException(400, "请在微信客户端打开！");
		}
		$openId= Yii::app()->session['wx_user']['openid'];
		$sd_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
		$time=Yii::app()->request->getParam('ts');
		$sign=Yii::app()->request->getParam('sign');
		$checkSign=md5('sd_id='.$sd_id.'&ts='.$time);
		if ($sign!=$checkSign){
			throw new CHttpException(400, "验证失败！");
		}
		$customer_id=intval(($sd_id-1778)/778);
		FaceSwipingScore::model()->addLog($customer_id,$openId,3);
		$photoModel = $this->isExistPic($customer_id);
		if (empty($photoModel->img)){
			throw new CHttpException(404, "页面不存在！");
		}
		$data['picUrl'] = $photoModel->ImgUrl;
		$data['nickname'] = $photoModel->nickname;
		//判断是否已评论过
		$hasComment = FaceSwipingScore::model()->find("customer_id =:customer_id and open_id=:open_id",array(
				':customer_id' => $customer_id,
				':open_id' => $openId
		));
		if (!empty($hasComment)){
			$data['hasComment'] = true;
		}
		$data['sd_id'] = $sd_id;
		//评论
		$data['commentList'] = FaceSwipingScore::model()->getCommentList($customer_id,1,5,true);
		$this->render('/v2016/faceSwiping/share',array(
			'data' => json_encode($data)
		));
	}
	
	/**
	 * 提交评论
	 */
	public function actionComment(){
		if (!isset($_POST['good']) || empty($_POST['good'])){
			echo json_encode(array(
					'status' => 0,
					'msg' => '请给你的好友颜值打分！'
			));
			exit();
		}
		if (!isset($_POST['comment']) || empty($_POST['comment'])){
			echo json_encode(array(
					'status' => 0,
					'msg' => '请给你的好友颜值评论！'
			));
			exit();
		}
		if (!isset(Yii::app()->session['wx_user']['openid']) || empty(Yii::app()->session['wx_user']['openid'])){
			echo json_encode(array(
					'status' => 0,
					'msg' => '请登录！'
			));
			exit();
		}
		//$photoModel = $this->isExistPic($customer_id);
		$openId = Yii::app()->session['wx_user']['openid'];
		$sd_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
		$customer_id=intval(($sd_id-1778)/778);
		//判断是否已评论过
		$hasComment = FaceSwipingScore::model()->find("customer_id =:customer_id and open_id=:open_id",array(
				':customer_id' => $customer_id,
				':open_id' => $openId
		
		));
		if (!empty($hasComment)){
			echo json_encode(array(
					'status' => -1,
					'msg' => '已评论！'
			));
			exit();
		}
		$name = '访客';
		if (isset(Yii::app()->session['wx_user']['nickname']) && !empty(Yii::app()->session['wx_user']['nickname'])){
			$name = Yii::app()->session['wx_user']['nickname'];
		}
		$picUrl = 'http://' . STATICS_DOMAIN . '/common/images/nopic.png';
		if (isset(Yii::app()->session['wx_user']['headimgurl']) && !empty(Yii::app()->session['wx_user']['headimgurl'])){
			$picUrl = Yii::app()->session['wx_user']['headimgurl'];
		}
		$content = FormatParam::formatGetParams($_POST['comment']); //需要过滤评论内容
		if (empty($content)){
			echo json_encode(array(
					'status' => 0,
					'msg' => '请给你的好友颜值评论！'
			));
			exit();
		}
		$length = F::getStringLength($content);
		if ($length >35){
			echo json_encode(array(
					'status' => -2,
					'msg' => '评论内容过长！'
			));
			exit();
		}
		$good = intval($_POST['good']);
		$anonymous = 0;
		if (isset($_POST['anonymous'])){
			$anonymous = intval($_POST['anonymous']);
		}
		//入库
		$result=FaceSwipingScore::model()->addScore($customer_id,2,urlencode($name),$openId,urlencode($content),$good,$anonymous,$picUrl);
		if ($result >0){
			$goodNum = FaceSwipingScore::model()->count("customer_id = :customer_id and good = 1",array(
				':customer_id' => $customer_id
			));
			$badNum = FaceSwipingScore::model()->count("customer_id = :customer_id and good = 2",array(
					':customer_id' => $customer_id
			));
			echo json_encode(array(
					'status' => 1,
					'msg' => '评论成功！',
					'param' => array(
						'picUrl' => empty($anonymous) ? $picUrl : F::getStaticsUrl('/activity/v2016/faceSwiping/images/img_pro.png'),
						'name' => empty($anonymous) ? $name : '匿名',
						'goodNum' => $goodNum,
						'badNum' => $badNum
					)
			));
		}elseif ($result == -1){
			echo json_encode(array(
					'status' => 0,
					'msg' => '用户不存在！'
			));
		}elseif ($result == -2){
			echo json_encode(array(
					'status' => 0,
					'msg' => '该用户头像已删除！'
			));
		}else {
			echo json_encode(array(
					'status' => 0,
					'msg' => '评论失败！'
			));
		}
	}
	
	/**
	 * 兑换优惠券
	 */
	public function actionChange(){
		//获取用户信息
		$customer = $this->getUserInfo();
		if (empty($customer)){
			echo json_encode(array(
					'status' => 0,
					'msg' => '请先登录！'
			));
			exit();
		}
		//判断用户颜值是否达到兑换值
		$photoModel = $this->isExistPic($customer->id);
		if ($photoModel->score < 100){
			echo json_encode(array(
					'status' => 0,
					'msg' => '请先攒够颜值！'
			));
			exit();
		}
		//是否已兑换过
		$isChange = FaceSwipingChange::model()->find("customer_id=:customer_id",array(
			':customer_id' => $customer->id
		));
		if (!empty($isChange)){
			//$url = $this->getUrl($customer->id);
			echo json_encode(array(
					'status' => -1,
					//'url' => $url
			));
			exit();
		}
		//兑换优惠券
		$result = FaceSwipingScore::model()->receiveQuan($customer->id,$customer->mobile);
		if ($result >= 0){
			//$url = $this->getUrl($customer->id);
			echo json_encode(array(
					'status' => 1,
					//'url' => $url
			));
		}elseif ($result ==-1){
			echo json_encode(array(
					'status' => 0,
					'msg' => '抱歉，优惠券已被领完！'
			));
		}elseif ($result ==-2){
			$url = $this->getUrl($customer->id);
			echo json_encode(array(
					'status' => -1,
					'url' => $url
			));
		}else {
			echo json_encode(array(
					'status' => 0,
					'msg' => '操作失败！'
			));
		}
	}
	
	/**
	 * 添加日志统计 2点击分享，4点击邀请注册
	 */
	public function actionLog(){
		if (isset($_POST['type'])){
			$type = 0;
			if (intval($_POST['type']) == 2){
				$type = 2;
			}elseif (intval($_POST['type']) == 4) {
				$type = 4;
			}
			FaceSwipingScore::model()->addLog($this->getUserId(),'',$type);
		}
	}
	/**
	 * 获取京东商城链接
	 * @param unknown $userId
	 * @return boolean|multitype:string
	 */
/* 	private function getUrl($userID){
		if(empty($userID)){
			return '';
		}
		$SetableSmallLoansModel = new SetableSmallLoans();
		//京东特供67 ， 彩特供 39
		$href3 = $SetableSmallLoansModel->searchByIdAndType(67, '', $userID);
		if ($href3) {
			$tgHref = $href3->completeURL;
		}
		else {
			$tgHref = '';
		}
		return $tgHref;
	} */
	
	/**
	 * 获取更多评论
	 */
/* 	public function actionMoreList(){
		if (!isset($_POST['page'])||empty($_POST['page'])){
			echo json_encode(array(
					'status' => 0,
					'msg' => '请求出错！'
			));
			exit();
		}
		$currentPage=intval($_POST['page']);//当前页
		$offset = $currentPage-1;
		if ($offset < 0){
			echo json_encode(array(
					'status' => 0,
					'msg' => '参数错误！'
			));
			exit();
		}
		$data=FaceSwipingScore::model()->getCommentList($this->getUserId(),$currentPage);
		echo json_encode(array(
				'status' => 1,
				'commentList' => json_encode($data)
		));
	} */
	
	/**
	 * 获取用户头像
	 * @param unknown $customer_id
	 * @throws CHttpException
	 * @return string
	 */
	private function getUserPic($customer){
		if (empty($customer)){
			return '';
		}
		$isFirst = false;
		$picUrl = '';
		$picModel = FaceSwipingPhoto::model()->find("customer_id=:customer_id",array(':customer_id'=>$customer->id));
		if (empty($picModel)){
			$isFirst = true;
			$picModel = new FaceSwipingPhoto();
			$picModel->customer_id = $customer->id;
			$picModel->nickname = $customer->nickname;
			$picModel->img = $customer->portrait;
			$picModel->is_default = 1;
			$picModel->create_time = time();
			$result = $picModel->save();
			if (!empty($customer->portrait)){
				$picUrl = Yii::app()->imageFile->getUrl($customer->portrait);
			}
		}else {
			if (empty($picModel->img)){
				if (!empty($customer->portrait)){
					$picModel->img = $customer->portrait;
					$picModel->update_time = time();
					$picModel->save();
					$picUrl = Yii::app()->imageFile->getUrl($customer->portrait);
				}
			}else {
				if ($picModel->is_default == 1){
					$picUrl = Yii::app()->imageFile->getUrl($picModel->img);
				}else {
					$picUrl = Yii::app()->imageFile->getUrl('faceswiping/'.$picModel->img);
				}
			}
		}
		return array('picUrl' => $picUrl,'isFirst' => $isFirst);
	}
	
	/**
	 * 判断用户是否有参加活动
	 * @param unknown $customer_id
	 * @throws CHttpException
	 * @return array
	 */
	private function isExistPic($customer_id){
		if (empty($customer_id)){
			throw new CHttpException(400, "请先登录！");
		}
		//获取用户图片
		$photoModel = FaceSwipingPhoto::model()->find("customer_id=:customer_id",array(
				':customer_id' => $customer_id
		));
		if (empty($photoModel)){
			throw new CHttpException(404, "页面不存在！");
		}
		return $photoModel;
	}
	
	private function getUploadDir(){
        $day = date('Ymd',time()).'/';
        $baseDir = dirname(__FILE__).'/../../uploads/images/faceswiping/';
        $uploadDir = $baseDir.$day;

        if (!file_exists($baseDir)) {
            @mkdir($baseDir,0777);
        }
        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir,0777);
        }
        return array(
        		'baseDir' => $baseDir,
        		'day' => $day
        );
	}
}