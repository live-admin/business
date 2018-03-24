<?php

/**
 * 文件上传类,用于保存客户端提交过来的文件
 * 
 * @author dw
 */
class FileUploader
{
	public $fileMaxSize = 10485760;  //最大上传大小
	
    private $saveStaticPath ;
    
    private $saveRelativePath ;
    
    private $fileTypeList = array();
    
    public $uploadFiles = array();
    
    /**
     * 构造文件上传对象
     * @param string $savePath 上传文件存放目录,相对于上传目录的相对路径
     * @param string $fileType 允许上传文件类型 如 jpg|bmp|png 或者 *
     */
    public function __construct($savePath = "uploads", $fileType = "*")
    {
        if($savePath === null || $savePath == "")
        {
            $savePath = "uploads";
        }
		$this->saveRelativePath = $this->combinePath($savePath, "");
		
        //设置路径
        $this->saveStaticPath = $this->combinePath($this->getDefaultUploadStaticBasePath(), $this->saveRelativePath);
        
        //创建路径
        if(!file_exists($this->saveStaticPath) )
        {
            mkdir($this->saveStaticPath, 0777, true);
        }
        
        $this->fileTypeList = explode("|", str_replace(".", "", $fileType));
    }
    
    /**
     * 将临时文件保存为正式文件
     * @param array $files
     
     */
    public function saveTempFiles($files)
    {
    	if(is_array($files))
    	{
    		$result = true;
    		foreach($files as $file)
    		{
    			$result = $this->saveTempFile($file) && $result;
    		}
    		return $result;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    /**
     * 保存一个临时文件
     * @param string $fileRelativePath
     */
    public function saveTempFile($fileRelativePath)
    {
    	$tmp_filePath = $this->toTempFileRelativePath($fileRelativePath);
    	$tmp_filePath = $this->combinePath($this->saveStaticPath, $tmp_filePath);
    	$filePath = $this->combinePath($this->saveStaticPath, $fileRelativePath);
    	if(file_exists($tmp_filePath) && is_file($tmp_filePath) && !file_exists($filePath))
    	{
    		return rename($tmp_filePath, $filePath);
    	}
    	return false;
    }
    
    /**
     * 将上传文件保存为临时文件
     * 
     * @param array $fileArr 传入$_FILES 数组
     * 
     * @return array 上传失败返回false
     */
    public function uploadTempFile($fileArr)
    {
    	if(!$this->checkFileType($fileArr))
    	{
    		return false;
    	}
    	
    	if(!$this->checkFileSize($fileArr))
    	{
    		return false;
    	}
    	
        $result = array();
        foreach ($fileArr as $fileFieldName => $file)
        {
            if(is_array($file['name']))
            {
            	exit("暂时不支持文件数组上传");
            }
            else
            {
            	$result[$fileFieldName] = array(
            			'name' => $file['name'],
            			'type' => $file['type'],
            			'error' => $file['error'],
            			'size' => $file['size'],
            			'fileStaticPath' => "",
            			'fileRelativePath' => "",
            			'url' => "",
            			'tmp_fileStaticPath' => "",
            			'tmp_fileRelativePath' => "",
            			'tmp_url' => ""
            	); 
            	
            	if( $file['error'] == 0)
            	{
            		$fileRelativePath = $this->getNewFileName($this->fileExtension($file['name']));
            		$fileStaticPath = $this->combinePath($this->saveStaticPath, $fileRelativePath);
            		$url = $this->fileUrl($fileRelativePath);            		
            		
            		$tmp_fileRelativePath = $this->toTempFileRelativePath($fileRelativePath);
            		$tmp_fileStaticPath = $this->combinePath($this->saveStaticPath, $tmp_fileRelativePath);
            		$tmp_url = $this->fileUrl($tmp_fileRelativePath);
            		
            		$this->ensureDirExisted($fileRelativePath);
            		if(move_uploaded_file($file['tmp_name'], $tmp_fileStaticPath) == true)
            		{
            			$result[$fileFieldName]['tmp_fileStaticPath'] = $tmp_fileStaticPath;
            			$result[$fileFieldName]['tmp_fileRelativePath'] =  $tmp_fileRelativePath;
            			$result[$fileFieldName]['tmp_url'] = $tmp_url;
            			
            			$result[$fileFieldName]['fileStaticPath'] =  $fileStaticPath;
            			$result[$fileFieldName]['fileRelativePath'] =  $fileRelativePath;
            			$result[$fileFieldName]['url'] = $url;
            		}
            	}
            } 
        }
        $this->uploadFiles = array_merge($this->uploadFiles, $result);
        return $result;
    }

	/**
	 * 直接保存客户端上传的文件到制定目
	 * @param array $fileArr 传入$_FILES 数组
	 * @return array
	 */
    public function uploadFile($fileArr)
    {
    	if(!$this->checkFileType($fileArr))
    	{
    		return false;
    	}
    	
    	if(!$this->checkFileSize($fileArr))
    	{
    		return false;
    	}
    	
    	$result = array();
    	foreach ($fileArr as $fileFieldName => $file)
    	{
    		if(is_array($file['name']))
    		{
    			exit("暂时不支持文件数组上传");
    		}
    		else
    		{
    			$result[$fileFieldName] = array(
    					'name' => $file['name'],
    					'type' => $file['type'],
    					'error' => $file['error'],
    					'size' => $file['size'],
    					'fileStaticPath' => "",
    					'fileRelativePath' => "",
    					'url' => "",
    			);
    
    			if( $file['error'] == 0)
    			{
    				$fileRelativePath = $this->getNewFileName($this->fileExtension($file['name']));
    				$fileStaticPath = $this->combinePath($this->saveStaticPath, $fileRelativePath);
    				$url = $this->fileUrl($fileRelativePath);
    				 
    				$this->ensureDirExisted($fileRelativePath);
    				if(move_uploaded_file($file['tmp_name'], $fileStaticPath) == true)
    				{
    					$result[$fileFieldName]['fileStaticPath'] =  $fileStaticPath;
    					$result[$fileFieldName]['fileRelativePath'] =  $fileRelativePath;
    					$result[$fileFieldName]['url'] = $url;
    				}
    			}
    		}
    	}
    	
    	$this->uploadFiles = array_merge($this->uploadFiles, $result);
    	
    	return $result;
    }
    
    /**
     * 获取文件的URL地址
     * 
     * @param string|array $fileRelativePath 单个文件或文件数组
     * @return string|array 文件的url地址
     */
    public function getFileUrl($fileRelativePath)
    {
    	if(is_array($fileRelativePath))
    	{
    		$result = array();
    		foreach ($fileRelativePath as $file)
    		{
    			$result[$file] = $this->fileUrl($file);
    		}
    		return $result ;
    	}
    	else
    	{
    		return $this->fileUrl($fileRelativePath);
    	}
    }
    
    private function checkFileSize($fileArr)
    {
    	foreach ($fileArr as $file)
    	{
    		if($file['size'] > $this->fileMaxSize)
    		{
    			return false;
    		}
    	}
    	return true;
    }
    
    private function checkFileType($fileArr)
    {
    	if(in_array("*", $this->fileTypeList))
    	{
    		return true;
    	}
    	foreach ($fileArr as $fileFieldName => $file)
    	{
    		$fileType = $this->fileExtension($this->fileExtension($file['name']));
    		$fileType = str_replace(".", "", $fileType);
    		if(!in_array($fileType, $this->fileTypeList))
    		{
    			return false;
    		}
    	}
    	return true;
    }
    
    private function fileUrl($fileName)
    {
    	$fileName = $this->combinePath("/" . $this->saveRelativePath, $fileName, "/");
    	return F::getUploadsUrl($fileName);
    }
    
    private function ensureDirExisted($fileName)
    {
    	$path = $this->combinePath($this->saveStaticPath, dirname($fileName));
    	if(!file_exists($path))
    	{
    		mkdir($path, 0777, true);
    	}    	
    }
    
    private function toTempFileRelativePath($fileName)
    {
    	return $this->combinePath(dirname($fileName), "tmp_" . basename($fileName), "/");
    }

    private function getDefaultUploadStaticBasePath()
    {
    	return $this->combinePath(dirname(__FILE__), "/../../uploads/");
    }
    
    
    private function fileExtension($fileName)
    {
    	return pathinfo($fileName, PATHINFO_EXTENSION) === "" ? "" : "." . pathinfo($fileName, PATHINFO_EXTENSION);
    }
    
    /**
     * 获取新文件名,包括相对路径
     * @param string $extension
     * @return string
     */
    private function getNewFileName($extension)
    {
    	return date("Y/m/d/H/is", time()) . uniqid(mt_rand(1000,9999)) . $extension;
    }
    
    
    /**
     * 连接路径
     * @param string $path1
     * @param string $path2
     * @param string $separator 路径分隔符
     * @return string
     */
    private function combinePath($path1, $path2, $separator = null)
    {
    	if ($separator === null)
    	{
    		$separator = DIRECTORY_SEPARATOR;
    	}
    	$path = $path1 . $separator . $path2;
    	$path = str_replace("/", $separator, $path);
    	$path = str_replace("\\", $separator, $path);
    	while (strstr($path, $separator.$separator))
    	{
    		$path = str_replace($separator.$separator, $separator, $path);
    	}
    	return $path;
    }    
}