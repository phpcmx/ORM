<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * time: 2017年07月22日23:51:26
 */

namespace phpcmx\ORM\syntactic;


use phpcmx\ORM\DBConfig;
use phpcmx\ORM\inc\traits\FinalSingleEngine;

/**
 * Class MakeWhere
 * 条件生成类
 * @package phpcmx\ORM\syntactic
 */
class WhereMaker
{
    use FinalSingleEngine;


    /**
     * 解析or
     * @param array $where or条件数组
     * @return string
     */
    public function or(array $where){
        $_whereArr = [];
        // 循环加入参数
        foreach ($where as $key => $value) {
            $_where = $this->parseComplexWhere($key, $value);

            $_whereArr[] = " ({$_where}) ";
        }

        return implode(' OR ', $_whereArr);
    }


    /**
     * 解析and
     * @param array $where
     * @return string
     */
    public function and(array $where){
        $_whereArr = [];
        // 循环加入参数
        foreach ($where as $key => $value) {
            $_where = $this->parseComplexWhere($key, $value);

            $_whereArr[] = " ({$_where}) ";
        }

        return implode(' AND ', $_whereArr);
    }


    /**
     * 解析复杂where
     *
     * @param $key
     * @param $value
     * @return string
     */
    private function parseComplexWhere($key, $value)
    {
        // 如果是数组，就安第一个元素作为操作符，第二个元素作为值
        if(is_array($value)){
            // 解析模式
            switch (strtoupper($value[0])){
                case 'IN' :
                    // ['in', [a,b,c,d,e]] in模式不进行值的替换
                    $_v = [];
                    foreach ($value[1] as $item) {
                        $_v[] = $this->parseValue($item);
                    }
                    $operation = 'IN';
                    $_value = "(".implode(',', $_v).")";
                    break;
                case 'BETWEEN' :
                    // ['between', [a,b]] between 模式
                    $operation = 'BETWEEN';
                    $_value = $this->parseValue($value[1][0])." AND ".$this->parseValue($value[1][1]);
                    break;
                default:
                    // [a,b,c,d,e] 未知模式就只解析第二个元素
                    // ['>', 4] ['!=', 10]
                    $operation = ($value[0]);
                    $_value = $this->parseValue($value[1]);
            }

            return "{$key} {$operation} {$_value}";
        }elseif(is_numeric($key)){
            // 如果key是数字，那就直接返回字符串
            return $value;
        }else{
            // 最简单的key value模式
            return $key." = ". $this->parseValue($value);
        }
    }


    /**
     * 处理要插入数据库的值
     *
     * @param $value
     * @return int|string
     */
    public function parseValue($value){
        // 对value进行处理
        // 如果是纯数字或者函数，就不处理
        if(is_numeric($value) || DBConfig::getInstance()->isKeep($value)){
            $v = $value;
        }
        // 如果是字符串，就加单引号
        else{ //if(is_string($value)){
            $v = "'{$value}'";
        }

        return $v;
    }
}