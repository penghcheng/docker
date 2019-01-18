--local template = require "resty.template"
--template.render("view.html", { message = "Hello, World!" })

-- 链接redis集群

local config = {
    name = "testCluster",                   --rediscluster name
    serv_list = {                           --redis cluster node list(host and port),
        { ip = "47.106.243.13", port = 6391 },
        { ip = "47.106.243.13", port = 6392 },
        { ip = "47.106.243.13", port = 6393 },
        { ip = "47.106.243.13", port = 6394 },
        { ip = "47.106.243.13", port = 6395 },
        { ip = "47.106.243.13", port = 6396 }
    },
    keepalive_timeout = 60000,              --redis connection pool idle timeout
    keepalive_cons = 1000,                  --redis connection pool size
    connection_timout = 1000,               --timeout while connecting
    max_redirection = 5,                    --maximum retry attempts for redirection
    -- auth=""
}

local redis_cluster = require "rediscluster"
local red_c = redis_cluster:new(config)

-- redis 函数
local function read_redis(key)
      local resp,err = red_c:get(key)

      if err then
          ngx.log(ngx.ERR, "err: ", err)
          return
      end

      if resp==ngx.null then
         resp=nil
      end
      return resp
end

-- 请求http
function read_http(url)
    local http = require("resty.http")
    local httpc = http.new()
    local resp, err = httpc:request_uri(url,{
       method = "GET"
    })
    if not resp then
      ngx.log(ngx.ERR,"request error: ", err)  --????
      return
    end
    httpc:close()
    return resp.body
end

-- 结合分发层（如果用户请求一个商品的id缓存不存在，发送请求到php服务）

local content=read_redis("name")

if not content then
    ngx.log(ngx.INFO,"发送http请求")
    --ngx.say(read_http('http://47.106.243.13:9501/'))

    --重写地址，继续匹配php-fpm
    return ngx.exec('/php', 'a=3&b=5&c=6');
end

ngx.say(content)