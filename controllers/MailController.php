<?php
namespace controllers;
use libs\Mail;
class Mailcontroller{
    public function send()
    {
        
        $mailer = new Mail;
        // 设置 socket 永不超时
        ini_set('default_socket_timeout', -1); 
        echo "发邮件队列启动成功。。。\r\n";

        while(true){

            // 先从队列中取出消息
            // 从email里取消息            一直等待直到等到为止
            $data = $redis->brpop('email',0);
            // 发邮件
            // json_decode默认把数组转成对象
            $message = json_decode($data[1], TRUE);
            
            $mailer->send($message['title'],$message['content'],$message['from']);
            echo "发送邮件成功，继续等待下一个。。。\r\n";
        }
    }
}