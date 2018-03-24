<?php

Yii::import('bootstrap.widgets.TbButton');
/**
 * Class AjaxConfirmButton
 * 确认操作按钮
 */
class AjaxConfirmButton extends TbButton
{
    /**
     * @var 确认对话框显示内容 id
     */
    public $targetId;

    public function run()
    {
        parent::run();
        if (empty($this->targetId))
            return;
        Yii::app()->clientScript->registerCoreScript('bbq');
        Yii::app()->clientScript->registerScript(__CLASS__, <<<EOD
$('.op').live('click', function() {
    var id = 'content';
    var self = $(this);
    var isDelete = self.hasClass('op-delete');
    var url = self.attr('href');
    var cur = location.href;
    var label = $.trim(self.text());
    var title = $('#{$this->targetId}').val();
    if(!confirm('确定要' + label + title + '吗？')) return false;
    $.ajax({
        'url': $.param.querystring(url, 'ajax='+id),
        'type': 'POST',
        'success': function(data) {
            if (isDelete) {
                location.href = $('#' + id + ' ul.breadcrumb li:eq(-3) a').attr('href');
            } else
            $.ajax({
                'url': cur,
                'success': function(data) {
                    var div='#' + id;
					$(div).replaceWith($(div,'<div>'+data+'</div>'));
                }
            });
        },
        'error': function(xhr) {
            alert(xhr.responseText);
        }
    });
    return false;
});
EOD
        );
    }

}