run: composer_install run_app

test:
	docker-compose run --rm app ./vendor/bin/phpunit
composer_install:
	docker-compose run --rm app composer install
run_app:
	docker-compose run --rm app php index.php

ssh_container:
	docker-compose run --rm app /bin/bash

prune:
	docker system prune -a
	docker volume prune
down:
	docker-compose down