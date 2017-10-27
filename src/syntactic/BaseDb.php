<?php
/**
 * Created by 不二进制·Number.
 * User: 不二进制·Number
 * Date: 2017年07月21日18:54:43
 */

namespace phpcmx\ORM\syntactic;
use phpcmx\ORM\entity\DataAdapter;


/**
 * 数据库查询封装方法基类
 * Class BaseDb
 */
abstract class BaseDb implements InterfaceDb
{

    /**
     * @var string  当前查询连接的数据库名称
     */
    protected $dbAliasName = null;

    /**
     * @var string 保存sql参数
     */
    protected $_sqlStr = null;

    /**
     * @var array 保存sql参数
     */
    protected $_sqlValue = [];

    /**
     * @var string 当前操作表名
     */
    protected $_tableName = null;

    /**
     * BaseDb constructor.
     * 初始化语句（数据库名称）
     *
     * @param string $dbAliasName 数据库名称
     */
    public function __construct(string $dbAliasName)
    {
        $this->dbAliasName = $dbAliasName;
    }


    /**
     * 设置table名称
     * @param string $tableName
     * @return $this
     */
    public function table(string $tableName)
    {
        $this->_tableName = $tableName;
        return $this;
    }

    /**
     * 拼接sql语句并运行返回结果
     *
     * @return mixed
     */
    abstract protected function sqlExecute();


    /**
     * 检查必须要操作的流程
     * @return void
     */
    abstract protected function checkRequire();


    /**
     * 最终执行
     *
     * @return int | DataAdapter | static[]
     */
    public function execute(){
        $this->checkRequire();
        return $this->sqlExecute();
    }
}