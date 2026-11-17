FROM php:8.2-cli

# Extensiones necesarias para PDO + MySQL (Clever Cloud / ejer24 / ABM TP Final)
RUN docker-php-ext-install pdo pdo_mysql

COPY . /app
WORKDIR /app

EXPOSE 8080
CMD php -S 0.0.0.0:${PORT:-8080}
