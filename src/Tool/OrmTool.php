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
use phpcmx\ORM\Tool\ddl\DDLCreateTable;
use phpcmx\ORM\Tool\ddl\DDLParse;

/**
 * Class Tool
 * 生成工具
 *
 * @package phpcmx\ORM\Tool
 */
class OrmTool
{
    const VERSION = 'v1.0.0';
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
     */
    private static function modelListAction()
    {
        $dbAliasName = $_GET['n'] ?? null;
        // 默认参数
        $modelPath = self::config()->modelPath;
        $modelNamespace = self::config()->modelNamespace;
        if (empty($modelPath) or empty($modelNamespace)) {
            header("location:" . self::url('setModelConfig'));

            return;
        }

        if(isset($_POST['table']) and is_array($_POST['table'])){
            foreach ($_POST['table'] as $index => $table) {
                self::makeField($dbAliasName, $table);
            }

            header("location:" . self::url('modelList'));
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
                'defaultDir'       => self::config()->modelPath ?: DBConfig::filePathReplace(
                    '{phpcmx}' . DIRECTORY_SEPARATOR . 'orm '
                    . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR
                    . 'model'
                ),
                'defaultNamespace' => self::config()->modelNamespace ?: "phpcmx\\ORM\\model",
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

        // 生成model文件
        if(isset($_GET['verify'])){
            self::makeField($dbAliaName, $tableName);
            header('location:'.self::url('modelList', true)."&n={$dbAliaName}");
        }

        $modelFilePath = self::makeModelFilePath($dbAliaName, $tableName, $className);
        $columns = DB::query()
            ->sql($dbAliaName)
            ->query("show full columns from `{$tableName}`")
            ->execute();

        $ddl = DB::query()->sql($dbAliaName)->query('show create table '.$tableName)->execute();
        $ddl = $ddl[0]['Create Table'];


        self::assign([
            'dbAliaName' => $dbAliaName,
            // 连接信息
            'linkInfo' => DBConfig::getInstance()->getDbConfig($dbAliaName),
            'tableName' => $tableName,
            'modelFilePath' => $modelFilePath,
            'modelNamespace' => self::config()->modelNamespace,
            // 字段列表
            'columns' => $columns,
            'ddl' => $ddl,
        ]);
        self::display();
    }


    ////////////////////////////////////////////////////////////////////////////
    /// 通用方法
    ////////////////////////////////////////////////////////////////////////////


    /**
     * 生成文件
     * @param $dbAliaName
     * @param $tableName
     *
     */
    private static function makeField($dbAliaName, $tableName)
    {
        $modelFilePath = self::makeModelFilePath($dbAliaName, $tableName, $className);
        $columns = DB::query()
            ->sql($dbAliaName)
            ->query("show full columns from `{$tableName}`")
            ->execute();

        $ddl = DB::query()->sql($dbAliaName)->query('show create table '.$tableName)->execute();
        $ddl = $ddl[0]['Create Table'];

        $fieldLabel = [];
        foreach ($columns as $index => $column) {
            $fieldLabel[$column['Field']] = $column['Comment'];
        }

        $ddlParse = new DDLParse($ddl);
        $ddlParseClass = $ddlParse->getClass();
        if($ddlParseClass===false){
            throw new \RuntimeException('获取的ddl不符合规则要求:'.$ddl);
        }
        /** @var DDLCreateTable $createTable */
        $createTable = new $ddlParseClass();
        $createTable->load($ddl);

        $fieldMaxLen = max(array_map(function($v){return strlen($v);}, $createTable->fields));

        file_put_contents($modelFilePath, strtr(file_get_contents(DBConfig::filePathReplace("{phpcmx}/orm/src/data/model.tmp")), [
            '{date}' => date('Y-m-d'),
            '{time}' => date('H:i:s'),
            '{namespace}' => self::config()->modelNamespace,
            '{className}' => $className,
            '{dbAliaName}' => $dbAliaName,
            '{tableName}' => $tableName,
            '{DDL}' => $ddl,
            '{primary}' =>
                "[\r\n            ".
                implode(",\r\n            ", array_map(function($v){
                    return var_export($v, 1);
                }, $createTable->primary)).
                "\r\n        ]",
            '{fieldLabel}' =>
                "[\r\n".
                implode(
                    "",
                    array_map(
                        function($field, $label)use($fieldMaxLen){
                            return sprintf("            %-".($fieldMaxLen+2)."s => %s,\r\n", var_export($field, 1), var_export($label, 1));
                        },
                        array_keys($fieldLabel),
                        $fieldLabel
                    )
                ).
                "        ]",
            //                '{fieldLabel}' => strtr(var_export($fieldLabel, 1),['array ('=>'[', ')'=>'        ]', '  '=>'            ']),
            '{property}' => implode('
', array_map(function($v) use($fieldMaxLen){
                $vType = strtolower($v['type']);
                $type = in_array($vType, [
                    'tinyint', 'smallint', 'mediumint', 'int', 'bigint'
                ]) ? 'int' : (
                in_array($vType, [
                    'float', 'double'
                ]) ? 'float' : (
                in_array($vType, [
                    'year', 'time', 'date', 'datetime', 'timestamp',
                    'char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'enum', 'set',
                ]) ? 'string' : 'mixed'
                )
                );
                return sprintf(" * @property %-6s \$%-{$fieldMaxLen}s %s%s", $type, $v['field'], $v['type'], isset($v['len'])?"({$v['len']})":'');
            }, $createTable->fieldType)),
        ]));
    }


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
        $show = $info;
        try{
            if(is_null($info)){
                throw new \Exception('(null)');
            }elseif ($info === ''){
                throw new \Exception("(empty string)");
            }
        }catch (\Exception $e){
            $show = "<small class='text-muted'><em>{$e->getMessage()}</em></small>";
        }

        return $show;
    }

    public static function makeModelFilePath($dbAliaName, $tableName, &$className=null)
    {
        // 生成model文件的规则
        $modelPath = self::config()->modelPath;

        $className = strtr(ucwords(strtr($tableName, ['_'=>' '])), [' '=>'']);

        return $modelPath.DIRECTORY_SEPARATOR.$className.".php";
    }
}