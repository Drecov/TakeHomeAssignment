#!/bin/sh

until nc -z -v -w30 mysql 3306
do
  echo "Aguardando MySQL estar disponível..."
  sleep 5
done

php /var/www/html/utils/runMigrationsScript.php
exec apache2-foreground