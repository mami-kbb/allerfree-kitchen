init:
	sail up -d
	sail artisan key:generate
	sail artisan storage:link
	sail artisan migrate --seed
	sail npm install
	@make fresh