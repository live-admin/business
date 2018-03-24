<?php
/**
 * copy自Excel
 * Excel类，使用的是ActiveDataProvider对象
 * 此处改写为普通array对象。
 * 方便sql语句的灵活编写并返回
 * @author xiaolei
 *
 */

class ExcelData
{
    private $phpExcel;
    public  $dataName;
    /*$col excel表的最大列的字母*/
    private $col;
    public  $columnValue = array('B', 'C', 'D', 'E', 'F', 'G', 'H', 'I','J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    function __construct(PHPExcel $phpExcel)
    {
        $this->phpExcel = $phpExcel;
    }

    /**
     * 根据文件名获取PHPExcel对象
     * @param $file
     * @return PHPExcel
     */
    public function getByFile($file)
    {
        $reader = PHPExcel_IOFactory::createReader('Excel5');
        $excel = $reader->load($file);
        return $excel;
    }

    /**
     * 把文件写出到浏览器(浏览器端可进行下载)
     * @param 文件名 $fileName
     */
    public function writeFile($fileName)
    {
        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="' . $fileName . '-' . date("Y年m月j日") . '.xls"');
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function setExcelDataName($array)
    {
        $this->dataName = $array;
        $num = count($this->dataName)-1;
        /*赋值最大列字母*/
        $this->col=$this->columnValue[$num];
    }

    private function setHeaderInfo($fileName)
    {

        //报表头的输出
        $this->phpExcel->getActiveSheet()->mergeCells('B1:' . $this->col.'1');
        $this->phpExcel->getActiveSheet()->setCellValue('B1', $fileName);

        $this->phpExcel->getActiveSheet()->setCellValue('B2', $fileName);
        $this->phpExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(24);
        $this->phpExcel->getActiveSheet()->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->phpExcel->getActiveSheet()->setCellValue('B2', '导出日期：' . date("Y年m月j日"));
        $this->phpExcel->getActiveSheet()->getStyle($this->col.'2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        //表格头的输出
        $this->phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);

        $column = 0;
        if (count($this->dataName) > 0) {
            foreach ($this->dataName as $dataName) {
                $this->phpExcel->getActiveSheet()->setCellValue($this->columnValue[$column] . '3', $dataName['name']);
                $this->phpExcel->getActiveSheet()->getColumnDimension($this->columnValue[$column])->setWidth($dataName['width']);
                $column++;
            }
        }

        //设置居中
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置边框
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
            ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
            ->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
            ->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
            ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
            ->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //设置颜色
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
    }

    private function setBorderStyle($n)
    {
        //设置边框
        $currentRowNum = $n + 4;
        $this->phpExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':'.$this->col.'' . $currentRowNum)
            ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':'.$this->col.'' . $currentRowNum)
            ->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':'.$this->col.'' . $currentRowNum)
            ->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':'.$this->col.'' . $currentRowNum)
            ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':'.$this->col.'' . $currentRowNum)
            ->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }

    public function saveExcel($fileName,$dataProvider)
    {
        $this->phpExcel->setActiveSheetIndex(0);

        //$dataProvider->setPagination(false);
        //$data = $dataProvider->getData();

        $n = 0;
        foreach ($dataProvider as $product) {
            if ($n === 0) {
                $this->setHeaderInfo($fileName);
            }
            //明细的输出
            $column = 0;
            if (count($this->dataName) > 0) {
                foreach ($this->dataName as $dataName) {
                    $this->phpExcel->getActiveSheet()->setCellValue($this->columnValue[$column] . ($n + 4), $product[$dataName['value']]);
                    $column++;
                }
            }

            $this->setBorderStyle($n);
            $n = $n + 1;
        }
        $this->writeFile($fileName);
    }
    
    /**
     * 保存为文件(适用于分段保存)
     * @param 文件名 $fileName
     * @param 内容标题头显示 $headName
     * @param 数据集 $dataProvider
     * @param 页码 $page
     * @param 产生标签标签  $gennerSheet    为1,产生标签(sheet)，为0，数据在上一次产生的后面拼加
     * @param 产生新文件     $gennerNewFile  为0,不产生新文件
     */
    public function saveExcelToFile($fileName,$headName,$data,$page,$gennerSheet=1,$gennerNewFile=0)
    {
        if(file_exists($fileName)){
            $this->phpExcel=$this->getByFile($fileName);
        }
        if($gennerSheet==1 && $gennerNewFile==0){  //不产生新文件，又要产生新sheet
            $this->phpExcel->createSheet($page-1);
            $this->phpExcel->setActiveSheetIndex($page-1);
            $this->phpExcel->getActiveSheet()->setTitle("第".$page."页");
        }else{
            $this->phpExcel->setActiveSheetIndex(0);
        }
        
        $n = ($gennerSheet==1)?(0):(($page-1)*200);  //只要是要新sheet，不管是同文件还是新文件，从0开始，否则，追加，从页码*大小开始
        $this->setHeaderInfoToFile($headName);
        foreach ($data as $product) {
            //明细的输出
            $column = 0;
            if (count($this->dataName) > 0) {
                foreach ($this->dataName as $dataName) {
                    $this->phpExcel->getActiveSheet()->setCellValue($this->columnValue[$column] . ($n + 4), $product[$dataName['value']]);
                    $column++;
                }
            }
    
            $this->setBorderStyle($n);
            $n = $n + 1;
        }
        //$this->writeFile($fileName);
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel5');
        $objWriter->save($fileName);
    }
    
    private function setHeaderInfoToFile($fileName)
    {
    
        //报表头的输出
        $this->phpExcel->getActiveSheet()->mergeCells('B1:' . $this->col.'1');
        $this->phpExcel->getActiveSheet()->setCellValue('B1', $fileName);
    
        $this->phpExcel->getActiveSheet()->setCellValue('B2', $fileName);
        $this->phpExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(24);
        $this->phpExcel->getActiveSheet()->getStyle('B1')
        ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
        $this->phpExcel->getActiveSheet()->setCellValue('B2', '导出日期：' . date("Y年m月j日"));
        $this->phpExcel->getActiveSheet()->getStyle($this->col.'2')
        ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    
        //表格头的输出
        $this->phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    
        $column = 0;
        if (count($this->dataName) > 0) {
            foreach ($this->dataName as $dataName) {
                $this->phpExcel->getActiveSheet()->setCellValue($this->columnValue[$column] . '3', $dataName['name']);
                $this->phpExcel->getActiveSheet()->getColumnDimension($this->columnValue[$column])->setWidth($dataName['width']);
                $column++;
            }
        }
    
        //设置居中
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
        ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
        //设置边框
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
        ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
        ->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
        ->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
        ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')
        ->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    
        //设置颜色
        $this->phpExcel->getActiveSheet()->getStyle('B3:'.$this->col.'3')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
    }
}