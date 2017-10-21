<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/10/21
 * Time: 18:05
 */
use phpcmx\ORM\Tool\OrmTool;

?>
<div class="row">
    <div class="bottom col-md-12 text-center" style="padding-bottom: 1.5em;">
        <hr>
        <h3><?= OrmTool::config()->webTitleSufFix?></h3>
        <h4>简化学习，给你更多的时间</h4>
        <i>phpcmx ORM version: <?=\phpcmx\ORM\OrmApp::VERSION?></i><br>
        <i>ormTool version: <?=ormTool::VERSION?></i><br>
    </div>
</div>
