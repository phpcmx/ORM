<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/13
 * Time: 16:15
 */

namespace phpcmx\ORM\entity;


/**
 * 数据容器
 * Class DataAdapter
 *
 * @package phpcmx\orm\entity
 */
class DataAdapter implements \ArrayAccess
{
    ////////////////////////////////////////////////////////////////////////////
    /// 数组方式操作
    ////////////////////////////////////////////////////////////////////////////

    /**
     * 数据存储
     * @var array
     */
    private $__dataList = [];

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->__dataList[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->__dataList[$offset];
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if($offset === null){
            $this->__dataList[] = $value;
        }else{
            $this->__dataList[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset) {
        throw new \LogicException('不允许操作');
    }


    ////////////////////////////////////////////////////////////////////////////
    /// 数据操作
    ////////////////////////////////////////////////////////////////////////////


    /**
     * 游标
     * @var int
     */
    private $cursor = 0;


    /**
     * 获取下一个元素
     * @param null|int $cursor 设置游标
     *
     * @return false|mixed
     */
    public function next(int $cursor = null)
    {
        if($cursor!==null){
            $this->cursor = $cursor;
        }

        return $this->__dataList[$this->cursor++] ?? false;
    }


    /**
     * 重置游标
     */
    public function reset()
    {
        $this->cursor = 0;
    }


    /**
     * 遍历获取方法
     * @return \Generator
     */
    public function yieldEach()
    {
        foreach ($this->__dataList as $index => $item) {
            yield $this->__dataList[$index];
        }
    }


    /**
     * 获取总数据的方法
     * @return int
     */
    public function count(){
        return count($this->__dataList);
    }
}