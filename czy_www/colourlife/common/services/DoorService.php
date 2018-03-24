<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/7
 * Time: 10:55
 */
Yii::import('common.services.BaseService');
class DoorService extends BaseService
{
    public function __construct()
    {
    }

    /**
     * @param $customer_id
     * @param $code
     * @return bool
     * 保存门禁用户关联信息
     */
    public function addRelation($customer_id , $code)
    {
        if(empty($customer_id) || empty($code))
        {
            return true;
        }
        try
        {
            $exist = DoorUserRelation::model()->findByAttributes(array('user_id'=>$customer_id , 'qrcode'=>$code));
            if(empty($exist))
            {
                $model = new DoorUserRelation();
                $model->qrcode = $code;
                $model->user_id = $customer_id;
                $model->create_time = time();
                $model->save();
            }
            return true;
        }catch (Exception $e)
        {
            return true;
        }

    }

    /**
     * 完善门禁信息
     */
    public function perfectDoor()
    {
        $begin_yesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $end_today = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $sql = "SELECT qrcode , user_id FROM door_user_relation WHERE create_time>={$begin_yesterday} AND create_time<= {$end_today}";
        $door_qr = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($door_qr))
        {
            foreach($door_qr as $key=>$value)
            {
                $exist = DoorInfo::model()->findByAttributes(array('qrcode' => $value['qrcode']));
                if(empty($exist))
                {
                    $door_info = $this->getDoorInfo($value['qrcode'] , $value['user_id']);
                    if($door_info)
                    {
                        $model = new DoorInfo();
                        $model->qrcode = $value['qrcode'];
                        $model->name = $door_info['name'];
                        $model->bid = $door_info['bid'];
                        $model->unitid = $door_info['unitid'];
                        $model->status = $door_info['status'];
                        $model->doorcode = $door_info['doorcode'];
                        $model->modifiedtime = $door_info['modifiedtime'];
                        $model->doortype = $door_info['doortype'];
                        $model->conntype = $door_info['conntype'];
                        $model->version = $door_info['version'];
                        $model->factorytype = $door_info['factorytype'];
                        $model->extra = $door_info['extra'];
                        $model->wifienable = $door_info['wifienable'];
                        $model->wificode = $door_info['wificode'];
                        $model->dynamic = $door_info['dynamic'];
                        $model->dynamicqr = $door_info['dynamicqr'];
                        $model->address = $door_info['address'];

                        $model->save();
                    }
                }

            }
        }
    }

    /**
     * @param $code
     * @return null
     * 获取单个门禁信息
     */
    public function getDoorInfo($code , $customer_id)
    {
        if(empty($code))
        {
            return null;
        }
        $preFun = 'door/get';
        $param = [
            'qrcode' => $code
        ];
        $resetUrl = '';
        $re = new ConnectWetown();
        $result =  $re->getRemoteData2($preFun, $param, $resetUrl , false , $customer_id);
        $result = (json_decode($result , true));
        if(isset($result['result']) && $result['result'] == 0 && isset($result['door']))
        {
            return $result['door'];
        }else{
            return null;
        }
    }
}