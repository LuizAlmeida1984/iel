# ============================================================
# Stage 1: Build do Angular (frontend)
# ============================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app
COPY frontend/package*.json ./
RUN npm ci --legacy-peer-deps
COPY frontend/ .
RUN npm run build

# ============================================================
# Stage 2: PHP-FPM + Nginx + Laravel (backend)
# ============================================================
FROM php:8.3-fpm

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite pdo_pgsql pgsql

# Corrige diretórios do Nginx e remove o site padrão
RUN mkdir -p /var/run/nginx /var/log/nginx \
    && chown -R www-data:www-data /var/run/nginx /var/log/nginx \
    && rm -f /etc/nginx/sites-enabled/default

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Certificado SSL
COPY backend/cacert.pem /usr/local/share/ca-certificates/cacert.pem
RUN echo 'curl.cainfo="/usr/local/share/ca-certificates/cacert.pem"' >> /usr/local/etc/php/conf.d/cacert.ini \
    && echo 'openssl.cafile="/usr/local/share/ca-certificates/cacert.pem"' >> /usr/local/etc/php/conf.d/cacert.ini

WORKDIR /var/www

# Copia o backend (Laravel)
COPY backend/ .

# Instala dependências PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissões de storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copia os arquivos do Angular já buildados no Stage 1
COPY --from=frontend-builder /app/dist/iel/browser/ /var/www/frontend/

# Permissões da pasta do frontend
RUN chown -R www-data:www-data /var/www/frontend

# Configuração do Nginx
COPY backend/docker/nginx/default.conf /etc/nginx/conf.d/default.conf
RUN nginx -t

# Entrypoint para rodar php-fpm e nginx juntos
COPY backend/docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
