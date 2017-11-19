<?php
/**
 * Created by PhpStorm.
 * Date: 2017/11/19
 * Time: 21:17
 */

namespace phpcmx\ORM\exception;


/**
 * load函数引入的对象不可以使用add再次添加入数据库
 * Class LoadEntityCouldNotAdd
 *
 * @package phpcmx\ORM\exception
 */
class LoadEntityCouldNotAdd extends \LogicException
{

}