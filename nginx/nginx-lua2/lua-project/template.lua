local template =require "resty.template"
--template.render("index.html",{goods="123"})

local share_data= ngx.shared.redis_cluster_addr --共享内存
local tool=require ("tool")
local cjson =require "cjson"
local cjsonObj=cjson.new()

-- 连接redis时
local data=share_data:get("consul_addr")
local addr=tool.split(data,",")
local redis_addr={}
for k,v in pairs(addr) do
        -- ngx.say(v)
        ip_addr=tool.split(v,":")
        redis_addr[k]={ ip=ip_addr[1],port=ip_addr[2] }
end

-- ngx.print(cjsonObj.encode(redis_addr)) -- 得到想要数据结构

local config = {
    name = "testCluster",                   --rediscluster name
    serv_list = redis_addr,
    keepalive_timeout = 60000,              --redis connection pool idle timeout
    keepalive_cons = 1000,                  --redis connection pool size
    connection_timout = 1000,               --timeout while connecting
    max_redirection = 5,                    --maximum retry attempts for redirection
    -- auth=""
}

local redis_cluster = require "rediscluster"
local red_c = redis_cluster:new(config)

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

local uri_args=ngx.req.get_uri_args()
 --读取redis分类
local content=read_redis("shop_category")
local  goods={}
if not content then
    --应用层连接php_fpm
    local  req_data,res
    local  action=ngx.var.request.method
    --根据不同的请求类型
    if action=="POST" then
        req_data={ method=ngx.HTTP_POST,body=ngx.req.read_body()}
    elseif action == "PUT" then
        req_data={ method=ngx.HTTP_PUT,body=ngx.req.read_body()}
    else
        req_data={ method=ngx.HTTP_GET}
    end
     --内部子请求,发送到laravel框架
     res = ngx.location.capture(
        '/php/shop/public/index.php'..ngx.var.request_uri,req_data
     )
     -- ngx.say('/php/shop/public/index.php'..ngx.var.request_uri)
     -- 判断状态码决定是否打印，如果返回不是200
     -- if res.status == ngx.HTTP_OK then
     --默认是从redis当中获取,redis当中不存在才访问php服务
     goods['category']=cjsonObj.decode(res.body); -- 分类
     template.render("index.html",{goods=goods})
     return
end

ngx.say(content)
goods['shop']='peter的店铺'; -- 店铺
goods['info']='商品详情';-- 详情