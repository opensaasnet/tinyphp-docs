<?php 
/**
 *
 * @copyright (C), 2013-, King.
 * @name Reader.php
 * @author King
 * @version stable 1.0
 * @Date 2017年3月12日下午2:05:36
 * @Class List
 * @Function List
 * @History King 2021年11月3日下午12:08:37 0 第一次建立该文件
 *          King 2021年11月3日下午12:08:37 1 修改
 *          King 2021年11月3日下午12:08:37 stable 1.0.01 审定
 */
namespace Tiny\Docs;

// 定义文档读取的路径常量
define('TINY_DOCS_ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

/**
 * 文档阅读类
 * 
 * @package Tiny.Docs
 * @since 2021年11月3日下午12:08:37 
 * @final 2021年11月3日下午12:08:37 
 *
 */
class Reader 
{
    
    /**
     * 文档读取的根路径
     * 
     * @var string
     */
    const TINY_DOCS_ROOT_PATH = TINY_DOCS_ROOT_PATH;
    
    /**
     * 默认的读取文件
     * 
     * @var string
     */
    const DOCS_PATH_DEFAULT =  'README.md';
    
    /**
     * 通过相对文档路径 返回完整的真实文档路径
     * 
     * @param string $docPath 文档的相对路径
     * @param callable 替换GITHUB链接的callback函数
     * 
     * @return string
     */
    public static function getDocPath($docPath)
    {
        if(!$docPath)
        {
            $docPath = self::DOCS_PATH_DEFAULT;
        }
        $docPath = self::TINY_DOCS_ROOT_PATH . $docPath;
        if (!is_file($docPath))
        {
            return FALSE;
        }
        return $docPath;
    }
    
    /**
     * 通过相对的文档路径  读取文档内容
     * 
     * @param string $docPath
     * @return string
     */
    public static function getDocContent($docPath)
    {
        $docPath = self::getDocPath($docPath);
        if(!$docPath)
        {
            return '';
        }
        return file_get_contents($docPath);
    }
}
?>