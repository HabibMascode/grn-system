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

# Aktifkan Apache mod_rewrite untuk URL routing CodeIgniter
RUN a2enmod rewrite

# Arahkan DocumentRoot Apache ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
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
RUN echo "upload_max_filesize = 20M\npost_max_size = 25M\nmemory_limit = 256M\n" > /usr/local/etc/php/conf.d/uploads.ini

# Port default
EXPOSE 80

# Railway menyuntikkan variabel PORT secara dinamis.
# Baris ini menyesuaikan port Apache agar sesuai dengan port yang ditentukan oleh Railway.
CMD sed -i "s/80/${PORT:-80}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf && apache2-foreground

