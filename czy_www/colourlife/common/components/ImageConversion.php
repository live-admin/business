<?php

/**
 * 图片转换
 *
 * $imageTargetFilename = Yii::app()->imageFile->getNewName($imageSourceFilename);
 * $conversion = new ImageConversion(Yii::app()->imageFile->getFilename($imageSourceFilename));
 * $conversion->conversion(Yii::app()->imageFile->getFilename($imageTargetFilename), array(
 *    'w' => 640,   // 结果图的宽
 *    'h' => 480,   // 结果图的高
 *    't' => 'resize,gray,clip', // 转换类型
 * );
 * 转换类型说明：
 * resize: 把原图最小边缩小到结果图大小，或原图最大边放大到结果图大小。另一边根据比例缩放。
 * clip：采用居中（水平+垂直）方式剪裁原图到结果图大小
 * gray：将原图转换成灰度结果图
 * 转换类型可以组合使用，如 'resize,clip' 表示同时采用缩放和剪裁两个处理
 *
 * $conversion->conversion(Yii::app()->imageFile->getFilename($imageTargetFilename));
 * 会强制处理图片，避免恶意的图片文件。
 *
 * Class ImageConversion
 */
class ImageConversion
{
    const TYPE_RESIZE = 'resize';
    const TYPE_GRAY = 'gray';
    const TYPE_CLIP = 'clip';

    private static $rule = array(
        'w' => 0,
        'h' => 0,
        't' => '',
    );

    private $_srcFile = false;
    private $_srcSize = false;
    private $_srcIm = false;
    private $_canResize = true;

    public function __construct($filename = '')
    {
        $this->setSource($filename);
    }

    public function setSource($filename)
    {
        if (file_exists($filename))
            $this->_srcFile = $filename;
    }

    public function getSource()
    {
        return $this->_srcFile;
    }

    private function _initSrcSize()
    {
        if ($this->_srcSize === false)
            $this->_srcSize = getimagesize($this->_srcFile);
    }

    public function getSourceWidth()
    {
        if ($this->_srcFile === false)
            return 0;
        $this->_initSrcSize();
        return $this->_srcSize[0];
    }

    public function getSourceHeight()
    {
        if ($this->_srcFile === false)
            return 0;
        $this->_initSrcSize();
        return $this->_srcSize[1];
    }

    public function getSourceType()
    {
        if ($this->_srcFile === false)
            return false;
        $this->_initSrcSize();
        return $this->_srcSize[2];
    }

    private function _to($filename)
    {
        if ($this->_srcFile == $filename)
            return true;
        return @copy($this->_srcFile, $filename);
    }

    private function _initSrcIm()
    {
        if ($this->_srcIm !== false)
            return;
        $type = $this->getSourceType();
        if ($type == 1) {
            $this->_canResize = false;
            $this->_srcIm = imagecreatefromgif($this->_srcFile);
        } else if ($type == 2) {
            $this->_srcIm = imagecreatefromjpeg($this->_srcFile);
        } else if ($type == 3) {
            $this->_srcIm = imagecreatefrompng($this->_srcFile);
            imagesavealpha($this->_srcIm, true);
        }
    }

    /**
     * JPEG 品质
     */
    private function _getQuality($w, $h)
    {
        return 100 - min(ceil(min($w, $h) / 100) * 5, 25);
    }

    private function _output($im, $filename, $w, $h)
    {
        $type = $this->getSourceType();
        if ($type == 2) {
            imagejpeg($im, $filename, $this->_getQuality($w, $h));
        } else if ($type == 3) {
            imagepng($im, $filename);
        }
    }

    private function _imageCreate($w, $h)
    {
        $im = imagecreatetruecolor($w, $h);
        $type = $this->getSourceType();
        if ($type == 3) {
            imagealphablending($im, false);
            imagesavealpha($im, true);
        }
        return $im;
    }

    private function _resizeTo($filename, $fixed, $w, $h, $gray)
    {
        $this->_initSrcIm();
        if ($this->_canResize === false) {
            return $this->_to($filename);
        }
        $srcW = imagesx($this->_srcIm);
        $srcH = imagesy($this->_srcIm);
        if ($srcW <= $w && $srcH <= $h) {
            // 大小太小
            if ($gray) {
                $im = $this->_imageCreate($srcW, $srcH);
                imagecopy($im, $this->_srcIm, 0, 0, 0, 0, $srcW, $srcH);
                imagefilter($im, IMG_FILTER_GRAYSCALE);
                $this->_output($im, $filename, $srcW, $srcH);
                imagedestroy($im);
                return;
            } else
                return $this->_to($filename);
        }
        $wh = $w / $h;
        $srcWh = $srcW / $srcH;
        $x = $y = 0;
        if ($fixed) {
            // 使用指定比例
            if ($srcWh > $wh) {
                // 源图比较宽，剪裁宽
                $x = floor(($srcW - $srcH * $w / $h) / 2);
                $srcW -= 2 * $x;
            } else {
                // 源图比较高，剪裁高
                $y = floor(($srcH - $srcW * $h / $w) / 2);
                $srcH -= 2 * $y;
            }
        } else {
            // 保持源图比例
            if ($srcWh > $wh) {
                // 源图比较宽，缩放目标图高
                $h = $w / $srcWh;
            } else {
                // 源图比较高，缩放目标图宽
                $w = $h * $srcWh;
            }
        }
        $im = $this->_imageCreate($w, $h);
        imagecopyresampled($im, $this->_srcIm, 0, 0, $x, $y, $w, $h, $srcW, $srcH);
        if ($gray)
            imagefilter($im, IMG_FILTER_GRAYSCALE);
        $this->_output($im, $filename, $w, $h);
        imagedestroy($im);
    }

    private function _grayTo($filename)
    {
        $this->_initSrcIm();
        if ($this->_canResize === false) {
            return $this->_to($filename);
        }
        $w = imagesx($this->_srcIm);
        $h = imagesy($this->_srcIm);
        $im = $this->_imagecreate($w, $h);
        imagecopyresampled($im, $this->_srcIm, 0, 0, 0, 0, $w, $h, $w, $h);
        imagefilter($im, IMG_FILTER_GRAYSCALE);
        $this->_output($im, $filename, $w, $h);
        imagedestroy($im);
    }

    private function _convTo($filename)
    {
        $this->_initSrcIm();
        if ($this->_canResize === false) {
            return $this->_to($filename);
        }
        $w = imagesx($this->_srcIm);
        $h = imagesy($this->_srcIm);
        $im = $this->_imagecreate($w, $h);
        imagecopyresampled($im, $this->_srcIm, 0, 0, 0, 0, $w, $h, $w, $h);
        $this->_output($im, $filename, $w, $h);
        imagedestroy($im);
    }

    public function conversion($filename, $rule = array())
    {
        if ($this->_srcFile === false)
            return false;
        $rule = array_merge(self::$rule, $rule);
        $resize = false;
        $fixed = false;
        $gray = false;
        foreach (explode(',', strtolower($rule['t'])) as $type) {
            switch (trim($type)) {
                case self::TYPE_RESIZE:
                    $resize = true;
                    $fixed = false;
                    break;
                case self::TYPE_CLIP:
                    $resize = true;
                    $fixed = true;
                    break;
                case self::TYPE_GRAY:
                    $gray = true;
                    break;
            }
        }
        if ($resize)
            return $this->_resizeTo($filename, $fixed, $rule['w'], $rule['h'], $gray);
        if ($gray)
            return $this->_grayTo($filename);
        return $this->_convTo($filename);
    }

    public function clean($deleteSource = false)
    {
        imagedestroy($this->_srcIm);
        $this->_srcIm = false;
        $this->_canResize = true;
        if ($deleteSource) {
            @unlink($this->_srcFile);
            $this->_srcFile = $this->_srcSize = false;
        }
    }

}