<?php

Yii::import('common.components.models.ApiAuth');

/**
 * API V10 基础控制器
 * @author lifeng
 *
 */
class ApiController extends CController
{
    public $layout = false;
    public $apiModelName = '';

    public function processOutput($output)
    {
        if ($this->layout === false) {
            @header('Content-type: application/json');
            return $output;
        }
        return parent::processOutput($output);
    }

    public function detail($model, $attributes = NULL, $formatter = NULL, $return = false)
    {
        if (empty($formatter))
            $formatter = Yii::app()->format;
        $data = array();
        if ($attributes === null) {
            if ($model instanceof CModel)
                $attributes = $model->attributeNames();
            else if (is_array($model))
                $attributes = array_keys($model);
            else
                throw new CException(Yii::t('zii', 'Please specify the "attributes" property.'));
        }
        foreach ($attributes as $attribute) {
            if (is_string($attribute)) {
                if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $attribute, $matches))
                    throw new CException(Yii::t('zii', 'The attribute must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
                $attribute = array(
                    'name' => $matches[1],
                    'type' => isset($matches[3]) ? $matches[3] : 'text',
                );
                if (isset($matches[5]))
                    $attribute['label'] = $matches[5];
            }
            if (isset($attribute['visible']) && !$attribute['visible'])
                continue;
            if (!isset($attribute['type']))
                $attribute['type'] = 'text';
            $name = null;
            if (isset($attribute['value']))
                $value = $attribute['value'];
            else if (isset($attribute['name'])) {
                $name = $attribute['name'];
                $value = CHtml::value($model, $attribute['name']);
            } else
                $value = null;
            if (isset($attribute['label']))
                $name = $attribute['label'];

            if ($value !== null) {
                if (is_array($value)) {
                    $array = array();
                    if (is_array($attribute['type'])) {
                        foreach ($value as $m)
                            $array[] = $this->detail($m, $attribute['type'], $formatter, true);
                    } else {
                        if (array_keys($value) !== range(0, sizeof($value) - 1)) {
                            foreach ($value as $k => $v)
                                $array[$k] = $formatter->format($v, $attribute['type']);
                        } else {
                            foreach ($value as $v)
                                $array[] = $formatter->format($v, $attribute['type']);
                        }
                    }
                    $value = $array;
                } else
                    $value = $formatter->format($value, $attribute['type']);
            }

            $names = explode('.', $name);
            $p = & $data;
            foreach ($names as $name) {
                if (!isset($p[$name]))
                    $p[$name] = array();
                $p = & $p[$name];
            }
            $p = $value;
        }
        if ($return)
            return $data;
        echo CJSON::encode($data);
    }

    public function errorSummary($model, $firstError = false)
    {
        $content = '';
        if (!is_array($model)) {
            $model = array($model);
        }
        foreach ($model as $m) {
            foreach ($m->getErrors() as $errors) {
                foreach ($errors as $error) {
                    if ($error != '') {
                        $content .= "$error\n";
                    }
                    if ($firstError) {
                        break;
                    }
                }
            }
        }
        return $content;
    }

    public function filterAuth($filterChain)
    {
        $auth = ApiAuth::model($this->apiModelName)->auth(Yii::app()->getRequest()->getRequestUri());
        if(strpos(Yii::app()->getRequest()->getRequestUri(),'1.0/version?version')){
            $auth = 0;
        }
        if ($auth == ApiAuth::AUTH_OK)
            $filterChain->run();
        else if ($auth == ApiAuth::AUTH_INVALID_TIME)
            throw new CHttpException(400, 'Forbidden: Invalid timestamp');
        else
            throw new CHttpException(400, 'Forbidden: Invalid sign or key');
    }

    public function getBranchIdsByCommunityId($community_id)
    {
        $ids = Community::model()->getBranchIdsByPk($community_id);
        if ($ids === false)
            throw new CHttpException(404, '找不到指定的小区');
        return $ids;
    }
}
