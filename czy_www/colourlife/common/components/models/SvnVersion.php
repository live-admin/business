<?php

class SvnVersion extends CModel
{
    private $_data = array();

    public function attributeNames()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'repos' => '版本库',
            'revision' => '当前版本',
            'date' => '更新时间',
            'author' => '更新作者',
        );
    }

    public function __get($name)
    {
        if (isset($this->_data[$name]))
            return $this->_data[$name];
        return parent::__get($name);
    }

    private function _getData($path)
    {
        $data = array();
        $exec = "svn info --xml \"{$path}\"";
        @exec($exec, $output, $return);
        if ($return == 0 && is_array($output)) {
            try {
                $info = new SimpleXMLElement(implode("\n", $output));
                //$data['repos'] = strval(@$info->entry->repository->root);
                $data['revision'] = intval(@$info->entry->commit['revision']);
                $data['date'] = strtotime(@$info->entry->commit->date);
                $data['author'] = strval(@$info->entry->commit->author);
                $arg = 'relative-url';
                $data['repos'] = substr(strval(@$info->entry->$arg), 2);
                if (empty($data['repos'])) {
                    $len = strlen(strval(@$info->entry->repository->root)) + 1;
                    $data['repos'] = substr(strval(@$info->entry->url), $len);
                }
            } catch (Exception $e) {
                $data = array();
            }
        }
        return $data;
    }

    private function _getCacheKey($path)
    {
        return 'svn:' + $path;
    }

    public function init($path, $cache = null, $cacheTime = 3)
    {
        if ($cache != null && ($cache instanceof CCache)) {
            $key = $this->_getCacheKey($path);
            if (($this->_data = $cache->get($key)) === false) {
                $this->_data = $this->_getData($path);
                $cache->set($key, $this->_data, $cacheTime);
            }
        } else {
            $this->_data = $this->_getData($path);
        }
    }

    public function hasData()
    {
        return !empty($this->_data);
    }

}
