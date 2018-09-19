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
    
   
}