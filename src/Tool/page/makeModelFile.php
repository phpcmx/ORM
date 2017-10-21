<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/10
 * Time: 15:25
 */

use phpcmx\ORM\Tool\OrmTool;

/** @var $dbAliaName string */
/** @var $tableName string */
/** @var $linkInfo array */
/** @var $modelFilePath string */
/** @var $modelNamespace string */
/** @var $columns array */
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
                    <td><?= $dbAliaName ?></td>
                </tr>
                <tr>
                    <td class="text-right">连接信息</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-right">表名</td>
                    <td><?= $tableName ?></td>
                </tr>
                <tr>
                    <td class="text-right">文件位置</td>
                    <td><?= $modelFilePath ?></td>
                </tr>
                <tr>
                    <td class="text-right">命名空间</td>
                    <td><?= $modelNamespace ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 column">
        <div class="col-md-12 column">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        <small class="text-muted"><em>Field</em></small>
                        <br>字段
                    </th>
                    <th>
                        <small class="text-muted"><em>Type</em></small>
                        <br>类型
                    </th>
                    <th>
                        <small class="text-muted"><em>Collation</em></small>
                        <br>编码
                    </th>
                    <th>
                        <small class="text-muted"><em>Null</em></small>
                        <br>可为空
                    </th>
                    <th>
                        <small class="text-muted"><em>Key</em></small>
                        <br>索引
                    </th>
                    <th>
                        <small class="text-muted"><em>Default</em></small>
                        <br>默认
                    </th>
                    <th>
                        <small class="text-muted"><em>Extra</em></small>
                        <br>额外
                    </th>
                    <th>
                        <small class="text-muted"><em>Comment</em></small>
                        <br>备注
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($columns as $index => $column) { ?>
                    <tr>
                        <td><?= OrmTool::tableValue($column['Field']) ?></td>
                        <td><?= OrmTool::tableValue($column['Type']) ?></td>
                        <td><?= OrmTool::tableValue(
                                $column['Collation']
                            ) ?></td>
                        <td><?= OrmTool::tableValue($column['Null']) ?></td>
                        <td><?= OrmTool::tableValue($column['Key']) ?></td>
                        <td><?= OrmTool::tableValue($column['Default']) ?></td>
                        <td><b><?= OrmTool::tableValue($column['Extra']) ?></b></td>
                        <td><?= OrmTool::tableValue($column['Comment']) ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 column">
            <blockquote class="text-warning">
                <b>
                <span class="glyphicon glyphicon-info-sign"></span> 如果数据并不符合您的要求，建议您修改数据库，尽量保持自动生成的代码保持原样，且不提交到版本库内
                </b>
            </blockquote>

            <button class="btn btn-success">确认</button>
        </div>
    </div>
</div>
