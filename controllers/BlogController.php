<?php
namespace controllers;
use PDO;
class BlogController{
    public function index(){
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

        /******************排序****************** */
        // 默认排序
        $odby = 'created_at';
        $odway = 'desc';
        if(isset($_GET['odby']) && $_GET['odby'] == 'display'){
            $odby = 'display';
        }
        if(isset($_GET['odway']) && $_GET['odway'] == 'asc'){
            $odway = 'asc';
        }
        /********************翻页********************** */
        $perpage = 15;  //每页条数
        // 接收当前页码
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        // 计算开始的下标
        $offset = ($page-1)*$perpage;
        // 制作按钮
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE $where");
        $stmt->execute($value);
        $count = $stmt->fetch(PDO::FETCH_COLUMN);
        // var_dump($count);
        // 计算总页数
        $pageCount = ceil($count / $perpage);
        $btn = '';

        for($i=1;$i<=$pageCount;$i++){

            $params = \getUrlParams(['page']);
            // if($page == $i){
            //     $btns .= "<a class='active' href='?{$params}page=$i'> $i </a>";
            // }else{
            //     $btns .= "<a href='?{$params}page=$i'> $i </a>";
            // }
            $class = $page==$i ? "active" : '';
            $btns .= "<a class='$class' href='?{$params}page=$i'> $i </a>";
        }

        // 预处理
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE $where ORDER BY $odby $odway LIMIT $offset,$perpage");
        // 执行SQL
        $stmt->execute($value);
        //取出数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // var_dump($data);
        // die;

        // 加载视图
        view('blogs.index',[
            'data'=>$data,
            'btns'=>$btns,
        ]);

    }
    public function content_to_html(){
        // 取出日志数据
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=blog1','root','123456');
        $pdo->exec('SET NAMES utf8');

        $stmt = $pdo->query('SELECT * FROM blogs');
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        ob_start();
        // 生成静态页
        foreach($blogs as $v)
        {
            // 加载视图
            view('blogs.content', [
                'blog' => $v,
            ]);
            // 取出缓冲区的内容
            $str = ob_get_contents();
            // 生成静态页
            file_put_contents(ROOT.'public/contents/'.$v['id'].'.html', $str);
            // 清空缓冲区
            ob_clean();
        }

    }

}