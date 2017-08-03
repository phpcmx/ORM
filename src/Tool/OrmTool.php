<?php
/**
 * OrmTool.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/25 下午5:53
 * 修改记录:
 *
 * $Id$
 */

namespace phpcmx\ORM\Tool;

/**
 * Class Tool
 * 生成工具
 *
 * @package phpcmx\ORM\Tool
 */
class OrmTool
{
    static $action = 'index';

    /**
     * 调度类，根据请求，请求不同的页面展示
     */
    static function action(){
        // 获取action
        self::$action = $_GET['action'] ?? 'index';

        if(!method_exists(self::class, self::$action)){
            self::$action = 'error404';
        }

        self::{(self::$action)}();
    }

    /**
     * 生成url
     * @param $action
     * @return string
     */
    static function url($action){
        $_GET['action'] = $action;
        return '?'.http_build_query($_GET);
    }

    /**
     * 加载模板
     * @param string $name
     */
    static function display($name = null){
        $html_name = $name ?: self::$action;
        $html_path = __DIR__."/page/{$html_name}.php";
        if(file_exists($html_path)){
            include __DIR__."/page/common/layout.php";
        }
    }

    /**
     * 404页面
     */
    static function error404(){
        self::display();
    }

    /**
     * 主页
     */
    static function index(){
        self::display();
    }

    /**
     * 生成model的页面
     */
    static function model(){
        self::display();
    }
}