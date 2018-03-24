<?php
/**
 * This is the model class for table "branch_middle".
 *
 * The followings are the available columns in table 'branch_middle':
 * @property integer $id
 * @property integer $branch_id
 * @property string $branch_name
 * @property integer $parent_id
 * @property integer $isclose
 * @property datetime $create_time
 * @property datetime $update_time
 * @property integer $storage
 * @property integer $isdelete
 * @property integer $relation_id
 */

class BranchMiddle extends CActiveRecord
{
  
    /**
     * @var string 模型名
     */
    public $modelName = '部门中间转存';

    public static $branchByOaCount = 0;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Branch the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'branch_middle';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('branch_id,branch_name,parent_id,isclose,create_time,update_time,storage,isdelete','required','on' => 'create,update'),
        );
    }
    
   

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'branch_id' => '组织架构ID',
            'branch_name' => '组织架构名称',
            'parent_id' => '上级组织架构ID',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'isclose' => '否是禁用',
            'storage' => '录入',
            'isdelete' => '是否删除',
            'relation_id' => '关联id'
        );
    }
    
    public static function checkIsExist($branch_id){
        return BranchMiddle::model()->find('branch_id=:branch_id',array(':branch_id'=>$branch_id));
    }
    
    public static function checkIsExistByName($branch_id,$branch_name){
        return BranchMiddle::model()->find('branch_id=:branch_id and branch_name=:branch_name',array(':branch_id'=>$branch_id,':branch_name'=>$branch_name));
    }
 
    /**
     * 批量插入OA所有组织架构数据
     */
    public function recInsert($pagesize,$pageindex){
        Yii::import('common.api.ColorCloudApi');
        $colure = ColorCloudApi::getInstance();
        $res = $colure->callGetCompanyGroup("",$pagesize,$pageindex);   
        //var_dump($res); die;    
        if(!$res || (!empty($res['error']))){
            $arrlog = array();
            $arrlog['level'] = "error";
            $arrlog['category'] = "get.company.group";
            $arrlog['logtime'] = time();
            $arrlog['message'] = "OaApi(get.company.group)访问异常pagesize=$pagesize,pageindex=$pageindex";
            $log = new MiddleLog("create");
            $log->attributes = $arrlog;
            $log->save();
            echo iconv("utf-8","gbk","OaApi(get.company.group)访问异常");                        
            sleep(5);
            self::$branchByOaCount++;
            if(self::$branchByOaCount > 5){
                die(iconv("utf-8","gbk","OaApi(get.company.group)访问异常"));
            }
            $this->recInsert($pagesize,$pageindex);
        }

        if(empty($res['data'])){
            //die(iconv("utf-8","gbk","插入完毕!"));
            die(iconv("utf-8","gbk","insert finished!"));
        }

        
        $this->createBranch($res);
    }
    
    public function createBranch($res,$instore=0){
        $saveData = array();
        if($res && $res['data']){
            foreach($res['data'] as $v){
                $saveData['branch_id'] = $v['id'];
                $saveData['branch_name'] = $v['name'];
                $saveData['parent_id'] = $v['parentid']?$v['parentid']:0;
                $saveData['create_time'] = $v['createtime']?$v['createtime']:date("Y-m-d");
                $saveData['update_time'] = $v['uptime']?$v['uptime']:date("Y-m-d");
                $saveData['isclose'] = $v['isclose']?$v['isclose']:0;
                $saveData['storage'] = 0;                
                $saveData['isdelete'] = 0;                                           
                $model = BranchMiddle::checkIsExist($saveData['branch_id']);
                if(!$model){
                    $model = new BranchMiddle("create");
                    $model['relation_id'] = 599;   //默认体验小区
                }else{
                    $model->setScenario("update");
                    //if(($model['parent_id'] != $saveData['parent_id']) && $model['parent_id'] > 0){
                    //    $model['relation_id'] = 599;    //默认体验小区
                    //}else{
                        $model['relation_id'] = $model['relation_id'];
                    //}
                }            
                $model->attributes = $saveData;
                if(!$model->save()){                
                    $arrlog = array();
                    $arrlog['level'] = "error";
                    $arrlog['category'] = "get.company.group";
                    $arrlog['logtime'] = time();
                    $arrlog['message'] = "branch_id为".$saveData['branch_id']."branch_name为".$saveData['branch_name']."的记录添加失败";                    
                    $log = new MiddleLog("create");
                    $log->attributes = $arrlog;
                    $log->save();
                }else{
                    if($instore == 1){
                        $arrlog = array();
                        $arrlog['level'] = "info";
                        $arrlog['category'] = "get.company.group";
                        $arrlog['logtime'] = time();
                        $arrlog['message'] = "branch_id为".$saveData['branch_id']."branch_name为".$saveData['branch_name']."的记录添加成功";                    
                        $log = new MiddleLog("create");
                        $log->attributes = $arrlog;
                        $log->save();
                    }else{
                        //$str = "branch_id为:".$saveData['branch_id']."branch_name为:".$saveData['branch_name']."的记录添加成功\r\n";                              
                        //echo iconv("utf-8","gbk",$str);
                        echo "branch_id:".$saveData['branch_id']."\n\r";
                    }
                }            
            }  
        }
    }
    
    /**
     * 插入每天更新的组织架构
     */
    public function insertByUpdate($uptime,$pagesize,$pageindex){        
        Yii::import('common.api.ColorCloudApi');
        $colure = ColorCloudApi::getInstance();
        $res = $colure->callGetCompanyUpGroup($uptime,$pagesize,$pageindex);
        //var_dump($res['data']);die;
        if((!$res) || (!empty($res['error']))){
            $arrlog = array();
            $arrlog['level'] = "error";
            $arrlog['category'] = "get.company.upgroup";
            $arrlog['logtime'] = time();
            $arrlog['message'] = "OaApi(get.company.upgroup)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime";
            $log = new MiddleLog("create");
            $log->attributes = $arrlog;
            $log->save();
            echo iconv("utf-8","gbk","OaApi(get.company.upgroup)访问异常");
            sleep(5);
            self::$branchByOaCount++;
            if(self::$branchByOaCount > 5){
                die(iconv("utf-8","gbk","OaApi(get.company.upgroup)访问异常"));
            }
            $this->insertByUpdate($uptime,$pagesize,$pageindex);
        }
        if(empty($res['data'])){
            //die(iconv("utf-8","gbk","插入完毕!"));
            die(iconv("utf-8","gbk","insert finished!"));
        }
        $this->createBranch($res,1);
    }
    
    /**
     * 标记删除每天被删除的组织架构
     */    
    public function deleteByUpdate($uptime,$pagesize,$pageindex){        
        Yii::import('common.api.ColorCloudApi');
        $colure = ColorCloudApi::getInstance();
        $res = $colure->callGetCompanyDelGroup($uptime,$pagesize,$pageindex);
        if((!$res) || (!empty($res['error']))){
            $arrlog = array();
            $arrlog['level'] = "error";
            $arrlog['category'] = "get.company.delgroup";
            $arrlog['logtime'] = time();
            $arrlog['message'] = "OaApi(get.company.delgroup)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime";
            $log = new MiddleLog("create");
            $log->attributes = $arrlog;
            $log->save();
            echo iconv("utf-8","gbk","OaApi(get.company.delgroup)访问异常");
            sleep(5);
            self::$branchByOaCount++;
            if(self::$branchByOaCount > 5){
                die(iconv("utf-8","gbk","OaApi(get.company.delgroup)访问异常"));
            }
            $this->deleteByUpdate($uptime,$pagesize,$pageindex);
        }
        if(empty($res['data'])){
            // die(iconv("utf-8","gbk","删除组织架构完毕!"));
            die(iconv("utf-8","gbk","delete branch finished!"));
        }else{
            foreach($res['data'] as $v){
                $model = BranchMiddle::checkIsExistByName($v['id'], $v['name']);
                if($model){
                    $model['isdelete'] = 1;
                    $model['update_time'] = $v['uptime'];
                    $model->save();
                    $arrlog = array();
                    $arrlog['level'] = "info";
                    $arrlog['category'] = "get.company.delgroup";
                    $arrlog['logtime'] = time();
                    $arrlog['message'] = "删除组织架构成功！id=".$v['id'].",name=".$v['name'];
                    $log = new MiddleLog("create");
                    $log->attributes = $arrlog;
                    $log->save();
                    //$str = "id=".$v['id'].",name=".$v['name']."的组织架构删除成功\r\n";
                    //echo iconv("utf-8","gbk",$str);
                }else{
                    $arrlog = array();
                    $arrlog['level'] = "error";
                    $arrlog['category'] = "get.company.delgroup";
                    $arrlog['logtime'] = time();
                    $arrlog['message'] = "删除组织架构失败！id=".$v['id'].",name=".$v['name'];
                    $log = new MiddleLog("create");
                    $log->attributes = $arrlog;
                    $log->save();
                    //$str = "删除组织架构失败！id=".$v['id'].",name=".$v['name']."\r\n";
                    //echo iconv("utf-8","gbk",$str);
                }
            }
        }
    }
    
}