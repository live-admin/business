<?php
/**
 * 报表CSV输出
 * @author dw
 *
 */
class ReportVisitorForCsv extends ReportFrameworkVisitor
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

        $filename = empty($filename) ? date("Y-m-d-H-i-s") : $filename;

        $filename .= ".csv";
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE"))
        { // 解决IE浏览器输出中文名乱码的bug
            $file_name = urlencode($file_name);
            $file_name = str_replace('+', '%20', $file_name);
        }
        header("Content-type:text/csv;");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        if($this->hasData == false)
        {
            echo "no data";
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
            return;
        }

        $this->header = $header;

        $csv_data = "";
        for ($i = 0; $i < count($this->header); $i ++)
        {
            $csv_data .= self::processCsvContent($this->header[$i]);
            if ($i < count($this->header) - 1)
            {
                $csv_data .= ",";
            }
        }
        $csv_data .= "\r\n";
        $csv_data = mb_convert_encoding($csv_data, "cp936", "UTF-8");
        echo $csv_data;
    }

    /**
     * 处理报表数据。
     * 报表框架会将数据分批次发送到该方法，以防止数据量过大，导致内存不足的
     * @param array $data 报表数据. array(array()).
     * @param integer $offset 数据的偏移量
     */
    public function buildBody($data, $offset)
    {
        if($this->hasData == false)
        {
            return;
        }

        $csv_data = "";
        foreach ($data as $row)
        {
            for ($i = 0; $i < count($this->header); $i ++)
            {
                $csv_data .= self::processCsvContent($row[$this->header[$i]]);
                if ($i < count($this->header) - 1)
                {
                    $csv_data .= ",";
                }
            }
            $csv_data .= "\r\n";
        }

        $csv_data = mb_convert_encoding($csv_data, "cp936", "UTF-8");
        echo $csv_data;
    }

    /**
     * 报表处理完成后执行
     */
    public function end()
    {

    }


    private static function processCsvContent($content)
    {
        $content = str_replace("\"", "\"\"", $content);
        return '"' . $content . '"';
    }
}