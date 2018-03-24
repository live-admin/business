<?php

/**
 * 报表数据访问者
 * @author dw
 *
 */
abstract class ReportFrameworkVisitor
{
    /**
     * 报表开始处理前执行
     * @param bool $hasData 是否有数据
     */
    public abstract function begin($hasData);
    
    /**
     * 处理报表表头数据
     * @param array $header 表头数据
     */
    public abstract function buildHeader($header);
    
    /**
     * 处理报表数据。
     * 报表框架会将数据分批次发送到该方法，以防止数据量过大，导致内存不足的
     * @param array $data 报表数据. array(array()).
     * @param integer $offset 数据的偏移量
     */
    public abstract function buildBody($data, $offset);
    
    /**
     * 报表处理完成后执行
     */
    public abstract function end();
}