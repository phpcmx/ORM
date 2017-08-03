<?php
/**
 * layout.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/26 下午6:17
 * 修改记录:
 *
 * $Id$
 */
use phpcmx\ORM\Tool\config\OrmConfig;
/** @var $html_path string */

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> 生成工具<?= OrmConfig::getInstance()->webTitleSufFix?></title>
    <!-- 包含头部信息用于适应不同设备 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 包含 bootstrap 样式表 -->
    <link rel="stylesheet" href="https://apps.bdimg.com/libs/bootstrap/3.2.0/css/bootstrap.min.css">
</head>

<body>
<div class="container">
    <?php include __DIR__."/nav.php"?>
    <?php include $html_path?>
</div>

<!-- JavaScript 放置在文档最后面可以使页面加载速度更快 -->
<!-- 可选: 包含 jQuery 库 -->
<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
<!-- 可选: 合并了 Bootstrap JavaScript 插件 -->
<script src="https://apps.bdimg.com/libs/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

</html>
