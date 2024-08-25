ARG PHP_VERSION=8.3.10-1

FROM rosven9856/php:$PHP_VERSION

RUN addgroup -g 1000 --system php
RUN adduser -G php --system -D -s /bin/sh -u 1000 php

RUN chown php:php /home/php
RUN chown php:php /usr/local/bin/composer

RUN mkdir /usr/bin/app
RUN chown -R php:php /usr/bin/app

COPY . /usr/bin/app
WORKDIR /usr/bin/app

RUN composer install

VOLUME ["/usr/bin/app"]

USER php

CMD ["php", "-f", "/usr/bin/app/app.php"]
