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
    abstract public static function dbAliaName() : string;

    /**
     * 字段列表（带描述）
     * @return array
     */
    abstract public static function attribute() : array;


    /**
     * 数据库结构描述
     * @return string
     */
    abstract public static function definition() : string;

    private $__fieldValue = [];

    /**
     * 不允许被实例化(new)
     * TableEntity constructor.
     */
    protected function __construct(){
        // 初始化所有的字段
        $this->__fieldValue = array_map(function(){ return null;}, static::attribute());
    }


    /**
     * 更新数据到当前类内
     *
     * @param array $array
     *
     * @return static
     */
    static public function load(array $array)
    {
        $static = new static();
        $static->__resetEntity($array);
        return $static;
    }


    /**
     * 重置内容的值
     * @param array $array
     */
    protected function __resetEntity(array $array){
        foreach (static::attribute() as $field => $label) {
            $this->__fieldValue[$field] = $array[$field] ?? null;
        }
    }


    /**
     * 设置字段值
     * @param $field
     * @param $value
     */
    public function __set($field, $value)
    {
        if(!isset($this->__fieldValue[$field])){
            throw new \OutOfRangeException('未找到字段：'.$field);
        }

        $this->__fieldValue[$field] = $value;
    }


    /**
     * 获取字段的值
     * @param $field
     *
     * @return mixed
     */
    public function __get($field)
    {
        if(!isset($this->__fieldValue[$field])){
            throw new \OutOfRangeException('未找到字段：'.$field);
        }

        return $this->__fieldValue[$field];
    }
}