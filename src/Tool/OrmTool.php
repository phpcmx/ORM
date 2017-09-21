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

use phpcmx\ORM\DB;
use phpcmx\ORM\Tool\config\OrmConfig;

/**
 * Class Tool
 * 生成工具
 *
 * @package phpcmx\ORM\Tool
 */
class OrmTool
{
    private static $action = 'index';

    /**
     * 调度类，根据请求，请求不同的页面展示
     */
    static function action()
    {
        // 获取action
        self::$action = $_GET['action'] ?? 'index';

        if (!method_exists(self::class, self::$action . 'Action')) {
            self::$action = 'error404';
        }

        self::{(self::$action) . "Action"}();
    }

    /**
     * 生成url
     *
     * @param $action
     *
     * @return string
     */
    static function url($action)
    {
        $_GET['action'] = $action;

        return '?' . http_build_query($_GET);
    }


    /**
     * 获取配置
     *
     * @return OrmConfig
     */
    public static function config()
    {
        return OrmConfig::getInstance();
    }

    /**
     * 加载模板
     *
     * @param string $name
     *
     * @throws \Exception
     */
    private static function display($name = null)
    {
        $html_name = $name ?: self::$action;
        $html_path = __DIR__ . "/page/{$html_name}.php";
        if (!file_exists($html_path)) {
            throw new \Exception('未找到模板页面:' . $html_path);
        }

        $params = self::assign();
        extract($params);

        include __DIR__ . "/page/common/layout.php";
    }


    /**
     * 设置变量
     *
     * @param array $userParams
     *
     * @return array
     * @author 曹梦欣 <caomengxin@zhibo.tv>
     */
    private static function assign($userParams = [])
    {
        static $params = [];
        if (!empty($userParams)) {
            $params = array_merge($params, $userParams);
        } else {
            return $params;
        }
    }

    /**
     * 404页面
     */
    private static function error404Action()
    {
        self::assign(
            [
                'title' => '404',
            ]
        );
        self::display();
    }

    /**
     * 主页
     */
    private static function indexAction()
    {
        self::assign(
            [
                'title' => '生成工具',
            ]
        );
        self::display();
    }

    /**
     * 生成model的页面
     */
    private static function modelAction()
    {
        self::assign(
            [
                'title'       => '数据库配置列表',
                'allDbConfig' => DB::config()->getAllDbConfig(),
            ]
        );
        self::display();
    }

    /**
     * model配置页面
     *
     * @author 曹梦欣 <caomengxin@zhibo.tv>
     */
    private static function modelListAction()
    {
        $dbAliasName = $_GET['n']??null;
        $dbConfig    = DB::config()->getDbConfig($dbAliasName);
        // 默认参数
        $modelPath      = self::config()->modelPath;
        $modelNamespace = self::config()->modelNamespace;
        if ($modelPath === false or $modelNamespace === false) {
            header("location:" . self::url('setModelConfig'));

            return;
        }

        self::assign(
            [
                'title' => 'model生成页面',
            ]
        );
        self::display();
    }

    /**
     * 设置模型的配置
     */
    private static function setModelConfigAction()
    {
        self::assign(
            [
                'title' => 'model默认配置',
            ]
        );
        self::display();
    }
}