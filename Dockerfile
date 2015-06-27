FROM ubuntu:latest
MAINTAINER Tim Rodger

# Expose the port
EXPOSE 80

# Start the server
CMD ["/home/app/build/run.sh"]

# Install dependencies
RUN apt-get update -qq && apt-get -y install \
    wget \
    curl \
    nginx \
    php-apc \
    php5-cli \
    php5-common \
    php5-fpm \
    sqlite \
    php5-sqlite

# Setup nginx
COPY build/default /etc/nginx/sites-available/default
RUN echo "cgi.fix_pathinfo = 0;" >> /etc/php5/fpm/php.ini && \
    echo "daemon off;" >> /etc/nginx/nginx.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log


# Install composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Move files into place
COPY src/ /home/app/src
COPY build/ /home/app/build

# Install dependencies
WORKDIR /home/app/src
RUN composer install --prefer-dist

WORKDIR /home/app/build

RUN /home/app/build/db-init.sh

RUN chmod -R -w /home/app/build /home/app/src

RUN chown -R www-data /home/app/data

