FROM alpine:edge
RUN apk update && \
    apk add --no-cache openrc git php7 mysql sudo php7-mysqli php7-intl php7-openssl php7-sqlite3 php7-gmp \
        php7-pdo_mysql openssh composer php7-ctype php7-tokenizer php7-xml yarn curl bash \
        php7-pcntl php7-fpm php7-mysqlnd php7-mbstring php7-dev php7-pdo_sqlite php7-gd php7-json php7-bcmath \
        php7-dom php7-pdo php7-simplexml php7-xmlwriter php7-pecl-memcached php7-zip mariadb-server-utils && \
    addgroup mysql mysql && \
    cd /var/lib/mysql && \
    sudo -u mysql mysql_install_db && \
    mkdir /run/mysqld && \
    chown mysql:mysql /run/mysqld && \
    cd /root && \
    git clone https://git.empherino.net/NEb0/oauth-test.git && \
    cd oauth-test && \
    composer install && \
    yarn install && yarn build && \
    bin/console doctrine:database:create && \
    mysql oauth_test <deploy/data.sql


WORKDIR /root

ADD deploy/script.sh /root/start.sh
ADD deploy/data.sql /root/oauth-test/data.sql
ADD deploy/.env.local /root/oauth-test/.env.local


ENTRYPOINT [ "/root/start.sh" ]
VOLUME [ "/var/lib/mysql" ]

EXPOSE 8000