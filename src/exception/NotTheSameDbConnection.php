<?php
/**
 * Created by PhpStorm.
 * Date: 2017/11/19
 * Time: 22:46
 */

namespace phpcmx\ORM\exception;


/**
 * 事物必须是同一个数据库连接才可以开启
 * Class NotTheSameDbConnection
 *
 * @package phpcmx\ORM\exception
 */
class NotTheSameDbConnection extends \LogicException
{

}