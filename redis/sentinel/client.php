<?php

include 'Round.php';

//返回主从节点

$sentinelConf = [
    ['ip' => 'ip', 'port' => 22530],
    ['ip' => 'ip', 'port' => 22531],
    ['ip' => 'ip', 'port' => 22532],
];

//随机访问
$sentinelInfo = $sentinelConf[array_rand($sentinelConf)];

$redis = new Redis();
$redis->connect($sentinelInfo['ip'], $sentinelInfo['port']);

//获取从节点信息
$slaveInfo = $redis->rawCommand('SENTINEL', 'slaves', 'mymaster');

//var_dump($slaveInfo);

$slaves = [];

foreach ($slaveInfo as $val) {
    $slaves[] = ['ip' => $val[3], 'port' => $val[5]];
}

//加载到缓存当中，可以记录一下这次访问的时间和上次的访问时间
swoole_timer_tick(1000, function () use ($slaves) {

    //轮训
    $slave = (new Round())->select($slaves);

    try {

        $redis = new Redis();
        $redis->connect($slave['ip'], $slave['port']);
        var_dump($slave, $redis->get('aaa'));

    } catch (\RedisException $e) {
        throw $e;
    }

});

//阻塞在这里
