<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/3/17
 * Time: 18:37
 * 彩富内页展示
 */

class TreasureLifeController extends Controller
{

    private $_userId = 0;

    /**
     * 验证登录
     */
    private function checkLogin()
    {
        if (empty($_REQUEST['userid']) && empty($_SESSION['userid'])) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        } else {
            $custId = 0;

            if (isset($_REQUEST['userid'])) {  //优先有参数的
                $custId = intval($_REQUEST['userid']);
                $_SESSION['userid'] = $custId;
            } else if (isset($_SESSION['userid'])) {  //没有参数，从session中判断
                $custId = $_SESSION['userid'];
            }

            $custId=intval($custId);
            $customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if (empty($custId) || empty($customer)) {
                exit('<h1>用户信息错误，请重新登录</h1>');
            }

            $this->_userId = $custId;
        }
    }


    /*欠费组成*/
    public function actionArrears()
    {
        $this->renderPartial('arrears');
    }

    /*基础问题*/
    public function actionQuestion()
    {
        $this->renderPartial('question');
    }

    /*资金问题*/
    public function actionFundingProblems()
    {
        $this->renderPartial('fundingProblems');
    }

    /*资金保障*/
    public function actionSecurityGuarantee()
    {
        $this->renderPartial('securityGuarantee');
    }

    /*停车费*/
    public function actionParkingFees()
    {
        $this->renderPartial('parkingFees');
    }

    /*物业费*/
    public function actionPropertyFees()
    {
        $this->renderPartial('propertyFees');
    }

    /*彩富内页*/
    public function actionTreasureLifeInside()
    {
        $this->checkLogin();

        $result = array(
            'is_bind' => false,
            'name' => '',
            'mobile' => '',
            'url' => ''
        );

        $ret = $this->getCustomerBindingManagerObj();

        if(false === $ret) {
            $model = SetableSmallLoans::model();
            $resource = $model->searchByIdAndType('jiafang', '', $this->_userId,false);
            if ($resource)
                $result['url'] = $resource->completeURL;
        }
        else {
            $result['is_bind'] = true;
            $result['name'] = $ret['name'];
            $result['mobile'] = $ret['mobile'];
            $result['portrait'] = $ret['portrait'];

        }

        $this->renderPartial('treasureLifeInside', array('result'=>$result));
    }

    /**
     * 获取用户和客户经理之间的绑定对象
     * $this->userId为当前用户id
     * @return 客户经理彩之云Id 不存在返回 null
     */
    private function getCustomerBindingManagerObj()
    {
        $result = CustomerBindManager::model()->with("manager", "proprietor")->find("proprietor_id=:uid", array(":uid"=>$this->_userId));

        if(empty($result))
            return false;

        $model = Customer::model()->findByPk(intval($result->manager_id));

        $employeemodel = EmployeeBindCustomer::model()->with("employee")->find("customer_id=:customerId", array(":customerId"=>$result->manager_id));

        return array(
            'name' => $employeemodel ? $employeemodel->employee->name : '',
            'mobile' => $model ? $model->mobile : '',
            'portrait' =>$model->getPortraitUrl(),
        );
    }

    /**
     * 彩富新人有礼活动
     */
    public function actionNewActivity()
    {
        $this->checkLogin();
        $HomeConfig = new HomeConfigResource();
        $url = '';
        $one = $HomeConfig->getResourceByKeyOrId('oneyuan',1,$this->_userId);
        if($one)
        {
            $url = $one->completeURL;
        }
        $this->renderPartial('newActivity', array('one_url'=>$url));

    }




}

