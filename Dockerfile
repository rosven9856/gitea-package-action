ARG PHP_VERSION=8.3

FROM rosven9856/php:$PHP_VERSION

RUN apk add --update --no-cache --virtual .build-deps ${PHPIZE_DEPS} \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apk del .build-deps

RUN addgroup -g 1000 --system php
RUN adduser -G php --system -D -s /bin/sh -u 1000 php

RUN chown php:php /home/php
RUN chown php:php /usr/local/bin/composer

RUN mkdir /usr/bin/app
RUN chown -R php:php /usr/bin/app

COPY . /usr/bin/app
WORKDIR /usr/bin/app

ENV GITHUB_WORKSPACE=/usr/bin/app

RUN composer install

VOLUME ["/usr/bin/app"]

USER php

CMD ["php", "-f", "/usr/bin/app/app.php"]
