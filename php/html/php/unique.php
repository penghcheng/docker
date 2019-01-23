<?php


//集群环境下对于事务的支持是有限的

//$queue='queue';  //集合的名称
//$key='shop_product_1'; //队列的key
//$value='peter'; //队列的value
//$res=$redis->sAdd($queue,$key); //key过滤
//if($res){
//    var_dump($redis->lPush($key,$value)); //放入到某个队列当中
//}
//push  rpop   //队列存储数据

ini_set('default_socket_timeout', -1);  //socket连接不超时
class unique
{
    public $redis;

    const   PUSH = '
          local q_set=KEYS[1]
          local v=redis.call("SADD",KEYS[1],KEYS[2]) --添加了一个集合元素，用来过滤key
          if v == 1 then 
            return redis.call("LPUSH",KEYS[2],ARGV[1])
          else
           return 0
          end
     ';
    const   POP = '
          local q_set=KEYS[1]
          local v=redis.call("RPOP",KEYS[2])  -- 弹出任务
          if type(v) == "boolean" then
            return 0  
          else
            redis.call("SREM",q_set,KEYS[2]) --在集合当中删除掉队列名称
            return  v
          end         
     ';

    public function __construct()
    {
        $this->redis = new  RedisCluster('', ['47.106.243.13:6391', '47.106.243.13:6392'], 0.5, 3, true);

        /*foreach ($this->redis->_masters() as $v) {
            var_dump($v);      //172.10.0.0.5
        }*/
    }

    /**
     * push 去重队列
     * @param $setName 集合key
     * @param $queueName 队列key
     * @param $body 队列value
     * @return mixed
     */
    public function push($setName, $queueName, $body)
    {
        // 在redis集群当中使用lua脚本时，里面的redis指令，只能写入本地的key(在数据槽范围内的)
        // 脚本当中redis客户端执行的key,必须属于同一个节点,使用了hashtag实现
        // 在我们设计key时，我们可以通过获得数据槽的范围，来生成需要的key
        // '{product_1_2000}:set','{product_1_2000}:2','product'
        // key   value                                  //集合key  //队列key  //队列value
        return $this->redis->eval(self::PUSH, [$setName, $queueName, $body], 2);
    }

    /**
     * pop 去重队列
     * @param $setName 集合key
     * @param $queueName 队列key
     * @return mixed
     */
    public function pop($setName, $queueName)
    {
        //'{product_1_2000}:set','{product_1_2000}:2'
        return $this->redis->eval(self::POP, [$setName, $queueName], 2);
    }

}