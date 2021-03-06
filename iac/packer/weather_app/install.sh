#!/usr/bin/env bash
set -euxo pipefail

mkdir -p /var/weather_app
mkdir -p /var/log/php

apt-get update
apt-get install -y wget ca-certificates apt-transport-https
wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add -
echo "deb https://packages.sury.org/php/ jessie main" | tee /etc/apt/sources.list.d/php.list

apt-get update
apt-get -y install supervisor nginx mysql-client
apt-get -y install php7.2 php7.2-fpm php7.2-xml php7.2-mysqli php7.2-zip unzip
