FROM centos:latest
MAINTAINER lemon "2865900737@qq.com"
RUN groupadd -r redis && useradd  -r -g redis redis
RUN  yum -y update &&  yum -y install epel-release \
&&   yum -y install redis  && yum -y install wget \
&&   yum -y install net-tools \
&&   yum -y install  ruby && yum  -y install  rubygems
RUN wget https://rubygems.global.ssl.fastly.net/gems/redis-3.2.1.gem  &&  gem install -l ./redis-3.2.1.gem \
&&  rm -f redis-3.2.1.gem
COPY  ./config/redis-trib.rb  /usr/bin
COPY  ./config/redis.sh       /usr/bin
RUN  chmod  775  /usr/bin/redis-trib.rb
RUN  mkdir -p /config  && chmod  775  /usr/bin/redis.sh