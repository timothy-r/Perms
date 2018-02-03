#! /bin/bash

# get composer
wget https://getcomposer.org/download/1.6.3/composer.phar
chmod +x composer.phar

# set up dependencies
cd src
../composer.phar install

# set up db
cd ../build
./db-init.sh


