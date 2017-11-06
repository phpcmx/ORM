<?php
/**
 * Created by PhpStorm.
 *
 * @auth: fijdemon@qq.com
 * Date: 11/6/17
 * Time: 6:41 PM
 */

/** @var $defaultDir string */
/** @var $defaultNamespace string */


echo "请先确定model的保存路径(直接回车设置为 {$defaultDir}):";

$modelPath = trim(fgets(STDIN));

echo "再确定model的命名空间(直接回车设置为 {$defaultNamespace}):";

$modelNamespace = trim(fgets(STDIN));


\phpcmx\ORM\Tool\OrmTool::config()->modelPath = $modelPath?:$defaultDir;
\phpcmx\ORM\Tool\OrmTool::config()->modelNamespace = $modelNamespace?:$defaultNamespace;

\phpcmx\ORM\Tool\OrmTool::cliChangeAction('modelList');