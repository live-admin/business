<?php

Yii::import('zii.widgets.CMenu');

class Menu extends CMenu
{
    static $_counter = 1;

    protected function genListId()
    {
        return 'clm' . self::$_counter++;
    }

    public function init()
    {
        parent::init();
        $classes = array('nav', 'clm');
        if (!empty($classes)) {
            $classes = implode(' ', $classes);
            if (isset($this->htmlOptions['class']))
                $this->htmlOptions['class'] .= ' ' . $classes;
            else
                $this->htmlOptions['class'] = $classes;
        }
    }

    protected function renderMenu($items)
    {
        $count = count($items);

        if ($count > 0) {
            echo CHtml::openTag('ul', $this->htmlOptions);

            foreach ($items as $item) {
                if (isset($item['end'])) {
                    echo '</ul>';
                    echo '</li>';
                } else {
                    $options = isset($item['itemOptions']) ? $item['itemOptions'] : array();
                    $classes = array();
                    if ($item['active'] && $this->activeCssClass != '')
                        $classes[] = $this->activeCssClass;
                    if ($this->itemCssClass !== null)
                        $classes[] = $this->itemCssClass;
                    if (isset($item['disabled']))
                        $classes[] = 'disabled';
                    if (!empty($classes)) {
                        $classes = implode(' ', $classes);
                        if (!empty($options['class']))
                            $options['class'] .= ' ' . $classes;
                        else
                            $options['class'] = $classes;
                    }
                    echo CHtml::openTag('li', $options);
                    $options = $this->htmlOptions;
                    if (isset($item['header'])) {
                        $options['id'] = $this->genListId();
                        $options['class'] .= ' accordion-body collapse' . (isset($item['in']) ? ' in' : '');
                        $item['label'] = '<i class="icon-clm pull-left"></i> ' . $item['label'];
                        $item['url'] .= $options['id'];
                        if (!isset($item['linkOptions']))
                            $item['linkOptions'] = array();
                        $item['linkOptions']['class'] = 'accordion-toggle' . (isset($item['in']) ? '' : ' collapsed');
                        $item['linkOptions']['data-toggle'] = 'collapse';
                    } else {
                        $item['label'] = '· ' . $item['label'];
                    }
                    echo $this->renderMenuItem($item);
                    if (isset($item['header'])) {
                        echo CHtml::openTag('ul', $options);
                    } else
                        echo '</li>';
                }
            }
            echo '</ul>';
        }
    }

    protected function renderMenuItem($item)
    {
        if (isset($item['icon'])) {
            if (strpos($item['icon'], 'icon') === false) {
                $pieces = explode(' ', $item['icon']);
                $item['icon'] = 'icon-' . implode(' icon-', $pieces);
            }
//            if ($item['active'])
//                $item['icon'] .= ' icon-white';
            $item['label'] = '<i class="' . $item['icon'] . '"></i> ' . $item['label'];
        }
        if (!isset($item['linkOptions']))
            $item['linkOptions'] = array();
        return CHtml::link('<span>' . $item['label'] . '</span>', $item['url'], $item['linkOptions']);
    }

    protected function normalizeItems($items, $route, &$active)
    {
        $header = array();
        foreach ($items as $i => $item) {
            if (isset($item['end'])) {
                $n = array_pop($header);
                if (isset($items[$n]['visible']))
                    $item['visible'] = $items[$n]['visible'];
                else
                    unset($item['visible']);
            } else {
                if (!isset($item['itemOptions']))
                    $item['itemOptions'] = array();
                if (!isset($item['url'])) {
                    $item['url'] = '#';
                    $item['header'] = true;
                    array_push($header, $i);
                }
                if (isset($item['active'])) {
                    foreach ($header as $n)
                        $items[$n]['in'] = true;
                }
            }
            $items[$i] = $item;
        }
        return parent::normalizeItems($items, $route, $active);
    }
}