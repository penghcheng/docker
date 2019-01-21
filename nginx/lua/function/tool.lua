
local tool= {}
-- 解码base64编码
function tool.decodeBase64(str64)
     local b64chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
     local temp={}
     for i=1,64 do
         temp[string.sub(b64chars,i,i)] = i
     end
     temp['=']=0
     local str=""
     for i=1,#str64,4 do
         if i>#str64 then
             break
         end
         local data = 0
         local str_count=0
         for j=0,3 do
             local str1=string.sub(str64,i+j,i+j)
             if not temp[str1] then
                 return
             end
             if temp[str1] < 1 then
                 data = data * 64
             else
                 data = data * 64 + temp[str1]-1
                 str_count = str_count + 1
             end
         end
         for j=16,0,-8 do
             if str_count > 0 then
                 str=str..string.char(math.floor(data/math.pow(2,j)))
                 data=math.mod(data,math.pow(2,j))
                 str_count = str_count - 1
             end
         end
     end
     local last = tonumber(string.byte(str, string.len(str), string.len(str)))
     if last == 0 then
         str = string.sub(str, 1, string.len(str) - 1)
     end
     return str
 end

 function tool.read_http(url)
    local http = require("resty.http")
    local httpc = http.new()
    local resp, err = httpc:request_uri(url,{
       method = "GET"
    })
    if not resp then
      ngx.log(ngx.ERR,"request error: ", err)  --日志等级
      return
    end
    httpc:close()
    return resp.body
end

--分割函数
function tool.split(input, delimiter)
     input = tostring(input)
     delimiter = tostring(delimiter)
     if (delimiter=='') then return false end
     local pos,arr = 0, {}
     -- for each divider found
     for st,sp in function() return string.find(input, delimiter, pos, true) end do
         table.insert(arr, string.sub(input, pos, st - 1))
         pos = sp + 1
     end
     table.insert(arr, string.sub(input, pos))
     return arr
end

return tool