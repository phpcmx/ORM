<?php
/**
 * OrmConfig.php
 *
 * 作者: CaoMengxin(505355059@qq.com)
 * 创建日期: 2017/7/26 下午6:31
 * 修改记录:
 *
 * $Id$
 */

namespace phpcmx\ORM\Tool\config;


use phpcmx\ORM\inc\traits\FinalSingleEngine;

class OrmConfig
{
    // 单例
    use FinalSingleEngine;


    /**
     * @var string 网站名称
     */
    public $webName = '不二ORM';


    /**
     * @var string 网站后缀
     */
    public $webTitleSufFix = null;


    /**
     * 初始化数据
     * OrmConfig constructor.
     */
    public function __construct()
    {
        $this->webTitleSufFix = ' -- '.$this->webName;
    }


    /**
     * 获取
     */
    public function getModelRuntime()
    {

    }
}