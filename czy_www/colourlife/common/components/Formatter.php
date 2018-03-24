<?php

class Formatter extends CFormatter
{
    public $booleanFormat = array('否', '是');
    public $localeDateFormat = 'Y年n月j日';
    public $localeDatetimeFormat = 'Y年n月j日 G:i';
    public $dateFormat = 'Y-n-j';
    public $datetimeFormat = 'Y-n-j G:i';
    public $timeFormat = 'G:i';

    public function formatUrl($value)
    {
        $url = $value;
        if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0)
            $url = 'http://' . $url;
        return CHtml::link(CHtml::encode($value), $url, array('target' => '_blank'));
    }

    public function formatInt($value)
    {
        return intval($value);
    }

    public function formatFloat($value)
    {
        return floatval($value);
    }

    public function formatLocaleDate($value)
    {
        return empty($value) ? '' : date($this->localeDateFormat, $value);
    }

    public function formatLocaleDatetime($value)
    {
        return empty($value) ? '' : date($this->localeDatetimeFormat, $value);
    }

    public function formatDate($value)
    {
        return empty($value) ? '' : date($this->dateFormat, $value);
    }

    public function formatDatetime($value)
    {
        return empty($value) ? '' : date($this->datetimeFormat, $value);
    }

    public function formatTime($value)
    {
        return empty($value) ? '' : date($this->timeFormat, $value);
    }

    public function formatTimeValue($value)
    {
        $seconds = $value;
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        $days = floor($hours / 24);
        $hours = $hours % 24;
        $return = trim(
            (empty($days) ? '' : " {$days} 天") .
            (empty($hours) ? '' : " {$hours} 小时") .
            (empty($minutes) ? '' : " {$minutes} 分钟") .
            (empty($seconds) ? '' : " {$seconds} 秒")
        );
        return empty($return) ? '0' : $return;
    }

    public function formatLatLng($value)
    {
        if (is_array($value)) {
            if (isset($value['lat']) && isset($value['lng']))
                return $value['lat'] . ',' . $value['lng'];
            return '';
        }
        return $value;
    }

    public function formatLatLngImageUrl($value)
    {
        $options = array(
            'zoom' => 16,
            'size' => '480x320',
            'sensor' => 'false',
        );
        $url = 'http://ditu.google.cn/maps/api/staticmap?';
        if (is_array($value)) {
            if (!isset($value['lat']) || !isset($value['lng']))
                return '';
            $lat = $value['lat'];
            $lng = $value['lng'];
            if (!isset($value['mars']))
                $value['mars'] = 'geoMars';
            if ($value['mars'] !== false)
                list($lat, $lng) = Yii::app()->getComponent($value['mars'])->wgs2mars($lat, $lng);
            $options['center'] = $options['markers'] = $lat . ',' . $lng;
            if (isset($value['url']))
                $url = $value['url'];
            if (isset($value['options']) && is_array($value['options']))
                $options = CMap::mergeArray($options, $value['options']);
        } else
            $options['center'] = $options['markers'] = $value;
        $url .= http_build_query($options);
        return $url;
    }

    public function formatLatLngImage($value)
    {
        $alt = $this->formatLatLng($value);
        $url = $this->formatLatLngImageUrl($value);
        if (empty($url))
            return '';
        return CHtml::image($url, $alt, array('class' => 'map'));
    }

    public function formatRoundedImage($value)
    {
        return CHtml::image($value, '', array('class' => 'img-rounded'));
    }

    public function formatCircleImage($value)
    {
        return CHtml::image($value, '', array('class' => 'img-circle'));
    }

    public function formatPolaroidImage($value)
    {
        return CHtml::image($value, '', array('class' => 'img-polaroid'));
    }
}
