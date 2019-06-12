## 创建 swoft 和 swoftconsul 镜像

## 创建 swoftconsul 容器
    docker run -itd --name consul0 -p 8501:8500 --network swoftNetwork  --ip 192.168.1.10   swoftconsul
 
    docker run -itd --name consul1 --network swoftNetwork  --ip 192.168.1.11   swoftconsul
 
    docker run -itd --name consul2 --network swoftNetwork  --ip 192.168.1.12   swoftconsul
 
## 配置consul集群

    consul agent -server -ui -node=server0  -bootstrap-expect=3  -bind=192.168.1.10  -data-dir /consul/data -join=192.168.1.10 -client 0.0.0.0
 
    consul agent -server -ui -node=server1  -bootstrap-expect=3  -bind=192.168.1.11  -data-dir /consul/data -join=192.168.1.10 -client 0.0.0.0
  
    consul agent -server -ui -node=server2  -bootstrap-expect=3  -bind=192.168.1.12  -data-dir /consul/data -join=192.168.1.10 -client 0.0.0.0
   
   
### 访问测试集群是否配置成功

    http://xx.xx.xx.xx:8501/ui/
    

 