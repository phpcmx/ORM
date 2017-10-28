<?php

/**
 * 数据库操作封装
 * Created by Phpstorm
 * @author 不二进制·Number
 */

namespace phpcmx\ORM\behavior;

use PDO;
use phpcmx\ORM\exception\ExecuteWasFailed;
use phpcmx\ORM\inc\traits\FinalSingleEngine;

/**
 * Class DbBehavior
 * pdo的数据库操作
 *
 * @package fijdemon\core\lib\db\behavior
 */
class DbBehavior
{
    use FinalSingleEngine;

    /**
     * @var bool 是否开启debug
     */
    public $debug = false;

    public $debugInfo = null;

    /**
     * 连接数据库
     *
     * @param string $type
     * @param string $host
     * @param string $dbName
     * @param string $charset
     * @param string $userName
     * @param string $password
     *
     * @return PDO
     */
    public function createDbLink(string $type,string $host,string $dbName,string $charset,string $userName,string $password) : PDO
    {

        // 生成连接数据
        $dsn = "{$type}:host={$host};dbname={$dbName};charset={$charset}";
        $dbLink     = new PDO($dsn, $userName, $password);

        return $dbLink;
    }


    /**
     * 执行需要返回数据的sql语句
     *
     * @param PDO $db
     * @param string $sqlStr sql语句
     * @param array $valueList 参数列表
     * @param int $mode 返回模式
     * @return array
     */
    public function queryNeedFetch(PDO $db, string $sqlStr, array $valueList, int $mode)
    {
        // 执行
        $sql = $db->prepare($sqlStr);

        // 执行
        if(!$sql->execute($valueList)){
            throw new ExecuteWasFailed(json_encode($sql->errorInfo()));
        }

        // 设置返回格式
        $sql->setFetchMode($mode);;

        $result = $sql->fetchAll();

        // debug info
        $this->debug and $this->debugInfo = $sql->debugDumpParams();

        $sql->closeCursor();

        return $result;
    }


    /**
     * 插入数据库
     *
     * @param PDO $db
     * @param     $sqlStr
     * @param     $valueList
     *
     * @return int
     */
    public function queryReturnInsertId(PDO $db,string $sqlStr,array $valueList)
    {
        // 执行
        $sql = $db->prepare($sqlStr);

        // 执行
        if(!$sql->execute($valueList)){
            throw new ExecuteWasFailed(json_encode($sql->errorInfo()));
        }

        // debug info
        $this->debug and $this->debugInfo = $sql->debugDumpParams();

        $sql->closeCursor();

        // 返回刚刚插入的数据的id
        return $db->lastInsertId();
    }


    /**
     * 返回影响行数
     *
     * @param PDO $db
     * @param     $sqlStr
     * @param     $valueList
     *
     * @return bool|int
     */
    public function queryReturnRowCount(PDO $db, $sqlStr, $valueList)
    {
        // 执行
        $sql = $db->prepare($sqlStr);

        // 执行
        if(!$sql->execute($valueList)){
            throw new ExecuteWasFailed(json_encode($sql->errorInfo()));
        }

        // debug info
        $this->debug and $this->debugInfo = $sql->debugDumpParams();

        $sql->closeCursor();

        // 返回刚刚插入的数据的id
        return $sql->rowCount();
    }


//    /**
//     * 执行sql语句
//     *
//     * @param PDO $db
//     * @param     $sqlStr
//     *
//     * @return null|\PDOStatement|false
//     */
//    public function queryReturnPDOStatement(PDO $db, $sqlStr)
//    {
////        $sql = null;
//        $sql = $db->query($sqlStr);
//
//        return $sql;
//    }

}