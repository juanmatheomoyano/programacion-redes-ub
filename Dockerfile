FROM php:8.2-cli

# Agregar PDO y PDO_MySQL para la conexión a base de datos en el ejer24
RUN docker-php-ext-install pdo pdo_mysql

COPY . /app
WORKDIR /app

EXPOSE 8080
CMD php -S 0.0.0.0:${PORT:-8080}
