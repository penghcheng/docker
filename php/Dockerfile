FROM php:7.2.2-fpm

ARG PHPREDIS_VERSION=4.0.0
ARG HIREDIS_VERSION=0.13.3
ARG SWOOLE_VERSION=4.2.0

# Timezone
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone

RUN apt-get update -y  && apt-get install vim wget curl net-tools zlib1g-dev  libssl-dev  libnghttp2-dev  libpcre3-dev -y \
    && docker-php-ext-install \
    iconv \
    mysqli \
    pdo_mysql \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get autoremove -y --purge \
    && apt-get clean \
    && rm -Rf /tmp/*

# Hiredis
RUN wget https://github.com/redis/hiredis/archive/v${HIREDIS_VERSION}.tar.gz -O hiredis.tar.gz \
    && mkdir -p hiredis \
    && tar -xf hiredis.tar.gz -C hiredis --strip-components=1 \
    && rm hiredis.tar.gz \
    && ( \
        cd hiredis \
        && make -j$(nproc) \
        && make install \
        && ldconfig \
    ) \
    && rm -r hiredis


# Swoole extension
RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
        cd swoole \
        && phpize \
        && ./configure --enable-async-redis --enable-mysqlnd --enable-openssl --enable-http2 \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole


