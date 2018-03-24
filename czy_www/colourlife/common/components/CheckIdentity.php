<?php 
// check
// 新的18位身份证号码各位的含义: 
// 1-2位省、自治区、直辖市代码；    11-65
// 3-4位地级市、盟、自治州代码； 
// 5-6位县、县级市、区代码； 
// 7-14位出生年月日，比如19670401代表1967年4月1日； 
// 15-17位为顺序号，其中17位男为单数，女为双数； 
// 18位为校验码，0-9和X，由公式随机产生。 
// 举例： 
// 130503 19670401 0012这个身份证号的含义: 13为河北，05为邢台，03为桥西区，出生日期为1967年4月1日，顺序号为001，2为验证码。 


// 15位身份证号码各位的含义: 
// 1-2位省、自治区、直辖市代码； 
// 3-4位地级市、盟、自治州代码； 
// 5-6位县、县级市、区代码； 
// 7-12位出生年月日,比如670401代表1967年4月1日,这是和18位号码的第一个区别； 
// 13-15位为顺序号，其中15位男为单数，女为双数； 
// 与18位身份证号的第二个区别：没有最后一位的验证码。 
// 举例：
// 130503 670401 001的含义; 13为河北，05为邢台，03为桥西区，出生日期为1967年4月1日，顺序号为001。

class CheckIdentity{
    // $num为身份证号码，$checkSex：1为男，2为女，不输入为不验证
    static public function checkCode($num,$checkSex=''){
        // 不是15位或不是18位都是无效身份证号
        if(strlen($num) != 15 && strlen($num) != 18){
            return false;
        }
        // 是数值
        if(is_numeric($num)){
            // 如果是15位身份证号
            if(strlen($num) == 15 ){
                // 省市县（6位）
                $areaNum = substr($num,0,6);
                // 出生年月（6位）
                $dateNum = substr($num,6,6);
                // 性别（3位）
                $sexNum = substr($num,12,3);
            }else{
            // 如果是18位身份证号
                // 省市县（6位）
                $areaNum = substr($num,0,6);
                // 出生年月（8位）
                $dateNum = substr($num,6,8);
                // 性别（3位）
                $sexNum = substr($num,14,3);
                // 校验码（1位）
                $endNum = substr($num,17,1);
            }
        }else{
        // 不是数值
            if(strlen($num) == 15){
                return false;
            }else{
                // 验证前17位为数值，且18位为字符x
                $check17 = substr($num,0,17);
                if(!is_numeric($check17)){
                    return false;
                }
                // 省市县（6位）
                $areaNum = substr($num,0,6);
                // 出生年月（8位）
                $dateNum = substr($num,6,8);
                // 性别（3位）
                $sexNum = substr($num,14,3);
                // 校验码（1位）
                $endNum = substr($num,17,1);
                if($endNum != 'x' && $endNum != 'X'){
                    return false;
                }
            }
        }

        if(isset($areaNum)){
            if(!self::checkArea($areaNum)){
                return false;
            }
        }

        if(isset($dateNum)){
            if(!self::checkDate($dateNum)){
                return false;
            }
        }

        // 性别1为男，2为女
        if($checkSex == 1){
            if(isset($sexNum)){
                if(!self::checkSex($sexNum)){
                    return false;
                }
            }
        }else if($checkSex == 2){
            if(isset($sexNum)){
                if(self::checkSex($sexNum)){
                    return false;
                }
            }
        }

        if(isset($endNum)){
            if(!self::checkEnd($endNum,$num)){
                return false;
            }
        }
        return true;
    }

    // 验证城市
    static private function checkArea($area){
        $num1 = substr($area,0,2);
        $num2 = substr($area,2,2);
        $num3 = substr($area,4,2);
        // 根据GB/T2260—999，省市代码11到65
        if(10 < $num1 && $num1 < 66){
            return true;
        }else{
            return false;
        }
        //============
        // 对市 区进行验证
        //============
    }

    // 验证出生日期
    static private function checkDate($date){
        if(strlen($date) == 6){
            $date1 = substr($date,0,2);
            $date2 = substr($date,2,2);
            $date3 = substr($date,4,2);
            $statusY = self::checkY('19'.$date1);
        }else{
            $date1 = substr($date,0,4);
            $date2 = substr($date,4,2);
            $date3 = substr($date,6,2);
            $nowY = date("Y",time());
            if(1900 < $date1 && $date1 <= $nowY){
                $statusY = self::checkY($date1);
            }else{
                return false;
            }
        }
        if(0<$date2 && $date2 <13){
            if($date2 == 2){
                // 润年
                if($statusY){
                    if(0 < $date3 && $date3 <= 29){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                // 平年
                    if(0 < $date3 && $date3 <= 28){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                $maxDateNum = self::getDateNum($date2);
                if(0<$date3 && $date3 <=$maxDateNum){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    // 验证性别
    static private function checkSex($sex){
        if($sex % 2 == 0){
            return false;
        }else{
            return true;
        }
    }

    // 验证18位身份证最后一位
    static private function checkEnd($end,$num){
        $checkHou = array(1,0,'x',9,8,7,6,5,4,3,2);
        $checkGu = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        $sum = 0;
        for($i = 0;$i < 17; $i++){
            $sum += (int)$checkGu[$i] * (int)$num[$i];
        }
        $checkHouParameter= $sum % 11;
        if($checkHou[$checkHouParameter] != strtolower($num[17])){
            return false;
        }else{
            return true;
        }
    }

    // 验证平年润年，参数年份,返回 true为润年  false为平年
    static private function checkY($Y){
        if(getType($Y) == 'string'){
            $Y = (int)$Y;
        }
        if($Y % 100 == 0){
            if($Y % 400 == 0){
                return true;
            }else{
                return false;
            }
        }else if($Y % 4 ==  0){
            return true;
        }else{
            return false;
        }
    }

    // 当月天数 参数月份（不包括2月）  返回天数
    static private function getDateNum($month){
        if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12){
            return 31;
        }else if($month == 2){
        }else{
            return 30;
        }
    }

}




