<?php
/**
 *
 * 图片文件处理
 * @author lifeng
 *
 */

class ImageFile extends CApplicationComponent
{
    public $defaultImage = '';
    public $basePath = '';
    public $baseUrl = '';
    public $filenameTemplate = 'Y/m/d/H/is~.$'; /* ~ 随机字符，$ 后缀 */

    public function init()
    {
        parent::init();
        if (empty($this->defaultImage))
            throw new CException("ImageFile defaultImage '{$this->defaultImage}' is invalid.");
        if (empty($this->basePath) || !is_dir($this->basePath))
            throw new CException("ImageFile basePath '{$this->basePath}' is invalid.");
        if (empty($this->baseUrl))
            throw new CException("ImageFile baseUrl '{$this->baseUrl}' is invalid.");
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

    public function getNewName($filename)
    {
        $info = pathinfo($filename);
        $extension = strtolower($info['extension']);
        $random = rand(10000, 99999);
        $filename = date($this->filenameTemplate);
        $filename = str_replace(array('~', '$'), array($random, $extension), $filename);
        $this->createDir($filename);
        return $filename;
    }

    public function getFilename($filename)
    {
        return $this->basePath . $filename;
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

    public function saveToFile($file, $oldFilename = '')
    {
        if (!($file instanceof CUploadedFile))
            return $oldFilename;
        $filename = $this->getNewName($file->getName());
        if ($file->saveAs($this->getFilename($filename))) {
            $this->delete($oldFilename);
            return $filename;
        }
        return $oldFilename;
    }

    //移动文件,返回一个新的地址
    //$oldFilename 为修改时删除的文件
    public function moveSave($fileName, $oldFilename = '')
    {
        $fileNew = $this->getNewName($fileName);
        if (rename($fileName, $this->getFilename($fileNew))) {
            $this->delete($oldFilename);
            return $fileNew;
        } else {
            return '';
        }
    }

}