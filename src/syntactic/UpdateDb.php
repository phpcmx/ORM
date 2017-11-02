<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Time: 2017年07月24日00:13:30
 */

namespace phpcmx\ORM\syntactic;


use phpcmx\ORM\behavior\DbBehavior;
use phpcmx\ORM\DB;
use phpcmx\ORM\DBConfig;
use phpcmx\ORM\exception\LackOfOperation;

class UpdateDb extends BaseDb
{
    private $_field = [];

    private $_values = [];

    private $_where = null;


    /**
     * 设置参数
     *
     * @param array $data
     *
     * @return $this
     */
    public function set($data){
        foreach ($data as $key => $value) {
            $this->_field[] = $key;
            $this->_values[] = $value;
        }

        return $this;
    }


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
     * @return mixed
     */
    protected function sqlExecute()
    {
        $this->makeSqlValue();

        $row = DbBehavior::getInstance()->queryReturnRowCount(
            DBConfig::getInstance()->getDbLinkCache($this->dbAliasName),
            $this->_sqlStr,
            $this->_sqlValue
        );

        return $row;
    }

    /**
     * 生成sql语句
     */
    protected function makeSqlStr()
    {
        // 如果对应的值是标量直接写入，如果是array，就写入0元素
        $_field = array_map(function($k, $v){
            if(DBConfig::getInstance()->isKeep($v)){
                return $k."=".$v;
            }else{
                return $k."=?";
            }
        }, $this->_field, $this->_values);
        $field = implode(',', $_field);

        $this->_sqlStr = "UPDATE {$this->_tableName} SET {$field} WHERE {$this->_where}";
    }


    /**
     * 生成sql值
     */
    private function makeSqlValue()
    {
        $_values = [];   // 真正要传入的数组
        foreach ($this->_values as $index => $value) {
            if (!DBConfig::getInstance()->isKeep($value)) {
                // 如果是不是保留函数，就添加到数组里
                $_values[] = $value;
            }
        }

        $this->_sqlValue = $_values;
    }



    /**
     * 检查必须要操作的流程
     * @return void
     */
    protected function checkRequire()
    {
        if(empty($this->_where)){
            throw new LackOfOperation('未设置条件');
        }
        if($this->_where == '1'){
            throw new LackOfOperation('不能更新所有数据');
        }
    }
}