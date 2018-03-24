#!/bin/bash
sudo sed -i "s/us\.archive\.ubuntu\.com/mirrors\.aliyun\.com/g" /etc/apt/sources.list
sudo apt-get update
sudo apt-get install -y apache2 libapache2-mod-php5 php5-cli php5-mysql php5-gd php5-curl git subversion dos2unix
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password vagrant'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password vagrant'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password vagrant'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password vagrant'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password vagrant'
sudo debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'
sudo apt-get -y install mysql-server phpmyadmin
sudo service mysql stop
if [ ! -d "/vagrant/common/runtime/mysql" ]; then
  initDb=1
  sudo mv /var/lib/mysql /vagrant/common/runtime/mysql
fi
sudo sed -i "s/\/var\/lib\/mysql/\/vagrant\/common\/runtime\/mysql/g" /etc/mysql/my.cnf /etc/apparmor.d/usr.sbin.mysqld
sudo service mysql start
if [ ! -d "/vagrant/vendor" ]; then
  sudo php /vagrant/composer.phar install -d /vagrant
fi
if [ ! -z $initDb ]; then
  sudo mysql -u root --password=vagrant <<< 'create database colourlife'
  sudo echo "development" > /vagrant/common/config/mode.php
  sudo dos2unix /vagrant/console/yiic
  sudo /vagrant/console/yiic migrate --interactive=0
fi
sudo a2enmod rewrite headers
sudo ln -s /vagrant/common/config/apache.conf /etc/apache2/sites-available/colourlife
sudo a2ensite colourlife
sudo service apache2 restart
