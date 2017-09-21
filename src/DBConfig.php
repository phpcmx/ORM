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

namespace phpcmx\ORM;


use phpcmx\ORM\behavior\DbBehavior;
use phpcmx\ORM\inc\traits\FinalSingleEngine;

/**
 * Class DBConfig
 * 配置类，所有关于数据库的【可变】数据，均在此类保存
 * @package phpcmx\ORM
 */
final class DBConfig
{
    use FinalSingleEngine;

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
     * @param string $dbAliasName 数据库别名，用来识别不同的配置的
     * @param string $host        服务器
     * @param string $dbName      数据库名
     * @param string $userName    用户名
     * @param string $password    密码
     * @param string $charset     默认字符
     *
     * @return DBConfig
     */
    public function addDbConfig(string $dbAliasName, string $host, string $dbName, string $userName, string $password, string $charset=self::CHARSET_UTF8): DBConfig
    {
        $this->dbConfig[$dbAliasName] = [
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
     * @param $dbAliasName
     *
     * @return array
     */
    public function getDbConfig(string $dbAliasName): array
    {
        return $this->dbConfig[$dbAliasName] ?? [];
    }


    /**
     * 获取所有的数据库配置
     * @return array
     */
    public function getAllDbConfig() : array
    {
        return $this->dbConfig;
    }


    /**
     * 获取数据库连接
     *
     * @param string $dbAliasName
     *
     * @return bool|\PDO
     */
    public function getDbLinkCache(string $dbAliasName){
        if(!isset($this->dbCache[$dbAliasName]) or empty($this->dbCache)){
            $dbConfig                    = $this->getDbConfig($dbAliasName);
            $this->dbCache[$dbAliasName] = DbBehavior::getInstance()->createDbLink(
                $dbConfig['type'],
                $dbConfig['host'],
                $dbConfig['dbName'],
                $dbConfig['charset'],
                $dbConfig['userName'],
                $dbConfig['password']
            );
        }

        return $this->dbCache[$dbAliasName];
    }


    /**
     * 判断是否是保存字段（不用添加引号等处理）
     * @param $value
     * @return bool
     */
    public function isKeep($value):bool{
        return in_array(strtolower($value), $this->keep);
    }


    /**
     * 地址转换函数
     * {vendor}{phpcmx}
     * @param $filePath
     */
    public static function filePathReplace($filePath)
    {
        $replace = [];
        $replace['{vendor}'] = dirname(dirname(DB::DIR_PACKAGE()));
        $replace['{phpcmx}'] = $replace['{vendor}'].DIRECTORY_SEPARATOR."phpcmx";

        return strtr($filePath, $replace);
    }
}