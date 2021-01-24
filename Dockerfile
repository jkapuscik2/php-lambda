FROM amazon/aws-lambda-provided:al2

RUN yum -y update; \
    yum -y install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm; \
    yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm; \
    yum -y install yum-utils; \
    yum -y install git; \
    yum-config-manager --disable 'remi-php*'; \
    yum-config-manager --enable remi-php80; \
    yum -y install php php-mbstring php-xml php-fpm \
    yum -y install nginx \
    yum -y autoremove;

COPY conf/nginx.conf /etc/nginx/
RUN mkdir /tmp/nginx
COPY conf/php-fpm.conf /etc/

WORKDIR /var/runtime/
COPY runtime/ .
RUN chmod -R 755 bootstrap
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

COPY bootstrap/ .
RUN chmod -R 755 start.sh

WORKDIR /var/task
COPY api/ .
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

ENTRYPOINT ["/var/runtime/start.sh"]
CMD [ "index.php"]
