<?php
namespace controllers;

use PDO;

class MockController
{
    // public function users()
    // {
    //     // 20个账号
    //     $pdo = new PDO('mysql:host=127.0.0.1;dbname=blog', 'root', '123456');
    //     $pdo->exec('SET NAMES utf8');

    //     // 清空表，并且重置 ID
    //     $pdo->exec('TRUNCATE users');

    //     for($i=0;$i<20;$i++)
    //     {
    //         $email = rand(50000,99999999999).'@126.com';
    //         $password = md5('123123');
    //         $pdo->exec("INSERT INTO users (email,password) VALUES('$email','$password')");
    //     }
    // }
    // public function blog()
    // {
    //     $pdo = new PDO('mysql:host=127.0.0.1;dbname=blog1', 'root', '123456');
    //     $pdo->exec('SET NAMES utf8');

    //     // 清空表，并且重置 ID
    //     $pdo->exec('TRUNCATE blogs');

    //     for($i=0;$i<300;$i++)
    //     {
    //         $title = $this->getChar( rand(20,100) ) ;
    //         $content = $this->getChar( rand(100,600) );
    //         $display = rand(10,500);
    //         $is_show = rand(0,1);
    //         $date = rand(1233333399,1535592288);
    //         $date = date('Y-m-d H:i:s', $date);
    //         $user_id = rand(1,20);
    //         $pdo->exec("INSERT INTO blogs (title,content,display,is_show,created_at,user_id) VALUES('$title','$content',$display,$is_show,'$date',$user_id)");
    //     }
    // }

    // private function getChar($num)  // $num为生成汉字的数量
    // {
    //     $b = '';
    //     for ($i=0; $i<$num; $i++) 
    //     {
    //         // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
    //         $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
    //         // 转码
    //         $b .= iconv('GB2312', 'UTF-8', $a);
    //     }
    //     return $b;
    // }
public function blog(){
    $host = '127.0.0.1';
    $dbname = 'blog1';
    $user = 'root';
    $pass = '123456';
    // 连接数据库
    $pdo = new PDO("mysql:host={$host};dbname={$dbname}",$user,$pass);
    // 设置编码
    $pdo->exec('set names utf8');
    // 取出10条数据
    $stmt = $pdo->query("SELECT * FROM blogs LIMIT 10");
    $stmt = $pdo->prepare('INSERT INTO blogs(title,content) VALUES(?,?)');
    $ret = $stmt->execute([
        '标题1234567',
        '内容2343'
    ]);
    if($ret){
        echo "添加成功新纪录的ID".$pdo->lastInsertId();
    }else{
        $error = $stmt->errorInfo();
        var_dump($error);
    }
    $data = $stmt->fetchAll();
    // var_dump($data);
    // 插入数据
    for($i=0;$i<100;$i++){
            $title = $this->getChar( rand(20,50) ) ;
            $content = $this->getChar( rand(100,600) );
            $display = rand(10,500);
            $is_show = rand(0,1);
            $date = rand(1233333399,1535592288);
            $date = date('Y-m-d H:i:s', $date);
    //     $title = getChar(rand(20,50));
    //     $content = getChar(rand(50,500));
        $stmt = $pdo->exec("INSERT INTO blogs(id,title,content,display,is_show) VALUES(null,'$title','$content','$display','$is_show')");
    }
}

// 网上搜 "PHP随机生成汉字"
function getChar($num)  // $num为生成汉字的数量
    {
        $b = '';
        for ($i=0; $i<$num; $i++) {
            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            // 转码
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }
// 修改
// $pdo->exec("UPDATE blogs SET title='标题001',content='内容001' WHERE id = 1");

// 删除
// $pdo->exec('DELETE FROM blogs WHERE id = 1');
// 清空数据库
// $pdo->exec('TRUNCATE blogs');

}