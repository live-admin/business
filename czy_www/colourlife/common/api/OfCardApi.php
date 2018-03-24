<?php

class OfCardApi
{
    const OF_BASE_URL = 'http://api2.ofpay.com/';
    static protected $instance;
    private $userid, $userpws, $KeyStr = 'OFCARD';
    protected $url, $xml;

    static public function getInstanceWithConfig($config)
    {
        if (!isset(self::$instance))
            self::$instance = new self($config);
        return self::$instance;
    }

    public function __construct($config)
    {
        $this->userid = @$config['userid'];
        $this->userpws = @$config['userpws'];
        if (isset($config['KeyStr']))
            $this->KeyStr = @$config['KeyStr'];
    }

    /**
     * 用户信息查询接口
     *
     * 此接口可以查询到SP用户的信用点余额，可以提醒SP进行补充信用点
     *
     * @return array
     * ret_leftcredit    账户剩余金额
     */
    public function queryUserInfo()
    {
        $params = $this->getDefaultParams();
        $data = $this->call('queryuserinfo.do', $params);
        return array(
            'ret_leftcredit' => strval(@$data->ret_leftcredit),
            'userid' => strval(@$data->userid),
        );
    }

    /**
     * 查询所有商品大类
     *
     * 此接口用于查询所有商品的大类信息
     *
     * @return array
     * @throws CException
     */
    public function queryBigCard()
    {
        $data = $this->call('querybigcard.do');
        if (!isset($data->ret_cardinfos))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 ret_cardinfos 值。内容为：\"{$this->xml}\"。");
        $infos = $data->ret_cardinfos;
        if (!isset($infos->card))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 card 值。内容为：\"{$this->xml}\"。");
        $data = array();
        foreach ($infos->card as $card) {
            $data[] = array(
                'classid' => strval(@$card->classid),
                'cardname' => strval(@$card->cardname),
            );
        }
        return $data;
    }

    /**
     * 商品小类信息同步接口
     *
     * 此接口依据用户提供的商品大类编码返回此中的小类列表和小类信息（可以同步到本地，定时维护，不用每次都去查询）
     *
     * @param $cardid 需查询商品的编码（支持2位，4位编码，2位表示该大类下的所有商品，4位是具体的小类商品）
     *                (21 游戏卡密、22 游戏直充、14 移动话费、15 联通话费、19 电信话费)
     * @return array
     * @throws CException
     */
    public function queryList($cardid)
    {
        $params = $this->getDefaultParams();
        $params['cardid'] = $cardid;
        $data = $this->call('querylist.do', $params);
        if (!isset($data->ret_cardinfos))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 ret_cardinfos 值。内容为：\"{$this->xml}\"。");
        $infos = $data->ret_cardinfos;
        if (!isset($infos->card))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 card 值。内容为：\"{$this->xml}\"。");
        $data = array();
        foreach ($infos->card as $card) {
            $data[] = array(
                'cardid' => strval(@$card->cardid), // 小类商品编码
                'classid' => strval(@$card->classid), // 此子类商品所属大类编码
                'cardname' => strval(@$card->cardname), // 小类商品名称
                'detail' => $this->decode(@$card->detail), // 商品介绍
                'compty' => $this->decode(@$card->compty), // 资费说明
                'usecity' => strval(@$card->usecity), // 开通城市
                'usemethod' => $this->decode(@$card->usemethod), // 使用方法
                'fullcostsite' => strval(@$card->fullcostsite), // 充值网址
                'proare' => strval(@$card->proare), // 产品产地
                'serviceNum' => $this->decode(@$card->serviceNum), // 客服服务中心
            );
        }
        return $data;
    }

    /**
     * 具体商品信息同步接口
     *
     * 此接口依据用户提供的商品编码返回此商品的具体信息（可以同步到本地，定时维护，不用每次都去查询）
     *
     * @param $cardid 需查询商品的编码（支持4位，6位编码，4位表示该小类下的所有商品，6位是具体的商品信息）
     * @return array
     * @throws CException
     */
    public function queryCardInfo($cardid)
    {
        $params = $this->getDefaultParams();
        $params['cardid'] = $cardid;
        $data = $this->call('querycardinfo.do', $params);
        if (!isset($data->ret_cardinfos))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 ret_cardinfos 值。内容为：\"{$this->xml}\"。");
        $infos = $data->ret_cardinfos;
        if (!isset($infos->card))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 card 值。内容为：\"{$this->xml}\"。");
        $data = array();
        foreach ($infos->card as $card) {
            $data[] = array(
                'cardid' => strval(@$card->cardid),
                'pervalue' => strval(@$card->pervalue),
                'inprice' => strval(@$card->inprice),
                'sysddprice' => strval(@$card->sysddprice),
                'sysdd1price' => strval(@$card->sysdd1price),
                'sysdd2price' => strval(@$card->sysdd2price),
                'memberprice' => strval(@$card->memberprice),
                'innum' => strval(@$card->innum),
                'cardname' => strval(@$card->cardname),
                'othername' => strval(@$card->othername),
                'howeasy' => strval(@$card->howeasy),
                'amounts' => strval(@$card->amounts), // 在线充产品可选数量，连续的用“-”表示，“,”用作分隔符
                'subclassid' => strval(@$card->subclassid), // 此商品所属子类编码
                'classtype' => strval(@$card->classtype), // 此商品所属商品类型（1-实物商品，2-直充商品，3-卡密商品，
                // 4-手机快冲，5-手机慢冲，6-支付商品）
                'fullcostsite' => strval(@$card->fullcostsite),
                'caption' => $this->decode(@$card->caption),
                'lastreftime' => strval(@$card->lastreftime), // 最后操作时间
                'accountdesc' => strval(@$card->accountdesc), // 账号描述
            );
        }
        return $data;
    }

    /**
     * 商品价格查询接口
     *
     * 此接口依据用户提供的商品编码返回此商品的价格信息
     *
     * @param $cardid 需查询商品的编码
     * @return array
     * @throws CException
     */
    public function queryPrice($cardid)
    {
        $params = $this->getDefaultParams();
        $params['cardid'] = $cardid;
        $data = $this->call('queryprice.do', $params);
        if (!isset($data->ret_cardinfos))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 ret_cardinfos 值。内容为：\"{$this->xml}\"。");
        $infos = $data->ret_cardinfos;
        if (!isset($infos->card))
            throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 card 值。内容为：\"{$this->xml}\"。");
        $card = @$infos->card[0];
        $data = array(
            'cardid' => strval(@$card->cardid), // 商品编号
            'cardname' => strval(@$card->cardname), // 商品名称
            'pervalue' => strval(@$card->pervalue), // 商品面值
            'inprice' => strval(@$card->inprice), // 对应SP等级的结算价
            'sysddprice' => strval(@$card->sysddprice), // CP系统1级直销价
            'sysdd1price' => strval(@$card->sysdd1price), // CP系统2级直销价
            'sysdd2price' => strval(@$card->sysdd2price), // CP系统3级直销价
            'memberprice' => strval(@$card->memberprice), // 普通会员价
            'innum' => strval(@$card->innum), // 商品库存情况
            'amounts' => strval(@$card->amounts), // 可选数量
        );
        return $data;
    }

    /**
     * 查询商品最后修改时间
     *
     * 此接口可以用于判断商品是否有修改，有修改则再次同步商品信息
     *
     * @param $cardid 商品编号
     * @return string 最后操作时间
     */
    public function retMaxTime($cardid)
    {
        return $this->call('retmaxtime.do', array('cardid' => $cardid), false);
    }

    /**
     * 游戏直充区服查询接口
     *
     * 此接口用于查询游戏直充的区服信息
     *
     * @param $gameid 商品编号（对应cardid）
     * @return array
     * @throws CException
     */
    public function getAreaServer($gameid)
    {
        $xml = $this->call('getareaserver.do', array('gameid' => $gameid), false);
        $data = simplexml_load_string($xml);
        if ($data === false)
            throw new CException("解析欧飞 \"{$this->url}\" API 返回出错。内容为：\"{$this->xml}\"。");
        if (!isset($data->ROW))
            return array();
        $rows = $data->ROW;
        $data = array();
        foreach ($rows as $row) {
            $data[] = array(
                'GAMEID' => strval(@$row->GAMEID), // 商品编号（对应cardid）
                'GAMENAME' => strval(@$row->GAMENAME),
                'AREA' => strval(@$row->AREA), // 游戏区（对应game_area）
                'SERVER' => strval(@$row->SERVER), // 游戏服（对应game_srv）
                'SX' => strval(@$row->SX),
                'AREAFLAG' => strval(@$row->AREAFLAG),
                'SERVERFLAG' => strval(@$row->SERVERFLAG),
            );
        }
        return $data;
    }

    /**
     * 游戏直充接口 / 手机直充接口
     *
     * 此接口依据用户提供的请求为指定游戏玩家直接充值
     * 此接口依据用户提供的请求为指定手机直接充值，这个接口和游戏直充使用同一个接口
     *
     * @param $cardid 所需提货商品的编码(需和CP商品编码一一对应,需22开头的编码)
     *                所需提货商品的编码(快充：140101，慢充：170101(只支持移动) )
     * @param $cardnum 所需提货商品的数量（1-10张）
     *                 快充可选面值（移动：1、2、5、10、20、30、50、100、300,200,500
     *                 联通、电信：10、20、30、50、100、300,500）
     *                 慢充可选面值（移动：30、50、100）
     * @param $sporder_id Sp商家的订单号
     * @param $sporder_time 订单时间 （yyyyMMddHHmmss 如：20070323140214）
     * @param $game_userid 游戏玩家账号
     *                     手机号
     * @param $game_area 游戏所在区域（没有则不填,需按CP要求填写）
     * @param $game_srv 游戏所在服务器组（没有则不填,需按CP要求填写）
     * @param $ret_url 订单充值成功后返回的URL地址，可为空（不参与MD5验算）请使用80端口
     *                 系统请求参数：ret_code 充值后状态，1代表成功，9代表撤消
     *                               sporder_id  SP订单号
     *                               ordersuccesstime 处理时间
     *                               err_msg 失败原因
     *                               提交方式为：POST
     *                 注：SP得到请求信息后，请自行处理系统订单状态。
     *                     如SP系统问题没收到返回结果，或者在长时间内没有收到充值成功信息，
     *                     可再次发送此笔订单到查询接口查看充值状态（SP订单号为原订单号），
     *                     如还是没有充值成功请和CP客服联系，以做进一步处理。
     * @param $userip 买家IP(不参与MD5验证，当此参数有值时，此值作为订单来源IP存入库)
     * ----------------------------------------------------------------------------------
     * @param $mctype 慢充类型：0.5（半小时到账）、4（4小时到账）、12（12小时到账）、
     *                24（24小时到账）、48（48小时到账）、72（72小时到账）
     *                （不传默认为24，不参与MD5验证）
     *
     * 以上参数如果是中文，需将参数值URL编码传递（GBK）
     */
    public function onlineOrder($cardid, $cardnum, $sporder_id, $sporder_time, $game_userid, $game_area, $game_srv, $ret_url, $userip)
    {
		//--- start 如果 收入金额 < 支出金额 则返回 订单异常,因为偶们亏钱  20150302 朱文望
		//$sql = "select id from virtual_recharge where id in (select DISTINCT object_id from others_fees where sn='{$sporder_id}' and ) and income_price >= expend_price"; 
		$sql = "select id from virtual_recharge where id = (select DISTINCT object_id from others_fees where sn='{$sporder_id}' and amount=bank_pay+red_packet_pay) and income_price >= expend_price;";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		if (empty($result)) throw new CHttpException(400,"充值金额异常,操作失败");
		//--- end
        // 编码转换 UTF-8 --> GBK
        $game_area = $this->encode($game_area);
        $game_srv = $this->encode($game_srv);

        $params = $this->getDefaultParams();
        $params['cardid'] = $cardid;
        $params['cardnum'] = $cardnum;
        $params['sporder_id'] = $sporder_id;
        $params['sporder_time'] = $sporder_time;
        $params['game_userid'] = $game_userid;
        $params['game_userpsw'] = ''; // 游戏玩家的密码（可以为空，并且不参与MD5验算）
        $params['game_area'] = $game_area;
        $params['game_srv'] = $game_srv;
        $params['md5_str'] = $this->md5_sign($cardid, $cardnum, $sporder_id, $sporder_time, $game_userid, $game_area, $game_srv); // MD5后字符串
        $params['ret_url'] = $ret_url;
        $params['userip'] = $userip;
        $data = $this->call('onlineorder.do', $params);
        return array(
            'orderid' => strval(@$data->orderid),
            'cardid' => strval(@$data->cardid),
            'cardnum' => strval(@$data->cardnum),
            'ordercash' => strval(@$data->ordercash),
            'cardname' => strval(@$data->cardname),
            'sporder_id' => strval(@$data->sporder_id),
            'game_userid' => strval(@$data->game_userid),
            'game_state' => strval(@$data->game_state), // 如果成功将为1，澈消(充值失败)为9，充值中为0,只能当状态为9时，商户才可以退款给用户。
        );
    }

    /**
     * 手机号码归属地查询
     *
     * 此接口用于查询手机号码的归属地
     *
     * @param $mobilenum 手机号码前七位
     * @return array array('1381383', '江苏南京', '移动')
     */
    public function mobInfo($mobilenum)
    {
        return explode('|', $this->call('mobinfo.do', array('mobilenum' => $mobilenum), false));
    }

    /**
     * 根据SP订单号查询充值状态
     *
     * 此接口用于查询订单的充值状态
     *
     * @param $spbillid Sp000001,商户系统订单号
     * @return String  返回（1，0，9，-1），其中一项。 1充值成功，0充值中，9充值失败，-1找不到此订单。
     *                 如果返回-1，请您务必进入平台或者联系欧飞客服进行核实，避免给自己带来不必要的损失。
     */
    public function query($spbillid)
    {
        return $this->call('query.do', array('userid' => $this->userid, 'spbillid' => $spbillid), false);
    }

    /**
     * 根据SP订单号补发充值状态
     *
     * 此接口用于没有接收到回调充值状态的情况下进行补发
     *
     * @param $spbillid Sp000001,商户系统订单号
     * @return SimpleXMLElement
     */
    public function reIssue($spbillid)
    {
        $params = $this->getDefaultParams();
        $params['spbillid'] = $spbillid;
        return $this->call('reissue.do', $params);
    }

    // 根据手机号和面值查询商品信息（telquery.do）
    public function telQuery($phoneno, $pervalue)
    {
        $params = $this->getDefaultParams();
        $params['phoneno'] = $phoneno;
        $params['pervalue'] = $pervalue;
        $data = $this->call('telquery.do', $params);
        return array(
            'cardid' => strval(@$data->cardid),
            'cardname' => strval(@$data->cardname),
            'inprice' => strval(@$data->inprice),
            'game_area' => strval(@$data->game_area),
        );
    }

    // 卡密商品库存查询接口（queryleftcardnum.do）
    // 提卡接口（order.do）
    // 查询手机号当时是否可以充值（telcheck.do）
    // 根据固话号码和面值查询商品信息（fixtelquery.do）
    // 固话直充接口（fixtelorder.do）
    // 自动对账接口（querybill.do）
    // 身份证核查接口（idcardquery.do）（此接口停用）

    /**
     * 签名算法
     * http://api2.ofpay.com/md5test.jsp
     *
     * 包体=userid+userpws+cardid+cardnum+sporder_id+sporder_time+ game_userid+ game_area+ game_srv
     *    1: 对: “包体+KeyStr” 这个串进行md5 的32位值. 结果大写
     *    2: KeyStr 默认为 OFCARD, 实际上线时可以修改。
     *    3: KeyStr 不在接口间进行传送。
     *
     * @param string $cardid
     * @param string $cardnum
     * @param string $sporder_id
     * @param string $sporder_time
     * @param string $game_userid
     * @param string $game_area
     * @param string $game_srv
     * @return string
     */
    protected function md5_sign($cardid, $cardnum, $sporder_id, $sporder_time, $game_userid, $game_area = '', $game_srv = '')
    {
        $str = $this->userid . strtolower(md5($this->userpws)) . $cardid . $cardnum . $sporder_id . $sporder_time . $game_userid . $game_area . $game_srv;
        $str .= $this->KeyStr;
        return strtoupper(md5($str));
    }

    protected function call($path, $params = array(), $retXml = true)
    {
        $this->url = Yii::app()->curl->buildUrl(self::OF_BASE_URL . $path, $params);
        Yii::log(sprintf("调用链接：\"%s\"", $this->url), CLogger::LEVEL_INFO, 'colourlife.core.api.OfCardApi');
        $this->xml = Yii::app()->curl->get($this->url);
        Yii::log(sprintf("调用链接：\"%s\"\n返回值：\"%s\"", $this->url, $this->decode($this->xml)), CLogger::LEVEL_INFO, 'colourlife.core.api.OfCardApi');
        if ($this->xml === false)
            throw new CException("调用欧飞 \"{$this->url}\" API 失败，没有返回值。");
        if ($retXml) {
            $this->xml = str_ireplace('encoding="gb2312"', 'encoding="gbk"', $this->xml);
            $data = @simplexml_load_string($this->xml);
            if ($data === false)
                throw new CException("解析欧飞 \"{$this->url}\" API 返回出错。内容为：\"{$this->xml}\"。");
            if (!isset($data->retcode))
                throw new CException("欧飞 \"{$this->url}\" API 返回没有定义 retcode 值。内容为：\"{$this->xml}\"。");
            if ($data->retcode != 1) {
                $msg = @$data->err_msg;
                throw new CException("欧飞 \"{$this->url}\" API 返回错误：\"{$msg}\"。");
            }
            return $data;
        } else
            return $this->xml;
    }

    protected function getDefaultParams()
    {
        return array(
            'userid' => $this->userid,
            'userpws' => strtolower(md5($this->userpws)),
            'version' => '6.0',
        );
    }

    protected function decode($str)
    {
        return @iconv('GBK', 'UTF-8', urldecode(strval($str)));
    }

    protected function encode($str)
    {
        return @iconv('UTF-8', 'GBK', strval($str));
        //return urlencode(@iconv('UTF-8', 'GBK', strval($str)));
    }

}
