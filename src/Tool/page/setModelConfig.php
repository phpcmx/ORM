<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Date: 2017/9/22
 * Time: 11:03
 */

/** @var $defaultDir string */
/** @var $defaultNamespace string */
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
                            <input name="dir" class="form-control" id="inputModelPath" value="<?=$defaultDir?>" />
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
                    <input name="namespace" class="form-control" id="inputModelNamespace" value="<?=$defaultNamespace?>" />
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
    // 对象定义
    var inputModelPath = $('#inputModelPath');
    var showModelPath = $('#showModelPath');
    var dirMenuList = $('.dirMenuList');
    var t_keyUpPost = null;

    // 目录选择
    inputModelPath.keyup(function(){
        var dir = $(this).val();
        getDir(dir, backMore);
    });

    // 选择二层
    $(document).on('click', '.dirMenuList', function(e){
        var dir = $(this).data('dir');
//        console.log(dir);
        inputModelPath.val(dir);
        getDir(dir, backMore, 0);
    });

    // 面包屑更新
    $(document).on('click', '.breadList', function(e){
        var id = $(this).attr('id');
        var dir = $(this).data('dir');
//        console.log(id, dir);
        getDir(dir, function(d){
            fillMenuBreak(id, d.data);
        }, 0);
    });


    function getDir(dir, callback, timeout){
        if(typeof timeout === 'undefined'){
            timeout = 1000;
        }

        clearTimeout(t_keyUpPost);
        t_keyUpPost = setTimeout(function(){
            $.post('<?=\phpcmx\ORM\Tool\OrmTool::url('ajaxDir')?>', {
                'dir' : dir
            },function(d){
                if(typeof d === 'string'){
                    d = eval("("+d+")");
                }
                if(!checkReturn(d)){
                    errorShow(d.message);
                }

                callback(d);
            });
        }, timeout);
    }

    function backMore(d){
        showModelPathInit(d.data);
    }

    function checkReturn(d){
        if(d.status!==0){
            return false;
        }
    }

    function errorShow(message){

    }

    function showModelPathInit(d){
        var html = "";
        var breadStr = '';
        for(var i=0; i<d.info.length; i++){
            var showBreadStr = '';
            if(i===1){
                breadStr += d.info[i-1];
                if(breadStr === ''){
                    showBreadStr = '/';
                }
            }else if(i!==0){
                breadStr += d.separator+d.info[i-1];
                showBreadStr = breadStr;
            }
            var id = "bread"+i;
            html += "<li class='dropdown'><a id='"+id+"' class='breadList dropdown-toggle' data-toggle='dropdown' data-dir='"+showBreadStr+"' href='javascript:void(0);'>"+d.info[i]+"</a><ul id='drow-"+id+"' class='dropdown-menu' role='menu' aria-labelledby='"+id+"'></ul></li>";
        }

        var list = "";
        for(i=0; i<d.list.length; i++){
            list +=
                "        <li role=\"presentation\">\n" +
                "            <a class='dirMenuList' role=\"menuitem\" data-dir='"+d.dir+d.separator+d.list[i]+"' tabindex=\"-1\" href=\"javascript:void(0);\">"+d.list[i]+"</a>\n" +
                "        </li>";
        }

        html += "<li class=\"dropdown\">\n" +
            "    <button type=\"button\" class=\"btn dropdown-toggle\" id=\"dropdownMenu1\" data-toggle=\"dropdown\">选择更多\n" +
            "        <span class=\"caret\"></span>\n" +
            "    </button>\n" +
            "    <ul class=\"dropdown-menu\" role=\"menu\" aria-labelledby=\"dropdownMenu1\">\n" +
            list +
            "    </ul>\n" +
            "</li>";

        showModelPath.html(html);
    }

    function fillMenuBreak(id, d){
        var list = "";
        console.log(d);
        for(var i=0; i<d.list.length; i++){
            list +=
                "        <li role=\"presentation\">\n" +
                "            <a class='dirMenuList' role=\"menuitem\" data-dir='"+d.dir+d.separator+d.list[i]+"' tabindex=\"-1\" href=\"javascript:void(0);\">"+d.list[i]+"</a>\n" +
                "        </li>";
        }

        $("#drow-"+id).html(list);
    }

    // 初始化执行
    inputModelPath.keyup();
</script>
