<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Jmultiselect2side.php');
class multiselect extends Jmultiselect2side
{
    public $id;
    public $size=22;
    public $url;
    public $modelId;
    public $val;//默认选中值

    public function run()
    {
        $this->val = json_encode($this->val);
        $id = (isset($this->name))?$this->name:"seleccion" . self::$idCount++;

        if ($this->url == null)
            $this->url = '/site/ajaxGetEmployeeName';

        echo '<div class="controls">
        <input class="span5" name="search_employee_'.$this->name.'" value="" style="height:24px;" id="search_employee_'.$this->name.'" maxlength="255" placeholder="搜索员工，如果搜不到请搜索员工全名">
        <input type="button" value="员工搜索" onclick="$(this).search_employee_'.$this->name.'()">
        </div>';

        if ($this->model !== null)
            echo CHtml::activeListBox($this->model, $this->attribute,
                $this->list,
                array('multiple' => true, 'id' => $id));
        else
            echo CHtml::listBox($this->name, null, $this->list, array('multiple' => true, 'id' => $id,'size'=>$this->size));

        $this->registerClientScript();
        $str = " $('#{$id}').multiselect2side({" . PHP_EOL;

        if (isset($this->moveOptions) && !$this->moveOptions) {
            $str .= "moveOptions: false," . PHP_EOL;
        }
        foreach (array('minSize','selectedPosition', 'labelTop', 'labelBottom', 'labelUp', 'labelDown', 'labelSort', 'maxSelected', 'labelsx', 'labeldx', 'autoSort', 'search') as $p) {
            if (isset($this->$p)) $str .= "{$p}:'{$this->$p}'," . PHP_EOL;
        }

        $str .= "});" . PHP_EOL;

        Yii::app()->clientScript->registerScript('multiselect' . $id, $str, CClientScript::POS_READY);

        Yii::app()->clientScript->registerScript(__CLASS__ . '-' . $id, <<<EOD
         $.fn.search_employee_{$this->name} = function(options){
          var val = $('#search_employee_{$this->name}').val();
            if(val!='')
            {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: {'ajax':'ajax','name':val},
                    url: '/site/ajaxGetAllEmployeeName',
                    success: function (data) {
                      //清除掉控件
                      $('#{$this->name}ms2side__sx').html("");
                      if(data.errorCode>0)
                          //alert(data.errorMessage);
                        console.log(data.errorMessage);
                      else
                      {
                        mydata = data.errorMessage;
                        var val = $('#{$id}').val();
                         //console.log(val);

                        for(var i=0;i<mydata.length;i++)
                        {
                          //console.log($.inArray(mydata[i]['id'],val));
                            if($.inArray(mydata[i]['id'],val)==-1){//除掉已选择的数据
                              $('#{$id}').multiselect2side('addOption', {name: mydata[i]['name'], value: mydata[i]['id'], selected: false});
                            }
                        }
                      }
                    }
                });
            }
};
function treeNodeChecked{$this->name}(event, treeId, treeNode) {
        if (treeNode.id != '') {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {branch_id: treeNode.id,'ajax':'ajax','id':'{$this->modelId}'},
                url: '{$this->url}',
                success: function (data) {
                  //清除掉控件
                  $('#{$this->name}ms2side__sx').html("");
                  if(data.errorCode>0)
                      //alert(data.errorMessage);
                    console.log(data.errorMessage);
                  else
                  {
                    mydata = data.errorMessage;
                    var val = $('#{$id}').val();
                     //console.log(val);
                    
                    for(var i=0;i<mydata.length;i++)
                    {
                      //console.log($.inArray(mydata[i]['id'],val));
                        if($.inArray(mydata[i]['id'],val)==-1){//除掉已选择的数据
                          $('#{$id}').multiselect2side('addOption', {name: mydata[i]['name'], value: mydata[i]['id'], selected: false});
                        }
                    }
                  }
                }
            });
        }
    }
(function() {
    if({$this->val}!=null){
        for(var i=0;i<{$this->val}.length;i++)
         {
            $('#{$id}').multiselect2side('addOption', {name: {$this->val}[i]['name'], value: {$this->val}[i]['id'], selected: true});
         }
    }
}());
EOD
);
    }

}

?>
