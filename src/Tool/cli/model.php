<?php
/**
 * model.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/27 上午11:09
 * 修改记录:
 *
 * $Id$
 */

/** @var $allDbConfig array */


$columns = ['序号', '别名', '数据库类型', '服务器', '数据库名','用户', '密码', '默认字符'];
//$max = array_fill_keys($columns, 15);
$max = array_combine($columns, array_map(function($v){return mb_strlen($v)*2;}, $columns));
//var_export($max);
$i = 1;
foreach ($allDbConfig as $dbAliaName => $config){
    $max['序号'] = max($max['序号'], mb_strlen($i++));
    $max['别名'] = max($max['别名'], mb_strlen($dbAliaName));
    $max['数据库类型'] = max($max['数据库类型'], mb_strlen($config['type']));
    $max['服务器'] = max($max['服务器'], mb_strlen($config['host']));
    $max['数据库名'] = max($max['数据库名'], mb_strlen($config['dbName']));
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
$configIndex = [];
foreach($allDbConfig as $dbAliaName => $config){
    printf(
            "   %{$max['序号']}s%{$max['别名']}s%{$max['数据库类型']}s%{$max['服务器']}s%{$max['数据库名']}s%{$max['用户']}s%{$max['密码']}s%{$max['默认字符']}s \n",
            "[".++$i."]",$dbAliaName, $config['type'], $config['host'], $config['dbName'], $config['userName'], $config['password'], $config['charset']
        );
    $configIndex[$i] = $dbAliaName;
}

echo PHP_EOL."请选择数据库配置对应【序号】或【别名】：";
$select = trim(fgets(STDIN));
if(isset($configIndex[$select])){
    $dbAliaName = $configIndex[$select];
}elseif(isset($allDbConfig[$select])){
    $dbAliaName = $select;
}else{
    echo "输入错误，程序结束".PHP_EOL;
    die;
}

\phpcmx\ORM\Tool\OrmTool::cliChangeAction('modelList', [
    'dbAliaName' => $dbAliaName
]);
