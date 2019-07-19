###设置网络
docker network create kong-net

###安装数据库，使用postgres
    docker run -d --name kong-database \
                  --network=kong-net \
                  -p 5432:5432 \
                  -e "POSTGRES_USER=kong" \
                  -e "POSTGRES_DB=kong" \
                  postgres:9.6

###准备kong数据
    docker run --rm \
         --link kong-database:kong-database \
         -e "KONG_DATABASE=postgres" \
         -e "KONG_PG_HOST=kong-database" \
         -e "KONG_PG_PASSWORD=your_pg_password" \
         -e "KONG_CASSANDRA_CONTACT_POINTS=kong-database" \
         kong:latest kong migrations bootstrap

###启动kong，设置postgres数据库
    docker run -d --name kong \
        --link kong-database:kong-database \
        -e "KONG_DATABASE=postgres" \
        -e "KONG_PG_HOST=kong-database" \
        -e "KONG_PG_PASSWORD=your_pg_password" \
        -e "KONG_CASSANDRA_CONTACT_POINTS=kong-database" \
        -e "KONG_PROXY_ACCESS_LOG=/dev/stdout" \
        -e "KONG_ADMIN_ACCESS_LOG=/dev/stdout" \
        -e "KONG_PROXY_ERROR_LOG=/dev/stderr" \
        -e "KONG_ADMIN_ERROR_LOG=/dev/stderr" \
        -e "KONG_ADMIN_LISTEN=0.0.0.0:8001, 0.0.0.0:8444 ssl" \
        -p 8000:8000 \
        -p 8443:8443 \
        -p 8001:8001 \
        -p 8444:8444 \
        kong:latest
	
###测试kong
    Kong is running:
    curl -i http://localhost:8001/
	
###UI 安装
    docker run -d --name kong-dashboard \
        --network=kong-net \
        --link kong:kong \
        -p 8080:8080 \
        pgbi/kong-dashboard:v2 migrations up
	
###测试UI http://:8080

psea,abc123456