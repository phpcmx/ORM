<?php
/**
 * Created by PhpStorm.
 *
 * @auth: 不二进制·Number
 * Date: 9/11/17
 * Time: 2:09 AM
 */

use phpcmx\ORM\Tool\OrmTool;

/** @var $modelPath string */
/** @var $modelNamespace string */
/** @var $tableList array */
?>

<div class="row clearfix">
    <div class="col-md-12 column">
        <table class="table table-hover table-striped">
            <caption>数据库连接</caption>
            <thead>
            <tr>
                <th>#</th>
                <th>表名</th>
                <th>引擎</th>
                <th>版本</th>
                <th>行</th>
                <th>数据量
                    <small>字节</small>
                </th>
                <th>索引占用磁盘</th>
                <th>下一条自增记录</th>
                <th>创建时间</th>
                <th>更新时间</th>
                <th>字符集</th>
                <th>注释</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $index = 0;
            foreach ($tableList as $index => $item) {
                ?>
                <tr>
                    <td><?= ++$index ?></td>
                    <td><?= OrmTool::tableValue($item['Name']) ?></td>
                    <td><?= OrmTool::tableValue($item['Engine']) ?></td>
                    <td><?= OrmTool::tableValue($item['Version']) ?></td>
                    <td><?= OrmTool::tableValue($item['Rows']) ?></td>
                    <td><?= OrmTool::tableValue($item['Data_length']) ?></td>
                    <td><?= OrmTool::tableValue($item['Index_length']) ?></td>
                    <td><?= OrmTool::tableValue($item['Auto_increment']) ?></td>
                    <td><?= OrmTool::tableValue($item['Create_time']) ?></td>
                    <td><?= OrmTool::tableValue($item['Update_time']) ?></td>
                    <td><?= OrmTool::tableValue($item['Collation']) ?></td>
                    <td><?= OrmTool::tableValue($item['Comment']) ?></td>
                    <td><a href="?action=modelList&n=<?= $index ?>"
                           class="btn btn-success">管理模型</a></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>