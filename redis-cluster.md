# docker
docker 配置、集群等
### redis 集群配置
#### 注意：
每个Redis Cluster节点会占用两个TCP端口，一个监听客户端的请求，默认是6379，另外一个在前一个端口加上10000，比如16379，来监听数据的请求，节点和节点之间会监听第二个端口，用一套二进制协议来通信。

#### redis-trib.rb 搭建集群
redis-trib.rb create --replicas 1 ip:6391  ip:6392  ip:6393 ip:6394 ip:6395 ip:6396
 
