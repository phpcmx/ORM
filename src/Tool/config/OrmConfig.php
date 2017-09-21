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


use phpcmx\ORM\inc\traits\FinalSingleEngine;

/**
 * Class OrmConfig
 *
 * @package phpcmx\ORM\Tool\config
 *
 * @property $webName string 网站名称
 * @property $webTitleSufFix string 网站名称后缀
 * @property $modelPath string 数据库模型物理地址
 * @property $modelConfigFilePath string 数据库模型配置的物理地址
 */
class OrmConfig
{
    use FinalSingleEngine;

    protected $config = [
        // 网站名称
        'webName' => '不二ORM',
        // 网站名称后缀
        'webTitleSufFix' => '',
        // modelPath
        'modelPath' => '',
        // model的配置文件地址
        'modelConfigFilePath' => '{phpcmx}/config/orm/modelConfig.php',
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
        if(!isset($this->config[$name])){
            throw new \LogicException('未知配置：'.$name);
        }

        // 调用钩子
        $this->hook($name);

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
        if(!isset($this->config[$name])){
            throw new \LogicException('未知配置：'.$name);
        }

        return $this->config[$name] = $value;
    }


    /**
     * hook
     * @param $name
     */
    public function hook($name)
    {
        if(method_exists($this, 'hook'.ucfirst($name))){
            $this->{'hook'.ucfirst($name)}();
        }
    }
    
    
    ////////////////////////////////////////////////////////////////////////////
    /// hook
    ////////////////////////////////////////////////////////////////////////////

    /**
     * webTitleSufFix hook
     */
    public function hookWebTitleSufFix()
    {
        // 赋值前缀
        $this->webTitleSufFix = ' -- '.$this->webName;
    }

    /**
     * modelPath hook
     */
    public function hookModelPath()
    {
        // 从缓存中获取
        $this->modelPath = $this->loadModelConfig('path');
    }

    ////////////////////////////////////////////////////////////////////////////
    /// function
    ////////////////////////////////////////////////////////////////////////////

    /**
     * 从文件中获取模型配置
     * @param string|null $name
     * @return string|array
     */
    public function loadModelConfig($name=null)
    {
        static $modelConfig = null;
        if(is_null($modelConfig)){
            // TODO 加载文件配置
            $filePath = $this->modelConfigFilePath;
        }

        if(!isset($modelConfig[$name])){
            throw new \LogicException('没有model配置：'.$name);
        }

        return $modelConfig[$name];
    }
}