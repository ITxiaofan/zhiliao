<?php
namespace models;
use PDO;

class Base{
     // 连接数据库
     public static $pdo = null;
     public function __construct(){
         if(self::$pdo === null){
              // 连接数据库
            self::$pdo = new PDO('mysql:host=127.0.0.1;dbname=blog1','root','123456');
            // 设置编码
            self::$pdo->exec('SET NAMES utf8');
         }
        
     }
}