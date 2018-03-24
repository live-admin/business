<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'BaseTree.php');
class zTreeBranchEmployee extends BaseTree
{
    /**
     * @var 更新的Dom元素id
     */
    public $updateDomId;
    public $id_num = 0;
    public  $htmlOptions = array('style' => 'width:250px; height:355px;');//默认大小

    protected function getRegisterScripts()
    {
        $js = array();
        $data = $this->getData();
        $options = CJavaScript::encode($this->options);
        $js[] = "zTree_{$this->id} = $.fn.zTree.init($('#{$this->id}'), {$options}, {$data}); var setting={$options};
        $('#{$this->backgroundId}').prepend(\"<div class='wrap'><input class='span2' name='inpNodeBranch' id='inpNodeBranch{$this->id_num}' style='width: 190px;height: 24px;'/><input type='button' value='搜索相关' id='searchNode{$this->id_num}' onclick='$(this).searchBranch{$this->id_num}()'/> [ <span id='serchCZYRet'><span id='serchCZYshow{$this->id_num}'>0</span>/<span id='serchCZYAll{$this->id_num}'>0</span>][ <a href='#' id='serchCZYPre{$this->id_num}'>上一条</a>][ <a href='#' id='serchCZYNext{$this->id_num}'>下一条</a></span>][ <a id='expandAllBtn' href='#' title='展开' onclick='return false;'>展开</a> ]&nbsp;&nbsp;[ <a id='collapseAllBtn' href='#' title='折叠' onclick='return false;'>折叠</a> ]</div>\");
        ";
        return $js;
    }

    public function run(){

        Yii::app()->clientScript->registerScript(__CLASS__ . '-branch-search-js-' . $this->id, <<<EOD
  $.fn.searchBranch{$this->id_num} = function(options){
  if(options)
  {
    i = options;
  }else
  {
    var i=1;
  }
    //搜索节点，并展开
    var name=$("#inpNodeBranch{$this->id_num}").val().trim();
    var treeObj = $.fn.zTree.getZTreeObj("{$this->id}");
    var nodes = treeObj.getNodesByParamFuzzy("name", name, null);
    if (nodes.length>0) {
        $("#serchCZYAll{$this->id_num}").text(nodes.length);
        var _node=nodes[i-1];
        if(_node){
            $("#serchCZYshow{$this->id_num}").text(i);
            treeObj.selectNode(_node);
            nodeCZY=_node;
            $("#infoCZY{$this->id_num}").text(_node.name);

            $("#serchCZYPre{$this->id_num}").attr("onclick","$(this).searchBranch{$this->id_num}("+(i-1)+")");
            $("#serchCZYNext{$this->id_num}").attr("onclick","$(this).searchBranch{$this->id_num}("+(i+1)+")");
        }
    }else{
        $("#serchCZYshow{$this->id_num}").text(0);
        $("#serchCZYAll{$this->id_num}").text(nodes.length);
    }
};
EOD
        );

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