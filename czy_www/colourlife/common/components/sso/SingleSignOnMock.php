<?php


class SingleSignOnMock extends AbstractSingleSignOn
{
	public function RequestAuthenticateImp($openId, $accessToken, $appId, $timestamp, $sign)
	{
		return json_encode(array(
				'code' => 0,
				'message' => "请求成功",
				'content' => array(
				    'uid' => '12098',
					'username' => 'mytest',
					'realname' => 'test',
					'jobId' => '1',
					'jobName' => '测试',
					'familyId' => '1',
					'familyName' => '测试',
					'mobile' => '18900000000',
					'email' => '',
					'disable' => '1' , //0正常，1禁止2锁定
					'createtime' => ''
				)
		));
	}
}