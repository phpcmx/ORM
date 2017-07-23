<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');


use phpcmx\mysql\DB;
use phpcmx\mysql\DBConfig;

spl_autoload_register(function($className){
    $baseDir = __DIR__."/../src/";
    $classPath = $baseDir.strtr($className, ["\\"=>"/","phpcmx\\mysql\\"=>'']).".php";

    include_once $classPath;
});


DBConfig::getInstance()->addDbConfig('localhost', 'test', 'test', 'test');


if(0) {
    DB::insert('test')
        ->table('test_table')
        ->one([
            'name' => '测试',
            'createTime' => 'now()',
        ])
        ->execute();
}
if(0){
    $dataList = DB::select('test', 'test_table')
        ->execute();

    var_export($dataList);
}
if(0){
    echo DB::delete('test', 'test_table')
        ->where([
            'id' => 2
        ])
        ->execute();
}
if(1){
    echo DB::update('test', 'test_table')
        ->set([
            'name' => '123',
//            'tess' => 'aaa',
        ])
        ->where([
//            'tiaojian' => 'tj',
            'id' => 1
        ])
        ->execute();
}


echo PHP_EOL."end".PHP_EOL;