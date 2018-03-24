<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	//菜单设置点亮
	protected function beforeAction($action)
    {
        $this->menu['<?php echo strtolower($this->modelClass); ?>']['active'] = true;
        return parent::beforeAction($action);
    }

	/**
     * 返回 Tab 菜单
     * @param $action 当前动作名
     * @param $model 模型对象
     * @return array
     */
    public function getSubMenu($action, $model)
    {
        $menu = array();
        $id = $model->id;
        $name = '';
        if (in_array($action, array('index', 'create'))) {
            $id = $model->parent_id;
            if (empty($id)) {
                $model = null;
                $menu['index'] = array('label' => '所有', 'url' => array('index'));
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
                    case 'move': $menu['move'] = array('label' => '转移', 'url' => array('move', 'id' => $id)); break;
                    case 'enable': $menu['enable'] = array('label' => '启用', 'url' => array('enable', 'id' => $id)); break;
                    case 'disable': $menu['disable'] = array('label' => '禁用', 'url' => array('disable', 'id' => $id)); break;
                    case 'delete': $menu['delete'] = array('label' => '删除', 'url' => array('delete', 'id' => $id)); break;
                }
            }
        }

        $menu['create'] = array('label' => '增加', 'url' => array('create', 'id' => $id),
            'visible' => Yii::app()->user->checkAccess('op_backend_<?php echo strtolower($this->modelClass); ?>_create'));

        if (isset($menu[$action])) {
            $menu[$action]['active'] = true;
        }
        return $menu;
    }

	public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('list'), // Ajax 获取地区列表
                'users' => array('@'),
            ),
            array('allow',  // 列表 查看
                'actions'=>array('index','view'),
                'expression'=>'$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_view")',
            ),
            array('allow',  
                'actions'=> array('update', 'enable', 'disable', 'move'),  // 编辑 启用 禁用 转移
                'expression'=>'$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_update")',
            ),
            array('allow',  
                'actions'=>array('create'),
                'expression'=>'$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_create")',
            ),
			array('allow',  
                'actions'=>array('delete'),
                'expression'=>'$user->checkAccess("op_backend_<?php echo strtolower($this->modelClass); ?>_delete")',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new <?php echo $this->modelClass; ?>;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			$model->setScenario($this->action->id);
			if($model->save())
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			$model->setScenario($this->action->id);
			if($model->save())
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['<?php echo $this->modelClass; ?>']))
			$model->attributes=$_GET['<?php echo $this->modelClass; ?>'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, '请求的内容不存在。');
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

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo $this->class2id($this->modelClass); ?>-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
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
}
