<?php
//热门房屋
class HothouseController extends CController
{
    public function actionIndex($cate_id = 0)
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('isdelete=0');
        $criteria->order="top DESC,order_seq ASC,pub_date DESC";

        //房屋列表
        $house_list = HouseUrl::model()->findAll($criteria);

        $this->renderPartial('index', array(
            'house_list' => $house_list,
        ));
    }
    public function actionBieyangcheng(){
    	$this->renderPartial('bieyangcheng');
    }
}