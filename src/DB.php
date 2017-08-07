<?php

/**
 * 数据库操作封装
 * Created by 不二进制·Number
 * 时间： 2017年07月21日18:39:32
 */

namespace phpcmx\ORM;
use PDO;
use phpcmx\ORM\syntactic\DeleteDb;
use phpcmx\ORM\syntactic\InsertDb;
use phpcmx\ORM\syntactic\SelectDb;
use phpcmx\ORM\syntactic\Sql;
use phpcmx\ORM\syntactic\Transaction;
use phpcmx\ORM\syntactic\UpdateDb;
use phpcmx\ORM\syntactic\WhereMaker;

/**
 * 数据库操作 工厂模式 调度类
 * Class DB
 */
final class DB
{

    // 返回模式 key为列名
    const MODE_KEY = PDO::FETCH_ASSOC;
    // 返回模式 key为数字
    const MODE_NUM = PDO::FETCH_NUM;
    // 返回模式 key包含列名及数字
    const MODE_BOTH = PDO::FETCH_BOTH;

    /**
     * DB constructor.
     * 此类不可被实例化
     */
    private function __construct(){}

    /**
     * @return WhereMaker
     */
    static function whereMaker():WhereMaker{
        return WhereMaker::getInstance();
    }

    /**
     * 返回插入对象
     *
     * @param string $dbAliasName
     * @param string $tableName
     *
     * @return InsertDb
     */
    static function insert($dbAliasName, $tableName = null):InsertDb{
        $operation = new InsertDb($dbAliasName);
        if(!is_null($tableName))
            return $operation->table($tableName);
        else
            return $operation;
    }


    /**
     * 返回删除对象
     *
     * @param string $dbAliasName
     * @param string $tableName
     *
     * @return DeleteDb
     */
    static function delete($dbAliasName, $tableName = null):DeleteDb{
        $operation = new DeleteDb($dbAliasName);
        if(!is_null($tableName))
            return $operation->table($tableName);
        else
            return $operation;
    }


    /**
     * 返回更新对象
     *
     * @param $dbAliasName
     * @param $tableName
     *
     * @return UpdateDb
     */
    static function update($dbAliasName, $tableName):UpdateDb{
        $operation = new UpdateDb($dbAliasName);
        if(!is_null($tableName))
            return $operation->table($tableName);
        else
            return $operation;
    }


    /**
     * 返回查询对象
     *
     * @param string $dbAliasName
     * @param string $tableName
     *
     * @return SelectDb
     */
    static function select($dbAliasName, $tableName = null):SelectDb{
        $operation = new SelectDb($dbAliasName);
        if(!is_null($tableName))
            return $operation->table($tableName);
        else
            return $operation;
    }


    /**
     * 返回sql执行对象
     *
     * @param $dbAliasName
     *
     * @return Sql
     */
    static function sql($dbAliasName):Sql{
        $operation = new Sql($dbAliasName);
        return $operation;
    }


    /**
     * 返回事务
     *
     * @param $dbAliasName
     *
     * @return Transaction
     */
    static function transaction($dbAliasName):Transaction{
        return new Transaction($dbAliasName);
    }
}