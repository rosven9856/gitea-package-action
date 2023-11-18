FROM rosven9856/basic-php-fpm-alpine:8.2.12.2

RUN addgroup -g 1000 --system php
RUN adduser -G php --system -D -s /bin/sh -u 1000 php

RUN chown php:php /home/php
RUN chown php:php /usr/local/bin/composer

RUN mkdir /var/src
RUN chown -R php:php /var/src

WORKDIR /var/src

COPY ./src /var/src

RUN composer install

USER php

COPY entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]