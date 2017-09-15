<?php
/**
 * model.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/27 上午11:09
 * 修改记录:
 *
 * $Id$
 */
?>

<div class="row clearfix">
    <div class="col-md-12 column">
        <table class="table table-hover table-striped">
            <caption>数据库连接</caption>
            <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    别名
                </th>
                <th>
                    数据库类型
                </th>
                <th>
                    服务器
                </th>
                <th>
                    数据库名
                </th>
                <th>
                    用户
                </th>
                <th>
                    密码
                </th>
                <th>
                    默认字符
                </th>
                <th>
                    操作
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            $index = 0;
            foreach (\phpcmx\ORM\DB::config()->getAllDbConfig() as $dbAliaName => $config){
                ?>
                <tr>
                    <td><?=++$index?></td>
                    <td><?=$dbAliaName?></td>
                    <td><?=$config['type']?></td>
                    <td><?=$config['host']?></td>
                    <td><?=$config['dbName']?></td>
                    <td><?=$config['userName']?></td>
                    <td><?=$config['password']?></td>
                    <td><?=$config['charset']?></td>
                    <td><a href="?action=modelList" class="btn btn-success">管理模型</a> </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>