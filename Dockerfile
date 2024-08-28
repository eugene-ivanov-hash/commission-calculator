###### PHP Base ######
FROM php:8.3.10-fpm-alpine3.20 as php-base

RUN apk update \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        linux-headers \
    && apk add --no-cache \
        icu-dev \
        zlib \
        zlib-dev \
        libzip-dev \
        libssh2-dev \
    && docker-php-ext-install opcache \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && rm -rf /tmp/* /var/cache/apk/* \
    && apk del .build-deps

COPY docker/php/php.ini /usr/local/etc/php/php.ini

WORKDIR /srv/app

ARG XDEBUG_VERSION=3.3.1

CMD ["tail", "-f", "/dev/null"]

###### PHP Dev ######
FROM php-base as php-dev

COPY docker/php/php.dev.ini /usr/local/etc/php/conf.d/php.dev.ini

RUN apk update \
    && apk add --update linux-headers \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
    && apk add --no-cache \
      bash \
      git \
      openssh \
      zsh \
    && pecl update-channels \
    && pecl install xdebug-${XDEBUG_VERSION} \
    && pecl clear-cache \
    && rm -rf /tmp/* /var/cache/apk/* \
    && apk del .build-deps

ARG XDEBUG_ENABLE

RUN if [[ "${XDEBUG_ENABLE}" == '1' ]]; then \
        docker-php-ext-enable xdebug; \
        echo 'XDebug enabled.'; \
    else \
        echo 'XDebug disabled.'; \
    fi

COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

ARG LINUX_USER_ID

RUN addgroup --gid $LINUX_USER_ID docker \
    && adduser --uid $LINUX_USER_ID --ingroup docker --home /home/docker --shell /bin/zsh --disabled-password --gecos "" docker

RUN chown docker /opt #PHP Storm Test Coverage

USER docker

RUN wget https://github.com/robbyrussell/oh-my-zsh/raw/65a1e4edbe678cdac37ad96ca4bc4f6d77e27adf/tools/install.sh -O - | zsh
RUN echo 'export ZSH=/home/docker/.oh-my-zsh' > ~/.zshrc \
    && echo 'ZSH_THEME="simple"' >> ~/.zshrc \
    && echo 'source $ZSH/oh-my-zsh.sh' >> ~/.zshrc \
    && echo 'PROMPT="%{$fg_bold[yellow]%}php:%{$fg_bold[blue]%}%(!.%1~.%~)%{$reset_color%} "' > ~/.oh-my-zsh/themes/simple.zsh-theme

###### PHP Build ######
FROM php-base as php-build
WORKDIR /build
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer
COPY composer.lock composer.json /build/

RUN composer install \
    --prefer-dist \
    --no-dev \
    --no-progress \
    --no-interaction \
    --no-scripts \
    --no-autoloader
COPY .. .
RUN composer dump-autoload --optimize --no-dev
ENV APP_ENV=prod
RUN bin/console cache:warmup
RUN APP_DEBUG=1 bin/console cache:warmup

###### PHP Prod ######
FROM php-base as php-prod
COPY --from=php-build /build/bin bin
COPY --from=php-build /build/vendor vendor
COPY --from=php-build /build/composer.json .
COPY --from=php-build /build/.env.dist .env
COPY --from=php-build /build/config config
COPY --from=php-build /build/var var
COPY --from=php-build /build/src src
RUN chmod -R 0777 var/cache var/log
USER www-data
ENV APP_ENV=prod

ENTRYPOINT ["php", "bin/console"]
CMD ["app:calculate:commissions", "transactions.txt"]
