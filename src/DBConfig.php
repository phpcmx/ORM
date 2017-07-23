<?php
/**
 * DBConfig.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/22 下午10:34
 * 修改记录:
 *
 * $Id$
 */

namespace phpcmx\mysql;


use phpcmx\mysql\behavior\DbBehavior;
use phpcmx\mysql\inc\traits\SingleEngine;

/**
 * Class DBConfig
 * 配置类，所有关于数据库的【可变】数据，均在此类保存
 * @package phpcmx\mysql
 */
final class DBConfig
{
    use SingleEngine;

    const CHARSET_UTF8 = 'utf8';
    const CHARSET_GBK = 'gbk';

    /**
     * @var array 数据库的连接配置
     */
    protected $dbConfig = [];

    /**
     * @var \PDO[] 数据库连接缓存
     */
    protected $dbCache = [];

    /**
     * @var array 保留字段,用来判断是否在条件里进行字符串解析
     */
    protected $keep = ['now()'];

    /**
     * 添加数据库配置
     *
     * @param $host
     * @param $dbName
     * @param $userName
     * @param $password
     * @param string $charset
     *
     * @return DBConfig
     */
    public function addDbConfig($host, $dbName, $userName, $password, $charset=self::CHARSET_UTF8): DBConfig
    {
        $this->dbConfig[$dbName] = [
            'type' => 'mysql',
            'host' => $host,
            'dbName' => $dbName,
            'charset' => $charset,
            'userName' => $userName,
            'password' => $password,
        ];
        return $this;
    }

    /**
     * 获取数据库连接配置
     *
     * @param $dbName
     * @return array
     */
    public function getDbConfig($dbName): array
    {
        return $this->dbConfig[$dbName] ?? [];
    }

    /**
     * 获取数据库连接
     *
     * @param string $dbName
     * @return bool|\PDO
     */
    public function getDbCache(string $dbName){
        if(!isset($this->dbCache[$dbName]) or empty($this->dbCache)){
            $dbConfig = $this->getDbConfig($dbName);
            $this->dbCache[$dbName] = DbBehavior::getInstance()->createDbLink(
                $dbConfig['type'],
                $dbConfig['host'],
                $dbConfig['dbName'],
                $dbConfig['charset'],
                $dbConfig['userName'],
                $dbConfig['password']
            );
        }

        return $this->dbCache[$dbName];
    }


    /**
     * 判断是否是保存字段（不用添加引号等处理）
     * @param $value
     * @return bool
     */
    public function isKeep($value):bool{
        return in_array(strtolower($value), $this->keep);
    }
}