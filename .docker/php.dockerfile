FROM php:8.2-fpm-alpine

ARG PHPGROUP
ARG PHPUSER

ENV PHPGROUP=${PHPGROUP}
ENV PHPUSER=${PHPUSER}
ENV PHP_MEMORY_LIMIT=256M

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}; exit 0

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN docker-php-ext-install pdo pdo_mysql

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis
RUN echo 'memory_limit = 256M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

RUN apk add --no-cache dcron \
    && echo "* * * * * php /var/www/html/bin/console.php app:push-reminder-notifications >> /var/log/cron.log 2>&1" | crontab -

RUN apk add --no-cache supervisor
COPY ./.docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
CMD crond
CMD php-fpm -y /usr/local/etc/php-fpm.conf -R