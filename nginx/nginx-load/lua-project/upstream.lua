local  uri_args=ngx.req.get_uri_args() --????
local  id=uri_args["id"]
local  server={"47.106.243.13:8002","47.106.243.13:8003"}
local  hash=ngx.crc32_long(id)
local  index=(hash % table.getn(server))+1
url="http://"..server[index]
local  http=require("resty.http") --??http?
local  httpClient=http.new()
ngx.say(url);
local  resp,err = httpClient:request_uri(url,{method="GET"})
if not resp then 
      ngx.say(err)
      return
end
ngx.say(resp.body)
httpClient:close()

