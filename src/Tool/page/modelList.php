<?php
/**
 * Created by PhpStorm.
 *
 * @auth: 不二进制·Number
 * Date: 9/11/17
 * Time: 2:09 AM
 */

use phpcmx\ORM\Tool\OrmTool;

/** @var $dbName string */
/** @var $modelPath string */
/** @var $modelNamespace string */
/** @var $tableList array */
?>

<div class="row clearfix">
    <div class="col-md-12 column">
        <table class="table table-hover table-striped">
            <caption><h1>数据库表【<?= $dbName ?>】</h1></caption>
            <thead>
            <tr>
                <th>
                    <small class="text-muted"><em>Name</em></small>
                    <br>表名
                </th>
                <th>#</th>
                <th>
                    <small class="text-muted"><em>Engine</em></small>
                    <br>引擎
                </th>
                <th>
                    <small class="text-muted"><em>Version</em></small>
                    <br>版本
                </th>
                <th>
                    <small class="text-muted"><em>Rows</em></small>
                    <br>行
                </th>
                <th>
                    <small class="text-muted"><em>Data_length</em></small>
                    <br><small>数据量 字节</small>
                </th>
                <th>
                    <small class="text-muted"><em>Index_length</em></small>
                    <br><small>索引量 字节</small>
                </th>
                <th>
                    <small class="text-muted"><em>Auto_increment</em></small>
                    <br>自增量
                </th>
                <th>
                    <small class="text-muted"><em>Create_time</em></small>
                    <br>创建时间
                </th>
                <th>
                    <small class="text-muted"><em>Update_time</em></small>
                    <br>更新时间
                </th>
                <th>
                    <small class="text-muted"><em>Collation</em></small>
                    <br>字符集
                </th>
                <th>
                    <small class="text-muted"><em>Comment</em></small>
                    <br>注释
                </th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $index = 0;
            foreach ($tableList as $index => $item) {
                ?>
                <tr>
                    <td><h4 class="text-primary"><?= OrmTool::tableValue(
                                $item['Name']
                            ) ?></h4></td>
                    <td><?= ++$index ?></td>
                    <td><?= OrmTool::tableValue($item['Engine']) ?></td>
                    <td><?= OrmTool::tableValue($item['Version']) ?></td>
                    <td><?= OrmTool::tableValue($item['Rows']) ?></td>
                    <td><?= OrmTool::tableValue($item['Data_length']) ?><br>
                        <?php if($item['Data_length'] > 1024){$_t = $item['Data_length']/1024;if($_t<1024){echo $_t." KB";}else{$_t/=1024;echo $_t." MB";}} ?>
                    </td>
                    <td><?= OrmTool::tableValue($item['Index_length']) ?><br>
                        <?php if($item['Index_length'] > 1024){$_t = $item['Index_length']/1024;if($_t<1024){echo $_t." KB";}else{$_t/=1024;echo $_t." MB";}} ?>
                    </td>
                    <td><?= OrmTool::tableValue($item['Auto_increment']) ?></td>
                    <td><?= OrmTool::tableValue($item['Create_time']) ?></td>
                    <td><?= OrmTool::tableValue($item['Update_time']) ?></td>
                    <td><?= OrmTool::tableValue($item['Collation']) ?></td>
                    <td><?= OrmTool::tableValue($item['Comment']) ?></td>
                    <td>
                        <?php
                        switch ($item['fileStatus']) {
                            case 0:
                                echo "<label class='label label-warning'>已存在</label>";
                                break;
                            case 1:
                                echo "<label class='label label-success'>可生成</label>";
                                break;
                            default:
                                echo $item['fileStatus'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        switch ($item['fileStatus']){
                            case 0:
                                echo "<a class='btn btn-warning' href='".OrmTool::url('makeModelFile')."&t={$item['Name']}'>覆盖生成</a>";
                                break;
                            case 1:
                                echo "<a class='btn btn-success' href='".OrmTool::url('makeModelFile')."&t={$item['Name']}'>生成</a>";
                                break;
                            default:
                                echo "";
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>