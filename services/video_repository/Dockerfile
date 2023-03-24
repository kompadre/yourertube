FROM php:8.2-fpm

ARG USER_ID
ARG GROUP_ID

RUN userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data
#    install -d -m 0755 -o www-data -g www-data /home/www-data &&\
#    chown --changes --silent --no-dereference --recursive \
#          --from=33:33 ${USER_ID}:${GROUP_ID} \
#        /media

RUN apt-get update && \
    apt-get install --no-install-recommends -y librabbitmq-dev && \
    apt-get clean

RUN pecl install amqp && docker-php-ext-enable amqp


WORKDIR /app

USER www-data

CMD ["php-fpm"]