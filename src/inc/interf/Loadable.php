<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·number
 * Date: 2017/10/13
 * Time: 16:02
 */

namespace phpcmx\ORM\inc\interf;


/**
 * 可被DB加载的类
 * Interface Loadable
 *
 * @package phpcmx\orm\inc\interf
 */
interface Loadable
{
    /**
     * 更新数据到当前类内
     *
     * @param array $array
     *
     * @return static
     */
    public static function load(array $array);
}