<?php
/**
 *  (0<=手机号请求次数<=1天已发10次<=10天总计发送30次)&手机号不在黑名单；
 *   如1天已发30次当次请求自动忽略不发送短信；提示用户：您当天注册请求超过30次请稍后再试！
 *   如10天总计发送300次则把此手机号加入黑名单以后都不再发送；
 *   提示用户：您的手机号因重复获取短信过多已被禁用，如果不是您本人操作请联系客服！
 * @author wede
 *
 */
class BindColourLifeSms extends CValidator{
	public $mobile;
	public $allowEmpty = true;
	protected function validateAttribute($object, $attribute)
	{
		$this->mobile = $object->$attribute;
	    if(empty($this->mobile)){
	    	return ;
	    }
		
		$data = Blacklist::model()->findAll("mobile=:mobile",array(":mobile"=>$this->mobile));
		$currentTime = time();
		
		if(!empty($data)){
			$this->addError($object, $attribute,"您的手机号因重复获取短信过多已被禁用，如果不是您本人操作请联系客服！");
		}else{
			$time = strtotime(date("Y-m-d"));
			$count = SmsCount::model()->count("`mobile` = :mobile and `create_time` between :begin and :end", array(':mobile' => $this->mobile, ':begin' => $time, ':end' => $currentTime));
		
			$time = $currentTime -  864000; //十天不能超过300条
            $count1 = SmsCount::model()->count("`mobile` = :mobile and `create_time` between :begin and :end", array(':mobile' => $this->mobile, ':begin' => $time, ':end' => $currentTime));

			$time = $currentTime -  (864000*3); //30天不能超过900条
            $count2 = SmsCount::model()->count("`mobile` = :mobile and `create_time` between :begin and :end", array(':mobile' => $this->mobile, ':begin' => $time, ':end' => $currentTime));

            if($count2 >900){
				$blacklist = new Blacklist();
				$blacklist->mobile = $this->mobile;
				$blacklist->address = Yii::app()->request->userHostAddress;
				$blacklist->code_num = $count;
				$blacklist->user_agent = Yii::app()->request->userAgent;
				$blacklist->create_time = time();
				$blacklist->save();
				$this->addError($object, $attribute,
						"您的手机号因重复获取短信过多已被禁用，如果不是您本人操作请联系客服!");
			}else if($count >=30){
				$this->addError($object, $attribute, "您当天验证码请求超过30次请稍后再试！");
			}else if($count1 >300){
				$this->addError($object, $attribute, "您10天内的验证码请求超过300次请稍后再试！");
			}
		}
	}
}

