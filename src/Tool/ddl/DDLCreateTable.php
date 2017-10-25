<?php
/**
 * Created by PhpStorm.
 * User: 曹梦欣 <caomengxin@zhibo.tv>
 * Date: 2017/10/25
 * Time: 13:37
 */

namespace phpcmx\ORM\Tool\ddl;

define('PREPARE',           0x1);
define('TABLE',             0x2);
define('DEFINITION',        0x4);
define('MORE',              0x8);
define('END',               0x10);

define('FIELD_FIELD',       0x100);
define('FIELD_TYPE',        0x200);
define('FIELD_ATTR',        0x400);

/**
 * Class DDLCreateTable
 *
 * @package phpcmx\ORM\Tool\ddl
 */
class DDLCreateTable extends DDLAbstract
{
    public $tableName = '';

    public $fields = [];

    public $fieldType = [];
    public $fieldAttr = [];

    protected function parseStart()
    {
        if(empty($this->ddlString)){
            throw new \RuntimeException('请确定您是否已经执行load参数加载ddl，或ddl为空');
        }
        $ddl = trim($this->ddlString);
        if(strtoupper(substr($ddl, 0, 12)) != 'CREATE TABLE'){
            throw new \RuntimeException('您解析的并不是create table操作:'.$ddl);
        }

        $keywords = [];
        $statusList = [
            1,
            PREPARE,
            TABLE,
            DEFINITION,
            MORE,
            END,
        ];
        $status = $statusList[$statusList[0]];
        $prepareStr = "";
        $field = '';

        while($keyword = $this->next()){
            $keywords[] = $keyword;
//            if(strpos("\'\"\`", $keyword) !==false){
//                $test[] = $this->stringEnd($keyword);
//            }else{
//                $test[] = $keyword;
//            }
            if($status & PREPARE) {
                // 准备阶段，一直合并直到获取table
                $prepareStr .= strtoupper($keyword);
                if ($prepareStr == 'CREATETABLE') {
                    $status = $status ^ $statusList[$statusList[0]] | $statusList[++$statusList[0]];
                }
            }elseif($status & TABLE) {
                // 表名阶段，抓取表名
                if ($keyword == '`') {
                    $this->tableName = $this->stringEnd($keyword);
                } else {
                    $this->tableName = $keyword;
                }

                $status = $status ^ $statusList[$statusList[0]] | $statusList[++$statusList[0]];
            }elseif($status & DEFINITION) {
                // 定义阶段要批量获取，分行进行处理
                if($keyword == '(' and empty($this->fields)){
                    continue;
                }

                if($status & FIELD_TYPE){
                    if($keyword == '('){
                        $this->fieldType[$field]['len'] = $this->stringEnd(')');

                        $status = $status & ~(FIELD_FIELD | FIELD_TYPE | FIELD_ATTR) | FIELD_ATTR;
                    }else
                        $this->fieldType[$field]['type'] = $keyword;
                }elseif($status & FIELD_ATTR){
                    if($keyword == ','){
                        $field = '';
                        $status = $status & ~(FIELD_FIELD | FIELD_TYPE | FIELD_ATTR) | FIELD_FIELD;
                    }else{
                        if(strpos("\'\"", $keyword)!==false){
                            $this->fieldAttr[$field][] = $this->stringEnd($keyword);
                        }else{
                            $this->fieldAttr[$field][] = $keyword;
                        }
                    }
                } else{
                    if($keyword == ')'){
                        $status = $status ^ $statusList[$statusList[0]] | $statusList[++$statusList[0]];
                    }
                    if ($keyword == '`') {
                        $field = $this->fields[] = $this->stringEnd($keyword);
                    } else {
                        $field = $this->fields[] = $keyword;
                    }
                    $this->fieldType[$field]['field'] = $field;
                    $this->fieldAttr[$field] = [];

                    $status = $status & ~(FIELD_FIELD | FIELD_TYPE | FIELD_ATTR) | FIELD_TYPE;
                }


            }elseif($status & MORE){
                // 更多信息处理

            }else{
                // END 结束处理
            }
        }
    }

}