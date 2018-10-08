<?php
namespace libs;
class Redis{
    private static $redis = null;
    private function __clone(){}
    private function __constuct(){
    
    }
    // 获取redis对象
    public static function getInstance(){
        // 连接redis
        if(self::$redis === null){
            $redis = new \Predis\Client([
             'scheme' => 'tcp',
             'host' => '127.0.0.1',
             'prot' => 6379,
            ]);
        }
        return self::$redis;
    }
    
}