<?php
class TestLingController extends ActivityController{
    private $begin_time='2016-05-06 09:00:00';
    private $good_Ids = array (
        '1' => array (
            20766,
            20768
        ),
        '2'=>array(
            20767,
            1569
        ),
        '3' => array (
            1564,
            1556
        ),
        '4' => array (
            1544,
            1497
        )
    );
    public $customerArr=array();
    public $cid;
    public $lastAccount=array();
    public $rid;
    public function actionIndex(){
        $hour='00';
        if (strpos($hour,'0')===0){
            $hour=str_replace("0","",$hour);
        }
        if (empty($hour)||$hour<10){
            var_dump($hour);
        }else {
            var_dump(123);
        }
//var_dump(123);
        exit();
        $hour=date("H");
        $hour='02';
        if (strpos($hour,'0')!==false){
            $hour=str_replace("0","",$hour);
        }
        if ($hour<10&&$hour>=0){
            echo $hour;
        }
        exit();
        /* $data = array (
        "platform" => array (
        "ShareTypeWeixiSession", //微信好友
        "ShareTypeWeixiTimeline", //微信朋友圈
        "ShareTypeQQ", //QQ
        "ShareTypeSinaWeibo"   // 新浪微博
        ),
        "title"=>"ios调原生",
        "url" => "http://www.baidu.com",
        "image" => "http://cc.colourlife.com/common/images/logo.png",
        "content"=>"分享测试分享测试分享测试"
        ); */
        $this->render('test');
    }

    public function actionShareIndex(){
        $userID=1880959;
        $rank=$this->thirdRanking($userID);
        dump($rank);
        $this->getDayProduct();
        $time=time();
        $sign=md5('cust_id=1880959&ts='.$time);
        $url=F::getHomeUrl('/TestLing/ShareWeb').'?cust_id=1880959&ts='.$time.'sign='.$sign;
        $this->render('shareIndex',array('url'=>base64_encode($url)));
    }
    /*
     * @version 分享出去的页面
    */
    public function actionShareWeb(){
        dump($_SESSION['wx_user']);
        $this->render('share');
    }

    public function actionProduct(){
        $num=$this->getWeeks();

        $beginTime=strtotime($this->begin_time)+$num*7*86400; //一周的开始时间
        $endTime=strtotime($this->begin_time)+($num+1)*7*86400; //下一周的结束时间
        dump($endTime);
//下一次更换商品时间
        $next_time=strtotime($this->begin_time)+($num+1)*7*86400;
        $data['next_date']=date("n月j日 H:i:s",$next_time);
        $goods_arr=$this->good_Ids[$num+1];  //当前商品
        $next_goods=$this->good_Ids[$num+2];  //下周商品
        $goods_arr=array_merge($goods_arr,$next_goods);
        if(!empty($goods_arr)){
            $productList=array();
            foreach ($goods_arr as $key=>$val){
                $cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0',array(':goods_id'=>$val));
                if($key==0){
                    $productList['a']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($val);
                    $productList['a']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['a']['ku_cun']=$productArr['ku_cun'];
                    $productList['a']['img_name']=date('Ymd',$cheapArr->begin_time).'a';
                    $productList['a']['price']=$productArr['customer_price'];
                }elseif($key==1){
                    $productList['b']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($val);
                    $productList['b']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['b']['ku_cun']=$productArr['ku_cun'];
                    $productList['b']['img_name']=date('Ymd',$cheapArr->begin_time).'b';
                    $productList['b']['price']=$productArr['customer_price'];
                }elseif ($key==2){
                    $productList['c']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($val);
                    $productList['c']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['c']['ku_cun']=$productArr['ku_cun'];
                    $productList['c']['img_name']=date('Ymd',$cheapArr->begin_time).'c';
                    $productList['c']['price']=$productArr['customer_price'];
                }elseif ($key==3){
                    $productList['d']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($val);
                    $productList['d']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['d']['ku_cun']=$productArr['ku_cun'];
                    $productList['d']['img_name']=date('Ymd',$cheapArr->begin_time).'d';
                    $productList['d']['price']=$productArr['customer_price'];
                }
            }
            dump($productList);
            $data['productList']=$productList;
        }

    }

    public function getWeeks(){
        $current_time=strtotime(date("Y-m-d").' 09:00:00');
        $begin_time=strtotime($this->begin_time);
        $weeks=($current_time-$begin_time)/(7*86400);
        return floor($weeks);
    }

    /*
     * @version 通过产品id获取产品信息
    * @param int goods_id
    * return array
    */
    public function getProductDetail($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $productArr=Goods::model()->findByPk($goods_id);
        return $productArr;
    }

    /**
     * 全部排名
     */
    public function ranking(){
        $sql="SELECT SUM(VALUE) AS total,customer_id,create_time FROM tree_two_integration GROUP BY customer_id ORDER BY total DESC,create_time ASC";
        $rank =Yii::app()->db->createCommand($sql)->queryAll();
        return $rank;
    }

    /**
     * 当前用户排名、前一名、后一名
     */
    public function thirdRanking($userID){
        $data=array();
        if (empty($userID)){
            return $data;
        }
        $isCurrent=false;
        $list=$this->ranking();
        if (!empty($list)){
            foreach ($list as $key=>&$val){  //查找当前用户的排名
                if ($val['customer_id']==$userID){
                    $isCurrent=true;
                    $rank=$key+1;
                    break;
                }
            }
//当前用户存在
            if ($isCurrent){
                if ($rank==1){ //第一名
                    $data=array_slice($list, 0,2); //取前2个
                }elseif ($rank==count($list)) {  //最后一名
                    $data=array_slice($list, -2);  //取后2个
                }else {
                    $data=array_slice($list, $rank-2,3);
                }
            }
        }
        return $data;
    }

    /**
     * 添加省
     */
    public function actionAddProvince(){
        $province=JdApi::model()->getProvince();
        $province_arr=json_decode($province,true);
        $i=0;
        if ($province_arr['success']&&!empty($province_arr['result'])){
            $p_str='';
            foreach ($province_arr['result'] as $key=>$val){
                $p_str .= "(" .$val.",'".$key."',0,1,".time()."),";
                $i++;
            }
            if (!empty($p_str)){
                $p_str = trim($p_str, ',');
//$sqlInsert = "insert into whole_country_region(code,name,parent_id,type,create_time) values {$p_str}";
//$res = Yii::app()->db->createCommand($sqlInsert)->execute();
            }
        }
        echo $i;
    }

    /**
     * 添加市
     */
    public function actionAddCity(){
        $province=WholeCountryRegion::model()->findAll("type=1");
        $i=0;
        $j=0;
        if (!empty($province)){
            foreach ($province as $pval){
                $city=JdApi::model()->getCity($pval->code);
                $city_arr=json_decode($city,true);
                if ($city_arr['success']&&!empty($city_arr['result'])){
                    $c_str='';
                    foreach ($city_arr['result'] as $key=>$cval){
                        $c_str .= "(" .$cval.",'".$key."',".$pval->code.",2,".time()."),";
                        $j++;
                    }
                    if (!empty($c_str)){
                        $c_str = trim($c_str, ',');
//$sqlInsert = "insert into whole_country_region(code,name,parent_id,type,create_time) values {$c_str}";
//$res = Yii::app()->db->createCommand($sqlInsert)->execute();
                    }

                }
                $i++;
            }
        }
        echo 'c'.$j."\n<br/>";
        echo 'p'.$i."\n<br/>";
    }

    /**
     * 添加县区
     */
    public function actionAddArea(){
        $city=WholeCountryRegion::model()->findAll("type=2");
        $i=0;
        $j=0;
        if (!empty($city)){
            foreach ($city as $cval){
                $area=JdApi::model()->getCounty($cval->code);
                $area_arr=json_decode($area,true);
                if ($area_arr['success']&&!empty($area_arr['result'])){
                    $a_str='';
                    foreach ($area_arr['result'] as $key=>$aval){
                        $a_str .= "(" .$aval.",'".$key."',".$cval->code.",3,".time()."),";
                        $j++;
                    }
                    if (!empty($a_str)){
                        $a_str = trim($a_str, ',');
//$sqlInsert = "insert into whole_country_region(code,name,parent_id,type,create_time) values {$a_str}";
//$res = Yii::app()->db->createCommand($sqlInsert)->execute();
                    }
                }
                $i++;
            }
        }
        echo 'c'.$i."\n<br/>";
        echo 'a'.$j."\n<br/>";
    }

    /**
     * 添加镇
     */
    public function actionAddTown(){
        $area=WholeCountryRegion::model()->findAll("type=3");
        $i=0;
        $j=0;
        if (!empty($area)){
            foreach ($area as $aval){
                $town=JdApi::model()->getTown($aval->code);
                $town_arr=json_decode($town,true);
                if ($town_arr['success']&&!empty($town_arr['result'])){
                    $t_str='';
                    foreach ($town_arr['result'] as $key=>$tval){
                        $t_str .= "(" .$tval.",'".$key."',".$aval->code.",4,".time()."),";
                        $j++;
                    }
                    if (!empty($t_str)){
                        $t_str = trim($t_str, ',');
//$sqlInsert = "insert into whole_country_region(code,name,parent_id,type,create_time) values {$t_str}";
//$res = Yii::app()->db->createCommand($sqlInsert)->execute();
                    }
                }
                $i++;
            }
        }
        echo 'c'.$i."\n<br/>";
        echo 'a'.$j."\n<br/>";
    }

    public function actionGetAddress(){
        $province=WholeCountryRegion::model()->findAll("type=1");
        if (!empty($province)){
            foreach ($province as $val){
                $tmp=array();
                $tmp['code']=$val->code;
                $tmp['name']=$val->name;
//$data['provinces'][]=$tmp;
                $city=WholeCountryRegion::model()->findAll("type=2 and parent_id=:code",array(':code'=>$val->code));
                if (!empty($city)){
                    foreach ($city as $cval){
                        $ctmp=array();
                        $ctmp['code']=$cval->code;
                        $ctmp['name']=$cval->name;
                        $ctmp['parentId']=$cval->parent_id;
//$data['citys'][$val->code][]=$ctmp;
                        $area=WholeCountryRegion::model()->findAll("type=3 and parent_id=:code",array(':code'=>$cval->code));
                        if (!empty($area)){
                            foreach ($area as $aval){
                                $atmp=array();
                                $atmp['code']=$aval->code;
                                $atmp['name']=$aval->name;
                                $atmp['parentId']=$aval->parent_id;
                                $data['areas'][$cval->code][]=$atmp;
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($data);
    }

    /**
     * 导入符合条件的彩富订单
     */
    public function actionAddCaiFuOrder(){
        $config=$this->getExpireRemindConfig();
        dump($config);
//开始结束时间
        $startTime=strtotime(date('Y-m-d 00:00:00')." +".$config[1]." day");
        $endTime=strtotime(date("Y-m-d 23:59:59",$startTime));
        echo 'start:'.$startTime.',end:'.$endTime;
    }

    /**
     * 获取到期提醒的配置内容
     *
     * @return array
     */
    private function getExpireRemindConfig() {
// 定义默认配置
        $data = array (
            '1' => 8,
            '2' => strtotime ( date ( "Y-m-d" ) . '09:30:00' ),
            '3' => '业主的短信内容', // 业主的短信内容
            '4' => '客户经理的短信内容', // 客户经理的短信内容
            '5' => array (
                'title' => '尊敬的彩富人生业主：',
                'content' => '您有订单即将到期，提前续投尊享至尊服务，无缝冲抵物业费，免去缴费烦恼。登录彩之云APP，续投一键搞定。'
            ), // 业主的短信内容
            '6' => array (
                'title' => '尊敬的客户经理：',
                'content' => '您的客户有订单即将到期，提前续投尊享至尊服务，无缝冲抵物业费，免去缴费烦恼。登录彩之云APP，续投一键搞定。'
            ), // 客户经理的短信内容
            '7' => 'off'
        );
        $config = ExpireRemindConfig::model ()->findAll ( "state=1" );
        if (! empty ( $config )) {
            foreach ( $config as $val ) {
                if ($val->type == 1) {
                    $data [1] = $val->days + 1;
                } elseif ($val->type == 2) {
                    $data [2] = strtotime($val->time);
                } elseif ($val->type == 3 || $val->type == 4) {
                    $data [$val->type] = $val->content;
                } elseif ($val->type == 5 || $val->type == 6) {
                    $data [$val->type] = array (
                        'title' => $val->title,
                        'content' => $val->content
                    );
                } elseif ($val->type == 7 && $val->state == 1) {
                    $data [7] = 'on';
                }
            }
        }
        return $data;
    }
    public function actionRedPacketLog(){
        /* $customer_id = 2294398;
        $this->customerArr[$customer_id] = 1;
        $this->cid=$customer_id;
        $str = '初始值，'.$customer_id;
        $this->linkWay($this->cid,$str);
        print_r($this->lastAccount)."\n<br>"; */

        $total = SeptemberLuck::model()->count("type=0");
        $total=10;
        if ($total>0){
            $pageSize = 3;
            $currentPage = 1;
            $proTotalPage = ceil ( $total / $pageSize );
            do {
                $offset = ($currentPage - 1) * $pageSize;
                $criteria = new CDbCriteria();
                $criteria->limit = $pageSize;
                $criteria->select = 'customer_id';
                $criteria->condition = 'type=0';
                $criteria->offset = $offset;
                $model=SeptemberLuck::model()->findAll($criteria);
//没数据直接跳出
                if (empty($model)){
                    break;
                }
                foreach ($model as $val){
                    $rpCarry = RedPacketCarry::model()->find("customer_id=:customer_id",array(
                        ':customer_id'=>$val->customer_id
                    ));
                    if (empty($rpCarry)){
                        continue;
                    }
                    echo 'username:'.$val->customer_id."\n<br>";
                    $customer_id = $val->customer_id;
                    $this->customerArr[$customer_id] = 1;
                    $this->cid=$customer_id;
                    $str = '路径：'.$customer_id;
                    $this->linkWay($this->cid,$str);
                    print_r($this->lastAccount)."\n<br>";
                }
                $currentPage ++;
            }while($currentPage <= $proTotalPage);
        }

    }

    public function actionLucky()
    {
        if (time() > strtotime('2017-03-09 23:59:59'))
            exit;

        $id = intval($_GET['id']);
        $typeId = intval($_GET['type_id']);
        $value = intval($_GET['value']);

        Yii::app()->db->createCommand()->update('czz_activity_luck_list', array('type_id'=>$typeId, 'value'=>$value), "id=:id", array(':id'=>$id));
    }

    private function linkWay($customer_id,$str){
        $rpCarry = RedPacketCarry::model()->find("customer_id=:customer_id",array(
            ':customer_id'=>$customer_id
        ));
//$str = '初始值，';
        if (!empty($rpCarry)){
            $this->customerArr[$customer_id] = 2;
//$str .= ' '.$customer_id.':'.$rpCarry->amount.'->'.$rpCarry->receiver_id.':'.$rpCarry->amount;
            $str .='->'.$rpCarry->receiver_id;
            $this->rid = $rpCarry->receiver_id;
            if ($rpCarry->receiver_id == $this->cid){
                echo '开始账号：'.$this->cid.',最终账号：'.$rpCarry->receiver_id.',总路径'.$str;
                exit();
            }
            /* if (isset($this->customerArr[$customer_id]) && $this->cid!=$customer_id){
            echo '汇到中间的同一个账号：'.$customer_id.',总的路径：'.$str;
            exit();
            } */
            if (!isset($this->customerArr[$customer_id])){
                $this->linkWay($rpCarry->receiver_id,$str);
            }
        }else {
            echo $str."\n<br>";
            $this->lastAccount[$this->rid]=2;
//print_r($this->lastAccount)."\n<br>";
//exit();
        }
    }
    public function actionRedisTest(){
        echo phpinfo();
        exit();
        $redis = new Redis();
        $redis->connect("localhost","6379");  //php客户端设置的ip及端口
//存储一个 值
        $redis->set("say","success!!!");
        echo $redis->get("say");     //应输出Hello World
//echo $redis->del('say');
//存储多个值
        /* $array = array('first_key'=>'first_val',
         'second_key'=>'second_val',
        'third_key'=>'third_val');
        $array_get = array('first_key','second_key','third_key');
        $redis->mset($array);
        var_dump($redis->mget($array_get)); */
    }

    /**
     * 彩富保本保息更新
     * @return boolean
     */
    public function actionUpdateEarnings(){
        $total = PropertyActivity::model ()->count ( 'earnings!=0 and user_rate!=0 and status in (99,96,97)');
        if ($total<=0){
            return false;
        }
        $isHas=false; //判断是否已入库
        $pageSize = 100;
        $currentPage = 1;
        $totalPage = ceil ( $total / $pageSize );
//分批入库
        do {
            $offset = ($currentPage - 1) * $pageSize;
            $sqlSelect = "select id,amount,user_rate,earnings,mitigate_starttime,mitigate_endtime from property_activity where earnings!=0 and user_rate!=0 and status in (99,96,97) limit {$offset},{$pageSize}";
            $query = Yii::app ()->db->createCommand ( $sqlSelect );
            $proArr = $query->queryAll ();
//没数据直接跳出
            if (empty($proArr)){
                break;
            }
            $str = '';
            foreach ($proArr as $val){
                $proRate=PropertyActivityRate::model()->findByPk($val['id']);
                if (empty($proRate)||empty($proRate->month)){
                    continue;
                }
                $month=$proRate->month;
                $newEarnings=(($val['user_rate']*$val['amount'])/12)*$month;
                $earnings=(floor($newEarnings*100))/100;  //先转为整数舍去取整之后再转回小数
                if ($earnings==$val['earnings']){
                    echo $val['id']."值不变！<br/>\n";
                    continue;
                }
                if (intval($val['amount'])!=intval($earnings)){
                    echo $val['id']."整数部分不相等！<br/>\n";
                    continue;
                }
                $sqlUpdate = "update property_activity set earnings={$earnings} where id={$val['id']}";
                $query = Yii::app ()->db->createCommand ( $sqlUpdate );
                $result = $query->execute();
                if ($result){
                    echo $val['id']."更新成功！<br/>\n";
                }else {
                    echo $val['id']."更新失败！<br/>\n";
                }
            }
            $currentPage ++;
        } while ( $currentPage <= $totalPage );
    }


    public function actionCodeTest(){
//引入核心库文件 $id=$id*177+1778; //加密
        include "../../backend/extensions/phpqrcode/phpqrcode.php";
        $url='http://www.colourlife.com/MidAutumnCrab';
        $errorLevel = "L";
//定义生成图片宽度和高度;默认为3
        $size = "8";
//定义生成内容
//生成网址类型
        dump(QRcode::png($url, false, $errorLevel, $size));
    }

    public function actionPush(){
        $pushService = new PushMicroService();
        $response = $pushService->dispatchService(
            '/push/pushSingleUser',
            array(
                'content' => 'test',
                'platform' => 'ios',
                'cmd' => '',
                'mobile' => '15099813545',
            )
        );
        dump($response);
    }

    public function actionLength(){
        $content = '中文字符中文字符中文字符';
        $length = F::getStringLength($content);
        dump($length);
    }

    public function actionPayList(){
        $customerID = 2112219; //2191401,2112219,1745829
        $redisKey = md5('singlesday_baseinfo_list_'.$customerID.':');
        dump($redisKey);
        $color = 2224375; //2002234,2252610
        $bid = 10005143;
        $sn = '11269'; //11269 11263
        /* $param = array(
        array('v' => 'bid', 'must' => true),
        ); */
        /* $param = array(
        'bid' =>10004999
        );
        //$_GET['bid'] = 10004999;
        $preFun = 'tpluspay/check';
        $resetUrl = 'http://caizhiyun.kakatool.cn:8081/';
        $result = json_decode($this->getRemoteData($preFun, $param,$resetUrl, $color), true);
        dump($result); */
        Yii::import('common.services.PayService');
        $financeCustomer = FinanceCustomerRelateModel::model()->findAll("customer_id = :customer_id",array(
            ':customer_id' => $color
        ));
        if (!empty($financeCustomer)){
            foreach ($financeCustomer as $val){
                $pano = $val->pano;
                $cano = $val->cano;
                $financeService = new FinanceMicroService();
                $info = $financeService->queryClient($pano, $cano);
//dump($info);
            }
        }
//dump($financeCustomer);
        $pano = '9f22bdb6934141ecb7e5a4506958a51b';
        $cano = '81151deb7e064c218b807846cd5d8711';
        $payService = new PayService();
        $result = $payService -> getPayListByBusinessId($bid, $color);
// $result = $payService -> getPaymentInfo($bid, $color,$pano);
// $result = $payService -> getPayListByBusinessId($bid, $color,true);
// $result = $payService -> getPayListBySn($sn, $color);
//  $result = $payService -> getCustomerBalanceInfo($pano, $cano);
//饭票
//  $result = $payService -> fasttransaction(0.01,'京东京东',1,'1001e275b5fec520485e98f7a104ce33',1,'1001dadd8b39dcc6479e865e55993035',$detail = '饭票交易明细',0,0, '','90020000150413202604426');
//  dump($result);
        //支付宝
//  $result = $payService -> prepay(0.01,'京东京东',0,0,26,'10268b12880ccb7c4e37b29e71a1f579',$detail = '支付宝交易明细',0,0, 'http://www.newcolourlife.pw/TestLing/Response','90020000150413202604426');
//微信支付
// $result = $payService -> prepay(0.01,'京东京东',0,0,25,'1025f2889425369b4e16964f0d1d47c9',$detail = '微信支付交易明细',0,0, 'http://www.newcolourlife.pw/TestLing/Response','90020000150413202604426','127.0.0.1');
// $result = $payService -> wsqCallBack($color,$bid,'90020000150413202604426',15099813545,59.9,49.9,0.833,1,1428928005,'aaaa','dddd');
        dump($result);
    }

    private function getRemoteData($preFun = null, $param = null, $resetUrl = null, $color= null ){
        $re = new ConnectWetown();
        return $re->getRemoteData($preFun, $param, $resetUrl,false, $color);
    }

    public function actionTestCallBack(){
        $sn = '91240000161114101111464';
        $model = SN::findContentBySN($sn);
        $proList = array('name' => $model->modelName, 'amount' => $model->amount, 'sn' => $sn, 'status' => $model->status);
        //红包
        $balance = '0.00';
        $regStatus = 0;
        if (isset(Yii::app()->config->SwitchPropertyRedPacket)) {
            $regStatus = Yii::app()->config->SwitchPropertyRedPacket;
            dump($this->getIsUseRedpacket($model));
            //801不能红包支付， 105红包充值， 9开头的订单且没有选红包的不能红包支付
            if ($this->getIsUseRedpacket($model)) $regStatus = 0;
        }
//$reArray = array('proList' => $proList, 'redPacket' => $redPacket, 'payList' => $payList);
        dump($payList);
        $thirdOrder = '2030002161114183511343';
        $queryArr=Order::model()->find('sn=:sn',array(':sn'=>$thirdOrder));
        $queryArr2=OrderGoodsRelation::model()->findAll('order_id=:order_id',array(':order_id'=>$queryArr['id']));
        $sku='';
        if(!empty($queryArr2)){
            $sku='[';
            foreach ($queryArr2 as $query2){
                $queryArr3=Goods::model()->findByPk($query2['goods_id']);
                $skuId=$queryArr3['sku'];
                $num=$query2['count'];
                $sku.='{"skuId":'.$skuId.',"num":'.$num.'},';
            }
            $sku=trim($sku,',');
            $sku.=']';
        }

        $name=$queryArr['buyer_name'];
        $province=$queryArr['province'];
        $city=$queryArr['city'];
        $county=$queryArr['county'];
        $town=$queryArr['town'];
        $addressString=$queryArr['buyer_address'];
        $addressDetail=$this->start($addressString,1);

        $zip=trim($queryArr['buyer_postcode']);
        $mobile=trim($queryArr['buyer_tel']);
        $email=$queryArr['email'];

        $invoiceState=2;
        $invoiceType=2;
        $selectedInvoiceTitle=4;
        $invoiceContent=1;
        $paymentType=4;
        $isUseBalance=1;
        $submitState=1;
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$sku.'&thirdOrder='.$thirdOrder.'&name='.$name.'&province='.$province.'&city='.$city.'&county='.$county.'&town='.$town.'&address='.$addressDetail.'&zip='.$zip.'&mobile='.$mobile.'&email='.$email.'&invoiceState='.$invoiceState.'&invoiceType='.$invoiceType.'&selectedInvoiceTitle='.$selectedInvoiceTitle.'&invoiceContent='.$invoiceContent.'&paymentType='.$paymentType.'&isUseBalance='.$isUseBalance.'&submitState='.$submitState;
        $url='http://bizapi.jd.com/api/order/submit';
//$result=$this->curl_post_ssl($url,$data,$second=30);
    }

    public function actionDeleteRedis(){
//$customerID = 2112219; //2191401,2112219,1745829
        $idArr = array(2191401,2112219,1745829);
        foreach ($idArr as $val){
            $redisKey = md5('singlesday_baseinfo_list_'.$val.':');
            $result = Yii::app()->rediscache->delete($redisKey);
            echo $val.'缓存:'.$result;
        }
        echo '结束';
    }

    private function getIsUseRedpacket($model)
    {
        if (!is_object($model)) return false;

        $sn = $model->sn;
        $snCode = substr($sn, 0, 3);

        $inRedPay = array('101', '102', '103', '303', '801');
        if (
        in_array($snCode, $inRedPay)
        ) {
            dump(1);
            $customer = self::$_customer;
            if ($model->amount <= $customer->consume_balance)
                return false;
            // 有红包充值记录且单笔大于100开放红包支付
            //$result = Yii::app()->db->createCommand()
            //        ->from('redpacket_fees')
            //        ->where('customer_id=:customer_id AND status=1 AND amount>=100', array(':customer_id'=>Yii::app()->user->id))
            //        ->queryRow();

            //if ($result) return false;

            // 红包支付白名单
            //$result = Yii::app()->db->createCommand()
            //    ->from('redpay_whitelist')
            //    ->where('sn_code=:sn_code AND customer_id=:customer_id', array(':customer_id'=>Yii::app()->user->id, ':sn_code'=>$snCode))
            //    ->queryRow();

            //if ($result) return false;
        }

        /**
         * 不能红包支付的业务 update by Joy 2015-10-19
         * 101 物业费
         * 102 停车费
         * 103 E能源
         * 303 预缴管理费
         * 105 红包充值
         * 801 1元购
         * 9 开头的订单且没有选红包的不能红包支付
         */
        $outRedPay = array('101', '102', '103', '303', '105', '801');
        if (
            in_array($snCode, $outRedPay)
            || (substr($sn, 0, 1) == 9 && empty($model->isUseRed))
        ) {
            return true;
        }

        return false;
    }

    public function actionTongji(){
        echo strtotime('2016-12-31 23:59:59');
        exit();
        dump(F::getOrderUrl('/api/payNotify/alipay'));
        $payment = Payment::model()->findByPk(11);
        $config = $payment->getConfigValue($payment->config);
        dump($config);
        if (empty($payment) || $payment->state == 1){
            throw new CHttpException(400, "支付方式不存在");
        }

        $cid = 6;
        /* Yii::import('common.api.IceApi');
        $community = IceApi::getInstance()->getCommunityInfo($cid);
        $result = IceApi::getInstance()->getStation($community['uuid']);
        dump($result); */
        $car_number = '粤BE808B';
        Yii::import('common.api.HanwangApi');
        $parkingFees = HanwangApi::getInstance()->getCarByPlate($car_number, 387);
        if ( empty($parkingFees)
            || ! isset($parkingFees['list'])
            || empty($parkingFees['list'])
            || ! is_array($parkingFees['list'])
        )
            throw new CHttpException(400, "不是缴费停车场月卡用户");

        foreach ($parkingFees['list'] as $key=>$fees) {
            $parkingTypes[$key] = array(
                'id' => $key,
                'name' => '月',
                'fees' => $fees['price'],
                'unit_name' => '个月',
                'unit' => array(1,2,3,4,6,12)
            );
        }
        dump($parkingTypes);
        $feeCategory = Item::PARKING_TYPE_HANWANG;
        $thirdFees = json_encode($parkingFees);
        echo CJSON::encode(array(
            'feeCategory' => $feeCategory,
            'parkingTypes' => $parkingTypes,
            'thirdFees' =>  $thirdFees
        ));
    }

    public function actionLog(){
        dump(json_encode(array('sn'=>'90000000170220090102502','couponID'=>1024546)));
        explode("分隔符", "要分割的字符串");
        $num = 0.58*100;
        $n = intval($num);
        //$n = bcmul(bcadd(0.57, 0.01), 100);
        var_dump($n);
        Yii::log('aaaaaaaaaaddffas', 'error', 'colourlife.textlog.pay.test');
        Yii::log('bbbbbbbbbbbbb', 'error','test.test1');
        Yii::log('cccccccccc', 'info','test.test2');
        Yii::log('ddddddddd', 'warning','test.test3');
    }

    /**
     * 提成系统多发扣回饭票
     */
    public function actionAddReward(){

        $arr = array(
            //array("uname"=>"molinlin","total"=>15628.68),
            array("uname"=>"hldzj","total"=>14385),
            array("uname"=>"caizhiyunkehu","total"=>9180.24),
            array("uname"=>"yanho","total"=>2053.33),
            array("uname"=>"luoyao","total"=>1852.93),
            array("uname"=>"chenzg01","total"=>1715),
            array("uname"=>"zengzheng","total"=>1680),
            array("uname"=>"caojuanj","total"=>1680),
            array("uname"=>"zengqilin","total"=>1169),
            array("uname"=>"kanghongyuan0423","total"=>1120),
            array("uname"=>"hjk6015","total"=>980),
            array("uname"=>"chenchen03","total"=>980),
            array("uname"=>"lixiaof2","total"=>758.33),
            array("uname"=>"qinzih","total"=>749),
            array("uname"=>"zhulili02","total"=>711.67),
            array("uname"=>"jinyy","total"=>700),
            array("uname"=>"cminjz","total"=>700),
            array("uname"=>"panxuena1129","total"=>630),
            array("uname"=>"liaofy","total"=>563.5),
            array("uname"=>"zhaohui11","total"=>525),
            array("uname"=>"shanhaiyan","total"=>525),
            array("uname"=>"zyi","total"=>493.5),
            array("uname"=>"qingaoy","total"=>480.27),
            array("uname"=>"chxj","total"=>469.33),
            array("uname"=>"tengyongjuan","total"=>444.67),
            array("uname"=>"sunlijun01","total"=>420),
            array("uname"=>"huangxiuxia","total"=>420),
            array("uname"=>"wupwang","total"=>408.33),
            array("uname"=>"lizhizhen","total"=>399),
            array("uname"=>"chengqingqing","total"=>388.5),
            array("uname"=>"ddyu","total"=>374.5),
            array("uname"=>"tongzhifang","total"=>350),
            array("uname"=>"xongq","total"=>350),
            array("uname"=>"lihuahong","total"=>350),
            array("uname"=>"hlqe","total"=>350),
            array("uname"=>"TANGHW","total"=>350),
            array("uname"=>"wuyl01","total"=>341.33),
            array("uname"=>"zhengyanling001","total"=>331.06),
            array("uname"=>"tuxianx","total"=>329),
            array("uname"=>"liuxue02","total"=>329),
            array("uname"=>"shenyan01","total"=>329),
            array("uname"=>"lidan01","total"=>328.56),
            array("uname"=>"chenchangqun","total"=>322.02),
            array("uname"=>"linina01","total"=>316.8),
            array("uname"=>"duyanping","total"=>315),
            array("uname"=>"denghaoc1111","total"=>315),
            array("uname"=>"liudelan0201","total"=>315),
            array("uname"=>"cqy","total"=>292.48),
            array("uname"=>"hmeng","total"=>280),
            array("uname"=>"donghaiq","total"=>275.67),
            array("uname"=>"gm","total"=>271.07),
            array("uname"=>"xiaor","total"=>259),
            array("uname"=>"zhaoxueni","total"=>259),
            array("uname"=>"lx0328","total"=>259),
            array("uname"=>"bjwanglili","total"=>250.83),
            array("uname"=>"yuhang01","total"=>247.8),
            array("uname"=>"kongqunyan","total"=>245),
            array("uname"=>"lxhong1","total"=>226.48),
            array("uname"=>"huangzhimin01","total"=>212.92),
            array("uname"=>"chenxuechun01","total"=>210),
            array("uname"=>"xj1","total"=>210),
            array("uname"=>"zhuxr","total"=>210),
            array("uname"=>"fangkuanqin","total"=>210),
            array("uname"=>"chq2","total"=>210),
            array("uname"=>"changjinping","total"=>210),
            array("uname"=>"wangjup","total"=>210),
            array("uname"=>"zss","total"=>210),
            array("uname"=>"xiamin","total"=>210),
            array("uname"=>"zyyan","total"=>210),
            array("uname"=>"zlingli","total"=>189),
            array("uname"=>"lvxl0220","total"=>175),
            array("uname"=>"yangl1210","total"=>175),
            array("uname"=>"wuli1","total"=>175),
            array("uname"=>"zhangyanmei02","total"=>164.5),
            array("uname"=>"panh0325","total"=>164.5),
            array("uname"=>"zhuping02","total"=>164.5),
            array("uname"=>"wulj","total"=>164.5),
            array("uname"=>"xiongmol","total"=>164.5),
            array("uname"=>"jianshaobing","total"=>140),
            array("uname"=>"wanggy01","total"=>140),
            array("uname"=>"gonggaoxiang","total"=>140),
            array("uname"=>"sulz","total"=>140),
            array("uname"=>"shenjhua","total"=>140),
            array("uname"=>"luyy","total"=>140),
            array("uname"=>"fanjing","total"=>140),
            array("uname"=>"lq1120","total"=>140),
            array("uname"=>"liushujiao","total"=>140),
            array("uname"=>"yinr0905","total"=>129.5),
            array("uname"=>"sunyangjuan","total"=>129.5),
            array("uname"=>"xushan1","total"=>129.5),
            array("uname"=>"liumin03","total"=>128.92),
            array("uname"=>"qiuxp","total"=>124.22),
            array("uname"=>"wangzhengjn","total"=>117.66),
            array("uname"=>"luhaimei","total"=>94.66),
            array("uname"=>"wenbaohua","total"=>94.5),
            array("uname"=>"taoyr","total"=>93.6),
            array("uname"=>"xujinwh","total"=>80.34),
            array("uname"=>"wuy0712","total"=>78.24),
            array("uname"=>"xingzhen","total"=>70),
            array("uname"=>"wangjinju","total"=>70),
            array("uname"=>"caidf","total"=>70),
            array("uname"=>"tanbiy","total"=>70),
            array("uname"=>"zhneglj","total"=>70),
            array("uname"=>"liuywh","total"=>70),
            array("uname"=>"lijuan12","total"=>70),
            array("uname"=>"zhanghuit","total"=>70),
            array("uname"=>"zhangming06","total"=>70),
            array("uname"=>"hexiumei","total"=>66.5),
            array("uname"=>"zoujuan","total"=>58.33),
            array("uname"=>"lvwenjing","total"=>58.33),
            array("uname"=>"wuxiaolan","total"=>56.4),
            array("uname"=>"maoxianhu","total"=>52.5),
            array("uname"=>"yangchen1","total"=>48.66),
            array("uname"=>"bjhelina","total"=>46.67),
            array("uname"=>"xiext","total"=>46.66),
            array("uname"=>"HULIPING","total"=>40),
            array("uname"=>"dongyanyan","total"=>36),
            array("uname"=>"wuxinyuan02","total"=>35.12),
            array("uname"=>"jianghaihong","total"=>35),
            array("uname"=>"wangwz","total"=>35),
            array("uname"=>"liujinping2","total"=>35),
            array("uname"=>"huannina","total"=>35),
            array("uname"=>"weikunming","total"=>34.96),
            array("uname"=>"yuzhenmin","total"=>32.07),
            array("uname"=>"liangsenf","total"=>29.17),
            array("uname"=>"lima","total"=>29.17),
            array("uname"=>"hljqg","total"=>29.17),
            array("uname"=>"wangzhaozhao1","total"=>29.17),
            array("uname"=>"byqing","total"=>29.17),
            array("uname"=>"xiaxiuli","total"=>29.17),
            array("uname"=>"mojiali","total"=>27.63),
            array("uname"=>"zenglx","total"=>24),
            array("uname"=>"wangli001","total"=>24),
            array("uname"=>"wangcg0627","total"=>23.33),
            array("uname"=>"lishuqin","total"=>22.39),
            array("uname"=>"xuli01","total"=>18.8),
            array("uname"=>"zhoup","total"=>17.5),
            array("uname"=>"lvhongyan","total"=>17.5),
            array("uname"=>"dah","total"=>16),
            array("uname"=>"chenxitnong","total"=>14.6),
            array("uname"=>"liqian05","total"=>14.58),
            array("uname"=>"zhouyux","total"=>12.15),
            array("uname"=>"chenxianj","total"=>11.68),
            array("uname"=>"zhminn","total"=>11.67),
            array("uname"=>"lxt123","total"=>11.67),
            array("uname"=>"manran","total"=>11.67),
            array("uname"=>"xiaqianwx","total"=>11.67),
            array("uname"=>"chenzhiwei","total"=>11.67),
            array("uname"=>"lvning","total"=>11.67),
            array("uname"=>"tangyon","total"=>11.67),
            array("uname"=>"yangaiping","total"=>9.75),
            array("uname"=>"fengxiaoying","total"=>8.76),
            array("uname"=>"wanglq","total"=>8.08),
            array("uname"=>"lji","total"=>8),
            array("uname"=>"quanjj","total"=>8),
            array("uname"=>"zhaobing","total"=>8),
            array("uname"=>"yxz","total"=>7.08),
            array("uname"=>"chenzhaoks","total"=>5.84),
            array("uname"=>"zhengl","total"=>5.84),
            array("uname"=>"wangwei3","total"=>5.84),
            array("uname"=>"tianjiqiang","total"=>5.84),
            array("uname"=>"GFHT18","total"=>5.84),
            array("uname"=>"xiejing01","total"=>5.83),
            array("uname"=>"xuchao12","total"=>5.83),
            array("uname"=>"wangqiuz","total"=>5.83),
            array("uname"=>"cuixm","total"=>5.38),
            array("uname"=>"wying0","total"=>5.02),
            array("uname"=>"chenxiuf","total"=>4.64),
            array("uname"=>"wangyangyang1","total"=>4.41),
            array("uname"=>"liangyuan","total"=>4),
            array("uname"=>"liliangg","total"=>3.33),
            array("uname"=>"yangel","total"=>2.92),
            array("uname"=>"shiwb","total"=>2.92),
            array("uname"=>"lijuan06","total"=>2.92),
            array("uname"=>"zhangqian2","total"=>2.92),
            array("uname"=>"zhuweidong","total"=>2.92),
            array("uname"=>"wangfang11","total"=>2.92),
            array("uname"=>"chenlihua1","total"=>2.92),
            array("uname"=>"shuqingjing","total"=>2.92),
            array("uname"=>"lsfujz","total"=>2.92),
            array("uname"=>"wangguoji","total"=>2.92),
            array("uname"=>"huangxiaohuan","total"=>2.92),
            array("uname"=>"wangshouchao","total"=>2.92),
            array("uname"=>"renjianfang","total"=>2.92),
            array("uname"=>"jinyunyang","total"=>2.92),
            array("uname"=>"chenyan1026","total"=>2.92),
            array("uname"=>"liuli123","total"=>2.92),
            array("uname"=>"shangmeiyun","total"=>2.92),
            array("uname"=>"chenxiangjun","total"=>2.92),
            array("uname"=>"yanhuap","total"=>2.92),
            array("uname"=>"zhangyuying02","total"=>2.83),
            array("uname"=>"guoqian","total"=>2.31),
            array("uname"=>"glip","total"=>1.32),
            array("uname"=>"zhangdanp","total"=>0.68),
            array("uname"=>"zhouru","total"=>0.58),
            array("uname"=>"qijhua","total"=>0.35),
            array("uname"=>"zhoudongm","total"=>0.34),
            array("uname"=>"zhangchangyu","total"=>0.33),
            array("uname"=>"xujing04","total"=>0.33),
            array("uname"=>"helixiang","total"=>0.33),
            array("uname"=>"wuyingying01","total"=>0.05),
            array("uname"=>"liweii","total"=>0.03),
            array("uname"=>"daihf","total"=>0.02),
            array("uname"=>"zhoushuyun","total"=>0.01),
        );
        $template = '提成系统多发奖励，扣回【{total}】饭票。如有疑问可咨询总部客户服务部。';
        $i = 0;
        foreach ($arr as $key => $val){
            //if ($key == 0){
            $note = str_replace("{total}", $val['total'], $template);
            $model = new RedpacketDebit();
            $model->note = $note;
            $model->name = $val['uname'];
            $model->amount = $val['total'];
            $model->debit_type = 'reward';
            $model->account_type = 2;
            $model->create_time = time();
            if ($model->save()){
                $i++;
                echo $val['uname']."保存成功！\n<br>";
            }else {
                echo $val['uname']."保存失败！\n<br>";
            }
            /* }else {
                break;
            } */
        }
        echo '完成数：'.$i;
    }

}