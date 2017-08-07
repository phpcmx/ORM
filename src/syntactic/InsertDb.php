<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * time: 2017年07月23日00:23:33
 */

namespace phpcmx\ORM\syntactic;


use phpcmx\ORM\behavior\DbBehavior;
use phpcmx\ORM\DBConfig;
use phpcmx\ORM\exception\LackOfOperation;

class InsertDb extends BaseDb
{
    /**
     * @var array 插入的字段列表
     */
    private $_field = [];

    /**
     * @var array 插入的值
     */
    private $_values = [];


    /**
     * 简便插入一条数据
     *
     * @param $data array
     *
     * @return $this
     */
    public function one($data){
        foreach ($data as $field => $value) {
            $this->_field[] = $field;
            $this->_values[] = $value;
        }

        return $this;
    }


    /**
     * 生成sql并运行返回结果
     *
     * @return mixed
     */
    protected function sqlExecute()
    {
        $this->makeSqlStr();
        $this->makeSqlValue();

        // 插入
        $code = DbBehavior::getInstance()->insert(
            DBConfig::getInstance()->getDbCache($this->dbAliasName),
            $this->_sqlStr,
            $this->_sqlValue
        );

        return $code;
    }


    /**
     * 生成sql语句
     */
    private function makeSqlStr()
    {
        // 做成逗号分割的字符串
        $field = implode(',', $this->_field);
        // 值的占位符
        $placeholder = implode(',', array_map(function ($v){
            // 如果是函数，就不替换
            if(DBConfig::getInstance()->isKeep($v)){
                return $v;
            }
            // 否则就返回占位符？
            return '?';
        }, $this->_values));

        // 最终sql
        $this->_sqlStr = "INSERT INTO {$this->_tableName} ({$field}) VALUES ({$placeholder})";
    }


    /**
     * 生成sql value列表
     */
    private function makeSqlValue(){
        $value = [];
        foreach ($this->_values as $index => $v) {
            // 如果不是函数：now() 这样的函数，才会添加到列表内
            if(!DBConfig::getInstance()->isKeep($v)){
                $value[]=$v;
            }
        }

        $this->_sqlValue = $value;
    }

    /**
     * 检查必须要操作的流程
     * @return void
     */
    protected function checkRequire()
    {
        if(empty($this->_tableName)){
            throw new LackOfOperation('未设置表名');
        }
        if(empty($this->_field)){
            throw new LackOfOperation('要添加的字段为空');
        }
    }
}