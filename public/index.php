<?php
// 当前文件的绝对路径
define('ROOT',dirname(__FILE__).'/../');

function autoload($class){
   $path = str_replace('\\','/',$class);
    require(ROOT.$path.'.php');
}
spl_autoload_register('autoload');

$userController = new controllers\UserController;
$userController->hello();
function view ($viewFileName,$data = []){
    
}