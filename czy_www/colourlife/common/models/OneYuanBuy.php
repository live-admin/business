<?php
/*
 * 一元购码相关
 * @add 2015.3.23 update: 2015.03
 */
class OneYuanBuy extends CActiveRecord {

    public $modelName = '一元购码';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'one_yuan_code';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                //array('title, content, user_id, community_id, cate_id', 'required'),
                //array('user_id, community_id, cate_id, favour_num, comment_num, status,parent_status', 'numerical', 'integerOnly'=>true),
                //array('title, create_time, update_time', 'length', 'max'=>100),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                //array('id, title, content, user_id, parent_id,parent_status,community_id, create_time, update_time, cate_id, favour_num, comment_num, status', 'safe', 'on'=>'search'),
                //array('id,order_id,order_start_time,order_total_price,fullname,telephone,mobile,address', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
                // "sfhkOrderProducts"=>array(self::MANY_MANY,"SfhkOrderProducts",array('order_id'=>'order_id')),
                // "community"=>array(self::BELONGS_TO,"Community","community_id"),
                // "cate"=>array(self::BELONGS_TO,"TopicCategory","cate_id"),
            'litchi' => array(self::HAS_ONE, 'LitChiResult', 'code_relation_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '',
            'code' => '',
            'goods_id' => '',
            'communities_id' => '',
            'is_send' => '',
            'is_use' => '',
            'state' => '',
            'customer_id' => '',
            'send_time' => '',
            'valid_time' => '',
            'update_time' => '',
            'note' => '',
            'model' => '',
            'relation_id' => '',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Topic the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * 判决是否在小区内，或指定换购的产品
     * @param: 一行记录
     * @param:  c_id 小区ID
     * @param:  g_id  商品ID
     * @return: bool
     */
    private static function getIsInvalid($v, $gid = '', $cid = '') {
        if (!$v || !$gid || !$cid)
            return false;
        if ($v) {
            //产品相关
            $goods_id = $v->goods_id;
            //小区相关
            $communities_id = $v->communities_id;
            if ($goods_id) {
                $goods_id = explode(',', $goods_id);
                if (!in_array($gid, $goods_id))
                    return false;
            }

            if ($communities_id) {
                $communities_id = explode(',', $communities_id);
                if (!in_array($cid, $communities_id))
                    return false;
            }
        }
        return true;
    }

    /*
     * 返回数组
     * @param: 一行记录
     * @param: keySmVal是ii否键等于值
     * @return: 组装后的数组
     */
    private static function retArray($v, $keySmVal = false) {
        if (empty($v))
            return array();
        $time = time();
        $note = $v['note'];
        if ($note) {
            $note = explode(':', $note);
            $note = current($note);
        }
        //码的有限时间
        $end_time = $v->valid_time;
        if ($time <= $end_time)
            $end_time = 0;
        else
            $end_time = 1;
        if ($keySmVal != true) {
            $re = array(
                'id' => $v->id,
                'code' => $v->code,
                'is_send' => $v->is_send,
                'is_use' => $v->is_use,
                'send_time' => $v->send_time,
                'valid_time' => $v->valid_time,
                'note' => $note,
                'invalid' => $end_time //有限时间
            );
        } else {	
            $re = $v->code;
        }
        return $re;
    }

    /*
     * 得到一元购使用码
     * @param: type， 1已使用的 2末使用的 3过期的 默认为全部
     * @param: user_id，用户ID
     * @return: array
     */
    public static function getMyCode($user_id, $type = '') {
        $user_id = intval($user_id);
        $type = intval($type);
        switch ($type) {
            //已使用的
            case 1 : {
                    $re = self::model()->findAll('customer_id=:customer_id AND state=:state AND is_use=:is_use', array(':customer_id' => $user_id, ':state' => 0, ':is_use' => 1));
                    break;
                }

            //gf末使用的
            case 2 : {
                    $re = self::model()->findAll('customer_id=:customer_id AND state=:state AND is_use=:is_use', array(':customer_id' => $user_id, ':state' => 0, ':is_use' => 0));
                    break;
                }

            /*  case 3 : {
              //过期的
              $re = self::model()->findAll('customer_id=:customer_id AND state=:state AND valid_time>:time',array(':customer_id'=>$user_id,':state'=>0, ':time'=>$time));
              break;
              } */

            default: {
                    $re = self::model()->findAll('customer_id=:customer_id AND state=:state', array(':customer_id' => $user_id, ':state' => '0'));
                }
        }
        $array = array();
        if ($re) {
            foreach ($re as $k => $v) {
                $array[] = self::retArray($v);
            }
        }
        return $array;
    }

    /*
     * 查询一元购使用码
     * @param: user_id  用户ID
     * @param:  c_id 小区ID
     * @param:  g_id  商品ID
     * @return: 返回值：如果有使用码，则返回使用码列表，否则返回一个空数组
     */
    static function searchCode($user_id, $c_id, $g_id) {
        $user_id = intval($user_id);
        $c_id = intval($c_id);
        $g_id = intval($g_id);
        if (empty($user_id) || empty($c_id) || empty($g_id)) {
            return array();
        }
        //return array('sn'=>'sn123456789','user_id'=>$user_id, 'c_id'=>$c_id, 'g_id'=>$g_id);
        $re = self::model()->findAll('customer_id=:customer_id AND state=:state AND is_use=:is_use', array(':customer_id' => $user_id, ':state' => 0, ':is_use' => 0));
        $array = array();
        if ($re) {
            foreach ($re as $k => $v) {
                if (!self::getIsInvalid($v, $g_id, $c_id))
                    continue;
                $array[$v->code] = self::retArray($v, true);
                //键等于什返回, true
            }
        }
        return $array;
    }

    /*
     * 验证一元购使用码
     * @param: code，一元购使用码
     * @param: user_id，用户ID
     * @param: c_id,小区ID
     * @param: g_id，商品ID
     * @return: 返回值：bool
     */
    public static function checkCode($code, $user_id, $c_id, $g_id) {
        $user_id = intval($user_id);
        $c_id = intval($c_id);
        $g_id = intval($g_id);
        if ($code && !empty($user_id) && !empty($c_id) && !empty($g_id)) {
            $re = self::model()->findAll('customer_id=:customer_id AND state=:state AND is_use=:is_use', array(':customer_id' => $user_id, ':state' => 0, ':is_use' => 0));
            if ($re) {
                foreach ($re as $k => $v) {
                    if (!self::getIsInvalid($v, $g_id, $c_id))
                        continue;
                    //判断是否所性小区
                    if ($v->code == $code)
                        return true;
                }
            }
        }
        return false;
    }

    /*
     * 使用一元购使用码
     * @param: code，一元购使用码
     * @param: user_id，用户ID
     * @param: c_id,小区ID
     * @param: g_id，商品ID
     * @return: 返回值：bool
     */
    public static function useCode($code, $user_id, $c_id, $g_id, $sn) {
        $user_id = intval($user_id);
        $c_id = intval($c_id);
        $g_id = intval($g_id);
        if ($sn && $code && !empty($user_id) && !empty($c_id) && !empty($g_id)) {
            $re = self::model()->findAll('customer_id=:customer_id AND state=:state AND is_use=:is_use', array(':customer_id' => $user_id, ':state' => 0, ':is_use' => 0));
            if ($re) {
                foreach ($re as $k => $v) {
                    //判断是否所性小区
                    if (self::getIsInvalid($v, $g_id, $c_id) && $v->code == $code) {
                        //更改数据库
                        $re = self::model()->find('id=:id', array(':id' => $v->id));
                        $re->is_use = 1;
                        // $re->is_send = 1;
                        $re->update_time = time();
                        $re->note = $sn . ':' . $g_id . ':' . time();
                        if ($re->update())
                            return true;
                    }
                }
            }
        }
        return false;
    }

    /*
     * 发放一元购码
     * @param:  custid 用户ID
     * @param:  type 发放类型
     * @param:  rid 关联ID
     * @return: bool
     */
    public static function sendCode($custid, $goods_id = 0, $communities_id = 0, $type = 'register', $rid = 0, $valid_time=0) {
        $onecode = OneYuanBuy::model()->find('is_send=:is_send', array(':is_send' => 0));
        if (!$onecode) {
            Yii::log("一元购码使用完毕,需要增加一元购码的数量。", CLogger::LEVEL_ERROR);
            return false;
        }

        $valid_time = $valid_time ===0 ? strtotime("+1 month") : $valid_time;

        $onecode->is_send = 1;
        $onecode->customer_id = $custid;
        $onecode->send_time = time();
        $onecode->valid_time = $valid_time;
        $onecode->goods_id = $goods_id;
        $onecode->communities_id = $communities_id;
        $onecode->model = $type;
        $onecode->relation_id = $rid;
        if (!$onecode->update()) {
            Yii::log("发放一元购码错误", CLogger::LEVEL_ERROR);
            return false;
        }
        return true;
    }

    /*
     * 发放一元购码
     * @param:  custid 用户ID
     * @param:  type 发放类型
     * @param:  rid 关联ID
     * @return: bool
     */
    public static function sendCodeLucky($custid, $goods_id = 0, $communities_id = 0, $type = 'register', $rid = 0) {
        $onecode = OneYuanBuy::model()->find('is_send=:is_send', array(':is_send' => 0));
        if (!$onecode) {
            Yii::log("一元购码使用完毕,需要增加一元购码的数量。", CLogger::LEVEL_ERROR);
            return false;
        }
        $onecode->is_send = 1;
        $onecode->customer_id = $custid;
        $onecode->send_time = time();
        $onecode->valid_time = strtotime("+1 month");
        $onecode->goods_id = $goods_id;
        $onecode->communities_id = $communities_id;
        $onecode->model = $type;
        $onecode->relation_id = $rid;
        if (!$onecode->update()) {
            Yii::log("发放一元购码错误", CLogger::LEVEL_ERROR);
            return false;
        }
        return $onecode->code;
    }

    /*
     * 发放一元购码
     * @param:  custid 用户ID
     * @param:  type 发放类型
     * @param:  rid 关联ID
     * @return: bool
     */
    public static function sendCodeLuckyForRobRiceDumplings($custid, $goods_id = 2365, $communities_id = 0, $type = 'rob_riceDumpings', $rid = 0) {
        $onecode = OneYuanBuy::model()->find('is_send=:is_send', array(':is_send' => 0));
        if (!$onecode) {
            Yii::log("一元购码使用完毕,需要增加一元购码的数量。", CLogger::LEVEL_ERROR);
            return false;
        }
        $onecode->is_send = 1;
        $onecode->customer_id = $custid;
        $onecode->send_time = time();
        $onecode->valid_time = strtotime("+1 month");
        $onecode->goods_id = $goods_id;
        $onecode->communities_id = $communities_id;
        $onecode->model = $type;
        $onecode->relation_id = $rid;
        if (!$onecode->update()) {
            Yii::log("发放一元购码错误", CLogger::LEVEL_ERROR);
            return false;
        }
        return $onecode->id;
    }





     /*
     * 免费抢 发放荔枝一元购码
     * @param:  custid 用户ID
     * @param:  type 发放类型
     * @param:  rid 关联ID
     * @return: bool
     */
    public static function sendCodeForRobLitChi($custid, $goods_id = 6936, $communities_id = 0, $type = 'rob_litchi', $rid = 0) {
        $onecode = OneYuanBuy::model()->find('is_send=:is_send', array(':is_send' => 0));
        if (!$onecode) {
            Yii::log("一元购码使用完毕,需要增加一元购码的数量。", CLogger::LEVEL_ERROR);
            return false;
        }
        $onecode->is_send = 1;
        $onecode->customer_id = $custid;
        $onecode->send_time = time();
        $onecode->valid_time = '1435247999';
        $onecode->goods_id = $goods_id;
        $onecode->communities_id = $communities_id;
        $onecode->model = $type;
        $onecode->relation_id = $rid;
        if (!$onecode->update()) {
            Yii::log("发放一元购码错误", CLogger::LEVEL_ERROR);
            return false;
        }
        return $onecode->id;
    }




    /*
     * E理财指定投资发放荔枝一元购码
     * @param:  custid 用户ID
     * @param:  type 发放类型
     * @param:  rid 关联ID
     * @return: bool
     */
    public static function sendCodeForEliCai($custid, $goods_id = 6938, $communities_id = 0, $type = 'elicai_ticheng', $rid = 0) {
        $onecode = OneYuanBuy::model()->find('is_send=:is_send', array(':is_send' => 0));
        if (!$onecode) {
            Yii::log("一元购码使用完毕,需要增加一元购码的数量。", CLogger::LEVEL_ERROR);
            return false;
        }
        $onecode->is_send = 1;
        $onecode->customer_id = $custid;
        $onecode->send_time = time();
        $onecode->valid_time = '1435247999';
        $onecode->goods_id = $goods_id;
        $onecode->communities_id = $communities_id;
        $onecode->model = $type;
        $onecode->relation_id = $rid;
        if (!$onecode->update()) {
            Yii::log("发放一元购码错误", CLogger::LEVEL_ERROR);
            return false;
        }
        return $onecode->id;
    }


    /*
     * 指定时间注册彩之云发放荔枝一元购码
     * @param:  custid 用户ID
     * @param:  type 发放类型
     * @param:  rid 关联ID
     * @return: bool
     */
    public static function sendCodeForNewCustomer($custid, $goods_id = 6937, $communities_id = 0, $type = 'litchi_reg', $rid = 0) {
        $onecode = OneYuanBuy::model()->find('is_send=:is_send', array(':is_send' => 0));
        if (!$onecode) {
            Yii::log("一元购码使用完毕,需要增加一元购码的数量。", CLogger::LEVEL_ERROR);
            return false;
        }
        $onecode->is_send = 1;
        $onecode->customer_id = $custid;
        $onecode->send_time = time();
        $onecode->valid_time = '1435247999';
        $onecode->goods_id = $goods_id;
        $onecode->communities_id = $communities_id;
        $onecode->model = $type;
        $onecode->relation_id = $rid;
        if (!$onecode->update()) {
            Yii::log("发放一元购码错误", CLogger::LEVEL_ERROR);
            return false;
        }
        return $onecode->id;
    }


    /*
     * 成功预存彩富人生送2斤荔枝一元购码
     * @param:  custid 用户ID
     * @param:  type 发放类型
     * @param:  rid 关联ID
     * @return: bool
     */
    public static function sendCodeForPropertyActivity($custid, $goods_id = 6938, $communities_id = 0, $type = 'litchi_caifu', $rid = 0) {
        $onecode = OneYuanBuy::model()->find('is_send=:is_send', array(':is_send' => 0));
        if (!$onecode) {
            Yii::log("一元购码使用完毕,需要增加一元购码的数量。", CLogger::LEVEL_ERROR);
            return false;
        }
        $onecode->is_send = 1;
        $onecode->customer_id = $custid;
        $onecode->send_time = time();
        $onecode->valid_time = '1435247999';
        $onecode->goods_id = $goods_id;
        $onecode->communities_id = $communities_id;
        $onecode->model = $type;
        $onecode->relation_id = $rid;
        if (!$onecode->update()) {
            Yii::log("发放一元购码错误", CLogger::LEVEL_ERROR);
            return false;
        }
        return $onecode->id;
    }
    

}
