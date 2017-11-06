<?php
/**
 * Created by PhpStorm.
 *
 * @auth: fijdemon@qq.com
 * Date: 11/6/17
 * Time: 6:22 PM
 */


/** @var $dbAliaName string */
/** @var $dbName string */
/** @var $modelPath string */
/** @var $modelNamespace string */
/** @var $tableList array */

if(!defined('__TEMP__65165463151')){
    define('__TEMP__65165463151',1);


    function showTable($tableList){

        $columns = ['序号', '表名', '注释', '状态'];

        $max = array_combine($columns, array_map(function($v){return mb_strlen($v)*2;}, $columns));

        $i = 1;
        foreach ($tableList as $index => $tr){
            $max['序号'] = max($max['序号'], mb_strlen($i++));
            $max['表名'] = max($max['表名'], mb_strlen($tr['Name']));
            $max['注释'] = max($max['注释'], mb_strlen($tr['Collation']));
            $max['状态'] = max($max['状态'], 6);
        }
        $max = array_map(function($v){return -(int)($v+3);}, $max);
        //var_export($max);
        echo PHP_EOL;


        echo "   ";
        foreach($columns as $index => $column){
            $m = ($max[$column]-mb_strlen($column));
            printf("%{$m}s", $column);
        }
        echo PHP_EOL;

        $i = 0;
        foreach ($tableList as $index => $item) {
            printf(
                "   %{$max['序号']}s%{$max['表名']}s%{$max['注释']}s%{$max['状态']}s \n",
                "[".++$i."]",$item['Name'], $item['Collation'], $item['fileStatus'] == 1?'可生成':($item['fileStatus']==0?"\033[41;37m已生成\033[0m":$item['fileStatus'])
            );
        }

        echo PHP_EOL;
    }


    function makeOneFile($dbAliaName){

        while(1) {
            echo "请输入表名(直接回车退出)：";
            $tableName = trim(fgets(STDIN));
            if(empty($tableName))return \phpcmx\ORM\Tool\OrmTool::cliChangeAction('modelList');
            \phpcmx\ORM\Tool\OrmTool::makeFile($dbAliaName, $tableName);

            echo "生成成功！" . PHP_EOL;
            echo "您可使用 [php 执行文件 makeModel {$dbAliaName} {$tableName}]来执行您刚刚的操作".PHP_EOL;
        }
    }


    function makeAllFile($dbAliaName, $tableList){

        foreach ($tableList as $index => $item) {
            \phpcmx\ORM\Tool\OrmTool::makeFile($dbAliaName, $item['Name']);
        }

        echo "生成成功!".PHP_EOL;
        echo "您可使用 [php 执行文件 makeAllModel {$dbAliaName}]";

        die;
    }

}

while(1) {
    echo <<<CMD
    
 [1] 查看完整表结构
 [2] 生成单个表
 [3] 生成所有表
 
CMD;
    echo "选择操作：";
    $select = trim(fgets(STDIN));
    switch ($select){
        case '1':
            showTable($tableList);
            break;
        case '2':
            makeOneFile($dbAliaName);
            break;
        case '3':
            makeAllFile($dbAliaName, $tableList);
            break;
        default:
            echo "命令错误。".PHP_EOL;
    }
}

