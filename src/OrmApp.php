<?php
/**
 * OrmApp.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/25 下午4:11
 * 修改记录:
 *
 * $Id$
 */

namespace phpcmx\ORM;


use phpcmx\ORM\inc\traits\FinalSingleEngine;
use phpcmx\ORM\Tool\OrmTool;

/**
 * Class OrmApp
 * 整个orm系统的初始化app
 * @package phpcmx\ORM
 */
final class OrmApp
{
    use FinalSingleEngine;


    /**
     * 版本
     */
    const VERSION = 'v1.0.0';


    /**
     * 在入口文件添加数据库配置
     *
     * eg.
     * OrmApp::run1_addConfig([
     * 　　'dbAliaName' => [
     * 　　　　'host' => '127.0.0.1',
     * 　　　　'dbName' => 'userdb',
     * 　　　　'userName' => 'root',
     * 　　　　'password' => 'root',
     * 　　　　'charset' => 'utf8',
     * 　　],
     * ]);
     *
     * @param array $config
     */
    public function run1_addConfig(array $config){
        foreach ($config as $dbAliaName => $item) {
            DB::config()->addDbConfig(
                $dbAliaName,
                $item['host'],
                $item['dbName'],
                $item['userName'],
                $item['password'],
                $item['charset']??DBConfig::CHARSET_UTF8
            );
        }
    }


    /**
     * 在controller调用此方法，进入model自动生成后台
     */
    public function run2_modelMaker()
    {
        OrmTool::action();
    }


    /**
     * 没有第三步，嘿，只需要在控制器调用就可以了
     */
    public function run3_use()
    {
        // 使用说明将在后期进行慢慢完善~
    }


    /**
     * 开启debug模式，会有额外数据进行计算，会影响效率的啊
     * 但是作为额外的优势，能看到sql语句的执行详情
     */
    public function debug()
    {
        DB::config()->debug = true;
    }
}