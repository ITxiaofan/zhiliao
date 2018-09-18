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
}