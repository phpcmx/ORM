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


DBConfig::getInstance()->addDbConfig('139.129.32.89', 'test', 'test', 'test');


if(0) {
    $transaction = DB::transaction('test');
    $transaction->beginTransaction();
    DB::insert('test')
        ->table('test_table')
        ->one([
            'name' => '测试',
            'createTime' => 'now()',
        ])
        ->execute();

    $transaction->commit();

    $transaction->rollBack();
}
if(0){
    $dataList = DB::select('test', 'test_table')
        ->field('id', 'name')
//        ->field(['id', 'name']) // 数组或者多参数均可
        ->where([
            'id' => 3,
            'createTime' => ['>', date('Y-m-d H:i:s', time())]
        ])
        ->group("name")
        ->order('id desc')
//        ->order(['id' => 'desc']) // 也是数组字符串双类型参数
        ->limit(10)
//        ->limit(0, 10)
//        ->limit("0, 10")
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
if(0){
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
if(1){
    /** @var PDOStatement $sql */
    $sql = DB::sql('test')
        ->query('select * from test_table')
        ->execute();

    var_export($sql);
}


echo PHP_EOL."end".PHP_EOL;