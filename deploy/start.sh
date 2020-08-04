#!/bin/bash

cd /var/lib/mysql
mysqld --user=mysql --datadir=./data --basedir=. &
sleep 5
cd /root/oauth-test
bin/console doctrine:database:drop --if-exists -f -n
bin/console doctrine:database:create
mysql oauth_test <deploy/data.sql
php -S 0.0.0.0:8000 -t public/ &
