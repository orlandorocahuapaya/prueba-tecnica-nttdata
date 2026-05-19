FROM php:8.3-cli

ENV TZ=America/Lima
RUN apt-get update && apt-get install -y \
    unzip git libpng-dev libonig-dev libxml2-dev zip curl tzdata \
 && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
 && echo $TZ > /etc/timezone \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

COPY . .

RUN mkdir -p storage bootstrap/cache public/uploads/remates \
 && chmod -R ug+rwX storage bootstrap/cache public/uploads


EXPOSE 8080

CMD sh -lc '\
  [ -f .env ] || cp .env.example .env; \
  php artisan key:generate --force || true; \
  php artisan package:discover --ansi || true; \
  php artisan config:clear || true; \
  php artisan route:clear || true; \
  php artisan cache:clear || true; \
  php artisan migrate --force || true; \
  exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080} \
'
