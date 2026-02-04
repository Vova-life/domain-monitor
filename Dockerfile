FROM richarvey/php-fpm-nginx:latest

# Копіюємо файли
COPY . /var/www/html

# Налаштування
WORKDIR /var/www/html
ENV WEBROOT /var/www/html/public
ENV APP_ENV production
ENV APP_DEBUG false

# Встановлюємо залежності
RUN composer install --no-dev --optimize-autoloader
RUN apk add --no-cache nodejs npm && npm install && npm run build

# Права доступу
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 👇 ЗАМІСТЬ КРОКУ 3: Вписуємо команду запуску прямо сюди 👇
# Вона запустить міграції і запустить сервер
CMD php artisan migrate --force && /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
