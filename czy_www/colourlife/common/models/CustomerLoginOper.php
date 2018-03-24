<?php
/**
 * 用户登录情况下，触发操作
 * <br/>如：抽奖。用户记住登录状态下，不经过登录操作，此时登录操作下的送抽奖机会没有执行到。
 * <br/>采用 在每个请求下，都判断是否已经送了抽奖机会，没有则送。
 * @author leizonghua
 *
 */
class CustomerLoginOper{
	
	public static function luckyAdd(){
		//Yii::log("CustomerLoginOper==".Yii::app()->user->isGuest,CLogger::LEVEL_ERROR);
		if(! Yii::app ()->user->isGuest){ //不是未登录状态，才进行  记住登录下的 操作
			//Yii::log("isGuest is false",CLogger::LEVEL_ERROR);
			$userId=Yii::app ()->user->id;
			$userName=Yii::app ()->user->name;
			//$luckyOper->execute($paramin);
			LuckyDoAdd::login($userId, $userName);
		}else{
			Yii::log("error手机登录没有验证到已登录",CLogger::LEVEL_ERROR,'colourlife.core.lucky');
		}
	}
	
}