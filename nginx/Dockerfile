FROM centos
RUN mkdir /data && mkdir /config && mkdir /nginx  && mkdir /lua  && mkdir /sock && mkdir /nginx-test
RUN groupadd -r nginx && useradd  -r -g nginx nginx

#安装相关依赖
RUN yum -y update
RUN yum -y install gcc gcc-c++ autoconf automake make zlib zlib-devel openssl* pcre* wget

RUN cd /data \
 && wget https://github.com/openresty/lua-nginx-module/archive/v0.10.13.tar.gz  \
 && tar -xzvf v0.10.13.tar.gz \
 && rm -rf  v0.10.13.tar.gz

RUN  cd /data \
 && wget https://codeload.github.com/simpl/ngx_devel_kit/tar.gz/v0.3.0rc1 \
 && tar -xzvf v0.3.0rc1 \
 && rm -rf v0.3.0rc1

RUN  cd /data \
 && wget http://labs.frickle.com/files/ngx_cache_purge-2.3.tar.gz \
 && tar -xzvf ngx_cache_purge-2.3.tar.gz \
 && rm -rf ngx_cache_purge-2.3.tar.gz

RUN  cd /data \
 && wget http://luajit.org/download/LuaJIT-2.0.5.tar.gz \
 && tar -xzvf LuaJIT-2.0.5.tar.gz \
 && cd ./LuaJIT-2.0.5 \
 && make PREFIX=/usr/local/ \
 && make install PREFIX=/usr/local/ \
 && cd .. \
 && export LUAJIT_LIB=/usr/local/lib  \
 && export LUAJIT_INC=/usr/local/include/luajit-2.0
RUN  cd /data \
 && wget http://nginx.org/download/nginx-1.14.1.tar.gz \
 && tar -zxvf nginx-1.14.1.tar.gz \
 && cd nginx-1.14.1 \
 && ./configure --prefix=/usr/local/nginx --user=nginx --group=nginx \
 	--with-ld-opt="-Wl,-rpath,/usr/local/lib"  \
 	--with-http_stub_status_module \
 	--with-http_ssl_module \
 	--with-http_sub_module \
 	--add-module=/data/ngx_devel_kit-0.3.0rc1 \
    --add-module=/data/lua-nginx-module-0.10.13 \
 	--add-module=/data/ngx_cache_purge-2.3 \
 	&& make && make install
COPY   ./nginx.sh      /usr/bin
RUN chmod  775  /usr/bin/nginx.sh  && ln -s /usr/local/nginx/sbin/* /usr/local/sbin/
EXPOSE 80 443
CMD ["/usr/local/nginx/sbin/nginx","-g","daemon off;"]
