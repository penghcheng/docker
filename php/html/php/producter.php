<?php

// 生产者负责接收，更新缓存请求，然后写到队列中

require "unique.php";

try {
    $setKey = '{product_1_20000}:set';  //集合key
    $queueKey = '{product_1_20000}:1';  //队列key
    $infoKey = "shop_info_1";           //获取商品详情的key
    $unique = new unique();
    //判断当前的id是否有更新任务，没有再添加,还是要得到缓存数据，返回数据
    $set = $unique->redis->sIsMember($setKey, $queueKey);
    if ($set != false) {
        //等待任务的完成,读取缓存,等待一定的时间
        $i = 0;
        $info = '';
        while (true) {
            $i++;
            $info = $unique->redis->get($infoKey);
            if ($i == 5 && $info == false) {
                throw  new Exception("次数超出");
            }
            if ($info != false) {
                break;
            }
            sleep(0.3);
        }
        echo $info . PHP_EOL;
    } else {
        $job = ['opera' => 'update', 'type' => 'info', 'id' => 1];
        var_dump($unique->push($setKey, $queueKey, json_encode($job)));
        //从缓存当中获取数据
    }

} catch (Exception $e) {
    var_dump($e->getMessage());
}

