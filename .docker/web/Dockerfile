FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# install all the dependencies and enable PHP modules
# additional "docker-php-ext-install" can be found here 
# https://gist.github.com/giansalex/2776a4206666d940d014792ab4700d80

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    cron \
    wget

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Xdebug (optional)
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
;fi

RUN curl -O "https://getcomposer.org/download/2.1.6/composer.phar" && chmod a+x composer.phar && mv composer.phar /usr/bin/composer

# Node 12 LTS + yarn classic
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && apt-get install -y nodejs \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y yarn


# RUN wget https://github.com/dunglas/mercure/releases/download/v0.10.2/mercure_0.10.2_Linux_x86_64.tar.gz \
#     && mkdir mercure \
#     && tar -zxvf mercure_0.10.2_Linux_x86_64.tar.gz -C mercure \
#     && mv mercure /mercure
    
RUN pwd && ls -la && ls -la /

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create the log file to be able to run tail
# RUN echo "* * * * * root /usr/local/bin/php /var/www/artisan schedule:run >> /var/log/cron.log 2>&1" >> /etc/crontab
# RUN touch /var/log/cron.log

# Set working directory
WORKDIR /var/www
USER root

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/conf.d/supervisord.conf"]
