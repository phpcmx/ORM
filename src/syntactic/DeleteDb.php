<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Time: 2017年07月23日23:45:10
 */

namespace phpcmx\ORM\syntactic;


use phpcmx\ORM\behavior\DbBehavior;
use phpcmx\ORM\DB;
use phpcmx\ORM\DBConfig;
use phpcmx\ORM\exception\LackOfOperation;

class DeleteDb extends BaseDb
{

    private $_where = null;


    /**
     * 设置where条件
     *
     * @param $where string|array array模式只支持and模式
     * @return $this
     */
    public function where($where){
        if(is_string($where)) {
            $this->_where = $where;
        }elseif(is_array($where)){
            $this->_where = DB::whereMaker()->and($where);
        }

        return $this;
    }


    /**
     * 生成sql并运行返回结果
     *
     * @return int
     */
    protected function sqlExecute()
    {
        $this->makeSqlStr();

        $code = DbBehavior::getInstance()->delete(
            DBConfig::getInstance()->getDbLinkCache($this->dbAliasName),
            $this->_sqlStr,
            $this->_sqlValue
        );

        return $code;
    }



    /**
     * 生成sql语句
     */
    private function makeSqlStr()
    {
        $this->_sqlStr = "DELETE FROM {$this->_tableName} WHERE {$this->_where}";
    }

    /**
     * 检查必须要操作的流程
     * @return void
     */
    protected function checkRequire()
    {
        if(is_null($this->_where)){
            throw new LackOfOperation('未设置where条件');
        }
        if($this->_where == '1'){
            throw new LackOfOperation('不能设置全部删除');
        }
    }
}