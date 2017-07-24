<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Time: 2017年07月24日03:10:25
 */

namespace phpcmx\mysql\syntactic;


use phpcmx\mysql\behavior\DbBehavior;
use phpcmx\mysql\DBConfig;
use phpcmx\mysql\exception\LackOfOperation;

class Sql extends BaseDb
{
    private $_sql = null;

    public function query($sql){
        $this->_sql = $sql;

        return $this;
    }

    /**
     * 运行sql
     *
     * @return \PDOStatement
     */
    protected function sqlExecute()
    {
        $this->_sqlStr = $this->_sql;

        $pdoStatement = DbBehavior::getInstance()->query(
            DBConfig::getInstance()->getDbCache($this->_dbName),
            $this->_sqlStr
        );

        // 判断是哪种操作
        $index = strpos($this->_sqlStr, ' ' );
        if($index === false){
            $index = strlen($this->_sqlStr);
        }



    }

    /**
     * 检查必须要操作的流程
     * @return void
     */
    protected function checkRequire()
    {
        if(empty($this->_sql)){
            throw new LackOfOperation('未设置sql语句');
        }
    }
}