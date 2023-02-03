#/!bin/bash

log=/tmp/test.dump
echo > $log

rm -rf vendor
rm -rf composer.lock
composer install --dev
rm -rf /tmp/NomenclatureBundle

/usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests >> $log 2>&1
status=$(cat /tmp/test.dump | grep "ERRORS!")
[ -z "$status" ] && return 0 ||  exit -1