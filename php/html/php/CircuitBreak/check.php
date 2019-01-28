<?php
/**
 * Created by PhpStorm.
 * User: derek
 * Date: 2019/1/28
 * Time: 17:14
 */

//消费任务

$redis=new  \RedisCluster('',['47.106.243.13:6381', '47.106.243.13:6382']);
$successCount=-3; //成功多少次
while (true){
    //得到的等于当前时间或者已经超时
    $service=$redis->zRangeByScore("circuit_open","-inf",time(),['limit'=>[0,20]]);
    //需要修改这个服务的状态值
    if(count($service)>0){
        foreach ($service as $s){
            //修改了服务的状态
            $redis->zAdd("circuit",$successCount,$s);
            $redis->zRem("circuit_open", $s);
            var_dump($s);
            echo "修改了{$s}状态" . PHP_EOL;
        }
    }
    usleep(50000);
}