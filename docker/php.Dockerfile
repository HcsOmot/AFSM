FROM chrisb9/php8-nginx-xdebug
WORKDIR /opt/app

COPY composer.json ./
COPY . ./
RUN composer install
