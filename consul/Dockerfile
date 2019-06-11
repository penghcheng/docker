
FROM  alpine
MAINTAINER  peter  "990979940@qq.com"

#配置环境变量consul版本
ENV CONSUL_VERSION=1.4.3
ENV HASHICORP_RELEASES=https://releases.hashicorp.com

#安装consul
RUN apk upgrade  && \
    apk add  net-tools && \
    apk add wget && \
    apk add unzip && \
    wget ${HASHICORP_RELEASES}/consul/${CONSUL_VERSION}/consul_${CONSUL_VERSION}_linux_amd64.zip && \
    unzip consul_${CONSUL_VERSION}_linux_amd64.zip && \
    rm -rf consul_${CONSUL_VERSION}_linux_amd64.zip && \
    mv consul /usr/local/bin


VOLUME /consul/data

#预开放端口
EXPOSE 8300
EXPOSE 8301 8301/udp 8302 8302/udp
EXPOSE 8500 8600 8600/udp

