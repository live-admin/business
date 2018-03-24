<?php
class PagePromptAppController extends Controller
{   

	public function actionNotCid()
    {
		$this->layout = 'xeiyi';
		$this->render('LinliNotCid');
    }

}