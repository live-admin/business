<?php

//require dirname(__FILE__).'/../models/Config.php';
/*
 * Usage:
 * $options = array(
     'client_id'  => 'xxx',   //你的信息
     'client_secret' => 'xxx',//你的信息
     'org_name' => '123' ,//你的信息
     'app_name' => '123' ,//你的信息
     );

	$e = new ApiEasemobService($options);

	$groupInfo = array(
  	  'groupname' => 'leeef',
   	 'desc'       => 'leeff',
   	 'owner' => 'sy1'
	);

 	$result = $e->getUserGroups('80983543');
 	$result = $e->getGroupList();
	$result = $e->getGroupDetial("1423734662380237");
	echo "<pre>";
	print_r($result);
	print_r($e->getToken());
*/



/*
 * --------------强调说明-------------
 * 参数 数字int 最好填 String 如groupId 1423734662380237 ,传参时传getGroupDetial("1423734662380237");
 */

/**
 * 环信-服务器端REST API
 * @author    Ender
 */
class ApiEasemobService  {

	private $host='https://a1.easemob.com';

	private $options= array(
		'client_id'  => 'YXA6nq3UoNxDEeWDz73i_oj-MQ',
		'client_secret' => 'YXA63rkt79Bhplc-MCrPjbcgpKrm0JU',
		'org_name' => 'caishenhuo' ,//企业名称
		'app_name' => 'colourlife' ,//应用名称
	);

	public function __construct($options) {

		if($options&&isset($options['client_id'])){
			$this->options = $options;
		}

		$this->host = $this->host.'/'.$this->options['org_name'].'/'.$this->options['app_name'].'/';

	}

	/**
	 * 获取app管理员token【7天有效，必须缓存下来，多次访问会被屏蔽】
	 * POST /{caishenhuo}/{colourlife}/token
	 */
	public function getToken()
	{
		$token = $this->getTokenFromDB();
		if($token){
			return $token;
		}

		$url=$this->host."token";
		$body=array(
			"grant_type"=>"client_credentials",
			"client_id"=>$this->options['client_id'],
			"client_secret"=>$this->options['client_secret'],
		);
		$patoken=json_encode($body);
		$res = $this->postCurl($url,$patoken);
		$tokenResult =  json_decode($res, true);
		if($tokenResult&&isset($tokenResult['access_token'])){
			$this->saveTokenToDB($tokenResult);
			return "Authorization:Bearer ". $tokenResult["access_token"];
		}

//		var_dump($tokenResult);
		return "Authorization:Bearer ";
	}

	/**
	 * 从数据库查询
	 * @return bool
	 */
	public function getTokenFromDB(){

		$config = Config::model()->find('`key`=:key', array(':key' => 'ApiEasemobService'));
//		var_dump($config);
		if($config){
			$data = $config->getVal();
			if($data&&is_array($data)){

				if(isset($data['access_token'])&&isset($data['expires_in'])){
					$access_token=$data['access_token'];
					$now = time();
					$expire = (int)$data['expires_in'];

					if($expire>$now){
						 return "Authorization:Bearer ". $access_token;
					}

				}
			}
		}

		return FALSE;

	}

	/**
	 * 保存access_token到数据库
	 * @param array $data
	 */
	public function saveTokenToDB($param =array()){

		if(isset($param['access_token'])&&isset($param['expires_in'])){
			$data = array(
				'access_token'=>$param['access_token'],
				'expires_in' => intval($param['expires_in'])+time(),
				'application'=>$param['application'],
			);

			$config = Config::model()->find('`key`=:key', array(':key' => 'ApiEasemobService'));
			if($config){
				$config->update_time = time();
				$config->setVal($data);
			}
			else{
				$config = new Config();
				$config->key = 'ApiEasemobService';
				$config->name='环信token';
				$config->type='ApiEasemobService';
				$config->update_time = time();
				$config->setVal($data);
			}
			$config->save();
		}
	}


	/**
	 * 授权注册模式 POST /{dihon}/{loveofgod}/users
	 */
	public  function accreditRegister($nikename,$pwd)
	{
		$url=$this->host."users";
		$body=array(
			"username"=>$nikename,
			"password"=>$pwd,
		);
		$patoken=json_encode($body);
		$header = array($this->getToken());
		//$res = postCurl($url,$patoken,$header);
		//开发者后台改成授权注册时，不带header不能注册成功，也就是可以无token注册
		$res = $this->postCurl($url,$patoken,$header);
		$arrayResult =  json_decode($res, true);
		return $arrayResult ;
	}
	/**
	 *重置用户密码  PUT /{dihon}/{loveofgod}/users/{username}/password
	 */
	public function editPassword($nikename,$newpwd)
	{
		$url=$this->host."users/".$nikename."/password";
		$body=array(
			"newpassword"=>$newpwd,
		);
		$patoken=json_encode($body);
		$header = array($this->getToken());
		$method = "PUT";
		$res = $this->postCurl($url,$patoken,$header,$method);
		$arrayResult =  json_decode($res, true);
		return $arrayResult ;
	}

	/**
	 *修改用户昵称  PUT /{dihon}/{loveofgod}/users/{username}
	 */
	public function editNickName($username,$nickname)
	{
		$url=$this->host."/users/".$username;
		$body=array(
			"nickname"=>$nickname,
		);
		$patoken=json_encode($body);
		$header = array($this->getToken());
		$method = "PUT";
		$res = $this->postCurl($url,$patoken,$header,$method);
		$arrayResult =  json_decode($res, true);
		return $arrayResult ;
	}

	/*
	 * 注册用户【开放注册】
	 */
	public function addUser($username,$password,$nickname){
		$url=$this->host."users/";
		$body=array(
			"username"=>$username,
			"password"=>$password,
			"nickname"=>$nickname,
		);
		$patoken=json_encode($body);
		$header = array($this->getToken());
		$method = "POST";
		$res = $this->postCurl($url,$patoken,$header,$method,CURLINFO_HTTP_CODE);
		$arrayResult =  json_decode($res, true);
		return $arrayResult;

	}

	/*
	 *删除用户 DELETE /{dihon}/{loveofgod}/users/{username}
	 */
	public function deleteUser($username)
	{
		$url=$this->host."users/".$username;
		$body=array();
		$patoken=json_encode($body);
		$header = array($this->getToken());
		$method = "DELETE";
		$res = $this->postCurl($url,$patoken,$header,$method);
		$arrayResult =  json_decode($res, true);
		return $arrayResult ;
	}

	/**
	 * 获取指定用户的详情
	 *
	 * @param $username 用户名
	 */
	public function userDetails($username) {
		$url=$this->host."users/".$username;
		$access_token=$this->getToken();
		//var_dump($access_token);
		//$header[]='Authorization: Bearer ' . $access_token;
		//注意：获取到的值中本来就有Authorization前缀了。
		$header[]=$access_token;
		$result=$this->postCurl($url,'',$header,$type='GET');
		return $result;
		/*
		$url = $this->url . "users/" . $username;
		$access_token = $this->getToken ();
		$header [] = 'Authorization: Bearer ' . $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = 'GET' );
		return $result;
		*/
	}

	/**
	 *给用户添加一个好友
	 */
	public function addFriend($owner_username,$friend_username){
		$url=$this->host."users/".$owner_username."/contacts/users/".$friend_username;
		$access_token = $this->getToken();
		$header[]=$access_token;
		$result=$this->postCurl($url,'',$header);
		return $result;
	}

	/**
	 * 查看用户的好友
	 *
	 * @param
	 * $owner_username
	 */
	public function showFriend($owner_username) {
		$url=$this->host."users/" . $owner_username . "/contacts/users/";
		$access_token = $this->getToken();
		$header[] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "GET" );
		return $result;
	}
	/**
	 * 删除好友
	 *
	 * @param
	 *          $owner_username
	 * @param
	 *          $friend_username
	 */
	public function deleteFriend($owner_username, $friend_username) {
		$url=$this->host."users/" . $owner_username . "/contacts/users/" . $friend_username;
		$access_token = $this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "DELETE" );
		return $result;
	}

	/**
	 * 查看用户是否在线
	 *
	 * @param
	 *          $username
	 */
	public function isOnline($username) {
		$url=$this->host."users/" . $username . "/status";
		$access_token =$this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "GET" );
		return $result;
	}

	/**
	 * 查看离线消息数
	 *
	 * @param
	 *          $username
	 */
	public function getOfflineMessages($username) {
		$url=$this->host."/users/" . $username . "/offline_msg_count";
		$access_token =$this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "GET" );
		return $result;
	}


//------------------聊天相关的方法

	/**
	 * 发送消息
	 *
	 * @param string $from_user
	 *          发送方用户名
	 * @param array $username
	 *          array('1','2')
	 * @param string $content
	 * @param string $target_type
	 *          默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
	 * @param array $ext
	 *          自定义参数
	 */
	public function yy_hxSend($from_user = "admin", $username, $content, $target_type = "users", $ext) {
		$option ['target_type'] = $target_type;
		$option ['target'] = $username;
		$params ['type'] = "txt";
		$params ['msg'] = $content;
		$option ['msg'] = $params;
		$option ['from'] = $from_user;
		$option ['ext'] = $ext;
		$url=$this->host."messages";
        // Request Headers :
        // {“Content-Type”:”application/json”,”Authorization”:”Bearer ${token}”}
		//$access_token = $this->getToken();
		//$access_token="Authorization:Bearer YWMtR5C9ugKUEeWF-3GovA5z7wAAAU6-Q0xnJgBx_km5NlCs-9lkSsLiNd5ttTM";
		//$header = array('Authorization:Bearer ' . $this->getToken());
		$header = array($this->getToken());
		$result = $this->postCurl($url, json_encode($option), $header);
		return $result;
	}

	/**
	 * 发送图片
	 *
	 * @param string $from_user
	 *          发送方用户名
	 * @param array $username
	 *          array('1','2')
	 * @param string $huanxin_file 上传文件成功后的环信地址
	 * @param string $target_type
	 *          默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
	 * @param array $ext
	 *          自定义参数
	 */
	public function yy_hxSendImage($from_user = "admin", $username, $huanxin_file, $target_type = "users", $ext) {
		$option ['target_type'] = $target_type;
		$option ['target'] = $username;
		$params ['type'] = "img";
		$params['url'] = $this->host."chatfiles/".$huanxin_file['uuid'];
		$params['filename']='file.jpg';
		if(isset($huanxin_file['share-secret'])){
			$params ['secret'] = $huanxin_file['share-secret'];
		}
		$params['size']=array('width'=>480,'height'=>720);
		$option ['msg'] = $params;
		$option ['from'] = $from_user;
		$option ['ext'] = $ext;
        //print_r($option);
		$url=$this->host."messages";
        /*
		$access_token = $this->getToken();
		$access_token="Authorization:Bearer YWMtR5C9ugKUEeWF-3GovA5z7wAAAU6-Q0xnJgBx_km5NlCs-9lkSsLiNd5ttTM";
		$header [] = $access_token;
         */
        // Request Headers :
        // {“Content-Type”:”application/json”,”Authorization”:”Bearer ${token}”}
		//$access_token = $this->getToken();
		//$access_token="Authorization:Bearer YWMtR5C9ugKUEeWF-3GovA5z7wAAAU6-Q0xnJgBx_km5NlCs-9lkSsLiNd5ttTM";
		//$header = array('Authorization:Bearer ' . $this->getToken());
		$header = array($this->getToken());
		$result = $this->postCurl($url, json_encode($option), $header);
		return $result;
	}

	/**
	 * 获取app中所有的群组
	 */
	public function chatGroups() {

		$url=$this->host."chatgroups";
		$access_token = $this->getToken();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "GET" );
		return $result;
	}

	/**
	 * 创建群组
	 *
	 * @param $option['groupname'] //群组名称,
	 *          此属性为必须的
	 * @param $option['desc'] //群组描述,
	 *          此属性为必须的
	 * @param $option['public'] //是否是公开群,
	 *          此属性为必须的 true or false
	 * @param $option['approval'] //加入公开群是否需要批准,
	 *          没有这个属性的话默认是true, 此属性为可选的
	 * @param $option['owner'] //群组的管理员,
	 *          此属性为必须的
	 * @param $option['members'] //群组成员,此属性为可选的
	 */
	public function createGroups($option) {
		$url=$this->host."chatgroups";
		$access_token = $this->getToken();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, $option, $header );
		return $result;
	}
	/**
	 * 获取群组详情
	 *
	 * @param
	 *          $group_id
	 */
	public function chatGroupsDetails($group_id) {
		$url=$this->host."chatgroups" . $group_id;
		$access_token = $this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "GET" );
		return $result;
	}

	/**
	 * 删除群组
	 *
	 * @param
	 *          $group_id
	 */
	public function deleteGroups($group_id) {
		$url=$this->host."chatgroups/" . $group_id;
		$access_token = $this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "DELETE" );
		return $result;
	}
	/**
	 * 获取群组成员
	 *
	 * @param
	 *          $group_id
	 */
	public function groupsUser($group_id) {
		$url=$this->host."chatgroups/" . $group_id . "/users";
		$access_token = $this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "GET" );
		return $result;
	}

	/**
	 * 群组添加成员
	 *
	 * @param
	 *          $group_id
	 * @param
	 *          $username
	 */
	public function addGroupsUser($group_id, $username) {
		$url=$this->host."chatgroups/" . $group_id . "/users/" . $username;
		$access_token = $this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "POST" );
		return $result;
	}
	/**
	 * 群组删除成员
	 *
	 * @param
	 *          $group_id
	 * @param
	 *          $username
	 */
	public function delGroupsUser($group_id, $username) {
		$url=$this->host."chatgroups/" . $group_id . "/users/" . $username;
		$access_token = $this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "DELETE" );
		return $result;
	}


	/**
	 * 聊天消息记录
	 *
	 * @param $ql 查询条件如：$ql
	 *          = "select+*+where+from='" . $uid . "'+or+to='". $uid ."'+order+by+timestamp+desc&limit=" . $limit . $cursor;
	 *          默认为order by timestamp desc
	 * @param $cursor 分页参数
	 *          默认为空
	 * @param $limit 条数
	 *          默认20
	 */
	public function chatRecord($ql = '', $cursor = '', $limit = 20) {
		$ql = ! empty ( $ql ) ? "ql=" . $ql : "order+by+timestamp+desc";
		$cursor = ! empty ( $cursor ) ? "&cursor=" . $cursor : '';
		$url=$this->host."chatmessages?" . $ql . "&limit=" . $limit . $cursor;
		$access_token = $this->getToken ();
		$header [] = $access_token;
		$result = $this->postCurl ( $url, '', $header, $type = "GET " );
		return $result;
	}

	/*
	* 上传文件：
	* @param $file 文件本地地址
	*/
	public function uploadFile($file=''){
//		$body['file']="/images/girl.jpg";
		if(!$file){
			return FALSE;
		}
		//$body['file']=$file;
		$body['file']=file_get_contents($file);
		//$option['file']="/Uploads/Lawyer/1-13040G6445U29.jpg";
		$url=$this->host."chatfiles";
		$access_token=$this->getToken();
		//$access_token="YWMttf6etvPpEeSsLisKR1j8fwAAAU5eJVuAGnvSn9EUMb1kdE8B9sTUNxjqXvA";
		$header=array('enctype:multipart/form-data',$access_token,'restrict-access:true');
		//$option=json_encode($body);
		//$option=$body;
		$result=$this->postCurl($url,$body,$header);
		//$data=json_decode($result,true);
		//print_r($result);
		//$uuid=$data['entities'][0]['uuid'];
		//return $uuid;
		return $result;
	}



//postCurl方法
	public function postCurl($url, $body, $header = array(), $method = "POST")
	{
		//array_push($header, 'Accept:application/json');
		//array_push($header, 'Content-Type:application/json');
		//array_push($header, 'http:multipart/form-data');

		$ch = curl_init();//启动一个curl会话
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); //20秒超时
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, $method, 1);

		switch ($method){
			case "GET" :
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				break;
			case "POST":
				curl_setopt($ch, CURLOPT_POST,true);
				break;
			case "PUT" :
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				break;
			case "DELETE":
				curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
		}

		curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  //原先是FALSE，可改为2
		//if (isset($body{3}) > 0) {
		if (count($body) > 0) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}
		if (count($header) > 0) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

        // 文件上传相关
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

		$ret = curl_exec($ch);
        //print_r(curl_getinfo($ch));
        //print_r($body);
        //print_r($header);
		$err = curl_error($ch);


		curl_close($ch);
		//clear_object($ch);
		//clear_object($body);
		//clear_object($header);

		if ($err) {
			return $err;
		}

		return $ret;
	}

}
?>
