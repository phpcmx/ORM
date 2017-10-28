<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/12
 * Time: 19:02
 */

namespace phpcmx\ORM\entity;

use phpcmx\ORM\DB;
use phpcmx\ORM\inc\interf\Loadable;
use phpcmx\ORM\syntactic\SelectDb;

/**
 * 数据库模型
 * Class Model
 *
 * @package phpcmx\orm\entity
 */
abstract class TableEntity implements Loadable
{
    /**
     * 数据库连接名称（别名）
     * @return string
     */
    abstract public static function dbAliaName() : string;


    /**
     * 表名
     * @return string
     */
    abstract public static function tableName() : string;


    /**
     * 字段列表（带描述）
     * @return array
     */
    abstract public static function attribute() : array;


    /**
     * 数据库结构描述
     * @return string
     */
    abstract public static function definition() : string;


    /**
     * 主键列表
     * @return array
     */
    abstract public static function primary() : array;


    ////////////////////////////////////////////////////////////////////////////
    /// 字段操作
    ////////////////////////////////////////////////////////////////////////////

    /**
     * 字段缓存
     * @var array
     */
    private $__fieldValue = [];

    /**
     * 不允许被实例化(new)
     * TableEntity constructor.
     */
    protected function __construct(){
        // 初始化所有的字段
        $this->__fieldValue = array_map(function(){ return null;}, static::attribute());
    }


    /**
     * 更新数据到当前类内
     *
     * @param array $array
     *
     * @return static
     */
    static public function load(array $array)
    {
        $static = new static();
        $static->__resetEntity($array);
        return $static;
    }


    /**
     * 重置内容的值
     * @param array $array
     */
    protected function __resetEntity(array $array){
        foreach (static::attribute() as $field => $label) {
            $this->__fieldValue[$field] = $array[$field] ?? null;
        }
    }


    /**
     * 设置字段值
     * @param $field
     * @param $value
     */
    public function __set($field, $value)
    {
        if(!isset($this->__fieldValue[$field])){
            throw new \OutOfRangeException('未找到字段：'.$field);
        }

        $this->__fieldValue[$field] = $value;
    }


    /**
     * 获取字段的值
     * @param $field
     *
     * @return mixed
     */
    public function __get($field)
    {
        if(!isset($this->__fieldValue[$field])){
            throw new \OutOfRangeException('未找到字段：'.$field);
        }

        return $this->__fieldValue[$field];
    }


    ////////////////////////////////////////////////////////////////////////////
    /// sql操作
    ////////////////////////////////////////////////////////////////////////////


    /**
     * 查询数据
     *
     * eg.
     * static::select()
     * 　　->field('id', 'name', 'value')     // 查询字段
     * 　　->where([          // id = 2 and create_time < 2017-10-27 15:52:10
     * 　　　　'id' => 2,
     * 　　　　'create_time' => [
     * 　　　　　　'<', date('Y-m-d H:i:s')
     * 　　　　]
     * 　　])
     * 　　->andWhere([       // and type = 1
     * 　　　　'type' => 1,
     * 　　])
     * 　　->groupBy('type')  // group by type
     * 　　->orderBy('id desc')    // ->orderBy(['id'=>'desc'])
     * 　　->limit(5)  // ->limit([0,20])
     * 　　->execute();
     *
     * @return SelectDb|static
     */
    public static function select()
    {
        return DB::query()->select(static::dbAliaName(), static::tableName())
            ->returnAs(static::class);
    }


    /**
     * 通过使用 主键（单一主键） 或者 条件（能确定唯一数据的）获取唯一数据
     *
     * @param string|int|array $idOrWhere 当是string|int的时候被认为是唯一id， 当传递数组的时候被认为是where条件
     *
     * @return false|mixed false说明未能识别参数或查询为空
     */
    public static function getOne($idOrWhere)
    {
        $primaryKey = static::primary();

        $priCnt = count($primaryKey);
        // 当主键只有一个，并且传递一个主键的时候
        if($priCnt == 1 and is_scalar($idOrWhere)){
            $dataAdapter = static::select()
                ->where([
                    $primaryKey[0] => $idOrWhere,
                ])
                ->limit(1)
                ->execute();
            return $dataAdapter->next(0);
        }
        // 传递where条件的时候
        elseif(is_array($idOrWhere)){
            $dataAdapter = static::select()
                ->where($idOrWhere)
                ->limit(1)
                ->execute();
            return $dataAdapter->next(0);
        }else{
            return false;
        }
    }


    /**
     * 获取count
     *
     * @param $where
     *
     * @return bool
     */
    public static function count($where)
    {
        $dataAdapter = DB::query()->select(static::dbAliaName(), static::tableName())
            ->returnAs(DB::MODE_KEY)
            ->field('count(*) cnt')
            ->where($where)
            ->execute();

        if($info = $dataAdapter->next(0)){
            return $info['cnt'];
        }

        return false;
    }


    /**
     * 添加
     *
     * eq.
     * static::insertExecute([
     * 　　'name' => '张三',
     * 　　'sex' => '1',
     * ]);
     *
     * @param array $data
     *
     * @return int
     */
    public static function insertExecute(array $data)
    {
        return DB::query()->insert(static::dbAliaName(), static::tableName())
            ->one($data)
            ->execute();
    }


    /**
     * 删除数据
     * eg.
     * static::deleteExecute(['id'=>123]);
     *
     * @param string|array $where
     *
     * @return int
     */
    public static function deleteExecute($where)
    {
        return DB::query()->delete(static::dbAliaName(), static::tableName())
            ->where($where)
            ->execute();
    }


    /**
     * 修改数据
     * eg.
     * static::updateExecute([
     * 　　'name' => '李四',
     * 　　'sex' => '0'
     * ], [
     * 　　'id' => '10',
     * ]);
     *
     * @param array $data
     * @param       $where
     *
     * @return int
     */
    public static function updateExecute(array $data, $where)
    {
        return DB::query()->update(static::dbAliaName(), static::tableName())
            ->set($data)
            ->where($where)
            ->execute();
    }
}