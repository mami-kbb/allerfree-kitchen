init:
	docker run --rm \
		-u "$$(id -u):$$(id -g)" \
		-v "$$(pwd):/var/www/html" \
		-w /var/www/html \
		laravelsail/php84-composer:latest \
		composer install --ignore-platform-reqs
	bash vendor/bin/sail up -d
	@echo "MySQLの起動を待っています..."
	@until bash vendor/bin/sail exec -T mysql mysqladmin ping -h 127.0.0.1 --silent; do \
		sleep 2; \
	done
	@echo "MySQLの準備ができました!"
	bash vendor/bin/sail artisan key:generate
	bash vendor/bin/sail artisan storage:link
	bash vendor/bin/sail artisan migrate --seed
	bash vendor/bin/sail npm install
	@make fresh

fresh:
	bash vendor/bin/sail npm run build