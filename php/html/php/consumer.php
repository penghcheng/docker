<?php

// 获取队列的任务，然后执行相应的业务逻辑（更新缓存、更新数据库）
require "unique.php";

//守护进程,  定时任务:用来检测当前进程是否意外关闭了
try {
    $unique = new unique();
    $key = "{product_1_20000}:set";
    while (true) {
        $data = $unique->redis->sMembers($key);
        if (!empty($data)) {
            foreach ($data as $v) {
                $job = json_decode($unique->pop($key, $v), true);
                //更新mysql，重试，继续放到队列中，发送警告，写入到失败队列当中
                switch ($job['type']) {
                    case  'info':
                        //从数据库当中得到数据，然后写入到缓存当中
                        sleep(0.2);
                        var_dump($unique->redis->set('shop_info_' . $job['id'], "info:" . $job['id']));
                        break;
                }
            }
        }
    }
} catch (\Exception $e) {
    //Timed out attempting to find data in the correct node!
    var_dump($e->getMessage());
    //redis连接失败了，选择重试下
    //mysql连接失败了，重试下
}