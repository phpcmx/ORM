<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·number
 * Date: 2017/10/13
 * Time: 16:02
 */

namespace phpcmx\orm\inc\interf;


/**
 * 可被DB加载的类
 * Interface Loadable
 *
 * @package phpcmx\orm\inc\interf
 */
interface Loadable
{
    /**
     * 加载单条数据库数据
     * @param array $array
     *
     * @return void
     */
    public function load(array $array);

}