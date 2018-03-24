<?php

class KindEditor extends CWidget
{
    /*
     * TextArea 输入框的属性，保证js调用KE失败时，文本框的样式。
     */
    public $textAreaOptions = array();
    /*
     * TextArea 输入框的name，必须设置。
     * 数据类型：String
     */
    public $name;
    public $style;
    /*
     * TextArea 的id，可为空
     */
    public $id;

    public $model;


    public $uploaderId = 'uploader';

    //不需要上传
    public $update_falg = true;


    /**
     * 类型
     */
    public $type = 'default';

    protected $properties = array(
        'default' => array(
            'items' => array('source', '|', 'undo', 'redo', '|', 'cut', 'copy', 'paste',
                'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                'table', 'hr', 'pagebreak',
                'anchor', 'link', 'unlink'),
            'fileManagerJson' => '',
            'newlineTag' => 'br',
            'allowPreviewEmoticons' => false,
            'allowImageUpload' => true,
            'allowFlashUpload' => false,
            'allowMediaUpload' => false,
            'allowFileUpload' => true,
            'allowFileManager' => false,
            'allowImageRemote' => true,
        ),
    );

    public function init()
    {

        if ($this->name === null)
            throw new CException(Yii::t('zii', 'The id property cannot be empty.'));

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir);
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseUrl . '/themes/default/default.css');
        if (YII_DEBUG)
            $cs->registerScriptFile($baseUrl . '/kindeditor.js');
        else
            $cs->registerScriptFile($baseUrl . '/kindeditor-min.js');

    }

    public function run()
    {
        $cs = Yii::app()->getClientScript();
        $textAreaOptions = $this->getTextAreaOptions();
        $textAreaOptions['name'] = CHtml::resolveName($this->model, $this->name);
        $this->id = $textAreaOptions['id'] = CHtml::getIdByName($textAreaOptions['name']);
        echo CHtml::activeTextArea($this->model, $this->name, $textAreaOptions);

        $properties_string = CJavaScript::encode($this->getTypeProperties());

        $js = <<<EOF
KindEditor.ready(function(K) {
	var editor_$this->id = K.create('#$this->id',
$properties_string
	);
});
EOF;
        $cs->registerScript('KE' . $this->name, $js, CClientScript::POS_HEAD);

    }

    protected function getTextAreaOptions()
    {
        //允许获取的属性
        $allowParams = array('rows', 'cols', 'style');
        //准备返回的属性数组
        $params = array(
            'style' => empty($this->style)?'width:97%;height:400px;':$this->style,
        );
        foreach ($allowParams as $key) {
            if (isset($this->textAreaOptions[$key]))
                $params[$key] = $this->textAreaOptions[$key];
        }
        $params['name'] = $params['id'] = $this->name;
        return $params;
    }

    protected function getTypeProperties()
    {
        $ret = array();
        if (array_key_exists($this->type, $this->properties))
            $ret = $this->properties[$this->type];
        if($this->update_falg){
            $uploader = Yii::app()->getComponent($this->uploaderId);
            $ret['uploadJson'] = $uploader->getActionUrl();
        }
        return $ret;
    }

}