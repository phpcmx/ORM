<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Time: 2017年07月23日01:37:19
 */

namespace phpcmx\ORM\syntactic;


use phpcmx\ORM\behavior\DbBehavior;
use phpcmx\ORM\DB;
use phpcmx\ORM\DBConfig;
use phpcmx\ORM\entity\DataAdapter;
use phpcmx\ORM\exception\LackOfOperation;
use phpcmx\ORM\inc\interf\Loadable;

class SelectDb extends BaseDb
{
    private $_field = '*';

    private $_where = '1';

    private $_group = null;

    private $_order = null;

    private $_limit = null;

    private $_mode = DB::MODE_KEY;

    private $_adapter = null;

    /**
     * @var Loadable
     */
    private $_loader = null;


    /**
     * 设置要查询的列表
     *
     * @param array ...$list
     * @return SelectDb
     */
    public function field(...$list)
    {
        // 非空才处理
        if(!empty($list)) {
            // 如果是数组，直接使用
            if (is_array($list[0])) {
                $list = $list[0];
            }
            // 赋值
            $this->_field = implode(', ', $list);
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
     * 添加条件
     *
     * @param $where
     * @return SelectDb
     */
    public function andWhere($where){
        if(is_string($where)){
            $this->_where .= " and ({$where})";
        }elseif(is_array($where)){
            $this->_where .= " and (".DB::whereMaker()->and($where).")";
        }

        return $this;
    }


    /**
     * 设置group分组
     *
     * @param string $group
     * @return SelectDb
     */
    public function groupBy($group){
        $this->_group = $group;

        return $this;
    }


    /**
     * 设置order条件
     *
     * @param string | array $order
     * @return SelectDb
     */
    public function orderBy($order)
    {
        // 如果是字符串就直接赋值
        if(is_string($order)){
            $this->_order = $order;
        }
        // 如果是数组，就是 field=>method
        elseif(is_array($order)){
            $_orderArr = [];
            foreach ($order as $field => $method) {
                $_orderArr[] = "{$field} {$method}";
            }

            $this->_order = implode(' , ', $_orderArr);
        }


        return $this;
    }

    /**
     * 设置limit参数
     *
     * @param null $limit
     * @return SelectDb
     */
    public function limit($limit)
    {
        // 去除limit
        if($limit === null or $limit === false){
            $this->_limit = null;
            return $this;
        }

        // 如果数组，只能有两个元素
        if(is_array($limit)){
            $limit = $limit[0].", ".$limit[1];
        }
        // 如果是数字，就是只要几个数据
        elseif(is_numeric($limit)){
            $limit = "0, ".$limit;
        }
        // 如果是字符串，就直接复制
        elseif(is_string($limit)){
            // 赋值
        }
        // 如果什么都不是，就直接不处理
        else{
            return $this;
        }

        $this->_limit = $limit;

        return $this;
    }


    /**
     * 生成并运行sql语句
     * @return DataAdapter | static[]
     */
    protected function sqlExecute()
    {
        // 查询
        $result = DbBehavior::getInstance()->queryNeedFetch(
            DBConfig::getInstance()->getDbLinkCache($this->dbAliasName),
            $this->_sqlStr,
            $this->_sqlValue,
            is_null($this->_loader) ? $this->_mode : DB::MODE_KEY);

        $dataAdapter = is_null($this->_adapter) ? []: new $this->_adapter();
        if(is_subclass_of($this->_loader, Loadable::class)){
            $loader = $this->_loader;
            foreach ($result as $index => $item) {
                /** @var Loadable $_tmp */
                $_tmp = $loader::load($item);
                $dataAdapter[] = $_tmp;
            }
        }else{
            $dataAdapter = $result;
        }

        // 返回结果
        return $dataAdapter;
    }


    /**
     * 生成sql语句
     */
    protected function makeSqlStr()
    {
        $sqlStr = "SELECT {$this->_field} FROM {$this->_tableName} WHERE {$this->_where}";

        if($this->_group!==null){
            $sqlStr .= " GROUP BY {$this->_group}";
        }

        if($this->_order!==null ){
            $sqlStr .= " ORDER BY {$this->_order}";
        }

        if($this->_limit!=null){
            $sqlStr .= " LIMIT {$this->_limit}";
        }

        $this->_sqlStr = $sqlStr;
    }


    /**
     * 设置获取模式
     *
     * @param int | Loadable $DB_MODE_or_Loadable DB::MODE_xxx
     *
     * @return SelectDb
     */
    public function returnAs($DB_MODE_or_Loadable = null){
        // 如果是配置的可加载的类
        if(class_exists($DB_MODE_or_Loadable) and is_subclass_of($DB_MODE_or_Loadable, Loadable::class)){
            $this->_loader = $DB_MODE_or_Loadable;
            return $this;
        }

        // 如果是配置选项 DB::MODE_BOTH
        if (!empty($DB_MODE_or_Loadable) and is_numeric($DB_MODE_or_Loadable)) $this->_mode = $DB_MODE_or_Loadable;

        return $this;
    }


    /**
     * 设置容器
     *
     * @param string $dataAdapter 必须是 Array
     *
     * @return SelectDb
     */
    public function adapter($dataAdapter)
    {
        if(is_subclass_of($dataAdapter, \ArrayAccess::class))
            $this->_adapter = $dataAdapter;

        return $this;
    }


    /**
     * 检查必须要操作的流程
     * @return void
     */
    protected function checkRequire()
    {
        if(empty($this->_tableName)){
            throw new LackOfOperation('未设置表名');
        }
    }
}