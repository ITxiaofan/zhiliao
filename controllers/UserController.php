<?php
namespace controllers;
use models\User;
class UserController{
    
   
    public function hello(){
        // 取数据
        $user = new User;
        $name = $user->getName();
        // 加载视图
        view('users.hello',[
            'name'=>$name,
        ]);
    }
    public function world(){
        echo "world";
    }
     public function register(){
        view('users.add');
    }
    public function store(){
        // 接收表单
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        // 插入到数据库中
        $user = new User;
        $ret = $user->add($email,$password);
        if(!$ret){
            
            die ("注册失败！");
        }
        
        // 把消息放到队列中

        // 从邮箱地址中取出用户名
        $name = explode('@', $email);
        
        // 构收件人地址
        $from = [$email,$name[0]];
        
        $message = [
            'title' => '欢迎加入',
            'content' => "点击链接进行激活：<br><a href=''>点击激活</a>",
            'from' => $from,
        ];
        // 把消息转换成字符串
        $message = json_encode($message);
        
        // $email = new \libs\Mail;
        // $content = "恭喜您,注册成功！";
        // 连接redis
        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'prot' => 6379,
        ]);
        // 发邮件
        $redis->lpush('email',$message);

        echo 'ok';
    }
}