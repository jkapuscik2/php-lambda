FROM amazon/aws-lambda-provided:al2

RUN yum -y update; \
    yum -y install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm; \
    yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm; \
    yum -y install yum-utils; \
    yum -y install git; \
    yum-config-manager --disable 'remi-php*'; \
    yum-config-manager --enable remi-php80; \
    yum -y install php php-mbstring php-xml php-fpm;

RUN yum install nano -y
RUN yum install procps -y

RUN yum -y install nginx
RUN touch /var/log/nginx/error.log

COPY conf/nginx.conf /etc/nginx/
COPY conf/php-fpm.conf /etc/

WORKDIR /var/runtime/
COPY bootstrap/ .
RUN chmod -R 755 bootstrap
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

WORKDIR /var/task
COPY app/ .
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

CMD [ "index.php"]
