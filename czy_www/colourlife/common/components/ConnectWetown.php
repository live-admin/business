<?php
/*
 * 连接wetown远程地址，提交正式注意正式地址与测试地址切换
 * add 2015.2.12 update:2015.3.17
 */
class ConnectWetown
{
     /*
	 * 远程连接类型 采用curl连接
	 */
    private $typeConnect = 'curl1';
	
	private $url = 'http://120.24.217.97';//http://caizhiyun.kakatool.com:8080   //正式地址
	//private $url = 'http://caizhiyun.kakatool.cn:8081';    //TODO测试地址
	
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
		       $temp = Yii::app()->request->getParam($v);
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
	public function getRemoteData($preFun = null, $param = null, $resetUrl = null, $isGetParamValue = true, $color = null){
	    //时间
		$ts = time();
		//colorid id号
		$colorid = Yii::app()->user->id;
		if(empty($colorid))
			$colorid = $color;
		if (empty($colorid)) return json_encode(array('result'=>005, 'reason'=>'请登录后再使用'));
		//key
		$key = 'test';
		//密钥
		$secret = 'test4wetown';
		//版本号
		$ve = '1.0';
		//前缀
		$preStr = '/color/v1/';
		//地址
		$url = empty($resetUrl) ? $this->url : $resetUrl;
		//取得参数
		$paramArr = $isGetParamValue ? $this->getValue($param) : $param;
		//固定参数
		$signArr = array('colorid'=>$colorid, 'key'=>$key, 'ts'=>$ts, 've'=>$ve);
		
		$signMergeArr = array_merge($signArr, $paramArr);
		ksort($signMergeArr);
		$signMergeArr = array_merge($signMergeArr, array('secret'=>$secret));
		$re = new PublicFunV23();
		//转换为url字符
		$signArrToString = $re->arrayToString($signMergeArr);
		//$signArrToString = http_build_query($signMergeArr, '', '&');
		//签名字符串
		$md5Url = $preStr . $preFun . "?{$signArrToString}";
		$sign=md5($md5Url);
		//传递参数
		unset($signMergeArr['secret']);
		$post = array('sign'=>$sign);
		$post = array_merge($post, $signMergeArr); 
		$server_url = $url . $preStr . $preFun;
        //远程curl连接
		$end = $re->contentMethod($server_url, $post);
		if($preFun == 'door/open')
		{
			Yii::import('common.services.DoorService');
			$service = new DoorService();
			try
			{
				$door_result = json_decode($end , true);
				if($door_result['result'] == 0)
				{
					$service->addRelation($colorid, $paramArr['qrcode']);
				}
			}catch (Exception $e)
			{
			}
		}
		return $end;
	}


	public function getRemoteData2($preFun = null, $param = null, $resetUrl = null, $isGetParamValue = true, $color = null){
		//时间
		$ts = time();
		//colorid id号
		$colorid = '';
		if(empty($colorid))
			$colorid = $color;
		if (empty($colorid)) return json_encode(array('result'=>005, 'reason'=>'请登录后再使用'));
		//key
		$key = 'test';
		//密钥
		$secret = 'test4wetown';
		//版本号
		$ve = '1.0';
		//前缀
		$preStr = '/color/v1/';
		//地址
		$url = empty($resetUrl) ? $this->url : $resetUrl;
		//取得参数
		$paramArr = $isGetParamValue ? $this->getValue($param) : $param;
		//固定参数
		$signArr = array('colorid'=>$colorid, 'key'=>$key, 'ts'=>$ts, 've'=>$ve);

		$signMergeArr = array_merge($signArr, $paramArr);
		ksort($signMergeArr);
		$signMergeArr = array_merge($signMergeArr, array('secret'=>$secret));
		$re = new PublicFunV23();
		//转换为url字符
		$signArrToString = $re->arrayToString($signMergeArr);
		//$signArrToString = http_build_query($signMergeArr, '', '&');
		//签名字符串
		$md5Url = $preStr . $preFun . "?{$signArrToString}";
		$sign=md5($md5Url);
		//传递参数
		unset($signMergeArr['secret']);
		$post = array('sign'=>$sign);
		$post = array_merge($post, $signMergeArr);
		$server_url = $url . $preStr . $preFun;
		//远程curl连接
		$end = $re->contentMethod($server_url, $post);
		if($preFun == 'door/open')
		{
			Yii::import('common.services.DoorService');
			$service = new DoorService();
			try
			{
				$door_result = json_decode($end , true);
				if($door_result['result'] == 0)
				{
					$service->addRelation($colorid, $paramArr['qrcode']);
				}
			}catch (Exception $e)
			{
			}
		}
		return $end;
	}

}