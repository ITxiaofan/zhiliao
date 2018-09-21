<?php
namespace controllers;
class TestController{
    public function testRedis(){
         // 连接redis
        $client = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'prot' => 6379,
        ]);
        // $client ->set('name','ruduo');
        $client->del('name');
    }
    public function register(){
        // 注册成功

        // 发邮件
         $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'prot' => 6379,
        ]);
        // 消息队列的信息
        $data = [
            'email' => 'ITXIFAN@126.com',
            'title' => '标题',
            'content' => '内容',
        ];
        // 数组转成JSON
        $data = json_encode($data);
        $redis->lpush('email',$data);
        echo "注册成功";
    }

    public function mail(){
        // 设置永不过期
        ini_set('default_socket_timeout',-1);
        echo "邮件程序已启动。。。等待中。。。";
        // 连接redis
        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'prot' => 6379,
        ]);
        // 监听一个列表
        while(true){
            // 从列表中取数据，设置为永久不超时
        $data = $redis->brpop('email',0);
        echo "开始发邮件";
        // 处理数据
        var_dump($data);
        echo "发完邮件。继续等待\r\n";

        }
    }
    public function testMail1(){
        $mail = new \libs\Mail;
        $mail->send('测试eamil类','测试mail类',['1724940950@qq.com','小范']);
    }

    public function testMail(){
        // 设置邮件服务器账号
        $transport = (new \Swift_SmtpTransport('smtp.126.com', 25))  // 邮件服务器IP地址和端口号
                    ->setUsername('ITXIFAN@126.com')       // 发邮件账号
                    ->setPassword('5467464love78386');      // 授权码

        // 创建发邮件对象
        $mailer = new \Swift_Mailer($transport);
        
        // 创建邮件消息
        $message = new \Swift_Message();
        $message->setSubject('测试标题')   // 标题
                ->setFrom(['ITXIFAN@126.com' => '全栈@清'])   // 发件人
                ->setTo(['1724940950@qq.com', '1724940950@qq.com' => '清@QQ'])   // 收件人
                ->setBody('Hello <a href="http://localhost:9999">点击激活</a> World ~', 'text/html');     // 邮件内容及邮件内容类型
        
        // 发送邮件
        $ret = $mailer->send($message);
        // var_dump($ret);
        }
   
}