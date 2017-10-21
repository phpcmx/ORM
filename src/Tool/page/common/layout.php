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
/** @var $html_name string */
/** @var $title string */

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> <?=$title??$html_name  ?><?= OrmConfig::getInstance()->webTitleSufFix?></title>
    <!-- 包含头部信息用于适应不同设备 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 包含 bootstrap 样式表 -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- 可选: 包含 jQuery 库 -->
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<!--    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->

    <style>
        @media (min-width: 992px) {
            .selfContainer{
                margin-top:72px;
                min-height:500px;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__."/nav.php"?>
<div class="container selfContainer">
    <?php include $html_path?>
</div>
    <?php include __DIR__."/bottom.php"?>

<!-- JavaScript 放置在文档最后面可以使页面加载速度更快 -->
<!-- 可选: 合并了 Bootstrap JavaScript 插件 -->
<!--<script src="https://cdn.bootcss.com/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>-->
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
