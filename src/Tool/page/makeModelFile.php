<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/10
 * Time: 15:25
 */

/** @var $dbAliaName string */
/** @var $tableName string */
/** @var $linkInfo array */
/** @var $modelFilePath string */
/** @var $modelNamespace string*/
?>

<div class="row clearfix">
    <div class="col-md-12 column">
        <h2>生成确认</h2>
        <hr>
        <div class="col-md-12 column">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="col-xs-3 text-right">连接别名</td>
                    <td><?=$dbAliaName?></td>
                </tr>
                <tr>
                    <td class="text-right">连接信息</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-right">表名</td>
                    <td><?=$tableName?></td>
                </tr>
                <tr>
                    <td class="text-right">文件位置</td>
                    <td><?=$modelFilePath?></td>
                </tr>
                <tr>
                    <td class="text-right">命名空间</td>
                    <td><?=$modelNamespace?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
