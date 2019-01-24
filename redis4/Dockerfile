FROM centos:latest
MAINTAINER peter "990979940@qq.com"
RUN groupadd -r redis && useradd  -r -g redis redis
RUN  yum update -y && yum install epel-release -y && yum install centos-release-scl-rh -y && yum install rh-ruby24 -y && /usr/bin/scl enable rh-ruby24 bash
RUN  yum -y install  wget  gcc automake autoconf libtool make  net-tools rubygems
RUN wget http://download.redis.io/releases/redis-4.0.12.tar.gz \
&& tar xzf redis-4.0.12.tar.gz  && cd redis-4.0.12  && make && make install \
&& cd src && cp redis-trib.rb  /usr/bin && chmod  775  /usr/bin/redis-trib.rb \
&& cd  ../../ && rm -rf  redis-4.0.12
RUN wget https://rubygems.global.ssl.fastly.net/gems/redis-3.2.1.gem  &&  gem install -l ./redis-3.2.1.gem \
&&  rm -f redis-3.2.1.gem
COPY  ./config/redis.sh       /usr/bin
RUN  mkdir -p /config && mkdir -p /var/log/redis && mkdir -p  /var/lib/redis && chmod  775  /usr/bin/redis.sh