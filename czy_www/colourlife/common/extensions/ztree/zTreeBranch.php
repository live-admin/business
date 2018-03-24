<?php
Yii::import('zii.widgets.jui.CJuiWidget');

class zTreeBranch extends CJuiWidget
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

    //是否禁用
    public $disabled;

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

        Yii::app()->clientScript->registerScript(__CLASS__ . '-js-' . $this->id, <<<EOD
(function() {
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
  var nodes = zTree.getCheckedNodes(true);
  for (var i = 0; i < nodes.length; i++) {
    zTree.checkNode(nodes[i], true, true);
  }

 //zTree.setting.check.chkboxType = '{ "Y" : "", "N" : "" }';

//默认编辑时需要自动去加载数据到隐藏域下
showCheckNode();

//展开与折叠
$("#expandAllBtn").bind("click", {type:"expandAll"}, expandNode);
$("#collapseAllBtn").bind("click", {type:"collapseAll"}, expandNode);
}());

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

function showCheckNode()
{
  clearHidden();//清空隐藏域
  var zTree = $.fn.zTree.getZTreeObj("{$this->id}");
  var nodes = zTree.getCheckedNodes();
  for (var i = 0; i < nodes.length; i++) {
    //if(!nodes[i].getCheckStatus().half) {
        appendHidden(nodes[i].id);//插入到隐藏域
    //}
  }  
}
function clearHidden(){
    $("#hiddenBox").html('');
}
function appendHidden(id){
    var hiddenString = '<input type="hidden" name="branch_ids[]" value="'+id+'">';
    $("#hiddenBox").append(hiddenString);
}
function removeHidden(id){
    $("#hiddenBox>input").each(function(index, element) {
        if($(this).val() == id){
            $(this).remove();
        }
    });
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