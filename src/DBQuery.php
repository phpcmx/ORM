<?php
/**
 * Created by PhpStorm.
 *
 * @auth: fijdemon@qq.com
 * Date: 9/11/17
 * Time: 1:15 AM
 */

namespace phpcmx\ORM;


use phpcmx\ORM\inc\traits\FinalSingleEngine;
use phpcmx\ORM\syntactic\DeleteDb;
use phpcmx\ORM\syntactic\InsertDb;
use phpcmx\ORM\syntactic\SelectDb;
use phpcmx\ORM\syntactic\Sql;
use phpcmx\ORM\syntactic\UpdateDb;

/**
 * Class DBQuery
 * 数据库操作方法封装
 *
 * @package phpcmx\ORM
 */
class DBQuery
{
    use FinalSingleEngine;


    /**
     * 返回插入对象
     *
     * @param string $dbAliasName
     * @param string $tableName
     *
     * @return InsertDb
     */
    function insert($dbAliasName, $tableName = null):InsertDb{
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
    function delete($dbAliasName, $tableName = null):DeleteDb{
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
    function update($dbAliasName, $tableName = null):UpdateDb{
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
    function select($dbAliasName, $tableName = null):SelectDb{
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
    function sql($dbAliasName):Sql{
        $operation = new Sql($dbAliasName);
        return $operation;
    }

}