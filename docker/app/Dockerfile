
FROM ubuntu:16.04

# 使用阿里云镜像
#RUN sed -i "s/archive.ubuntu.com/mirrors.aliyun.com/g" /etc/apt/sources.list \
#  && sed -i "s/security.ubuntu.com/mirrors.aliyun.com/g" /etc/apt/sources.list

# 使用网络代理
#ENV http_proxy=http://192.168.1.105:1087 https_proxy=https://192.168.1.105:1087

# 安装 php7.1
RUN apt-get update && apt-get install -y \
    language-pack-en-base \
  && locale-gen en_US.UTF-8 \
  && apt-get install -y \
    software-properties-common \
  && LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php \
  && apt-get update && apt-get install -y \
    php7.1-cli \
    php7.1-fpm \
    php7.1-xml \
    php7.1-mbstring \
    php7.1-curl \
    php-xdebug \
  && rm -rf /var/lib/apt/lists/*

# 安装 composer 相关
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    wget \
  && rm -rf /var/lib/apt/lists/* \
  && wget -O /usr/local/bin/composer https://getcomposer.org/download/1.6.2/composer.phar \
  && chmod +x /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    php-pear \
    php7.1-dev \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install swoole \
  && echo "extension=swoole.so" > /etc/php/7.1/mods-available/swoole.ini \
  && phpenmod swoole

# 安装 redis 扩展
RUN apt-get update && apt-get install -y \
    php-redis \
    && rm -rf /var/lib/apt/lists/*

# 关闭使用网络代理
#ENV http_proxy='' https_proxy=''