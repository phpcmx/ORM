<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/25
 * Time: 13:41
 */

namespace phpcmx\ORM\Tool\ddl;


/**
 * ddl处理的基类
 * Interface DDLInterf
 *
 * @package phpcmx\ORM\Tool\ddl
 */
interface DDLInterf
{
    /**
     * 解析ddl字符串
     *
     * @param string $ddl
     */
    public function load($ddl);
}