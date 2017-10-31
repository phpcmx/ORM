<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/25
 * Time: 14:09
 */

namespace phpcmx\ORM\Tool\ddl;


/**
 * ddl解析器的基类
 * Class DDLAbstract
 *
 * @package phpcmx\ORM\Tool\ddl
 */
abstract class DDLAbstract implements DDLInterf
{
    protected $ddlString = '';

    /**
     * 解析ddl字符串
     *
     * @param string $ddl
     */
    public function load($ddl)
    {
        $this->ddlString = $ddl;

        $this->parseStart();
    }

    abstract protected function parseStart();

    ////////////////////////////////////////////////////////////////////////////
    /// START 游标获取字母
    ////////////////////////////////////////////////////////////////////////////

    private $__cursor = 0;

    private $__special = [
        'split' => ' 
',
        'special' => '\`\"\'(){}[];',
    ];

    /**
     * 设置特殊字符
     *
     * @param string $split
     * @param string $special
     */
    protected function setSpecialString(string $split=null, string $special=null){
        $this->__special = [
            'split' => $split ?? $this->__special['split'],
            'special' => $special ?? $this->__special['special'],
        ];
    }

    /**
     * 重置游标位置
     */
    protected function reset(){
        $this->__cursor = 0;
    }

    /**
     * 获取下一个有用字符串
     * @return bool|string
     */
    protected function next(){
        // 第一个字符
        $next = $this->ddlString[$this->__cursor++]?? false;
        if($next===false){
            return false;
        }
        // 如果是特殊需要返回的字符，直接返回字符
        if(strpos($this->__special['special'], $next) !== false){
            return $next;
        }
        // 过滤空白字段
        if(strpos($this->__special['split'], $next) !== false){
            return $this->next();
        }

        $string = '';
        while(1) {
            $string .= $next;
            $next = $this->ddlString[$this->__cursor++] ?? false;
            if($next===false){
                return false;
            }
            if (strpos($this->__special['split'], $next)!==false) {
                return $string;
            }
            if (strpos($this->__special['special'], $next)!==false) {
                $this->__cursor--;
                return $string;
            }
        }

        // ide tips
        return false;
    }


    /**
     * 判断下一个字符是不是参数
     * @param $string
     *
     * @return bool
     */
    public function nextIs($string)
    {
        $cursor = $this->__cursor;
        $next = $this->next();
        $this->__cursor = $cursor;

        return $string == $next;
    }


    /**
     * @param $endFlag
     *
     * @return bool|string
     * @author 曹梦欣 <caomengxin@zhibo.tv>
     */
    protected function stringEnd($endFlag)
    {
        $ddl = trim($this->ddlString);
        $pos = strpos($ddl, $endFlag, $this->__cursor);
        if($pos === false){
            return false;
        }

        $start = $this->__cursor;
        $this->__cursor = $pos+1;
        return substr($ddl,$start, $pos-$start);
    }


    ////////////////////////////////////////////////////////////////////////////
    /// END 获取游标结束
    ////////////////////////////////////////////////////////////////////////////
}