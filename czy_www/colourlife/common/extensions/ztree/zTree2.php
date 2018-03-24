<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'BaseTree.php');
class zTree2 extends BaseTree
{
    /**
     * @var 更新的Dom元素id
     */
    public $updateDomId;
    public function run(){
        if(isset($this->options['callback'])){
            $callback = $this->options['callback'];
            if(is_array($callback)){
                foreach($callback as $key =>$method){
                    $this->options['callback']["{$key}"]= "js:function(event, treeId, treeNode) {
                        {$method}(event, treeId, treeNode);
                    } ";
                }
            }else{
                $this->options['callback']['onCheck']= "js:function(event, treeId, treeNode) {
                    {$this->options['callback']}(event, treeId, treeNode);
                } ";
            }
        }
        parent::run();
    }
}