FROM php:8.5-fpm

ARG user=junior
ARG uid=1000

WORKDIR /var/www/html

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user && chown -R $user:$uid /home/$user && chown -R $user:$uid /var/www/html//
COPY --chown=$user:$uid . /var/www/html/

RUN apt-get update
RUN apt-get install -y sudo \
    zip \
    curl \
    wget \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ \
    nano

RUN echo "$user ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN composer global require laravel/installer
RUN docker-php-ext-install pdo pdo_mysql exif
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

RUN echo "upload_max_filesize = 100M\npost_max_size = 100M\nmemory_limit = 256M" > /usr/local/etc/php/conf.d/uploads.ini

USER $user
EXPOSE 9000
CMD ["php-fpm"]