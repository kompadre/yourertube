FROM php:8.2-cli

RUN apt-get update && \
    apt-get install --no-install-recommends -y ffmpeg librabbitmq-dev && \
    apt-get clean

RUN pecl install amqp && docker-php-ext-enable amqp

WORKDIR /app
CMD ["php", "/app/consumer.php"]