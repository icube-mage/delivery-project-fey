#!/bin/bash
# Entrypoint for docker

# Logging and supervisord
ln -sf /dev/stdout /var/log/nginx/access.log
ln -sf /dev/stderr /var/log/nginx/error.log
ln -sf /dev/stdout /var/log/php-fpm.log
supervisord -c /etc/supervisor/supervisord.conf

#start service
service nginx start
service php8.1-fpm start
supervisorctl reread
supervisorctl update
php /home/app/site/artisan queue:restart
php /home/app/site/artisan config:clear
