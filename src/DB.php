<?php

/**
 * 数据库操作封装
 * Created by 不二进制·Number
 * 时间： 2017年07月21日18:39:32
 */

namespace phpcmx\ORM;
use PDO;
use phpcmx\ORM\entity\TableEntity;
use phpcmx\ORM\exception\NotTheSameDbConnection;
use phpcmx\ORM\syntactic\Transaction;
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
     * 返回事务
     *
     * @param string[]|TableEntity[] $tableEntities  TableEntity 的类，把要进行事务的所有的类都写进去
     * @return Transaction | false
     * @throws NotTheSameDbConnection
     */
    static function transaction(string ...$tableEntities):Transaction{
        if(empty($tableEntities)){
            return false;
        }
        foreach ($tableEntities as $index => $tableEntity) {
            if(!is_subclass_of($tableEntity, TableEntity::class)){
                return false;
            }
        }

        $dbAliasName = $tableEntities[0]::dbAliaName();
        foreach ($tableEntities as $index => $item) {
            if($item::dbAliaName() != $dbAliasName){
                throw new NotTheSameDbConnection('必须是相同的别名设置:'.$dbAliasName."!=".$item::dbAliaName());
            }
        }

        return new Transaction($dbAliasName);
    }


    /**
     * 返回数据库操作
     *
     * @return DBQuery
     *
     */
    public static function query()
    {
        return DBQuery::getInstance();
    }


    /**
     * 返回config对象
     * @return DBConfig
     */

    public static function config()
    {
        return DBConfig::getInstance();
    }


    /**
     * 返回项目根目录
     * @return string
     */
    public static function DIR_PACKAGE()
    {
        return dirname(__DIR__);
    }


    /**
     * debug的跟踪模式 获取信息，请无参请求
     * @var null
     */
    private static $trace = null;

    public static function trace($type=null, $id=null, $info=null)
    {
        if($type === null){
            return self::$trace;
        }elseif(!isset(self::$trace[$type])){
            self::$trace[$type] = [];
        }

        self::$trace[$type][$id] = array_merge(self::$trace[$type][$id]??[], $info);
        return null;
    }
}