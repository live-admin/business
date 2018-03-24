<?php

/**
 * 报表HTML输出
 * @author dw
 *
 */
class ReportVisitorForHtml extends ReportFrameworkVisitor
{
    private $header = null;
    private $hasData = false;
    
     /**
     * 报表开始处理前执行
     * @param bool $hasData 是否有数据
     */
    public function begin($hasData)
    {
        $this->hasData = $hasData;
        
        echo '<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>报表数据</title>

<style type="text/css">
.table1{
border-top: 1px solid #ddd;
border-left: 1px solid #ddd;
}
.table1 th{
    border-right: 1px solid #ddd;
    border-bottom: 1px solid #ddd;}
.table1 td{
    border-right: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}

</style>

</head>

<body>';
        
        if($this->hasData == false)
        {
            echo "<h1>没有数据</h1>";
        }
        else
        {
            echo '<table class="table1" cellpadding="5" cellspacing="0">';
        }
    }
    
    /**
     * 处理报表表头数据
     * @param array $header 表头数据
     */
    public function buildHeader($header)
    {
        if($this->hasData == false)
        {
            return ;
        }        
        
        $this->header = $header;
                
        echo '  <tr>';
        foreach($this->header as $h)
        {
            echo '<th>' . $h . '</th>';
        } 
        echo '  </tr>';
        
    }
    
    /**
     * 处理报表数据。
     * 报表框架会将数据分批次发送到该方法，以防止数据量过大，导致内存不足的
     * @param array $data 报表数据. array(array()).\
     * @param integer $offset 数据的偏移量
     */
    public function buildBody($data, $offset)
    {
        if($this->hasData == false)
        {
            return;
        }
        $html = "";
        foreach($data as $d) 
        {
            $html .= '<tr>';
            foreach ($this->header as $v) 
            {
                $html .= '<td>' . $d[$v] . '</td>';
            }
            $html .= '</tr>';
        }
        echo $html;
    }
    
    /**
     * 报表处理完成后执行
     */
    public function end()
    {
        if($this->hasData)
        {
            echo '</table>';
        }
        
        echo '
</body>
</html>';
    }
}
