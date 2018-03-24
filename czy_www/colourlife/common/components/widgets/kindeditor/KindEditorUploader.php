<?php

class KindEditorUploader extends CApplicationComponent
{
    public $basePath;
    public $baseUrl;
    public $filenameTemplate = 'Ymd/YmdHis~.$'; /* ~ 随机字符，$ 后缀 */
    public $maxSize = 2097152; //2MBs
    public $ext = array(
        'image' => array('jpg', 'jpeg', 'png'),
        'flash' => array(),
        'media' => array(),
        'file' => array(),
    );
    public $action = '/site/upload';

    public function init()
    {
        parent::init();
        if (empty($this->basePath) || !is_dir($this->basePath))
            throw new CException("KindEditorUploader basePath '{$this->basePath}' is invalid.");
        if (empty($this->baseUrl))
            throw new CException("KindEditorUploader baseUrl '{$this->baseUrl}' is invalid.");
    }

    protected function createDir($path)
    {
        $path = dirname($path);
        $pathName = $this->getFilename($path);
        if (!is_dir($pathName)) {
            $this->createDir($path);
            @mkdir($pathName, 0777);
        }
    }

    protected function getNewName($dir, $ext)
    {
        $random = rand(10000, 99999);
        $filename = $dir . '/' . date($this->filenameTemplate);
        $filename = str_replace(array('~', '$'), array($random, $ext), $filename);
        $this->createDir($filename);
        return $filename;
    }

    protected function getFilename($filename)
    {
        return $this->basePath . $filename;
    }

    protected function getUrl($filename)
    {
        return $this->baseUrl . $filename;
    }

    public function actionUpload()
    {
        $dir = isset($_GET['dir']) ? trim($_GET['dir']) : 'file';
        if (empty($this->ext[$dir])) {
            echo CJSON::encode(array('error' => 1, 'message' => '目录名不正确。'));
            exit;
        }

        $file = CUploadedFile::getInstanceByName('imgFile');
        if (!is_object($file) || get_class($file) !== 'CUploadedFile') {
            echo CJSON::encode(array('error' => 1, 'message' => '未知错误'));
            exit();
        }

        if ($file->size > $this->maxSize) {
            echo CJSON::encode(array('error' => 1, 'message' => '上传文件大小超过限制。'));
            exit;
        }
        $ext = $file->extensionName;
        if (in_array($ext, $this->ext[$dir]) === false) {
            echo CJSON::encode(array('error' => 1, 'message' => "上传文件扩展名是不允许的扩展名。\n只允许" . implode(',', $this->ext[$dir]) . '格式。'));
            exit;
        }

        $filename = $this->getNewName($dir, $ext);
        $file->saveAs($this->getFilename($filename));
        echo CJSON::encode(array('error' => 0, 'url' => $this->getUrl($filename)));
    }

    public function getActionUrl()
    {
        return Yii::app()->createUrl($this->action);
    }

}