FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libxml2-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxslt-dev \
    libzip-dev \
    ghostscript \
    locales \
    zip \
    unzip \
    git \
    curl \
    nano

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath xmlrpc xsl sockets
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Install Nodejs & Npm
RUN curl -sL https://deb.nodesource.com/setup_13.x  | bash -
RUN apt-get -y install nodejs
RUN npm install

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Imagick
RUN apt-get update && apt-get install -y \
    libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Install Xdebugger
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Set Locale
RUN apt-get update && \
    apt-get install locales && \
    sed -i 's/# it_IT.UTF-8 UTF-8/nl_NL UTF-8/' /etc/locale.gen && \
    sed -i 's/# en_GB.UTF-8 UTF-8/en_GB UTF-8/' /etc/locale.gen && \
    locale-gen

RUN apt-get update \
    && apt-get install -y wget gnupg ca-certificates \
    && wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \
    && apt-get update \
    # We install Chrome to get all the OS level dependencies, but Chrome itself
    # is not actually used as it's packaged in the node puppeteer library.
    # Alternatively, we could could include the entire dep list ourselves
    # (https://github.com/puppeteer/puppeteer/blob/master/docs/troubleshooting.md#chrome-headless-doesnt-launch-on-unix)
    # but that seems too easy to get out of date.
    && apt-get install -y google-chrome-stable \
    && rm -rf /var/lib/apt/lists/* \
    && wget --quiet https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh -O /usr/sbin/wait-for-it.sh \
    && chmod +x /usr/sbin/wait-for-it.sh

# Install Puppeteer under /node_modules so it's available system-wide
ADD package.json package-lock.json /
RUN npm install

# Permissions: Add user to fix permissions for development & nginx
RUN groupadd -g 1000 nginx
RUN useradd -u 1000 -ms /bin/bash -g nginx nginx
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

#RUN apt-get install -y ssh rsync

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
