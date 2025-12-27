FROM node:20 AS node_build
WORKDIR /app
COPY package.json package-lock.json* vite.config.js ./
RUN npm install
COPY resources ./resources
COPY public ./public
# Если есть tailwind или другие конфиги, их тоже нужно скопировать, но проще скопировать всё
COPY . .
RUN npm run build

FROM serversideup/php:8.4-fpm-nginx AS production

ENV PHP_OPCACHE_ENABLE=1

WORKDIR /var/www/html

# Копируем файлы проекта
COPY --chown=www-data:www-data . .

# Копируем собранные ассеты из node_build
COPY --from=node_build --chown=www-data:www-data /app/public/build ./public/build

# Устанавливаем зависимости Composer
USER www-data
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Права на запись (хотя serversideup обычно это обрабатывает, перестрахуемся)
USER root
RUN chmod -R 775 storage bootstrap/cache
USER www-data
