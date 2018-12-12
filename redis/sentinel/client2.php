<?php

include 'Round.php';

$retry = 3;//重试3次

try {
    $redis = new Redis();
    $redis->connect('198.10.0.11', 6379,0.2);
    $redis->set('redis', 123);
} catch (\Exception $e) {
    var_dump($e->getMessage());
    while ($e->getMessage() == 'Redis server went away' && $retry--) {
        var_dump('重新尝试连接');
    }

    //访问哨兵获取新的主节点
    /*while ($retry--) {
        var_dump('重新尝试连接');
    }*/

    //获取从节点信息
    //$slaveInfo = $redis->rawCommand('SENTINEL', 'get-master-addr-by-name', 'mymaster');
}
