FROM php:7.1.0-apache
MAINTAINER "Harlan Wilton" <harlan.wilton@4mation.com.au>

# Update system files and install required packages
RUN set -ex; \
	\
	apt-get update; \
	apt-get install -y \
	    git \
	    zip \
	    vim \
	    sudo \
	    wget \
	    nmap \
	    redis-server \
		libjpeg-dev \
		libpng12-dev \
	; \
	rm -rf /var/lib/apt/lists/*; \
	\
	docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
	docker-php-ext-install gd mysqli opcache

# Install redis php extension
RUN git clone https://github.com/phpredis/phpredis -b php7; mv phpredis/ /etc/; cd /etc/phpredis; phpize; ./configure; make && make install;
COPY conf/redis.ini /usr/local/etc/php/conf.d/redis.ini

# WP-CLI
RUN wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar
RUN sudo mv wp-cli.phar /usr/local/bin/wp

# turn on apache modules
RUN a2enmod rewrite expires ssl

# Configure Apache & PHP
COPY conf/php.ini /usr/local/etc/php/
COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN echo "cd /home/wp" >> /root/.bashrc

# setup our folder
RUN mkdir /home/wp/
RUN mkdir /home/wp/logs/

# Setup our web root
COPY conf/.htaccess /home/wp/web/
RUN chown -R www-data:www-data /home/wp/

# Add Certs
ADD certs/local.boilerplate.com.cert /etc/httpd/ssl/local.boilerplate.com.cert
ADD certs/local.boilerplate.com.key /etc/httpd/ssl/local.boilerplate.com.key

# This makes sure everyhting we did is within this folder
VOLUME /home/wp/

# Check services are working
RUN service redis-server restart
RUN service apache2 restart

# Expore our ports
EXPOSE 80 443 9000 6379

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
