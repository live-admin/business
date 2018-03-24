<?php
class CommerceController extends CController {
    public function actionCategory(){
        $this->renderPartial('category');
    }
    
    //招供应商列表
    public function actionSupply(){
        $pageIndex = 1;
        $list = $this->getCommerceData($pageIndex,Commerce::TYPE_SUPPLIER);
        $this->renderPartial('supply', array(
            'list' => $list,
        ));
    }
    
    //招加盟商列表
    public function actionLeague(){
        $pageIndex = 1;
        $list = $this->getCommerceData($pageIndex,Commerce::TYPE_LEAGUE);
        $this->renderPartial('league', array(
            'list' => $list,
        ));
    }
    
    public function getCommerceData($pageIndex,$type){
        $criteria = new CDbCriteria;
        $criteria->addCondition('is_deleted =0');
        $criteria->addCondition('state=0');
        //$criteria->addCondition('type='.$type);
        $criteria->compare('type', $type);
        $criteria->order = ' priority DESC';
        $criteria->distinct = true;        
        $criteria->limit = 100;
        $criteria->offset = ($pageIndex - 1)*3;   
        $count = Commerce::model()->count($criteria);
        if($count >= ($pageIndex - 1)*3){
            return Commerce::model()->findAll($criteria);            
        }else{
            return "";
        }                
    }
    
    //AJAX获取列表
    public function actionDataByAjax(){
        $pageIndex = $_POST['pageIndex'];
        $type = $_POST['type'];
        $type=intval($type);
        $list = $this->getCommerceData($pageIndex,$type);
        $result_list = array();
        if(!empty($list)){
            foreach($list as $key=> $data){                
                $result_list[$key]['title'] = $data->title;
                $result_list[$key]['introduction'] = $data->introduction;
                $result_list[$key]['logoImgUrl'] = $data->logoImgUrl;
                $result_list[$key]['href'] = CHtml::normalizeUrl(array('/commerce/show','id'=>$data->id));               
            }
        }
        echo CJSON::encode(array("list"=>$result_list));
    }

    //下页内容展示
    public function actionView($id){
        //$model = Commerce::model()->findByPk($id);
    	$model = Commerce::model()->findByPk(intval($id));
        $this->renderPartial('view', array(
            'model' => $model,
        ));
    }

    //详情展示
    public function actionShow($id){
        //$model = Commerce::model()->findByPk($id);
    	$model = Commerce::model()->findByPk(intval($id));
        $this->renderPartial('show', array(
            'model' => $model,
        ));
    }

    public function actionApplyForProcess(){
        $username = $_POST['username'];
        $sex = $_POST['sex'];
        $tel = $_POST['tel'];
        $note = $_POST['note'];
        /* $type = $_POST['type'];
        $commerce_id = $_POST['c_id']; */
        $type = intval($_POST['type']);
        $commerce_id = intval($_POST['c_id']);
        $model = new ApplyForCommerce("create");
        $model->username = $username;
        $model->sex = $sex;
        $model->tel = $tel;
        $model->note = $note;
        $model->create_time = time();
        $model->type = $type;
        $model->commerce_id = $commerce_id;
        if($model->save()){
            echo CJSON::encode(1);
        }else{
            echo CJSON::encode(0);
        }
    }

}