<?php
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
// $data = $stmt->fetchAll();
// var_dump($data);
// 插入数据
// for($i=0;$i<100;$i++){
//     $title = getChar(rand(20,50));
//     $content = getChar(rand(50,500));
//     $stmt = $pdo->exec("INSERT INTO blogs(id,title,content) VALUES(null,'$title','$content')");
// }
// 网上搜 "PHP随机生成汉字"
// function getChar($num)  // $num为生成汉字的数量
//     {
//         $b = '';
//         for ($i=0; $i<$num; $i++) {
//             // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
//             $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
//             // 转码
//             $b .= iconv('GB2312', 'UTF-8', $a);
//         }
//         return $b;
//     }
// 修改
// $pdo->exec("UPDATE blogs SET title='标题001',content='内容001' WHERE id = 1");

// 删除
// $pdo->exec('DELETE FROM blogs WHERE id = 1');
// 清空数据库
// $pdo->exec('TRUNCATE blogs');
