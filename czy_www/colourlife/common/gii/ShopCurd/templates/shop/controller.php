<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
    public $layout = '//layouts/column2';

    /**
     * 处理模型
     * @var <?php echo $this->modelClass; ?>
     */
    private $_model;

    /**
     * 缓存的面包条数组
     * @var array
     */
    private $_breadcrumbs;

    /**
     * 缓存的二级 Tab 菜单
     * @var array
     */
    private $_subMenu;

    /**
     * 点亮当前菜单
     * @param CAction $action
     * @return bool
     */
    /*
    protected function beforeAction($action)
    {
        $this->setMenuActive('other');
        return parent::beforeAction($action);
    }
    */

    /**
     * 返回导航
     * @return array
     */
    public function getBreadcrumbs()
    {
        if (isset($this->_breadcrumbs)) {
            return $this->_breadcrumbs;
        }
        $action = $this->action->id;
        $model = $this->_model;
        $breadcrumbs = array(
            $model->modelName => array('index'),
        );
        // 有上级时
        /* foreach ($model->getParents() as $parent) {
            $breadcrumbs[$parent->name] = array('index', 'id' => $parent->id);
        } */
        if ($action == 'index') {
            $breadcrumbs[] = '管理';
        } else if ($action == 'create') {
                $breadcrumbs[] = '增加';
        } else {
            $breadcrumbs[$model->name] = array('view', 'id' => $model->id);
            switch ($action) {
                case 'view': $breadcrumbs[] = '查看'; break;
                case 'update': $breadcrumbs[] = '编辑'; break;
                // 如果有状态时
                // case 'enable': $breadcrumbs[] = '启用'; break;
                // case 'disable': $breadcrumbs[] = '禁用'; break;
                // case 'move': $breadcrumbs[] = '转移'; break;
                case 'delete': $breadcrumbs[] = '删除'; break;
            }
        }
        $this->_breadcrumbs = $breadcrumbs;
        return $this->_breadcrumbs;
    }

    /**
     * 返回二级 Tab 菜单
     * @return array
     */
    public function getSubMenu()
    {
        if (isset($this->_subMenu)) {
            return $this->_subMenu;
        }
        $action = $this->action->id;
        $model = $this->_model;
        $menu = array();
        $id = $model->id;
        if (in_array($action, array('index', 'create'))) {
            $id = isset($model->parent_id)?$model->parent_id:null;
            if (empty($id)) {
                $model = null;
                $menu['index'] = array('label' => '<?php echo $this->modelClass; ?>', 'url' => array('index'));
            } else {
                $model = $model->findByPk($id);
            }
        }

        if (!empty($model)) {
            $menu['index'] = array('label' => $model->name, 'url' => array('index', 'id' => $id));
            $menu['view'] = array('label' => '查看', 'url' => array('view', 'id' => $id));
            $menu['update'] = array('label' => '编辑', 'url' => array('update', 'id' => $id),
                'visible' => Yii::app()->user->checkAccess('op_backend_<?php echo strtolower($this->modelClass); ?>_update'));
            if (!isset($menu[$action])) {
                switch ($action) {
                    // case 'move': $menu['move'] = array('label' => '转移', 'url' => array('move', 'id' => $id)); break;
                    // case 'enable': $menu['enable'] = array('label' => '启用', 'url' => array('enable', 'id' => $id)); break;
                    // case 'disable': $menu['disable'] = array('label' => '禁用', 'url' => array('disable', 'id' => $id)); break;
                    case 'delete': $menu['delete'] = array('label' => '删除', 'url' => array('delete', 'id' => $id)); break;
                }
            }
        }

        $menu['create'] = array('label' => '增加', 'url' => array('create', 'id' => $id),
            'visible' => Yii::app()->user->checkAccess('op_backend_<?php echo strtolower($this->modelClass); ?>_create'));

        if (isset($menu[$action])) {
            $menu[$action]['active'] = true;
        }

        $this->_subMenu = $menu;
        return $this->_subMenu;
    }

    /**
     * 权限控制
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('list'), // Ajax 获取地区列表
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('index', 'view'),    // 列表 查看
                'expression' => '$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_view")',
            ),
            array('allow',
                'actions' => array('update', 'enable', 'disable', 'move'),  // 编辑 启用 禁用 转移
                'expression' => '$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_update")',
            ),
            array('allow',
                'actions' => array('create'),   // 创建
                'expression' => '$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_create")',
            ),
            array('allow',
                'actions' => array('delete'),   // 删除
                'expression' => '$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_delete")',
            ),
            /*
            array('allow',
                'actions' => array('audit'),   // 审核
                'expression' => '$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_audit")',
            ),
            */
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $this->_model = $model;
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id = 0)
    {
        $this->checkParentExist($id);
        $model = new <?php echo $this->modelClass; ?>;
        // $model->parent_id = $id;

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
            $model->setScenario($this->action->id);
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->_model = $model;
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
            $model->setScenario($this->action->id);
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->_model = $model;
        $this->render('update', array(
            'model' => $model,
        ));
    }

    private function _confirmAction($id, $func, $msg, $returnUrl)
    {
        $action = $this->action->id;
        if (count($id) > 1 && Yii::app()->request->isPostRequest) {
            $this->checkBatchAccessWithCount(count($id));
            $can = true;
            foreach ($this->loadModels($id) as $model) {
                $model->setScenario($action);
                if (!$model->validate()) {
                    // 只要有一个项目验证未通过
                    $can = false;
                    $error = $this->errorSummary($model);
                    break;
                }
            }
            if ($can) {
                foreach ($this->loadModels($id) as $model) {
                    $func($model);
                }
            } else {
                throw new CHttpException(400, $error);
            }
        } else {
            $id = @$id[0];  // 强制转换
            $model = $this->loadModel($id);
            if (Yii::app()->request->isPostRequest) {
                $model->setScenario($action);
                if ($model->validate()) {
                    $func($model);
                    if (!isset($_GET['ajax'])) {
                       $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $returnUrl);
                    } else {
                        return;
                    }
                } else {
                    if (isset($_GET['ajax'])) {
                        throw new CHttpException(400, $this->errorSummary($model));
                    }
                }
            }
            $this->_model = $model;
            $this->render('confirm', array(
                'model' => $model,
                'msg' => $msg,
            ));
        }
    }

    /**
     * 启用
     * @param $id
     * @throws CHttpException
     */
   /* public function actionEnable(array $id)
    {
        $this->_confirmAction($id, create_function('$a', '$a->enable();$a->save(false);'), array(
            'label' => '启用',
            'icon' => 'ok-sign',
        ), array('disable', 'id' => @$id[0]));
    } */

    /**
     * 禁用
     * @param $id
     * @throws CHttpException
     */
    /* public function actionDisable(array $id)
    {
        $this->_confirmAction($id, create_function('$a', '$a->disable();$a->save(false);'), array(
            'label' => '禁用',
            'icon' => 'remove-sign',
        ), array('enable', 'id' => @$id[0]));
     }*/

    /**
     * 删除
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete(array $id)
    {
        $this->_confirmAction($id, create_function('$a', '$a->delete();'), array(
            'label' => '删除',
            'icon' => 'trash',
        ), array('index'));
    }

    /**
     * 转移
     * @param $id
     */
   /* public function actionMove($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];
            $model->setScenario($this->action->id);
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->_model = $model;
        $this->render('move', array(
            'model' => $model,
        ));
    }*/

    /**
     * Lists all models.
     */
    public function actionIndex($id = 0)
    {
        $model = new <?php echo $this->modelClass; ?>('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['<?php echo $this->modelClass; ?>'])) {
            $model->attributes = $_GET['<?php echo $this->modelClass; ?>'];
        }

        // 管理指定的 $id 下的地区
       /* if (empty($model->search_all)) {
            $this->checkParentExist($id);
            $model->parent_id = $id;
        }*/

        $this->_model = $model;
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * @param int $id
     * @param int $fid
     */
    public function actionList($id = 0, $fid = 0)
    {
        $data = array();
        foreach (<?php echo $this->modelClass; ?>::model()->findEnabledChildrenByPk($id, $fid) as $model) {
            $data[$model->id] = array(
                'name' => $model->name,
            );
        }
        echo CJSON::encode($data);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, '请求的内容不存在。');
        }
        return $model;
    }

    /**
     * 取出多个模型对象用于批量操作
     * @param $array
     * @return array|CActiveRecord|mixed|null
     * @throws CHttpException
     */
    public function loadModels($array)
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition("id", $array);
        $models = <?php echo $this->modelClass; ?>::model()->findAll($criteria);
        if (empty($models)) {
            throw new CHttpException(404, '请求的内容不存在。');
        }
        return $models;
    }

    protected function checkParentExist($id)
    {
        if (!empty($id) && <?php echo $this->modelClass; ?>::model()->findByPk($id) === null) {
            throw new CHttpException(404, '请求的内容不存在。');
        }
    }

    /**
     * 检查批量处理权限和批量处理数
     * @param $count
     * @throws CHttpException
     */
    protected function checkBatchAccessWithCount($count)
    {
        if (!Yii::app()->user->checkAccess('op_backend_<?php echo strtolower($this->modelClass); ?>_batch')) {
            throw new CHttpException(403, '没有权限进行批量操作。');
        }
        if ($count > Yii::app()->params['batchCount']) {
            $count = Yii::app()->params['batchCount'];
            throw new CHttpException(403, "不能批量操作 {$count} 个以上的数据。");
        }
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === '<?php echo strtolower($this->modelClass); ?>-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}


<?php echo "\n ?> "; ?>
