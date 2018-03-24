<?php
/**
 * 水果嘉年华控制器
 * @author gongzhiling
 * @date 2015-11-27
 */
class FruitCarnivalController extends CController{
	
	public $modelName = 'TieZi';
	public $user_id;
	public $_username;
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	
	public function init()
	{
		$this->checkLogin();
	}
	
	/**
	 * 验证登录
	 */
	private function checkLogin()
	{
		if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
			exit('<h1>用户信息错误，请重新登录</h1>');
		} else {
			$custId = 0;
	
			if (isset($_REQUEST['cust_id'])) {  //优先有参数的
				$custId = intval($_REQUEST['cust_id']);
				$_SESSION['cust_id'] = $custId;
			} else if (isset($_SESSION['cust_id'])) {  //没有参数，从session中判断
				$custId = $_SESSION['cust_id'];
			}
			$custId=intval($custId);
			$customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
			if (empty($custId) || empty($customer)) {
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
			$this->user_id = $custId;
			$this->_username = $customer->nickname;
		}
	}
	/**
	 * 水果嘉年华首页
	 */
    public function actionIndex(){
        $this->renderPartial("index");
    }
    
	/**
	 * 各帖子列表页
	 */
	public function actionShow(){
		$type='';
		$dataList=array();
		$type_arr=array('tgg','qparty','cs','xyrg');
		//判断去到哪个页面
		if (!empty($_GET['type'])&&in_array($_GET['type'], $type_arr)){
			$type=$_GET['type'];
			$title='';
			switch ($type){
				case 'tgg':  //水果脱光光页面
					$title='水果脱光光';	
					//脱光光帖子列表
					$real_type=1;
					break;
				case 'qparty': //水果群party页面
					$title='水果群party';
					//群party帖子列表
					$real_type=2;
					break;
				case 'cs': //水果厨神页面
					$title='水果厨神';
					//厨神帖子列表
					$real_type=3;
					break;
				case 'xyrg': //幸运如果
					$title='幸运如果';
					//幸运如果帖子列表
					$real_type=4;
					break;
			}
			$dataList=TieZi::model()->findAll(array('order' => 'create_time DESC','condition'=>'type='.$real_type));
			$this->renderPartial("show",array('title'=>$title,'type'=>$type,'user_id'=>$this->user_id,'dataList'=>$dataList));
		}else {
			$this->renderPartial("index");
		}
	}
	
	/**
	 * 邀请好友
	 */
	public function actionInvite(){
		$this->renderPartial("invite");
	}
	
	/**
	 * 发送帖子
	 */
	public function actionEditShow(){
		$type_arr=array('tgg','qparty','cs','xyrg');
		//判断去到哪个页面
		if (!empty($_GET['type'])&&in_array($_GET['type'], $type_arr)){
			$type=$_GET['type'];
			$title='';
			$model = new TieZi();
			switch ($type){
				case 'tgg':  //水果脱光光页面
					$title='水果脱光光';
					$real_type=1;
					break;
				case 'qparty': //水果群party页面
					$title='水果群party';
					$real_type=2;
					break;
				case 'cs': //水果厨神页面
					$title='水果厨神';
					$real_type=3;
					break;
				case 'xyrg': //幸运如果
					$title='幸运如果';
					$real_type=4;
					break;
			}
			//发帖
			if (isset($_POST[$this->modelName])) {
				 $_POST[$this->modelName]['content']=FormatParam::formatGetParams($_POST[$this->modelName]['content']);
				 $_POST[$this->modelName]['user_id'] = $this->user_id;
				 $_POST[$this->modelName]['type'] = $real_type;
				 $_POST[$this->modelName]['image_url'] = $this->get_img_url();
				 $_POST[$this->modelName]['create_time'] = time();
				$model->attributes = $_POST[$this->modelName];
				if ($model->save()) {
				$this->redirect(array("Show?type=$type"));
				}
			}
			$this->renderPartial("editShow",array('model'=>$model,'title'=>$title,'type'=>$type));
		}else {
			$this->renderPartial("index");
		}
	}
	
	
	/**
	 * 获取图片路径
	 * @return string
	 */
	private function get_img_url(){
		$img_path='';
		if (Yii::app()->user->hasState('images')) {
	            $userImages = Yii::app()->user->getState('images');
	
	            //Now lets create the corresponding models and move the files
	            foreach ($userImages as $k => $image) {
	                if (is_file($image["path"])) {
	                    $img_path[] = $image["savePath"];
	                }
	            }
	            if (!empty($img_path)){
	            	$img_path=implode(";", $img_path);
	            }
	            Yii::app()->user->setState('images', null);
	    }
	    return $img_path;
	}
	
	/**
	 * 提交点赞
	 * @return string
	 */
	public function actionDianZan(){
		$data=array();
		if (empty($_POST['tzid'])||empty($_POST['type'])){
			$data['status']=0;
			$data['msg']='参数为空!';
			echo json_encode($data);
			exit();
		}
		$real_type=0;
		$type=$_POST['type'];
		$tzid=intval($_POST['tzid']);
		if ($type=='Y'){//点赞
			$real_type=2;
		}elseif ($type=='N') {
			$real_type=1; //取消
		}
		if (empty($real_type)){
			$data['status']=0;
			$data['msg']='提交非法参数!';
			echo json_encode($data);
			exit();
		}
		//判断是否有存在数据，已存在
		$dianzan=DianZan::model()->find("tie_zi_id=:tie_zi_id and user_id=:user_id",array(':tie_zi_id'=>$tzid,':user_id'=>$this->user_id));
		if (empty($dianzan)){
			$model=new DianZan();
			$model->user_id=$this->user_id;
			$model->tie_zi_id=$tzid;
			$model->is_praised=$real_type;
			$model->create_time=time();
			$model->update_time=time();
			if ($model->save()){
				$data['status']=1;
				$data['msg']='点赞成功';
				$data['userID']=$this->user_id;
				//$customer=Customer::model()->findByPk($this->user_id);
				$data['username']=$this->_username;
				$data['url']=F::getStaticsUrl('/home/fruitCarnival/');
			}else {
				$data['status']=0;
				$data['msg']='点赞失败';
			}
		}else {
			if ($dianzan->is_praised!=$real_type){
				$result=DianZan::model()->updateByPk($dianzan->id,array('is_praised'=>$real_type,'update_time'=>time()));
				if ($result>0){
					$data['status']=1;
					$data['msg']='点赞成功';
					$data['userID']=$this->user_id;
					//$customer=Customer::model()->findByPk($this->user_id);
					$data['username']=$this->_username;
					$data['url']=F::getStaticsUrl('/home/fruitCarnival/');	
				}else {
					$data['status']=0;
					$data['msg']='点赞失败';
				}
			}else {
				$data['status']=0;
				$data['msg']='操作失败';
			}
		}
		echo json_encode($data);
	}
	
	/**
	 * 提交评论
	 */
	public function actionComment(){
		$data=array();
		if (empty($_POST['tzid'])||empty($_POST['content'])){
			$data['status']=0;
			$data['msg']='参数为空!';
			echo json_encode($data);
			exit();
		}
		$tzid=intval($_POST['tzid']);
		$content=FormatParam::formatGetParams($_POST['content']);
		$model=new PingLun();
		$model->user_id=$this->user_id;
		$model->tie_zi_id=$tzid;
		$model->content=$content;
		$model->create_time=time();
		$pid=$model->save();
		if ($pid>0){
			$data['status']=1;
			$data['msg']='评论成功';
			//$customer=Customer::model()->findByPk($this->user_id);
			$data['data']=array('pid'=>$model->id,'userID'=>$this->user_id,'username'=>$this->_username,'content'=>$content,'url'=>F::getStaticsUrl('/home/fruitCarnival/'));
		}else {
			$data['status']=0;
			$data['msg']='评论失败';
		}
		echo json_encode($data);
	}
	
	/**
	 * 删除操作
	 */
	public function actionDComment(){
		$data=array();
		if (empty($_POST['id'])||empty($_POST['type'])){
			$data['status']=0;
			$data['msg']='参数为空!';
			echo json_encode($data);
			exit();
		}
		$type=$_POST['type'];
		$id=intval($_POST['id']);
		//评论
		if ($type=='C'){
			$pinglun=PingLun::model()->find("id=:pid",array(":pid"=>$id));
			if (!empty($pinglun)){
				$count=PingLun::model()->deleteByPk($pinglun->id);
				if ($count>0){
					$criteria=new CDbCriteria();
					$criteria->compare("ping_lun_id",$pinglun->id);
					Reply::model()->deleteAll($criteria);  //删除和评论有关的回复
					$data['status']=1;
					$data['msg']='删除成功';
				}else {
					$data['status']=0;
					$data['msg']='删除失败';
				}
			}else {
				$data['status']=0;
				$data['msg']='评论不存在';
			}
		}elseif ($type=='R'){ //回复
			$reply=Reply::model()->findByPk($id);
			if (!empty($reply)){
				$count=Reply::model()->deleteByPk($reply->id);
				if ($count>0){
					$criteria=new CDbCriteria();
					$criteria->compare("to_reply_id",$reply->id);
					Reply::model()->deleteAll($criteria);  //删除和评论有关的回复
					$data['status']=1;
					$data['msg']='删除成功';
				}else {
					$data['status']=0;
					$data['msg']='删除失败';
				}
			}else {
				$data['status']=0;
				$data['msg']='回复不存在';
			}
		}
		echo json_encode($data);
	}
	
	/**
	 * 回复评论
	 */
	public function actionAddReply(){
		$data=array();
		if (empty($_POST['pid'])||empty($_POST['content'])){
			$data['status']=0;
			$data['msg']='参数为空!';
			echo json_encode($data);
			exit();
		}
		$to_userID=0;
		$to_replyID=0;
		$content=FormatParam::formatGetParams($_POST['content']);
		$pid=intval($_POST['pid']);
		if (!empty($_POST['to_uid'])){
			$to_userID=intval($_POST['to_uid']);
		}
		if (!empty($_POST['to_rid'])){
			$to_replyID=intval($_POST['to_rid']);
		}
		$model=new Reply();
		$model->user_id=$this->user_id;
		$model->ping_lun_id=$pid;
		$model->to_reply_id=$to_replyID;
		$model->to_user_id=$to_userID;
		$model->content=$content;
		$model->create_time=time();
		$rid=$model->save();
		if ($rid>0){
			$data['status']=1;
			$data['msg']='点赞成功';
			//$customer=Customer::model()->findByPk($this->user_id);
			$data['data']=array('rid'=>$model->id,'userID'=>$this->user_id,'username'=>$this->_username,'content'=>$content,'url'=>F::getStaticsUrl('/home/fruitCarnival/'));
		}else {
			$data['status']=0;
			$data['msg']='点赞失败';
		}
		echo json_encode($data);
	}
	/**
	 * 上传图片
	 */
	public function actionFileupload()
	{
		@set_time_limit(5 * 60);
		// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$targetDir = dirname(__FILE__).'/../../uploads/upload_tmp';
		$day = date('Ymd',time());
		$baseDir = dirname(__FILE__).'/../../uploads/fruitcarnival/';
		$uploadDir = $baseDir.$day;
		$savePath = '/fruitcarnival/'.$day;
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
	
		// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
		if (!file_exists($baseDir)) {
			@mkdir($baseDir);
		}
		// Create target dir
		if (!file_exists($uploadDir)) {
			@mkdir($uploadDir);
		}
	
		// Get a file name
		if (isset($_REQUEST["name"])) {
			$originalName=trim($_REQUEST["name"]);
			$fileName = time().rand(0,10000) . $_REQUEST["name"];
			$fileSize = $_FILES["file"]["size"];
		} elseif (!empty($_FILES)) {
			$originalName=trim($_FILES["file"]["name"]);
			$fileName = $_FILES["file"]["name"];
			$fileSize = $_FILES["file"]["size"];
		} else {
			$fileName = uniqid("file_");
			$fileSize = 0;
		}
		$fileName_arr=explode(".", $fileName);
		$fileName=time().'.'.$fileName_arr[1];
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
	
		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
	
		// Remove old temp files
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
	
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
					continue;
				}
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}
	
		// Open temp file
		if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
	
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
	
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {
			if (!$in = @fopen("php://input", "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}
	
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
	
		@fclose($out);
		@fclose($in);
	
		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
	
		$index = 0;
		$done = true;
		for ($index = 0; $index < $chunks; $index++) {
			if (!file_exists("{$filePath}_{$index}.part")) {
				$done = false;
				break;
			}
		}
		if ($done) {
			if (!$out = @fopen($uploadPath, "wb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
	
			if (flock($out, LOCK_EX)) {
				for ($index = 0; $index < $chunks; $index++) {
					if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
						break;
					}
	
					while ($buff = fread($in, 4096)) {
						fwrite($out, $buff);
					}
	
					//Now we need to save this path to the user's session
					if( Yii::app()->user->hasState('images')) {
						$userImages = Yii::app()->user->getState('images');
					} else {
						$userImages = array();
					}
	
					//$userImages = Yii::app()->cache->get('images_' . $token);
	
					//$userImages = $userImages ? $userImages : array();
	
					$userImages[] = array(
							"path" => $uploadDir .'/'. $fileName,
							'size' => $fileSize,
							'savePath'=>$savePath.'/'. $fileName,
							'originalName'=>$originalName
					);
					Yii::app()->user->setState( 'images', $userImages );
	
					@fclose($in);
					@unlink("{$filePath}_{$index}.part");
				}
	
				flock($out, LOCK_UN);
			}
			@fclose($out);
		}
	
		// Return Success JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}
	
	/**
	 * 删除图片
	 */
	public function actionDeleteImg(){
	
		if (Yii::app()->user->hasState('images') && isset($_GET['imgName'])) {
			$fileName = trim($_GET['imgName']);
			$data =  Yii::app()->user->getState('images');
			foreach($data as $key => $image){
				if($image['originalName'] == $fileName) {
					unset($data[$key]);
					@unlink($image['path']);
				}
			}
			Yii::app()->user->setState('images',$data);
		}
	
	}
}