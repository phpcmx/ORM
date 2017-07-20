<?php
spl_autoload_register(function($className){
    $baseDir = __DIR__."/../src/";
    $classPath = $baseDir.strtr($className, ["phpcmx\\mysql\\"=>'']).".php";

    include_once $classPath;
});



