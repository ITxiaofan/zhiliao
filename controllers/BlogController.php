<?php
namespace controllers;
use PDO;
class BlogController{
    public function index(){
        // $host = '127.0.0.1';
        // $dbname = 'blog1';
        // $user = 'root';
        // $pass = '123456';
        // 连接数据库
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=blog1','root','123456');
        // 设置编码
        $pdo->exec('SET NAMES utf8');

        /*****************搜索******************* */
        // 关键字
        $where = 1;
        if(isset($_GET['keyword']) && $_GET['keyword']){
            $where .= " AND (title LIKE '%{$_GET['keyword']}%')";
        }
        // 日期搜索
        if(isset($_GET['start_date']) && $_GET['start_date']){
            $where .= " AND created_at >= '{$_GET['start_date']}'";
        }
        if(isset($_GET['end_date']) && $_GET['end_date']){
            $where .= " AND created_at <= '{$_GET['end_date']}'";
        }
        if(isset($_GET['is_show']) && $_GET['is_show']==1 || $_GET['is_show']==='0'){
            $where .= " AND is_show = '{$_GET['is_show']}'";
        }
        // 执行sql语句
        $stmt = $pdo->query("SELECT * FROM blogs WHERE $where");
        // 获取错误信息
        // $error = $pdo->errorInfo();
        // echo "<pre>";
        // var_dump($error);
        // echo "SELECT * FROM blogs WHERE $where";
        //取出数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // var_dump($data);
        // die;
        // 加载视图
        view('blogs.index',[
            'data'=>$data,
        ]);
    }
}