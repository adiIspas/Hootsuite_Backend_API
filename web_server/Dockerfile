FROM ubuntu:14.04

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y \
    git \
    curl \
    php5-cli \
    php5-json \
    php5-intl

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

ADD entrypoint.sh /entrypoint.sh
ADD ./code /var/www

WORKDIR /var/www

#RUN chmod +x /entrypoint.sh
ENTRYPOINT [ "/bin/bash", "/entrypoint.sh" ]
