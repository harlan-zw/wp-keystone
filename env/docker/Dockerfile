# We go via ubuntu so our lives become easier
FROM ubuntu:16.04
MAINTAINER "Harlan Wilton" <harlan.wilton@4mation.com.au>

# Update system files and install required packages
RUN set -ex; \
	\
	apt-get update; \
	apt-get install -y \
	        apache2 \
	        software-properties-common python-software-properties \
       	    curl \
       	    git \
       	    zip \
       	    vim \
       	    sudo \
       	    wget \
       	    nmap \
       	    imagemagick \
       	    mysql-client \
       	    # required for add-apt-repo
       	    language-pack-en-base \
       	    default-jre \
    ; \
    #  add php 7.1 repo
    LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php; \
    apt-get update; \

    apt-get install -y \
         # php main extensions
         php7.1 php7.1-mysql php7.1-json php7.1-soap php7.1-curl php7.1-intl php7.1-mcrypt php7.1-gd php7.1-zip php7.1-xml php7.1-mbstring \
         # xdebug
         php-xdebug \
         # php extra dependencies
         php-pear php-redis \
         # apache php
         libapache2-mod-php7.1 \
	; \
	rm -rf /var/lib/apt/lists/*

# Configure Apache & PHP

# Install redis php extension
COPY conf/redis.ini /usr/local/etc/php/conf.d/redis.ini

# Copy php.ini config
COPY conf/php.ini /etc/php/7.1/apache2/
# Copy vhosts
COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod php7.1 rewrite expires ssl headers proxy proxy_http proxy_balancer

# Yarn - latest

RUN curl -sS http://dl.yarnpkg.com/debian/pubkey.gpg |  apt-key add -; echo "deb http://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y yarn bzip2

# WP-CLI
RUN wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar; chmod +x wp-cli.phar; mv wp-cli.phar /usr/local/bin/wp

# Composer
RUN curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer;

# AWS - CLI
RUN curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py; \
    python get-pip.py; \
    pip install awscli; \
    rm get-pip.py

RUN curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -; \
    sudo apt-get install -y nodejs

RUN echo "cd /home/wp/code" >> /root/.bashrc

# We need to include a new user for our builds
RUN useradd -ms /bin/bash wp
RUN usermod -a -G www-data wp

ENV NVM_DIR /home/wp/.nvm
ENV NODE_VERSION 6.0.0

# Install nvm with node and npm
RUN curl https://raw.githubusercontent.com/creationix/nvm/v0.33.2/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH      $NVM_DIR/v$NODE_VERSION/bin:$PATH

ADD conf/.bashrc /home/wp/.nvmbashrc
RUN cat /home/wp/.nvmbashrc >> /home/wp/.bashrc

RUN mkdir /home/wp/code; \
    mkdir /home/wp/code/web; \
    mkdir /home/wp/code/logs; \
    mkdir /home/wp/.ssh

# Setup our web root
RUN chown -R www-data:www-data /home/wp/code/

# Add Certs
ADD certs/ssl.cert /etc/httpd/ssl/ssl.cert
ADD certs/ssl.key /etc/httpd/ssl/ssl.key

# This makes sure everyhting we did is within this folder
VOLUME /home/wp/code

# Check services are working
RUN service apache2 restart

# Expore our ports
EXPOSE 80 443
WORKDIR /home/wp/code

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
