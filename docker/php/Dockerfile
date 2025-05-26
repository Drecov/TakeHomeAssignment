FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY ./src ./

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 80