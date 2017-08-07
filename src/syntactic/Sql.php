<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Time: 2017年07月24日03:10:25
 */

namespace phpcmx\ORM\syntactic;


use phpcmx\ORM\behavior\DbBehavior;
use phpcmx\ORM\DBConfig;
use phpcmx\ORM\exception\LackOfOperation;

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
     * @return mixed
     */
    protected function sqlExecute()
    {
        $this->_sqlStr = $this->_sql;

        $dbLink = DBConfig::getInstance()->getDbCache($this->dbAliasName);

        $pdoStatement = DbBehavior::getInstance()->query(
            $dbLink,
            $this->_sqlStr
        );

        // 判断是哪种操作
        $index = strpos(trim($this->_sqlStr), ' ' );
        if($index === false){
            $index = strlen($this->_sqlStr);
        }
        switch(strtoupper(substr(trim($this->_sqlStr), 0, $index))){
            case 'SELECT':
                $return = $pdoStatement->fetchAll();
                break;
            case 'INSERT':
                $return = $dbLink->lastInsertId();
                break;
            case 'UPDATE':
            case 'DELETE':
                $return  = $pdoStatement->rowCount();
                break;
            default:
                $return = false;
        }

        return $return;
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