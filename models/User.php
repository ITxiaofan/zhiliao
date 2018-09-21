<?php
namespace models;
use PDO;
class User {
    // 连接数据库
    public $pdo;
    public function __construct(){
        // 连接数据库
       $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=blog1','root','123456');
        // 设置编码
       $this->pdo->exec('SET NAMES utf8');
    }
    // function getName(){
    //     return '铎铎';
    // }
    public function add($email,$password){
        $stmt = $this->pdo->prepare("INSERT INTO users(email,password) VALUES(?,?)");
        return $stmt->execute([
                $email,
                $password,
        ]);
        // $error = $stmt->errorInfo();
        //     // echo '<pre>';
        //     var_dump($error);
    }
    
}