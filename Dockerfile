FROM php:8.3-cli

# ====== System deps + TZ ======
ENV TZ=America/Lima
RUN apt-get update && apt-get install -y \
    unzip git libpng-dev libonig-dev libxml2-dev zip curl tzdata \
 && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
 && echo $TZ > /etc/timezone \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ====== 1) Instala vendor SIN scripts (no existe artisan aún) ======
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

# ====== 2) Copia el resto del proyecto (ya incluye artisan) ======
COPY . .

# Permisos para Laravel
RUN mkdir -p storage bootstrap/cache \
 && chmod -R ug+rwX storage bootstrap/cache

# Puerto de la app
EXPOSE 8080

# ====== Arranque ======
# Descubre paquetes ahora que ya existe artisan, limpia caches y migra;
# luego levanta el servidor embebido de PHP en 0.0.0.0:${PORT:-8080}
CMD sh -lc '\
  php artisan package:discover --ansi || true; \
  php artisan config:clear || true; \
  php artisan route:clear || true; \
  php artisan cache:clear || true; \
  php artisan migrate --force || true; \
  exec php -d variables_order=EGPCS -S 0.0.0.0:${PORT:-8080} -t public public/index.php \
'
