FROM php:8.2-apache
WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y netcat-openbsd

COPY ./src ./
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 80