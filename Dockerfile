FROM php:8.2-apache

# Install dependencies & PHP extensions yang dibutuhkan CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install \
    intl \
    mysqli \
    pdo_mysql \
    zip \
    opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite dan pastikan hanya mpm_prefork yang aktif
RUN for module in /etc/apache2/mods-enabled/mpm_*.load; do \
        [ -e "$module" ] || continue; \
        a2dismod "$(basename "$module" .load)"; \
    done \
    && a2enmod mpm_prefork rewrite \
    && test "$(apache2ctl -M 2>/dev/null | grep -Ec 'mpm_[[:alnum:]_]+_module')" -eq 1 \
    && apache2ctl -M 2>/dev/null | grep -q 'mpm_prefork_module'

# Arahkan DocumentRoot Apache ke folder /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Izinkan penggunaan .htaccess (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Salin seluruh kode proyek ke dalam container
COPY . /var/www/html

# Pastikan direktori writable dan uploads tersedia dengan permission www-data
RUN mkdir -p /var/www/html/writable \
             /var/www/html/public/uploads/surat \
             /var/www/html/public/uploads/grn \
             /var/www/html/public/uploads/berita_acara \
    && chown -R www-data:www-data /var/www/html/writable /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/writable /var/www/html/public/uploads

# Pengaturan batas ukuran upload file PHP
RUN printf "upload_max_filesize = 20M\npost_max_size = 25M\nmemory_limit = 256M\n" > /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 8080

CMD ["/bin/sh", "-c", "set -eu; for module in /etc/apache2/mods-enabled/mpm_*.load; do [ -e \"$module\" ] || continue; a2dismod \"$(basename \"$module\" .load)\"; done; a2enmod mpm_prefork rewrite; test \"$(apache2ctl -M 2>/dev/null | grep -Ec 'mpm_[[:alnum:]_]+_module')\" -eq 1; apache2ctl -M 2>/dev/null | grep -q 'mpm_prefork_module'; TARGET_PORT=${PORT:-8080}; printf 'Listen %s\n' \"$TARGET_PORT\" > /etc/apache2/ports.conf; sed -ri \"s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:$TARGET_PORT>/g\" /etc/apache2/sites-available/*.conf; exec apache2-foreground"]