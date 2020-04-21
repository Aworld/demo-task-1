FROM php:7.4-apache

# MAIN FILES
ADD ./ /var/www/html

RUN apt-get update && apt-get install -y libzip-dev unzip
RUN docker-php-ext-install zip

# COPY FILES
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/start.sh /start.sh

# APACHE
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite headers

WORKDIR /var/www/html
RUN mkdir -p /var/www/html/var/log
RUN mkdir /var/www/html/testing

# COMPOSER
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& mkdir -p /usr/local/bin && php composer-setup.php  --install-dir=/usr/local/bin --filename=composer \
&& php -r "unlink('composer-setup.php');"

CMD /start.sh
