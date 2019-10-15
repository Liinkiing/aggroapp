release: php bin/console doctrine:migrations:migrate --no-interaction
web: vendor/bin/heroku-php-nginx -C heroku/nginx.conf public/
worker: php bin/console messenger:consume async -vv --time-limit=3600
