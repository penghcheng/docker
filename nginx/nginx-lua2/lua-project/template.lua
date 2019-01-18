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

local v, err = red_c:get("peter")
if err then
    ngx.log(ngx.ERR, "err: ", err)
else
    ngx.say(v)
end