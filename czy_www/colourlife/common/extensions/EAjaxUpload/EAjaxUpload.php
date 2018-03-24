<?php
/**
 * EAjaxUpload class file.
 * This extension is a wrapper of http://valums.com/ajax-upload/
 *
 * @author Vladimir Papaev <kosenka@gmail.com>
 * @version 0.1
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

class EAjaxUpload extends CWidget
{
    public $id = "fileUploader";
    public $postParams = array();
    public $config = array();
    public $css = null;
    public $uploadId = 'ajaxUploadImage';
    private $ajaxUploadImage;

    public function init()
    {
        parent::init();
        $this->ajaxUploadImage = Yii::app()->getComponent($this->uploadId);
        if (empty($this->ajaxUploadImage))
            throw new CException("AjaxUploadImage defaultImage '{$this->ajaxUploadImage}' is invalid.");
    }

    public function run()
    {
        Yii::app()->getComponent('ajaxUploadImage');
        $this->config['action'] = Yii::app()->createUrl($this->ajaxUploadImage->action);
        $this->config['allowedExtensions'] = Yii::app()->ajaxUploadImage->allowedExtensions;
        $this->config['sizeLimit'] = Yii::app()->ajaxUploadImage->sizeLimit;

        if (empty($this->config['action'])) {
            throw new CException('EAjaxUpload: param "action" cannot be empty.');
        }

        if (empty($this->config['allowedExtensions'])) {
            throw new CException('EAjaxUpload: param "allowedExtensions" cannot be empty.');
        }

        if (empty($this->config['sizeLimit'])) {
            throw new CException('EAjaxUpload: param "sizeLimit" cannot be empty.');
        }

        unset($this->config['element']);

        echo '<div id="' . $this->id . '" class="eajax-uploader"><noscript><p>Please enable JavaScript to use file uploader.</p></noscript></div>';
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);

        Yii::app()->clientScript->registerScriptFile($baseUrl . '/fileuploader.js', CClientScript::POS_HEAD);

        $this->css = (!empty($this->css)) ? $this->css : $baseUrl . '/fileuploader.css';
        Yii::app()->clientScript->registerCssFile($this->css);

        $postParams = array('PHPSESSID' => session_id(), 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken);
        if (isset($this->postParams)) {
            $postParams = array_merge($postParams, $this->postParams);
        }

        $config = array(
            'minSizeLimit' => 512, // minimum file size in bytes
            'element' => 'js:document.getElementById("' . $this->id . '")',
            'debug' => false,
            'multiple' => false,
        );
        $config = array_merge($config, $this->config);
        $config['params'] = $postParams;
        $config = CJavaScript::encode($config);
        Yii::app()->getClientScript()->registerScript("FileUploader_" . $this->id, "var FileUploader_" . $this->id . " = new qq.FileUploader($config); ", CClientScript::POS_LOAD);
    }


}