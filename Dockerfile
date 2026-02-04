# Використовуємо офіційний образ PHP з Apache (він простіший для деплою)
FROM php:8.3-apache

# Встановлюємо системні залежності для Laravel та PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# Вмикаємо мод_реврайт для Apache (потрібно для Laravel)
RUN a2enmod rewrite

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копіюємо файли проекту
COPY . /var/www/html

# Вказуємо робочу директорію
WORKDIR /var/www/html

# Налаштовуємо права доступу
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Встановлюємо залежності PHP
RUN composer install --no-dev --optimize-autoloader

# Збираємо фронтенд
RUN npm install && npm run build

# Міняємо DocumentRoot Apache на папку public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Відкриваємо порт
EXPOSE 80

# Команда запуску: міграції + запуск Apache
CMD php artisan migrate --force && apache2-foreground
