<?php
/**
 * InterfaceDb.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/22 下午11:22
 * 修改记录:
 *
 * $Id$
 */

namespace phpcmx\ORM\syntactic;

/**
 * Interface InterfaceDb
 * 数据库操作类接口
 * @package phpcmx\ORM\syntactic
 */
interface InterfaceDb
{
    /**
     * InterfaceDb constructor.
     * 确定数据库名称
     *
     * @param string $dbName
     */
    public function __construct(string $dbName);


    /**
     * 设置table
     * @param string $tableName
     * @return InterfaceDb
     */
    public function table(string $tableName);


    /**
     * 最终执行
     * @return mixed
     */
    public function execute();
}