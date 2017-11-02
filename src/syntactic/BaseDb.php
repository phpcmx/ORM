<?php
/**
 * Created by 不二进制·Number.
 * User: 不二进制·Number
 * Date: 2017年07月21日18:54:43
 */

namespace phpcmx\ORM\syntactic;
use phpcmx\ORM\DB;
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
     * @var null|callable 执行前回调
     */
    protected $_hookBeforeExecute = null;

    /**
     * @var null|callable 执行后回调
     */
    protected $_hookAfterExecute = null;

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
     * 生成sql语句
     * @return mixed
     */
    abstract protected function makeSqlStr();


    /**
     * hookBeforeExecute
     * 检查必须要操作的流程
     * @return void
     */
    abstract protected function checkRequire();


    /**
     * hook 在执行前
     *
     * @param callable $fun
     */
    public function beforeExecute(callable $fun)
    {
        $this->_hookBeforeExecute = $fun;
    }


    /**
     * hook 在执行之后
     *
     * @param callable $fun
     */
    public function afterExecute(callable $fun)
    {
        $this->_hookAfterExecute = $fun;
    }


    /**
     * 最终执行
     *
     * @return int | DataAdapter | static[]
     */
    public function execute(){
        // 检查必须
        $this->checkRequire();

        // 执行前的钩子
        is_callable($this->_hookBeforeExecute) and ($this->_hookBeforeExecute)($this);

        // 生成语句
        $this->makeSqlStr();

        if(DB::config()->debug){
            $time = microtime(1);

            $traceId = microtime(1);
            DB::trace('sql', $traceId, [
                'sqlStr' => $this->_sqlStr,
                'dumpInfo' => $this->_sqlValue,
                'executeTime' => 0,
            ]);

            $return = $this->sqlExecute();

            $executeTime = microtime(1) - $time;
            DB::trace('sql', $traceId, [
                'sqlStr' => $this->_sqlStr,
                'dumpInfo' => $this->_sqlValue,
                'executeTime' => $executeTime,
            ]);
        }else{
            $return  = $this->sqlExecute();
        }

        // 执行后的钩子
        is_callable($this->_hookAfterExecute) and ($this->_hookAfterExecute)($this);

        return $return;
    }
}