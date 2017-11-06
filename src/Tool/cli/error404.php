<?php
/**
 * Created by PhpStorm.
 *
 * @auth: fijdemon@qq.com
 * Date: 11/3/17
 * Time: 11:51 PM
 */


echo <<<HELP

未识别命令。
命令格式为：
 
    php [执行的文件] [命令]
    
如： php {$fileName} model

可支持的[命令]为：

\33[1m[model]\033[0m                           
    生成模型引导
\33[1m[makeModel 参数:配置别名 参数:表名]\033[0m  
    生成单个表模型
\33[1m[makeAllModel 参数:配置别名]\033[0m        
    生成配置下的所有表


HELP;

die;
