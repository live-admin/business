<?php
/**
 * Created by PhpStorm.
 * User: taodanfeng
 * Date: 2016/10/17
 * Time: 18:07
 */
class LectureController extends ActivityController
{
    public $beginTime='2016-10-16 00:00:00';//活动开始时间
    //正式开始时间
    //public $beginTime='2016-10-24 00:00:00';//活动开始时间
    public $endTime='2016-11-03 23:59:59';//活动结束时间
    public $secret = 'le^ct*ur%e';
//    //测试商品id
    //private $good_ids='29750';
    public $layout = false;
    //正式商品id
    private $good_ids='37606';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }

    //根据彩特供商品id获取商家链接
    public function actionIndex(){
        $customer = $this->getUserInfo();
        if (empty($customer)){
            exit("参数错误！");
        }
        $userID = $customer->id;
        //彩特供
        $ctgUrl = $this->getUrl($userID);
        $pid=$this->getPid($this->good_ids);
        $this->render('/v2016/lecture/index',array(
            'ctgUrl' => $ctgUrl,
            'pid'=>$pid
        ));
    }
    //获取彩特供url
    private function getUrl($userID){
        if(empty($userID)){
            return '';
        }
        $SetableSmallLoansModel = new SetableSmallLoans();
        //彩生活特供
        $href3 = $SetableSmallLoansModel->searchByIdAndType(39, '', $userID);
        if ($href3) {
            $tgHref = $href3->completeURL;
        }
        else {
            $tgHref = '';
        }
        return $tgHref;
    }
    //获取优惠pid
    private function getPid($goods_id){
        $connection = Yii::app()->db;
        $Sql = 'SELECT `id`  FROM `cheap_log` WHERE `goods_id`=:goods_id and `status`=0 and `is_deleted`=0';
        $command = $connection->createCommand($Sql);
        $command->bindParam(':goods_id', $goods_id, PDO::PARAM_STR);
        $list = $command->queryColumn();
        if ($list) {
          return $list[0];
        }else{
            return '';
        }
    }


}