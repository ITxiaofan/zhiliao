<?php
namespace models;
class User extends Base {
   
    // function getName(){
    //     return '铎铎';
    // }
    public function add($email,$password){
        $stmt = self::$pdo->prepare("INSERT INTO users(email,password) VALUES(?,?)");
        return $stmt->execute([
                $email,
                $password,
        ]);
        // $error = $stmt->errorInfo();
        //     // echo '<pre>';
        //     var_dump($error);
    }
    
}