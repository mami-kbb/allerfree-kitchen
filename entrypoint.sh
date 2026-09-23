set -e

php artisan config:clear
php artisan migrate --force

if [ ! -L public/storage ]; then
    php artisan storage:link
fi

PORT="${PORT:-8080}"
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/g" /etc/apache2/sites-available/000-default.conf
exec apache2-foreground