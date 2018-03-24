<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 13-10-8
 * Time: 下午2:15
 */

class Excel
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

        $this->phpExcel->setActiveSheetIndex(0)->setCellValue('B2', $fileName);
        $this->phpExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
        $this->phpExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->phpExcel->setActiveSheetIndex(0)->setCellValue('B2', '导出日期：' . date("Y年m月j日"));
        $this->phpExcel->setActiveSheetIndex(0)->getStyle($this->col.'2')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        //表格头的输出
        $this->phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);

        $column = 0;
        if (count($this->dataName) > 0) {
            foreach ($this->dataName as $dataName) {
                $this->phpExcel->setActiveSheetIndex(0)->setCellValue($this->columnValue[$column] . '3', $dataName['name']);
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

        $dataProvider->setPagination(false);
        $data = $dataProvider->getData();

        $n = 0;
        foreach ($data as $product) {
            if ($n === 0) {
                $this->setHeaderInfo($fileName);
            }
            //明细的输出
            $column = 0;
            if (count($this->dataName) > 0) {
                foreach ($this->dataName as $dataName) {
                    $this->phpExcel->getActiveSheet()->setCellValueExplicit($this->columnValue[$column] . ($n + 4), $product->$dataName['value']);
                    $column++;
                }
            }

            $this->setBorderStyle($n);
            $n = $n + 1;
        }
        $this->writeFile($fileName);
    }
}