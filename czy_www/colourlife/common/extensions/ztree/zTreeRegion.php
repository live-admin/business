<?php
/**
 * zTree class file.
 *
 * ztree Js扩展包
 * @author jake <jake451@163.com>
 * @link http://hi.baidu.com/jake451
 * @version 1.0
 */
Yii::import('zii.widgets.jui.CJuiWidget');
/**
 *
 * ztree树形菜单
 *
 * ztree扩展包使用方法:
 * <pre>
 * $this->widget('path.ztree.zTree',array(
 *        'treeNodeNameKey'=>'name',
 *        'treeNodeKey'=>'id',
 *        'treeNodeParentKey'=>'pId',
 *        'options'=>array(
 *            'expandSpeed'=>"",
 *            'showLine'=>true,
 *            ),
 *        'data'=>array(
 *            array('id'=>1, 'pId'=>0, 'name'=>'目录1'),
 *            array('id'=>2, 'pId'=>1, 'name'=>'目录2'),
 *            array('id'=>3, 'pId'=>1, 'name'=>'目录3'),
 *            array('id'=>4, 'pId'=>1, 'name'=>'目录4'),
 *            array('id'=>5, 'pId'=>2, 'name'=>'目录5'),
 *            array('id'=>6, 'pId'=>3, 'name'=>'目录6')
 *        )
 * ));
 * </pre>
 *
 * 一、定义数据的两种方式：
 * 1、设置model属性后(model类名或者model对象)：
 *        数据获得方式则为$model->model()->findAll($this->criteria)
 *        例如：
 *            1、array(
 *                'model'=>'tree'
 *            )
 *            2、array(
 *                'model'=>$model, //此处为一个model对象(需要是CModel的子类)
 *            )
 * 2、设置data属性
 *        数据可以为数组，或者model的数据集(数组形式)
 *        例如：
 *            1、array(
 *                'data'=>array(
 *                    array('id'=>1, 'pId'=>0, 'name'=>'目录1'),
 *                    array('id'=>2, 'pId'=>1, 'name'=>'目录2'),
 *                    array('id'=>3, 'pId'=>1, 'name'=>'目录3'),
 *                    array('id'=>4, 'pId'=>1, 'name'=>'目录4'),
 *                    array('id'=>5, 'pId'=>2, 'name'=>'目录5'),
 *                    array('id'=>6, 'pId'=>3, 'name'=>'目录6')
 *                )
 *            )
 *
 *            2、array(
 *                'data'=>tree::model()->findAll()
 *            )
 *    二、提醒：
 *            1、iconsCss属性在新版中已经废弃不存在
 *            2、width属性不填的话，背景层宽度与containerId宽度一样
 *
 */
class zTreeRegion extends CJuiWidget
{
    /**
     * 脚本文件列表
     *
     * @var array|string
     */
    public $scriptFile = array('jquery.ztree.all-3.5.min.js');
    /**
     * 样式文件列表
     *
     * @var array|string
     */
    public $cssFile = array('zTreeStyle.css');
    /**
     * 数据
     *
     * @var array|string
     */
    public $data = '{}';
    /**
     * 容器宽度
     *
     * @var int
     */
    public $width;
    /**
     * 容器高度
     *
     * @var int
     */
    public $height;
    /**
     * 是否只允许选择子项
     *
     * @var bool
     */
    public $onlySon = false;
    /**
     * 背景容器的ID名
     *
     * @var string
     */
    public $backgroundId;

    //是否顶层可以收
    public $dblClickExpand;
    /**
     * 背景容器
     * 默认为DIV，为空则没有背景层
     * @var string
     */
    public $backgroundTagName = 'div';
    /**
     * 背景容器HTML选项
     *
     * @var array
     */
    public $backgroundHtmlOptions = array();
    /**
     * assets目录地址
     *
     * @var string
     */
    public $baseUrl;
    /**
     * zTree数据的model名称
     * 设置此项，则为$model::model()->findAll($this->criteria)
     * @var string|object
     */
    public $model;
    /**
     * 查询条件
     * 设置model属性后生效
     * @var mixed
     */
    public $criteria;
    /**
     * 树形节点列名键名
     * 默认为name
     * @var string
     */
    public $treeNodeNameKey = 'name';
    /**
     * 树形节点ID键名
     *
     * @var string
     */
    public $treeNodeKey;
    /**
     * 树形节点父ID键名
     *
     * @var string
     */
    public $treeNodeParentKey;
    /**
     * 是否为普通数据
     *
     * @var bool
     */
    public $isSimpleData = true;

    /**
     * AJAx的URL
     * @var string
     */
    public $url;

    /**
     * @var 组织架构 hidden 表单 id
     */
    public $targetId;

    /**
     * @var 是否服务小区的 id
     */
    public $isServeId;

    //是否禁用
    public $disabled;
    //是否全选
    public $check_all;

    /**
     * 初始化
     * @see CJuiWidget::init()
     */
    public function init()
    {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($path);
        $this->themeUrl = $this->scriptUrl = $this->baseUrl;
        parent::init();

        $this->htmlOptions['id'] = $this->id;
        if (!empty($this->backgroundHtmlOptions['class'])) {
            $this->htmlOptions['class'] .= ' ztree';
        } else {
            $this->htmlOptions['class'] = 'ztree';
        }

        if (!isset($this->options['data'])) {
            $this->options['data'] = array();
        }

        $this->options['view']['dblClickExpand'] = "dblClickExpand";

        if (!isset($this->options['data']['simpleData'])) {
            $this->options['data']['simpleData'] = array();
        }

        if ($this->isSimpleData) {
            $this->options['data']['simpleData']['enable'] = true;
        }

        if ($this->treeNodeKey !== null) {
            $this->options['data']['simpleData']['idKey'] = $this->treeNodeKey;
        }

        if ($this->treeNodeParentKey !== null) {
            $this->options['data']['simpleData']['pIdKey'] = $this->treeNodeParentKey;
        }

        if ($this->width !== null) {
            $this->backgroundHtmlOptions['style'] .= " width:{$this->width}px;";
        }
        if ($this->height !== null) {
            $this->backgroundHtmlOptions['style'] .= " height:{$this->height}px;";
        }
        if ($this->backgroundId['id'] === null) {
            $this->backgroundId = isset($this->backgroundHtmlOptions['id']) ? $this->backgroundHtmlOptions['id'] : $this->id . 'background';
        }
        $this->backgroundHtmlOptions['id'] = $this->backgroundId;
        $this->url = CHtml::normalizeUrl($this->url);
    }

    public function run()
    {
        //外部容器
        if (!empty($this->backgroundTagName)) {
            echo CHtml::openTag($this->backgroundTagName, $this->backgroundHtmlOptions);
        }
        //树形容器
        echo CHtml::tag('ul', $this->htmlOptions);
        if (!empty($this->backgroundTagName)) {
            echo CHtml::closeTag($this->backgroundTagName);
        }

        // 是否只允许选择子节点
        if ($this->onlySon) {
            $this->options['callback']['beforeClick'] = "js:function(treeId, treeNode) {
				var check = (treeNode && !treeNode.isParent);
				return check;
			}";
        }

        //是否顶层可以收
        if ($this->dblClickExpand) {
            $this->options['view']['dblClickExpand'] = "js:function(treeId, treeNode) {
				return treeNode.level > 0;
			}";
        }

        $this->options['callback']['onCheck'] = "js:function(event, treeId, treeNode) {
        showCheckNode();
        } ";

        //注册JS
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->id, implode("\n", $this->getRegisterScripts()));

        //全选
        if ($this->check_all) {
            Yii::app()->clientScript->registerScript(__CLASS__ . '-new-check_all-js-' . $this->id, <<<EOD
(function() {
  clearHidden();//清空隐藏域
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
    zTree.checkAllNodes(true);
    showCheckNode();
}());
EOD
            );
        }

        Yii::app()->clientScript->registerScript(__CLASS__ . '-js-' . $this->id, <<<EOD
(function() {
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
  var nodes = zTree.getCheckedNodes(true);
  for (var i = 0; i < nodes.length; i++) {
    zTree.checkNode(nodes[i], true, true);
  }
//默认编辑时需要自动去加载数据到隐藏域下
showCheckNode();

if($('#{$this->isServeId}').val()==1)
{
    clearHidden();//清空隐藏域
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
    zTree.checkAllNodes(true);
    //showCheckNode();
    var nodes = zTree.getNodes();
    for (var i=0; i < nodes.length; i++) {
        zTree.setChkDisabled(nodes[i], true,true,true);
    }
}

$('#{$this->isServeId}').change(function(){
if($('#{$this->isServeId}').val()==1)
{
    clearHidden();//清空隐藏域
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
    zTree.checkAllNodes(true);
    showCheckNode();
    var nodes = zTree.getNodes();
    for (var i=0; i < nodes.length; i++) {
        zTree.setChkDisabled(nodes[i], true,true,true);
    }
}else
{
    clearHidden();//清空隐藏域
   var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
    var nodes = zTree.getNodes();
    for (var i=0; i < nodes.length; i++){
        zTree.setChkDisabled(nodes[i], false,true,true);
    }
    showCheckNode();
}
});

$('#{$this->targetId}').bind('change', branchChange);

//展开与折叠
$("#expandAllBtn").bind("click", {type:"expandAll"}, expandNode);
$("#collapseAllBtn").bind("click", {type:"collapseAll"}, expandNode);

}());

function branchChange(){
    if($('#{$this->targetId}').val()!=''){
        $('#{$this->id}').html("<img src='{$this->baseUrl}/base/img/big_loading.gif' />");//加载第一条时显示加载中
        //取数据
        $.ajax({
            url: '{$this->url}',
            data:{'id':$('#{$this->targetId}').val()},
            dataType: 'JSON',
            async:false,
            success: function(result){
                if($.isArray(result)){
                   $('#{$this->id}').empty();
                   $.fn.zTree.init($('#{$this->id}'), setting,result);
                   //将隐藏域里的数据填充树
                    var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
                    var nodes = zTree.transformToArray(zTree.getNodes());
                    for (var i=0; i < nodes.length; i++) {
                        var reg=/^r\d+$/gi;//踢除带r的ID
                        if(!reg.test(nodes[i].id)){
                                id = nodes[i].id;
                               $("#hiddenBox>input").each(function(index, element) {
                                if($(this).val() == nodes[i].id){
                                     zTree.checkNode(nodes[i], true, true);
                                }
                            });
                        }
                    }
                    showCheckNode(); //写入隐藏值
                } else {
                     alert(result);
                }
            }
        });
    }

    //编辑时加载
    if($('#{$this->isServeId}').val()==1)
    {
        clearHidden();//清空隐藏域
        var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
        zTree.checkAllNodes(true);
        showCheckNode();
        var nodes = zTree.getNodes();
        for (var i=0; i < nodes.length; i++) {
            zTree.setChkDisabled(nodes[i], true,true,true);
        }
    }
}
function expandNode(e) {
    var zTree = $.fn.zTree.getZTreeObj("{$this->id}"),
    type = e.data.type;
    if (type == "expandAll") {
        zTree.expandAll(true);
    } else if (type == "collapseAll") {
        var node = zTree.getNodeByParam('id', 'r0', null);
        zTree.expandAll(false);
        zTree.expandNode(node, true);
    }
}
function clearHidden(){
    $("#hiddenBox").html('');
}
function appendHidden(id){
    var hiddenString = '<input type="hidden" name="region_ids[]" value="'+id+'">';
    $("#hiddenBox").append(hiddenString);
}
function removeHidden(id){
    $("#hiddenBox>input").each(function(index, element) {
        if($(this).val() == id){
            $(this).remove();
        }
    });
}
function showCheckNode()
{
  clearHidden();//清空隐藏域
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
  var nodes = zTree.getCheckedNodes(true);
  for (var i = 0; i < nodes.length; i++) {
    if(!nodes[i].getCheckStatus().half) {
      var reg=/^r\d+$/gi;//踢除带r的ID
      if(!reg.test(nodes[i].id)) {
        id = nodes[i].id;//踢除带c_的 得到ID
        appendHidden(id);//插入到隐藏域
      }
    }
  }  
}
EOD
        );

        //是否不可以编辑
        if ($this->disabled) {
            Yii::app()->clientScript->registerScript(__CLASS__ . '-new-js-' . $this->id, <<<EOD
(function() {
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
    var nodes = zTree.getNodes();
    for (var i=0; i < nodes.length; i++) {
        zTree.setChkDisabled(nodes[i], true,true,true);
    }
}());
EOD
            );
        }
    }

    /**
     * 注册JS列表
     *
     */
    protected function getRegisterScripts()
    {
        $js = array();
        $data = $this->getData();
        $options = CJavaScript::encode($this->options);
        $js[] = "zTree_{$this->id} = $.fn.zTree.init($('#{$this->id}'), {$options}, {$data}); var setting={$options};
        $('#{$this->backgroundId}').prepend(\"<div class='wrap'>[ <a id='expandAllBtn' href='#' title='展开' onclick='return false;'>展开</a> ]&nbsp;&nbsp;[ <a id='collapseAllBtn' href='#' title='折叠' onclick='return false;'>折叠</a> ]</div>\");
        ";
        return $js;
    }


    /**
     * 获得数据
     *
     */
    protected function getData()
    {
        if ($this->model !== null) {
            $model = is_object($this->model) ? $this->model : new $this->model;
            if ($model instanceof CModel) {
                $data = $model->findAll($this->criteria);
            }
        } else {
            $data = $this->data;
        }

        if (is_array($data)) {
            $arr = array();
            foreach ($data as $key => $value) {
                if ($value instanceof CModel) {
                    $value = $value->getAttributes();
                }
                $value['name'] = $value[$this->treeNodeNameKey];
                $arr[] = $value;
            }
            $data = $arr;
            $data = CJavaScript::encode($data);
        }

        return $data;
    }
}