<?php
/*
 * @version 宝箱活动表格
 */
class BaoXiangBiaoGeController extends CController {
    public $layout = false;
    public function actionIndex(){
        $sql="select * from bao_all_num order by create_time";
        $baoNumArr=Yii::app()->db->createCommand($sql)->queryAll();
        $sqlSum="select sum(register_num) as total_1,sum(activity_num) as total_2,sum(banner_click_num) as total_3,sum(bao_ling_user_num) as total_4,sum(bao_chou_user_num) as total_5,sum(red_num) as total_6,sum(blue_num) as total_7,sum(green_num) as total_8,sum(yellow_num) as total_9,sum(purple_num) as total_10,sum(yao_num) as total_11,sum(bao_open) as total_12,sum(one_yuan_num) as total_13,sum(eweixiu_num) as total_14,sum(ezufang_num) as total_15,sum(youlun_num) as total_16,sum(youhuiquan_num) as total_17,sum(youhuiquan_use_num) as total_18,sum(youhuiquan_num_h) as total_19,sum(youhuiquan_use_num_h) as total_20 from bao_all_num";
        $he=Yii::app()->db->createCommand($sqlSum)->queryAll();
        $this->render('index', array(
            'baoNumArr'=>$baoNumArr,
            'he'=>$he,
         ));
    }
}





