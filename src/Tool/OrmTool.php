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
use phpcmx\ORM\DBConfig;
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


    ////////////////////////////////////////////////////////////////////////////
    /// 模板方法
    ////////////////////////////////////////////////////////////////////////////

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

        // 注册error handler
        self::registerErrorHandler();

        try {
            self::{(self::$action) . "Action"}();
        } catch (\Exception $e) {
            var_dump($e);
            die();
        }
    }

    /**
     * 生成url
     *
     * @param      $action
     *
     * @param bool $absolute 是否是绝对连接（非绝对连接会有当前页面的get值）
     *
     * @return string
     */
    static function url($action, bool $absolute = false)
    {
        if ($absolute) {
            $get = [];
        } else {
            $get = $_GET;
        }
        $get['action'] = $action;

        return '?' . http_build_query($get);
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
        }

        return $params;
    }

    ////////////////////////////////////////////////////////////////////////////
    /// 静态的页面方法
    ////////////////////////////////////////////////////////////////////////////

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
        self::display('error404');
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
        $dbAliasName = $_GET['n'] ?? null;
        // 默认参数
        $modelPath = self::config()->modelPath;
        $modelNamespace = self::config()->modelNamespace;
        if ($modelPath === false or $modelNamespace === false) {
            header("location:" . self::url('setModelConfig'));

            return;
        }

        // 找到所有的表列表
        $sqlResult = DB::query()
            ->sql($dbAliasName)
            ->query('show table status')
            ->execute();

        // 添加是否已经存在文件的状态
        foreach ($sqlResult as $index => $item) {
            if(file_exists(self::makeModelFilePath($dbAliasName, $item['Name']))){
                $sqlResult[$index]['fileStatus'] = 0;
            }else{
                $sqlResult[$index]['fileStatus'] = 1;
            }
        }

        self::assign(
            [
                'title' => 'model生成页面',
                'dbName' => DB::config()->getDbConfig($dbAliasName)['dbName'],
                'modelPath' => $modelPath,
                'modelNamespace' => $modelNamespace,
                'tableList' => $sqlResult
            ]
        );
        self::display();
    }

    /**
     * 设置模型的配置
     */
    private static function setModelConfigAction()
    {
        if ($_POST) {
            $dir = $_POST['dir'];
            $namespace = $_POST['namespace'];

            self::config()->modelPath = $dir;
            self::config()->modelNamespace = $namespace;

            // 设置文件缓存 并跳回正常流程
            header("location:" . self::url('modelList'));
            return;
        }

        self::assign(
            [
                'title'            => 'model默认配置',
                'defaultDir'       => DBConfig::filePathReplace(
                    '{phpcmx}' . DIRECTORY_SEPARATOR . 'orm'
                    . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR
                    . 'model'
                ),
                'defaultNamespace' => 'phpcmx\\orm\\model',
            ]
        );
        self::display();
    }


    /**
     * 系统目录结构
     */
    private static function ajaxDirAction()
    {
        // 参数
        $defaultDir = mb_convert_encoding(
                rtrim($_POST['dir'], DIRECTORY_SEPARATOR), 'gbk'
            ) ?? '';
        $defaultDir = strtr(
            $defaultDir, [
                '\\' => DIRECTORY_SEPARATOR,
                '/'  => DIRECTORY_SEPARATOR
            ]
        );
        // 判断linux下的一种特殊情况
        if ($_POST['dir'] == '/' and is_dir('/')) {
            $defaultDir = '/';
        } // 如果不是目录就返回异常
        else {
            if (!is_dir($defaultDir)) {
                $defaultDir = DBConfig::filePathReplace(
                    '{phpcmx}' . DIRECTORY_SEPARATOR . 'orm'
                    . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR
                    . 'model'
                );
                self::ajaxError(
                    'dir is not exist', [
                        'dir'        => $_POST['dir'],
                        'defaultDir' => $defaultDir,
                    ]
                );
            }
        }

        // dir对象
        $dir = dir($defaultDir);

        // 目录列表
        $list = [];
        while ($dirName = $dir->read()) {
            if (in_array($dirName, ['.', '..']) or !is_dir(
                    $dir->path . DIRECTORY_SEPARATOR . $dirName
                )
            ) {
                continue;
            }
            $list[] = mb_convert_encoding($dirName, 'utf-8', 'gbk');
        }

        // 获取文件夹信息
        $path = mb_convert_encoding($dir->path, 'utf-8', 'gbk');
        $info = rtrim($path, DIRECTORY_SEPARATOR);
        $info = explode(DIRECTORY_SEPARATOR, $info);


        // 返回信息
        $return = [
            'separator' => DIRECTORY_SEPARATOR,
            'dir'       => $path,
            'info'      => $info,
            'list'      => $list,
        ];

//        var_export($return);
        self::ajaxSuccess($return);
    }


    /**
     * 生成对应类
     */
    private static function makeModelFileAction()
    {
        $dbAliaName = $_GET['n'] ?? null;
        $tableName = $_GET['t'] ?? null;
        if(empty($dbAliaName) or empty($tableName)){
            self::error404Action();
            return ;
        }

        $modelFilePath = self::makeModelFilePath($dbAliaName, $tableName);

        self::assign([
            'dbAliaName' => $dbAliaName,
            'linkInfo' => DBConfig::getInstance()->getDbConfig($dbAliaName),
            'tableName' => $tableName,
            'modelFilePath' => $modelFilePath,
            'modelNamespace' => self::config()->modelNamespace,
        ]);
        self::display();
    }


    ////////////////////////////////////////////////////////////////////////////
    /// 通用方法
    ////////////////////////////////////////////////////////////////////////////

    private static function ajaxReturn($array)
    {
        header('content-type:text/json');
        echo json_encode($array);
    }

    private static function ajaxError($type, $data)
    {
        static $typeList = [
            'dir is not exist' => -1,
        ];

        if (!isset($typeList[$type])) {
            throw new \LogicException('未知的错误类型：' . $type);
        }

        $status = $typeList[$type];
        $message = $type;

        self::ajaxReturn(
            [
                'status'  => $status,
                'message' => $message,
                'data'    => $data,
            ]
        );
    }

    private static function ajaxSuccess($data)
    {
        self::ajaxReturn(
            [
                'status'  => 0,
                'message' => 'ok',
                'data'    => $data,
            ]
        );
    }


    private static function registerErrorHandler()
    {
        set_error_handler(
            function ($ErrNo, $ErrMsg, $File, $Line, $Vars) {
                $ErrorType = array(1    => "Error", 2 => "Warning",
                                   4    => "Parsing Error",
                                   8    => "Notice", 16 => "Core Error",
                                   32   => "Core Warning",
                                   64   => "Complice Error",
                                   128  => "Compile Warning",
                                   256  => "User Error",
                                   512  => "User Warning",
                                   1024 => "User Notice",
                                   2048 => "Strict Notice");
                $Time = date('Y-m-d H:i:s');
                $Err
                    = <<<ERROR_MESSAGE
        <errorentry>  
            <time>$Time</time>  
            <number>$ErrNo</number>  
            <type>$ErrorType[$ErrNo]</type>  
            <errmsg><b>$ErrMsg</b></errmsg>  
            <filename>$File</filename>  
            <linenum>$Line</linenum>
        </errorentry>
ERROR_MESSAGE;
                OrmTool::assign(
                    [
                        'err'  => $Err,
                        'vars' => $Vars,
                    ]
                );
                OrmTool::display('error');
//            die();
            }
        );
    }

    public static function tableValue($info)
    {
        return $info ?? "<small class='text-muted'><em>(null)</em></small>";
    }

    public static function makeModelFilePath($dbAliaName, $tableName)
    {
        // 生成model文件的规则
        $modelPath = self::config()->modelPath;

        $className = strtr(ucwords(strtr($tableName, ['_'=>' '])), [' '=>'']);

        return $modelPath.DIRECTORY_SEPARATOR.$className.".php";
    }
}