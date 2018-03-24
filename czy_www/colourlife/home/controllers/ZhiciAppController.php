<?php
class ZhiciAppController extends Controller
{   

	public function actionIndex()
    {
		$this->layout = 'xeiyi';
		$this->render('zhici');
    }

}