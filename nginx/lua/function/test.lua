-- 常用的方法
function say()
   return 123
end


-- http请求方法
function  read_http(url)
    local  http=require("resty.http")
    local  httpClient=http.new()
    local  resp,err = httpClient:request_uri(url,{method="GET"})
    if not resp then
         ngx.log(ngx.ERR,"请求失败")
         return
    end
    httpClient:close()
    return resp.body
end






-- 分割字符串方法


