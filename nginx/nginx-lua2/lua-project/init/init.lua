-- 不会写入到error.log当中因为级别不够
--[[

check = function()
   ngx.log(ngx.INFO,"23456")
end

--]]
 
check = function()
   --ngx.log(ngx.ERR,"test-23456")
end

 local ok, err = ngx.timer.every(3, check)
 if not ok then
     ngx.log(ngx.err, "创建出错", err)
     return
 end



