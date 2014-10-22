#!/usr/bin/env bash
cd /vagrant/

#disable the os frontend
export DEBIAN_FRONTEND=noninteractive

#update application list
apt-get update
apt-get install -q -y python-software-properties
add-apt-repository ppa:ondrej/php5-oldstable
add-apt-repository ppa:chris-lea/node.js
apt-get update

#install utilities
apt-get install -q -y git curl nodejs

#install and configure apache2
apt-get install -q -y apache2
cp /vagrant/VirtualHost /etc/apache2/sites-available/VirtualHost
a2dissite default
a2ensite VirtualHost
a2enmod rewrite
service apache2 reload

#install and configure mysql-server
echo 'mysql-server-5.5 mysql-server/root_password password root' | debconf-set-selections
echo 'mysql-server-5.5 mysql-server/root_password_again password root' | debconf-set-selections
apt-get install -q -y mysql-server-5.5 libapache2-mod-auth-mysql php5-mysql
Q1="create database if not exists cake;"
Q2="grant all privileges on cake.* to root@'localhost' identified by 'root';"
Q3="create database if not exists cake_test;"
Q4="grant all privileges on cake_test.* to root@'localhost' identified by 'root';"
Q5="flush privileges;"
SQL="${Q1}${Q2}${Q3}${Q4}${Q5}"
mysql -uroot -proot -e"$SQL"

#install php5
apt-get install -q -y php5 libapache2-mod-php5 php5-mcrypt php5-xdebug

#install phpMyAdmin
echo 'phpmyadmin phpmyadmin/dbconfig-install boolean true' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/app-password-confirm password root' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/admin-pass password root' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/app-pass password root' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2' | debconf-set-selections
apt-get install -q -y phpmyadmin

#install composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

#install bower
npm config set registry http://registry.npmjs.org/
npm install -g bower

#add the cakePHP console to the path
echo "PATH=\"\$PATH:/vagrant/vendor/cakephp/cakephp/lib/Cake/Console/\"" > cakephp.sh
mv cakephp.sh /etc/profile.d/cakephp.sh

#final tasks/cleanup
apt-get clean
