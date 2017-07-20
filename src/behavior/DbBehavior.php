<?php

/**
 * 数据库操作封装
 * Created by Phpstorm
 * @author 不二进制·Number
 */

namespace phpcmx\mysql\behavior;

use PDO;
use phpcmx\mysql\exception\ExecuteWasFailed;
use phpcmx\mysql\inc\traits\SingleEngine;

/**
 * Class DbBehavior
 * pdo的数据库操作
 *
 * @package fijdemon\core\lib\db\behavior
 */
class DbBehavior
{
    use SingleEngine;

    // 返回模式 key为列名
    const MODE_KEY = PDO::FETCH_ASSOC;
    // 返回模式 key为数字
    const MODE_NUM = PDO::FETCH_NUM;
    // 返回模式 key包含列名及数字
    const MODE_BOTH = PDO::FETCH_BOTH;

    /**
     * 连接数据库
     *
     * @param $type
     * @param $host
     * @param $dbName
     * @param $charset
     * @param $userName
     * @param $password
     *
     * @return PDO
     */
    public function createDbLink($type, $host, $dbName, $charset, $userName, $password) : PDO
    {

        // 生成连接数据
        $dsn = "{$type}:host={$host};dbname={$dbName};charset={$charset}";
        $dbLink     = new PDO($dsn, $userName, $password);

        return $dbLink;
    }


    /**
     * 执行select语句
     *
     * @param PDO $db
     * @param     $sqlStr
     * @param $valueList
     * @param     $mode
     * @return array|false
     */
    public function select(PDO $db, string $sqlStr, array $valueList, int $mode)
    {
        // 执行
        $sql = $db->prepare($sqlStr);

        // 执行
        if(!$sql->execute($valueList)){
            throw new ExecuteWasFailed($sql->errorInfo(), $sql->errorCode());
        }

        // 设置返回格式
        $sql->setFetchMode($mode);;

        $result = $sql->fetchAll();

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
    public function insert(PDO $db, $sqlStr, $valueList)
    {
        // 执行
        $sql = $db->prepare($sqlStr);

        // 执行
        if(!$sql->execute($valueList)){
            throw new ExecuteWasFailed($sql->errorInfo(), $sql->errorCode());
        }

        $sql->closeCursor();

        // 返回刚刚插入的数据的id
        return $db->lastInsertId();
    }


    /**
     * 更新
     *
     * @param PDO $db
     * @param     $sqlStr
     * @param     $valueList
     *
     * @return bool|int
     */
    public function update(PDO $db, $sqlStr, $valueList)
    {
        $re = 0;
        // 执行
        $sql = $db->prepare($sqlStr);

        // 执行
        if(!$sql->execute($valueList)){
            throw new ExecuteWasFailed($sql->errorInfo(), $sql->errorCode());
        }

        $sql->closeCursor();

        // 返回刚刚插入的数据的id
        return $re;
    }


    /**
     * @param PDO $db
     * @param     $sqlStr
     *
     * @param $valueList
     * @return bool|int
     */
    public function delete(PDO $db, $sqlStr, $valueList)
    {
        $re = 0;
        // 执行
        $sql = $db->prepare($sqlStr);

        // 执行
        if(!$sql->execute($valueList)){
            throw new ExecuteWasFailed($sql->errorInfo(), $sql->errorCode());
        }

        $sql->closeCursor();

        return $re;
    }


    /**
     * 执行sql语句
     *
     * @param PDO $db
     * @param     $sqlStr
     *
     * @return null|\PDOStatement|false
     */
    public function query(PDO $db, $sqlStr)
    {
        $sql = null;
        $sql = $db->query($sqlStr);

        return $sql;
    }

}