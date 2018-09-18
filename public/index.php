<?php
// 当前文件的绝对路径
define('ROOT',dirname(__FILE__).'/../');

function autoload($class){
   $path = str_replace('\\','/',$class);
    require(ROOT.$path.'.php');
}
spl_autoload_register('autoload');

if(php_sapi_name() == 'cli'){
    $controller = ucfirst($argv[1]) . 'Controller';
    $action = $argv[2];
}else{
    // 添加路由
    // 获取url上的路径
    if(isset($_SERVER['PATH_INFO'])){

        $pathInfo = $_SERVER['PATH_INFO'];
        // 根据/转成数组
        $pathInfo = explode('/',$pathInfo);

        // echo "<pre>";
        // var_dump($_SERVER);
        // 得到控制器和方法名
        $controller = ucfirst($pathInfo[1]).'Controller';
        $action = $pathInfo[2];

    }else{

        // 默认控制器
        $controller = 'IndexController';
        $action = 'index';

    } 
}


// 未控制器添加命名空间
$fullController = 'controllers\\'.$controller;

$_C = new $fullController;
$_C->$action();

function view($viewFileName,$data = []){
    // 解压数组成变量
    extract($data);
    $path = str_replace('.','/',$viewFileName).'.html';
    // 加载视图
    require(ROOT.'views/'.$path);
}

function getUrlParams($except = []){

    foreach($except as  $v){

        unset($_GET[$v]);
    }
    $str = '';
    foreach($_GET as $k => $v){
        $str .= "$k=$v&";
    }
    return $str;
}