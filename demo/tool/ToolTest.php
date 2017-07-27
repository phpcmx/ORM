<?php
/**
 * ToolTest.php
 * 测试 实体关系 类生成工具
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/25 下午4:07
 * 修改记录:
 *
 * $Id$
 */

// 初始化
error_reporting(E_ALL);
ini_set('display_errors', 'On');


// 自动加载，不需要自己声明，composer已经集成
spl_autoload_register(function($className){
    $baseDir = __DIR__."/../../src/";
    $classPath = $baseDir.strtr($className, ["\\"=>"/","phpcmx\\ORM\\"=>'']).".php";

    include_once $classPath;
});


\phpcmx\ORM\Tool\OrmTool::action();