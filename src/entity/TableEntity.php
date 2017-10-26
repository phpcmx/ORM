<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/12
 * Time: 19:02
 */

namespace phpcmx\ORM\entity;

use phpcmx\ORM\inc\interf\Loadable;

/**
 * 数据库模型
 * Class Model
 *
 * @package phpcmx\orm\entity
 */
abstract class TableEntity implements Loadable
{
    /**
     * 数据库连接名称（别名）
     * @return string
     */
    abstract public function dbAliaName() : string;

    /**
     * 字段列表（带描述）
     * @return array
     */
    abstract public function attribute() : array;


    /**
     * 数据库结构描述
     * @return string
     */
    abstract public function definition() : string;

    /**
     * 不允许被实例化
     * TableEntity constructor.
     */
    protected function __construct()
    {
    }

    /**
     * 生成实例化对象的唯一方法
     * @return static
     */
    public static function createEntity()
    {
        return new static();
    }

    /**
     * 更新数据到当前类内
     *
     * @param array $array
     *
     * @return void
     */
    public function load(array $array)
    {

    }
}