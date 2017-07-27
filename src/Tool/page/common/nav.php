<?php
/**
 * nav.php
 *
 * 作者: 不二进制·Number
 * 创建日期: 2017/7/26 下午6:28
 * 修改记录:
 *
 * $Id$
 */
use phpcmx\ORM\Tool\config\OrmConfig;
use phpcmx\ORM\Tool\OrmTool;

$naves = [
        [
            'title' => '生成model',
            'url' => OrmTool::url('model'),
//            'subNav' => [
//                    [
//                        'title' => 'sdfsdf',
//                        'url' => '',
//                    ]
//            ]
        ]
];

?>

<div class="row clearfix">
    <div class="col-md-12 column">
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="?action=index"><?= OrmConfig::getInstance()->webName?></a>
        </div>
        <div>
            <ul class="nav navbar-nav">
<?php
foreach($naves as $i => $nav){
    if(isset($nav['subNav'])){
?>
                <li class="dropdown">
                    <a href="<?=$nav['url']??'javascript:void();'?>" class="dropdown-toggle" data-toggle="dropdown">
                        <?=$nav['title']?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
        <?php
        foreach($nav['subNav'] as $ii => $subNav){
        ?>
            <li><a href="<?=$subNav['url']??'javascript:void();'?>"><?=$subNav['title']?></a></li>
        <?php
        }
        ?>
                    </ul>
                </li>
    <?php
    }else{
    ?>
                <li><a href="<?=$nav['url']??'javascript:void();'?>"><?=$nav['title']?></a></li>
    <?php
    }
}
?>
            </ul>
        </div>
    </div>
</nav>
    </div>
</div>
