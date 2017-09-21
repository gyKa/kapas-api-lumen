install:
	composer install
	cp .env.example .env

server:
	php -S localhost:8000 -t public

migration:
	php artisan migrate
