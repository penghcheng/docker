## Dockerfile 创建镜像
##### docker build -t xxx .

## 创建docker consul
##### docker pull consul
##### docker run -itd -p 8501:8500 --name consul consul

##### 写入数据
##### curl  -X  PUT  -d  '47.106.243.13:6391'  http://127.0.0.1:8501/v1/kv/redis_cluster/1

#### 查看数据
#### curl http://127.0.0.1/v1/kv/?recurse (可以使用前缀redis [curl http://127.0.0.1/v1/kv/redis?recurse] )

## docker-compose 创建容器命令
##### docker-compose up -d

## 查看docker的日志信息
##### docker logs ba85fb04d3c0