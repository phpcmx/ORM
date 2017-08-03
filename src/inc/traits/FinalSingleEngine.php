<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 8/15/16
 * Time: 12:48 AM
 */

namespace phpcmx\ORM\inc\traits;


/**
 * Trait SingleEngine
 * 简单单例的实现  PS:请勿使用此单例进行继承操作。这里的单例类，不应该被继承
 *
 * @package phpcmx\ORM\inc\traits
 */
trait FinalSingleEngine
{
    /**
     * 初始化
     *
     * @return static
     */
    static function getInstance(){
        static $selfObj = null;

        if(is_null($selfObj)){
            $selfObj = new static();
        }

        return $selfObj;
    }


    /**
     * 私有化构造函数
     *
     * SingleEngine constructor.
     */
    protected function __construct(){
        if(method_exists($this, 'init')){
            $this->init();
        }
    }

    /**
     * 默认执行
     */
    protected function init(){}
}