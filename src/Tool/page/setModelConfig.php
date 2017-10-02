<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/9/22
 * Time: 11:03
 */

/** @var $defaultDir string */
?>

<!--<link rel="stylesheet" href="../../../../../../public/bootstrap.min.css">-->

<div class="row clearfix">
    <div class="col-md-12 column">
        <form class="form-horizontal" method="post">
            <div class="form-group">
                <label for="inputModelPath" class="col-sm-2 control-label">实体存放目录</label>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <input class="form-control" id="inputModelPath" value="<?=$defaultDir?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <ul id="showModelPath" class="breadcrumb col-sm-8 col-sm-offset-2">
<!--                    <li>-->
<!--                        <a href="#">Home</a>-->
<!--                    </li>-->
                </ul>
            </div>
            <div class="form-group">
                <label for="inputModelNamespace" class="col-sm-2 control-label">命名空间</label>
                <div class="col-sm-8">
                    <input class="form-control" id="inputModelNamespace" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-default" value="保存">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // 目录选择
    $('#inputModelPath').keyup(function(){
        var dir = $(this).val();
        $.post('<?=\phpcmx\ORM\Tool\OrmTool::url('ajaxDir')?>', {
            'dir' : dir
        }, function(d){

        });
    });


</script>
