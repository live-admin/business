<?php

class AjaxUploadImage extends CApplicationComponent
{
    public $defaultImage = '';
    public $basePath = '';
    public $tempBasePath = '';
    public $baseUrl = '';
    public $tempBaseUrl = '';
    public $filenameTemplate = 'Y/m/d/H/is~.$'; /* ~ 随机字符，$ 后缀 */
    public $tempFilenameTemplate = 'Ymd/is~.$'; /* ~ 随机字符，$ 后缀 */
    public $action = 'site/ajaxUpload'; //上传的action
    public $allowedExtensions = array("jpg", "jpeg", "gif"); //array("jpg","jpeg","gif","exe","mov" and etc...
    public $sizeLimit = 10485760; // maximum file size in bytes 10*1024*1024

    public function init()
    {
        parent::init();
        if (empty($this->defaultImage))
            throw new CException("AjaxUploadImage defaultImage '{$this->defaultImage}' is invalid.");
        if (empty($this->basePath) || !is_dir($this->basePath))
            throw new CException("AjaxUploadImage basePath '{$this->basePath}' is invalid.");
        if (empty($this->tempBasePath) || !is_dir($this->tempBasePath))
            throw new CException("AjaxUploadImage tempBasePath '{$this->tempBasePath}' is invalid.");
        if (empty($this->baseUrl))
            throw new CException("AjaxUploadImage baseUrl '{$this->baseUrl}' is invalid.");
        if (empty($this->tempBaseUrl))
            throw new CException("AjaxUploadImage tempBaseUrl '{$this->tempBaseUrl}' is invalid.");
        if (empty($this->action))
            throw new CException("AjaxUploadImage action '{$this->action}' is invalid.");
        if (empty($this->allowedExtensions))
            throw new CException("AjaxUploadImage allowedExtensions '{$this->allowedExtensions}' is invalid.");
        if (empty($this->sizeLimit))
            throw new CException("AjaxUploadImage sizeLimit '{$this->sizeLimit}' is invalid.");
    }

    protected function createDir($path, $is_temp = '')
    {
        $path = dirname($path);
        if ($is_temp != '') {
            $pathName = $this->getTempFilename($path);
        } else {
            $pathName = $this->getFilename($path);
        }
        if (!is_dir($pathName)) {
            $this->createDir($path);
            @mkdir($pathName, 0777);
        }
    }

    public function getNewName($filename, $is_temp = '')
    {
        $info = pathinfo($filename);
        $extension = strtolower($info['extension']);
        $random = rand(10000, 99999);
        if ($is_temp != '') {
            $filename = date($this->tempFilenameTemplate);
            $is_temp = 'true';
        } else {
            $filename = date($this->filenameTemplate);
            $is_temp = '';
        }
        $filename = str_replace(array('~', '$'), array($random, $extension), $filename);
        $this->createDir($filename, $is_temp);
        return $filename;
    }

    public function getFilename($filename)
    {
        return $this->basePath . $filename;
    }

    public function getTempFilename($filename)
    {
        return $this->tempBasePath . $filename;
    }

    public function exists($filename)
    {
        if (empty($filename))
            return false;
        return file_exists($this->getFilename($filename));
    }

    public function getUrl($filename, $defaultImage = '')
    {
        $defaultImage = empty($defaultImage) ? $this->defaultImage : F::getStaticsUrl($defaultImage);
        if (!$this->exists($filename))
            return $defaultImage;
        return $this->baseUrl . $filename;
    }

    public function image($filename, $alt = '', $htmlOptions = array())
    {
        return CHtml::image($this->getUrl($filename), $alt, $htmlOptions);
    }

    public function delete($filename)
    {
        if ($this->exists($filename))
            @unlink($this->getFilename($filename));
    }

    //移动文件,返回一个新的地址
    //$oldFilename 为修改时删除的文件
    public function moveSave($fileName, $oldFilename = '')
    {
        $fileNew = $this->getNewName($fileName);
        if (rename($this->getTempFilename($fileName), $this->getFilename($fileNew))) {
            $this->delete($oldFilename);
            return $fileNew;
        } else {
            return '';
        }
    }

}
