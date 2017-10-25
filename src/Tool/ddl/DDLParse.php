<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/25
 * Time: 12:13
 */

namespace phpcmx\ORM\Tool\ddl;


/**
 * ddl字符串解析
 * Class DDLParse
 *
 * @package phpcmx\ORM\Tool
 */
class DDLParse
{
    private $ddlString = '';

    private $command = '';

    private $commandClass = false;

    public function __construct($ddlString)
    {
        $this->ddlString = $ddlString;

        $this->parseStart();
    }


    /**
     * 解析开始
     */
    private function parseStart()
    {
        $ddlString = ltrim($this->ddlString);
        $space1 = strpos($ddlString, ' ');
        $space2 = strpos(substr($ddlString, $space1+1), ' ');
        $this->command = substr($ddlString, 0, $space1+$space2+1);
        $this->commandClass = "DDL".implode('',array_map(function($v){return ucfirst($v);}, explode(' ',strtolower($this->command))));
    }


    /**
     * 获取对应的命令，以便个人处理
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }


    /**
     * 返回对应的类或者false
     * @return bool|string
     */
    public function getClass(){
        $className = __NAMESPACE__."\\".$this->commandClass;
        if(class_exists($className)){
            return $className;
        }

        return false;
    }
}