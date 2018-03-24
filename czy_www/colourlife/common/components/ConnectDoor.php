<?php
/*
 * 连接门禁服务器地址，提交正式注意正式地址与测试地址切换
 */
class ConnectDoor
{
     /*
	 * 远程连接类型 采用curl连接
	 */
    private $typeConnect = 'curl1';
	
	private $url = 'http://112.74.86.178:8001';

	public function __construct()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			//$this->url = 'http://backend.door.colourlife.cc:8001/';  //正式地址
			$this->url = 'http://112.74.86.178:8001/';  //测试地址
		} else {
			$this->url = 'http://backend.door.colourlife.cc:8001/';  //正式地址
		}
	}
	
	/*
	 * 统一得到值
	 *@var must 是否必须值
	 *@var alias 替换的key值
	 */
	private function getValue($array = null){
	    $param = array();
	    if ($array){
		   foreach($array as $arr1){
		       if (empty($arr1)) continue;
			   $v = $arr1['v'];
			   //键值为空不运行
			   if (empty($v)) continue;

			   // 新增小区uuid参数
			   if($v == 'uuid')
			   {
				   $bid = Yii::app()->request->getParam('bid');
				   $temp = $this->getCommunityUuid($bid);
			   }else{
				   $temp = Yii::app()->request->getParam($v);
			   }
			   if ($arr1['must'] == false && empty($temp)) continue;
			   else{
				   if($temp !=0 && empty($temp)){
					 echo json_encode(array('result'=>003, 'reason'=>'"'. $v . '"为必填参数不能为空'));
					  exit;
				   }
			   }
			   if (!empty($arr1['alias'])) $param[$arr1['alias']] = $temp;
			   else $param[$v] = $temp;
		   }
		}
		return $param;
	    
	}
	
	/*
	 *@var类名$preFun
	 *@var变量名$param
	 *@param $isGetParamValue 是否获取参数值 （内部调用直接传对应参数）
	 *@return 返回wetown数据
	 *例子：@md5("/color/v1/apply/apply1?&colorid={$colorid}&key={$test}&ts=$t&ve={$ve}&secret={$test4wetown}");
	 *@$lastStr = "&colorid={$colorid}&key={$key}&ts={$ts}&ve={$ve}&secret={$secret}";
	 */
	public function getRemoteData($preFun = null, $param = null, $resetUrl = null, $isGetParamValue = true, $color = null , $other_data = array()){
		if($preFun == 'apply/Approve')
		{
			$preFun = 'apply/approve';
		}
		$ts = time();
		$color_id = Yii::app()->user->id;
		if(empty($color_id))
		{
			$color_id = $color;
		}
		if (empty($color_id)) return json_encode(array('result'=>005, 'reason'=>'请登录后再使用'));
		$secret = '&dH92#G2Cadk#';
		$token = md5($color_id.$secret.$ts);
		$url = empty($resetUrl) ? $this->url : $resetUrl;
		$server_url = $url.$preFun;
		$paramArr = $isGetParamValue ? $this->getValue($param) : $param;
		if(!empty($other_data))
		{
			$paramArr = array_merge($paramArr , $other_data);
		}
		$signArr = array('colorid'=>$color_id,  'timestamp'=>$ts, 'token'=>$token);
		$signMergeArr = array_merge($signArr, $paramArr);
		$re = new PublicFunV23();
		$end = $re->contentMethod($server_url, $signMergeArr);
		$end =json_decode($end , true);
		if($end  && isset($end['code']) && isset($end['message']) && isset($end['content']))
		{
			$data =
				[
					'result' => $end['code'] ,
					'reason' =>  $end['message'],
				];
			if(!empty($end['content']))
			{
				$data =  array_merge($data , $end['content']);
			}
		}else{
			return json_encode(array('result'=>005, 'reason'=>'请求门禁服务失败'));
		}
		return json_encode($data);
	}


	public function getRemoteData2($preFun = null, $param = [], $resetUrl = null, $isGetParamValue = true, $color = null){
		$ts = time();
		$color_id = Yii::app()->user->id;
		if(empty($color_id))
		{
			$color_id = $color;
		}
		if (empty($color_id)) return json_encode(array('result'=>005, 'reason'=>'请登录后再使用'));
		$secret = '&dH92#G2Cadk#';
		$token = md5($color_id.$secret.$ts);
		$url = empty($resetUrl) ? $this->url : $resetUrl;
		$server_url = $url.$preFun;
		$paramArr = $param;
		$signArr = array('colorid'=>$color_id,  'timestamp'=>$ts, 'token'=>$token);
		$signMergeArr = array_merge($signArr, $paramArr);
		$re = new PublicFunV23();
		$end = $re->contentMethod($server_url, $signMergeArr);
		$end =json_decode($end , true);
		if($end  && isset($end['code']) && isset($end['message']) && isset($end['content']))
		{
			$data =
				[
					'result' => $end['code'] ,
					'reason' =>  $end['message'],
				];
			if(!empty($end['content']))
			{
				$data =  array_merge($data , $end['content']);
			}
		}else{
			return json_encode(array('result'=>005, 'reason'=>'请求门禁服务失败'));
		}
		return json_encode($data);
	}

	/*
	 * 根据彩之云小区id获取小区uuid
	 */
	public function getCommunityUuid($community_id)
	{
		if(!$community_id)
		{
			return null;
		}
		$cacheKey = 'cmobile:cache:community:region:door'.$community_id;
		$uuid = Yii::app()->rediscache->get($cacheKey);
		if(!$uuid)
		{
			$result = $this->getRemoteData2('community/get' , ['communityid' => $community_id] , false);
			$result =json_decode($result , true);
			if($result  && isset($result['result']) && $result['result'] == 0  && isset($result['community']) && !empty($result['community']))
			{
				$uuid =$result['community']['uuid'];
				Yii::app()->rediscache->set($cacheKey, $uuid, 600);
			}else{
				throw new CHttpException(400, '请求门禁服务失败');
			}
		}
		return $uuid;
	}

}