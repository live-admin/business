<?php
/**
 * exceldata操作类。
 * <br/>提取于：LuckyCustResultController中，对数据的处理方法
 * <br/>使得适用于其他地方，如订单导出，缴费导出，停车费导出等..
 * @author xiaolei
 *
 */
class ExcelDataOper{
	private $dataProvider;
	private $header;
	
	/**
	 * 实例化
	 * @param 数据集 $dataProvider
	 * @param 表头 $header
	 */
	public function __construct($dataProvider,$header){
		$this->dataProvider=$dataProvider;
		$this->header=$header;
		
	}
	
	/**
	 * 简单导出，把数据一次导入并输出到浏览器，由浏览器决定是保存还是显示。
	 * @param 标题 $title
	 */
	public function exportSimple($title){
		//$excel = new Excel(new PHPExcel());
		$excel = new ExcelData(new PHPExcel());
		$excel->setExcelDataName($this->header);
		$excel->saveExcel($title,$this->dataProvider);
	}
	
	
	/**
	 * 分批次导入到一个excel文件并把此文件保存在服务器
	 * @param 标题 $title
	 * @param 批次 $index  从1开始
	 * @param 是否产生新sheet(0，在同一个sheet下,拼加.1,每一个批次，建一个sheet) $gennerSheet
	 */
	public function exportSubsction($title,$index,$gennerSheet){
		$result=array("success"=>0,"data"=>array("msg"=>"系统错误"));
		if($index){
			ini_set('memory_limit','512M');
			$resourName="SIhiPQctyky2_DHh1.xls";
			//如果是第一批次，删除已有的文件
			if($index==1){
				$this->delExportFile($resourName);
				if(empty($this->dataProvider)){  //第一批次就没有数据
					$result=array("success"=>0,"data"=>array("msg"=>"没有数据"));
					return $result;
				}
			}
			if(empty($this->dataProvider)){  //没有数据了，说明导完了，访问生成的接资源
				$result=array("success"=>1,"data"=>array("over"=>1,"href"=>$resourName));
				return $result;
			}
		
			$excel = new ExcelData(new PHPExcel());
			$excel->setExcelDataName($this->header);
			$excel->saveExcelToFile($resourName,$title,$this->dataProvider,$index,$gennerSheet);
			$result=array("success"=>1,"data"=>array("over"=>0,"page"=>$index+1));
		}else{
			$result=array("success"=>0,"data"=>array("msg"=>"参数错误"));
		}
		return $result;
		
	}

	/**
	 * 每批次产生一个文件，并把所有产生的文件zip压缩，
	 * @param 文件名  $resourName
	 * @param 标题 $title
	 * @param 批次 $index  (根据标题生成规则命名文件，以便压缩和删除)  从1开始
	 * @param 文件夹  $folder 如：export/customerOrder/
	 */
	public function exportSubsctionZip($resourName,$title,$index,$folder=""){
		$result=array("success"=>0,"data"=>array("msg"=>"系统错误"));
		if($index){
			ini_set('memory_limit','512M');
			$resourIndexName= $folder.$resourName."_".$index.".xls";
			//如果是第一页，
			if($index==1){
				//$this->delExportFile($resourName);  删除已有的文件
				if(empty($this->dataProvider)){  //第一页就没有数据
					$result=array("success"=>0,"data"=>array("msg"=>"没有数据"));
					return $result;
				}
			}
			if(empty($this->dataProvider)){  //没有数据了，说明导完了，访问生成的接资源
				$files=array();
				for ($i=1;$i<=$index;$i++){
					$files[]=$folder.$resourName."_".$i.".xls";
				}
				$destination=$folder.$resourName.".zip";
				$this->delExportFile($destination);
				$this->create_zip($files,$destination,true);
				//删除其他的
				for ($i=1;$i<=$index;$i++){
					$this->delExportFile($folder.$resourName."_".$i.".xls");
				}
		
				$result=array("success"=>1,"data"=>array("over"=>1,"href"=>$destination));
				return $result;
			}
		
			$excel = new ExcelData(new PHPExcel());
			$excel->setExcelDataName($this->header);
			//$excel->saveExcelToFile($resourName,$title,$this->dataProvider,$index,0);
			$excel->saveExcelToFile($resourIndexName,$title,$this->dataProvider,$index,1,1);
			$result=array("success"=>1,"data"=>array("over"=>0,"page"=>$index+1));
		}else{
			$result=array("success"=>0,"data"=>array("msg"=>"参数错误"));
		}
		return $result;
		
		
	}
	
	
	/**
	 * 删除文件
	 * @param 文件名 $name
	 */
	private function delExportFile($name){
		if(file_exists($name)){
			unlink($name);
		}
	}
	
	
	/**
	 * 生成压缩文件
	 *
	 * @param unknown $files
	 * @param string $destination
	 * @param string $overwrite
	 * @return boolean
	 */
	private function  create_zip($files = array(), $destination = '', $overwrite = false) {
		// if the zip file already exists and overwrite is false, return false
		if (file_exists ( $destination ) && ! $overwrite) {
			return false;
		}
		// vars
		$valid_files = array ();
		// if files were passed in...
		if (is_array ( $files )) {
			// cycle through each file
			foreach ( $files as $file ) {
				// make sure the file exists
				if (file_exists ( $file )) {
					$valid_files [] = $file;
				}
			}
		}
		// if we have good files...
		if (count ( $valid_files )) {
			// create the archive
			$zip = new ZipArchive ();
			if ($zip->open ( $destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE ) !== true) {
				return false;
			}
			// add the files
			$index=1;
			foreach ( $valid_files as $file ) {
				$zip->addFile ($file, $index.".xls");
				$index++;
			}
			$zip->close ();
			return file_exists ( $destination );
		} else {
			return false;
		}
	}

/**
 * 在LuckyCustResultController中调用测试
 *
 * if(isset($_GET['action']) && $_GET['action']=="export"){
    		$page=isset($_GET['page'])?($_GET['page']):(1);
    		$dataProvider = LuckyCustResult::model()->report_search_sql($page);
    		$header= array(
                array('name'=>'记录ID','value'=>'id','width'=>'15'),
                array('name'=>'用户名','value'=>'cust_name','width'=>'15'),
                array('name'=>'收获人','value'=>'receive_name','width'=>'15'),
                array('name'=>'联系电话','value'=>'moblie','width'=>'15'),
                array('name'=>'收获地址','value'=>'address','width'=>'60'),
                array('name'=>'获得奖品等级','value'=>'prize_level_name','width'=>'15'),
                array('name'=>'获得奖品名称','value'=>'prize_name','width'=>'15'),   
                array('name'=>'获得奖品时间','value'=>'lucky_date','width'=>'30'),
                array('name'=>'处理状态','value'=>'deal_state','width'=>'30'),
            );
    		$excelOper=new ExcelDataOper($dataProvider, $header);
    		//$a=$excelOper->exportSubsctionZip("标题", $page);
    		//$a=$excelOper->exportSubsction("标题", $page, 1);
    		//$a=$excelOper->exportSubsction("标题", $page, 0);
    		$a=$excelOper->exportSimple("标题");
    		var_dump($a);
    	}
    	
    	return;
 */
}