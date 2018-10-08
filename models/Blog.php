<?php
namespace models;
class Blog extends Base
{
  
    public function search(){
        
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
        $stmt = self::$pdo->query("SELECT * FROM blogs WHERE $where");
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
        $stmt = self::$pdo->prepare("SELECT COUNT(*) FROM blogs WHERE $where");
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
        $stmt = self::$pdo->prepare("SELECT * FROM blogs WHERE $where ORDER BY $odby $odway LIMIT $offset,$perpage");
        // 执行SQL
        $stmt->execute($value);
        //取出数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'btns' => $btns,
            'data' => $data,
        ];
    }
    public function content2html(){
        $stmt = self::$pdo->query('SELECT * FROM blogs');
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
    public function index2html(){
        // 取20  条记录
       $stmt =  self::$pdo->query("SELECT * FROM blogs ORDER BY id DESC LIMIT 20");
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // 开启缓冲区
        ob_start();
        // 加载视图文件
        view('index.index',[
            'blogs' => $blogs,

        ]);
        // 从缓冲区取出页面
        $str = ob_get_contents();
        // 把页面生成到静态页中
        file_put_contents(ROOT.'public/index.html',$str);
    }
    // 从数据库中取出浏览量
    public function getDisplay($id){
        
        // 使用日志ID 拼出键名
        $key = "blog-{$id}";
         // 连接redis
        $redis = \libs\Redis::getInstance();

        if($redis->hexists('blog_display', $key)){
            //    累加并返回添加完之后的值
            // hincrby 把值加1
               $newNum = $redis->hincrby("blog_display",$key,1);
               return $newNum;
           } else{
                $stmt = self::$pdo->prepare("SELECT display FROM blogs WHERE id=?");
                $stmt->execute([$id]);
                $display = $stmt->fetch(PDO::FETCH_COLUMN);
                $display++;
            //    加到 redis    hset  保存到redis中
            $redis->hset("blog_display",$key,$display);
            return $display;
           }
    }
    public function displayToDb(){
        // 先取出数据库中取出所有浏览量
         // 连接redis
        $redis = \libs\Redis::getInstance();
        $redis->hgetall('blog_display');
        // 更新回数据库
        foreach($data as $k => $v){
        $sql = "UPDATE blogs SET display={$v} WHERE id = {$id}";
        self::$pdo->exec($sql);
        }
    }
            
}