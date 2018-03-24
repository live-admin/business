<?php
/**
 * Created by PhpStorm.
 * User: ゛嗨⑩啉°
 * Date: 13-12-4
 * Time: 上午10:57
 */

class SmsTemplate extends CActiveRecord
{
    /**
     * @var
     */
    public $id;
    /**
     * 模板名称
     * @var
     */
    public $name;
    /**
     * 模板唯一标识符
     * @var
     */
    public $code;
    /**
     * 短信模板分类
     * @var
     */
    public $category;
    /**
     * 模板描述
     * @var
     */
    public $description;
    /**
     * 短信模板需要的参数
     * @var
     */
    public $value;
    /**
     * @var
     */
    public $template;
    /**
     * @var
     */
    public $update_time;
    /**
     * 启禁用状态
     * @var
     */
    public $state;
    public $modelName = '短信模板';
    public $checkValue = array();

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'sms_template';
    }

    public function rules()
    {
        return array(
            array(
                'name, code, state, value, description, template, update_time',
                'safe',
                'on' => 'search'
            ),
            array('template', 'checkTemplate', 'on' => 'update'),
            array('state', 'boolean', 'on' => 'enable, disable'),
            //array('name, code ,state'),
        );
    }

    public function checkTemplate($attrbiute, $params)
    {
        if (!$this->hasErrors() && !empty($this->checkValue)) {
            foreach ($this->checkValue as $val) {
                if (!empty($val) && strpos($this->$attrbiute, $val) === false) {
                    $this->addError($attrbiute == 'template' ? 'template' : 'description', sprintf($this->getAttributeLabel($attrbiute) . '必须包含 <code>%s</code> %s', $val, $attrbiute == 'template' ? '代码' : '说明'));
                    break;
                }
            }
        }
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'code' => '代码',
            'name' => '模板名称',
            'description' => '描述',
            'category' => '分类',
            'value' => '参数',
            'template' => '短信模板',
            'update_time' => '更新时间',
            'state' => '状态'
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributes()
    {
        return array(
            'name',
            'category',
            'code',
            array(
                'name' => 'state',
                'type' => 'raw',
                'value' => $this->getStateName(true),
            ),
            array(
                'name' => 'template',
                'type' => 'ntext',
            ),
            array(
                'name' => 'description',
                'type' => 'ntext',
            ),
            'value',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->addCondition('category = "' . $this->category . '"');
        /*if ( ! empty( $this->category ) ) {
            $this->categoryName[] = SmsTemplate::model ()->find ( 'code = "' . $this->category . '"' );
        }*/
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * 组件挂载
     * @return array
     */
    public function behaviors()
    {
        return array(
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

    public function getCategoryName()
    {
        $category = SmsTemplateCategory::model()->findByPk($this->category);
        return empty($category) ? '' : $category->name;
    }
} 