<?php


class Util
{

    public static function isDate($date)
    {
        return strtotime($date) ? true : false;
    }

    /**
     * 获取一天中的第一秒那个时间
     */
    public static function getStartTimeOfDay($date)
    {
        return strtotime(date("Y-m-d 0:0:0", strtotime($date)));
    }

    /**
     * 获取一天中退后一秒的那个时间
     *
     * @param unknown $date
     */
    public static function getEndTimeOfDay($date)
    {
        return strtotime(date("Y-m-d 23:59:59", strtotime($date)));
    }

    /**
     * 获取ID数组.
     *
     * @param mix $param
     *            单个ID，或者数组ID
     * @return array(number);
     */
    public static function toIdArray($param)
    {
        if (is_array($param))
        {
            $ids = array();
            foreach ($param as $id)
            {
                $ids[] = intval($id);
            }
            return $ids;
        } else
        {
            return array(
                intval($param)
            );
        }
    }
    
    /**
     * 判断是否是手机号码
     * @param string $mobile 
     * @return 是手机号码返回true
     */
    public static function isMobile($mobile)
    {
        return preg_match("/^1[3-9]\d{9}$/", $mobile);
    }

    public static function cleanSql($str)
    {
        return str_replace("'", "\\'", $str);
    }
    public static function getWebSecret($timestamp)
    {
    	$usrid = Yii::app()->user->id;
    	if (empty($usrid)){
    		throw new CHttpException(403, "请您先登录!"); 
    	}
    	return strtolower(md5(Yii::app()->getRequest()->getCsrfToken().$timestamp.$usrid.'false'));
    }
}