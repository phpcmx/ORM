<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/9/22
 * Time: 11:03
 */
?>

<!--<link rel="stylesheet" href="../../../../../../public/bootstrap.min.css">-->

<div class="row clearfix">
    <div class="col-md-12 column">
        <form class="form-horizontal" method="post">
            <div class="form-group">
                <label for="inputModelPath" class="col-sm-2 control-label">实体存放目录</label>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-10">
                            <input class="form-control" id="inputModelPath" />
                        </div>
                        <div class="col-sm-2 text-right">
                            <input id="showDir" type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#myModal" value="目录">
                        </div>
                    </div>
                </div>
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

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    路径选择
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-1 col-xs-offset-1">
                        <label for="modal-path">path:</label>
                    </div>
                    <div class="col-xs-9">
                        <input id="modal-path" class="form-control" value="{path}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="media-list">
                            <div class="media">
                                <div class="media-body">phpcmx</div>
                                <span class="pull-right caret"></span>
                            </div>
                            <div class="media">
                                <div class="media-body">phpcmx</div>
                                <span class="pull-right caret"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary">
                    提交更改
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<script>
    (function(){
        var showDir = $('#showDir');
        var myModal = $('#myModal');
        var myModal_modalBody = myModal.find('.modal-body');

        showDir.click(function(e){
//            myModal_modalBody.html('正在加载...');
            // ajax加载更多
            $.post(
                '<?=\phpcmx\ORM\Tool\OrmTool::url('ajaxDir')?>',
                {
                    'default' : showDir.val()
                },
                function(d){
                    if(d.status===200){
                        initFill(d.data);
                    }else{
                        alert(d.message);
                    }
                }
            );
        });
    })();

    var tmp = {
        'init' : ""
    };

    /**
     * 基础样式
     * @param dirInfo
     */
    function initFill(dirInfo){
        var path = dirInfo.path;
        var list = dirInfo.list;


    }
</script>