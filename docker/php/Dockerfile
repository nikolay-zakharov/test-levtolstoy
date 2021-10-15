FROM php:7.4.13-fpm

#RUN   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
#RUN   php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
#RUN   php composer-setup.php --install-dir=/usr/local/bin --filename=composer
#RUN   chown 1000:1000 /usr/local/bin/composer
#RUN   php -r "unlink('composer-setup.php');"

RUN apt-get update && apt-get install -y apt-utils && apt-get install -y git curl

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions pdo_mysql bcmath intl mcrypt

RUN groupadd dev -g 1000
RUN useradd dev -g dev -d /home/dev -m

# install Symfony CLI
#RUN apt-get update && apt-get install -y wget
#RUN wget https://get.symfony.com/cli/installer -O - | bash
#RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR "/var/www/levtolstoy"
