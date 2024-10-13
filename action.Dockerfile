ARG PHP_VERSION=8.3

FROM rosven9856/php:$PHP_VERSION

COPY . /usr/bin/app
WORKDIR /usr/bin/app

RUN composer install --optimize-autoloader

VOLUME ["/usr/bin/app"]

ENTRYPOINT ["php", "-f", "/usr/bin/app/app.php"]
