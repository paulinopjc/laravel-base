#!/bin/bash

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php -S 0.0.0.0:$PORT -t public
