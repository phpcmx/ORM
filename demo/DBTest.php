<?php
/**
 * 数据库基本操作：DB操作测试
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');


use phpcmx\ORM\DB;
use phpcmx\ORM\DBConfig;


// 自动加载，不需要自己声明，composer已经集成
spl_autoload_register(function($className){
    $baseDir = __DIR__."/../src/";
    $classPath = $baseDir.strtr($className, ["\\"=>"/","phpcmx\\ORM\\"=>'']).".php";

    include_once $classPath;
});





// 配置数据库
DBConfig::getInstance()->addDbConfig('139.129.32.89', 'test', 'test', 'test');


// insert + 事务
if(0) {
    $transaction = DB::transaction('test');
    $transaction->beginTransaction();
    // 插入返回的是 lastInsertId 最后插入数据id
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

// 查询
if(0){
    // 查询返回的是多条数据数组
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

// 删除
if(0){
    // 返回影响行数
    echo DB::delete('test', 'test_table')
        ->where([
            'id' => 2
        ])
        ->execute();
}

// 更新
if(0){
    // 返回影响行数
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

// 执行sql语句
if(0){
    // 根据执行语句的类型来决定返回的类型，说实在的，不是很建议使用，写出来也是为了尽可能保证完整性，满足所有的sql需求
    $sql = DB::sql('test')
        ->query('select * from test_table')
        ->execute();

    var_export($sql);
}


echo PHP_EOL."end".PHP_EOL;