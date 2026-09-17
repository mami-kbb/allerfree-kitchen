set -e

php artisan config:clear
php artisan migrate --force

if [ ! -L public/storage ]; then
    php artisan storage:link
fi

exec apache2-foreground