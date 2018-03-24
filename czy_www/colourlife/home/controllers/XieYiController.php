<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2015/12/29
 * Time: 20:44
 */
class XieYiController extends CController
{
    public function actionPlan()
    {
        $v = (int)Yii::app()->request->getParam('v', 1);
        $type = Yii::app()->request->getParam('type', 'app');

        Yii::import('common.services.AppreciationPlanService');
        if ( !in_array($v, array(
                AppreciationPlanService::APPRECIATION_PLAN_FIRST,
                AppreciationPlanService::APPRECIATION_PLAN_SECOND,
                AppreciationPlanService::APPRECIATION_PLAN_THIRD,
                AppreciationPlanService::APPRECIATION_PLAN_FOURTH)
        )) {
            exit('参数错误');
        }


        $appreciationPlanService = new AppreciationPlanService();

        $month = $appreciationPlanService->getAppreciationPlanMonth($v);
        $rate = $appreciationPlanService->getAppreciationPlanRate($month);
        $rate = sprintf('%.2f', $rate * 100);

        if ($type == 'pc') {
            $this->renderPartial("/xieyi/plan_pc", array('month' => $month, 'rate' => $rate));
        }
        else {
            $this->renderPartial("/xieyi/plan_app", array('month' => $month, 'rate' => $rate));
        }
    }

    public function actionPlanRule()
    {
        $this->renderPartial("/xieyi/rule");
    }

}