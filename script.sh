#!/bin/bash

cd /var/lib/mysql
mysqld --user=mysql --datadir=./data --basedir=. &
cd /root/oauth-test
php -S 0.0.0.0:8000 -t public/