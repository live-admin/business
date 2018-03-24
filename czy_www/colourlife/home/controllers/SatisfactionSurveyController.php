<?php

/**
 * 满意度调查
 * Class SatisfactionSurveyController
 */
class SatisfactionSurveyController extends Controller
{
    protected $satisfactionSurveyUrl;
    protected $shortUrl;

    public function init()
    {
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->satisfactionSurveyUrl = 'http://www-czytest.colourlife.com/satisfactionSurvey?cust_id=';
            $this->shortUrl = 'http://test.czy.im';
        } else {
            $this->satisfactionSurveyUrl = 'http://www.colourlife.com/satisfactionSurvey?cust_id=';
            $this->shortUrl = 'http://czy.im';
        }
    }

    public function actionIndex()
    {
        if (!isset($_GET['cust_id'])) {
            throw new CHttpException(404,"页面不存在");
        }
        $customer_id = intval(Yii::app()->request->getParam('cust_id'));
        $flag="true";
        $date = date('Y-m') . '-01';
        $month = 1;
        $end_date =  date('Y-m-d', strtotime($date."+{$month} month -1 day"));
        $start_date = strtotime($date . ' 00:00:00');
        $end_date = strtotime($end_date . ' 23:59:59');
        $re = SatisfactionSurvey::model()->find("customer_id=:uid AND create_time>=:start_date AND create_time<=:end_date",array(':uid' => $customer_id, ':start_date' => $start_date, ':end_date' => $end_date));
        if (!empty($re)) {
            $flag = "false";
        }
        $this->renderPartial('index', ['flag' => $flag, 'userid' => $customer_id]);
    }

    public function actionPostAnswer()
    {
        $result1 = $this->getAnswer($_POST['evaluation_1']);
        $result2 = $this->getAnswer($_POST['evaluation_2']);
        $result3 = $this->getAnswer($_POST['evaluation_3']);
        $result4 = $this->getAnswer($_POST['evaluation_4']);
        $result5 = $this->getAnswer($_POST['evaluation_5']);
        $result6 = $this->getAnswer($_POST['evaluation_6']);
        $result7 = $this->getAnswer($_POST['evaluation_7']);
        $results = "1" . $result1 . ",2" . $result2 . ",3". $result3 . ",4" . $result4 . ",5" . $result5 . ",6" . $result6 . ",7" . $result7;
        $model = new SatisfactionSurvey('create');
        $model->answers = $results;
        $model->customer_id = intval($_POST['userid']);
        $model->create_time = time();
        $model->ip = Yii::app()->request->getUserHostAddress();
        $model->user_agent = Yii::app()->request->userAgent;
        $model->phone_type = $this->getPhoneType();
        $model->note = htmlspecialchars($_POST['note']);
        $model->save();

        $this->redirect("result");
    }

    public function actionResult()
    {
        $this->renderPartial('result');
    }

    /**
     * 导入excel
     * @throws CHttpException
     */
    public function actionImport() {
        if(!isset($_GET['access_token']) || $_GET['access_token'] != 'colourlife') {
            throw new CHttpException(404,"页面不存在");
        }

        if (isset($_POST['submit'])) {
            $extension = strtolower(pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION) );
            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
                $excelFile = '../../uploads/uploads/' . date("YmdHis") . mt_rand(1, 9999) . "." . $extension;
                $is = move_uploaded_file($_FILES['excel']['tmp_name'], $excelFile);
                if (!$is) exit('上传文件失败！');
                Yii::$enableIncludePath = false;
                Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
                if ($extension =='xlsx') {
                    $reader = PHPExcel_IOFactory::createReader('Excel2007');
                } else if ($extension =='xls') {
                    $reader = PHPExcel_IOFactory::createReader('Excel5');
                } else if ($extension=='csv') {
                    $reader = PHPExcel_IOFactory::createReader('CSV');
                }
                $PHPExcel = $reader->load($excelFile);
                $sheet = $PHPExcel->getSheet(0); // 读取第一个工作表从0读起
                $highestRow = $sheet->getHighestRow(); // 取得总行数
//                $highestColumn = $sheet->getHighestColumn(); // 取得总列数
                $sql = "INSERT INTO `colourlife`.`satisfaction_survey_mobile`(mobile) VALUES ";
                // 每次读取一行，再在行中循环每列的数值
                for ($row = 2; $row <= $highestRow; $row++) {
                    $val = $sheet->getCellByColumnAndRow(0, $row)->getValue();
                    if (is_numeric($val)) {
                        $sql .= " ('" . addslashes(htmlspecialchars($val)) . "'), ";
                    }
                }
                $sql = rtrim($sql, ', ') . ';';

                //清空数据表
                $truncate_sql = 'TRUNCATE TABLE satisfaction_survey_mobile';
                Yii::app()->db->createCommand($truncate_sql)->execute();

                $result = Yii::app()->db->createCommand($sql)->execute();
                @unlink($excelFile);
                if ($result) {
                    $this->redirect("Console?access_token=colourlife");
                } else {
                    echo '导入失败';
                }
                exit;
            } else {
                exit('上传文件格式不支持！');
            }
        }

        $this->renderPartial('import');
    }

    /**
     * 导出
     * @throws CHttpException
     */
    public function actionExport() {
        if(!isset($_GET['access_token']) || $_GET['access_token'] != 'colourlife') {
            throw new CHttpException(404,"页面不存在");
        }
        if (isset($_GET['submit']) && $_GET['submit'] == '导入手机号') {
            $this->redirect("Import?access_token=colourlife");
        }

        $start1 = Yii::app()->request->getParam('start');
        $end1 = Yii::app()->request->getParam('end');
        $start = strtotime($start1);
        $end = strtotime($end1);
        $msg = '';
        $sql = "select
                    customer.id customer_id,
					customer.name,
					customer.mobile,	
					ss.answers,
					ss.create_time,
					ss.note,
					ss.ip,
					ss.phone_type,
					ss.user_agent			
					from satisfaction_survey ss
					LEFT JOIN customer on customer.id = ss.customer_id
					";
        if ($start && $end) {
            $sql .= " where ss.create_time>='{$start}' and ss.create_time<='{$end}'";
        }

        $data = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($data as $key => &$value) {
            $value = array_merge($value, $this->getScore($value['answers']));
        }

        if (isset($_GET['submit']) && $_GET['submit'] == '导出满意度调查数据') {
            $this->exportExcel($data);
        }
        $this->renderPartial('export', ['data' => $data, 'msg' => $msg, 'start' => $start1, 'end' => $end1]);
    }

    public function actionSendSms()
    {
        if(!isset($_GET['access_token']) || $_GET['access_token'] != 'colourlife') {
            throw new CHttpException(404,"页面不存在");
        }

        ini_set('max_execution_time', '0');
        ob_end_clean();
        ob_implicit_flush(1);
        echo '<script type="text/javascript">function bottom(){ javascript:document.getElementsByTagName("body")[0].scrollTop=document.getElementsByTagName("body")[0].scrollHeight + 1000;}</script>';
        echo '-------开始发送短信  ' . date('Y-m-d H:i:s', time()) . '-------<br>';

        Yii::import('common.api.IceApi');
        $tpl = '【彩生活】物业诚邀您参加客户满意度调查，点击{url}参与答题，促进提升小区管理！退订回N';
        $sql = "SELECT c.id, c.mobile FROM customer c, satisfaction_survey_mobile ssm WHERE c.mobile = ssm.mobile and ssm.send_msg = 0 LIMIT 100";
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data as $datum) {
            if (!file_exists('lock')) {
                echo '-------已停止-------<br>';
                exit;
            }
            $is_short_url = $is_send_sms = 0;
            $short_url = $this->generateShortUrl($datum['id']);
            if ($short_url) {
                $is_short_url = 1;
                $send_sms = IceApi::getInstance()->sendMarketingSms($datum['mobile'], str_replace('{url}', $short_url, $tpl));
                if ($send_sms) {
                    $is_send_sms = 1;
                } else {
                    $is_send_sms = 2;
                }
            } else {
                $is_short_url = 2;
            }
            echo sprintf(
                '用户ID：%s，手机号：%s，生成短网址：%s，发送短信：%s <br/>',
                $datum['id'],
                $datum['mobile'],
                $is_short_url != 1 ? '<font color="red">失败</font>' : '<font color="green">成功</font>',
                $is_send_sms != 1 ? '<font color="red">失败</font>' : '<font color="green">成功</font>'
            );
            echo '<script>bottom()</script>';
            $update_sql = 'UPDATE satisfaction_survey_mobile SET short_url = ' . $is_short_url . ', send_msg = ' . $is_send_sms . " WHERE mobile = '" . $datum['mobile'] . "';";
            Yii::app()->db->createCommand($update_sql)->execute();
        }

        $sql = "SELECT count(*) FROM customer c, satisfaction_survey_mobile ssm WHERE c.mobile = ssm.mobile and ssm.send_msg = 0";
        $count_data = Yii::app()->db->createCommand($sql)->queryAll();
        if (count($count_data) > 0) {
            echo '<script>window.location.reload()</script>';
        }
    }

    public function generateShortUrl($customer_id)
    {
        $url = sprintf(
            '%s/yourls-api.php?signature=4cf3d19b02&action=shorturl&format=json&url=%s',
            $this->shortUrl,
            $this->satisfactionSurveyUrl . $customer_id
        );
        $response = Yii::app()->curl->get($url);
        $response = json_decode($response, true);
        if (isset($response['shorturl'])) {
            return $response['shorturl'];
        }
        return false;
//        try {
//            $response = ICEService::getInstance()->dispatch(
//                'community',
//                [
//                    'signature' => '4cf3d19b02',
//                    'action' => 'shorturl',
//                    'url' => $this->satisfactionSurveyUrl . $customer_id,
//                ],
//                [],
//                'get'
//            );
//            if (isset($response['shorturl'])) {
//                return $response['shorturl'];
//            }
//        } catch (Exception $e) {
//
//        }
    }

    public function getAnswer($answer)
    {
        $score = [
            '非常满意' => 'a',
            '比较满意' => 'b',
            '一般' => 'c',
            '不满意' => 'd',
            '非常不满意' => 'e',
        ];
        if (!isset($score[$answer])) {
            exit('请填写完整!');
        }
        return $score[$answer];
    }

    public function getScore($answer) {
        $data = [];
        $score = [
            'a' => 5,
            'b' => 4,
            'c' => 3,
            'd' => 2,
            'e' => 1,
        ];
        $answer = explode(',', $answer);
        foreach ($answer as $value) {
            if (strlen($value) == 2) {
                $data['t' . substr($value, 0, 1)] = $score[substr($value, 1, 1)];
            }
        }
        return $data;
    }

    public function getPhoneType()
    {
        $user_agent = Yii::app()->request->userAgent;
        if(strpos($user_agent, 'Android')){
            return 1;
        }else if(strpos($user_agent, 'iPhone')||strpos($user_agent, 'iPad')){
            return 2;
        }else{
            return 3;
        }
    }

    public function exportExcel($data)
    {
        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
        $objPHPExcel = new PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setTitle('满意度调查数据');
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1','总序号')
        	->setCellValue('B1','用户ID')
        	->setCellValue('C1','姓名')
        	->setCellValue('D1','电话号码')
        	->setCellValue('E1','IP地址')
        	->setCellValue('F1','手机类型')
        	->setCellValue('G1','用户UA')
        	->setCellValue('H1','总体评价')
        	->setCellValue('I1','管理服务')
        	->setCellValue('J1','保安')
        	->setCellValue('K1','设施设备')
        	->setCellValue('L1','清洁卫生')
        	->setCellValue('M1','绿化')
        	->setCellValue('N1','电梯')
        	->setCellValue('O1','意见和建议')
        	->setCellValue('P1','评价时间');

        foreach($data as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $k + 1)
                ->setCellValue('B'.$num, ' ' . $v['customer_id'])
                ->setCellValue('C'.$num, $v['name'])
                ->setCellValue('D'.$num, ' ' . $v['mobile'])
                ->setCellValue('E'.$num, $v['ip'])
                ->setCellValue('F'.$num, $v['phone_type'] == 1 ? 'android' : ($v['phone_type'] == 2 ? 'ios' : '其它'))
                ->setCellValue('G'.$num, $v['user_agent'])
                ->setCellValue('H'.$num, $v['t1'])
                ->setCellValue('I'.$num, $v['t2'])
                ->setCellValue('J'.$num, $v['t3'])
                ->setCellValue('K'.$num, $v['t4'])
                ->setCellValue('L'.$num, $v['t5'])
                ->setCellValue('M'.$num, $v['t6'])
                ->setCellValue('N'.$num, $v['t7'])
                ->setCellValue('O'.$num, $v['note'])
                ->setCellValue('P'.$num, date('Y/m/d H:i:s', $v['create_time']));
        }

        $objPHPExcel->getActiveSheet()->setTitle('满意度调查数据');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="满意度调查数据.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }

    public function actionConsole()
    {
        if(!isset($_GET['access_token']) || $_GET['access_token'] != 'colourlife') {
            throw new CHttpException(404,"页面不存在");
        }
        if (Yii::app()->request->isAjaxRequest) {
            if ($_POST['type'] == 'start') {
                file_put_contents('lock', '');
                exit(json_encode(array('code' => 0)));
            } else {
                @unlink('lock');
                exit(json_encode(array('code' => 0)));
            }
        } else {
            $this->renderPartial('console');
        }
    }

    public function actionSendStatus()
    {
        if(!isset($_GET['access_token']) || $_GET['access_token'] != 'colourlife') {
            throw new CHttpException(404,"页面不存在");
        }

        $count_sql = 'SELECT send_msg, count(*) AS num FROM satisfaction_survey_mobile ssm, customer c WHERE ssm.mobile = c.mobile GROUP BY send_msg;';
        $sql = "SELECT * FROM satisfaction_survey_mobile WHERE send_msg = 2";
        $count = Yii::app()->db->createCommand($count_sql)->queryAll();
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        echo '总数量：' .$this->getNum($count) . '<br/>';
        echo '未发送数量：' . $this->getNum($count, 0) . '<br/>';
        echo '发送成功数量：' . $this->getNum($count, 1) . '<br/>';
        echo '发送失败数量：' . $this->getNum($count, 2) . '<br/>';
        echo '<br/>---------------------<br/>';
        echo '发送失败手机列表：<br/>';
        foreach ($data as $datum) {
            echo sprintf(
                '手机号：%s，生成短网址：%s，发送短信：%s <br/>',
                $datum['mobile'],
                $datum['short_url'] == 0 ? '<font color="red">失败</font>' : '<font color="green">成功</font>',
                '<font color="red">失败</font>'
            );
        }

    }

    public function getNum($data, $type = '')
    {
        $num = $count = 0;
        foreach ($data as $datum) {
            if ($datum['send_msg'] == $type) {
                $num = $datum['num'];
            }
            $count += $datum['num'];
        }

        return $type === '' ? $count : $num;
    }
}
