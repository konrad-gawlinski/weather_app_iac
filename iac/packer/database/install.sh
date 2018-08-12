#!/usr/bin/env bash
set -euxo pipefail

apt-get update
debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
apt-get -y install mysql-server

sed -i 's/127\.0\.0\.1/0\.0\.0\.0/g' /etc/mysql/my.cnf

/etc/init.d/mysql start
mysql -su root -proot < "/tmp/secure_mysql.sql"
rm "/tmp/secure_mysql.sql"
