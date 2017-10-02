<?php
/**
 * Created by PhpStorm.
 *
 * @auth: 不二进制·Number
 * Date: 9/11/17
 * Time: 2:09 AM
 */

// 判断是否有设置模型路径和命名空间
// TODO 如果有设置model的命名空间和文件路径，就展示所有model。如果没有设置命名空间，就进入设置界面
// 命名空间和文件路径缓存在 phpcmx/config/ormTool_model.data 下
\phpcmx\ORM\Tool\OrmTool::config()->getModelRuntime();

?>
