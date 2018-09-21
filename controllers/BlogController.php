<?php
namespace controllers;
use models\Blog;
class BlogController{
    // 日志列表
    public function index(){
        // echo "<pre>";
        // var_dump($data);
        // die;
        $blog = new Blog;
        $data = $blog->search();
        // 加载视图
        view('blogs.index',$data);
    }
    // 为所有日志生成详情页
    public function content_to_html(){
      $blog = new Blog;
      $blog->content2html();
    }
    public function index2html(){
        $blog = new Blog;
        $blog->index2html();
    }
    public function upadte_display(){
        $id = $_GET['id'];
        // 连接redis
        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'prot' => 6379,
        ]);
        // 拼接日志键
        $key = "blog-{$id}";

        if($redis->hexists('blog_display', $key)){
            //    累加并返回添加完之后的值
               $newNum = $redis->hincrby("blog_display",$key,1);
            echo $newNum;
           } else{
            //    从数据库中取出浏览量
            $blog = new Blog;
            $display = $blog->getDisplay($id);
            $display++;
            //    加到 redis
            $redis->hset("blog_display",$key,$display);
            echo $display;
           }
    }
    public function display(){
        // 接收日志ID
        $id = $_GET['id'];
        $blog = new Blog;
       echo $blog->getDisplay($id);
    }
    public function displayToDb(){
        $blog = new Blog;
        $blog->displayToDb();
    }
}