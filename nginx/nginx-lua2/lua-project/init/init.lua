-- 不会写入到error.log当中因为级别不够

-- 在worker进程启动时就应该直接读取consul当中的地址信息

-- lua模块的封装
local share_data= ngx.shared.redis_cluster_addr --共享内存
local tool=require ("tool")
local cjson =require "cjson"
local cjsonObj=cjson.new()

-- 定时获取地址
local delay=3
local check

check = function()
   consul=tool.read_http('http://47.106.243.13:8501/v1/kv/redis?recurse')
   local unjson=cjsonObj.decode(consul)
   consul_addr={}
   for k,v in pairs(unjson) do
          consul_addr[k]=ngx.decode_base64(v['Value'])
   end
   local result=table.concat(consul_addr,',')   -- 是将表里的value值连接
   share_data:set('consul_addr',result)         --写入都共享内存
end

-- 定时方法 check
 local ok, err = ngx.timer.every(delay, check)
 if not ok then
     ngx.log(ngx.err, "创建出错", err)
     return
 end