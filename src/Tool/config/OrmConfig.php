<?php
/**
 * OrmConfig.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/26 下午6:31
 * 修改记录:
 *
 * $Id$
 */

namespace phpcmx\ORM\Tool\config;


use phpcmx\ORM\DBConfig;
use phpcmx\ORM\exception\ExecuteWasFailed;
use phpcmx\ORM\inc\traits\FinalSingleEngine;

/**
 * Class OrmConfig
 *
 * @package phpcmx\ORM\Tool\config
 *
 * @property $webName             string 网站名称
 * @property $webTitleSufFix      string 网站名称后缀
 * @property $modelPath           string 数据库模型物理地址
 * @property $modelConfigFilePath string 数据库模型配置的物理地址
 * @property $modelNamespace      string 数据库模型配置的命名空间
 */
class OrmConfig
{
    // 单例
    use FinalSingleEngine;

    protected $config = [
        // 网站名称
        'webName'             => '不二ORM',
        // 网站名称后缀
        'webTitleSufFix'      => '',
        // modelPath
        'modelPath'           => '',
        // modelNamespace
        'modelNamespace'      => '',
        // model的配置文件地址
        'modelConfigFilePath' => '{phpcmx}/config/orm/ormTool_model.php',
    ];


    ////////////////////////////////////////////////////////////////////////////
    /// 基本方法
    ////////////////////////////////////////////////////////////////////////////


    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->config[$name])) {
            throw new \LogicException('未知配置：' . $name);
        }

        // 调用钩子
        $this->hookGet($name);

        return $this->config[$name];
    }


    /**
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        if (!isset($this->config[$name])) {
            throw new \LogicException('未知配置：' . $name);
        }

        // 调用设置钩子
        $this->hookSet($name, $value);

        return $this->config[$name] = $value;
    }


    /**
     * hookGet
     *
     * @param $name
     */
    private function hookGet($name)
    {
        if (method_exists($this, 'hookGet' . ucfirst($name))) {
            $this->{'hookGet' . ucfirst($name)}();
        }
    }


    /**
     * hookSet
     * @param $name
     * @param $value
     *
     * @author 曹梦欣 <caomengxin@zhibo.tv>
 */
    private function hookSet($name, $value)
    {
        if (method_exists($this, 'hookSet' . ucfirst($name))) {
            $this->{'hookSet' . ucfirst($name)}($value);
        }
    }


    ////////////////////////////////////////////////////////////////////////////
    /// hookGet
    ////////////////////////////////////////////////////////////////////////////

    /**
     * webTitleSufFix hook
     */
    private function hookGetWebTitleSufFix()
    {
        // 赋值前缀
        $this->webTitleSufFix = ' -- ' . $this->webName;
    }

    /**
     * modelPath hook
     */
    private function hookGetModelPath()
    {
        // 从缓存中获取
        $this->modelPath = $this->loadModelConfig('modelPath');
    }

    private function hookGetModelNamespace()
    {
        // 从缓存中获取
        $this->modelNamespace = $this->loadModelConfig('modelNamespace');
    }

    ////////////////////////////////////////////////////////////////////////////
    /// function
    ////////////////////////////////////////////////////////////////////////////

    /**
     * 从文件中获取模型配置
     *
     * @param string|null $name
     *
     * @return string|array
     */
    public function loadModelConfig($name = null)
    {
        static $modelConfig = null;
        if (is_null($modelConfig)) {
            // 加载文件配置
            $filePath = DBConfig::filePathReplace($this->modelConfigFilePath);
            if (!file_exists($filePath)) {
                // 没有找到配置缓存
                return false;
            }
        }

        if (!isset($modelConfig[$name])) {
            throw new \LogicException('没有model配置项为：' . $name);
        }

        return $modelConfig[$name];
    }


    /**
     * 生成新的配置缓存
     *
     * @param array $config
     */
    public function resetModelConfigCache(array $config)
    {
        $filePath = DBConfig::filePathReplace($this->modelConfigFilePath);
        $this->makeDir(dirname($filePath));
        if (!file_put_contents(
            $filePath,
            strtr(
                file_get_contents(
                    DBConfig::filePathReplace("{phpcmx}/orm/src/data/ormTool_model.data")
                ),
                $config
            )
        )
        ) {
            throw new \LogicException('生成文件权限不足：' . $filePath);
        }
    }


    /**
     * @param $path string 要生成的path，要带/ 或者带/文件.后缀。即最后一个不处理
     */
    private function makeDir($path)
    {
        if (!file_exists($path)) {
            $this->makeDir(dirname($path));

            mkdir($path, 0777);
        }
    }
}