FROM ubuntu:20.04

ARG DEBIAN_FRONTEND=noninteractive
ARG php_version="8.1"
ARG src_php="src/php"
ARG src_nginx="src/nginx"
ARG dst_nginx="/etc/nginx"
ARG dst_spv="/etc/supervisor"
RUN printf '#!/bin/sh\nexit 0' > /usr/sbin/policy-rc.d

# Install package
RUN mkdir -p /root/.ssh
RUN apt update -y && apt --no-install-recommends -y install mysql-client curl sudo software-properties-common wget git redis-tools nano screen && \
    add-apt-repository -y ppa:ondrej/php && add-apt-repository -y ppa:nginx/stable && apt-get install -y --no-install-recommends nginx && \
    apt-get -y install supervisor tzdata zip unzip php8.1 php8.1-fpm php8.1-cli php8.1-mysql php8.1-mbstring php8.1-exif php8.1-bcmath php8.1-gd php8.1-curl php8.1-dom libpng-dev libonig-dev libxml2-dev ca-certificates apt-transport-https

#Change timezone
ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN rm /etc/nginx/sites-available/default; \
    rm /etc/nginx/sites-enabled/default;

# Supervisor config
COPY supervisord.conf ${dst_spv}/supervisord.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy source code
RUN useradd -ms /bin/bash app
RUN mkdir -p /home/app/.ssh
COPY --chown=app:app .servcomp/ssh_config /home/app/.ssh/config
COPY --chown=app:app .servcomp/git_rsa /home/app/.ssh/git_rsa

# change to home apps

WORKDIR /home/app/
COPY --chown=app:app . site

USER app
WORKDIR /home/app/site
RUN composer install

USER root
EXPOSE 80 9000

# Cleanup build and cache
RUN apt -y autoremove; \
    apt -y autoclean; \
    rm -rf /var/lib/apt/lists/* /tmp/*; \
    echo "session required pam_limits.so" >> /etc/pam.d/common-session;

# Set ownership and permision
RUN chown app:app -Rf /home/app; \
    chmod 775 /home/app; \
    chown app:app -R /var/lib/php; \
    chmod 755 -R /var/lib/php;

COPY start.sh /
RUN chmod +x /start.sh && chown root:root /start.sh
ENTRYPOINT /start.sh